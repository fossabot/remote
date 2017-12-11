<?php

namespace Jgile\Remote;

use Collective\Remote\RemoteManager as RM;

class RemoteManager extends RM
{
    protected function makeConnection($name, array $config)
    {
        $timeout = isset($config['timeout']) ? $config['timeout'] : 10;

        $this->setOutput($connection = new Connection(
            $name,
            $config['host'],
            $config['username'],
            $this->getAuth($config),
            null,
            $timeout
        ));

        return $connection;
    }
}
