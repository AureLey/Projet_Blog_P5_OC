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
        $database = new Database();
        $database->getPdo();
        $req = $database->query($requete,Category::class);
        
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
        
        $database = new DataBase();
        $database->getPdo();        
        $database->modificationQuery($requete,$arrayCategory);
    }
    public function getOneCategory($slug)
    {
        $requete = "SELECT * FROM category where slug= ?";
               
        $database = new Database();
        $database->getPdo();
        $req = $database->parametersQuery($requete,$slug,Category::class);
        
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
                                 
      $database = New Database();
      $database->getPdo();
      $database->modificationQuery($requete,$arrayCategory);
        
    }

    public function getPostCatById($idCategory)
    {
        $database = new DataBase();         
        $database->getPdo();
        $requete = "SELECT  category.id ,category.name, category.slug
        FROM `post_cat`
        JOIN `category`
          ON post_cat.category_id_cat = category.id        
          WHERE post_cat.post_id_post = ?";               
        
        $req = $database->parametersQuery($requete,$idCategory,Category::class);
        
        return $req;
    }
    public function deleteCategory($idCategory)
    {
      $requete = "DELETE FROM category where id= ?";
              
      $database = new Database();
      $database->getPdo();
      $database->parametersQuery($requete,$idCategory,Category::class);
      
    }
}