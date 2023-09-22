<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Core\Msg;
use Lazydev\Model\Editora as ModelEditora;

final class Editora extends \Lazydev\Core\Controller{ 

    # Lista de Editora
    # renderiza a visão /view/Editora/lista.tpl
    # url: /Editora/lista
    function lista(){
        $this->setTitle('Lista');
        $c = new Criteria();
        $c->setOrder('nome');
        $editoras=ModelEditora::getList($c);
        $this->set('editoras', $editoras);
    }

    # Visualiza um(a) Editora
    # renderiza a visão /view/Editora/ver.tpl
    # url: /Editora/ver/2
    function ver(){
        try {
            $editora = new ModelEditora($this->getParam(0));
            $this->set('editora', $editora);
            $this->set('livros', $editora->getLivros());
            $this->set('categorias', $editora->getCategorias());
            $this->setTitle($editora->nome);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 2);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('editora/lista');
        }
    }

    # Cadastrar Editora
    # renderiza a visão /view/Editora/cadastrar.tpl
    # url: /Editora/cadastrar
    function cadastrar(){
        $this->setTitle('Cadastrar Editora');
        $editora = new ModelEditora;
        $this->set('editora', $editora);
    }

    # Recebe os dados do formulário de cadastrar Editora e redireciona para a lista
    function post_cadastrar(){
        $editora = new ModelEditora;
        $this->set('editora', $editora);
        try {
            $editora->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
            new Msg("Cadastro realizado com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Editora/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Editar Editora
    # renderiza a visão /view/Editora/editar.tpl
    # url: /Editora/editar
    function editar(){
        try {
            $this->setTitle('Editar Editora');
            $editora = new ModelEditora($this->getParam(0));
            $this->set('editora', $editora);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('editora/lista');
        }
    }

    # Recebe os dados do formulário de editar Editora e redireciona para a lista
    function post_editar(){
        try {
            $editora = new ModelEditora($this->getParam(0));
            $this->set('editora', $editora);
            $editora->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
            new Msg("Edição realizada com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Editora/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    }