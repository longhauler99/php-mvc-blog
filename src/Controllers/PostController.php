<?php
namespace App\Controllers;

use App\Controller;
use App\Core\Connection;
use App\Models\Post;
use App\Utils\Helper;
use PDO;
use PDOException;

class PostController extends Controller
{
    protected $db;

    protected Post $postModel;

    public function __construct($db)
    {
        parent::__construct();
        $this->postModel = new Post($db);
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
            $columns = $this->postModel->getTableColumns();

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
        elseif ($action == 'edit')
        {
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
            $title = Helper::sanitizeInput($_POST['title'] ?? null);
            $content = Helper::sanitizeInput($_POST['content'] ?? null);

            if (empty($user_id) || empty($title) || empty($content)) // Validate the input data
            {
                echo json_encode(['error' => 'All fields must be filled']);
            }
            else
            {
                try // Insert the new post into the database
                {
                    $this->postModel->createPost($user_id, $title, $content);

                    echo json_encode(['success' => 'Post created successfully']); // Send a success response
                }
                catch (PDOException $e)
                {

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

        if (isset($_SESSION['user_id']))
        {
            $user_id = $_SESSION['user_id'];

            if ($this->postModel->getAllPosts($user_id))
            {
                echo json_encode(['posts' => $this->postModel->getAllPosts($user_id)]);
            }
            else
            {
                echo json_encode(['posts' => []]);
            }
        }
        else
        {
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
}