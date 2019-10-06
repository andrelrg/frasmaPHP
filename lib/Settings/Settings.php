<?php

//Things that can be changed.
define("VERBOSE", TRUE);
define("ROUTES_FILE", "/Src/Routes.php");
define("CONTROLLER", "App\Controllers");

//Messages.
define("BADREQUEST", "Verify your request");
define("NOTALLOWED", "The credentials given in request is not allowed to perform this action");

//Things that should not be changed.
define("GET", "GET");
define("POST", "POST");
define("DELETE", "DELETE");
define("PUT", "PUT");
define("HEAD", "HEAD");