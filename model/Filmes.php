<?php
namespace Lazydev\Model;

final class Filmes extends \Lazydev\Core\Record{ 

    const TABLE = 'filmes'; # tabela de referência
    const PK = 'cod_filme'; # chave primária
    
    public $cod_filme;
    public $titulo_original;
    public $titulo;
    public $duracao;
    public $ano_lancamento;
    public $cod_diretor;
    public $cod_genero;
    
    /**
    * Filmes pertence a Diretores
    * @return Diretores $diretores
    */
    function getDiretores(){
        return $this->belongsTo('Diretores','cod_diretor');
    }
    
    /**
    * Filmes pertence a Generos
    * @return Generos $generos
    */
    function getGeneros(){
        return $this->belongsTo('Generos','cod_genero');
    }
    
    /**
    * Filmes possui Elenco(s)
    * @param Lazydev\Core\Criteria $criteria
    * @return Elenco[] array de Elenco
    */
    function getElencos($criteria = NULL){
        return $this->hasMany('Elenco','cod_filme', $criteria);
    }
    
    /**
    * Filmes possui Atores(s) via Elenco(NxN)
    * @param Lazydev\Core\Criteria $criteria
    * @return Atores[] array de Atores
    */
    function getAtoress($criteria = NULL){
        return $this->hasNN('Elenco', 'cod_filme', 'Atores', 'cod_ator', $criteria);
    }
}