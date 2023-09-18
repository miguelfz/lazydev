<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Core\Msg;
use Lazydev\Model\Livroautor as ModelLivroautor;
use Lazydev\Model\Livro as ModelLivro;
use Lazydev\Model\Autor as ModelAutor;

final class Livroautor extends \Lazydev\Core\Controller{ 

    # Lista de Livroautor
    # renderiza a visão /view/Livroautor/lista.tpl
    # url: /Livroautor/lista
    function lista(){
        $this->setTitle('Lista');
        $c = new Criteria();
        $c->setOrder('codLivro');
        $livroautors=ModelLivroautor::getList($c);
        $this->set('livroautors', $livroautors);
    }

    # Visualiza um(a) Livroautor
    # renderiza a visão /view/Livroautor/ver.tpl
    # url: /Livroautor/ver/2
    function ver(){
        try {
            $livroautor = new ModelLivroautor($this->getParam(0), $this->getParam(1));
            $this->set('livroautor', $livroautor);
            $this->set('testes', $livroautor->getTestes());
            $this->set('testes', $livroautor->getTestes());
            $this->setTitle($livroautor->codLivro);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 2);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('livroautor', 'lista');
        }
    }

    # Cadastrar Livroautor
    # renderiza a visão /view/Livroautor/cadastrar.tpl
    # url: /Livroautor/cadastrar
    function cadastrar(){
        $this->setTitle('Cadastrar Livroautor');
        $livroautor = new ModelLivroautor;
        $this->set('livroautor', $livroautor);
        $this->set('livros',  array_column((array)ModelLivro::getList(), 'titulo', 'cod'));
        $this->set('autors',  array_column((array)ModelAutor::getList(), 'nome', 'cod'));
    }

    # Recebe os dados do formulário de cadastrar Livroautor e redireciona para a lista
    function post_cadastrar(){
        $this->go('Livroautor/lista');
    }

    # Editar Livroautor
    # renderiza a visão /view/Livroautor/editar.tpl
    # url: /Livroautor/editar
    function editar(){
        $this->setTitle('Editar');
    }

    # Recebe os dados do formulário de editar Livroautor e redireciona para a lista
    function post_editar(){
        $this->go('Livroautor/lista');
    }

    }