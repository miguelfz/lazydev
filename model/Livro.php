<?php
namespace Lazydev\Model;

final class Livro extends \Lazydev\Core\Record{ 

    const TABLE = 'livro'; # tabela de referência
    const PK = 'cod'; # chave primária
    
    public $cod;
    public $titulo;
    public $edicao;
    public $idioma;
    public $exemplares;
    public $paginas;
    public $codCategoria;
    public $codEditora;
}