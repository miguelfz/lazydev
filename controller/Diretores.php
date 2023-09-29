<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Core\Msg;
use Lazydev\Model\Diretores as ModelDiretores;

final class Diretores extends \Lazydev\Core\Controller{ 

    # Lista de Diretores
    # renderiza a visão /view/Diretores/lista.tpl
    # url: /Diretores/lista
    function lista(){
        $this->setTitle('Lista');
        $c = new Criteria();
        $c->setOrder('nome_diretor');
        $diretoress=ModelDiretores::getList($c);
        $this->set('diretoress', $diretoress);
    }

    # Visualiza um(a) Diretores
    # renderiza a visão /view/Diretores/ver.tpl
    # url: /Diretores/ver/2
    function ver(){
        try {
            $diretores = new ModelDiretores($this->getParam(0));
            $this->set('diretores', $diretores);
            $this->set('filmess', $diretores->getFilmess());
            $this->set('geneross', $diretores->getGeneross());
            $this->setTitle($diretores->nome_diretor);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 2);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('diretores/lista');
        }
    }

    # Cadastrar Diretores
    # renderiza a visão /view/Diretores/cadastrar.tpl
    # url: /Diretores/cadastrar
    function cadastrar(){
        $this->setTitle('Cadastrar Diretores');
        $diretores = new ModelDiretores;
        $this->set('diretores', $diretores);
    }

    # Recebe os dados do formulário de cadastrar Diretores e redireciona para a lista
    function post_cadastrar(){
        $diretores = new ModelDiretores;
        $this->set('diretores', $diretores);
        try {
            $diretores->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
            new Msg("Cadastro realizado com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Diretores/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Editar Diretores
    # renderiza a visão /view/Diretores/editar.tpl
    # url: /Diretores/editar
    function editar(){
        try {
            $this->setTitle('Editar Diretores');
            $diretores = new ModelDiretores($this->getParam(0));
            $this->set('diretores', $diretores);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('diretores/lista');
        }
    }

    # Recebe os dados do formulário de editar Diretores e redireciona para a lista
    function post_editar(){
            $this->setTitle('Editar Diretores');
        try {
            $diretores = new ModelDiretores($this->getParam(0));
            $this->set('diretores', $diretores);
            $diretores->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
            new Msg("Edição realizada com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Diretores/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Confirma a exclusão ou não de um(a) Diretores
    # renderiza a visão /view/Diretores/excluir.tpl
    # url: /Diretores/excluir
    function excluir(){
            $this->setTitle('Excluir Diretores');
        try {
            $diretores = new ModelDiretores($this->getParam(0));
            $this->set('diretores', $diretores);
            new Msg("Exclusão realizada com sucesso.", 1);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Diretores/lista');
        }
    }

    # Recebe o id via post e exclui Diretores
    function post_excluir(){
        $this->go('Diretores/lista');
    }

    }