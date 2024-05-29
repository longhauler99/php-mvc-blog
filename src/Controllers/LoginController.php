<?php
namespace App\Controllers;

use App\Controller;
use App\Core\Connection;
use PDOException;

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
        if($_SERVER['REQUEST_METHOD'] === 'POST') // Get the form data
        {
            $username = $_POST['username'] ?? null;
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if (empty($username) || empty($email) || empty($password)) // Validate the input data
            {
//                http_response_code(230); // Bad Request
                echo json_encode(['error' => 'All fields must be filled']);
            }
            elseif($this->userExists($username)) // Check if user already exists
            {
//                http_response_code(409); // Conflict
                echo json_encode(['error' => 'Username already exists']);
            }
            else
            {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Insert the new user into the database
                try {
                    $stmt = $this->db->prepare("INSERT INTO users (UserName, Email, Password) VALUES (?, ?, ?)");
                    $stmt->execute([$username, $email, $hashedPassword]);

                    // Send a success response
//                    http_response_code(201); // Created
                    echo json_encode(['success' => 'User registered successfully']);
                } catch (PDOException $e) {
                    // Send an error response if the insert fails
//                    http_response_code(500); // Internal Server Error
                    echo json_encode(['error' => 'Failed to register user: ' . $e->getMessage()]);
                }
            }
        }
        else
        {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['error' => 'Method Not Allowed']);
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
        $stmt = $this->db->query("SELECT username, Password FROM `users` WHERE username = '$username' AND Password = '$password'");

        return $stmt->rowCount() == 1;
    }

    private function userExists($username): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM `users` WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    public function Escalate($msgCode, $msgText)
    {
    }
}