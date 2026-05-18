<?php
declare(strict_types=1);

namespace ArchitectureLab\EventDispatcherDemo\Events;

final class SnapshotRequestedEvent {
    public function __construct(
        public readonly int $postId,
        public readonly string $snapshotKey = 'latest-posts'
    ){}
}