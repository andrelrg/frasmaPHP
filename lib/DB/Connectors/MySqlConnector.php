<?php

namespace Frasma\DB\Connectors;

use Exception;
use PDO;
use PDOException;

/**
 * Classe resposável por Conectar ao banco MySql.
 *
 * @author André Gapar <and_lrg@hotmail.com>
 */
class MySqlConnector implements ConnectorInterface {

    const conString = "mysql:host=%s;port=%s;dbname=%s";

    /**
     * @return PDO
     * @throws Exception
     */
    public function connect(){
        global $SETTINGS;
        $connection = $SETTINGS["database"]["mysql"];

        $conString = sprintf(
            self::conString,
            $connection['host'],
            $connection['port'],
            $connection['database']
        );

        try{
            $conn = new PDO(
                $conString,
                $connection['user'],
                $connection['password']
            );
        } catch (PDOException $e) {
            throw new Exception("Invalid mysql connection: " . $e->getMessage());
        }
        return $conn;
    }

    public function close(){
        $conn = null;
    }

}