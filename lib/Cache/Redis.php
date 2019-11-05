<?php

namespace Frasma\Cache;

use Predis\Client;

class Redis
{
    private $client;

    /**
     * Redis constructor.
     * @param array $connection This array MUST contain these keys:
     * host, port, database, user, password
     * @throws \Exception
     */
    public function __construct(array $connection=null)
    {
        global $SETTINGS;
        //@TODO Add support to multiple connections inside global settings.
        if (!$connection){
            if (!$SETTINGS || !$SETTINGS["redis"]){
                throw new \Exception("Missing configuration definition");
            }
            $connection = $SETTINGS["redis"];
        }

        if ($missing = $this->invalidConnection($connection)){
            throw new \Exception("Invalid Connection array, misssing". $missing);
        }
        $this->client = new Client(
            array(
                'host' => $connection["host"],
                'port' => $connection["port"],
                'database' => $connection["database"],
                'user' => $connection["user"],
                'password' => $connection["password"]
            )
        );
    }

    public function set(string $key, string $value, int $secondsTTL=null): void{
        if (!$secondsTTL){
            $this->client->set($key, $value);
            return;
        }else{
            $secondsTTL = $secondsTTL*60;
            $this->client->set($key, $value, "ex", $secondsTTL);
            return;
        }
    }

    public function get(string $key): ?string{
        return $this->client->get($key);
    }

    public function deleteMany(string $key): void{
        foreach ($this->client->keys("$key") as $key){
            $this->client->del($key);
        }
    }

    public function close(){
        unset($this->client);
    }

    public function getOrSaveArray(string $key, Callable $callback, int $ttl): array {
        $result = $this->get($key);
        if (empty($result)){
            $result = json_encode($callback());
            $this->set($key, $result, $ttl);
        }
        return json_decode($result, true);
    }

    private function invalidConnection(array $conn): ?string {
        if (array_key_exists("host", $conn)) {
            return "host";
        }
        if (array_key_exists("port", $conn)) {
            return "port";
        }
        if (array_key_exists("database", $conn)) {
            return "database";
        }
        if (array_key_exists("user", $conn)) {
            return "user";
        }
        if (array_key_exists("password", $conn)) {
            return "password";
        }
        return null;
    }
}