<?php

//CommentRepository
declare (strict_types=1);

namespace App\Repository;
use App\Model\Comment;
use Aleyg\Core\DataBase\Database;


class CommentRepository extends AbstractRepository
{
    public function getAllComment($id)
    {
        //$requete = "SELECT * FROM comment where post_id= ?";
        //get User nickname from User table, to print it in comment section content + author
        $requete = "SELECT comment.*, user.nickname
                    FROM comment
                    JOIN user
                    ON comment.user_id = user.id     
                    WHERE comment.post_id = ? ORDER BY comment.id DESC";     
        $db = new Database();
        $db->getPdo();        
        $req = $db->parametersQuery($requete,$id,Comment::class);
        
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
        
        $db = new DataBase();
        $db->getPdo(); 
        $db->modificationQuery($requete,$arrayComment);
    }

    public function getAllUnvalidComment($id)
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
        $db = new Database();
        $db->getPdo();        
        $req = $db->parametersQuery($requete,$id,Comment::class);
        
        return $req;
    }

    public function getAllvalidComment($id)
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
        $db = new Database();
        $db->getPdo();        
        $req = $db->parametersQuery($requete,$id,Comment::class);
        
        return $req;
    }

    public function getComment($id)
    { 
        $requete = "SELECT comment.*, user.nickname
                    FROM comment
                    JOIN user
                    ON comment.user_id = user.id     
                    WHERE comment.id = ?";     
        $db = new Database();
        $db->getPdo();        
        $req = $db->parametersQuery($requete,$id,Comment::class);
        
        return $req;
    }

    public function setValidComment($idcomment)
    {
        $requete ="UPDATE `comment` SET `comment_status`= 1 WHERE `id`=?";
        $db = new DataBase();
        $db->getPdo(); 
        $db->modificationQuery($requete,$idcomment);
    }
}