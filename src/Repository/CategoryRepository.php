<?php

//PostRepository
declare (strict_types=1);

namespace App\Repository;
use App\Model\Category;
use Aleyg\Core\DataBase\Database;



class CategoryRepository extends AbstractRepository
{
        
    /**
     * getAllCategory
     *
     * Get All category 
     * @return void
     */
    public function getAllCategory(): array
    {   
        $requete = "SELECT * FROM category";
        $database = new Database();
        $database->getPdo();
        $req = $database->query($requete,Category::class);
        
        return $req;      
    }    
    /**
     * addCategory
     * ADD a category
     * @param  Category $newcategory 
     */
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
        
    /**
     * getOneCategory
     * Get ONE category by (array)slug
     * @param  array $slug
     * @return void
     */
    public function getOneCategory($slug):array
    {
        $requete = "SELECT * FROM category where slug= ?";
               
        $database = new Database();
        $database->getPdo();
        $req = $database->parametersQuery($requete,$slug,Category::class);
        
        return $req;
    }
        
    /**
     * updateCategory
     * Update one category then change all content needed
     * @param Category $category
     * 
     */
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
    
    /**
     * getPostCatById
     * Get all categories relative to one post 
     * @param  array $idCategory
     * @return arrray
     */

    public function getPostCatById($idPost): array
    {
        $database = new DataBase();         
        $database->getPdo();
        $requete = "SELECT  category.id ,category.name, category.slug
        FROM `post_cat`
        JOIN `category`
          ON post_cat.category_id_cat = category.id        
          WHERE post_cat.post_id_post = ?";               
        
        $req = $database->parametersQuery($requete,$idPost,Category::class);
        
        return $req;
    }

        
    /**
     * deleteCategory
     * Delete category by Id
     * @param  mixed $idCategory
     * @return void
     */
    public function deleteCategory($idCategory)
    {
      $requete = "DELETE FROM category where id= ?";
              
      $database = new Database();
      $database->getPdo();
      $database->parametersQuery($requete,$idCategory,Category::class);
      
    }
}