<?php
//AdminCommentController
declare (strict_types=1);

namespace App\Controllers\Admin;

use Aleyg\Core\Security\Auth;
use App\Controllers\AbstractController;
use App\Model\Comment;
use App\Model\Post;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Config\RoutesPath;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;


class AdminCommentController extends AbstractController
{

    public function validCommentController($request)
    {  
        $auth = new Auth();
        $session = new session();
        
        if($this->checkingAuth())
        {
            $idPost = array(0 => $request->attributes->get('id'));
            $slug = array(0 => $request->attributes->get('slug_post'));
            
            if($request->getMethod() ==='POST'&& $auth->tokenChecking($session->get('token'),$request->request->get('usertoken')))
            {               
                try
                {                
                    $repoComment = new CommentRepository();                
                    $repoComment->setValidComment($idPost);                
                    $pr = new PostRepository();
                    $data = $pr->getOnePost($slug);
                    $idpost = array(0 => $data[0]->getId());
                    $datacomment = $repoComment->getAllComment($idpost);
                    
                }
                catch (PDOException $exception )
                {
                    $this->getContainer()->get('log')->error($exception);
                }
                
                $content = $this->render('admin/post/post.html.twig', ['post'=>$data[0],'comments'=> $datacomment]);
            
            }
            else
            {
                try
                {
                    $pr = new PostRepository();
                    $data = $pr->getOnePost($slug);                           
                    
                    $repoComment = new CommentRepository();
                    $datacomment = $repoComment->getComment($idPost);
                }
                catch (PDOException $exception) {
                    $this->getContainer()->get('log')->error($exception);
                }
                $content = $this->render('admin/comment/commentvalidation.html.twig', ['post'=>$data[0],'comment'=> $datacomment[0]]);
            }
            return new Response($content);
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
    }
}
