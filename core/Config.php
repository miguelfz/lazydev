<?php
namespace Lazydev\Core;

class Config {

    public static $params = [];

    public static function get(string $name) {
        if (array_key_exists($name, self::$params)){
            return self::$params[$name];
        }
        return NULL;
    }

    public static function set(string $name, $value) {
        self::$params[$name] = $value;
    }

}