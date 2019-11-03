<?php

namespace Frasma;

/**
 * Responsible for route management.
 *
 * @author André Gaspar
 */
class Router {

    private static $gets = array();
    private static $posts = array();
    private static $deletes = array();
    private static $puts = array();
    private static $heads = array();

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

        self::getMethodRoutes($verb)[$pattern] = $infos;
    }

    /**
     * Function responsible for handling the request by redirecting to the
     * correct route
     *
     * @param string $url request URL.
     * @param string $rMethod request method.
     *
     * @return string with information of success or failure of the request,
     * if it is missing information, and 404 if the route does not exist.
     */
    public static function execute($url, $rMethod) : string {
        $routes = self::getMethodRoutes($rMethod);

        $url = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

        foreach ($routes as $pattern => $infos) {
            if (preg_match($pattern, $url)) {
                $class = sprintf("%s\%s", CONTROLLER, $infos['class']);
                $method = $infos['method'];
                try{
                    $requestResult = (new $class($_GET, $_POST))->$method();
                }catch(\Exception $e){
                    http_response_code(500);
                    if (VERBOSE){
                        return print_r($e);
                    }else{
                        return "The request resulted in a problem";
                    }
                }
                header('Content-Type: application/json');
                http_response_code($requestResult['status']);
                return json_encode($requestResult['content']);
            }
        }

        http_response_code(404);
        return NOTFOUND;
    }

    private static function &getMethodRoutes($verb) {
        switch ($verb){
            case GET:
                return self::$gets;
            case POST:
                return self::$posts;
            case PUT:
                return self::$puts;
            case DELETE:
                return self::$deletes;
            case HEAD:
                return self::$heads;
        }
    }
}
