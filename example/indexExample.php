<?php

    // Composer Autoload.
    require_once __DIR__ . "/vendor/autoload.php";
    require_once __DIR__ . "/Settings/Settings.php";

    // Initiating the Router Component.
    use BRouter\Router;
    
    // File where the Routes will be initialized.
    require_once "Routes.php";

    // Executing the program.
    echo Router::execute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
