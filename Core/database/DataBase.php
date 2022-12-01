<?php

namespace Aleyg\Core\Database;
use \PDO;
use App\Model\Tag;
use App\Model\Post;
use App\Model\User;
use App\Model\Comment;
use App\Model\Category;




class DataBase {
    
    private $db_name;
    private $db_user;
    private $db_password;
    private $db_host;
    private $pdo;

    public function __construct($db_name = 'p5_blog',$db_user = 'root', $db_password = '', $db_host = 'localhost' )
    {
        
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_password = $db_password;
        $this->db_host = $db_host;
    }

    private function getLog()
    {
        $log = new Logger('log');
        $log->pushHandler(new StreamHandler(__ROOT__.'TMP/blog.log', Level::Warning));
        return $log;
    }

    public function getPdo()
    {
        if($this->pdo === null)
        {
            $pdo = new PDO('mysql:dbname=p5_blog;host=localhost','root','');            
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //$pdo->query("SET NAMES UTF8");
            $this->pdo = $pdo;
        } 
        return $this->pdo;     
    }
    
    /**
     * query
     *
     * @param  mixed $statement : request send by Ctrl
     * @param  mixed $class : declare the class type for $data
     * @return void
     */
    public function query($statement,$class)
    {
        $req= $this->getPdo()->query($statement);
        $data = $req->fetchAll(PDO::FETCH_CLASS, $class);
        
        return $data;
    }
    
    /**
     * parametersQuery
     *
     * @param  mixed $statement
     * @param  array $attributes represent query parameter
     * @param  mixed $class 
     * @return void
     */
    public function parametersQuery($statement,$attributes, $class)
    {
               
        $req = $this->getPdo()->prepare($statement);        
        $req->execute($attributes);
        $req->setFetchMode(PDO::FETCH_CLASS, $class);
        
        
        //$count number of rows and test which fetch need to be use.
        $count = $req->rowCount();      
        if($count<=1)
        {            
            $data = $req->fetch();
            $data = array(0 => $data);                     
        }
        else
        {
            $data = $req->fetchAll();                      
        }     
        return $data;
    }

    public function modificationQuery($statement,$attributes)
    {
        try
        {
            $req = $this->getPdo()->prepare($statement);            
            $req->execute($attributes);       
        }
        catch (PDOException $exception) 
        {
            $this->getLog();            
        }
    }    
}