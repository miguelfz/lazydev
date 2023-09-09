<?php
namespace Lazydev\Model;

final class Editora extends \Lazydev\Core\Record{ 

    const TABLE = 'editora'; # tabela de referência
    const PK = 'cod'; # chave primária
    
    public $cod;
    public $nome;
    
    /**
    * Editora possui Livro(s)
    * @return Livro[] array de Livro
    */
    function getLivros($criteria = NULL){
        return $this->hasMany('Livro','codEditora',$criteria);
    }
}