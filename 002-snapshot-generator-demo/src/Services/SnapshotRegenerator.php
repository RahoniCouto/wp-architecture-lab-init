<?php
declare(strict_types=1);

namespace ArchitectureLab\SnapshotGeneratorDemo\Services;

use ArchitectureLab\SnapshotGeneratorDemo\Contracts\SnapshotRepositoryInterface;
use ArchitectureLab\SnapshotGeneratorDemo\Generator\LatestPostsGenerator;

final class SnapshotRegenerator {
    public const SNAPSHOT_KEY = 'latest-posts';

    public function __construct(
        private readonly LatestPostsGenerator $generator,
        private readonly SnapshotRepositoryInterface $repository
    ){}

    public function regenerateLatestPosts(int $limit = 5): int {
        $items = $this->generator->generate($limit);
        $this->repository->save(self::SNAPSHOT_KEY, $items);
        return count($items);
    }
}