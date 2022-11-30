<?php
//index.php
require_once __DIR__.'/../vendor/autoload.php';

use Monolog\Level;
use Monolog\Logger;
use Twig\Environment;
use Config\RoutesPath;
use Aleyg\Core\Security\Auth;
use Symfony\Component\Routing;
use Aleyg\Core\Pattern\Container;
use Twig\Loader\FilesystemLoader;
use Monolog\Handler\StreamHandler;
use Twig\Extension\DebugExtension;
use App\Controllers\HomePageController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;



//Define CONST for default User role and call it each time if necessary
define('ROLE_DEFAULT', 'user');
define('ROLE_SUPER_USER', 'admin');
define('__ROOT__', __DIR__.'/../');


//REQUEST
global $request;
$request = Request::createFromGlobals();

//RESPONSE
$response = new Response();

//ROUTES
$routes = RoutesPath::getRoutes();

//CONTEXT
$requestContext = new RequestContext();
$requestContext->fromRequest($request);

//URLMATCHER
$urlMatch = new UrlMatcher($routes, $requestContext);

//URLGENERATOR
$urlGenerator = new UrlGenerator($routes,$requestContext);

//TWIG
$loader = new FilesystemLoader('../src/View');
//$twig = new Environment($loader);

//MONOLOG
$log = new Logger('log');
$log->pushHandler(new StreamHandler(__ROOT__.'TMP/blog.log', Level::Warning));


//DEBUG TWIG
$twig = new Environment($loader, [
    'debug' => true
    
]);
$twig->addExtension(new DebugExtension());

//SESSION
$session = new Session();
if($session->get('auth') === true )
{        
    $twig->addGlobal('session', $session);
}    
    //CONTAINER 
    $data =[
        'twig' => $twig,        	
        'urlGenerator' => $urlGenerator,
        'log' => $log,	
    ];

//ADD DATA IN THE CONTAINER
$container = new Container($data);


try
{
    //rajoute le path du fichier dans la route
    $result = ($urlMatch->match($request->getPathInfo()));       
    $request->attributes->add($result);    
    $controller = $result['_controller'][0];
    $response = new $controller($container);
        
    $response = call_user_func([$response, $result['_controller'][1]], $request);
     
}
catch(ResourceNotFoundException $exception)
{
    $response->setContent("Page Not Foundddddddd");
    $response->setStatusCode(404);
}

$response->send();




















