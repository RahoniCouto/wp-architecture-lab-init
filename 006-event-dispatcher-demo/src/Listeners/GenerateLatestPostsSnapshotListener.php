<?php
declare(strict_types=1);

namespace ArchitectureLab\EventDispatcherDemo\Listeners;

use ArchitectureLab\EventDispatcherDemo\Events\SnapshotRequestedEvent;
use ArchitectureLab\EventDispatcherDemo\Services\SnapshotRegenerator;

final class GenerateLatestPostsSnapshotListener {
    public function __construct(
        public readonly SnapshotRegenerator $generator,
    ){}

    public function handle(SnapshotRequestedEvent $event): void {
        $this->regenerator->regenerateLatestPosts();
    }
}