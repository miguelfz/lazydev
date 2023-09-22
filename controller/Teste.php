<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Core\Msg;
use Lazydev\Model\Teste as ModelTeste;
use Lazydev\Model\Livroautor as ModelLivroautor;

final class Teste extends \Lazydev\Core\Controller{ 

    # Lista de Teste
    # renderiza a visão /view/Teste/lista.tpl
    # url: /Teste/lista
    function lista(){
        $this->setTitle('Lista');
        $c = new Criteria();
        $c->setOrder('cod');
        $testes=ModelTeste::getList($c);
        $this->set('testes', $testes);
    }

    # Visualiza um(a) Teste
    # renderiza a visão /view/Teste/ver.tpl
    # url: /Teste/ver/2
    function ver(){
        try {
            $teste = new ModelTeste($this->getParam(0));
            $this->set('teste', $teste);
            $this->setTitle($teste->cod);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 2);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('teste/lista');
        }
    }

    # Cadastrar Teste
    # renderiza a visão /view/Teste/cadastrar.tpl
    # url: /Teste/cadastrar
    function cadastrar(){
        $this->setTitle('Cadastrar Teste');
        $teste = new ModelTeste;
        $this->set('teste', $teste);
        $this->set('livroautors',  array_column((array)ModelLivroautor::getList(), 'codLivro', 'codAutor'));
    }

    # Recebe os dados do formulário de cadastrar Teste e redireciona para a lista
    function post_cadastrar(){
        $teste = new ModelTeste;
        $this->set('teste', $teste);
        try {
            $teste->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
            new Msg("Cadastro realizado com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Teste/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Editar Teste
    # renderiza a visão /view/Teste/editar.tpl
    # url: /Teste/editar
    function editar(){
        try {
            $this->setTitle('Editar Teste');
            $teste = new ModelTeste($this->getParam(0));
            $this->set('teste', $teste);
            $this->set('livroautors',  array_column((array)ModelLivroautor::getList(), 'codLivro', 'codAutor'));
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('teste/lista');
        }
    }

    # Recebe os dados do formulário de editar Teste e redireciona para a lista
    function post_editar(){
        try {
            $teste = new ModelTeste($this->getParam(0));
            $this->set('teste', $teste);
            $teste->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
            new Msg("Edição realizada com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Teste/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    }