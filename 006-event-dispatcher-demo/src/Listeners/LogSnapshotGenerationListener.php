<?php
declare(strict_types=1);

namespace ArchitectureLab\EventDispatcherDemo\Listeners;

use ArchitectureLab\EventDispatcherDemo\Events\SnapshotRequestedEvent;
use ArchitectureLab\EventDispatcherDemo\Contracts\EventSubscriberInterface;

final class LogSnapshotGenerationListener implements EventSubscriberInterface {
    public static function getSubscribedEvents(): array {
        return[
            SnapshotRequestedEvent::class => [
                'method' => 'handle',
                'priority' => 100
            ]
        ];
    }
    

    public function handle(SnapshotRequestedEvent $event): void {
        error_log(
            sprintf(
                '[EventDispatcherDemo] Snapshot chamado por post %d',
                $event->postId
            )
        );
    }
}