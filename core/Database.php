<?php

namespace Forge\core;

use Forge\core\Console\Console;

class Database
{

    private $DB_TYPE, $host, $user, $pass, $dbname, $pdo, $error, $stmt;

    private Console|null $console = null;

    protected function __construct()
    {
        $this->DB_TYPE = $_ENV['DB_TYPE'];
        $this->host = $_ENV['DB_HOST'];
        $this->user = $_ENV['DB_USER'];
        $this->pass = $_ENV['DB_PASS'];
        $this->dbname = $_ENV['DB_NAME'];
        $this->console = new Console();
    }

    protected function makeConnection()
    {
        if (
            !isset($this->DB_TYPE) ||
            !isset($this->host) ||
            !isset($this->user) ||
            !isset($this->pass) ||
            !isset($this->dbname)

        ) {
            $this->console->log('Database credentials not set!!' . PHP_EOL . 'Please set the database credentials in .env file!!');
            exit('Database credentials not set!!' . PHP_EOL . 'Please set the database credentials in .env file!!');
        }
        switch ($this->DB_TYPE) {
            case 'mysql':
                $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
                break;
            case 'pgsql':
                $dsn = 'pgsql:host=' . $this->host . ';dbname=' . $this->dbname;
                break;
            case 'sqlite':
                $dsn = 'sqlite:' . $this->dbname;
                break;
            default:
                exit('The Database type is not supported!! Try mysql, pgsql or sqlite');
                break;
        }

        try {
            $this->pdo = new \PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->console->log('Database connected successfully!!');
            return $this->pdo;
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            exit($e->getMessage());
        }
    }

    protected function closeConnection()
    {
        $this->pdo = null;
    }

    protected function query($sql, $params = [])
    {
        $stmt = $this->makeConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function select($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    protected function insert($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    protected function update($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    protected function delete($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    protected function dropTables(
        $tables = [],
        $cascade = false
    ) {
        $sql = 'DROP TABLE ';
        if ($cascade) {
            $sql .= 'CASCADE ';
        }
        $sql .= implode(', ', $tables);
        $stmt = $this->query($sql);
        return $stmt->rowCount();
    }

    protected function upsertTable(
        $table,
        $attributes = []
    ) {
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $table . ' (';
        $sql .= implode(', ', $attributes);
        $sql .= ')';
        $stmt = $this->query($sql);
        return $stmt->rowCount();
    }

    protected function createTable(
        $table,
        $attributes = []
    ) {
        $sql = 'CREATE TABLE ' . $table . ' (';
        $sql .= implode(', ', $attributes);
        $sql .= ')';
        $stmt = $this->query($sql);
        return $stmt->rowCount();
    }

    protected function dropTable(
        $table,
        $cascade = false
    ) {
        $sql = 'DROP TABLE ' . $table;
        if ($cascade) {
            $sql .= ' CASCADE';
        }
        $stmt = $this->query($sql);
        return $stmt->rowCount();
    }

    protected function alterTable(
        $table,
        $attributes = []
    ) {
        $sql = 'ALTER TABLE ' . $table . ' ';
        $sql .= implode(', ', $attributes);
        $stmt = $this->query($sql);
        return $stmt->rowCount();
    }

    protected function addColumn(
        $table,
        $attributes = []
    ) {
        $sql = 'ALTER TABLE ' . $table . ' ';
        $sql .= implode(', ', $attributes);
        $stmt = $this->query($sql);
        return $stmt->rowCount();
    }

    protected function dropColumn(
        $table,
        $attributes = []
    ) {
        $sql = 'ALTER TABLE ' . $table . ' ';
        $sql .= implode(', ', $attributes);
        $stmt = $this->query($sql);
        return $stmt->rowCount();
    }

    protected function renameColumn(
        $table,
        $attributes = []
    ) {
        $sql = 'ALTER TABLE ' . $table . ' ';
        $sql .= implode(', ', $attributes);
        $stmt = $this->query($sql);
        return $stmt->rowCount();
    }

    protected function renameTable(
        $table,
        $attributes = []
    ) {
        $sql = 'ALTER TABLE ' . $table . ' ';
        $sql .= implode(', ', $attributes);
        $stmt = $this->query($sql);
        return $stmt->rowCount();
    }

    protected function truncateTable(
        $table,
        $cascade = false
    ) {
        $sql = 'TRUNCATE TABLE ' . $table;
        if ($cascade) {
            $sql .= ' CASCADE';
        }
        $stmt = $this->query($sql);
        return $stmt->rowCount();
    }

    protected function getTableSchema(
        $table
    ) {
        $sql = 'SELECT * FROM ' . $table . ' LIMIT 1';
        $stmt = $this->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }



    protected function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    protected function endTransaction()
    {
        $this->pdo->commit();
    }

    protected function cancelTransaction()
    {
        $this->pdo->rollBack();
    }

    protected function debugDumpParams()
    {
        $this->pdo->debugDumpParams();
    }

    protected function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    protected function rowCount()
    {
        return $this->stmt->rowCount();
    }

    protected function columnCount()
    {
        return $this->stmt->columnCount();
    }

    protected function fetch()
    {
        return $this->stmt->fetch();
    }

    protected function fetchAll()
    {
        return $this->stmt->fetchAll();
    }

    protected function fetchColumn()
    {
        return $this->stmt->fetchColumn();
    }

    protected function fetchObject()
    {
        return $this->stmt->fetchObject();
    }

    protected function fetchAllObject()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    protected function fetchAssoc()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function fetchAllAssoc()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function fetchClass($class)
    {
        return $this->stmt->fetchAll(\PDO::FETCH_CLASS, $class);
    }

    protected function fetchAllClass($class)
    {
        return $this->stmt->fetchAll(\PDO::FETCH_CLASS, $class);
    }

    protected function fetchNamed()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_NAMED);
    }

    protected function fetchAllNamed()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_NAMED);
    }

    protected function fetchNum()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_NUM);
    }

    protected function fetchAllNum()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_NUM);
    }

    protected function fetchLazy()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_LAZY);
    }

    protected function fetchAllLazy()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_LAZY);
    }

    protected function fetchGroup()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_GROUP);
    }

    protected function fetchAllGroup()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_GROUP);
    }

    protected function fetchUnique()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_UNIQUE);
    }

    protected function fetchAllUnique()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_UNIQUE);
    }

    protected function fetchKeyPair()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    protected function fetchAllKeyPair()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    protected function fetchFunc()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_FUNC);
    }

    protected function fetchAllFunc()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_FUNC);
    }

    protected function fetchGroupColumn()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_GROUP | \PDO::FETCH_COLUMN);
    }
}
