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
      $database = new Database();
      $database->getPdo();
      $req = $database->query($requete,User::class);
      
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
      
      $database = new DataBase();
      $database->getPdo();        
      $database->modificationQuery($requete,$arrayCategory);
      
    }
    //checking if email or nickname already used (SQL constraint)
    public function ExistingUser($user)
    {
        $requete = "SELECT * FROM user where email= :email or nickname = :nickname";
               
        $database = new Database();
        $database->getPdo();        
        $arrayUser = array(
          "nickname" => $user->getNickname(),
          "email"=> $user->getEmail());
        $req = $database->parametersQuery($requete,$arrayUser,User::class);
        
        if($req[0] != false)
        {
          return true;
        }
        else
          return false;        
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
                                 
      $database = New Database();
      $database->getPdo();
      $database->modificationQuery($requete,$arrayUser);
    }

    public function getOneUser($idUser)
    {
        $requete = "SELECT * FROM user where id= ?";
               
        $database = new Database();
        $database->getPdo();
        $req = $database->parametersQuery($requete,$idUser,User::class);
        
        return $req;
    }
    
    public function deleteUser($idUser)
    {
      $requete = "DELETE FROM user where id= ?";
              
      $database = new Database();
      $database->getPdo();
      $database->parametersQuery($requete,$idUser,User::class);
      
    }

    public function findUser($user)
    {
        
        $requete = "SELECT * FROM user where email= ?";               
        $database = new Database();
        $database->getPdo();
        $datauser = $database->parametersQuery($requete,$user,User::class);        

        return $datauser;      
    }
}