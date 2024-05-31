<?php
namespace App\Controllers;

use App\Controller;
use App\Core\Connection;
use PDO;
use PDOException;

class PostController extends Controller
{
    protected $db;
    public function __construct($config)
    {
        parent::__construct();
        $this->db = Connection::connect($config);
    }

    public function createPost(): void
    {
        session_start();

        $errors = [];

        if(!isset($_SESSION['user_id']))
        {
            $errors[] = 'Unauthorized';
            $this->EscalateErrors($errors);
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $user_id = $_SESSION['user_id'];

            $title = $_POST['title'] ?? null;
            $content = $_POST['description'] ?? null;

            if (empty($title) || empty($content)) // Validate the input data
            {
                echo json_encode(['error' => 'All fields must be filled']);
            }
            else
            {
                try // Insert the new post into the database
                {
                    $stmt = $this->db->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
                    $stmt->execute([$user_id, $title, $content]);

                    echo json_encode(['success' => 'Post created successfully']); // Send a success response
                }
                catch (PDOException $e)
                {

                    http_response_code(500); // Send an error response if the insert fails
                    echo json_encode(['error' => 'Failed to create post: ' . $e->getMessage()]);
                }
            }
        }
        else
        {
            echo json_encode(['error' => 'Method Not Allowed']);
        }
    }

    public function fetchPosts(): void
    {
        session_start();

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            $stmt = $this->db->prepare(
                "SELECT posts.*, users.username
                        FROM posts
                            INNER JOIN users ON posts.user_id = users.id
                        WHERE user_id = :user_id
                            ORDER BY id DESC");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($posts) {
                echo json_encode(['posts' => $posts]);
            } else {
                echo json_encode(['posts' => []]);
            }
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(['error' => 'User not authenticated']);
        }
    }

    public function editPost(): void
    {
        session_start();

        $errors = [];

        if(!isset($_SESSION['user_id']))
        {
            $errors[] = 'Unauthorized';
            $this->EscalateErrors($errors);
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (empty($_POST)) // Validate the input data
            {
                echo json_encode(['error' => 'All fields must be filled']);
            }
            else
            {
                try // Insert the new post into the database
                {
                    $stmt = $this->db->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
                    $stmt->execute([$user_id, $title, $content]);

                    echo json_encode(['success' => 'Post created successfully']); // Send a success response
                }
                catch (PDOException $e)
                {

                    http_response_code(500); // Send an error response if the insert fails
                    echo json_encode(['error' => 'Failed to create post: ' . $e->getMessage()]);
                }
            }
        }
    }

    public function EscalateErrors($errors): void // Error propagation
    {
        if (!empty($errors))
        {
            echo json_encode(['errors' => $errors]);
        }
    }
}