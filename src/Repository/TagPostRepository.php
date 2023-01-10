<?php

//TagPostRepository
declare (strict_types=1);

namespace App\Repository;
use App\Model\Tag;
use App\Model\Post;
use Aleyg\Core\DataBase\Database;



class TagPostRepository extends AbstractRepository
{

        
    /**
     * addTagPost
     * Make the link between the table Post and Tag
     * @param  array $arrayTp
     * @param  array $idpost     
     */
    public function addTagPost($arrayTp,$idpost)
    {   
        $database = new DataBase();         
        $database->getPdo();        
        $arrayValue = [];
        foreach ($arrayTp as $value) 
        {
            $arrayValue = array( 
                "idpost"=> $idpost,          
                "idtag"=> $value);
            
            $requete = "INSERT INTO `tag_post`(`post_id`,`tag_id`) VALUES (:idpost, :idtag)";
            $database->modificationQuery($requete,$arrayValue);     
        }      
    }

    /**
     * updateTagPost
     *
     * @param  mixed $arrayTp All Tags id selected in updateForm
     * @param  mixed $idpost  is an array, represent id post.In first for execute quer, then extract the value to insert into query
     */
    public function updateTagPost($arrayTp,$idpost)
    {
        $database = new DataBase();         
        $database->getPdo();

        $requetedel = "DELETE FROM tag_post where post_id = ?";             
        
        $database->modificationQuery($requetedel,$idpost);

        $arrayValue = [];
        $idpost = $idpost[0];//convert to INT for INSERT INTO QUERY
        
        foreach ($arrayTp as $line => $value) 
        {
            $arrayValue = array( 
                "idpost"=> $idpost,          
                "idtag"=> $value);
            
            $requete = "INSERT INTO `tag_post`(`post_id`,`tag_id`) VALUES (:idpost, :idtag)";
            $database->modificationQuery($requete,$arrayValue);     
        }

    }
    
}