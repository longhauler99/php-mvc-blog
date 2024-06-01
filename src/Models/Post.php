<?php
namespace App\Models;

use App\Core\Connection;
use PDO;

class Post
{
    private PDO $db;
    public function __construct($config)
    {
        $this->db = Connection::connect($config);
    }

    public function createPost($user_id, $title, $content): bool
    {
        $stmt = $this->db->prepare("INSERT INTO posts (user_id, title, content) VALUES (:user_id, :title, :content)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);

        return $stmt->execute();
    }

    public function updatePost($post_id, $title, $content): bool
    {
            $stmt = $this->db->prepare("UPDATE `posts` SET title = :title, content = :content WHERE id = :post_id");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':post_id', $post_id);

            return $stmt->execute();
    }

    public function getOnePost($id)
    {
        $stmt = $this->db->prepare("SELECT title, content FROM `posts` WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllPosts($user_id): false|array
    {
        $stmt = $this->db->prepare(
            "SELECT posts.*, users.username
                        FROM posts
                            INNER JOIN users ON posts.user_id = users.id
                        WHERE user_id = :user_id
                            ORDER BY id DESC");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTableColumns(): false|array
    {
        $table_name = "posts";
        $stmt = $this->db->prepare("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table");
        $stmt->bindParam(':table', $table_name);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}