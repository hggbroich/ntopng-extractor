<?php

namespace App\MessageHandler;

use Exception;

class FlowNotFoundException extends Exception {
    public function __construct(
        public string $key,
        public string $hashId,
        string $message = "", int $code = 0, ?Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
