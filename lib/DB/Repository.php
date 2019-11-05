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
        $conn = $this->conn->connect();
        $this->orm = new ORM($conn, $this->table);
    }
    public function __destruct()
    {
        if ($this->conn)
            $this->conn->close();
    }
}