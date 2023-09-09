<?php
namespace Lazydev\Model;

final class Teste extends \Lazydev\Core\Record{ 

    const TABLE = 'teste'; # tabela de referência
    const PK = 'cod'; # chave primária
    
    public $cod;
    public $codlivro;
    public $codAutor;
    
    /**
    * Teste pertence a Livroautor
    * @return Livroautor $livroautor
    */
    function getLivroautor(){
        return $this->belongsTo('Livroautor','codlivro');
    }
    
    /**
    * Teste pertence a Livroautor
    * @return Livroautor $livroautor
    */
    function getLivroautor2(){
        return $this->belongsTo('Livroautor','codAutor');
    }
}