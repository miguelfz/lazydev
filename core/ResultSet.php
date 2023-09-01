<?php
namespace Lazydev\Core;

class ResultSet extends \ArrayObject {

    public $rows;
    public $nav = null;

    function __construct(Array $array) {
        parent::__construct($array);
    }

    public function getNav() {
        if (isset($this->nav)) {
            $nav = $this->nav;
            return $nav();
        }
    }

}
