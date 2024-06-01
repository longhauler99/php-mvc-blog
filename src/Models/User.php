<?php
namespace App\Models;

use App\Core\Connection;

use PDO;
class User
{

    private bool|null|PDO $db;
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
        $count = $stmt->fetchColumn();

        return $count > 0;
    }
}