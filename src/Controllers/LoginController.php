<?php
namespace App\Controllers;

use App\Controller;
use App\Core\Connection;
use PDO;
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

            $errors = [];

            if (empty($username) || empty($email) || empty($password)) // Validate the input data
            {
                $errors[] = 'All fields must be filled';
                $this->EscalateErrors($errors);
            }
            elseif($this->userExists($email)) // Check if user already exists
            {
                $errors[] = 'Email already exists';
                $this->EscalateErrors($errors);
            }
            else
            {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash the password

                try // Insert the new user into the database
                {
                    $stmt = $this->db->prepare("INSERT INTO users (UserName, Email, Password) VALUES (?, ?, ?)");
                    $stmt->execute([$username, $email, $hashedPassword]);

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
                if($this->userExists($email))
                {
                    if($this->authenticateUser($email, $password))
                    {
                        session_start();
                        $_SESSION['acc_login'] = password_hash($password, PASSWORD_BCRYPT);
                        $_SESSION['user_id'] = $this->getUserInfo($email)['id'];
                        $_SESSION['username'] = $this->getUserInfo($email)['username'];
                        $_SESSION['email'] = $this->getUserInfo($email)['email'];

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

    private function authenticateUser($email, $password): bool
    {
        try
        {
            // Prepare the SQL statement to prevent SQL injection
            $stmt = $this->db->prepare("SELECT password FROM `users` WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Check if the user exists and fetch the stored hashed password
            if ($stmt->rowCount() == 1)
            {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $storedPassword = $row['password'];

                // Verify the provided password against the stored hashed password
                if (password_verify($password, $storedPassword))
                {
                    return true;
                }
            }
        }
        catch (PDOException $e)
        {
            // Log the error message or handle it accordingly
            error_log('Database query error: ' . $e->getMessage());
        }

        // Return false if authentication fails
        return false;
    }

    private function userExists($email): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM `users` WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    private function getUserInfo($email)
    {
        $stmt = $this->db->prepare("SELECT id, username, email FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?? null;
    }

    public function EscalateErrors($errors): void // Error propagation
    {
        if (!empty($errors))
        {
            echo json_encode(['errors' => $errors]);
        }
    }
}