<?php

namespace Forge\core\Services;

use Forge\core\Application;

class Cookie
{

    public function __construct()
    {
        // check is session is not started
        Application::$app->session->start();

        $APP_NAME = $_ENV['APP_NAME'];

        // check if user is logged in
        if (!Application::$app->session->get('user')) {
            // check if user has a cookie
            $token = $this->get(
                strtolower(substr($APP_NAME, 0, 3) . '_atoken')
            );

            if ($token) {
                // check if token is in the database
                Application::$app->db->table("remember_tokens");
                $token = Application::$app->db->where('token', "=", $token)->first();
                if ($token) {
                    // check if token is expired
                    if (strtotime($token['expires_at']) > time()) {
                        // get user
                        Application::$app->db->table("users");
                        $user = Application::$app->db->where('id', "=", $token['user_id'])->first();
                        if ($user) {
                            // set user in session
                            Application::$app->session->set('user', $user);
                        }
                    }
                }
            }
        }
    }

    public static function set($name, $value, $expiry)
    {
        if (setcookie($name, $value, time() + $expiry, '/')) {
            return true;
        }
        return false;
    }

    public static function get($name)
    {
        return $_COOKIE[$name] ?? false;
    }

    public static function remove($name)
    {
        self::set($name, '', time() - 1);
    }
}
