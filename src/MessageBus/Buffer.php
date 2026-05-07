<?php

namespace App\MessageBus;

use Countable;
use IteratorAggregate;
use Override;
use Psr\Clock\ClockInterface;
use Traversable;

/**
 * @template T
 */
class Buffer implements IteratorAggregate, Countable {

    /** @var T[] */
    private array $buffer = [ ];

    private int $lastFlush = 0;

    public function __construct(
        public int $thresholdInSeconds,
        public ClockInterface $clock,
    ) {
        $this->lastFlush = $this->clock->now()->getTimestamp();
    }

    /**
     * @return T[]
     */
    public function getBuffer(): array {
        return $this->buffer;
    }

    /**
     * @param string $key
     * @param T $value
     * @return void
     */
    public function add(string $key, mixed $value): void {
        $this->buffer[$key] = $value;
    }

    public function flush(): void {
        $this->buffer = [ ];
        $this->lastFlush = $this->clock->now()->getTimestamp();
    }

    public function mustFlush(): bool {
        return $this->clock->now()->getTimestamp() - $this->lastFlush > $this->thresholdInSeconds;
    }

    #[Override]
    public function getIterator(): Traversable {
        return yield from $this->buffer;
    }

    #[Override]
    public function count(): int {
        return count($this->buffer);
    }
}
