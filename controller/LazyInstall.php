<?php

namespace Lazydev\Controller;

use Lazydev\Core\Msg;

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
        if (!\Lazydev\Core\Config::get('db_name')) {
            new Msg('Banco de dados não especificado no arquivo de configuração', 3);
        }
        echo '<pre>';
        print_r($this->getDbSchema());
        $this->set("db", $this->getDbSchema());
        $this->set("lazyjson", $this->getLazyJson());
    }

    public function model()
    {
        $this->setTitle('Iniciar Instalação');
        $this->set("tables", $this->getTables());
        $this->set("lazyjson", $this->getLazyJson());
        $this->set("modelName", $this->getParam(0));
    }


    private function getLazyJson()
    {
        if (file_exists(__DIR__ . '/../lazy.json')) {
            return json_decode(file_get_contents(__DIR__ . '/../lazy.json'));
        }
        return [];
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

    private function getDbSchema()
    {
        $db = [];
        $tables = $this->getTables();
        foreach ($tables as $table) {
            $tableschema = $this->query("DESCRIBE `$table->name`");
            $pk = [];
            $fks = $this->getFKs($table->name);
            foreach ($tableschema as $f) {
                if ($f->Key == 'PRI') {
                    $pk[] = $f->Field;
                }
                $f->fk = 0;
                foreach($fks as $fk){
                    if($fk->fk==$f->Field){
                        $f->fk = 1;
                    }
                }
            }
            $tableschema['pk'] = $pk;
            $tableschema['fk'] = $fks;
            $db[$table->name] = $tableschema;
        }
        return $db;
    }

    private function getTables()
    {
        return $this->query('SELECT table_name AS `name` FROM information_schema.tables WHERE table_schema = DATABASE()');
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
