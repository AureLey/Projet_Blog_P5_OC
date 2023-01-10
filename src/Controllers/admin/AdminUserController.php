<?php
//AdminUserController
declare (strict_types=1);

namespace App\Controllers\Admin;

use Aleyg\Core\Security\Auth;
use App\Controllers\AbstractController;
use App\Model\User;
use App\Repository\UserRepository;
use Config\RoutesPath;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;



class AdminUserController extends AbstractController
{
        
    /**
     * newUserController
     *
     * @param  mixed $request httpFoundationRequest
     * @return Response|RedirectResponse
     * get infos in $request by POST method then send request in repository if not send creation form
     */
    public function newUserController($request):Response|RedirectResponse
    {
        $auth = new Auth();
        $session = new session();

        //checking role of user
        if($this->checkingAuth())
        {

            //Check if method post is used and CSRF Token then handle the form and create User OR return User form
            if($request->getMethod() ==='POST'&& $auth->tokenChecking($session->get('token'),$request->request->get('usertoken')))
            {           
                
                //create user object
                $newuser = new User();
                $newuser->setName($request->get('name'))
                        ->setSurname($request->get('surname'))
                        ->setNickname($request->get('nickname'))   
                        ->setEmail($request->get('email'))
                        ->setRole($request->get('role'));  
                $hash = $auth->passwordhashing($request->get('password'));   
                $newuser->setPassword($hash);
                                    
                
                $userRepo = new UserRepository();        
                $userRepo->addUser($newuser);             

                return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin_users'));
            }
            else            
                return new Response($this->render('admin/user/userForm.html.twig'));
            
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
        
    }
    
    /**
     * getAllUserController
     *
     * @return Response|RedirectResponse
     * Send a request to get All infos in the table
     */
    public function getAllUserController():Response|RedirectResponse
    {  
        //checking role of User      
        if($this->checkingAuth())
        {
            try
            {
                $userRepo = new UserRepository();
                $dataUsers = $userRepo->getAllUser();             
            }
            catch (PDOException $exception) {
                $this->getContainer()->get('log')->error($exception);
            }
                    
            $content = $this->render('admin/user/userList.html.twig', ['users' => $dataUsers]);
            return new Response($content);
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
    }
    

    
    /**
     * updateUserController
     *
     * @param  mixed $request httpFoundationRequest
     * @return Response|RedirectResponse
     * get infos in $request by POST method then send request in repository if not send prefilled update form
     */
    public function updateUserController($request):Response|RedirectResponse
    {           
        $auth = new Auth();
        $session = new session();
        //checking Role of user
        if($this->checkingAuth())
        {
            //if Method is POST and CSRF token is ok, handle update's form and update the user in DB
            if($request->getMethod() ==='POST'&& $auth->tokenChecking($session->get('token'),$request->request->get('usertoken')))
            {
                //get User info from the request
                $newuser = new User();              
                $newuser->setId($request->get('id'))
                        ->setName($request->get('name'))
                        ->setSurname($request->get('surname'))
                        ->setNickname($request->get('nickname'))   
                        ->setEmail($request->get('email'))
                        ->setPassword($request->get('password'))
                        ->setRole($request->get('role'));

                try
                {
                    $userRepo = new UserRepository();
                    $userRepo->updateUser($newuser);
                    $data = $userRepo->getAllUser();
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }

                return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin_users'));
            }
            else
            {   
                //Repo function ask array as parameter                                       
                $idUser= array(0 =>$request->get('id'));       
                
                try
                {
                    $userRepo = new UserRepository();
                    $data = $userRepo->getOneUser($idUser);                            
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }       
                
                return new Response($this->render('admin/user/updateUser.html.twig', ['user' => $data[0]]));
            }
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
        
    }

    
    /**
     * deleteUserController
     *
     * @param  mixed $request
     * @return Response|RedirectResponse
     * get id from request then send delete request to repository then return the list
     */
    public function deleteUserController($request):Response|RedirectResponse
    {
        $idUser= array(0 =>$request->get('id'));          
        $auth = new Auth();
        $session = new session();

        //Checking Role of user
        if($this->checkingAuth())
        {
            //Checking Token CSRF then Delete User by Id
            if($auth->tokenChecking($session->get('token'),$session->get('usertoken')))
            {
                try
                {
                    $userRepo = new UserRepository();
                    $userRepo->deleteUser($idUser);                                            
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }     
                return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin_users'));
            }
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
    }
}
