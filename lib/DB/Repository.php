<?php


namespace Frasma\DB;


class Repository
{
    private $conn;
    protected $orm;

    /**
     * Repository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->conn = new Connector($this->connector);
        $this->conn->connect();
        $this->orm = new ORM($this->conn->connection);
    }
    public function __destruct()
    {
        if ($this->conn)
            $this->conn->close();
    }
}