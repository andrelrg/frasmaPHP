<?php

//Things that can be changed.
define("VERBOSE", TRUE);
define("ROUTES_FILE", $_SERVER['DOCUMENT_ROOT']."/Src/Routes.php");
define("CONTROLLER", "App\Controllers");
define("DEFAULT_DATABASE", "mysql");

//Messages.
define("BADREQUEST", "Verify your request");
define("NOTALLOWED", "The credentials given in request is not allowed to perform this action");
define("NOTFOUND", "The page you've requested doesn't exist");

//Things that should not be changed.
define("GET", "GET");
define("POST", "POST");
define("DELETE", "DELETE");
define("PUT", "PUT");
define("HEAD", "HEAD");
define("MYSQL", "mysql");