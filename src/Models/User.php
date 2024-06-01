<?php
namespace App\Models;

use App\Core\Connection;

use PDO;
use PDOException;

class User
{
    private PDO $db;
    public function __construct($config)
    {
        $this->db = Connection::connect($config);
    }

    public function createUser($name, $email, $password): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash the password

        $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        return $stmt->execute();
    }

    public function userExists($email): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM `users` WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function authenticateUser($email, $password): bool
    {
        // Prepare the SQL statement to prevent SQL injection
        $stmt = $this->db->prepare("SELECT * FROM `users` WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password']))
        {
            return true;
        }
        //
        return false;
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT id, username, email FROM `users` WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($userId, $username, $email, $password = null): bool
    {
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id");
            $stmt->bindParam(':password', $hashedPassword);
        } else {
            $stmt = $this->db->prepare("UPDATE users SET username = :username, email = :email WHERE id = :id");
        }

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $userId);

        return $stmt->execute();
    }

    public function deleteUser($userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $userId);

        return $stmt->execute();
    }
}