<?php

//RegisterController
declare (strict_types=1);

namespace App\Controllers;

use Aleyg\Core\Database\Database;
use Aleyg\Core\Security\Auth;
use App\Model\User;
use App\Repository\UserRepository;
use Config\RoutesPath;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Generator\UrlGenerator;

class RegisterController extends AbstractController
{
        
    /**
     * signup function
     *
     * @param  Request $request
     * @return Response|RedirectResponse
     */
    public function signup($request):Response|RedirectResponse
    {
        $auth = new Auth();

        //checking if method is POST then handle signup's form OR Send it
        if($request->getMethod() ==='POST')
        {
            //Checking if both password are the same
            if($request->request->get('password') == $request->request->get('confirm_password'))
            {
                $newuser = new User();
                $newuser->setName($request->request->get('name'))
                        ->setSurname($request->request->get('surname'))
                        ->setNickname($request->request->get('nickname'))
                        ->setEmail($request->request->get('email')) 
                        ->setRole(ROLE_DEFAULT);           
                $hash = $auth->passwordhashing($request->request->get('password'));   
                $newuser->setPassword($hash);            
                                
                $userRepo = new UserRepository();

                //Checking is User already exist or Return an error
                if($userRepo->ExistingUser($newuser))
                {
                    return new Response($this->render('signup.html.twig',['error'=>'E-mail/Pseudo utilisé']));
                }
                else
                {
                    $userRepo->addUser($newuser);
                    return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('login'));
                }
            }
            else
            {
                return new Response($this->render('signup.html.twig',['error'=>'Mot de passe non identique']));   
            }               
                
                               
        }
        else
        {
            return new Response($this->render('signup.html.twig'));
        }
            
       
        
    }
    
    /**
     * login function
     *
     * @param  Request $request
     * @return Response|RedirectResponse
     */
    public function login($request):Response|RedirectResponse
    {
        $auth = new Auth();
        $session = new Session();
        
        //redirect to index or indicate Token Error if user is already log then ask login page
        if($session->get('role') != NULL &&  $session->get('role') == ROLE_DEFAULT)
        {
            if($auth.tokenChecking($session->get('usertoken'),$session->get('token')))
            {

                return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
            }
            else
            {
                return new Response($this->render('login.html.twig',['error'=>'Vous ne possèdez pas le bon token']));
            }
            
        }
        else //Handle Login's form OR Send it
        {        
            if($request->getMethod() ==='POST')
            {            
                $userRepo = new UserRepository();
                $user = new User();
                $session = new Session();

                //start session if not
                if($session->get('auth') == NULL)
                {
                    $session->start();
                }                
                try 
                {
                    $email = array(0 => $request->request->get('email'));
                    $user =  $userRepo->findUser($email);                                    
                    $user = $user[0]; //get an array from fetch::class in Database.php so convert user array to an user object
                    
                    //Fields checking Email and password then return Error if needed OR handle Session with User's info 
                    if($user != NULL)
                    {
                        if(password_verify($request->request->get('password'), $user->getPassword()))
                        {
                            $token = $auth->tokenCreate();
                            $session->set('id', $user->getId());
                            $session->set('email', $user->getEmail());
                            $session->set('nickname', $user->getNickname());                                    
                            $session->set('role', $user->getRole());
                            $session->set('auth', true);
                            $session->set('usertoken',$token);
                            $session->set('token', $token);

                            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
                        }
                        else
                            return new Response($this->render('login.html.twig',['error'=>'Mauvais Login ou mot de passe']));
                        
                    }
                    else
                    {
                        return new Response($this->render('login.html.twig',['error' =>'Mauvais Login ou mot de passe']));
                    }              

                } 
                catch (NotFoundException $exception) 
                {
                    $this->getContainer()->get('log')->error($exception);              
                }            
            }
            else
            {
                return new Response($this->render('login.html.twig'));
            }      
        }
    }
    
    /**
     * leaveSession
     *
     * @return RedirectResponse
     */
    public function leaveSession():RedirectResponse
    {
        $session = new session();
        $session->clear();

        return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
    }
    
    /**
     * loginAdmin
     *
     * @param  Request $request
     * @return Response|RedirectResponse
     */
    public function loginAdmin($request):Response|RedirectResponse
    {  
        $auth = new Auth();
        $session = new Session();

        //Control Admin permission if user ask loginAdmin page
        if($session->get('role') != NULL &&  $session->get('role') == ROLE_SUPER_USER)
        {
            
            if($auth->tokenChecking($session->get('usertoken'),$session->get('token')))
            {
                return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin'));
            }
            else
            {
                return new Response($this->render('admin/template/loginadmin.html.twig',['error'=>'Vous ne possèdez pas le bon token']));
            }            
        }
        else
        {   
            //Checking Method is POST, handle loginAdmin's form OR Send it          
            if($request->getMethod() ==='POST')
            {            
                $userRepo = new UserRepository();
                $user = new User();            
                
                //Start session if not
                if($session->get('auth') == NULL)
                {
                    $session->start();
                }

                try 
                {
                    $email = array(0 => $request->request->get('email'));
                    $user =  $userRepo->findUser($email);
                    $user = $user[0]; //get an array from fetch::class so convert user array to an user object 
                    
                    //USER EXISTING, PASSWORD IDENTICAL THEN ROLE ADMIN OR RETURN ERRORS
                    //checking if user exist
                    if($user != NULL)
                    {
                        //checking password
                        if(password_verify($request->request->get('password'), $user->getPassword()))
                        {  
                            //last verification checking role then redirect to Admin Dashboard                          
                            if($user->getRole() === ROLE_SUPER_USER)
                            {
                                $token = $auth->tokenCreate();
                                $session->set('id', $user->getId());
                                $session->set('nickname', $user->getNickname());
                                $session->set('email', $user->getEmail());
                                $session->set('role', $user->getRole());
                                $session->set('auth', true);
                                $session->set('usertoken',$token );
                                $session->set('token', $token);                                
                                

                                return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin'));
                            }
                            else
                            {
                                return new Response($this->render('admin/template/loginadmin.html.twig',['error'=>'Vous ne possèdez pas la permission']));
                            }                       
                        }
                        else
                        {
                            return new Response($this->render('admin/template/loginadmin.html.twig',['error'=>'Identifiants ou Mot de passe incorrect']));
                        }
                    }
                    else
                    {
                        return new Response($this->render('admin/template/loginadmin.html.twig',['error' =>'Identifiants ou Mot de passe incorrect']));
                    }
                } 
                catch (NotFoundException $exception) 
                {
                    $this->getContainer()->get('log')->error($exception);             
                }           
            }
            else
            {
                return new Response($this->render('admin/template/loginadmin.html.twig'));
            }        
            
        }
    }
}