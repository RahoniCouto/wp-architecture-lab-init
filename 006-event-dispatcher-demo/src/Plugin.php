<?php
declare(strict_types=1);

namespace ArchitectureLab\EventDispatcherDemo;

use ArchitectureLab\EventDispatcherDemo\Generator\LatestPostsGenerator;
use ArchitectureLab\EventDispatcherDemo\Infrastructure\SnapshotRepository;
use ArchitectureLab\EventDispatcherDemo\Services\SnapshotRegenerator;
use ArchitectureLab\EventDispatcherDemo\Hooks\SavePostHook;
use ArchitectureLab\EventDispatcherDemo\Frontend\SnapshotShortcode;
use ArchitectureLab\EventDispatcherDemo\Cli\RegenerateCommand;
use ArchitectureLab\EventDispatcherDemo\Dispatcher\EventDispatcher;
use ArchitectureLab\EventDispatcherDemo\Events\SnapshotRequestedEvent;
use ArchitectureLab\EventDispatcherDemo\Listeners\GenerateLatestPostsSnapshotListener;
use ArchitectureLab\EventDispatcherDemo\Listeners\LogSnapshotGenerationListener;

final class Plugin {
    public static function init(): void {
        $repository = new SnapshotRepository();
        $generator = new LatestPostsGenerator();

        $regenerator = new SnapshotRegenerator($generator, $repository);

        $dispatcher = new EventDispatcher();

        $listener = new GenerateLatestPostsSnapshotListener($regenerator);

        $dispatcher->listen(
            SnapshotRequestedEvent::class,
            [$listener, 'handle']
        );

        $dispatcher->subscribe(
            new LogSnapshotGenerationListener()
        );

        (new SavePostHook($dispatcher))->register();
        (new SnapshotShortcode($repository))->register();

        if (defined('WP_CLI') && WP_CLI) {
            \WP_CLI::add_command(
                'snapshot-demo regenerate',
                new RegenerateCommand($regenerator)
            );
        }
    }
}