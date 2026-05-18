<?php
declare(strict_types=1);

namespace ArchitectureLab\EventDispatcherDemo\Listeners;

use ArchitectureLab\EventDispatcherDemo\Events\SnapshotRequestedEvent;

final class LogSnapshotGenerationListener {

    public function handle(SnapshotRequestedEvent $event): void {
        error_log(
            sprintf(
                '[EventDispatcherDemo] Snapshot chamado por post %d',
                $event->postId
            )
        );
    }
}