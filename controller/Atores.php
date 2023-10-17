<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Core\Msg;
use Lazydev\Model\Atores as ModelAtores;

final class Atores extends \Lazydev\Core\Controller{ 

    # Lista de Atores
    # renderiza a visão /view/Atores/lista.tpl
    # url: /Atores/lista
    function lista(){
        $this->setTitle('Lista');
        $c = new Criteria();
        $c->setOrder('nome_ator');
        $atoress=ModelAtores::getList($c);
        $this->set('atoress', $atoress);
    }

    # Visualiza um(a) Atores
    # renderiza a visão /view/Atores/ver.tpl
    # url: /Atores/ver/2
    function ver(){
        try {
            $atores = new ModelAtores($this->getParam(0));
            $this->set('atores', $atores);
            $this->set('elencos', $atores->getElencos());
            $this->set('filmess', $atores->getFilmess());
            $this->setTitle($atores->nome_ator);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 2);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('atores/lista');
        }
    }

    # Cadastrar Atores
    # renderiza a visão /view/Atores/cadastrar.tpl
    # url: /Atores/cadastrar
    function cadastrar(){
        $this->setTitle('Cadastrar Atores');
        $atores = new ModelAtores;
        $this->set('atores', $atores);
    }

    # Recebe os dados do formulário de cadastrar Atores e redireciona para a lista
    function post_cadastrar(){
        $atores = new ModelAtores;
        $this->set('atores', $atores);
        try {
            $atores->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS), true);
            new Msg("Cadastro realizado com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Atores/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Editar Atores
    # renderiza a visão /view/Atores/editar.tpl
    # url: /Atores/editar
    function editar(){
        try {
            $this->setTitle('Editar Atores');
            $atores = new ModelAtores($this->getParam(0));
            $this->set('atores', $atores);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('atores/lista');
        }
    }

    # Recebe os dados do formulário de editar Atores e redireciona para a lista
    function post_editar(){
            $this->setTitle('Editar Atores');
        try {
            $atores = new ModelAtores($this->getParam(0));
            $this->set('atores', $atores);
            $atores->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
            new Msg("Edição realizada com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Atores/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Confirma a exclusão ou não de um(a) Atores
    # renderiza a visão /view/Atores/excluir.tpl
    # url: /Atores/excluir
    function excluir(){
            $this->setTitle('Excluir Atores');
        try {
            $atores = new ModelAtores($this->getParam(0));
            $this->set('atores', $atores);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Atores/lista');
        }
    }

    # Recebe o id via post e exclui Atores
    function post_excluir(){
        try {
            $atores = new ModelAtores($this->getParam(0));
            $atores->delete();
            new Msg("Exclusão realizada com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Atores/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    }