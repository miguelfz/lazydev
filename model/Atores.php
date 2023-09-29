<?php
namespace Lazydev\Model;

final class Atores extends \Lazydev\Core\Record{ 

    const TABLE = 'atores'; # tabela de referência
    const PK = 'cod_ator'; # chave primária
    
    public $cod_ator;
    public $nome_ator;
    
    /**
    * Atores possui Elenco(s)
    * @param Lazydev\Core\Criteria $criteria
    * @return Elenco[] array de Elenco
    */
    function getElencos($criteria = NULL){
        return $this->hasMany('Elenco','cod_ator', $criteria);
    }
    
    /**
    * Atores possui Filmes(s) via Elenco(NxN)
    * @param Lazydev\Core\Criteria $criteria
    * @return Filmes[] array de Filmes
    */
    function getFilmess($criteria = NULL){
        return $this->hasNN('Elenco', 'cod_ator', 'Filmes', 'cod_filme', $criteria);
    }
}