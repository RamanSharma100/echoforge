<?php

namespace Forge\core;

class Application
{
    public Router $router;
    public Request $request;
    public Response $response;
    public static Application $app;
    public static string $ROOT_DIR;

    public function __construct()
    {
        self::$ROOT_DIR = dirname(__DIR__);
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router(
            $this->request,
            $this->response
        );
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
