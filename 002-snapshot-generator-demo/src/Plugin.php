<?php
declare(strict_types=1);

namespace ArchitectureLab\SnapshotGeneratorDemo;

use ArchitectureLab\SnapshotGeneratorDemo\Generator\LatestPostsGenerator;
use ArchitectureLab\SnapshotGeneratorDemo\Infrastructure\SnapshotRepository;
use ArchitectureLab\SnapshotGeneratorDemo\Services\SnapshotRegenerator;

final class Plugin {
    public static function init(): void {
        $repository = new SnapshotRepository();
        $generator = new LatestPostsGenerator();

        $regenerator = new SnapshotRegenerator($generator, $repository);
    }
}