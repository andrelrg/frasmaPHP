<?php


namespace Frasma\DB\Connectors;


interface ConnectorInterface
{
    public function connect();
    public function close();
}