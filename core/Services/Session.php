<?php

namespace Forge\core\Services;

class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function __construct()
    {
        self::start();
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public static function destroy()
    {
        session_destroy();
    }
}
