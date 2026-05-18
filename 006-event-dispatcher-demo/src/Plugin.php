<?php
declare(strict_types=1);

namespace ArchitectureLab\EventDispatcherDemo;

use ArchitectureLab\EventDispatcherDemo\Generator\LatestPostsGenerator;
use ArchitectureLab\EventDispatcherDemo\Infrastructure\SnapshotRepository;
use ArchitectureLab\EventDispatcherDemo\Services\SnapshotRegenerator;
use ArchitectureLab\EventDispatcherDemo\Hooks\SavePostHook;
use ArchitectureLab\EventDispatcherDemo\Frontend\SnapshotShortcode;
use ArchitectureLab\EventDispatcherDemo\Cli\RegenerateCommand;

final class Plugin {
    public static function init(): void {
        $repository = new SnapshotRepository();
        $generator = new LatestPostsGenerator();

        $regenerator = new SnapshotRegenerator($generator, $repository);

        (new SavePostHook($regenerator))->register();
        (new SnapshotShortcode($repository))->register();

        if (defined('WP_CLI') && WP_CLI) {
            \WP_CLI::add_command(
                'snapshot-demo regenerate',
                new RegenerateCommand($regenerator)
            );
        }
    }
}