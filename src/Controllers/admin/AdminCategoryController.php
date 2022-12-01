<?php
//AdminCategoryController
declare (strict_types=1);

namespace App\Controllers\Admin;

use Aleyg\Core\Security\Auth;
use App\Controllers\AbstractController;
use App\Model\Category;
use App\Repository\UserRepository;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Config\RoutesPath;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;



class AdminCategoryController extends AbstractController
{    

    /**
     * newCategoryController
     *
     * @param  mixed $request httpFoundationRequest
     * @return twig render
     * get infos in $request by POST method then send request in repository if not send creation form
     */
    public function newCategoryController($request)
    {
        $auth = new Auth();
        $session = new session();
        
        if($this->checkingAuth())
        {
            if($request->getMethod() ==='POST'&& $auth->tokenChecking($session->get('token'),$request->request->get('usertoken')))
            {
                
                $newcategory = new Category();
                $newcategory->setName($request->request->get('name'));
                $newcategory->setSlug($request->request->get('slug'));   

                $rc = new CategoryRepository();        
                $rc->addCategory($newcategory);                

                return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin_categories'));
            }
            else
            {
                return new Response($this->render('admin/category/categoryForm.html.twig'));
            }
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index')); 
        
        
    }   

    /**
     * getAllCategoryController
     *
     * @return twig render
     * Send a request to get All infos in the table
     */
    public function getAllCategoryController()
    {
        if($this->checkingAuth())
        {
            try
            {
                $rc = new CategoryRepository();
                $data = $rc->getAllCategory();             
            }
            catch (PDOException $exception ) {
                $this->getContainer()->get('log')->error($exception);
            }       
            
            $content = $this->render('admin/category/categoryList.html.twig', ['categories' => $data]);

            return new Response($content);
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
    }


    /**
     * updateCategoryController
     *
     * @param  mixed $request httpFoundationRequest
     * @return twig render
     * get infos in $request by POST method then send request in repository if not send prefilled update form
     */
    public function updateCategoryController($request)
    {
        $auth = new Auth();
        $session = new session();
        
        if($this->checkingAuth())
        {
            if($request->getMethod() ==='POST'&& $auth->tokenChecking($session->get('token'),$request->request->get('usertoken')))
            {           
                //create category object then send by request to repository
                $newcategory = new Category();              
                $newcategory->setid($request->request->get('id'));
                $newcategory->setName($request->request->get('name'));     
                $newcategory->setSlug($request->request->get('slug'));
                try
                {
                    $rc = new CategoryRepository();
                    $rc->updateCategory($newcategory);
                    $data = $rc->getAllCategory();
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }

                return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin_categories'));
            }
            else
            {
                //create an array =>params for execute then return category infos         
                $slug = array(0 => $request->get('slug_category'));          
                
                try
                {
                    $rc = new CategoryRepository();
                    $data = $rc->getOneCategory($slug);                                            
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }       
                
                return new Response($this->render('admin/category/updateCategory.html.twig', ['category' => $data[0]]));
            }
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
        
    }

    /**
     * deleteCategoryController
     *
     * @param  mixed $request
     * @return twig render
     * get id from request then send delete request to repository then return the list
     */
    public function deleteCategoryController($request)
    { 
        $auth = new Auth();
        $session = new session();

        if($this->checkingAuth())
        {
            if($auth->tokenChecking($session->get('token'),$session->get('usertoken')))
            {           
                //create an array =>params for execute      
                $id = array(0 => $request->attributes->get('id'));          
                
                try
                {
                    $rc = new CategoryRepository();
                    $rc->deleteCategory($id);                                                
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }
            }      
            
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin_categories'));
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
    }

    
}