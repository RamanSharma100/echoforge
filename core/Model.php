<?php

namespace Forge\core;

class Model
{

    protected $table, $attributes, $migration;

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }


    public static function __callStatic($name, $arguments)
    {

        $reflection = new \ReflectionClass(get_called_class());

        $fillableProperties = $reflection->getDefaultProperties()['fillable'];
        $gaurdedProperties = $reflection->getDefaultProperties()['gaurded'];

        $table = strtolower(explode(
            '\\',
            get_called_class()
        )[2]) . 's';

        Application::$app->db->table($table);
        Application::$app->db->fillables($fillableProperties);
        Application::$app->db->gaurded($gaurdedProperties);

        return Application::$app->db->$name(...$arguments);
    }
}
