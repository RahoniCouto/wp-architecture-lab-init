<?php
declare(strict_types=1);

use ArchitectureLab\EventDispatcherDemo\Dispatcher\EventDispatcher;
use ArchitectureLab\EventDispatcherDemo\Contracts\EventSubscriberInterface;
use ArchitectureLab\EventDispatcherDemo\Events\SnapshotRequestedEvent;
use PHPUnit\Framework\TestCase;

final class EventDispatcherTest extends TestCase {
    public function test_executes_priority_order(): void {
        $dispatcher = new EventDispatcher();

        $executionOrder = [];

        $dispatcher->listen(
            DummyEvent::class,
            function () use (&$executionOrder): void {
                $executionOrder[] = 'low';
            },
            0
        );

        $dispatcher->listen(
            DummyEvent::class,
            function () use (&$executionOrder): void {
                $executionOrder[] = 'high';
            },
            100
        );

        $dispatcher->dispatch(new DummyEvent());

        $this->assertSame(
            ['high', 'low'],
            $executionOrder
        );
    }

    public function test_register_subscriber(): void {
        $dispatcher = new EventDispatcher();
        $executed = false;

        $subscriber = new class($executed) implements EventSubscriberInterface {
            private bool $executedRef;    

            public function __construct(bool &$executed){
                $this->executedRef = &$executed;
            }

            public static function getSubscribedEvents(): array{
                return [
                    DummyEvent::class => [
                        'method' => 'handle',
                    ]
                ];
            }

            public function handle(): void {
                $this->executedRef = true;
            }
        };

        $dispatcher->subscribe($subscriber);
        $dispatcher->dispatch(new DummyEvent());
        $this->assertTrue($executed);
    }

    public function test_stop_propagation(): void {
        $dispatcher = new EventDispatcher();
        $executionOrder = [];

        $dispatcher->listen(
            SnapshotRequestedEvent::class,
            function (SnapshotRequestedEvent $event) use (&$executionOrder): void {
                $executionOrder[] = 'first';

                $event->stopPropagation();
            },
            100
        );

        $dispatcher->listen(
            SnapshotRequestedEvent::class,
            function () use (&$executionOrder): void {
                $executionOrder[] = 'second';
            },
            0
        );

        $dispatcher->dispatch(
            new SnapshotRequestedEvent(1)
        );

        $this->assertSame(
            ['first'],
            $executionOrder
        );
    }
}

final class DummyEvent{}