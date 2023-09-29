<?php

namespace Lazydev\Core;

class ResultSet extends \ArrayObject
{

    public $rows;
    public $nav = null;

    function __construct(array $array)
    {
        array_walk($array, function ($v) {
            $v->lazyobjectsavedondatabase = true;
        });
        parent::__construct($array);
    }

    public function getNav()
    {
        if (isset($this->nav)) {
            $nav = $this->nav;
            return $nav();
        }
    }
}
