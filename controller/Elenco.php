<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Core\Msg;
use Lazydev\Model\Elenco as ModelElenco;
use Lazydev\Model\Filmes as ModelFilmes;
use Lazydev\Model\Atores as ModelAtores;

final class Elenco extends \Lazydev\Core\Controller{ 

    # Lista de Elenco
    # renderiza a visão /view/Elenco/lista.tpl
    # url: /Elenco/lista
    function lista(){
        $this->setTitle('Lista');
        $c = new Criteria();
        $c->setOrder('cod_filme');
        $elencos=ModelElenco::getList($c);
        $this->set('elencos', $elencos);
    }

    # Cadastrar Elenco
    # renderiza a visão /view/Elenco/cadastrar.tpl
    # url: /Elenco/cadastrar
    function cadastrar(){
        $this->setTitle('Cadastrar Elenco');
        $elenco = new ModelElenco;
        $this->set('elenco', $elenco);
        $this->set('filmess',  array_column((array)ModelFilmes::getList(), 'titulo_original', 'cod_filme'));
        $this->set('atoress',  array_column((array)ModelAtores::getList(), 'nome_ator', 'cod_ator'));
    }

    # Recebe os dados do formulário de cadastrar Elenco e redireciona para a lista
    function post_cadastrar(){
        $elenco = new ModelElenco;
        $this->set('elenco', $elenco);
        try {
            $elenco->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
            new Msg("Cadastro realizado com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Elenco/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Editar Elenco
    # renderiza a visão /view/Elenco/editar.tpl
    # url: /Elenco/editar
    function editar(){
        try {
            $this->setTitle('Editar Elenco');
            $elenco = new ModelElenco($this->getParam(0), $this->getParam(1));
            $this->set('elenco', $elenco);
            $this->set('filmess',  array_column((array)ModelFilmes::getList(), 'titulo_original', 'cod_filme'));
            $this->set('atoress',  array_column((array)ModelAtores::getList(), 'nome_ator', 'cod_ator'));
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('elenco/lista');
        }
    }

    # Recebe os dados do formulário de editar Elenco e redireciona para a lista
    function post_editar(){
            $this->setTitle('Editar Elenco');
        try {
            $elenco = new ModelElenco($this->getParam(0), $this->getParam(1));
            $this->set('elenco', $elenco);
            $elenco->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
            new Msg("Edição realizada com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Elenco/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Confirma a exclusão ou não de um(a) Elenco
    # renderiza a visão /view/Elenco/excluir.tpl
    # url: /Elenco/excluir
    function excluir(){
            $this->setTitle('Excluir Elenco');
        try {
            $elenco = new ModelElenco($this->getParam(0), $this->getParam(1));
            $this->set('elenco', $elenco);
            new Msg("Exclusão realizada com sucesso.", 1);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Elenco/lista');
        }
    }

    # Recebe o id via post e exclui Elenco
    function post_excluir(){
        $this->go('Elenco/lista');
    }

    }