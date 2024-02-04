<?php

namespace Forge\core\Controllers;

use Forge\core\Application;

class Controller
{
    public function view($view, $params = [])
    {
        return Application::$app->router->renderView($view, $params);
    }
}
