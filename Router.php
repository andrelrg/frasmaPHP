<?php

namespace Router;

define("GET", "GET");
define("POST", "POST");
if (!defined("CONTROLLER")){
    define("CONTROLLER", "App\Controllers");
}


/**
 * Responsible for route management.
 * 
 * @author André Gaspar
 */
class Router {
    
    private static $getroutes = array();
    private static $postroutes = array();
    
    /**
     * Responsible for registering the routes.
     *
     * @param string $verb GET or POST.
     * @param string $pattern Route pattern.
     * @param string $class Name of the class to be instantiated for the 
     * route controller.
     * @param string $method Method to be called.
     * 
     * @return void
     */
    public static function route($verb, $pattern, $class, $method) {
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
        $infos = array(
            "class"  => $class,
            "method" => $method
        );
        if ($verb == GET){
            self::$getroutes[$pattern] = $infos;
        }else if ($verb == POST){
            self::$postroutes[$pattern] = $infos;
        }
    }
    
    /**
     * Function responsible for handling the request by redirecting to the 
     * correct route
     *
     * @param string $url request URL.
     * @param string $rMethod request method.
     * 
     * @return JSON with information of success or failure of the request, 
     * if it is missing information, and 404 if the route does not exist.
     */
    public static function execute($url, $rMethod) {
        if ($rMethod == GET){
            $routes = self::$getroutes;
        }
        if ($rMethod == POST){
            $routes = self::$postroutes;
        }
        $url = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
        
        foreach ($routes as $pattern => $infos) {
            if (preg_match($pattern, $url)) {
                $class = sprintf("%s\%s", CONTROLLER, $infos['class']);
                $method = $infos['method'];
                $requestResult = (new $class($_GET, $_POST))->$method();
                header('Content-Type: application/json');
                http_response_code($requestResult['status']);
                return json_encode($requestResult['content']);
            }
        }

        return http_response_code(404);
    }
}
