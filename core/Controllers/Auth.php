<?php

namespace Forge\core\Controllers;

use Forge\core\Application;

class Auth
{
    public static function attempt(string $email, string $password)
    {
        $db = Application::$app->db;
        $db->table('users');
        $user = $db->where('email', "=", $email)->first();
        if (!$user) {
            return false;
        }

        if (password_verify($password, $user['password'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            unset($user['password']);
            $_SESSION['user'] = $user;
            return true;
        }

        return false;
    }

    public static function user()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user'] ?? null;
    }

    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['user']);
    }

    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user']);
    }

    public static function guest()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !isset($_SESSION['user']);
    }
}
