<?php
namespace Lazydev\Model;

final class Diretores extends \Lazydev\Core\Record{ 

    const TABLE = 'diretores'; # tabela de referência
    const PK = 'cod_diretor'; # chave primária
    
    public $cod_diretor;
    public $nome_diretor;
    
    /**
    * Diretores possui Filmes(s)
    * @param Lazydev\Core\Criteria $criteria
    * @return Filmes[] array de Filmes
    */
    function getFilmess($criteria = NULL){
        return $this->hasMany('Filmes','cod_diretor', $criteria);
    }
    
    /**
    * Diretores possui Generos(s) via Filmes(NxN)
    * @param Lazydev\Core\Criteria $criteria
    * @return Generos[] array de Generos
    */
    function getGeneross($criteria = NULL){
        return $this->hasNN('Filmes', 'cod_diretor', 'Generos', 'cod_genero', $criteria);
    }
}