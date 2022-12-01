<?php
//AdminPostController
declare (strict_types=1);

namespace App\Controllers\Admin;

use Aleyg\Core\Security\Auth;
use App\Controllers\AbstractController;
use App\Model\Post;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\PostCatRepository;
use App\Repository\TagPostRepository;
use App\Repository\TagRepository;
use Config\RoutesPath;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Generator\UrlGenerator;





class AdminPostController extends AbstractController
{

    /**
     * newPostController
     *
     * @param  mixed $request httpFoundationRequest
     * @return twig render
     * get infos in $request by POST method then send request in repository if not send creation form
     */
    public function newPostController($request)
    {
        $auth = new Auth();
        $session = new session();

        if($this->checkingAuth())
        {

            if($request->getMethod() ==='POST'&& $auth->tokenChecking($session->get('token'),$request->request->get('usertoken')))
            {   
                //-----Import Image-----//
                $path = __DIR__."../../../../public/assets/img";     
                $picture = $request->files->get('picture');           
                if($picture->isValid() === true)
                {
                    $picture->move($path,$picture->getClientOriginalName());
                }  

                //-----END Import Image-----//
                
                $date = new \DateTime();
                $date = $date->format('Y-m-d H:i:s');        
                $newpost = new Post();
                $newpost->setTitle($request->request->get('title'));
                $newpost->setChapo($request->request->get('chapo'));   
                $newpost->setAuthor($request->request->get('author'));   
                $newpost->setContent($request->request->get('content'));  
                $newpost->setPicture($picture->getClientOriginalName());
                $newpost->setSlug($request->request->get('slug'));
                $newpost->setCreated_at($date);
                $newpost->setUpdated_at($date);
                $newpost->setUser_id($session->get('id'));

                //create each repository to prepare insert request,
                //$idpost is id return by the function addPost and in this function PDO->lastInsertId
                $rp = new PostRepository();            
                $idpost = $rp->addPost($newpost);

                $rpc = new PostCatRepository();
                $rpc->addPostCat($request->request->all('categories'),$idpost);

                
                $rtp = new TagPostRepository();
                $rtp->addTagPost($request->request->all('tags'),$idpost);
                
                            
                return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin_posts'));   
            }
            else
            {
                //send 2 request to get all categories and tags to complete the creation form
                $rc = new CategoryRepository();
                $dataCategory = $rc->getAllCategory();
                $rt = new TagRepository();
                $dataTag = $rt->getAllTag();
                
                return new Response($this->render('admin/post/postForm.html.twig', ['categories' => $dataCategory,'tags' => $dataTag]));            
            }
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));        
        
    }


    /**
     * getAllPostController
     *
     * @return twig render
     * Send a request to get All infos in the table
     */
    public function getAllPostController($request)
    {       
        if($this->checkingAuth())
        {

            $typePost = $request->attributes->get('_route');

            //compare routes _admin_posts_ is All post with all comments are valid, 
            //_admin_posts_comment_ is route with some comments need to be validated
            if($typePost == "admin_posts")
            {
                $css_arraylist = "info";
                $status_post = "En totalité";
                try
                {
                    $rp = new PostRepository();
                    $data = $rp->getAllPost();             
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }
            }
            else
            {
                $css_arraylist = "danger";
                $status_post = " avec commentaires non validés";
                
                try
                {
                    $rp = new PostRepository();
                    $data = $rp->getAllPostUnvalidComment();             
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }
            }                       
            
            $content = $this->render('admin/post/postlist.html.twig', ['posts' => $data,'css_arraylist' => $css_arraylist,'status_post' => $status_post]);
            return new Response($content);
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
        
    }
    /**
     * getPostController
     *
     * @return twig render
     * Send a request to get one post then send a request to get all comment related to the post
     */
    public function getPostController($request)
    {
        $auth = new Auth();
        $session = new session();

        if($this->checkingAuth())
        {

            $slug = array(0 => $request->get('slug_post'));        
            
            if($request->getMethod() ==='POST'&& $auth->tokenChecking($session->get('token'),$request->request->get('usertoken')))
            {
                try
                {
                    $rp = new PostRepository();
                    $data = $rp->getOnePost($slug);               
                    
                    $cp = new CommentRepository();
                    $id = array(0 => $data[0]->getId());       
                    $datacomment = $cp->getAllComment($id);                            
                    
                    $content = $this->render('admin/post/post.html.twig', ['post' => $data[0],'comments' => $datacomment]);
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }
            }
            else
            {
                try
                {
                    $rp = new PostRepository();
                    $data = $rp->getOnePost($slug);             
                
                    $cp = new CommentRepository();
                    $id = array(0 => $data[0]->getId());       
                    $datacomment = $cp->getAllComment($id);                           
                    
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }
                $content = $this->render('admin/post/post.html.twig', ['post' => $data[0],'comments' => $datacomment]);

            }       
            
            return new Response($content);
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
    }
    


    /**
     * updatePostController
     *
     * @param  mixed $request httpFoundationRequest
     * @return twig render
     * get infos in $request by POST method then send request in repository if not send prefilled update form
     */
    public function updatePostController($request)
    {
        $auth = new Auth();
        $session = new session();

        if($this->checkingAuth())
        {

            if($request->getMethod() ==='POST'&& $auth->tokenChecking($session->get('token'),$request->request->get('usertoken')))
            {    
                         
                $newpost = new Post();
                $date = new \DateTime();
                $date = $date->format('Y-m-d H:i:s'); 
                $newpost->setId($request->request->get('id'));
                $newpost->setTitle($request->request->get('title'));
                $newpost->setChapo($request->request->get('chapo'));   
                $newpost->setAuthor($request->request->get('author'));   
                $newpost->setContent($request->request->get('content'));           
                $newpost->setSlug($request->request->get('slug'));
                $newpost->setCreated_at($request->request->get('created_at'));
                $newpost->setUpdated_at($date);            
                $newpost->setUser_id($request->request->get('user_id'));            
                
                $picture_path = $request->files->get('picture_path');                

                if(!empty($picture_path))
                {           
                    //-----Import Image-----//
                    $path = __DIR__."../../../../public/assets/img";     
                    $picture = $request->files->get('picture_path');           
                    if($picture->isValid() === true)
                    {
                        $picture->move($path,$picture->getClientOriginalName());
                    }
                    //-----END Import Image-----//   
                    $newpost->setPicture($picture->getClientOriginalName());  
                        
                }
                else{
                    
                    $newpost->setPicture($request->request->get('picture_name'));
                }                
            
                try
                {
                    $idpost = array(0 => $newpost->GetId());
                    $rp = new PostRepository();
                    $rp->updatePost($newpost);
                    $data = $rp->getAllPost();


                    //UPDATE TABLE ASSOCIATION POST_CAT AND TAG_POST
                    $rpc = new PostCatRepository();
                
                    $rpc->updatePostCat($request->request->all('categories'),$idpost);
                    $rtp = new TagPostRepository();
                    $rtp->updateTagPost($request->request->all('tags'),$idpost);
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }

                $content = $this->render('admin/post/postlist.html.twig', ['posts' => $data]);
            }
            else
            {
                $slug = array(0 => $request->get('slug_post'));          
                
                try
                {
                    $rp = new PostRepository();
                    $data = $rp->getOnePost($slug);                
                    //send 5 request to get the Post 
                    //then tags and categories related to this post, and 2 quest for list tag and categories
                    $rc = new CategoryRepository();
                    
                    $id = array(0 => $data[0]->getId());
                    $dataCategory = $rc->getAllCategory();
                    $dataselectedCat = $rc->getPostCatById($id);
                    
                    $rt = new TagRepository();                
                    $dataTags = $rt->getAllTag();
                    $dataselectedTags = $rt->getTagPostById($id);
                    
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }       
                
                $content = $this->render('admin/post/updatePost.html.twig',[
                                            'post' => $data[0],
                                            'categories' => $dataCategory,
                                            'categoriesSelected' => $dataselectedCat,
                                            'tags' => $dataTags,
                                            'tagsSelected' => $dataselectedTags
                                        ]);
            }
            return new Response($content);
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
    }

    /**
     * deletePostController
     *
     * @param  mixed $request
     * @return twig render
     * get id from request then send delete request to repository then return the list
     * all links related to the post are deleted ON CASCADE, like comments, post_cat, tag_post
     */
    public function deletePostController($request)
    {
        $auth = new Auth();
        $session = new session();        
        
        if($this->checkingAuth())
        {

            if($auth->tokenChecking($session->get('token'),$session->get('usertoken')))
            {        
                //create an array =>params for execute      
                $id = array(0 => $request->get('id'));          
                
                try
                {
                    $rp = new PostRepository();
                    $rp->deletePost($id);                                               
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }     
            }
            
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin_posts'));
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
    }
    
}

