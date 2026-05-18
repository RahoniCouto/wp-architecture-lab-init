<?php
declare(strict_types=1);

namespace ArchitectureLab\EventDispatcherDemo\Contracts;

interface LatestPostsGeneratorInterface{
    public function generate(int $limit): array;
}