<?php
declare(strict_types=1);

namespace ArchitectureLab\FragmentCacheLite\Contracts;

interface PostsProviderInterface {
    public function getLatestPosts(int $limit = 5): array;
}