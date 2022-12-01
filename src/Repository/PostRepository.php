<?php

//PostRepository
declare (strict_types=1);

namespace App\Repository;
use App\Model\Post;
use Aleyg\Core\DataBase\Database;


class PostRepository extends AbstractRepository
{
    
    public function getAllPost()
    {   
        $requete = "SELECT * FROM post  ORDER BY created_at DESC";
        $db = new Database();
        $db->getPdo();
        $req = $db->query($requete,Post::class);        
        return $req;      
    }
    public function getOnePost($slug)
    {
        $requete = "SELECT * FROM post where slug= ?";
               
        $db = new Database();
        $db->getPdo();
        $req = $db->parametersQuery($requete,$slug,Post::class);
        
        return $req;
    }
    
    public function getAllPostUnvalidComment()
    {      
      //This query find any post with unvalid comment => comment.comment_status bool to 0
      $requete = "SELECT post.*
                  FROM `post`
                  JOIN `comment`
                  ON post.id = comment.post_id
                  WHERE comment.comment_status = 0 GROUP BY post.id";
               
      $db = new Database();
      $db->getPdo();
      $req = $db->query($requete,Post::class);
      
      return $req;
    }


    public function getPostByTag($slugTag)
    {
        
        $requete = "SELECT post.id, post.title, post.chapo, post.author, post.content, post.picture, post.slug, post.created_at, post.updated_at, post.user_id,tag.name
        FROM `post`
        JOIN `tag_post`
          ON post.id = tag_post.post_id
        JOIN `tag`
          ON tag_post.tag_id = tag.id
          WHERE tag.slug = ? GROUP BY post.id ORDER BY created_at DESC";
        
        $db = new Database();
        $db->getPdo();
        $req = $db->parametersQuery($requete,$slugTag,Post::class);
        
        return $req;
    }

    public function getPostByCat($slugCategory)
    {
        
        $requete = "SELECT post.id, post.title, post.chapo, post.author, post.content, post.picture, post.slug, post.created_at, post.updated_at, post.user_id,category.name
        FROM `post`
        JOIN `post_cat`
          ON post.id = post_cat.post_id_post
        JOIN `category`
          ON post_cat.category_id_cat = category.id
          WHERE category.slug = ? GROUP BY post.id ORDER BY created_at DESC";
        
        $db = new Database();
        $db->getPdo();
        $req = $db->parametersQuery($requete,$slugCategory,Post::class);
        
        return $req;
    }
    public function addPost($newPost)
    {
        $requete = "INSERT INTO `post`(`id`, `title`, `chapo`, `author`, `content`, `picture`, `slug`, `created_at`, `updated_at`, `user_id`) VALUES (:id, :title, :chapo, :author, :content, :picture, :slug, :created_at, :updated_at, :user_id)";
        $arrayPost = array(
          "id"=> NULL,
          "title"=> $newPost->getTitle(),
          "chapo"=> $newPost->getChapo(),
          "author"=> $newPost->getAuthor(),
          "content"=> $newPost->getContent(),
          "picture"=> $newPost->getPicture(),
          "slug"=> $newPost->getSlug(),
          "created_at"=> $newPost->getCreated_at(),
          "updated_at"=> $newPost->getUpdated_at(),
          "user_id"=> $newPost->getUser_id(),
        );
        
        $db = new DataBase();
        $db->getPdo();        
        $db->modificationQuery($requete,$arrayPost);

        return $db->getPdo()->lastInsertId();
        
    }
    public function updatePost($post)
    {
      //Requete UPDATE
      $requete = "UPDATE post 
                  SET 
                  title = :title,
                  chapo = :chapo,
                  author = :author,
                  content = :content,
                  picture = :picture,
                  slug = :slug,
                  created_at = :created_at,
                  updated_at = :updated_at,
                  user_id = :user_id 
                  WHERE id = :id";

      //Attributes Array            
      $arrayPost = array(        
        "title"=> $post->getTitle(),
        "chapo"=> $post->getChapo(),
        "author"=> $post->getAuthor(),
        "content"=> $post->getContent(),
        "picture"=> $post->getPicture(),
        "slug"=> $post->getSlug(),
        "created_at"=> $post->getCreated_at(),
        "updated_at"=> $post->getUpdated_at(),
        "user_id"=> $post->getUser_id(),
        "id"=> $post->getId());                           
      $db = New Database();
      $db->getPdo();
      $db->modificationQuery($requete,$arrayPost);     
        
    }

    public function deletePost($id)
    {
      $requete = "DELETE FROM post where id= ?";
              
      $db = new Database();
      $db->getPdo();
      $db->parametersQuery($requete,$id,Post::class);      
      
    }
}