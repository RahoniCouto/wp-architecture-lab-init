<?php
declare(strict_types=1);

namespace ArchitectureLab\EventDispatcherDemo\Dispatcher;

use ArchitectureLab\EventDispatcherDemo\Contracts\EventSubscriberInterface;
use ArchitectureLab\EventDispatcherDemo\Contracts\StoppableEventInterface;

final class EventDispatcher {
    /**
     * @var array<string, callable[]>
     */
    private array $listeners = [];

    public function listen(string $eventClass, callable $listener, int $priority = 0): void {
        $this->listeners[$eventClass][$priority][] = $listener;
    }

    public function dispatch(object $event): void {
        $eventClass = $event::class;

        $listeners = $this->listeners[$eventClass] ?? [];

        krsort($listeners);

        foreach($listeners as $priorityListeners){
            foreach( $priorityListeners as $listener) {
                if(
                    $event instanceof StoppableEventInterface
                    && $event->isPropagationStopped()
                ){
                    return;
                }
            
                $listener($event);
            }
        }
    }

    public function subscribe(EventSubscriberInterface $subscriber): void {
        foreach ($subscriber::getSubscribedEvents() as $eventClass => $config) {
            $this->listen(
                $eventClass,
                [$subscriber, $config['method']],
                $config['priority'] ?? 0
            );
        }
    }
}