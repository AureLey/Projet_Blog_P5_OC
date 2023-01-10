<?php

//TagRepository
declare (strict_types=1);

namespace App\Repository;
use App\Model\Tag;
use Aleyg\Core\DataBase\Database;



class TagRepository extends AbstractRepository
{

      
    /**
     * getAllTag
     * Get list of all tag
     * @return array
     */
    public function getAllTag():array
    {   
        $requete = "SELECT * FROM tag";
        $database = new Database();
        $database->getPdo();
        $req = $database->query($requete,Tag::class);
        
        return $req;      
    }

        
    /**
     * addTag
     * Add ONE with (object) $newtag infos
     * @param  Tag $newtag
     */
    public function addTag($newtag)
    {
        $requete = "INSERT INTO `tag`(`name`, `slug`) VALUES (:name, :slug)";        
        $arrayTag = array(         
          "name"=> $newtag->getName(),          
          "slug"=> $newtag->getSlug()
        );       
        
        $database = new DataBase();
        $database->getPdo();        
        $database->modificationQuery($requete,$arrayTag);
    }

        
    /**
     * getOneTag
     * get ONE TAG by his slug $slug
     * @param  array $slug
     * @return array
     */
    public function getOneTag($slug): array
    {
        $requete = "SELECT * FROM tag where slug= ?";
               
        $database = new Database();
        $database->getPdo();
        $req = $database->parametersQuery($requete,$slug,Tag::class);
        
        return $req;
    }

        
    /**
     * deleteTag
     * Delete ONE tag by his id $id
     * @param  array $id     
     */
    public function deleteTag($id)
    {
        $requete = "DELETE FROM tag where id= ?";
               
        $database = new Database();
        $database->getPdo();
        $database->parametersQuery($requete,$id,Tag::class);        
        
    }
    
    /**
     * updateTag
     * Update Tag infos
     * @param  Tag $tag
     */
    public function updateTag($tag)
    {
      //Requete UPDATE
      $requete = "UPDATE tag 
                  SET 
                  name = :name,                  
                  slug = :slug                  
                  WHERE id = :id";

      //Attributes Array            
      $arrayTag = array(        
        "name"=> $tag->getName(),        
        "slug"=> $tag->getSlug(),        
        "id"=> $tag->getId()); 
                                 
      $database = New Database();
      $database->getPdo();
      $database->modificationQuery($requete,$arrayTag);
        
    }
    
    /**
     * getTagPostById
     * Return list of tags relative to ONE Post with id $id
     * @param  array $id
     * @return array
     */
    public function getTagPostById($id):array
    {
        $database = new DataBase();         
        $database->getPdo();
        $requete = "SELECT  tag.id ,tag.name, tag.slug
        FROM `tag_post`
        JOIN `tag`
          ON tag_post.tag_id = tag.id        
          WHERE tag_post.post_id = ?";               
        
        $req = $database->parametersQuery($requete,$id,Tag::class);
        
        return $req;
    }
}