<?php
namespace Lazydev\Core;
/**
 * classe Session
 * 
 * @author Miguel
 */
class Session {

    /**
     * Serializa e salva na sessão uma variável de qualquer tipo (obj, array, string,...)
     * 
     * @param String $varName
     * @param mixed $value
     */
    public static function set($varName, $value) {
        $_SESSION[$varName . md5(PATH)] = serialize($value);
    }

    /**
     * retorna o valor de uma sessão a partir do nome da variável.
     * 
     * @param String $varName
     * @return mixed value
     */
    public static function get($varName) {
        if (isset($_SESSION[$varName . md5(PATH)])) {
            return unserialize($_SESSION[$varName . md5(PATH)]);
        } 
        return NULL;
    }

    /**
     * Serializa e salva em cookie uma variável de qualquer tipo (obj, array, string,...)
     * 
     * @param String $varName
     * @param mixed $value
     * @param int $tempo em segundos de validade do cookie.
     */
    public static function setCookie($varName, $value, $tempo = 1) {
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;        
        setcookie($varName. md5(PATH) , Cript::cript(serialize($value)), $tempo, '/', $domain, false);
    }

    /**
     * retorna o valor de um cookie a partir do nome da variável.
     * 
     * @param String $varName
     * @return mixed value
     */
    public static function getCookie($varName) {
        $cookie = filter_input(INPUT_COOKIE, $varName. md5(PATH));
        if ($cookie) {
            return unserialize(Cript::decript($cookie));
        }
        return NULL;
    }
}

