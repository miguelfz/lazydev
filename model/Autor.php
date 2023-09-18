<?php
namespace Lazydev\Model;

final class Autor extends \Lazydev\Core\Record{ 

    const TABLE = 'autor'; # tabela de referência
    const PK = 'cod'; # chave primária
    
    public $cod;
    public $nome;
    public $pais;
    
    /**
    * Autor possui Livroautor(s)
    * @param Lazydev\Core\Criteria $criteria
    * @return Livroautor[] array de Livroautor
    */
    function getLivroautors($criteria = NULL){
        return $this->hasMany('Livroautor','codAutor', $criteria);
    }
    
    /**
    * Autor possui Livro(s) via Livroautor(NxN)
    * @param Lazydev\Core\Criteria $criteria
    * @return Livro[] array de Livro
    */
    function getLivros($criteria = NULL){
        return $this->hasNN('Livroautor', 'codAutor', 'Livro', 'codLivro', $criteria);
    }
}