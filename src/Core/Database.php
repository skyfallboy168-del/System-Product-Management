<?php

namespace Core;

use PDO;
use PDOException;

/*
 * PDO Database Class
 * Connects to the database, creates prepared statements,
 * binds values, and returns rows and results.
 * Implemented as a Singleton to ensure only one connection is made.
 */
class Database {
    private static $instance = null;

    private $host = DB_HOST;
    private $port = DB_PORT;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $charset = DB_CHARSET;

    private $dbh; // Database handler
    private $stmt; // Statement
    private $error;

    private function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname . ';charset=' . $this->charset;
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            // In a real app, you'd log this error, not display it
            die('Database Connection Error: ' . $this->error);
        }
    }

    /**
     * Get the Singleton instance of the Database class.
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Prepare statement with query.
     * @param string $sql
     */
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    /**
     * Bind values to the prepared statement using named parameters.
     * @param string $param e.g., ':name'
     * @param mixed $value
     * @param int|null $type PDO::PARAM_* constant
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Execute the prepared statement.
     * @return bool
     */
    public function execute() {
        return $this->stmt->execute();
    }

    /**
     * Get result set as array of objects.
     * @return array
     */
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    /**
     * Get single record as object.
     * @return object
     */
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    /**
     * Get row count.
     * @return int
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    /**
     * Get the ID of the last inserted row.
     * @return string
     */
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}
