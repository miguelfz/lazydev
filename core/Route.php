<?php
namespace Lazydev\Core;

class Route {

    private static $route = [];

    /**
     * Define controler e action com base em um rota   
     * @author Samue S Gonçalves <samuuel.gs@gmail.com>
     * <b>Exemplo: </b> Route::set('contatar', 'Index', 'contato');<br>
     * Quando acessada a aplicação/contatar, o controler a ser
     * acessado é o Index->contato().
     * 
     * @param string $rota Nome da rota
     * @param string $controller Controler responsavel pela rora
     * @param string $action Método do controler responsável pela view e model
     */
    public static function set(string $rota, string $controller, string $action = 'inicio') {
        self::$route[strtolower($rota)] = [$controller, $action];
    }

    public static function checkRoute(string $controller) {
        $rota = strtolower($controller);
        if (array_key_exists($rota, self::$route)) {
            return self::$route[$rota];
        } else {
            return [];
        }
    }

}
