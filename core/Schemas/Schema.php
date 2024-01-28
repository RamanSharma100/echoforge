<?php

namespace Forge\core\Schemas;

use Closure;

class Schema
{

    public static function create($table, Closure $callback)
    {
        $db = $_ENV['DB_TYPE'] || "mysql";

        if ($db == "mysql") {
            $blueprint = new SQL($table);
            $callback($blueprint);
            $blueprint->build();
            $blueprint->migrate();
        }
    }
}
