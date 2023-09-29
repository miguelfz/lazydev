<?php
namespace Lazydev\Model;

final class Generos extends \Lazydev\Core\Record{ 

    const TABLE = 'generos'; # tabela de referência
    const PK = 'cod_genero'; # chave primária
    
    public $cod_genero;
    public $nome_genero;
    
    /**
    * Generos possui Filmes(s)
    * @param Lazydev\Core\Criteria $criteria
    * @return Filmes[] array de Filmes
    */
    function getFilmess($criteria = NULL){
        return $this->hasMany('Filmes','cod_genero', $criteria);
    }
    
    /**
    * Generos possui Diretores(s) via Filmes(NxN)
    * @param Lazydev\Core\Criteria $criteria
    * @return Diretores[] array de Diretores
    */
    function getDiretoress($criteria = NULL){
        return $this->hasNN('Filmes', 'cod_genero', 'Diretores', 'cod_diretor', $criteria);
    }
}