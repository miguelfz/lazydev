<?php
date_default_timezone_set("America/Sao_Paulo");
header('Content-Type:text/html; charset=UTF-8');
use Lazydev\Core\Config;

##################################
# CONFIGURAÇÕES DO SGBD MySQL
##################################

# servidor do SGBD:
Config::set('db_host', 'localhost');

# usuario do SGBD:
Config::set('db_user', 'root');

# senha do SGBD:
Config::set('db_password', '');
# nome do banco de dados:

Config::set('db_name', 'filmes');


##################################
# CONFIGURAÇÕES GERAIS
##################################

# Modo de DEBUG - desativar antes de colocar em produção
Config::set('debug', true);

# TEMPLATE PADRÃO
# Ex: informe o nome da pasta do template que se encontra dentro do diretório /template
# padrão: default
Config::set('template', 'default');

# Página inicial - Controller
Config::set('indexController', 'Inicio');

#Página inicial - Método do controller
Config::set('indexAction', 'inicio');

# parâmetros get criptografados
Config::set('criptedGetParamns', []);


##################################
# ACL - Access Control List (opcional)
# 
# O framework passará a utilizar a classe /lib/util/Acl.php
# para gerenciar o controle de acesso de páginas e exibição de links
# de acordo com a especificação do atributo Acl::$acl
##################################

Config::set('enableACL', false);