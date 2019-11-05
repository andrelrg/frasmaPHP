<?php


namespace Frasma\DB;


class Repository
{
    private $conn;
    protected $orm;

    public function __construct()
    {
        $this->conn = new Connector($$this->connector);
        $this->orm = new ORM($this->conn);
    }
    public function __destruct()
    {
        $this->conn->close();
    }
}