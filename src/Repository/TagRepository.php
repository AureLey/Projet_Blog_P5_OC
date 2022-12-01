<?php

//TagRepository
declare (strict_types=1);

namespace App\Repository;
use App\Model\Tag;
use Aleyg\Core\DataBase\Database;



class TagRepository extends AbstractRepository
{
    public function getAllTag()
    {   
        $requete = "SELECT * FROM tag";
        $db = new Database();
        $db->getPdo();
        $req = $db->query($requete,Tag::class);
        
        return $req;      
    }
    public function addTag($newtag)
    {
        $requete = "INSERT INTO `tag`(`name`, `slug`) VALUES (:name, :slug)";        
        $arrayTag = array(         
          "name"=> $newtag->getName(),          
          "slug"=> $newtag->getSlug()
        );       
        
        $db = new DataBase();
        $db->getPdo();        
        $db->modificationQuery($requete,$arrayTag);
    }

    public function getOneTag($slug)
    {
        $requete = "SELECT * FROM tag where slug= ?";
               
        $db = new Database();
        $db->getPdo();
        $req = $db->parametersQuery($requete,$slug,Tag::class);
        
        return $req;
    }
    public function deleteTag($id)
    {
        $requete = "DELETE FROM tag where id= ?";
               
        $db = new Database();
        $db->getPdo();
        $db->parametersQuery($requete,$id,Tag::class);        
        
    }

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
                                 
      $db = New Database();
      $db->getPdo();
      $db->modificationQuery($requete,$arrayTag);
        
    }

    public function getTagPostById($id)
    {
      $db = new DataBase();         
        $db->getPdo();
        $requete = "SELECT  tag.id ,tag.name, tag.slug
        FROM `tag_post`
        JOIN `tag`
          ON tag_post.tag_id = tag.id        
          WHERE tag_post.post_id = ?";               
        
        $req = $db->parametersQuery($requete,$id,Tag::class);
        
        return $req;
    }
}