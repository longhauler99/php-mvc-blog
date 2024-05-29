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
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
//            var_dump($_POST);
            $username = $_POST['username'] ?? null;
            $email = $_POST['email'] ?? null;
            $password = md5($_POST['password'] ?? null);
            $password2 = md5($_POST['password2'] ?? null);

            if(empty($username)
                || empty($email)
                || empty($password)
                || empty($password2))
            {
                http_response_code(400);
                echo json_encode(['error', 'Invalid input data']);
                return;
            }


            if($password == $password2)
            {
                $stmt = $this->db->prepare("INSERT INTO users (username, email, password, password2) VALUES (:username, :email, :password, :password2)");

                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':password2', $password2);

                if ($stmt->execute())
                {
                    http_response_code(201);
                    echo json_encode(['success' => 'User registered successfully']);
                }
                else
                {
                    http_response_code(202);
                    echo json_encode(['error' => 'Something went wrong']);
                }
            }
            else
            {
                http_response_code(203);
                echo json_encode(['error' => 'Passwords do not match']);
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