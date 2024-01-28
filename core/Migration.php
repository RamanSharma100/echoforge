<?php

namespace Forge\core;


class Migration extends Database
{

    public function create($table, $columns)
    {
        $this->upsertTable($table, $columns);
    }

    public function drop($table)
    {
        $this->dropTable($table);
    }
}
