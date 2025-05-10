<?php

namespace Forge\core;

interface MigrationContract
{
    public function up();
}


class Migration extends Database implements MigrationContract
{

    public function up() {}

    public function createMigration($table, $columns)
    {
        $this->upsertTable($table, $columns);
    }

    public function drop($table)
    {
        $this->dropTable($table);
    }
}
