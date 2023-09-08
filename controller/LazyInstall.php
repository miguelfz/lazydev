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
        $this->set("tables", $this->getTables());
        $this->set("lazyjson", $this->getLazyJson());
    }

    public function table()
    {
        $this->setTitle('Criar modelo');
        $dbSchema = $this->getDbSchema();
        $this->set("dbSchema", $dbSchema);
        $this->set("tableSchema", $dbSchema[$this->getParam(0)]);
        $this->set("lazyjson", $this->getLazyJson());
    }
    
    public function post_table()
    {
        $table = filter_input(INPUT_POST, 'model');
        $dbSchema = $this->getDbSchema();
        if (filter_input(INPUT_POST, 'createmodel')) {
            $this->cretateModel($table, $dbSchema);
        }
        if (filter_input(INPUT_POST, 'createcontroller')) {
            echo ('cria controller');
        }
        exit;
    }

    private function cretateModel(string $table, $dbSchema)
    {
        $tableSchema = $dbSchema[$table];
        $pks = $tableSchema['pk'];
        $fks = $tableSchema['fk'];
        $fields = $tableSchema['fields'];
        $model = ucfirst($tableSchema['name']);
        $handle = fopen("../model/$model.php", 'w');
        if (!$handle) {
            new Msg("Não foi possível criar o model $model. Verifique as permissões do diretório", 3);
            return;
        }
        fwrite($handle, "<?php");
        fwrite($handle, "{$this->nlt(0)}namespace Lazydev\Model;\n");
        fwrite($handle, "{$this->nlt(0)}final class $model extends \Lazydev\Core\Record{ \n");
        $pk = count($pks) > 1 ? '[\'' . implode('\', \'', $pks) . '\']' : '\'' . array_shift($pks) . '\'';
        # contantes
        fwrite($handle, $this->nlt(1) . "const TABLE = '$table'; # tabela de referência");
        fwrite($handle, $this->nlt(1) . "const PK = $pk; # chave primária");
        # atributos do modelo
        fwrite($handle, $this->nlt(1));
        foreach ($schema as $f) {
            fwrite($handle, $this->nlt(1) . 'public $' . $f->Field . ';');
        }

        # fim da classe        
        fwrite($handle, $this->nlt(0) . "}");
        fclose($handle);
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
            $selected = 'selected';
            foreach ($tableschema as $f) {
                $f->InputType = '';
                $f->selected = '';
                if (strstr($f->Type, 'int(1)')) {
                    $f->InputType = 'checkbox';
                } elseif (strstr($f->Type, 'int')) {
                    $f->InputType = 'number';
                } elseif (strpos($f->Type, 'decimal') !== false) {
                    $f->InputType = 'number';
                } elseif ($f->Type == 'date') {
                    $f->InputType = 'date';
                } elseif ($f->Type == 'datetime') {
                    $f->InputType = 'now';
                } elseif ($f->Type == 'time') {
                    $f->InputType = 'time';
                } elseif (strstr($f->Type, 'text')) {
                    $f->InputType = 'html';
                }
                else{
                    $f->InputType = 'text';
                    $f->selected = $selected;
                    $selected = '';
                }

                if ($f->Key == 'PRI') {
                    $pk[] = $f->Field;
                }
                $f->fk = 0;
                foreach ($fks as $fk) {
                    if ($fk->fk == $f->Field) {
                        $f->fk = $fk->reftable;
                    }
                }
            }
            $db[$table->name]['fields'] = (array)$tableschema;
            $db[$table->name]['pk'] = $pk;
            $db[$table->name]['fk'] = (array)$fks;
            $db[$table->name]['name'] = $table->name;
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
