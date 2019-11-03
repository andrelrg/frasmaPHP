<?php

namespace Frasma\Cache;

use Predis\Client;

class Redis
{
    private $client;

    public function __construct(ConnectorInterface $conn)
    {
        $this->client = new Client(
            array(
                'host' => $conn->getHost(),
                'port' => $conn->getPort(),
                'database' => $conn->getDatabase(),
                'user' => $conn->getUser(),
                'password' => $conn->getPassword()
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
}