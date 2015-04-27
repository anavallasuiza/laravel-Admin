<?php

namespace Admin\Library;

class Memcache
{
    private $server;

    public function __construct($server)
    {
        $this->server = $server;
        $this->server->addServer('localhost', 11211);
    }

    public function __call($name, $params)
    {
        return $this->server->$name();
    }
}
