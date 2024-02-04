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
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'INT',
            'length' => 11,
            'auto_increment' => false,
            'primary_key' => false,
        ];
        return $this;
    }

    public function auto_increment()
    {
        end($this->columns);
        $key = key($this->columns);
        $this->columns[$key]['auto_increment'] = true;
        reset($this->columns);
        return $this;
    }

    public function primary()
    {
        end($this->columns);
        $key = key($this->columns);
        $this->columns[$key]['primary_key'] = true;
        reset($this->columns);
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

    public function integer($columnName, $length = 11)
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'INT',
            'length' => $length,
        ];
        return $this;
    }

    public function text($columnName, $length = 255)
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'TEXT',
            'length' => $length,
        ];
        return $this;
    }

    public function longText($columnName)
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'LONGTEXT',
        ];
        return $this;
    }

    public function enum($columnName, $values = [])
    {
        $this->columns[] = [
            'name' => $columnName,
            'type' => 'ENUM',
            'values' => $values,
        ];
        return $this;
    }

    public function default($value)
    {
        end($this->columns);
        $key = key($this->columns);
        $this->columns[$key]['default'] = $value;
        reset($this->columns);
        return $this;
    }

    public function timestamps()
    {
        $this->columns[] = [
            'name' => 'created_at',
            'type' => 'TIMESTAMP',
            'default' => 'CURRENT_TIMESTAMP',
        ];
        $this->columns[] = [
            'name' => 'updated_at',
            'type' => 'TIMESTAMP',
            'default' => 'CURRENT_TIMESTAMP',
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


    public function unique()
    {
        end($this->columns);
        $key = key($this->columns);
        $this->columns[$key]['unique'] = true;
        reset($this->columns);
        return $this;
    }

    public function nullable()
    {
        end($this->columns);
        $key = key($this->columns);
        $this->columns[$key]['nullable'] = true;
        reset($this->columns);
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

            if (isset($column['default'])) {
                if (
                    $column['default'] == 'CURRENT_TIMESTAMP'
                ) {
                    $this->SQL .= " DEFAULT " . $column['default'];
                } else {
                    $this->SQL .= " DEFAULT '" . $column['default'] . "'";
                }
            }

            if (isset($column['values'])) {
                $this->SQL .= " (";
                foreach ($column['values'] as $value) {
                    $this->SQL .= "'" . $value . "',";
                }
                $this->SQL = rtrim($this->SQL, ",");
                $this->SQL .= ")";
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
