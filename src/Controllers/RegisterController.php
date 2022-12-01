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
    public function signup($request)
    {
        $auth = new Auth();

        if($request->getMethod() ==='POST')
        {

            if($request->request->get('password') == $request->request->get('confirm_password'))
            {
                $newuser = new User();
                $newuser->setName($request->request->get('name'));
                $newuser->setSurname($request->request->get('surname'));
                $newuser->setNickname($request->request->get('nickname'));
                $newuser->setEmail($request->request->get('email'));            
                $hash = $auth->passwordhashing($request->request->get('password'));   
                $newuser->setPassword($hash);            
                $newuser->setRole(ROLE_DEFAULT);                
                $ru = new UserRepository();

                
                if($ru->ExistingUser($newuser))
                {
                    return new Response($this->render('signup.html.twig',['error'=>'E-mail/Pseudo utilisé']));
                }
                else
                {
                    $ru->addUser($newuser);
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

    public function login($request)
    {
        $auth = new Auth();
        $session = new Session();
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
        else
        {        
            if($request->getMethod() ==='POST')
            {            
                $ru = new UserRepository();
                $user = new User();
                $session = new Session();

                if($session->get('auth') == NULL)
                {
                    $session->start();
                }                
                try 
                {
                    $email = array(0 => $request->request->get('email'));
                    $user =  $ru->findUser($email);                                    
                    $user = $user[0]; //get an array from fetch::class in Database.php so convert user array to an user object
                    
                    if($user != NULL)
                    {
                        if(password_verify($request->request->get('password'), $user->getPassword()))
                        {
                            $token = $auth->tokenCreate();
                            $session->set('id', $user->getId());
                            $session->set('nickname', $user->getNickname());
                            $session->set('email', $user->getEmail());
                            $session->set('role', $user->getRole());
                            $session->set('auth', true);
                            $session->set('usertoken',$token );
                            $session->set('token', $token);

                            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
                        }
                        else
                        {
                            return new Response($this->render('login.html.twig',['error'=>'Mauvais Login ou mot de passe']));
                        }
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

    public function leaveSession()
    {
        $session = new session();
        $session->clear();

        return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
    }

    public function loginAdmin($request)
    {  
        $auth = new Auth();
        $session = new Session();
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
            if($request->getMethod() ==='POST')
            {            
                $ru = new UserRepository();
                $user = new User();            
                
                if($session->get('auth') == NULL)
                {
                    $session->start();
                }

                try 
                {
                    $email = array(0 => $request->request->get('email'));
                    $user =  $ru->findUser($email);
                    $user = $user[0]; //get an array from fetch::class so convert user array to an user object                               
                    if($user != NULL)
                    {
                        if(password_verify($request->request->get('password'), $user->getPassword()))
                        {                            
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