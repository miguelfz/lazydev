<?php
namespace Lazydev\Model;

final class Autor2 extends \Lazydev\Core\Record{ 

    const TABLE = 'autor2'; # tabela de referência
    const PK = ''; # chave primária
    
    public $cod;
    public $nome;
    public $pais;
}