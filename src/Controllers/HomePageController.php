<?php
//ControllerHomePage

declare (strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Aleyg\Core\Mail\Mailer;
use Aleyg\Core\Pattern\Container;


class HomePageController extends AbstractController
{    
    
    public function index(Request $request): Response
    {
        
        $session = new session();

        if($request->getMethod() ==='POST')
        {
            $mail = new mailer();
            $mail->sendEmail($request);
            return new Response($this->render('home.html.twig', ['title'=> 'Developper needed', 'register'=> 'false']));
        }
        else
        {            
            return new Response($this->render('home.html.twig', ['title'=> 'Developper needed', 'register'=> 'false','session' =>$session]));           
                    
        }
        
    }
}
