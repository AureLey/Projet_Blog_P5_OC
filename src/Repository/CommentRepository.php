<?php

//CommentRepository
declare (strict_types=1);

namespace App\Repository;
use App\Model\Comment;
use Aleyg\Core\DataBase\Database;


class CommentRepository extends AbstractRepository
{
    public function getAllComment($idPost)
    {
        //$requete = "SELECT * FROM comment where post_id= ?";
        //get User nickname from User table, to print it in comment section content + author
        $requete = "SELECT comment.*, user.nickname
                    FROM comment
                    JOIN user
                    ON comment.user_id = user.id     
                    WHERE comment.post_id = ? ORDER BY comment.id DESC";     
        $database = new Database();
        $database->getPdo();        
        $req = $database->parametersQuery($requete,$idPost,Comment::class);
        
        return $req;
    }



    public function addComment($newcomment)
    {
        $requete = "INSERT INTO `comment`(`content`, `comment_status`, `post_id`, `user_id`) VALUES (:content, :comment_status,:post_id,:user_id)";        
        //Set comment_status to 0, mean not publish and need validation in dashboard
        $arrayComment = array(
                    
          "content"=> $newcomment->getContent(),
          "comment_status"=> $newcomment->getComment_status(), 
          "post_id"=> $newcomment->getPost_id(), 
          "user_id"=> $newcomment->getUser_id()
        );       
        
        $database = new DataBase();
        $database->getPdo(); 
        $database->modificationQuery($requete,$arrayComment);
    }

    public function getAllUnvalidComment($idPost)
    {
        //$requete = "SELECT * FROM comment where post_id= ?";
        //get User nickname from User table, to print it in comment section content + author
        $requete = "SELECT comment.*, user.nickname
                    FROM comment
                    JOIN user
                    ON comment.user_id = user.id     
                    WHERE comment.post_id = ?
                    AND comment.comment_status = 0
                    GROUP BY comment.id";     
        $database = new Database();
        $database->getPdo();        
        $req = $database->parametersQuery($requete,$idPost,Comment::class);
        
        return $req;
    }

    public function getAllvalidComment($idPost)
    {
        //$requete = "SELECT * FROM comment where post_id= ?";
        //get User nickname from User table, to print it in comment section content + author
        $requete = "SELECT comment.*, user.nickname
                    FROM comment
                    JOIN user
                    ON comment.user_id = user.id     
                    WHERE comment.post_id = ?
                    AND comment.comment_status = 1
                    GROUP BY comment.id";     
        $database = new Database();
        $database->getPdo();        
        $req = $database->parametersQuery($requete,$idPost,Comment::class);
        
        return $req;
    }

    public function getComment($idPost)
    { 
        $requete = "SELECT comment.*, user.nickname
                    FROM comment
                    JOIN user
                    ON comment.user_id = user.id     
                    WHERE comment.id = ?";     
        $database = new Database();
        $database->getPdo();        
        $req = $database->parametersQuery($requete,$idPost,Comment::class);
        
        return $req;
    }

    public function setValidComment($idcomment)
    {
        $requete ="UPDATE `comment` SET `comment_status`= 1 WHERE `id`=?";
        $database = new DataBase();
        $database->getPdo(); 
        $database->modificationQuery($requete,$idcomment);
    }
}