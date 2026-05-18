<?php
declare(strict_types=1);

namespace ArchitectureLab\EventDispatcherDemo\Dispatcher;

final class EventDispatcher {
    /**
     * @var array<string, callable[]>
     */
    private array $listeners = [];

    public function listen(string $eventClass, calable $listener): void {
        $this->listeners[$eventClass][] = $listener;
    }

    public function dispatch(object $event): void {
        $eventClass = $event::class;

        foreach($this->listeners[$eventClass] ?? [] as $listeners){
            $listener($event);
        }
    }
}