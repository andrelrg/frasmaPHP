<?php

namespace Frasma\DB;


use Frasma\DB\Connectors\MySqlConnector;

/**
 * Class responsible to handle the db conections.
 *
 * @author André Gaspar <and_lrg@hotmail.com>
 */
class Connector{

    private $connectorName;
    private $connection;

    public function __construct(string $connector=null){
        $this->connectorName = $connector ?? DEFAULT_DATABASE;
    }

    public function connect(){
        switch($this->connectorName){
            case MYSQL:
                $this->connection = new MySqlConnector();
                break;
            default:
                die("Wrong default connector setting or connection not supported yet");
        }
    }

    public function close(){
        $this->connection->close();
    }
}