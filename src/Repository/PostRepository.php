<?php

//PostRepository
declare (strict_types=1);

namespace App\Repository;
use App\Model\Post;
use Aleyg\Core\DataBase\Database;


class PostRepository extends AbstractRepository
{
        
    /**
     * getAllPost
     * Return All post and by DESC
     * @return array
     */
    public function getAllPost(): array
    {   
        $requete = "SELECT * FROM post  ORDER BY created_at DESC";
        $database = new Database();
        $database->getPdo();
        $req = $database->query($requete,Post::class);        
        return $req;      
    }

    
    /**
     * getOnePost
     * Return ONE Post with $slug
     * @param  array $slug
     * @return array
     */
    public function getOnePost($slug): array
    {
        $requete = "SELECT * FROM post where slug= ?";
               
        $database = new Database();
        $database->getPdo();
        $req = $database->parametersQuery($requete,$slug,Post::class);
        
        return $req;
    }
        
    /**
     * getAllPostUnvalidComment
     *  Return List of Post,these posts have one comment who need to be valided
     * @return array
     */
    public function getAllPostUnvalidComment(): array
    {      
      //This query find any post with unvalid comment => comment.comment_status bool to 0
      $requete = "SELECT post.*
                  FROM `post`
                  JOIN `comment`
                  ON post.id = comment.post_id
                  WHERE comment.comment_status = 0 GROUP BY post.id";
               
      $database = new Database();
      $database->getPdo();
      $req = $database->query($requete,Post::class);
      
      return $req;
    }

    
    /**
     * getPostByTag
     * Return the list of post with $slugtag as parameter
     * @param  array $slugTag
     * @return array
     */
    public function getPostByTag($slugTag): array
    {
        
        $requete = "SELECT post.id, post.title, post.chapo, post.author, post.content, post.picture, post.slug, post.created_at, post.updated_at, post.user_id,tag.name
        FROM `post`
        JOIN `tag_post`
          ON post.id = tag_post.post_id
        JOIN `tag`
          ON tag_post.tag_id = tag.id
          WHERE tag.slug = ? GROUP BY post.id ORDER BY created_at DESC";
        
        $database = new Database();
        $database->getPdo();
        $req = $database->parametersQuery($requete,$slugTag,Post::class);
        
        return $req;
    }

        
    /**
     * getPostByCat
     * Return the list of post with $slugCategory as parameter
     * @param  mixed $slugCategory
     * @return array
     */
    public function getPostByCat($slugCategory):array
    {
        
        $requete = "SELECT post.id, post.title, post.chapo, post.author, post.content, post.picture, post.slug, post.created_at, post.updated_at, post.user_id,category.name
        FROM `post`
        JOIN `post_cat`
          ON post.id = post_cat.post_id_post
        JOIN `category`
          ON post_cat.category_id_cat = category.id
          WHERE category.slug = ? GROUP BY post.id ORDER BY created_at DESC";
        
        $database = new Database();
        $database->getPdo();
        $req = $database->parametersQuery($requete,$slugCategory,Post::class);
        
        return $req;
    }

        
    /**
     * addPost
     * Add ONE post to DB with (object)$newPost infos
     * @param  Post $newPost     
     */
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
        
        $database = new DataBase();
        $database->getPdo();        
        $database->modificationQuery($requete,$arrayPost);

        return $database->getPdo()->lastInsertId();
        
    }

        
    /**
     * updatePost
     * Request to update infos of (object)$post
     * @param  array $post     
     */
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
      $database = New Database();
      $database->getPdo();
      $database->modificationQuery($requete,$arrayPost);     
        
    }

        
    /**
     * deletePost
     * Delete ONE post by id $idPost
     * @param  array $idPost     
     */
    public function deletePost($idPost)
    {
      $requete = "DELETE FROM post where id= ?";
              
      $database = new Database();
      $database->getPdo();
      $database->parametersQuery($requete,$idPost,Post::class);      
      
    }
}