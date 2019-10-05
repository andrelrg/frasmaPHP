<?php

namespace App;

use Router\Router;

//In this example HeartbeatController should be in the Controller directory.
Router::route(GET, '/heartbeat', 'HeartbeatController', 'ping');

