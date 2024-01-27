<?php

namespace Forge\core\utils;


use Closure;
use Forge\core\Console\Console;

class MigrationParser
{

    private string $migrationPath, $migrationFolder;
    private array $attributes = [];
    private string $tableName;
    private Console|null $console = null;

    public function __construct()
    {
        $this->migrationFolder = dirname(__DIR__, 2) . '/database/migrations';
        $this->console = new Console();
    }

    public function getMigration(string $table)
    {
        $this->migrationPath = $this->migrationFolder . '/create_' . $table . '.php';
        $this->tableName = $table;
        if (file_exists($this->migrationPath)) {
            return $this->migrationPath;
        }
        throw new \Exception('Migration file not found!!');
    }

    public function getAttributes()
    {
        $contents = file_get_contents(
            $this->migrationPath
        );

        preg_match_all(
            '/\$this->table\(\'' . $this->tableName . '\'\)->create\((.*?)\);/s',
            $contents,
            $matches
        );

        $attributes = explode(',', $matches[1][0]);

        foreach ($attributes as $attribute) {
            $attribute = trim($attribute);
            $attribute = str_replace("'", "", $attribute);
            $attribute = str_replace(")", "", $attribute);
            $attribute = str_replace("(", "", $attribute);
            $attribute = str_replace(";", "", $attribute);
            $attribute = explode(',', $attribute);
            $this->attributes[$attribute[0]] = $attribute[1];
        }

        return $this->attributes;
    }

    public function getMigrationFolder()
    {
        return $this->migrationFolder;
    }

    public function getMigrationPath()
    {
        return $this->migrationPath;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getAttributesClosure()
    {
        return Closure::bind(
            function () {
                return $this->attributes;
            },
            $this
        );
    }

    public function getAllMigrations()
    {
        $migrations = [];
        $files = scandir($this->migrationFolder);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $migrations[] = $file;
            }
        }
        return $migrations;
    }

    public function getMigrationName(string $migration)
    {
        $migration = explode('_', $migration);
        $migration = explode('.', $migration[1]);
        return $migration[0];
    }

    public function getMigrationClass(string $migration)
    {
        $migration = explode('_', $migration);
        $migration = explode('.', $migration[1]);
        return $migration[0];
    }

    public function getAllTablesWithAttributes()
    {
        $migrations = $this->getAllMigrations();
        $tables = [];
        foreach ($migrations as $migration) {
            $this->getMigration(
                $this->getMigrationName($migration)
            );
            $tables[$this->getMigrationName($migration)] = $this->getAttributes();
        }
        return $tables;
    }

    protected function createMigration(
        $migrationName,
        $migrationPath,
        $migrationFolder
    ) {
        $migrationName = $migrationName . '.php';
        $migrationPath = $migrationPath . '/' . $migrationName;
        $migrationFolder = $migrationFolder . '/' . $migrationName;
        if (!file_exists($migrationFolder)) {
            $migrationFile = fopen($migrationPath, 'w');
            $migrationContent = '<?php' . PHP_EOL . PHP_EOL;
            $migrationContent .= 'namespace Forge\migrations;' . PHP_EOL . PHP_EOL;
            $migrationContent .= 'use Forge\core\Migration;' . PHP_EOL . PHP_EOL;
            $migrationContent .= 'class ' . $migrationName . ' extends Migration' . PHP_EOL;
            $migrationContent .= '{' . PHP_EOL . PHP_EOL;
            $migrationContent .= '    public function up()' . PHP_EOL;
            $migrationContent .= '    {' . PHP_EOL . PHP_EOL;
            $migrationContent .= '    }' . PHP_EOL . PHP_EOL;
            $migrationContent .= '    public function down()' . PHP_EOL;
            $migrationContent .= '    {' . PHP_EOL . PHP_EOL;
            $migrationContent .= '    }' . PHP_EOL . PHP_EOL;
            $migrationContent .= '}' . PHP_EOL;
            fwrite($migrationFile, $migrationContent);
            fclose($migrationFile);
            $this->console->log('Migration created successfully!!' . PHP_EOL . 'Please run php forge migrate to migrate your database!!');
        } else {
            $this->console->log('Migration already exists!!' . PHP_EOL . "File path: $migrationFolder");
        }
    }

    protected function createModel(
        $modelName,
        $modelPath,
        $modelFolder
    ) {
        $modelName = $modelName . '.php';
        $modelPath = $modelPath . '/' . $modelName;
        $modelFolder = $modelFolder . '/' . $modelName;
        if (!file_exists($modelFolder)) {
            $modelFile = fopen($modelPath, 'w');
            $modelContent = '<?php' . PHP_EOL . PHP_EOL;
            $modelContent .= 'namespace Forge\models;' . PHP_EOL . PHP_EOL;
            $modelContent .= 'use Forge\core\Model;' . PHP_EOL . PHP_EOL;
            $modelContent .= 'class ' . $modelName . ' extends Model' . PHP_EOL;
            $modelContent .= '{' . PHP_EOL . PHP_EOL;
            $modelContent .= '    protected $table = \'' . strtolower($modelName) . 's\';' . PHP_EOL . PHP_EOL;
            $modelContent .= '    protected $fillable = [];' . PHP_EOL . PHP_EOL;
            $modelContent .= '}' . PHP_EOL;
            fwrite($modelFile, $modelContent);
            fclose($modelFile);
            $this->console->log('Model created successfully!!');
        } else {
            $this->console->log('Model already exists!!');
        }
    }
}
