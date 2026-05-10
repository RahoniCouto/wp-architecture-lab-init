<?php
declare(strict_types=1);

namespace ArchitectureLab\SnapshotGeneratorDemo;

use ArchitectureLab\SnapshotGeneratorDemo\Generator\LatestPostsGenerator;
use ArchitectureLab\SnapshotGeneratorDemo\Infrastructure\SnapshotRepository;
use ArchitectureLab\SnapshotGeneratorDemo\Services\SnapshotRegenerator;
use ArchitectureLab\SnapshotGeneratorDemo\Hooks\SavePostHook;
use ArchitectureLab\SnapshotGeneratorDemo\Frontend\SnapshotShortcode;

final class Plugin {
    public static function init(): void {
        $repository = new SnapshotRepository();
        $generator = new LatestPostsGenerator();

        $regenerator = new SnapshotRegenerator($generator, $repository);

        (new SavePostHook($regenerator))->register();
        (new SnapshotShortcode($repository))->register();
    }
}