<?php
namespace App\Utils;

use Random\RandomException;

class Helper
{
    public static function escalateErrors($errors, $msg): void
    {
        if($msg)
        {
            $errors[] = $msg;
        }
        else
        {
            echo json_encode(['errors' => "No message was passed!"]);
        }

        if (!empty($errors))
        {
            echo json_encode(['errors' => $errors]);
        }
    }

    public static function sanitizeInput($input): string
    {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    public static function redirect($url): void
    {
        header("Location: $url");
        exit();
    }

    public static function generateCSRFToken()
    {
        if (session_status() == PHP_SESSION_NONE)
        {
            session_start();
        }
        if (empty($_SESSION['csrf_token']))
        {
            try {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            } catch (RandomException $e) {
            }
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCSRFToken($token): bool
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token))
        {
            unset($_SESSION['csrf_token']);
            return true;
        }

        return false;
    }
}
