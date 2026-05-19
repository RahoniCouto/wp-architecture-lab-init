<?php
declare(strict_types=1);

namespace ArchitectureLab\EventDispatcherDemo\Events;

use ArchitectureLab\EventDispatcherDemo\Contracts\StoppableEventInterface;

final class SnapshotRequestedEvent implements StoppableEventInterface {
    private bool $propagationStopped = false;

    public function __construct(
        public readonly int $postId,
        public readonly string $snapshotKey = 'latest-posts'
    ){}

    public function stopPropagation(): void {
        $this->propagationStopped = true;
    }

    public function isPropagationStopped(): bool {
        return $this->propagationStopped;
    }
}