<?php 

namespace App\Controllers;

use Aleyg\Core\Pattern\Container;
use Aleyg\Core\DataBase\DataBase;
use Config\RoutesPath;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Environment;
use Twig\TwigFunction;
use Twig\Loader\FilesystemLoader;



abstract class AbstractController
{    
    protected $database;
    private ?Container $container = NULL;

    public function __construct(Container $container)
    {  
        $this->database = new DataBase($container);
        $this->container = $container;                            
        
        
        $this->container->get('twig')->addFunction(
            new TwigFunction('path', function($routeName,?array $params= []){  
                  
                return $this->container->get('urlGenerator')->generate($routeName,$params);
            })
        );                          
        $this->container->get('twig')->addFunction(new TwigFunction('asset',function($asset){
                                        return '/assets/'.$asset;

        }));

    }
    

    public function render(string $template, array $params = []): string
    {
        
        return $this->container->get('twig')->render($template,$params);
    }


    /**
     * Get the value of container
     */ 
    protected function getContainer()
    {
        return $this->container;
    }


    protected function checkingAuth()
    {
        $session = new session();
        if($session->get('auth')!= NULL && $session->get('role') == ROLE_SUPER_USER)
        {
            return true;            
        }
        else
            return false;
    }   
}