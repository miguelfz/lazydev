<?php

namespace Lazydev\Controller;

class LazyInstall extends \Lazydev\Core\Controller
{

    function __construct()
    {
        if (!\Lazydev\Core\Config::get("debug")) {
            die('O instalador só é permitido com o modo de debug habilitado no /config');
        }
    }

    public function inicio()
    {
        $this->setTitle('Iniciar Instalação');
        $db = [];
        $tables = $this->getTables();
        foreach ($tables as $t) {
            $db[$t->name] = $this->getTableSchema($t->name);
        }
        echo '<pre>';
        print_r($db);
        exit;
        $this->set("tables", $this->getTables());
        $this->set("lazyjson", $this->getLazyJson());
    }

    public function model()
    {
        $this->setTitle('Iniciar Instalação');
        $this->set("tables", $this->getTables());
        $this->set("lazyjson", $this->getLazyJson());
        $this->set("modelName", $this->getParam(0));
        $this->set("model", $this->getTableSchema($this->getParam(0)));
    }


    private function getLazyJson()
    {
        if (file_exists(__DIR__ . '/../lazy.json')) {
            return json_decode(file_get_contents(__DIR__ . '/../lazy.json'));
        }
        return [];
    }

    private function getTables()
    {
        return $this->query('SELECT table_name AS `name` FROM information_schema.tables WHERE table_schema = DATABASE()');
    }

    private function getPlural($nome)
    {
        if (substr($nome, -1) == "s") {
            return $nome;
        }
        if (substr($nome, -1) == "r") {
            return $nome . "es";
        }
        if (substr($nome, -1) == "m") {
            return substr($nome, 0, -1) . "ns";
        }
        if (substr($nome, -1) == "il") {
            return substr($nome, 0, -2) . "is";
        }
        if (substr($nome, -1) == "l") {
            return substr($nome, 0, -1) . "is";
        }
        return $nome . "s";
    }

    private function getTableSchema($table)
    {
        $tableschema = $this->query("DESCRIBE `$table`");
        $pk = [];
        foreach ($tableschema as $f) {
            if ($f->Key == 'PRI') {
                $pk[] = $f->Field;
            }
        }
        $tableschema['pk'] = $pk;
        $tableschema['fk'] = $this->getFKs($table);
        return $tableschema;
    }

    private function getFKs($table)
    {
        return $this->query("SELECT table_name AS `table`,  column_name AS  `fk`, 
        referenced_table_name AS `reftable`, referenced_column_name  AS `refpk` 
        FROM information_schema.key_column_usage
        WHERE referenced_table_name IS NOT NULL 
        AND TABLE_SCHEMA=(SELECT database() AS db)  AND `table_name`='$table'");
    }

    private function nlt($n = 1)
    {
        return "\n" . str_repeat(" ", 4 * $n);
    }
}
