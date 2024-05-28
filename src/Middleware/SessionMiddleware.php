<?php
namespace App\Middleware;

class SessionMiddleware
{
    public static function check(): void
    {
        session_start();
        if (!isset($_SESSION['acc_login'])) {
            header("Location: /");
            exit;
        }
    }
}