<?php

namespace Lazydev\Core;

use Exception;

class Cript
{
    private static $method = "AES-256-CBC";
    private static  $key = '$fKvfE1LtQ1szsSljq51ro8IC6f7Hx6YC/'; 
    private static $iv = 'i^isoTsqx1A8[{]C';#16 caracteres

    public static function cript($data)
    {
        try{
            return base64_encode(openssl_encrypt($data, self::$method, self::$key, 0, self::$iv));
        } catch (\Exception $exc) {
            new Msg("Não foi possível criptografar ".serialize($data), 5);
            return $data;
        }
    }

    public static function decript($data)
    {
        if(is_numeric($data)){
            return $data;
        }
        try {
            $decString = openssl_decrypt(base64_decode($data), self::$method, self::$key, 0, self::$iv);
            if(!$decString){
                throw new Exception("");
            }
            return $decString;
        } catch (\Exception $exc) {
            new Msg("Não foi possível descriptografar ".serialize($data),5);
            return $data;
        }
    }
}
