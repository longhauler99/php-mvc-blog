<?php
namespace App\Controllers;

use App\Controller;
use App\Core\Connection;
use App\Utils\Helper;
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

    public function fetchModal(): void
    {
        session_start();

        $data = [];
        $errors = [];

        if (!isset($_SESSION['user_id']))
        {
            $errors[] = 'Unauthorized';
            Helper::escalateErrors($errors);
        }

        $action = $_POST["action"] ?? '';

        $form_template = '';

        if ($action == 'add')
        {
            $data['modal_title'] = 'New Post';
            $columns = $this->getTableColumns();

            foreach ($columns as $column) {
                $columnName = $column['COLUMN_NAME'];
                $columnType = $column['DATA_TYPE'];

                if (in_array($columnName, ["id", "created_at", "updated_at"])) {
                    continue;
                } elseif ($columnType === 'varchar' && $columnName === 'title') {
                    $label = 'Title';
                    $form_template .= '
                    <div class="mb-3">
                        <label for="' . $columnName . '" class="form-label">' . $label . '</label>
                        <input type="text" class="form-control post-' . $columnName . '" name="' . $columnName . '" id="' . $columnName . '">
                    </div>
                ';
                } elseif ($columnType === 'text' && $columnName === 'content') {
                    $label = 'Description';
                    $form_template .= '
                    <div class="mb-3">
                        <label for="' . $columnName . '" class="form-label">' . $label . '</label>
                        <textarea class="form-control post-' . $columnName . '" name="' . $columnName . '" id="' . $columnName . '" rows="5"></textarea>
                    </div>
                ';
                }
            }


            $data['form_template'] = $form_template;
        }
        elseif ($action == 'edit') {
            $data['modal_title'] = 'Edit Post';
        }

        echo json_encode(['success' => 'Request sent successfully', 'data' => $data]);
    }

    public function createPost(): void
    {
        session_start();

        $errors = [];

        if(!isset($_SESSION['user_id']))
        {
            $errors[] = 'Unauthorized';
            Helper::escalateErrors($errors);
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $user_id = $_SESSION['user_id'];

            $title = $_POST['title'] ?? null;
            $content = $_POST['content'] ?? null;

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
            Helper::escalateErrors($errors);
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

    private function getTableColumns(): false|array
    {
        $table_name = "posts";
        $stmt = $this->db->prepare("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table");
        $stmt->bindParam(':table', $table_name);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}