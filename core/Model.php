<?php

namespace Forge\core;

use Closure;
use Forge\core\Console\Console;
use Forge\core\utils\MigrationParser;

class Model extends Database
{

    protected $table, $attributes, $migration, $fillable = [], $gaurded = [];
    private Console|null $console = null;

    public function __construct()
    {
        $this->console = new Console();
        $migration = new MigrationParser(
            strtolower(
                get_class($this)
            ) . 's'
        );
        $this->migration = $migration->getMigration(
            strtolower(
                get_class($this)
            ) . 's'
        );
        $this->attributes = $migration->getAttributes();
    }


    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }
}
