<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Core\Msg;
use Lazydev\Model\Autor as ModelAutor;

final class Autor extends \Lazydev\Core\Controller{ 

    # Lista de Autor
    # renderiza a visão /view/Autor/lista.tpl
    # url: /Autor/lista
    function lista(){
        $this->setTitle('Lista');
        $c = new Criteria();
        $c->setOrder('nome');
        $autors=ModelAutor::getList($c);
        $this->set('autors', $autors);
    }

    # Visualiza um(a) Autor
    # renderiza a visão /view/Autor/ver.tpl
    # url: /Autor/ver/2
    function ver(){
        try {
            $autor = new ModelAutor($this->getParam(0));
            $this->set('autor', $autor);
            $this->set('livros', $autor->getLivros());
            $this->setTitle($autor->nome);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 2);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('autor', 'lista');
        }
    }

    # Cadastrar Autor
    # renderiza a visão /view/Autor/cadastrar.tpl
    # url: /Autor/cadastrar
    function cadastrar(){
        $this->setTitle('Cadastrar Autor');
        $autor = new ModelAutor;
        $this->set('autor', $autor);
    }

    # Recebe os dados do formulário de cadastrar Autor e redireciona para a lista
    function post_cadastrar(){
        $this->go('Autor/lista');
    }

    # Editar Autor
    # renderiza a visão /view/Autor/editar.tpl
    # url: /Autor/editar
    function editar(){
        $this->setTitle('Editar');
    }

    # Recebe os dados do formulário de editar Autor e redireciona para a lista
    function post_editar(){
        $this->go('Autor/lista');
    }

    }