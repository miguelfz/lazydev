<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Core\Msg;
use Lazydev\Model\Livro as ModelLivro;
use Lazydev\Model\Categoria as ModelCategoria;
use Lazydev\Model\Editora as ModelEditora;

final class Livro extends \Lazydev\Core\Controller{ 

    # Lista de Livro
    # renderiza a visão /view/Livro/lista.tpl
    # url: /Livro/lista
    function lista(){
        $this->setTitle('Lista');
        $c = new Criteria();
        $c->setOrder('titulo');
        $livros=ModelLivro::getList($c);
        $this->set('livros', $livros);
    }

    # Visualiza um(a) Livro
    # renderiza a visão /view/Livro/ver.tpl
    # url: /Livro/ver/2
    function ver(){
        try {
            $livro = new ModelLivro($this->getParam(0));
            $this->set('livro', $livro);
            $this->set('livroautors', $livro->getLivroautors());
            $this->set('autors', $livro->getAutors());
            $this->setTitle($livro->titulo);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 2);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('livro/lista');
        }
    }

    # Cadastrar Livro
    # renderiza a visão /view/Livro/cadastrar.tpl
    # url: /Livro/cadastrar
    function cadastrar(){
        $this->setTitle('Cadastrar Livro');
        $livro = new ModelLivro;
        $this->set('livro', $livro);
        $this->set('categorias',  array_column((array)ModelCategoria::getList(), 'nome', 'cod'));
        $this->set('editoras',  array_column((array)ModelEditora::getList(), 'nome', 'cod'));
    }

    # Recebe os dados do formulário de cadastrar Livro e redireciona para a lista
    function post_cadastrar(){
        $livro = new ModelLivro;
        $this->set('livro', $livro);
        try {
            $livro->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
            new Msg("Cadastro realizado com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Livro/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Editar Livro
    # renderiza a visão /view/Livro/editar.tpl
    # url: /Livro/editar
    function editar(){
        try {
            $this->setTitle('Editar Livro');
            $livro = new ModelLivro($this->getParam(0));
            $this->set('livro', $livro);
            $this->set('categorias',  array_column((array)ModelCategoria::getList(), 'nome', 'cod'));
            $this->set('editoras',  array_column((array)ModelEditora::getList(), 'nome', 'cod'));
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('livro/lista');
        }
    }

    # Recebe os dados do formulário de editar Livro e redireciona para a lista
    function post_editar(){
        try {
            $livro = new ModelLivro($this->getParam(0));
            $this->set('livro', $livro);
            $livro->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
            new Msg("Edição realizada com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Livro/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    }