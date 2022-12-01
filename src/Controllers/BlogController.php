<?php

//ControllerHomePage
declare (strict_types=1);

namespace App\Controllers;


use Aleyg\Core\Database\Database;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Config\RoutesPath;


class BlogController extends AbstractController
{
    
    /**
     * getAllPost
     *
     * @return twig render
     * Send a request to get All infos in the table
     */
    public function getAllPost()
    {
        
        try
        {
            $rp = new PostRepository();
            $dataPost = $rp->getAllPost();
            $rc = new CategoryRepository();
            $dataCategory = $rc->getAllCategory();
            $rt = new TagRepository();
            $dataTag = $rt->getAllTag();           
        }
        catch (PDOException $exception) {            
            $this->getContainer()->get('log')->error($exception);
        }        
        
        return new Response($this->render('blog.html.twig', ['posts' => $dataPost, 'categories' => $dataCategory,'tags' => $dataTag]));

    }

    /**
     * getPostByCat
     *
     * @return twig render
     * Send a request to get All post related to one category
     */
    public function getPostByCat($request)
    {        
        // create an array, and take slug of the category 
        $category = array(0 => $request->attributes->get('slug_category'));
            
        try
        {
            $rp = new PostRepository();
            $dataPostbyCat = $rp->getPostByCat($category);
            $rc = new CategoryRepository();           
            $categorySelected= $rc->getOneCategory($category)[0]->getName();//Get category's name, who inform which tag is selected
            $dataCategory = $rc->getAllCategory(); 
            $rt = new TagRepository();
            $dataTags = $rt->getAllTag();
                    
        }
        catch (PDOException $exception) {
            $this->getContainer()->get('log')->error($exception);
        }

        $content = $this->render('postby.html.twig', ['posts' => $dataPostbyCat, 'categories' => $dataCategory,'tags' => $dataTags,'selected'=>$categorySelected]);        
        return new Response($content);
    }
    /**
     * getPostByTag
     *
     * @return twig render
     * Send a request to get All post related to one tag
     */
    public function getPostByTag($request)
    {  
        // create an array, and take slug of the tag                        
        $tag = array(0 => $request->attributes->get('slug_tag'));         
        
        try
        {
            $rp = new PostRepository();
            $dataPostbyTag = $rp->getPostByTag($tag);
            $rt = new TagRepository();
            $tagSelected= $rt->getOneTag($tag)[0]->getName();//Get tag's name, who inform which tag is selected
            $dataTags = $rt->getAllTag();
            $rc = new CategoryRepository();
            $dataCategory = $rc->getAllCategory();
                       
        }
        catch (PDOException $exception) {
            $this->getContainer()->get('log')->error($exception);
        }     
        $content = $this->render('postby.html.twig', ['posts' => $dataPostbyTag, 'categories' => $dataCategory,'tags' => $dataTags,'selected'=>$tagSelected]);
        return new Response($content);
    }
}