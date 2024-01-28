<?php

namespace Forge\core\Fabrications;

use Forge\core\Console\Console;

class Controller
{
    private $controllerName, $controllerPath;

    private Console|null $console = null;

    public function __construct($controllerName)
    {
        $this->controllerName = strtolower($controllerName);
        $this->controllerPath = dirname(__DIR__, 2) . '/app/Controllers/';
        $this->console = new Console();
    }

    public function createController()
    {
        if (!file_exists($this->controllerPath)) {
            mkdir($this->controllerPath);
        }

        if (file_exists($this->controllerPath . $this->controllerName . '.php')) {
            $this->console->log('Controller already exists!!' . PHP_EOL . 'Please check the app/Controllers directory');
            exit();
        }

        $controller = fopen($this->controllerPath . $this->controllerName . '.php', 'w');
        $controllerContent = "<?php \n\nnamespace App\Controllers;\n\nuse Forge\core\Controller;\n\nclass " . ucfirst($this->controllerName) . " extends Controller\n{\n \n \n}";

        fwrite($controller, $controllerContent);
        fclose($controller);

        $this->console->log('Controller created successfully!!' . PHP_EOL);
        $this->console->log('Created file: ' . $this->controllerPath . $this->controllerName . '.php' . PHP_EOL);
        $this->console->log('Please check the app/Controllers directory' . PHP_EOL);
    }
}
