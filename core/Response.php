<?php

namespace Forge\core;

use Closure;

class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function json(array $data, int $code)
    {
        $this->setStatusCode($code);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function render($view, $params = [])
    {
        $view = str_replace('.', '/', $view);
        $view = Application::$ROOT_DIR . "/views/$view.php";
        extract($params);
        ob_start();
        include_once $view;
        ob_end_flush();
        return $this;
    }

    public function flash($key, $message)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION[$key] = $message;
        return $this;
    }

    public function redirect($url)
    {
        header('Location: ' . $url);
        return $this;
    }

    public function __destruct()
    {
        if (isset($_SESSION['message'])) {
            unset($_SESSION['message']);
        }
    }
}
