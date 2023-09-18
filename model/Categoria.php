<?php
namespace Lazydev\Model;

final class Categoria extends \Lazydev\Core\Record{ 

    const TABLE = 'categoria'; # tabela de referência
    const PK = 'cod'; # chave primária
    
    public $cod;
    public $nome;
    
    /**
    * Categoria possui Livro(s)
    * @param Lazydev\Core\Criteria $criteria
    * @return Livro[] array de Livro
    */
    function getLivros($criteria = NULL){
        return $this->hasMany('Livro','codCategoria', $criteria);
    }
    
    /**
    * Categoria possui Editora(s) via Livro(NxN)
    * @param Lazydev\Core\Criteria $criteria
    * @return Editora[] array de Editora
    */
    function getEditoras($criteria = NULL){
        return $this->hasNN('Livro', 'codCategoria', 'Editora', 'codEditora', $criteria);
    }
}