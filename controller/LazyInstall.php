<?php
namespace Lazydev\Controller;

class LazyInstall extends \Lazydev\Core\Controller{

    private $tableSchema;
    private $dbschema;

    function __construct()
    {
        if(!\Lazydev\Core\Config::get("debug")){
            die('O instalador só é permitido com o modo de debug habilitado no /config');
        }
    }
    
    public function inicio() {
        $this->setTitle('Iniciar Instalação');      
        $this->set("tables", $this->getTables());        
        $this->set("lazyjson", $this->getLazyJson());
    }

    private function getLazyJson() {
        if(file_exists(__DIR__ . '/../lazy.json')){
            return json_decode(file_get_contents(__DIR__ . '/../lazy.json'));
        }
        return [];
    }

    private function getTables() {
        return $this->query('SELECT table_name AS name FROM information_schema.tables WHERE table_schema = DATABASE()');
    }

    private function getPlural($nome) {
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

    private function getDbSchema() {
        if (is_null($this->dbschema)) {
            $data = $this->query('select database() as db');
            $db = $data[0]->db;
            $data = $this->query("SELECT table_name AS 'table',  column_name AS  'fk', 
            referenced_table_name AS 'reftable', referenced_column_name  AS 'refpk' 
            FROM information_schema.key_column_usage
            WHERE referenced_table_name IS NOT NULL 
            AND TABLE_SCHEMA='" . $db . "' ");
            $this->dbschema = $data;
        }
        return $this->dbschema;
    }

    private function getTableSchema($table) {
        if (!isset($this->tableSchema[$table])) {
            return $this->tableSchema[$table] = $this->query('describe ' . $table);
        }
        return $this->tableSchema[$table];
    }

    private function getPrimaryKey($tableName) {
        $tableschema = $this->getTableSchema($tableName);
        foreach ($tableschema as $f) {
            if ($f->Key == 'PRI') {
                return $f;
            }
        }
        return NULL;
    }

    private function nlt($n=1) {
        return "\n".str_repeat(" ",4*$n);
    }

}