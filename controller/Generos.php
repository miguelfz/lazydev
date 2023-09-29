<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Core\Msg;
use Lazydev\Model\Generos as ModelGeneros;

final class Generos extends \Lazydev\Core\Controller{ 

    # Lista de Generos
    # renderiza a visão /view/Generos/lista.tpl
    # url: /Generos/lista
    function lista(){
        $this->setTitle('Lista');
        $c = new Criteria();
        $c->setOrder('nome_genero');
        $geneross=ModelGeneros::getList($c);
        $this->set('geneross', $geneross);
    }

    # Visualiza um(a) Generos
    # renderiza a visão /view/Generos/ver.tpl
    # url: /Generos/ver/2
    function ver(){
        try {
            $generos = new ModelGeneros($this->getParam(0));
            $this->set('generos', $generos);
            $this->set('filmess', $generos->getFilmess());
            $this->set('diretoress', $generos->getDiretoress());
            $this->setTitle($generos->nome_genero);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 2);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('generos/lista');
        }
    }

    # Cadastrar Generos
    # renderiza a visão /view/Generos/cadastrar.tpl
    # url: /Generos/cadastrar
    function cadastrar(){
        $this->setTitle('Cadastrar Generos');
        $generos = new ModelGeneros;
        $this->set('generos', $generos);
    }

    # Recebe os dados do formulário de cadastrar Generos e redireciona para a lista
    function post_cadastrar(){
        $generos = new ModelGeneros;
        $this->set('generos', $generos);
        try {
            $generos->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
            new Msg("Cadastro realizado com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Generos/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Editar Generos
    # renderiza a visão /view/Generos/editar.tpl
    # url: /Generos/editar
    function editar(){
        try {
            $this->setTitle('Editar Generos');
            $generos = new ModelGeneros($this->getParam(0));
            $this->set('generos', $generos);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('generos/lista');
        }
    }

    # Recebe os dados do formulário de editar Generos e redireciona para a lista
    function post_editar(){
            $this->setTitle('Editar Generos');
        try {
            $generos = new ModelGeneros($this->getParam(0));
            $this->set('generos', $generos);
            $generos->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
            new Msg("Edição realizada com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Generos/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Confirma a exclusão ou não de um(a) Generos
    # renderiza a visão /view/Generos/excluir.tpl
    # url: /Generos/excluir
    function excluir(){
            $this->setTitle('Excluir Generos');
        try {
            $generos = new ModelGeneros($this->getParam(0));
            $this->set('generos', $generos);
            new Msg("Exclusão realizada com sucesso.", 1);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Generos/lista');
        }
    }

    # Recebe o id via post e exclui Generos
    function post_excluir(){
        $this->go('Generos/lista');
    }

    }