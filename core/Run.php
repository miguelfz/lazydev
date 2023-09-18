<?php

namespace Lazydev\Core;

class Run
{
    private array $urlArr;
    private int $params = 0;
    private string $controller;
    private string $method = '';

    public function __construct()
    {
        require  __DIR__ . '/../config.php';
        session_start();
        define('PATH', preg_replace('/\\\\|\/$/', '', dirname($_SERVER["SCRIPT_NAME"])));
        $url = preg_replace('/\/$/', '', filter_input(INPUT_GET, '_url', FILTER_SANITIZE_URL));;
        $this->urlArr = $url ? explode("/", $url) : [];
        $this->params = count($this->urlArr);
        $this->setController();
        $this->setAction();
        $this->setRoute();
        $this->setGlobals();

        if (!method_exists('\Lazydev\Controller\\' . $this->controller, $this->method)) {
            new Msg("Método $this->method não encontrado ou não definido no controller $this->controller", 5);
        }
        if (!$this->controller || !$this->method || !file_exists(__DIR__ . '/../controller/' . $this->controller . '.php')) {
            $c = new Controller;
            $c->render('404');
            exit;
        }
        if (!file_exists(__DIR__ . "/../view/$this->controller/$this->method.php") && !file_exists(__DIR__ . "/../view/$this->controller/$this->method.tpl") && !file_exists(__DIR__ . "/../view/$this->controller/$this->method.html")) {
            $c = new Controller;
            new Msg("Arquivo de view não encontrado.<br>Esperado /../view/$this->controller/$this->method.[ tpl | php | html ]", 5);
            $c->render('404');
            exit;
        }
        $this->controller = "\Lazydev\Controller\\$this->controller";
        $c = new $this->controller;
        $this->setParams($c);
        $method = ACTION;
        $posts = (array) filter_input_array(INPUT_POST);
        if (count($posts) && method_exists($c, 'post_' . ACTION)) {
            $method = 'post_' . ACTION;
        }
        $c->initParameters();
        $c->uncriptGetParams();
        $c->beforeRun();
        $c->$method();
        $c->render();
    }

    private function setController()
    {
        $this->controller = '';
        if (!$this->params) {
            $this->controller = Config::get('indexController');
        } elseif (file_exists(__DIR__ . '/../controller/' . $this->urlArr[0] . '.php')) {
            $this->controller = array_shift($this->urlArr);
        } else {
            new Msg("Não foi possível chamar este controller. Classe ou arquivo não encontrado não encontrado.", 5);
        }
    }

    private function setAction()
    {
        if ($this->params < 2) {
            $this->method = Config::get('indexAction');
        } elseif (count($this->urlArr) && method_exists('\Lazydev\Controller\\' . $this->controller, $this->urlArr[0])) {
            $this->method = array_shift($this->urlArr);
        }        
    }

    private function setRoute()
    {
        $route = [];
        if ($this->params && !$this->method && $this->controller) {
            $route = Route::checkRoute($this->controller);
        } elseif ($this->params && !$this->controller && count($this->urlArr)) {
            $route = Route::checkRoute($this->urlArr[0]);
        }
        if (count($route)>1) {
            $this->controller = $route[0];
            $this->method = $route[1];
        }
    }

    private function setGlobals()
    {
        define('CONTROLLER', $this->controller);
        define('ACTION', $this->method);
        define('URLF', filter_input(INPUT_GET, '_urlf', FILTER_SANITIZE_NUMBER_INT));
    }

    private function setParams(object $c)
    {
        foreach ($this->urlArr as $param) {
            $parr = explode(':', $param, 2);
            if (count($parr) > 1 && $parr[1] != '') {
                $c->setParam($parr[0], $parr[1]);
            } elseif (count($parr) == 1 && $parr[0] != '') {
                $c->addParam($parr[0]);
            }
        }
    }
}
