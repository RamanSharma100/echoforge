<?php

namespace Forge\core;

use Dotenv\Dotenv;
use Forge\core\Services\Cookie;
use Forge\core\Services\Session;

function dd($data)
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    die();
}

function view($view, $params = [])
{
    header('Content-Type: text/html');

    $view = str_replace('.', '/', $view);
    $view = Application::$ROOT_DIR . "/views/$view.php";
    extract($params);
    ob_start();
    include_once $view;
    return ob_end_flush();
}

class Application
{
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $db;
    public Session $session;
    public Cookie $cookie;
    public static Application $app;
    public static string $ROOT_DIR;

    public function __construct()
    {
        $this->loadEnv(dirname(__DIR__));
        self::$ROOT_DIR = dirname(__DIR__);
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router(
            $this->request,
            $this->response
        );
        $this->db = new Database();
        $this->session = new Session();
        $this->cookie = new Cookie();
    }

    private function loadEnv(string $path)
    {
        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();
    }

    public function loadWebRoutes()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once self::$ROOT_DIR . '/routes/web.php';
    }

    public function run()
    {
        $this->router->resolve();
    }
}
