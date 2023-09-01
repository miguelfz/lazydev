<?php

namespace Lazydev\Core;

class Cript
{
    private static $method = "AES-256-CBC";
    private static  $key = '$fKvfE1LtQ1szsSljq51ro8IC6f7Hx6YC/'; 
    private static $iv = 'i^isoTsqx1A8[{]C';#16 caracteres

    public static function cript($data)
    {
        return base64_encode(openssl_encrypt($data, self::$method, self::$key, 0, self::$iv));
    }

    public static function decript($data)
    {
        if(is_numeric($data)){
            return $data;
        }
        try {
            return openssl_decrypt(base64_decode($data), self::$method, self::$key, 0, self::$iv);
        } catch (\Exception $exc) {
            return $data;
        }
    }
}
