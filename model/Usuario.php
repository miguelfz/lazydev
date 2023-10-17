<?php
namespace Lazydev\Model;

final class Usuario extends \Lazydev\Core\Record{ 

    const TABLE = 'usuario'; # tabela de referência
    const PK = 'cod'; # chave primária
    
    public $cod;
    public $email;
    public $senha;
}