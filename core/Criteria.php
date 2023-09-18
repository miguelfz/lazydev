<?php

namespace Lazydev\Core;

/**
 * classe Criteria
 * 
 * @author Miguel
 * @package \lib\core
 */
class Criteria
{

    private static $idIndex = 0;
    public $locked = false;
    public $curpage = 1;
    public $id = 0;
    private $perPage = 0;
    private $pageParamName = 'pagina';
    private $paginate = false;
    private $conditions = [];
    private $limit;
    private $order;
    private $tables = [];
    private $table;
    private $sql;
    private $vars;

    function __toString()
    {
        return '_criteria_' . $this->id;
    }

    function __construct(...$params)
    {
        $this->id = ++self::$idIndex;
        foreach ($params as $param) {
            #limit
            if (isset($param['limit'])) {
                $this->setLimit((int)$params['limit']);
            }
            # campos envolvidos
            elseif (isset($param['vars']) && is_array($param['vars'])) {
                $this->vars = ($param['vars']);
            }
            # orderby
            elseif (isset($param['orderby'])) {
                $this->setOrder($param['order']);
            }
            #paginate
            elseif (isset($param['paginate']) && is_int($param['paginate'])) {
                $this->paginate($params['paginate']);
            } elseif (isset($param['paginate']) && is_array($param['paginate'])) {
                $this->paginate(...$param['paginate']);
            }
        }
    }

    function setTable(string $table)
    {
        $this->table = $table;
        $this->addTable($table);
    }

    private function addTable($table)
    {
        if (!in_array($table, $this->tables)) {
            $this->tables[] = $table;
        }
    }

    public function getTables()
    {
        return $this->tables;
    }

    public function getVars()
    {
        return $this->vars;
    }

    /**
     * Adiciona um filtro ao se buscar uma instancia ou coleção de Models. 
     * 
     * Exemplo: buscar uma coleção com filtros:
     * 
     * $c = new Criteria();<br>
     * $c->addCondition('foo', '=' 'teste');<br>
     * $c->addCondition('bar', '>' 5);<br>
     * $arr = Model::getList($c);<br>
     * 
     * o exemplo acima resultará na consulta SQL:<br>
     * SELECT * FROM model WHERE foo = 'teste' AND bar > 5;
     * 
     * @param mixed $field String ou Array
     * @param String $op operador = >=  <= IS NOT
     * @param mixed $value String ou Array
     */
    public function addCondition($field, string $operator, $value)
    {
        if (is_array($field)) {
            foreach ($field as $v) {
                $fvalue = array_shift($value);
                if (strstr($v, '.')) {
                    $r = explode('.', $v);
                    $table = $r[0];
                    $this->addTable($table);
                }
                $this->conditions[] = array($v, $operator, $fvalue, str_replace('.', '', $v) . uniqid());
            }
        } else {
            if (strstr($field, '.')) {
                $r = explode('.', $field);
                $table = $r[0];
                $this->addTable($table);
            }
            $fvalue = is_array($value)?array_shift($value):$value;
            $this->conditions[] = array($field, $operator, $fvalue, str_replace('.', '', $field) . uniqid());
        }
    }

    /**
     * Adiciona um OR entre os filtros ao se buscar uma instancia ou coleção de Models. 
     * 
     * Exemplo: buscar uma coleção com filtros:
     * 
     * $c = new Criteria();<br>
     * $c->addCondition('foo', '=' 'teste');<br>
     * $c->addCondition('bar', '>' 5);<br>
     * $c->addOr();<br>
     * $c->addCondition('y', '>' 0);<br>
     * $arr = Model::getList($c);<br>
     * 
     * o exemplo acima resultará na consulta SQL:<br>
     * SELECT * FROM model WHERE (foo = 'teste' AND bar > 5) OR (y > 0);         * 
     */
    public function addOr()
    {
        $this->conditions[] = ') OR (';
    }

    /**
     * Define um limite de resultados ao retornar uma coleção de Models
     * 
     * @param int $number
     */
    public function setLimit($number)
    {
        $this->limit = $number;
    }

    /**
     * Define a ordenação dos resultados ao retornar uma coleção de Models.
     * 
     * Exemplo: buscar uma coleção ordenada por um campo:
     * 
     * $c = new Criteria();<br>
     * $c->setOrder('foo');<br>
     * $arr = Model::getList($c);<br>
     * 
     * o exemplo acima resultará na consulta SQL:<br>
     * SELECT * FROM model ORDER BY foo;         * 
     * 
     * @param String $field
     */
    public function setOrder($field)
    {
        $this->order = $field;
    }

    public function paginate($perPage, $paramName = 'pagina')
    {
        if (!$perPage) {
            $this->paginate = false;
            return;
        }
        $this->paginate = true;
        $this->perPage = $perPage;
        $this->pageParamName = $paramName;
        $this->curpage = isset($_GET[$paramName]) ? (int) $_GET[$paramName] : 1;
        $this->setLimit(($this->curpage - 1) * $this->perPage . ',' . $this->perPage);
    }

    public function getPerPage()
    {
        return $this->perPage;
    }

    public function getPageParamName()
    {
        return $this->pageParamName;
    }

    /**
     * Unifica duas instancias de Criteria
     * @param Criteria $criteria
     */
    public function merge(Criteria $criteria)
    {
        $this->conditions = array_merge($criteria->conditions, $this->conditions);
        if (empty($this->perPage) && empty($this->limit)) {
            $this->perPage = $criteria->getPerPage();
            $this->pageParamName = $criteria->getPageParamName();
            $this->paginate = $criteria->paginate;
            $this->curpage = $criteria->curpage;
        }
        if (empty($this->limit)) {
            $this->limit = $criteria->limit;
        }
        if (empty($this->order)) {
            $this->order = $criteria->order;
        }
    }

    public function getConditions()
    {
        if (!empty($this->table)) {
            foreach ($this->conditions as &$c) {
                if (is_array($c)) {
                    if (!strstr($c[0], '.')) {
                        $c[0] = $this->table . '.' . $c[0];
                    }
                }
            }
        }
        if (!is_array(reset($this->conditions))) {
            array_shift($this->conditions);
        }
        if (!is_array(end($this->conditions))) {
            array_pop($this->conditions);
        }
        return $this->conditions;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getOrder()
    {
        if (!empty($this->table) && !empty($this->order)) {
            if (!strstr($this->order, '.') && $this->order != 'RAND()') {
                $orders = explode(',', $this->order);
                foreach ($orders as &$o) {
                    $o = $this->table . '.' . $o;
                }
                $this->order = implode(',', $orders);
            }
        }
        return $this->order;
    }

    /**
     * Adiciona cláusulas SQL para filtrar consultas  
     * 
     * Exemplo: buscar uma coleção com addSqlConditions:
     * 
     * $c = new Criteria();<br>
     * $c->addSqlConditions('foo=1 OR bar=2');<br>
     * $arr = Model::getList($c);<br>
     * 
     * o exemplo acima resultará na consulta SQL:<br>
     * SELECT * FROM model WHERE foo = 1 OR bar = 2;
     * 
     * @param String $field
     * @param String $op
     * @param String $value
     */
    public function addSqlCondition($sqlString)
    {
        $this->sql .= ' ' . $sqlString;
    }

    public function getSqlConditions()
    {
        return $this->sql;
    }
}
