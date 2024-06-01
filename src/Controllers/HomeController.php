<?php
namespace App\Controllers;

use App\Controller;
use App\Core\Connection;
use App\Middleware\SessionMiddleware;
use App\Models\Journal;

class HomeController extends Controller
{
    public function __construct($config)
    {
        parent::__construct();
        SessionMiddleware::check();
    }

    /**
     * @throws \Exception
     */
    public function index(): void
    {
        $this->render('home.php');
//        include '../Views/home.php';
    }
}