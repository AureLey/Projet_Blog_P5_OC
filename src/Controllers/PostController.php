<?php

//ControllerHomePage
declare (strict_types=1);

namespace App\Controllers;


use Aleyg\Core\Database\Database;
use App\Model\Comment;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use App\Repository\CommentRepository;
use Config\RoutesPath;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PostController extends AbstractController
{
        
    /**
     * GetPostController return one post via Slug field
     *
     * @param  mixed $request return by index.php attributes[]
     * @return void
     */
    public function getPostController($request)
    {
        $session = new Session();

        //Methode POST if one comment was send
        if($request->getMethod() ==='POST')
        {
            $slug = array(0 => $request->attributes->get('slug_post'));
                        
            try
            {
                $repoPost = new PostRepository();
                $data = $repoPost->getOnePost($slug);                
                //send 5 request to get the Post 
                //then tags and categories related to this post, and 2 quest for list tag and categories
                $rc = new CategoryRepository();
                $idPost = array(0 => $data[0]->getId());
                $dataCategory = $rc->getAllCategory();
                $dataselectedCat = $rc->getPostCatById($idPost);
                
                $repoTag = new TagRepository();                
                $dataTags = $repoTag->getAllTag();
                $dataselectedTags = $repoTag->getTagPostById($idPost);
                
                $newcomment = new Comment();
                $newcomment->setContent($request->request->get('comment'));
                $newcomment->setComment_status(0);
                $newcomment->setPost_id($data[0]->getId());
                $newcomment->setUser_id($session->get('id'));
                $newcomment->setNickname($session->get('nickname'));

                $rcmt = new CommentRepository();
                $rcmt->addComment($newcomment);                
                $dataComment = $rcmt->getAllvalidComment($idPost);
            }
            catch (PDOException $exception) {
                $this->getContainer()->get('log')->error($exception);
            }
        }
        else
        {
            // create an array, and take slug of the post
            $slug = array(0 => $request->attributes->get('slug_post'));        
            
            try
            {
                $repoPost = new PostRepository();
                $data = $repoPost->getOnePost($slug);                
                //send 5 request to get the Post 
                //then tags and categories related to this post, and 2 quest for list tag and categories
                $rc = new CategoryRepository();
                
                $idPost = array(0 => $data[0]->getId());
                $dataCategory = $rc->getAllCategory();
                $dataselectedCat = $rc->getPostCatById($idPost);

                $rcmt = new CommentRepository();
                $dataComment = $rcmt->getAllvalidComment($idPost);
                
                $repoTag = new TagRepository();                
                $dataTags = $repoTag->getAllTag();
                $dataselectedTags = $repoTag->getTagPostById($idPost);
                             
            }
            catch (PDOException $exception) {
                $this->getContainer()->get('log')->error($exception);
            }      
           
        }
       
        $content = $this->render('post.html.twig',[
            'post' => $data[0],
            'categories' => $dataCategory,
            'categoriesSelected' => $dataselectedCat,
            'tags' => $dataTags,
            'tagsSelected' => $dataselectedTags,
            'comments' => $dataComment]);
        
        return new Response($content);
    }
}