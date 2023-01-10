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
     * @return Response|RedirectResponse
     * get infos in $request by POST method then send request in repository if not send creation form
     */
    public function newPostController($request):Response|RedirectResponse
    {
        $auth = new Auth();
        $session = new session();

        //Checking role of user 
        if($this->checkingAuth())
        {

            //if is POST method and CSRF Token ok, Add the post to DB
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
                $newpost->setTitle($request->request->get('title'))
                        ->setChapo($request->request->get('chapo'))  
                        ->setAuthor($request->request->get('author'))   
                        ->setContent($request->request->get('content')) 
                        ->setPicture($picture->getClientOriginalName())
                        ->setSlug($request->request->get('slug'))
                        ->setCreated_at($date)
                        ->setUpdated_at($date)
                        ->setUser_id($session->get('id'));

                //create each repository to prepare insert request,
                //$idpost is id return by the function addPost and in this function PDO->lastInsertId
                $repoPost = new PostRepository();            
                $idpost = $repoPost->addPost($newpost);

                $repoPostCategory = new PostCatRepository();
                $repoPostCategory->addPostCat($request->request->all('categories'),$idpost);

                
                $repoTagPost = new TagPostRepository();
                $repoTagPost->addTagPost($request->request->all('tags'),$idpost);
                
                            
                return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('admin_posts'));   
            }
            else
            {
                //send 2 request to get all categories and tags to complete the creation form
                $repoCategory = new CategoryRepository();
                $dataCategory = $repoCategory->getAllCategory();
                $repoTag = new TagRepository();
                $dataTag = $repoTag->getAllTag();
                
                return new Response($this->render('admin/post/postForm.html.twig', ['categories' => $dataCategory,'tags' => $dataTag]));            
            }
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));        
        
    }


    /**
     * getAllPostController
     *
     * @return Response|RedirectResponse
     * Send a request to get All infos in the table
     */
    public function getAllPostController($request):Response|RedirectResponse
    {   
        //checking role of user   
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
                    $repoPost = new PostRepository();
                    $data = $repoPost->getAllPost();             
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
                    $repoPost = new PostRepository();
                    $data = $repoPost->getAllPostUnvalidComment();             
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
     * @return Response|RedirectResponse
     * Send a request to get one post then send a request to get all comment related to the post
     */
    public function getPostController($request):Response|RedirectResponse
    {
        $auth = new Auth();
        $session = new session();

        //Checking role of user
        if($this->checkingAuth())
        {
            //Repo function var and need to be an array
            $slug = array(0 => $request->get('slug_post')); 
            
            try
            {
                $repoPost = new PostRepository();
                $data = $repoPost->getOnePost($slug);             
            
                $repoComment = new CommentRepository();
                $idPost= array(0 => $data[0]->getId());       
                $datacomment = $repoComment->getAllComment($idPost);                           
                
            }
            catch (PDOException $exception) {
                $this->getContainer()->get('log')->error($exception);
            }
            $content = $this->render('admin/post/post.html.twig', ['post' => $data[0],'comments' => $datacomment]);
                
                 
            
            return new Response($content);
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
    }
    


    /**
     * updatePostController
     *
     * @param  mixed $request httpFoundationRequest
     * @return Response|RedirectResponse
     * get infos in $request by POST method then send request in repository if not send prefilled update form
     */
    public function updatePostController($request):Response|RedirectResponse
    {
        $auth = new Auth();
        $session = new session();

        // checking role of user
        if($this->checkingAuth())
        {

            //If method is post and CSRF token OK, Post is updated to DB
            if($request->getMethod() ==='POST'&& $auth->tokenChecking($session->get('token'),$request->request->get('usertoken')))
            {    
                //create post object from request        
                $newpost = new Post();
                $date = new \DateTime();
                $date = $date->format('Y-m-d H:i:s'); 
                $newpost->setId($request->request->get('id'))
                        ->setTitle($request->request->get('title'))
                        ->setChapo($request->request->get('chapo'))  
                        ->setAuthor($request->request->get('author'))   
                        ->setContent($request->request->get('content'))           
                        ->setSlug($request->request->get('slug'))
                        ->setCreated_at($request->request->get('created_at'))
                        ->setUpdated_at($date)            
                        ->setUser_id($request->request->get('user_id'));            
                
                //Image process
                $picture_path = $request->files->get('picture_path');                

                //control if a file already exist if not import the file
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
                else
                    $newpost->setPicture($request->request->get('picture_name'));
                                
            
                try
                {
                    //get Idpost then do modification in associative table 
                    $idpost = array(0 => $newpost->GetId());
                    $repoPost = new PostRepository();
                    $repoPost->updatePost($newpost);
                    $data = $repoPost->getAllPost();


                    //UPDATE TABLE ASSOCIATION POST_CAT AND TAG_POST
                    $repoPostCategory = new PostCatRepository();
                
                    $repoPostCategory->updatePostCat($request->request->all('categories'),$idpost);
                    $repoTagPost = new TagPostRepository();
                    $repoTagPost->updateTagPost($request->request->all('tags'),$idpost);
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
                    $repoPost = new PostRepository();
                    $data = $repoPost->getOnePost($slug);                
                    //send 5 request to get the Post 
                    //then tags and categories related to this post, and 2 quest for list tag and categories
                    $repoCategory = new CategoryRepository();
                    
                    $idPost= array(0 => $data[0]->getId());
                    $dataCategory = $repoCategory->getAllCategory();
                    $dataselectedCat = $repoCategory->getPostCatById($idPost);
                    
                    $repoTag = new TagRepository();                
                    $dataTags = $repoTag->getAllTag();
                    $dataselectedTags = $repoTag->getTagPostById($idPost);
                    
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
            return new Response($content);//return form with post info or handle form to update post
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
    }

    /**
     * deletePostController
     *
     * @param  mixed $request
     * @return RedirectResponse
     * get id from request then send delete request to repository then return the list
     * all links related to the post are deleted ON CASCADE, like comments, post_cat, tag_post
     */
    public function deletePostController($request):RedirectResponse
    {
        $auth = new Auth();
        $session = new session();        
        
        if($this->checkingAuth())
        {

            if($auth->tokenChecking($session->get('token'),$session->get('usertoken')))
            {        
                //create an array =>params for execute      
                $idPost= array(0 => $request->get('id'));          
                
                try
                {
                    $repoPost = new PostRepository();
                    $repoPost->deletePost($idPost);                                               
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

