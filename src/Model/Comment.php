<?php

namespace App\Model;

/**
 * Comment class represent a blog post and his attributs
 */
class Comment 
{
    private $id;        
    private $content;
    private $comment_status;
    private $post_id;
    private $user_id;
    private $nickname;

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    

    /**
     * Get the value of content
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of comment_status
     */ 
    public function getComment_status()
    {
        return $this->comment_status;
    }

    /**
     * Set the value of comment_status
     *
     * @return  self
     */ 
    public function setComment_status($comment_status)
    {
        $this->comment_status = $comment_status;

        return $this;
    }

    /**
     * Get the value of post_id
     */ 
    public function getPost_id()
    {
        return $this->post_id;
    }

    /**
     * Set the value of post_id
     *
     * @return  self
     */ 
    public function setPost_id($post_id)
    {
        $this->post_id = $post_id;

        return $this;
    }

    /**
     * Get the value of user_id
     */ 
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */ 
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get the value of user_id
     */ 
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */ 
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }
}
