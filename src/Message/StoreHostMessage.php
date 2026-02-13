<?php

namespace App\Message;

use App\Ntopng\Host;
use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
readonly class StoreHostMessage {

    public function __construct(
        public Host $host
    ) { }
}
