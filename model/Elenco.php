<?php
namespace Lazydev\Model;

final class Elenco extends \Lazydev\Core\Record{ 

    const TABLE = 'elenco'; # tabela de referência
    const PK = ['cod_filme', 'cod_ator']; # chave primária
    
    public $cod_filme;
    public $cod_ator;
    
    /**
    * Elenco pertence a Atores
    * @return Atores $atores
    */
    function getAtores(){
        return $this->belongsTo('Atores','cod_ator');
    }
    
    /**
    * Elenco pertence a Filmes
    * @return Filmes $filmes
    */
    function getFilmes(){
        return $this->belongsTo('Filmes','cod_filme');
    }
}