<?php

//UserRepository
declare (strict_types=1);

namespace App\Repository;
use App\Model\User;
use Aleyg\Core\DataBase\Database;



class UserRepository extends AbstractRepository
{
    public function getAllUser()
    {   
      $requete = "SELECT * FROM user";
      $db = new Database();
      $db->getPdo();
      $req = $db->query($requete,User::class);
      
      return $req;      
    }
    public function addUser($newuser)
    {
     
      $requete = "INSERT INTO `user`(`name`, `surname`,`nickname`, `email`, `password`, `role`) VALUES (:name, :surname,:nickname, :email, :password, :role)";        
      $arrayCategory = array(                  
        "name"=> $newuser->getName(),
        "surname"=> $newuser->getSurname(),
        "nickname" => $newuser->getNickname(),
        "email"=> $newuser->getEmail(),          
        "password"=> $newuser->getPassword(),
        "role"=> $newuser->getRole()
      );       
      
      $db = new DataBase();
      $db->getPdo();        
      $db->modificationQuery($requete,$arrayCategory);
      
    }
    //checking if email or nickname already used (SQL constraint)
    public function ExistingUser($user)
    {
        $requete = "SELECT * FROM user where email= :email or nickname = :nickname";
               
        $db = new Database();
        $db->getPdo();        
        $arrayUser = array(
          "nickname" => $user->getNickname(),
          "email"=> $user->getEmail());
        $req = $db->parametersQuery($requete,$arrayUser,User::class);
        
        if($req[0] != false)
        {
          return true;
        }
        else
        {
          return false;
        }
    }


    public function updateUser($user)
    {
      //Requete UPDATE
      $requete = "UPDATE user 
                  SET 
                  name = :name,
                  surname = :surname,
                  nickname = :nickname,                  
                  email = :email,                  
                  password = :password,                  
                  role = :role                 
                  WHERE id = :id";

      //Attributes Array            
      $arrayUser = array(        
        "name"=> $user->getName(),
        "surname"=> $user->getSurname(),
        "nickname" => $user->getNickname(),
        "email"=> $user->getEmail(),
        "password"=> $user->getPassword(),      
        "role"=> $user->getRole(),        
        "id"=> $user->getId()); 
                                 
      $db = New Database();
      $db->getPdo();
      $db->modificationQuery($requete,$arrayUser);
    }

    public function getOneUser($id)
    {
        $requete = "SELECT * FROM user where id= ?";
               
        $db = new Database();
        $db->getPdo();
        $req = $db->parametersQuery($requete,$id,User::class);
        
        return $req;
    }
    
    public function deleteUser($id)
    {
      $requete = "DELETE FROM user where id= ?";
              
      $db = new Database();
      $db->getPdo();
      $db->parametersQuery($requete,$id,User::class);
      
    }

    public function findUser($user)
    {
        
        $requete = "SELECT * FROM user where email= ?";               
        $db = new Database();
        $db->getPdo();
        $datauser = $db->parametersQuery($requete,$user,User::class);        

        return $datauser;      
    }
}