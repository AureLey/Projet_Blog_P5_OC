<?php

//PostRepository
declare (strict_types=1);

namespace App\Repository;
use App\Model\Category;
use Aleyg\Core\DataBase\Database;



class CategoryRepository extends AbstractRepository
{
    
    public function getAllCategory()
    {   
        $requete = "SELECT * FROM category";
        $db = new Database();
        $db->getPdo();
        $req = $db->query($requete,Category::class);
        
        return $req;      
    }
    public function addCategory($newcategory)
    {
        $requete = "INSERT INTO `category`(`id`,`name`, `slug`) VALUES (:id, :name, :slug)";        
        $arrayCategory = array(
          "id" => NULL,          
          "name"=> $newcategory->getName(),          
          "slug"=> $newcategory->getSlug()
        );       
        
        $db = new DataBase();
        $db->getPdo();        
        $db->modificationQuery($requete,$arrayCategory);
    }
    public function getOneCategory($slug)
    {
        $requete = "SELECT * FROM category where slug= ?";
               
        $db = new Database();
        $db->getPdo();
        $req = $db->parametersQuery($requete,$slug,Category::class);
        
        return $req;
    }
    
    public function updateCategory($category)
    {
      //Requete UPDATE
      $requete = "UPDATE category 
                  SET 
                  name = :name,                  
                  slug = :slug                  
                  WHERE id = :id";

      //Attributes Array            
      $arrayCategory = array(        
        "name"=> $category->getName(),        
        "slug"=> $category->getSlug(),        
        "id"=> $category->getId()); 
                                 
      $db = New Database();
      $db->getPdo();
      $db->modificationQuery($requete,$arrayCategory);
        
    }

    public function getPostCatById($id)
    {
        $db = new DataBase();         
        $db->getPdo();
        $requete = "SELECT  category.id ,category.name, category.slug
        FROM `post_cat`
        JOIN `category`
          ON post_cat.category_id_cat = category.id        
          WHERE post_cat.post_id_post = ?";               
        
        $req = $db->parametersQuery($requete,$id,Category::class);
        
        return $req;
    }
    public function deleteCategory($id)
    {
      $requete = "DELETE FROM category where id= ?";
              
      $db = new Database();
      $db->getPdo();
      $db->parametersQuery($requete,$id,Category::class);
      
    }
}