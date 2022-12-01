<?php
//Class Tag

namespace App\Model;

class Tag
{
    private $id;
    private $name;
    private $slug;

    // public function __construct($id,$name ,$slug)
    // {
    //     if (isset($id))
    //     {
    //         $this->id = $id;
    //     }
    //     else{
    //         $this->id = NULL;
    //     }
        
    //     $this->name = $name;        
    //     $this->slug = $slug;        
    // }

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
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of slug
     */ 
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @return  self
     */ 
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }
}