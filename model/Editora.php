<?php
namespace Lazydev\Model;

final class Editora extends \Lazydev\Core\Record{ 

    const TABLE = 'editora'; # tabela de referência
    const PK = 'cod'; # chave primária
    
    public $cod;
    public $nome;
    
    /**
    * Editora possui Livro(s)
    * @param Lazydev\Core\Criteria $criteria
    * @return Livro[] array de Livro
    */
    function getLivros($criteria = NULL){
        return $this->hasMany('Livro','codEditora', $criteria);
    }
    
    /**
    * Editora possui Categoria(s) via Livro(NxN)
    * @param Lazydev\Core\Criteria $criteria
    * @return Categoria[] array de Categoria
    */
    function getCategorias($criteria = NULL){
        return $this->hasNN('Livro', 'codEditora', 'Categoria', 'codCategoria', $criteria);
    }
}