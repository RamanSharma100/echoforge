<?php

namespace Forge\core\Schemas;

use Forge\core\Database;

class SQL
{
    private $table;
    private $columns = [];
    private $SQL = '';

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function id($columnName = 'id')
    {
        return $this->increments($columnName);
    }

    public function increments($columnName)
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'INT',
            'length' => 11,
            'auto_increment' => true,
            'primary_key' => true,
        ];
        return $this;
    }

    public function string($columnName, $length = 255)
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'VARCHAR',
            'length' => $length,
        ];
        return $this;
    }

    public function integer($columnName)
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'INT',
            'length' => 11,
        ];
        return $this;
    }

    public function text($columnName)
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'TEXT',
        ];
        return $this;
    }

    public function timestamp($columnName)
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'TIMESTAMP',
        ];
        return $this;
    }

    public function datetime($columnName)
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'DATETIME',
        ];
        return $this;
    }

    public function date($columnName)
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'DATE',
        ];
        return $this;
    }

    public function float($columnName, $totalDigits = 8, $decimalDigits = 2)
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'FLOAT',
            'total_digits' => $totalDigits,
            'decimal_digits' => $decimalDigits,
        ];
        return $this;
    }

    public function double($columnName, $totalDigits = 16, $decimalDigits = 4)
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'DOUBLE',
            'total_digits' => $totalDigits,
            'decimal_digits' => $decimalDigits,
        ];
        return $this;
    }

    public function decimal($columnName, $totalDigits = 10, $decimalDigits = 2)
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'DECIMAL',
            'total_digits' => $totalDigits,
            'decimal_digits' => $decimalDigits,
        ];
        return $this;
    }

    public function boolean($columnName)
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'BOOLEAN',
        ];
        return $this;
    }

    public function primary($columnName)
    {
        $this->columns[] = [
            'name' => $columnName,
            'primary_key' => true,
        ];
        return $this;
    }

    public function unique($columnName)
    {
        $this->columns[] = [
            'name' => $columnName,
            'unique' => true,
        ];
        return $this;
    }

    public function nullable($columnName)
    {
        foreach ($this->columns as &$column) {
            if ($column['name'] === $columnName) {
                $column['nullable'] = true;
            }
        }
        return $this;
    }

    public function getSchema()
    {
        return $this->SQL;
    }

    public function build()
    {
        $this->SQL = "CREATE TABLE IF NOT EXISTS `" . $this->table . "` (";
        foreach ($this->columns as $column) {
            $this->SQL .= "`" . $column['name'] . "` " . $column['type'];
            if (isset($column['length'])) {
                $this->SQL .= "(" . $column['length'] . ")";
            }
            if (isset($column['total_digits'])) {
                $this->SQL .= "(" . $column['total_digits'] . "," . $column['decimal_digits'] . ")";
            }
            if (isset($column['nullable'])) {
                $this->SQL .= " NULL";
            } else {
                $this->SQL .= " NOT NULL";
            }
            if (isset($column['auto_increment'])) {
                $this->SQL .= " AUTO_INCREMENT";
            }
            if (isset($column['primary_key'])) {
                $this->SQL .= " PRIMARY KEY";
            }
            if (isset($column['unique'])) {
                $this->SQL .= " UNIQUE";
            }
            $this->SQL .= ",";
        }
        $this->SQL = rtrim($this->SQL, ",");
        $this->SQL .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        return $this->SQL;
    }

    public function migrate()
    {
        $this->drop();
        $this->build();
        $this->execute();
    }

    public function drop()
    {
        $this->SQL = "DROP TABLE IF EXISTS `" . $this->table . "`;";
        $this->execute();
    }

    public function execute()
    {
        $db = new Database();
        $db->query($this->SQL);
    }
}
