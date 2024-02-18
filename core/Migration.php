<?php

namespace Forge\core;


class Migration extends Database
{

    public function createMigration($table, $columns)
    {
        $this->upsertTable($table, $columns);
    }

    public function drop($table)
    {
        $this->dropTable($table);
    }
}
