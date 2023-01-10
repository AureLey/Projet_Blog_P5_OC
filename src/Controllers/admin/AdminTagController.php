<?php
//AdminTagController
declare (strict_types=1);

namespace App\Controllers\Admin;

use Aleyg\Core\Security\Auth;
use App\Controllers\AbstractController;
use App\Model\Tag;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Config\RoutesPath;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;


class AdminTagController extends AbstractController
{

    /**
     * newTagController
     *
     * @param  mixed $request httpFoundationRequest
     * @return :Response|RedirectResponse
     * get infos in $request by POST method then send request in repository if not send creation form
     */
    public function newTagController($request):Response|RedirectResponse
    {
        $auth = new Auth();
        $session = new session();

        //checking role of user
        if($this->checkingAuth())
        {
            //Handle New Tag Form or Send it
            if($request->getMethod() ==='POST'&& $auth->tokenChecking($session->get('token'),$request->request->get('usertoken')))
            {               
                //object creation
                $newtag = new Tag();
                $newtag ->setName($request->request->get('name'))
                        ->setSlug($request->request->get('slug'));   

                $repoTag = new TagRepository();        
                $repoTag->addTag($newtag);            

                return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin_tags'));
            }
            else
                return new Response($this->render('admin/tag/tagForm.html.twig'));
            
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));        
        
    }

    /**
     * getAllTagController
     *
     * @return :Response|RedirectResponse
     * Send a request to get All infos in the table
     */
    public function getAllTagController():Response|RedirectResponse
    {
        //checking role of user
        if($this->checkingAuth())
        {
            try
            {
                $repoTag = new TagRepository();
                $data = $repoTag->getAllTag();                         
            }
            catch (PDOException $exception) {
                $this->getContainer()->get('log')->error($exception);
            }       
            
            $content = $this->render('admin/tag/tagList.html.twig', ['tags' => $data]);
            return new Response($content);
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
    }

    /**
     * updateTagController
     *
     * @param  mixed $request httpFoundationRequest
     * @return Response|RedirectResponse
     * get infos in $request by POST method then send request in repository if not send prefilled update form
     */
    public function updateTagController($request):Response|RedirectResponse
    {
        $auth = new Auth();
        $session = new session();
        //checking role of user
        if($this->checkingAuth())
        {
            //handle Update Tag Form or send it
            if($request->getMethod() ==='POST'&& $auth->tokenChecking($session->get('token'),$request->request->get('usertoken')))
            {
                //Tag modification by request then DB updated
                $newtag = new Tag();              
                $newtag ->setid($request->request->get('id'))
                        ->setName($request->request->get('name'))     
                        ->setSlug($request->request->get('slug'));
                try
                {
                    $repoTag = new TagRepository();
                    $repoTag->updateTag($newtag);
                    $data = $repoTag->getAllTag();
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }

                return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin_tags'));
            }
            else
            {   
                //repo function ask array as parameter     
                $slug = array(0 => $request->get('slug_tag'));          
                
                try
                {
                    $repoTag = new TagRepository();
                    $data = $repoTag->getOneTag($slug);                            
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }       
                
                return new Response($this->render('admin/tag/updateTag.html.twig', ['tag' => $data[0]]));
            }
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
        
    }

    /**
     * deleteTagController
     *
     * @param  mixed $request
     * @return RedirectResponse
     * get id from request then send delete request to repository then return the list
     */
    public function deleteTagController($request):RedirectResponse
    {   
        //repo function ask array as parameter        
        $idTag = array(0 => $request->get('id'));

        $auth = new Auth();
        $session = new session();

        //chekcing role of user
        if($this->checkingAuth())
        {
            //checking CSRF Token then delete tag by id
            if($auth->tokenChecking($session->get('token'),$session->get('usertoken')))
            {        
                try
                {
                    $repoTag = new TagRepository();
                    $repoTag->deleteTag($idTag);                                               
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }     
            }
            
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin_tags'));
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
    }
}