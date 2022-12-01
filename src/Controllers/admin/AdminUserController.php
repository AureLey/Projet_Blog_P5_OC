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
     * @return twig render
     * get infos in $request by POST method then send request in repository if not send creation form
     */
    public function newUserController($request)
    {
        $auth = new Auth();
        $session = new session();

        if($this->checkingAuth())
        {

            //Check with the request if the form was send or not
            if($request->getMethod() ==='POST'&& $auth->tokenChecking($session->get('token'),$request->request->get('usertoken')))
            {           
                
                //create user object
                $newuser = new User();
                $newuser->setName($request->get('name'));
                $newuser->setSurname($request->get('surname'));
                $newuser->setNickname($request->get('nickname'));   
                $newuser->setEmail($request->get('email'));
                $hash = $auth->passwordhashing($request->get('password'));   
                $newuser->setPassword($hash);
                $newuser->setRole($request->get('role'));              
                
                $ru = new UserRepository();        
                $ru->addUser($newuser);             

                return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin_users'));
            }
            else
            {
                return new Response($this->render('admin/user/userForm.html.twig'));
            }
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
        
    }
    
    /**
     * getAllUserController
     *
     * @return twig render
     * Send a request to get All infos in the table
     */
    public function getAllUserController()
    {        
        if($this->checkingAuth())
        {
            try
            {
                $rp = new UserRepository();
                $dataUsers = $rp->getAllUser();             
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
     * @return twig render
     * get infos in $request by POST method then send request in repository if not send prefilled update form
     */
    public function updateUserController($request)
    {           
        $auth = new Auth();
        $session = new session();

        if($this->checkingAuth())
        {

            if($request->getMethod() ==='POST'&& $auth->tokenChecking($session->get('token'),$request->request->get('usertoken')))
            {
                
                $newuser = new User();              
                $newuser->setId($request->get('id'));
                $newuser->setName($request->get('name'));
                $newuser->setSurname($request->get('surname'));
                $newuser->setNickname($request->get('nickname'));   
                $newuser->setEmail($request->get('email'));
                $newuser->setPassword($request->get('password'));
                $newuser->setRole($request->get('role'));

                try
                {
                    $ru = new UserRepository();
                    $ru->updateUser($newuser);
                    $data = $ru->getAllUser();
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }

                return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin_users'));
            }
            else
            {                                          
                $id = array(0 =>$request->get('id'));       
                
                try
                {
                    $ru = new UserRepository();
                    $data = $ru->getOneUser($id);                            
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
     * @return twig render
     * get id from request then send delete request to repository then return the list
     */
    public function deleteUserController($request)
    {
        $id = array(0 =>$request->get('id'));          
        $auth = new Auth();
        $session = new session();

        if($this->checkingAuth())
        {
            if($auth->tokenChecking($session->get('token'),$session->get('usertoken')))
            {
                try
                {
                    $ru = new UserRepository();
                    $ru->deleteUser($id);                                            
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
