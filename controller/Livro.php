<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Model\Livro as ModelLivro;

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
        $this->setTitle('Ver');
    }

    # Cadastrar Livro
    # renderiza a visão /view/Livro/cadastrar.tpl
    # url: /Livro/cadastrar
    function cadastrar(){
        $this->setTitle('Cadastrar');
    }

    # Recebe os dados do formulário de cadastrar Livro e redireciona para a lista
    function post_cadastrar(){
        $this->go('Livro/lista');
    }

    # Editar Livro
    # renderiza a visão /view/Livro/editar.tpl
    # url: /Livro/editar
    function editar(){
        $this->setTitle('Editar');
    }

    # Recebe os dados do formulário de editar Livro e redireciona para a lista
    function post_editar(){
        $this->go('Livro/lista');
    }

    }