<?php
declare(strict_types=1);

namespace ArchitectureLab\SnapshotGeneratorDemo\Contracts;

interface LatestPostsGeneratorInterface{
    public function generate(int $limit): array;
}