<?php 

//AbstractRepository

namespace App\Repository;




abstract class AbstractRepository
{    
    
    public function addElement()
    {
        return "addElement";
    }
    public function updateElement()
    {
        return "updateElement";
    }
    public function deleteElement()
    {
        return "deleteElement";
    }
}