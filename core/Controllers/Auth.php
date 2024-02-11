<?php

namespace Forge\core\Controllers;

use App\Models\User;
use Forge\core\Application;
use Forge\core\Services\Cookie;
use Forge\core\Services\Session;

class Auth
{
    public static function attempt(string $email, string $password)
    {
        $APP_NAME = $_ENV['APP_NAME'];
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


            $db->table('remember_tokens');
            $previousToken = $db->where('user_id', "=", $user['id'])->first();

            $cookie = new Cookie();
            $session = new Session();

            if ($previousToken) {
                if (strtotime($previousToken['expires_at']) < time()) {
                    $db->table('remember_tokens');
                    $db->where('id', "=", $previousToken['id'])->delete();
                } else {
                    $token = $previousToken['token'];

                    User::where('id', "=", $user['id'])->update([
                        'last_login' => date('Y-m-d H:i:s')
                    ]);

                    $cookie->set(strtolower(substr($APP_NAME, 0, 3) . '_atoken'), $token, 60 * 60 * 24 * 30);

                    $session->set('user', $user);

                    return true;
                }
            } else {
                $token = bin2hex(random_bytes(32));
                $db->table('remember_tokens');
                $db->create([
                    'user_id' => $user['id'],
                    'token' => $token,
                    'expires_at' => date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 30)
                ]);

                $cookie->set(strtolower(substr($APP_NAME, 0, 3) . '_atoken'), $token, 60 * 60 * 24 * 30);

                $session->set('user', $user);

                return true;
            }

            $token = bin2hex(random_bytes(32));
            $db->table('remember_tokens');
            $db->create([
                'user_id' => $user['id'],
                'token' => $token,
                'expires_at' => date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 30)
            ]);

            $cookie->set(strtolower(substr($APP_NAME, 0, 3) . '_atoken'), $token, 60 * 60 * 24 * 30);

            $session->set('user', $user);

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

    public static function token()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION[strtolower(substr($_ENV['APP_NAME'], 0, 3) . '_atoken')] ?? null;
    }

    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $cookie = new Cookie();
        $session = new Session();
        $token = self::token();
        $db = Application::$app->db;
        $db->table('remember_tokens');
        $db->where('token', "=", $token)->delete();
        $cookie->remove(strtolower(substr($_ENV['APP_NAME'], 0, 3) . '_atoken'));
        $session->remove('user');

        return true;
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
