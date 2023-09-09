<?php
namespace Lazydev\Model;

final class Livro extends \Lazydev\Core\Record{ 

    const TABLE = 'livro'; # tabela de referência
    const PK = 'cod'; # chave primária
    
    public $cod;
    public $titulo;
    public $edicao;
    public $idioma;
    public $exemplares;
    public $paginas;
    public $codCategoria;
    public $codEditora;
    
    /**
    * Livro pertence a Categoria
    * @return Categoria $categoria
    */
    function getCategoria(){
        return $this->belongsTo('Categoria','codCategoria');
    }
    
    /**
    * Livro pertence a Editora
    * @return Editora $editora
    */
    function getEditora(){
        return $this->belongsTo('Editora','codEditora');
    }
    
    /**
    * Livro possui Livroautor(s)
    * @param Lazydev\Core\Criteria $criteria
    * @return Livroautor[] array de Livroautor
    */
    function getLivroautors($criteria = NULL){
        return $this->hasMany('Livroautor','codLivro',$criteria);
    }
    
    /**
    * Livro possui Autor(s) via Livroautor(NxN)
    * @param Lazydev\Core\Criteria $criteria
    * @return Autor[] array de Autor
    */
    function getAutors($criteria = NULL){
        return $this->hasNN('Livroautor', 'codLivro', 'Autor', 'codAutor', $criteria);
    }
}