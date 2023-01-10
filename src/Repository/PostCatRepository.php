<?php

//PostCatRepository
declare (strict_types=1);

namespace App\Repository;
use App\Model\Category;
use App\Model\Post;
use Aleyg\Core\DataBase\Database;



class PostCatRepository extends AbstractRepository
{
        
    /**
     * addPostCat
     * Make the link between the table Post and Category
     * @param  array $arrayPc
     * @param  array $idpost     
     */
    public function addPostCat($arrayPc,$idpost)
    {   
        $database = new DataBase();         
        $database->getPdo();        
        $arrayValue = [];
        foreach ($arrayPc as $value) 
        {
            $arrayValue = array( 
                "idpost"=> $idpost,          
                "idcat"=> $value);
            
            $requete = "INSERT INTO `post_cat`(`post_id_post`,`category_id_cat`) VALUES (:idpost, :idcat)";
            $database->modificationQuery($requete,$arrayValue);     
        }      
    }
    
    /**
     * updatePostCat
     *
     * @param  array $arrayPc All categories id selected in updateForm
     * @param  array $idpost  is an array, represent id post in first for execute query. then extract the value to insert into query
     */
    public function updatePostCat($arrayPc,$idpost)
    {
        $database = new DataBase();         
        $database->getPdo();

        $requetedel = "DELETE FROM post_cat where post_id_post = ?";             
        
        $database->modificationQuery($requetedel,$idpost);

        $arrayValue = [];
        $idpost = $idpost[0];//convert to INT for INSERT INTO QUERY
        
        foreach ($arrayPc as $line => $value) 
        {
            $arrayValue = array( 
                "idpost"=> $idpost,          
                "idcat"=> $value);
            
            $requete = "INSERT INTO `post_cat`(`post_id_post`,`category_id_cat`) VALUES (:idpost, :idcat)";
            $database->modificationQuery($requete,$arrayValue);     
        }

    }
    
}