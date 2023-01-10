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
     * @param Request $request return by index.php attributes[]
     * @return Response
     */
    public function getPostController($request):Response
    {
        $session = new Session();

        //REFACTORISATION POSSIBLE CODE DUPLIQUé
        

        //Checking if Method is POST then handle comment's form then add it in DB OR 
        if($request->getMethod() ==='POST')
        {
            //repo function ask array as parameter
            $slug = array(0 => $request->attributes->get('slug_post'));
                        
            try
            {
                $repoPost = new PostRepository();
                $data = $repoPost->getOnePost($slug);                
                //send 5 request to get the Post 
                //then tags and categories related to this post, and 2 quest for list tag and categories
                $repoCategory = new CategoryRepository();
                $idPost = array(0 => $data[0]->getId());
                $dataCategory = $repoCategory->getAllCategory();
                $dataselectedCat = $repoCategory->getPostCatById($idPost);
                
                $repoTag = new TagRepository();                
                $dataTags = $repoTag->getAllTag();
                $dataselectedTags = $repoTag->getTagPostById($idPost);
                
                $newcomment = new Comment();
                $newcomment ->setContent($request->request->get('comment'))
                            ->setComment_status(0)
                            ->setPost_id($data[0]->getId())
                            ->setUser_id($session->get('id'))
                            ->setNickname($session->get('nickname'));

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
            //Repo function asked array as parameter
            $slug = array(0 => $request->attributes->get('slug_post'));        
            
            try
            {
                $repoPost = new PostRepository();
                $data = $repoPost->getOnePost($slug);                
                //send 5 request to get the Post 
                //then tags and categories related to this post, and 2 quest for list tag and categories
                $repoCategory = new CategoryRepository();
                
                $idPost = array(0 => $data[0]->getId());
                $dataCategory = $repoCategory->getAllCategory();
                $dataselectedCat = $repoCategory->getPostCatById($idPost);

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