<?php

namespace Aleyg\Core\Security;

class Auth 
{
    public function passwordhashing($password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        return $hash;
    }
    
    public function tokenCreate()
    {
       return bin2hex(random_bytes(16));
    }
    
    public function tokenChecking($user_token, $session_token) : bool
    {
        if($user_token === $session_token)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function checkingRole($role) : bool
    {
        if(isset($role) && $role === 'admin')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}