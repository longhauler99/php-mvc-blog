<?php
namespace App\Controllers;

use App\Controller;
use App\Core\Connection;
use App\Middleware\SessionMiddleware;
use App\Models\Journal;

class HomeController extends Controller
{
    protected $db;
    public function __construct($config)
    {
        parent::__construct();
        SessionMiddleware::check();
        $this->db = Connection::connect($config);
    }
    public function index(): void
    {
        $stmt = $this->db->query("SELECT * FROM `users`");
        $users = $stmt->fetchAll(\PDO::FETCH_OBJ);
        //
        $this->render('home', ['users' => $users]);
    }
}