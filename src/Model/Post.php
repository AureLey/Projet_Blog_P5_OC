<?php

namespace App\Model;

use DateTime;

/**
 * Post class represent a blog post and his attributs
 */
class Post 
{
    private $id;
    private $title;
    private $chapo;
    private $author;
    private $content;
    private $picture;
    private $slug;
    private $created_at;
    private $updated_at;
    private $user_id;

    // public function __construct($id, $title,$chapo,$author,$content,$picture,$slug,$created_at,$updated_at,$user_id)
    // {
    //     $this->id = $id;
    //     $this->title = $title;
    //     $this->chapo = $chapo;
    //     $this->author = $author;
    //     $this->content = $content;
    //     $this->picture = $picture;
    //     $this->slug = $slug;
    //     $this->created_at = $created_at;
    //     $this->updated_at = $updated_at;
    //     $this->user_id = $user_id;
    // }
    

    

    /**
     * Get the value of id
     */ 
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of chapo
     */ 
    public function getChapo(): ?string
    {
        return $this->chapo;
    }

    /**
     * Set the value of chapo
     * @return  self
     */ 
    public function setChapo($chapo): self
    {
        $this->chapo = $chapo;

        return $this;
    }

    /**
     * Get the value of author
     */ 
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @return  self
     */ 
    public function setAuthor($author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the value of content
     */ 
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */ 
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of img
     */ 
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * Set the value of img
     *
     * @return  self
     */ 
    public function setPicture($picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get the value of slug
     */ 
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @return  self
     */ 
    public function setSlug($slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the value of created_at
     */ 
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */ 
    public function setCreated_at($created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of updated_at
     */ 
    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @return  self
     */ 
    public function setUpdated_at($updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the value of user_id
     */ 
    public function getUser_id(): ?int
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */ 
    public function setUser_id($user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}