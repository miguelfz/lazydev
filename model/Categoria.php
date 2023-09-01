<?php
namespace Lazydev\Model;

class Categoria extends \Lazydev\Core\Record{

    const TABLE = 'categoria';
    const PK = 'id';

    public $id;
    public $nome;

}