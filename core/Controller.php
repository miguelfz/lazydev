<?php
namespace Lazydev\Core;

class Controller
{
    private $vars = [];
    private $title;
    private $view;
    private $params = [];
    private $htmlhead = [];
    private $template;

    /**
     * Esta função é automaticamente executada antes da execução da funçao do
     * Controller especificado.
     * 
     * Implemente aqui regras globais que podem valer para todos os Controllers
     */
    public function beforeRun()
    {
        $this->set('html', new Html());
        # segurança da página quando ativado o ACL no config
        if (!Acl::check(CONTROLLER, ACTION, Session::get(Acl::$loggedSession))) {
            if ($this->getParam('url_origem')) {
                $this->goUrl(PATH . Acl::$redirectTo . '/url_origem:' . $this->getParam('url_origem'));
            }
            $this->goUrl(PATH . Acl::$redirectTo);
        }
    }

    /**
     * Define o nome da pasta que contem o template a ser usado
     * Os templates se encontram na pasta \template
     * 
     * @param String $template
     */
    protected function setTemplate(string $template)
    {
        $this->template = $template;
    }

    /**
     * Define o título da página
     * <title>Lorem Imspum</title>
     * 
     * @param String $title
     */
    protected function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Define uma variável e atribui seu valor para ser utilizada na View (visão)
     * O primeiro parãmetro é o nome da variável que será criada na View
     * O segundo parãmetro é o seu valor
     *      * 
     * @param String $varname
     * @param Mixed $value
     */
    protected function set(string $varname, mixed $value)
    {
        $this->vars[$varname] = $value;
    }

    public function __set(string $varname, mixed $value)
    {
        $this->vars[$varname] = $value;
    }

    /**
     * Redireciona para uma´página do sistema (Controller e seu método).
     * (opcional) Utilize um array associativo para enviar parâmetros adicionais via GET.
     * 
     * Exemplo 1: Para se construir a url /Produto/all/?categoria=Foo&tipo=Bar
     * $this->go('Produto/all', ['categoria'=>'Foo', 'tipo'=>'Bar'] )
     * 
     * Exemplo 2: Para se construir uma URL para /Produto/all
     * $this->go('Produto/all' )
     *      
     * @param String $url
     * @param array $urlParams
     */
    protected function go(string $url, array $urlParams = [])
    {
        $controller = ucfirst($url);
        $anchor = '';
        $link = PATH . '/' . ucfirst($controller) . '/';
        if (is_array($urlParams)) {
            $carr = (Config::get('criptedGetParamns'));
            if (is_array($carr)) {
                foreach ($carr as $param) {
                    foreach ($urlParams as $key => $value) {
                        if ($param === $key) {
                            $urlParams[$key] = Cript::cript($value);
                        }
                    }
                }
            }
            foreach ($urlParams as $key => $value) {
                if ($key === '#') {
                    $anchor = '#' . $value;
                } elseif (is_int($key)) {
                    $link .= $value . '/';
                } else {
                    $link .= $key . ':' . $value . '/';
                }
                unset($urlParams[$key]);
            }
        }
        $link .= $anchor;
        $link = str_replace('//', '/', $link);
        header('Location:' . $link);
        exit;
    }

    /**
     * Redireciona para uma URL
     *       
     * @param String $url
     */
    protected function goUrl(string $url)
    {
        $url = empty($url) ? '/' : $url;
        header('Location:' . ($url));
        exit;
    }

    /**
     * Adiciona código html no <head> da página
     * ex: $this->addHtmlToHead('<meta name="author" content="Miguel">'); 
     * @param string $string
     */
    public function addHtmlToHead($string)
    {
        $this->htmlhead[] = $string;
    }

    /**
     * <b>Renderiza uma view</b>.
     * Este método é acionado automaticamente caso não seja explicitamente definido no controller.
     * 
     * Exemplo: Para renderizar a view <b>\view\Foo\bar.php</b> utilize:
     * $this->render('Foo/bar');
     * 
     * @param String $view
     */
    public function render($view = NULL)
    {
        if (is_null($this->template)) {
            $this->template = Config::get('template');
        }
        if (is_null($view)) {
            $view = CONTROLLER . '/' . ACTION;
        }
        $view = 'view/' . $view;
        $this->view = $view;
        $use_template = $this->getParam('template') == 'off' ? false : true;
        if (!$use_template) {
            $this->template = null;
        } 
        new Template($this->template, $this->view, $this->vars, $this->htmlhead, $this->title);
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
     * @return \ArrayObject ou array de objetos | false
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

    /**
     * Renderiza uma visão sem template como um componente
     * (opcional) Utilize um array associativo para enviar parâmetros adicionais via GET.
     * 
     * Exemplo:
     * $this->loadComponent('Produto','all', ['categoria'=>'Foo', 'tipo'=>'Bar'])
     *      
     * @param String $controller
     * @param String $action
     * @param array $urlParams
     */
    public function loadComponent(string $controller, string $action = 'index', array $urlParams = [])
    {
        $link = PATH . '/' . $controller . '/' . $action . '/';
        if (is_array($urlParams)) {
            $carr = (Config::get('criptedGetParamns'));
            if (is_array($carr)) {
                foreach ($carr as $param) {
                    foreach ($urlParams as $key => $value) {
                        if ($param === $key) {
                            $urlParams[$key] = Cript::cript($value);
                        }
                    }
                }
            }
            foreach ($urlParams as $key => $value) {
                if (is_int($key)) {
                    $link .= $value . '/';
                } else {
                    $link .= $key . ':' . $value . '/';
                }
                unset($urlParams[$key]);
            }
        }
        $protocolo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://");
        $link .= '/template:off';
        $link = str_replace('//', '/', $link);
        $link = $protocolo . 'localhost' . $link;
        $cr = curl_init();
        curl_setopt($cr, CURLOPT_URL, $link);
        curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
        $retorno = curl_exec($cr);
        curl_close($cr);
        echo $retorno;
    }

    /**
     * Retorna a url atual.
     * 
     * @return string URL
     */
    public function getURL()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public function uncriptGetParams()
    {
        # uncript
        $carr = Config::get('criptedGetParamns');
        foreach ($carr as $value) {
            if (isset($this->params[$value])) {
                $this->params[$value] = Cript::decript($this->params[$value]);
            }
        }
    }

    public function getParam($name)
    {
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }
        return NULL;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
        $_GET[$name] = $value;
    }

    public function addParam($value)
    {
        $this->params[] = $value;
    }

    public function initParameters()
    {
        $vars = (array) filter_input_array(INPUT_GET);
        foreach ($vars as $key => $value) {
            $this->params[$key] = strip_tags($value);
        }
    }

    protected function transactionBegin()
    {
        $db = new MariaDB();
        $db->beginTransaction();
    }

    protected function transactionCommit()
    {
        $db = new MariaDB();
        $db->endTransaction();
    }

    protected function transactionRollBack()
    {
        $db = new MariaDB();
        $db->cancelTransaction();
    }
}
