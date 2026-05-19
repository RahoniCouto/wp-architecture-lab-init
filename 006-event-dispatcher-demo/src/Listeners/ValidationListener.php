<?php
declare(strict_types=1);

namespace ArchitectureLab\EventDispatcherDemo\Listeners;

use ArchitectureLab\EventDispatcherDemo\Events\SnapshotRequestedEvent;
use ArchitectureLab\EventDispatcherDemo\Contracts\EventSubscriberInterface;

final class ValidationListener implements EventSubscriberInterface {
    public static function getSubscribedEvents(): array {
        return[
            SnapshotRequestedEvent::class => [
                'method' => 'handle',
                'priority' => 200
            ]
        ];
    }
    

    public function handle(SnapshotRequestedEvent $event): void {
        if($event->postId <= 0){
            $event->stopPropagation();
        }
    }
}