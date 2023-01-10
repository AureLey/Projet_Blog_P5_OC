<?php

//UserRepository
declare (strict_types=1);

namespace App\Repository;
use App\Model\User;
use Aleyg\Core\DataBase\Database;



class UserRepository extends AbstractRepository
{

        
    /**
     * getAllUser
     * Return All User 
     * @return array
     */
    public function getAllUser(): array
    {   
      $requete = "SELECT * FROM user";
      $database = new Database();
      $database->getPdo();
      $req = $database->query($requete,User::class);
      
      return $req;      
    }

        
    /**
     * addUser
     * Add New USER and get infos from (object)$newuser
     * @param  User $newuser
     */
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
    
        
    /**
     * ExistingUser
     * checking if email or nickname already used (SQL constraint)
     * @param  User $user
     * @return bool
     */
    public function ExistingUser($user): bool
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

    
    /**
     * updateUser
     * Update User's infos from (object)$user
     * @param  User $user
     */
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

        
    /**
     * getOneUser
     * Get ONE User with his id $idUser
     * @param  array $idUser
     * @return array
     */
    public function getOneUser($idUser): array
    {
        $requete = "SELECT * FROM user where id= ?";
               
        $database = new Database();
        $database->getPdo();
        $req = $database->parametersQuery($requete,$idUser,User::class);
        
        return $req;
    }
    
        
    /**
     * deleteUser
     * Delete One User by his id $idUser
     * @param  array $idUser
     */
    public function deleteUser($idUser)
    {
      $requete = "DELETE FROM user where id= ?";
              
      $database = new Database();
      $database->getPdo();
      $database->parametersQuery($requete,$idUser,User::class);
      
    }

        
    /**
     * findUser
     * Find ONE User by his Email($user)
     * @param  array $user
     * @return array
     */
    public function findUser($user): array
    {
        
        $requete = "SELECT * FROM user where email= ?";               
        $database = new Database();
        $database->getPdo();
        $datauser = $database->parametersQuery($requete,$user,User::class);        

        return $datauser;      
    }
}