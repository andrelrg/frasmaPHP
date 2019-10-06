<?php

namespace Frasma;

use Frasma\Router;

/**
 * Class Api
 * This component is the starter component.
 * If your project will use a different file organization, the definitions must be before the API class call.
 * @package Frasma
 */
class Api{
    /**
     * Starts the application.
     */
    public static function go(){
        if (!defined("OVERRIDE")){
            require_once dirname(__FILE__)."/Settings/Settings.php";
        }
        require_once ROUTES_FILE;

        echo Router::execute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    }
}
