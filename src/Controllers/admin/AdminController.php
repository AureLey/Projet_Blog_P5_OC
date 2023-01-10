<?php
//ControllerAdmin
declare (strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\AbstractController;
use App\Repository\Userrepository;
use Config\RoutesPath;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


class AdminController extends AbstractController
{    
    /**
     * DashBoard Acces checking if the user is granted else return to Homepage
     *
     * @return Response|RedirectResponse
     */
    public function index():Response|RedirectResponse
    {
        if($this->checkingAuth())
        {
            try
            {
                $repoPost = new UserRepository();
                $dataUsers = $repoPost->getAllUser();             
            }
            catch (PDOException $exception) {
                $this->getContainer()->get('log')->error($exception);
            }

            $content = $this->render('admin/user/userList.html.twig', ['users' => $dataUsers]);
            
            return new Response($content);        
        }
        else
            return new RedirectResponse($this->getContainer()->get('urlGenerator')->generate('index'));
        
    }
    
}