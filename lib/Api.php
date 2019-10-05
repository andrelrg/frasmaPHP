<?php

use Router\Router;

namespace Api;
    //This component is the starter component.
    //If your project will use a different file organization, the definitions 
    //must be before the API class call.
    class Api{
        public static function go(){
            if (!defined("ROUTES_FILE")){
                define("ROUTES_FILE", $_SERVER['DOCUMENT_ROOT']."/Src/Routes.php");
            }else{
                define("ROUTES_FILE", $_SERVER['DOCUMENT_ROOT'].ROUTES_FILE);
                
            }
            require_once ROUTES_FILE;
            echo Router::execute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
        }
    }
