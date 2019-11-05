# Frasma PHP

## PHP Basic Components
This is a basic PHP component package, extremelly simple to use and recommended for small projects where you don't need to import an extremely heavy framework.

### Instaling
composer require andrelrg/frasma-php

### How to use

#### Namespace
The framework will search for the `App` namespace.

#### Configuration
If you will make use of Cache or Database functions of the framework, you will need to create a configuration file in json where you will put all the configuration needed.
<br><br>~Configuration file example:
```json
{
    "database":{
        "mysql": {
            "host":"localhost",
            "port":"3306",
            "user":"root",
            "password":"",
            "database":"database"
        }
    },
    "redis":{
        "password": "",
        "database": 0,
        "host": "redis",
        "port": 6379
    }
}
```
And then, in the begging of your code you'll need to put this code: 
```php
<?php
use Frasma\Settings\Settings;

Settings::getSettings(dirname(__FILE__).'/Settings/config.json');
```
<br>
Also, if you want, you can define the following constants to change it's default values:<br>

`VERBOSE` : boolean default: true <br>
`ROUTES_FILE` : string default: $_SERVER['DOCUMENT_ROOT']."/Src/Routes.php" <br>
`CONTROLLER` : string default: "App\Controllers" <br>
`DEFAULT_DATABASE` : string default: "mysql" <br>
`BADREQUEST` : string default: "Verify your request" <br>
`NOTALLOWED` : string default: "The credentials given in request is not allowed to perform this action" <br>
`NOTFOUND` : string default: "The page you've requested doesn't exist" <br>
#### Router
Making sure that the variable `CONTROLLER` has the right value, define the routes like this in that file:
```php
<?php

namespace App;

use Frasma\Router;

//HeartbeatController
Router::route(GET, '/heartbeat', "HeartbeatController", "ping");
```
And then start the API like:
```php
<?php

use Frasma\Api;
Api::go();
```
<br>

#### Controllers
The controllers should extends the `Frasma\Controller` class, it will give you a lot of useful functions (that will be documented soon).


#### Repository
The repository will give you the connection of the database completely transparent, your repository will need only to extends repository, indicate the table, and the framework will do the connection for you in the default database, if the database you want is different than the dafault, just define a variable `protected $connection` with the name of the connection in configuration file.
the following example makes the use of ORM facilitator, that is a simple query builder.
```php
<?php

namespace App\Repositories;

use Frasma\DB\ORM;
use Frasma\DB\Repository;

class UserRepository extends Repository
{
    protected $table = "users";

    public function get(string $email): array{
        $this->orm
            ->fields("*")
            ->where(ORM::equals('email', "'$email'"))
            ->getTo($result);
        return $result;
    }
}
```

#### Cache
Example:
```php
<?php 

try{
    $r = new Redis();
    $result = $r->get("cache" . $email);
}catch (\Exception $e){
    return $this->fail();
}
```

