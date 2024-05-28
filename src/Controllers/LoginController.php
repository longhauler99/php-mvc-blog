<?php
namespace App\Controllers;

use App\Controller;
use App\Core\Connection;

class LoginController extends Controller
{
    protected $db;
    public function __construct($config)
    {
        parent::__construct();
        $this->db = Connection::connect($config);
    }
    public function index(): void
    {
        $this->render('login');
    }

    public function register(): void
    {

        if(isset($_POST['signup-button']))
        {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = md5($_POST['pwd']);
            $password2 = md5($_POST['pwd2']);

            if($password == $password2)
            {
                $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);

                if ($stmt->execute())
                {
                    header("Location: /");
                    exit;
                }
                else
                {
                    echo "Error: " . $stmt->errorInfo()[2];
                }
            }
            else
            {
                $this->render('login', ['error' => 'Passwords do not match']);
            }
        }
    }

    public function login(): void
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $username = $_POST['username'];
            $password = md5($_POST['pwd']);

            if($this->authenticateUser($username, $password))
            {
                session_start();
                $_SESSION['acc_login'] = md5($username.$password);

                header("Location: /home");
                exit;
            }
            else
            {
                $this->render('login', ['error' => 'Invalid username or password']);
            }
        }
        else
        {
            header("Location: /");
            exit;
        }
    }

    public function logout(): void
    {
        session_start();
        if(isset($_POST['logout-btn']))
        {
            session_destroy(); // or unset($_SESSION['acc_login']);

            header("Location: /");
            exit;
        }
    }

    private function authenticateUser($username, $password): bool
    {
        $stmt = $this->db->query("SELECT UserName, Password FROM `users` WHERE UserName = '$username' AND Password = '$password'");

        return $stmt->rowCount() == 1;
    }
}