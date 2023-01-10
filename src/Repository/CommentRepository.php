<?php

//CommentRepository
declare (strict_types=1);

namespace App\Repository;
use App\Model\Comment;
use Aleyg\Core\DataBase\Database;


class CommentRepository extends AbstractRepository
{

        
    /**
     * getAllComment
     * Return All comments relative to $idPost
     * @param  array $idPost
     * @return array
     */
    public function getAllComment($idPost):array
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


    
    /**
     * addComment
     * Add One comment to a Post and get info 
     * @param  Comment $newcomment
     * 
     */
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

        
    /**
     * getAllUnvalidComment
     * get all Comment "Unvalid" mean comment_status = 0, who needs admin's validation
     * @param  array $idPost
     * @return array
     */
    public function getAllUnvalidComment($idPost):array
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

        
    /**
     * getAllvalidComment
     * get all valid comment from $idpost
     * @param  array $idPost
     * @return array
     */
    public function getAllvalidComment($idPost):array
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
    
    /**
     * getComment
     * get all infos of the comment find with $idpost
     * @param  mixed $idPost
     * @return array
     */
    public function getComment($idPost): array
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

        
    /**
     * setValidComment
     * Change Status of the comment to 1, meaning the comment can be see on Post page
     * @param  array $idcomment     
     */
    public function setValidComment($idcomment)
    {
        $requete ="UPDATE `comment` SET `comment_status`= 1 WHERE `id`=?";
        $database = new DataBase();
        $database->getPdo(); 
        $database->modificationQuery($requete,$idcomment);
    }
}