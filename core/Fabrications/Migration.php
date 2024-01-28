<?php

namespace Forge\core\Fabrications;

use Forge\core\Console\Console;

class Migration
{
    private $tableName, $migrationPath;
    private Console|null $console = null;

    public function __construct($tableName)
    {
        $this->tableName = strtolower($tableName);
        $this->migrationPath = dirname(__DIR__, 2) . '/database/migrations/';
        $this->console = new Console();
    }

    public function createMigration()
    {

        if (file_exists($this->migrationPath . 'create' . '_' . $this->tableName . '.php')) {
            exit('Migration already exists!!' . PHP_EOL . 'Please check the database/migrations directory');
        }

        if (!file_exists(
            explode("/migrations/", $this->migrationPath)[0]
        )) {
            mkdir(
                explode("/migrations/", $this->migrationPath)[0]
            );
            if (!file_exists($this->migrationPath)) {
                mkdir($this->migrationPath);
            }
        }

        if (!file_exists($this->migrationPath)) {
            mkdir($this->migrationPath);
        }

        if (
            file_exists(
                $this->migrationPath . 'create' . '_' . $this->tableName . 's.php'
            )
        ) {
            $this->console->log('Migration already exists!!' . PHP_EOL . 'Please check the database/migrations directory');
        }


        $migration = fopen($this->migrationPath . 'create' . '_' . $this->tableName . 's.php', 'w');
        $migrationContent = <<<EOL
        <?php

        use Forge\core\Migration;
        use Forge\core\Schema;

        return new class extends Migration
        {
            public function up()
            {
                Schema::create('{$this->tableName}s', function (\$table) {
                    \$table->id();
                });
            }

            public function down()
            {
                \$this->dropTable('{$this->tableName}s');
            }
        };
        EOL;

        if (file_exists($this->migrationPath . 'create' . '_' . $this->tableName . '.php')) {
            exit('Migration already exists!!' . PHP_EOL . 'Please check the database/migrations directory');
        }
        fwrite($migration, $migrationContent);
        fclose($migration);

        $this->console->log('Migration created successfully!!' . PHP_EOL);
        $this->console->log('Created file: ' . $this->migrationPath . 'create' . '_' . $this->tableName . '.php' . PHP_EOL);
        $this->console->log('Please check the database/migrations directory' . PHP_EOL);
    }
}
