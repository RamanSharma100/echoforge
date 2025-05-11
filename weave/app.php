<?php

namespace Forge;

class App extends core\Console\Kernel
{

    private $container = [];

    protected $httpKernel;


    public function __construct()
    {
        parent::__construct();
    }

    public function singleton(string $key, callable $callback)
    {
        $this->container[$key] = $callback;
    }

    public function make()
    {
        $this->registerCommands();
    }

    public function serve()
    {

        $this->app->run();
    }
}

$app = new App();

return $app;
