<?php

namespace Config;

use Aleyg\Core\Security\Auth;
use App\Controllers\BlogController;
use App\Controllers\HomePageController;
use App\Controllers\PostController;
use App\Controllers\admin\AdminController;
use App\Controllers\admin\AdminUserController;
use App\Controllers\admin\AdminTagController;
use App\Controllers\admin\AdminPostController;
use App\Controllers\admin\AdminCategoryController;
use App\Controllers\admin\AdminCommentController;

use App\Controllers\RegisterController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

use App\Controllers;


Class RoutesPath{

        static function getRoutes()
        {
            $routes = new RouteCollection();

            

            $routes->add('index', new Route('/',[
            '_controller' =>[HomePageController::class,'index'],
            'authorization'=>'authorized'
            ]));


            $routes->add('signup', new Route('/signup',[
            '_controller' =>[RegisterController::class,'signup']
            ]));
            $routes->add('login', new Route('/login',[
            '_controller' =>[RegisterController::class,'login']
            ]));
            $routes->add('logout', new Route('/logout',[
            '_controller' =>[RegisterController::class,'leaveSession']
            ]));

            $routes->add('blog', new Route('/blog',[
            '_controller' =>[BlogController::class,'getAllPost']
            ]));
            $routes->add('postbycat', new Route('/blog/category/{slug_category}',[            
            '_controller' =>[BlogController::class,'getPostByCat']
            ]));
            $routes->add('postbytag', new Route('/blog/tag/{slug_tag}',[            
            '_controller' =>[BlogController::class,'getPostByTag']
            ]));           
            $routes->add('post', new Route('/blog/{slug_post}',[            
            '_controller' =>[PostController::class,'getPostController']
            ]));
            

            
            // //*********************************************************************************************/
            // //***********************************Partie admin  *******************************************/
            // //*******************************************************************************************/
                        
            $routes->add('admin', new Route('/admin',[
            '_controller' =>[AdminController::class,'index']
            ]));


            $routes->add('loginAdmin', new Route('/loginAdmin',[
            '_controller' =>[RegisterController::class,'loginAdmin']
            ]));             
            
            
            // //**************************************POST************************************************/
            
            
            $routes->add('admin_posts', new Route('/admin/posts',[
            '_controller' =>[AdminPostController::class,'getAllPostController']
            ]));
            $routes->add('admin_newpost', new Route('/admin/new/post',[
            '_controller' =>[AdminPostController::class,'newPostController']
            ]));
            
            $routes->add('admin_updatepost', new Route('/admin/update/post/{slug_post}',[            
            '_controller' =>[AdminPostController::class,'updatePostController']
            ]));
            $routes->add('admin_onepost', new Route('/admin/post/{slug_post}',[
            '_controller' =>[AdminPostController::class,'getPostController']
            ]));            
            $routes->add('admin_deletepost', new Route('/admin/delete/post/{id}',[
            '_controller' =>[AdminPostController::class,'deletePostController']
            ]));            

            // //*************************************TAG**********************************************/


            $routes->add('admin_tags', new Route('/admin/tags',[
            '_controller' =>[AdminTagController::class,'getAllTagController']
            ]));                 
            $routes->add('admin_newtag', new Route('/admin/new/tag',[
            '_controller' =>[AdminTagController::class,'newTagController']
            ]));
            $routes->add('admin_updatetag', new Route('/admin/update/tag/{slug_tag}',[
            '_controller' =>[AdminTagController::class,'updateTagController']
            ]));
            $routes->add('admin_deletetag', new Route('/admin/delete/tag/{id}',[
            '_controller' =>[AdminTagController::class,'deleteTagController']
            ]));


            // //*************************************CATEGORY****************************************/


            $routes->add('admin_categories', new Route('/admin/categories',[
            '_controller' =>[AdminCategoryController::class,'getAllCategoryController']
            ]));
            $routes->add('admin_newcategory', new Route('/admin/new/category',[
            '_controller' =>[AdminCategoryController::class,'newCategoryController']
            ]));
            $routes->add('admin_updatecategory', new Route('/admin/category/{slug_category}',[
            '_controller' =>[AdminCategoryController::class,'updateCategoryController']
            ]));
            $routes->add('admin_deletecategory', new Route('/admin/delete/category/{id}',[
            '_controller' =>[AdminCategoryController::class,'deleteCategoryController']
            ]));


            // //**************************USER********************************************/


            $routes->add('admin_users', new Route('/admin/users',[
            '_controller' =>[AdminUserController::class,'getAllUserController']
            ]));
            $routes->add('admin_newuser', new Route('/admin/new/user',[
            '_controller' =>[AdminUserController::class,'newUserController']
            ]));
            $routes->add('admin_updateuser', new Route('/admin/user/{id}',[
            '_controller' =>[AdminUserController::class,'updateUserController']
            ]));
            $routes->add('admin_deleteuser', new Route('/admin/delete/user/{id}',[
            '_controller' =>[AdminUserController::class,'deleteUserController']
            ]));


            // //***********************************COMMENT*********************************/  
            
            
            $routes->add('admin_validcomment', new Route('/admin/post/{slug_post}/{id}',[
            '_controller' =>[AdminCommentController::class,'validCommentController']
            ]));            
            $routes->add('admin_posts_comment', new Route('/admin/posts/comment',[
            '_controller' =>[AdminPostController::class,'getAllPostController']
            ])); 

            return $routes;
        }       
 
}

