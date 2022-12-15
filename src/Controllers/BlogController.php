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
            $repoPost = new PostRepository();
            $dataPost = $repoPost->getAllPost();
            $repoCategory = new CategoryRepository();
            $dataCategory = $repoCategory->getAllCategory();
            $repoTag = new TagRepository();
            $dataTag = $repoTag->getAllTag();           
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
            $repoPost = new PostRepository();
            $dataPostbyCat = $repoPost->getPostByCat($category);
            $repoCategory = new CategoryRepository();           
            $categorySelected= $repoCategory->getOneCategory($category)[0]->getName();//Get category's name, who inform which tag is selected
            $dataCategory = $repoCategory->getAllCategory(); 
            $repoTag = new TagRepository();
            $dataTags = $repoTag->getAllTag();
                    
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
            $repoPost = new PostRepository();
            $dataPostbyTag = $repoPost->getPostByTag($tag);
            $repoTag = new TagRepository();
            $tagSelected= $repoTag->getOneTag($tag)[0]->getName();//Get tag's name, who inform which tag is selected
            $dataTags = $repoTag->getAllTag();
            $repoCategory = new CategoryRepository();
            $dataCategory = $repoCategory->getAllCategory();
                       
        }
        catch (PDOException $exception) {
            $this->getContainer()->get('log')->error($exception);
        }     
        $content = $this->render('postby.html.twig', ['posts' => $dataPostbyTag, 'categories' => $dataCategory,'tags' => $dataTags,'selected'=>$tagSelected]);
        return new Response($content);
    }
}