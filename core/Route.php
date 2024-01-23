<?php

namespace Forge\core;

class Route
{
    public static function __callStatic($name, $arguments)
    {
        return Application::$app->router->$name(...$arguments);
    }
}
