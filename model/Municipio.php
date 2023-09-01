<?php
namespace Lazydev\Model;

class Municipio extends \Lazydev\Core\Record{

    const TABLE = 'municipio';
    const PK = 'id';

    public $id;
    public $nome;

    public function getImoveis(){
        return $this->hasMany('Imovel','municipio_id');
    }

}