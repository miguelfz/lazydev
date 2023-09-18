<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Model\Autor2 as ModelAutor2;

final class Autor2 extends \Lazydev\Core\Controller{ 

    # Lista de Autor2
    # renderiza a visão /view/Autor2/lista.tpl
    # url: /Autor2/lista
    function lista(){
        $this->setTitle('Lista');
        $c = new Criteria();
        $c->setOrder('nome');
        $autor2s=ModelAutor2::getList($c);
        $this->set('autor2s', $autor2s);
    }

    # Visualiza um(a) Autor2
    # renderiza a visão /view/Autor2/ver.tpl
    # url: /Autor2/ver/2
    function ver(){
        $this->setTitle('Ver');
    }

    # Cadastrar Autor2
    # renderiza a visão /view/Autor2/cadastrar.tpl
    # url: /Autor2/cadastrar
    function cadastrar(){
        $this->setTitle('Cadastrar');
    }

    # Recebe os dados do formulário de cadastrar Autor2 e redireciona para a lista
    function post_cadastrar(){
        $this->go('Autor2/lista');
    }

    # Editar Autor2
    # renderiza a visão /view/Autor2/editar.tpl
    # url: /Autor2/editar
    function editar(){
        $this->setTitle('Editar');
    }

    # Recebe os dados do formulário de editar Autor2 e redireciona para a lista
    function post_editar(){
        $this->go('Autor2/lista');
    }

    }