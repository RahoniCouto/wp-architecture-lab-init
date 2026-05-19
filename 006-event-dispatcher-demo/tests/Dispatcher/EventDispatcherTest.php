<?php
declare(strict_types=1);

use ArchitectureLab\EventDispatcherDemo\Dispatcher\EventDispatcher;
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
            0
        );

        $dispatcher->dispatch(new DummyEvent());

        $this->assetSame(
            ['high', 'low'],
            $executionOrder
        );
    }

    public function test_register_subscriber(): void {
        $dispatcher = new EventDispatcher();
        $executed = false;

        $subscriber = new class($executed) implements EventSubscriberInterface {
            public function __construct(
                private bool &$executed 
            ){}

            public static function getSubscribedEvents(): array{
                return [
                    DummyEvent::class => [
                        'method' => 'handle',
                    ]
                ];
            }

            public function handle(): void {
                $this->executed = true;
            }
        };

        $dispatcher->subscribe($subscriber);
        $dispatcher->dispatch(new DummyEvent());
        $this->assetTrue($executed);
    }
}

final class DummyEvent{}