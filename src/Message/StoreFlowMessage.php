<?php

namespace App\Message;

use App\Ntopng\Flow;
use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
readonly class StoreFlowMessage {
    public function __construct(
        public Flow $flow
    ) { }
}
