<?php

namespace Lazydev\Core;

use PDO;
use PDOException;

final class MariaDB
{

    private static $conn = NULL;
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $dbh;
    private $stmt;
    public $errorMsg = '';
    private $query = '';
    private $placeholders = [];
    private static $queryCounter = 1;

    function __construct()
    {
        $this->connect();
    }

    function close()
    {
        $this->stmt = NULL;
        $this->dbh = NULL;
    }

    private function connect()
    {
        if (!self::$conn) {
            $this->host = Config::get('db_host');
            $this->user = Config::get('db_user');
            $this->pass = Config::get('db_password');
            $this->dbname = Config::get('db_name');
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
            // Set options
            $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );
            try {
                $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
                self::$conn = $this->dbh;
            } catch (PDOException $e) {
                $this->errorMsg = $e->getMessage();
                file_put_contents('../log_errors.txt', date("d/M/Y H:i") . ' - ' . $this->errorMsg . "\r\n\n", FILE_APPEND | LOCK_EX);
                new Msg("ERRO<br>" . $this->errorMsg, 5);
                exit;
            }
        }
        $this->dbh = self::$conn;
    }

    public function query($query)
    {
        $withlimit = strpos(strtolower($query), 'limit') !== false ? true : false;
        if ($withlimit) {
            $query = str_replace('SELECT', 'SELECT SQL_CALC_FOUND_ROWS', $query);
        }
        $this->query = $query;
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = NULL)
    {
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
        $this->placeholders[$param] = $value;
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        $d = debug_backtrace();
        $files = '';
        foreach ($d as $value) {
            if (isset($value['class'])) {
                $files .= '<br>' . $value['class'] . $value['type'] . $value['function'] . '()';
            }
        }
        $query = htmlentities($this->pdo_sql_debug($this->query));
        new Msg('<strong>Query ' . self::$queryCounter++ . ':</strong><br>' . $query . '<br>' . $files, 5);

        try {
            return $this->stmt->execute();
        } catch (PDOException $p) {
            $this->errorMsg = $p->getMessage();
            file_put_contents(
                '../log_errors.txt',
                date("d/M/Y H:i") . "\n" . $this->query . "\n" . $this->errorMsg . "\n\n",
                FILE_APPEND | LOCK_EX
            );
            new Msg("ERRO<br>" . $this->query . "<br>" . $this->errorMsg, 5);
        }
    }

    public function getResults(string $class = NULL)
    {
        $this->execute();
        if ($class) {
            $rs = new ResultSet($this->stmt->fetchAll(PDO::FETCH_CLASS, $class));
        } else {
            $rs = new ResultSet($this->stmt->fetchAll(PDO::FETCH_OBJ));
        }
        $rs->rows = $rs->count();
        $withlimit = strpos(strtolower($this->query), 'limit') !== false ? true : false;
        if ($withlimit) {
            $this->query('SELECT FOUND_ROWS() as `rows`');
            $rs->rows = $this->getRow()->rows;
        }
        return $rs;
    }

    public function getRow(string $class = NULL)
    {
        if ($class) {
            $this->stmt->setFetchMode(PDO::FETCH_CLASS, $class);
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_CLASS);
        } else {
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    public function endTransaction()
    {
        return $this->dbh->commit();
    }

    public function cancelTransaction()
    {
        return $this->dbh->rollBack();
    }
    private function pdo_sql_debug($sql)
    {
        foreach ($this->placeholders as $k => $v) {
            $sql = str_replace($k, strval($v), $sql);
        }
        return $sql;
    }
}
