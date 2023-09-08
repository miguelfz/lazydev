<?php

namespace Lazydev\Core;

abstract class Record
{

    const TABLE = '';
    const PK = '';
    private $vars = [];

    /**
     * Instancia um novo objeto do Modelo ou busca uma instancia 
     * da base de dados a partir da chave primária (pk);
     * 
     * <b>Exemplo:</b><br>     * 
     * $m = new Modelo(5);
     * 
     * Retorna um objeto que representa o Modelo com pk = 5
     * na base de dados;
     * 
     * @param mixed $pk
     */
    public function __construct($pk = NULL)
    {
        if (!is_null($pk)) {
            if (!$this->load($pk)) {
                throw new \Exception('Não foi possível localizar na tabela <strong>' . $this::TABLE . '</strong> o identificador ' . $pk);
            }
        }
    }

    public function __toString()
    {
        return $this->{$this::PK};
    }

    public function __set(string $varname, mixed $value)
    {
        $this->vars[$varname] = $value;
    }

    public function __get(string $varname)
    {
        if (array_key_exists($varname, $this->vars)) {
            return $this->vars[$varname];
        }
    }

    public function sanitize()
    {
    }

    /**
     * Faz o mapeamento 1x1
     * Informe o nome do modelo e a chave estrangeiro que cria a relação
     * 
     * @param String $model
     * @param String $FK
     * @return object $obj instância da classe passada por parâmetro $model
     */
    protected function hasOne($model, $FK)
    {
        $model = '\Lazydev\Model\\' . $model;
        $criteria = new Criteria();
        $criteria->addCondition($FK, '=', $this->{$this::PK});
        $objArr = (array)$model::getList($criteria);
        $obj = array_shift($objArr);
        if (is_null($obj)) {
            $obj = new $model();
        }
        return $obj;
    }

    /**
     * Realiza o mapeamento "possui muitos" (1 x N)
     * Informe o nome do Modelo que possui muitos e o nome do campo chave estrangeira 
     * que cria a relação
     * 
     * @param String $model
     * @param String $FK
     * @param Criteria $criteria
     * @return object $obj instância da classe passada no parâmetro $model
     */
    protected function hasMany(string $model, string $FK, Criteria $criteria = NULL)
    {
        $model = '\Lazydev\Model\\' . $model;
        $att = $model . 's' . $criteria;
        if (!isset($this->$att) || !count($this->$att)) {
            if (is_null($criteria)) {
                $criteria = new Criteria();
            }
            if (empty($this->{$this::PK})) {
                $criteria->addCondition($FK, 'IS', NULL);
            } else {
                $criteria->addCondition($FK, '=', $this->{$this::PK});
            }
            $this->$att = $model::getList($criteria);
        }
        return $this->$att;
    }

    /**
     * Realiza o mapeamento "possui e pertence a muitos" (N x N)
     * 
     * @param String $MiddleModel - Modelo intermediário
     * @param String $sourceFK - chave estrangeira que aponta para o seu modelo
     * @param String $destinationFK - chave estrangeira que aponta para a tabela de destino
     * @param String $destinationModel - nome do Modelo da tabela de destino
     * @param Criteria $criteria
     * @return array $objs coleção de instância da classe passada por parâmetroModel $destinationModel
     */
    protected function hasNN($MiddleModel, $sourceFK, $destinationFK, $destinationModel, $criteria = NULL)
    {
        $att = $MiddleModel . $destinationModel . 's' . $criteria;
        if (isset($this->$att) && count($this->$att)) {
            return $this->$att;
        }
        $db = new MariaDB();
        $class = get_called_class();
        $sourceTable = $class::TABLE;
        $middleTable = $MiddleModel::TABLE;
        $destinationTable = $destinationModel::TABLE;

        $q = "SELECT t3.* FROM $sourceTable t1,$middleTable t2,$destinationTable t3";
        $q .= ' WHERE t1.' . $class::PK . '=' . 't2.' . $sourceFK . ' AND ';
        $q .= 't2.' . $destinationFK . '= t3.' . $destinationModel::PK . ' AND ';
        $q .= 't1.' . $class::PK . '=' . $this->{$this::PK};
        $criteriaConfig = $destinationModel::configure();
        if (empty($criteria) && empty($criteriaConfig)) {
            $db->query($q);
            $rs = $db->getResults($destinationModel);
            self::setNav($rs, $criteria);
            $this->$att = $rs;
            return $this->$att;
        }
        if (!empty($criteriaConfig)) {
            if (empty($criteria)) {
                $criteria = new Criteria();
            }
            $criteria->merge($criteriaConfig);
        }
        $criteria->setTable('t3');
        if ($criteria->getConditions()) {
            $conditions = array();
            $q .= ' AND ( ';
            foreach ($criteria->getConditions() as $c) {
                $label = $c[3];
                if (is_array($c)) {
                    $conditions[] = $c[0] . ' ' . $c[1] . ' :' . $label;
                } else {
                    $conditions[] = $c;
                }
            }
            $q .= implode(' AND ', $conditions) . ' )';
            $q = str_replace('AND ) OR ( AND', ') OR (', $q);
        }
        if ($criteria->getSqlConditions()) {
            $q .= ' AND ' . $criteria->getSqlConditions();
        }
        if ($criteria->getOrder()) {
            $q .= ' ORDER BY ' . $criteria->getOrder();
        }
        if ($criteria->getLimit()) {
            $q .= ' LIMIT ' . $criteria->getLimit();
        }

        $db->query($q);
        foreach ($criteria->getConditions() as $c) {
            if (!is_array($c)) {
                continue;
            }
            $label = $c[3];
            $db->bind(':' . $label, $c[2]);
        }
        $rs = $db->getResults($destinationModel);
        self::setNav($rs, $criteria);
        $this->$att = $rs;
        return $this->$att;
    }

    /**
     * Realiza o mapeamento "pertence a" (N x 1)
     * Informe o nome do Modelo à quem pertence e o nome do campo chave estrangeira 
     * que cria a relação
     * 
     * @param string $model
     * @param String $FK
     * @param Criteria $criteria
     * @return object $obj  instância da classe passada no parâmetro $model
     */
    protected function belongsTo($model, $FK)
    {
        $model = '\Lazydev\Model\\' . $model;
        $model = '' . ucfirst($model);
        $att = $model . $FK;
        if (empty($this->$att)) {
            $this->$att = new $model($this->$FK);
        }
        return $this->$att;
    }

    /**
     * Salva ou atualiza um registro.
     * 
     * <b>Exemplo de uso 1:</b>
     * 
     * $model = new Model();<br>
     * $model->foo = 'bar';<br>
     * $foo->save(); <br>// salva um novo registro na tabela Model<br>
     * 
     * <b>Exemplo de uso 2:</b>
     * 
     * $model = new Model(5);<br>
     * $model->foo = 'bar';<br>
     * $foo->save(); <br>// atualiza o registro com id = 5 na tabela Model<br>
     * 
     * <b>Exemplo de uso 3:</b>
     * 
     * $model = new Model();<br>
     * $foo->save($_POST); <br>// salva um novo registro na tabela Model 
     * com os dados recebido pelo formulário<br>
     * 
     * @param array $data Array associativo 'campo'=>'valor'
     * @return boolean
     * @throws Exception
     */
    public function save($data = NULL)
    {
        $this->sanitize();
        $db = new MariaDB();
        $pk = $this::PK;
        $table = $this::TABLE;
        $atts = [];
        $tabledesc = $this->getTableDescription($table);
        if (!is_null($data) && is_array($data)) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }

        if (empty($this->$pk)) {
            foreach ($tabledesc as $field) {
                if (isset($this->$field))
                    if (trim($this->$field) == '')
                        $this->$field = NULL;
                $atts[$field] = $this->$field;
            }
            $q = "INSERT INTO $table (" . implode(',', array_keys($atts)) . ") VALUES (:" . implode(',:', array_keys($atts)) . ")";
            $db->query($q);
            foreach ($atts as $key => $value) {
                $db->bind(':' . $key, $value);
            }
            $result = $db->execute();
            $id = $db->lastInsertId();
            if (!$result) {
                throw new \Exception('Preencha todos os campo obrigatórios.', 2);
            }
            $this->$pk = $id;
            return $result;
        } else {
            foreach ($tabledesc as $field) {
                if (isset($this->$field))
                    if (trim($this->$field) == '' || trim($this->$field) == NULL)
                        $this->$field = NULL;
                $atts[$field] = $this->$field;
                $fields[] = $field . '=:' . $field;
            }
            $q = "UPDATE $table SET " . implode(',', $fields);
            $q .= " WHERE $pk = :$pk";
            $db->query($q);
            foreach ($atts as $key => $value) {
                $db->bind(':' . $key, $value);
            }
            $result = $db->execute();
            if (!$result)
                throw new \Exception($db->errorMsg);
            return $result;
        }
    }

    protected function load($id)
    {
        $pk = $this::PK;
        $class = get_class($this);
        $criteriaConfig = $class::configure();
        if (empty($criteriaConfig)) {
            $criteriaConfig = new Criteria();
        }
        $criteriaConfig->addCondition($pk, '=', $id);
        $data = $this->getFirst($criteriaConfig);
        if (empty($data)) {
            return false;
        }
        foreach ($data as $key => $value) {
            $this->$key = $data->$key;
        }
        return true;
    }

    /**
     * deleta uma linha da tabela a partir de uma instancia do modelo
     * 
     * <b>Exemplo de uso:</b>
     * 
     * $model = new Model( 5 );<br>
     * $model->delete(); <br>
     * // apaga o registro 5 da tabela Model
     * 
     * @return boolean
     * @throws Exception
     */
    public function delete()
    {
        $db = new MariaDB();
        $pk = $this::PK;
        $table = $this::TABLE;
        $id = $this->$pk;
        $db->query("DELETE FROM $table WHERE $pk = :id");
        $db->bind(':id', $id);
        $result = $db->execute();
        if (!$result) {
            throw new \Exception($db->errorMsg, 3);
        }
        return $result;
    }

    public function getPage($pageName = 'pagina')
    {
        return $this;
    }

    /**
     * Retorna uma coleção (array) de objetos de Model
     * 
     * <b>Exemplo de uso:</b>
     * 
     * $models = Model::getList(); 
     * 
     * @param Criteria $criteria
     * @return array de Objetos do modelo
     */
    public static function getList(Criteria $criteria = NULL)
    {
        if (!empty($criteria)) {
            $criteria = clone $criteria;
        }
        $db = new MariaDB();
        $class = get_called_class();
        $table = $class::TABLE;
        $criteriaConfig = $class::configure();
        if (empty($criteria) && empty($criteriaConfig)) {
            $q = "SELECT * FROM $table";
            $db->query($q);
            $rs = $db->getResults($class);
            self::setNav($rs, $criteria);
            return $rs;
        }
        if (!empty($criteriaConfig)) {
            if (empty($criteria)) {
                $criteria = new Criteria();
            }
            $criteria->merge($criteriaConfig);
        }
        $criteria->setTable($table);
        $vars = '*';
        if($criteria->getVars()){            
         $vars = implode(', '.$table.'.',$criteria->getVars());
        }
        $q = "SELECT $table.$vars FROM " . implode(',', $criteria->getTables());
        if ($criteria->getConditions()) {
            $conditions = array();
            $q .= ' WHERE ( ';
            foreach ($criteria->getConditions() as $c) {
                $label = $c[3];
                if (is_array($c)) {
                    $conditions[] = $c[0] . ' ' . $c[1] . ' :' . $label;
                } else {
                    $conditions[] = $c;
                }
            }
            $q .= implode(' AND ', $conditions) . ' )';
            $q = str_replace('AND ) OR ( AND', ') OR (', $q);
            if ($criteria->getSqlConditions()) {
                $q .= ' AND ' . $criteria->getSqlConditions();
            }
        } elseif ($criteria->getSqlConditions()) {
            $q .= ' WHERE ' . $criteria->getSqlConditions();
        }

        if ($criteria->getOrder()) {
            $q .= ' ORDER BY ' . $criteria->getOrder();
        }

        if ($criteria->getLimit()) {
            $q .= ' LIMIT ' . $criteria->getLimit();
        }

        $db->query($q);
        foreach ($criteria->getConditions() as $c) {
            if (!is_array($c)) {
                continue;
            }
            $label = $c[3];
            $db->bind(':' . $label, $c[2]);
        }
        $rs = $db->getResults($class);
        self::setNav($rs, $criteria);
        return $rs;
    }

    /**
     * Retorna a primeira ocorrência de Model da base de dados
     * 
     * @param Criteria $criteria
     * @return object 
     */
    public static function getFirst(Criteria $criteria = NULL)
    {
        $db = new MariaDB();
        $class = get_called_class();
        $table = $class::TABLE;
        $q = "SELECT * FROM $table";

        $criteriaConfig = $class::configure();
        if (empty($criteria) && empty($criteriaConfig)) {
            $db->query($q);
            return $db->getRow($class);
        }
        if (!empty($criteriaConfig)) {
            if (empty($criteria)) {
                $criteria = new Criteria();
            }
            $criteria->merge($criteriaConfig);
        }

        if ($criteria->getConditions()) {
            $conditions = array();
            foreach ($criteria->getConditions() as $c) {
                $conditions[] = $c[0] . ' ' . $c[1] . ' :' . $c[3];
            }
            $q .= ' WHERE ' . implode(' AND ', $conditions);
            if ($criteria->getSqlConditions()) {
                $q .= ' AND ' . $criteria->getSqlConditions();
            }
        } elseif ($criteria->getSqlConditions()) {
            $q .= ' WHERE ' . $criteria->getSqlConditions();
        }

        if ($criteria->getOrder()) {
            $q .= ' ORDER BY ' . $criteria->getOrder();
        }
        if ($criteria->getLimit()) {
            $q .= ' LIMIT ' . $criteria->getLimit();
        } else {
            $q .= ' LIMIT 1';
        }

        $db->query($q);
        foreach ($criteria->getConditions() as $c) {
            $db->bind(':' . $c[3], $c[2]);
        }
        return $db->getRow($class);
    }

    /**
     * Retorna a quantidade de registro existentes
     * 
     * @param Criteria $criteria
     * @return int $n Número de linhas
     */
    public static function count(Criteria $criteria = NULL)
    {
        $db = new MariaDB();
        $class = get_called_class();
        $table = $class::TABLE;
        $q = "SELECT count(*) as count FROM $table";
        $criteriaConfig = $class::configure();
        if (empty($criteria) && empty($criteriaConfig)) {
            $db->query($q);
            return $db->getRow()->count;
        }
        if (!empty($criteriaConfig)) {
            if (empty($criteria)) {
                $criteria = new Criteria();
            }
            $criteria->merge($criteriaConfig);
        }
        if ($criteria->getConditions()) {
            $conditions = array();
            $used = array();
            $i = 2;
            $q .= ' WHERE (';
            foreach ($criteria->getConditions() as $c) {
                $label = $c[3];
                while (array_search($label, $used)) {
                    $label .= $i++;
                }
                if (is_array($c)) {
                    $conditions[] = $c[0] . ' ' . $c[1] . ' :' . $label;
                } else {
                    $conditions[] = $c;
                }
                $used[] = $label;
            }
            $q .= implode(' AND ', $conditions) . ')';
            $q = str_replace('AND ) OR ( AND', ') OR (', $q);
            if ($criteria->getSqlConditions()) {
                $q .= ' AND ' . $criteria->getSqlConditions();
            }
        } elseif ($criteria->getSqlConditions()) {
            $q .= ' WHERE ' . $criteria->getSqlConditions();
        }

        if ($criteria->getOrder()) {
            $q .= ' ORDER BY ' . $criteria->getOrder();
        }

        $db->query($q);
        foreach ($criteria->getConditions() as $c) {
            if (!is_array($c)) {
                continue;
            }
            $db->bind(':' . $c[3], $c[2]);
        }
        return $db->getRow()->count;
    }

    private function getTableDescription($tablename)
    {
        $db = new MariaDB();
        $db->query("DESCRIBE $tablename");
        $r = $db->getResults();
        $desc = array();
        foreach ($r as $rvalue) {
            $desc[] = $rvalue->Field;
        }
        return $desc;
    }

    /**
     * Retorna o menu de navegação do sistema de paginação
     * para ser utilizado na View;
     * 
     * @return null|string
     */
    private static function setNav(ResultSet $rs, Criteria $criteria = NULL)
    {
        $rs2 = clone $rs; # para evitar recursão
        $rs->nav = function () use ($criteria, $rs2) {
            if (empty($criteria) || !$criteria->getPerPage()) {
                return '';
            }
            $count = $rs2->rows;
            $pages = ceil($count / $criteria->getPerPage());
            $pageParam = $criteria->getPageParamName();
            if ($pages <= 1) {
                return null;
            }
            $http = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL = $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            } else {
                $pageURL = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            }

            $r = '';
            $r .= '<div class="pagination">';
            $pageURL = preg_replace('#(' . preg_quote($pageParam) . ')(:\d+)(' . preg_quote('/') . ')#si', '', $pageURL);
            $pageURL = str_replace('//', '/', $pageURL.'/');
            if (URLF && !filter_input(INPUT_GET, '_url', FILTER_SANITIZE_URL)) {
                $pageURL .= CONTROLLER . '/' . ACTION . '/';
            } else if (!URLF && !filter_input(INPUT_GET, '_url', FILTER_SANITIZE_URL)) {
                $pageURL .= '?_url=' . CONTROLLER . '/' . ACTION . '/';
            }

            $urls = explode('?', $pageURL);
            $pageURL = $urls[0];
            $NRparamns = isset($urls[1]) ? '?' . substr($urls[1],0,-1) : '';
            if (substr($pageURL, -1, 1) != '/') {
                $pageURL .= '/';
            }
            $maxPages = 2;
            $start = $criteria->curpage - $maxPages < 1 ? 1 : $criteria->curpage - $maxPages;
            $end = ($criteria->curpage + $maxPages > $pages ? $pages : $criteria->curpage + ((($maxPages - $criteria->curpage) > 0 ? ($maxPages - $criteria->curpage) : 0) + $maxPages));
            if ($criteria->curpage - 1 > $maxPages) {
                $r .= '<a href="' . $http . $pageURL . $pageParam . ':1'. '/' . $NRparamns.'#' . $pageParam . '">1</a>';
                if($criteria->curpage -2  > $maxPages){
                    $r .= ' ... ';
                }
            }
            for ($i = $start; $i <= $end; $i++) {
                $r .= '<a class="' . (($criteria->curpage == $i) ? "active" : "") . '" '
                    . 'href="' . $http . $pageURL . $pageParam . ':' . $i  . '/' . $NRparamns. '#' . $pageParam . '">';
                $r .= $i;
                $r .= '</a>';
            }
            if ($end< $pages) {
                if(($end+1)<$pages){
                    $r .= ' ... ';
                }
                $r .= '<a href="' . $http . $pageURL . $pageParam . ':' . $pages .'/' . $NRparamns. '#' . $pageParam . '">' . $pages . '</a>';
            }
            $r .= "</div>";
            return $r;
        };
    }

    /**
     * Execulta uma consulta direta ao baco de dados, sem o uso de Models.
     * Evite o uso abusivo desta função;
     * 
     * Exemplo 1: consulta personalizada que <b>retorna um array de objetos standard:</b>
     * $resultados = $this->query('SELECT campo1, campo2 FROM foo LEFT JOIN bar ON foo.id = bar.id');
     * 
     * Exemplo 2: consulta personalizada que <b>retorna um array de objetos standard:</b>
     * $resultados = $this->query('SHOW TABLES');
     * 
     * Exemplo 3: apaga dados de uma tabela <b>retorna true ou false</b>
     * $resultados = $this->query('DELETE FROM foo WHERE id = 2');
     * 
     * @param String $sqlQuery
     * @param String $className - [opcional] nome de uma classe existente para Casting dos objetos do resultado
     * @return boolean ou array de objetos
     */
    protected function query($sqlQuery, $className = NULL)
    {
        $db = new MariaDB();
        $db->query($sqlQuery);
        $command = strtolower(strtok($sqlQuery, ' '));
        if ($command == 'select' || $command == 'show' || $command == 'describe') {
            return $db->getResults($className);
        } else {
            return $db->execute();
        }
    }

    public static function configure()
    {
    }
}
