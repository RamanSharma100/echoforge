<?php

namespace Forge\core\Fabrications;

use Forge\core\Console\Console;

class Model
{
    protected $tableName, $modelPath;

    private Console|null $console = null;
    private Migration $migration;
    private Controller $controller;

    public function __construct($tableName)
    {
        $this->tableName = strtolower($tableName);
        $this->migration = new Migration($tableName, []);
        $this->controller = new Controller($tableName);
        $this->modelPath = dirname(__DIR__, 2) . '/app/Models/';
        $this->console = new Console();
    }

    public function createModel()
    {

        if (file_exists($this->modelPath . ucfirst($this->tableName) . '.php')) {
            exit('Model already exists!!' . PHP_EOL . 'Please check the app/Models directory');
        }
        $model = fopen($this->modelPath . ucfirst($this->tableName) . '.php', 'w');
        $modelContent = "<?php \n\nnamespace App\Models;\n\nuse Forge\core\Model;\n\nclass " . ucfirst($this->tableName) . " extends Model\n{\n\n\n protected \$fillable = [];  \n\n\n protected \$gaurded = []; \n}";

        fwrite($model, $modelContent);
        fclose($model);

        $this->console->log('Model created successfully!!' . PHP_EOL);
        $this->console->log('Created file: ' . $this->modelPath . ucfirst($this->tableName) . '.php' . PHP_EOL);
        $this->console->log('Please check the app/Models directory' . PHP_EOL);
    }

    public function createModelWithMigration()
    {
        $this->migration->createMigration();
        $this->createModel();
    }

    public function createModelWithMigrationAndController()
    {
        $this->migration->createMigration();
        $this->createModel();
        $this->controller->createController();
    }
}
