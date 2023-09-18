<?php
namespace Lazydev\Model;

final class Livroautor extends \Lazydev\Core\Record{ 

    const TABLE = 'livroautor'; # tabela de referência
    const PK = ['codLivro', 'codAutor']; # chave primária
    
    public $codLivro;
    public $codAutor;
    
    /**
    * Livroautor pertence a Livro
    * @return Livro $livro
    */
    function getLivro(){
        return $this->belongsTo('Livro','codLivro');
    }
    
    /**
    * Livroautor pertence a Autor
    * @return Autor $autor
    */
    function getAutor(){
        return $this->belongsTo('Autor','codAutor');
    }
    
    /**
    * Livroautor possui Teste(s)
    * @param Lazydev\Core\Criteria $criteria
    * @return Teste[] array de Teste
    */
    function getTestes($criteria = NULL){
        return $this->hasMany('Teste',['codlivro', 'codAutor'], $criteria);
    }
    
    /**
    * Livroautor possui Teste(s)
    * @param Lazydev\Core\Criteria $criteria
    * @return Teste[] array de Teste
    */
    function getTestes2($criteria = NULL){
        return $this->hasMany('Teste',['codAutor', 'codlivro'], $criteria);
    }
}