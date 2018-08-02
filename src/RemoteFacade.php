<?php

namespace Jgile\Remote;

use Illuminate\Support\Facades\Facade;

class RemoteFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'jgile-remote';
    }
}
