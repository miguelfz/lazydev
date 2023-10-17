<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Core\Msg;
use Lazydev\Model\Filmes as ModelFilmes;
use Lazydev\Model\Diretores as ModelDiretores;
use Lazydev\Model\Generos as ModelGeneros;

final class Filmes extends \Lazydev\Core\Controller{ 

    # Lista de Filmes
    # renderiza a visão /view/Filmes/lista.tpl
    # url: /Filmes/lista
    function lista(){
        $this->setTitle('Lista');
        $c = new Criteria();
        $c->setOrder('titulo_original');
        $filmess=ModelFilmes::getList($c);
        $this->set('filmess', $filmess);
    }

    # Visualiza um(a) Filmes
    # renderiza a visão /view/Filmes/ver.tpl
    # url: /Filmes/ver/2
    function ver(){
        try {
            $filmes = new ModelFilmes($this->getParam(0));
            $this->set('filmes', $filmes);
            $this->set('elencos', $filmes->getElencos());
            $this->set('atoress', $filmes->getAtoress());
            $this->setTitle($filmes->titulo_original);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 2);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('filmes/lista');
        }
    }

    # Cadastrar Filmes
    # renderiza a visão /view/Filmes/cadastrar.tpl
    # url: /Filmes/cadastrar
    function cadastrar(){
        $this->setTitle('Cadastrar Filmes');
        $filmes = new ModelFilmes;
        $this->set('filmes', $filmes);
        $this->set('diretoress',  array_column((array)ModelDiretores::getList(), 'nome_diretor', 'cod_diretor'));
        $this->set('geneross',  array_column((array)ModelGeneros::getList(), 'nome_genero', 'cod_genero'));
    }

    # Recebe os dados do formulário de cadastrar Filmes e redireciona para a lista
    function post_cadastrar(){
        $filmes = new ModelFilmes;
        $this->set('filmes', $filmes);
        try {
            $filmes->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS), true);
            new Msg("Cadastro realizado com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Filmes/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Editar Filmes
    # renderiza a visão /view/Filmes/editar.tpl
    # url: /Filmes/editar
    function editar(){
        try {
            $this->setTitle('Editar Filmes');
            $filmes = new ModelFilmes($this->getParam(0));
            $this->set('filmes', $filmes);
            $this->set('diretoress',  array_column((array)ModelDiretores::getList(), 'nome_diretor', 'cod_diretor'));
            $this->set('geneross',  array_column((array)ModelGeneros::getList(), 'nome_genero', 'cod_genero'));
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('filmes/lista');
        }
    }

    # Recebe os dados do formulário de editar Filmes e redireciona para a lista
    function post_editar(){
            $this->setTitle('Editar Filmes');
        try {
            $filmes = new ModelFilmes($this->getParam(0));
            $this->set('filmes', $filmes);
            $filmes->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
            new Msg("Edição realizada com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Filmes/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Confirma a exclusão ou não de um(a) Filmes
    # renderiza a visão /view/Filmes/excluir.tpl
    # url: /Filmes/excluir
    function excluir(){
            $this->setTitle('Excluir Filmes');
        try {
            $filmes = new ModelFilmes($this->getParam(0));
            $this->set('filmes', $filmes);
            new Msg("Exclusão realizada com sucesso.", 1);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Filmes/lista');
        }
    }

    # Recebe o id via post e exclui Filmes
    function post_excluir(){
        $this->go('Filmes/lista');
    }

    }