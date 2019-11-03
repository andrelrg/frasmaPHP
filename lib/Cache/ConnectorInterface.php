<?php

namespace Frasma\Cache;

interface ConnectorInterface
{
    public function getHost(): string;
    public function getPassword(): string;
    public function getDatabase(): int;
    public function getPort(): int;
    public function getUser(): string;
}