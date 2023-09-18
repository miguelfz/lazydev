<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Core\Msg;
use Lazydev\Model\Categoria as ModelCategoria;

final class Categoria extends \Lazydev\Core\Controller{ 

    # Lista de Categoria
    # renderiza a visão /view/Categoria/lista.tpl
    # url: /Categoria/lista
    function lista(){
        $this->setTitle('Lista');
        $c = new Criteria();
        $c->setOrder('nome');
        $categorias=ModelCategoria::getList($c);
        $this->set('categorias', $categorias);
    }

    # Visualiza um(a) Categoria
    # renderiza a visão /view/Categoria/ver.tpl
    # url: /Categoria/ver/2
    function ver(){
        try {
            $categoria = new ModelCategoria($this->getParam(0));
            $this->set('categoria', $categoria);
            $this->set('livros', $categoria->getLivros());
            $this->set('editoras', $categoria->getEditoras());
            $this->setTitle($categoria->nome);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 2);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('categoria', 'lista');
        }
    }

    # Cadastrar Categoria
    # renderiza a visão /view/Categoria/cadastrar.tpl
    # url: /Categoria/cadastrar
    function cadastrar(){
        $this->setTitle('Cadastrar Categoria');
        $categoria = new ModelCategoria;
        $this->set('categoria', $categoria);
    }

    # Recebe os dados do formulário de cadastrar Categoria e redireciona para a lista
    function post_cadastrar(){
        $this->go('Categoria/lista');
    }

    # Editar Categoria
    # renderiza a visão /view/Categoria/editar.tpl
    # url: /Categoria/editar
    function editar(){
        $this->setTitle('Editar');
    }

    # Recebe os dados do formulário de editar Categoria e redireciona para a lista
    function post_editar(){
        $this->go('Categoria/lista');
    }

    }