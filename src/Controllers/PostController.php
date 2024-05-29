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
        $stmt = $this->db->prepare("SELECT * FROM posts ORDER BY id DESC");
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($posts) {
            echo json_encode(['posts' => $posts]);
        } else {
            echo json_encode(['posts' => []]);
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