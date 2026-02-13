<?php

namespace App\Message;

use App\Ntopng\ActiveFlowEntry;
use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
readonly class StoreFlowDetailsMessage {
    public function __construct(
        public ActiveFlowEntry $flowEntry
    ) { }
}
