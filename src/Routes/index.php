<?php

use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Router;

$router = new Router();
$router->get('/', LoginController::class, 'index');
$router->post('/register', LoginController::class, 'register');
$router->post('/login', LoginController::class, 'login');
$router->post('/logout', LoginController::class, 'logout');
$router->get('/home', HomeController::class, 'index');

try
{
    $router->dispatch();
}
catch (Exception $e)
{
    echo $e->getMessage();
}

