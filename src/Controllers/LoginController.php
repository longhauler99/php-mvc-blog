<?php
namespace App\Controllers;

use App\Controller;
use App\Core\Connection;
use App\Models\User;
use App\Utils\Helper;
use PDO;
use PDOException;

class LoginController extends Controller
{
//    protected PDO $db;
    private User $userModel;

    public function __construct($db)
    {
        parent::__construct();
        $this->userModel = new User($db);
    }
    public function index(): void
    {
        $this->render('login');
    }

    public function register(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') // Get the form data
        {
            $username = $_POST['username'] ?? null;
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            $errors = [];

            if (empty($username) || empty($email) || empty($password)) // Validate the input data
            {
                $errors[] = 'All fields must be filled';
                Helper::escalateErrors($errors);
            }
            elseif($this->userModel->userExists($email)) // Check if user already exists
            {
                $errors[] = 'Email already exists';
                Helper::escalateErrors($errors);
            }
            else
            {
                try // Insert the new user into the database
                {
                    $this->userModel->createUser($username, $email, $password);

                    echo json_encode(['success' => 'User registered successfully', 'redirect' => '/']);
                }
                catch (PDOException $e)
                {
                    echo json_encode(['error' => 'Failed to register user: ' . $e->getMessage()]);
                }
            }
        }
        else
        {
            echo json_encode(['error' => 'Method Not Allowed']);
        }
    }



    public function login(): void
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if (empty($email) || empty($password)) // Validate the input data
            {
                echo json_encode(['error' => 'All fields must be filled']);
            }
            else
            {
                if($this->userModel->userExists($email))
                {
                    if($this->userModel->authenticateUser($email, $password))
                    {
                        session_start();
                        $_SESSION['acc_login'] = password_hash($password, PASSWORD_BCRYPT);
                        $_SESSION['user_id'] = $this->userModel->getUserByEmail($email)['id'];
                        $_SESSION['username'] = $this->userModel->getUserByEmail($email)['username'];
                        $_SESSION['email'] = $this->userModel->getUserByEmail($email)['email'];

                        echo json_encode(['success' => 'You have logged in successfully', 'redirect' => '/home']);
                    }
                    else
                    {
                        echo json_encode(['error' => 'Invalid username or password']);
                    }
                }
                else
                {
                    echo json_encode(['error' => 'You don\'t have an account. Please sign up', 'redirect' => '/']);
                }
            }
        }
        else
        {
            echo json_encode(['error' => 'Method Not Allowed']);
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
}