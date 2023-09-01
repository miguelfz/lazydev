<?php
namespace Lazydev\Model;

class Imovel extends \Lazydev\Core\Record{

    const TABLE = 'imovel';
    const PK = 'id';

    public $id;
    public $nome;
    public $municipio_id;

}