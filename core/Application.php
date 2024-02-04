<?php

namespace Forge\core;

use Dotenv\Dotenv;

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
    }

    private function loadEnv(string $path)
    {
        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();
    }

    public function loadWebRoutes()
    {
        require_once self::$ROOT_DIR . '/routes/web.php';
    }

    public function run()
    {
        $this->router->resolve();
    }
}
