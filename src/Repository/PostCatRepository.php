<?php

//PostCatRepository
declare (strict_types=1);

namespace App\Repository;
use App\Model\Category;
use App\Model\Post;
use Aleyg\Core\DataBase\Database;



class PostCatRepository extends AbstractRepository
{
    public function addPostCat($arrayPc,$idpost)
    {   
        $db = new DataBase();         
        $db->getPdo();        
        $arrayValue = [];
        foreach ($arrayPc as $value) 
        {
            $arrayValue = array( 
                "idpost"=> $idpost,          
                "idcat"=> $value);
            
            $requete = "INSERT INTO `post_cat`(`post_id_post`,`category_id_cat`) VALUES (:idpost, :idcat)";
            $db->modificationQuery($requete,$arrayValue);     
        }      
    }
    
    /**
     * updatePostCat
     *
     * @param  mixed $arrayPc All categories id selected in updateForm
     * @param  mixed $idpost  is an array, represent id post in first for execute query. then extract the value to insert into query
     * @return void
     */
    public function updatePostCat($arrayPc,$idpost)
    {
        $db = new DataBase();         
        $db->getPdo();

        $requetedel = "DELETE FROM post_cat where post_id_post = ?";             
        
        $db->modificationQuery($requetedel,$idpost);

        $arrayValue = [];
        $idpost = $idpost[0];//convert to INT for INSERT INTO QUERY
        
        foreach ($arrayPc as $line => $value) 
        {
            $arrayValue = array( 
                "idpost"=> $idpost,          
                "idcat"=> $value);
            
            $requete = "INSERT INTO `post_cat`(`post_id_post`,`category_id_cat`) VALUES (:idpost, :idcat)";
            $db->modificationQuery($requete,$arrayValue);     
        }

    }
    
}