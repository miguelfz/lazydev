<?php

namespace Lazydev\Core;

use Smarty;

class Template
{

    private $template;
    private $view;
    private $vars;
    private $htmlHead;
    private $title;

    function __construct(?string $template, string $view, array $vars, array $htmlHead, ?string $title)
    {
        $this->template = $template;
        $this->view = $view;
        $this->vars = $vars;
        $this->htmlHead = $htmlHead;
        $this->title = $title;
        if ($template) {
            if (file_exists(__DIR__ . '/../template/' . $this->template . '/index.php')) {
                require __DIR__ . '/../template/' . $this->template . '/index.php';
            } else {
                echo '<h1>Template não encontrado</h1>';
                exit;
            }
        } else {
            $this->getContents();
        }
    }


    /**
     * Processa as visões e demais saídas do sistema;
     * 
     * Esta função deve ser chamada no arquivo de template dentro da tag <body> 
     * e no container preparado para receber o conteúdo.
     */
    public function getContents()
    {
        foreach ($this->vars as $key => $value) {
            $$key = $value;
        }
        echo Msg::getMsg();
        if (file_exists(__DIR__ . '/../' . $this->view . '.tpl')) {
            $smarty = new Smarty();
            $smarty->setCompileDir(__DIR__ . '/../template/compiled');
            $smarty->setCacheDir(__DIR__ . '/../template/cache');
            $smarty->setTemplateDir(__DIR__ . '/../' . dirname($this->view));
            foreach ($this->vars as $key => $value) {
                $smarty->assign($key, $value);
            }
            $smarty->display(basename($this->view . '.tpl'));
        } elseif (file_exists(__DIR__ . '/../' . $this->view . '.php')) {
            foreach ($this->vars as $key => $value) {
                $$key = $value;
            }
            require __DIR__ . '/../' . $this->view . '.php';
        } elseif (file_exists(__DIR__ . '/../' . $this->view . '.html')) {
            require __DIR__ . '/../' . $this->view . '.html';
        } else {
            echo '<h1>View ' . __DIR__ . '/../' . $this->view . ' não encontrada!</h1>';
            exit;
        }
        if (!Config::get('debug')) {
            Msg::getDebugMsg();
        }
        echo Msg::getDebugMsg();
    }

    /**
     * Inclui no template os códigos enviados pelo método addToHead(String)
     */
    public function getHtmlHead()
    {
        foreach ($this->htmlHead as $value) {
            echo $value;
        }
    }

    /**
     * Retorna o título da página atual, definida no controller por $this->setTitle();
     * 
     * @return String título do documento
     */
    public function getTitle()
    {
        return $this->title;
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
}
