<?php

use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\PostController;
use App\Router;

$router = new Router();
$router->get('/', LoginController::class, 'index');
$router->post('/register', LoginController::class, 'register');
$router->post('/login', LoginController::class, 'login');
$router->get('/home', HomeController::class, 'index');

$router->post('/newpost', PostController::class, 'createPost');
$router->post('/editpost', PostController::class, 'updatePost');
$router->post('/deletepost', PostController::class, 'deletePost');
$router->get('/fetchPosts', PostController::class, 'fetchPosts');
$router->post('/fetchModal', PostController::class, 'fetchModal');

$router->post('/logout', LoginController::class, 'logout');

try
{
    $router->dispatch();
}
catch (Exception $e)
{
    echo $e->getMessage();
}

