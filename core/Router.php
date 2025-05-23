<?php

namespace Forge\core;

use Closure;

class Router
{

    protected array $routes = [];
    public Request $request;
    public Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;

        return $this;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;

        return $this;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }
        $method = strtolower($this->request->method());
        $callback = $this->routes[$method][$path] ?? false;

        // check if the route is parameterized using :param in the route
        // $parametrizedRoute = false;

        // if(
        //     strpos($path, ':') !== false
        //     && strpos($path, ':') === 0
        // ) {
        //     $parametrizedRoute = true;
        // }

        if ($callback === false) {
            foreach ($this->routes[$method] as $route => $callback) {
                $params = [];
                $route = preg_replace('/\//', '\/', $route);
                $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
                $route = '/^' . $route . '$/';

                if (preg_match($route, $path, $matches)) {
                    foreach ($matches as $key => $match) {
                        if (is_string($key)) {
                            $params[$key] = $match;
                        }
                    }

                    $this->request->setParams($params);
                } else {
                    $this->response->setStatusCode(404);
                    echo "The [" . $method . "] route $path does not exist";
                    exit;
                }
            }
        }

        if (is_string($callback)) {
            if (strpos($callback, '@') === false) {
                return $this->renderView($callback);
            }

            $callback = explode('@', $callback);

            if (
                strpos(strtolower($callback[0]), 'controller') === false
            ) {
                $callback[0] = $callback[0] . 'Controller';
            }

            $callback[0] = ucfirst($callback[0]);
            try {
                $controller = "App\\Controllers\\$callback[0]";

                if (!file_exists(Application::$ROOT_DIR . "/app/Controllers/$callback[0].php")) {
                    $callback[0] = lcfirst($callback[0]);
                    if (!file_exists(Application::$ROOT_DIR . "/app/Controllers/$callback[0].php")) {
                        $this->response->setStatusCode(404);
                        echo "The controller $callback[0] does not exist";
                        exit;
                    } else {

                        $this->response->setStatusCode(404);
                        echo "The controller $callback[0] does not exist";
                        exit;
                    }
                }
                $controller = new $controller();

                if (!method_exists($controller, $callback[1])) {
                    $callback[1] = lcfirst($callback[1]);
                    if (!method_exists($controller, $callback[1])) {
                        $this->response->setStatusCode(404);
                        echo "The method $callback[1] does not exist";
                        exit;
                    } else {
                        $this->response->setStatusCode(404);
                        echo "The method $callback[1] does not exist";
                        exit;
                    }
                }

                return $controller->{$callback[1]}(
                    $this->request,
                    $this->response
                );
            } catch (\Exception $e) {
                $this->response->setStatusCode(500);
                echo $e->getMessage();
                exit;
            }
        }

        if (is_array($callback)) {



            $callback[0] = new $callback[0]();

            if (!method_exists($callback[0], $callback[1])) {
                $this->response->setStatusCode(404);
                echo "The method $callback[1] does not exist";
                exit;
            }

            $returnData = $callback[0]->{$callback[1]}(
                $this->request,
                $this->response
            );

            if (is_string($returnData)) {
                die($returnData);
            }

            return $returnData;
        }

        if (is_object($callback)) {
            $returned = $callback($this->request, $this->response);
            if (is_string($returned)) {
                die($returned);
            }

            if (is_array($returned)) {
                echo json_encode($returned);
            }

            if (is_object($returned)) {
                return $returned;
            }
        }


        echo Closure::fromCallable($callback)->call($this->request, $this->response);
    }


    public function renderView($view)
    {
        $frontendFramework = $_ENV['FRONTEND_FRAMEWORK'] ?? 'default';
        $frontendBuildTool = $_ENV['FRONTEND_BUILD_TOOL'] ?? 'default';
        $frontendDirName = $_ENV['FRONTEND_DIR_NAME'] ?? 'views';

        if (
            $frontendFramework === 'default'
            && $frontendBuildTool === 'default'
        ) {
            $this->renderViewWithoutLayout($view, $frontendDirName ?? 'views');
        } else {
            $this->renderMixins($view);
        }
    }

    public function renderViewWithoutLayout($view, $dir = 'views')
    {
        ob_start();

        include_once Application::$ROOT_DIR . "/$dir/$view.php";

        ob_end_flush();
    }

    public function renderMixins($view)
    {
        $frontendDistPath = Application::$ROOT_DIR . '/public/dist';
        $requestedFile = $frontendDistPath . $view;

        if (file_exists($requestedFile)) {
            $mimeType = mime_content_type($requestedFile);
            header("Content-Type: $mimeType");
            readfile($requestedFile);
            exit;
        }

        $indexFile = $frontendDistPath . "/index.html";
        if (file_exists($indexFile)) {
            header("Content-Type: text/html");
            readfile($indexFile);
            exit;
        }

        $this->response->setStatusCode(404);
        echo "404 Not Found";
        exit;
    }
}
