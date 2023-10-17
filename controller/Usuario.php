<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Core\Msg;
use Lazydev\Model\Usuario as ModelUsuario;

final class Usuario extends \Lazydev\Core\Controller{ 

    # Lista de Usuario
    # renderiza a visão /view/Usuario/lista.tpl
    # url: /Usuario/lista
    function lista(){
        $this->setTitle('Lista');
        $c = new Criteria();
        $c->setOrder('email');
        $usuarios=ModelUsuario::getList($c);
        $this->set('usuarios', $usuarios);
    }

    # Visualiza um(a) Usuario
    # renderiza a visão /view/Usuario/ver.tpl
    # url: /Usuario/ver/2
    function ver(){
        try {
            $usuario = new ModelUsuario($this->getParam(0));
            $this->set('usuario', $usuario);
            $this->setTitle($usuario->email);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 2);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('usuario/lista');
        }
    }

    # Cadastrar Usuario
    # renderiza a visão /view/Usuario/cadastrar.tpl
    # url: /Usuario/cadastrar
    function cadastrar(){
        $this->setTitle('Cadastrar Usuario');
        $usuario = new ModelUsuario;
        $this->set('usuario', $usuario);
    }

    # Recebe os dados do formulário de cadastrar Usuario e redireciona para a lista
    function post_cadastrar(){
        $usuario = new ModelUsuario;
        $this->set('usuario', $usuario);
        try {
            $usuario->save(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS), true);
            new Msg("Cadastro realizado com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Usuario/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Editar Usuario
    # renderiza a visão /view/Usuario/editar.tpl
    # url: /Usuario/editar
    function editar(){
        try {
            $this->setTitle('Editar Usuario');
            $usuario = new ModelUsuario($this->getParam(0));
            $this->set('usuario', $usuario);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('usuario/lista');
        }
    }

    # Recebe os dados do formulário de editar Usuario e redireciona para a lista
    function post_editar(){
            $this->setTitle('Editar Usuario');
        try {
            $usuario = new ModelUsuario($this->getParam(0));
            $this->set('usuario', $usuario);
            $dados = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $dados['senha'] = md5($dados['senha']);
            $usuario->save($dados);
            new Msg("Edição realizada com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->go($this->getParam('url'));
            }
            $this->go('Usuario/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    # Confirma a exclusão ou não de um(a) Usuario
    # renderiza a visão /view/Usuario/excluir.tpl
    # url: /Usuario/excluir
    function excluir(){
            $this->setTitle('Excluir Usuario');
        try {
            $usuario = new ModelUsuario($this->getParam(0));
            $this->set('usuario', $usuario);
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Usuario/lista');
        }
    }

    # Recebe o id via post e exclui Usuario
    function post_excluir(){
        try {
            $usuario = new ModelUsuario($this->getParam(0));
            $usuario->delete();
            new Msg("Exclusão realizada com sucesso.", 1);
            if ($this->getParam('url')) {
                $this->goUrl($this->getParam('url'));
            }
            $this->go('Usuario/lista');
        } catch (\Exception $e) {
            new Msg($e->getMessage(), 3);
        }
    }

    }