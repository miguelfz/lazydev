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
        $table = filter_input(INPUT_POST, 'table');
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
        # namespace e classe
        fwrite($handle, "<?php");
        fwrite($handle, "{$this->nlt(0)}namespace Lazydev\Model;\n");
        fwrite($handle, "{$this->nlt(0)}final class $model extends \Lazydev\Core\Record{ \n");
        $pk = count($pks) > 1 ? '[\'' . implode('\', \'', $pks) . '\']' : '\'' . array_shift($pks) . '\'';
        # contantes
        fwrite($handle, $this->nlt(1) . "const TABLE = '$table'; # tabela de referência");
        fwrite($handle, $this->nlt(1) . "const PK = $pk; # chave primária");
        # atributos do modelo
        fwrite($handle, $this->nlt(1));
        foreach ($fields as $f) {
            fwrite($handle, $this->nlt(1) . 'public $' . $f->Field . ';');
        }
        # métodos pertence a ...
        foreach ($fks as $f) {
            fwrite($handle, $this->nlt(1));
            fwrite($handle, $this->nlt(1) . '/**');
            fwrite($handle, $this->nlt(1) . '* ' . $model . ' pertence a ' . ucfirst($f->reftable));
            fwrite($handle, $this->nlt(1) . '* @return ' . ucfirst($f->reftable) . ' $' . $f->reftable);
            fwrite($handle, $this->nlt(1) . '*/');
            fwrite($handle, $this->nlt(1) . 'function get' . ucfirst($f->reftable) . $f->used . '(){');
            fwrite($handle, $this->nlt(2) . 'return $this->belongsTo(\'' . ucfirst($f->reftable) . '\',\'' . $f->fk . '\');');
            fwrite($handle, $this->nlt(1) . "}");
        }
        # métodos possui ...
        foreach ($dbSchema as $dbtables) {
            foreach ($dbtables['fk'] as $fk) {
                if ($fk->reftable == $table) {
                    fwrite($handle, $this->nlt(1));
                    fwrite($handle, $this->nlt(1) . '/**');
                    fwrite($handle, $this->nlt(1) . '* ' . $model . ' possui ' . ucfirst($fk->table) . '(s)');
                    fwrite($handle, $this->nlt(1) . '* @param Lazydev\Core\Criteria $criteria');
                    fwrite($handle, $this->nlt(1) . '* @return ' . ucfirst($fk->table) . '[] array de ' . ucfirst($fk->table));
                    fwrite($handle, $this->nlt(1) . '*/');
                    fwrite($handle, $this->nlt(1) . 'function get' . ucfirst($fk->table) . 's'  . $fk->used . '($criteria = NULL){');
                    fwrite($handle, $this->nlt(2) . 'return $this->hasMany(\'' . ucfirst($fk->table) . '\',\'' . $fk->fk . '\',$criteria);');
                    fwrite($handle, $this->nlt(1) . "}");
                }
            }
        }

        # métodos NxN
        foreach ($dbSchema as $dbtables) {
            $ffk = '';
            foreach ($dbtables['fk'] as $fk) {
                if ($fk->reftable == $table) {
                    $ffk = $fk->fk;
                    continue;
                }
                if ($ffk) {
                    fwrite($handle, $this->nlt(1));
                    fwrite($handle, $this->nlt(1) . '/**');
                    fwrite($handle, $this->nlt(1) . '* ' . $model . ' possui ' . ucfirst($fk->reftable) . '(s) via ' . ucfirst($fk->table) . '(NxN)');
                    fwrite($handle, $this->nlt(1) . '* @param Lazydev\Core\Criteria $criteria');
                    fwrite($handle, $this->nlt(1) . '* @return ' . ucfirst($fk->reftable) . '[] array de ' . ucfirst($fk->reftable));
                    fwrite($handle, $this->nlt(1) . '*/');
                    fwrite($handle, $this->nlt(1) . 'function get' . ucfirst($fk->reftable) . 's' . $fk->used . '($criteria = NULL){');
                    fwrite($handle, $this->nlt(2) . 'return $this->hasNN(');
                    fwrite($handle, '\'' . ucfirst($fk->table) . '\', \'' . $ffk . '\', \'' . ucfirst($fk->reftable) . '\', \'' . $fk->fk . '\', $criteria');
                    fwrite($handle, ');');
                    fwrite($handle, $this->nlt(1) . "}");
                }
            }
            $ffk = '';
            foreach (array_reverse($dbtables['fk']) as $fk) {
                if ($fk->reftable == $table) {
                    $ffk = $fk->fk;
                    continue;
                }
                if ($ffk) {
                    fwrite($handle, $this->nlt(1));
                    fwrite($handle, $this->nlt(1) . '/**');
                    fwrite($handle, $this->nlt(1) . '* ' . $model . ' possui ' . ucfirst($fk->reftable) . '(s) via ' . ucfirst($fk->table) . '(NxN)');
                    fwrite($handle, $this->nlt(1) . '* @param Lazydev\Core\Criteria $criteria');
                    fwrite($handle, $this->nlt(1) . '* @return ' . ucfirst($fk->reftable) . '[] array de ' . ucfirst($fk->reftable));
                    fwrite($handle, $this->nlt(1) . '*/');
                    fwrite($handle, $this->nlt(1) . 'function get' . ucfirst($fk->reftable) . 's' . $fk->used . '($criteria = NULL){');
                    fwrite($handle, $this->nlt(2) . 'return $this->hasNN(');
                    fwrite($handle, '\'' . ucfirst($fk->table) . '\', \'' . $ffk . '\', \'' . ucfirst($fk->reftable) . '\', \'' . $fk->fk . '\', $criteria');
                    fwrite($handle, ');');
                    fwrite($handle, $this->nlt(1) . "}");
                }
            }
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
                } else {
                    $f->InputType = 'text';
                    $f->selected = $selected;
                    $selected = '';
                }
                if ($f->Key == 'PRI') {
                    $pk[] = $f->Field;
                }
                $f->fk = 0;
                array_map(function ($fk) {
                    $fk->used = '';
                }, (array)$fks);
                $fkUsed = [];
                foreach ($fks as $fk) {
                    $fk->used = in_array($fk->reftable, $fkUsed) ? ++$fk->used : '';
                    $fk->used = $fk->used == 1 ? 2 : $fk->used;
                    $fkUsed[] = $fk->reftable;
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
