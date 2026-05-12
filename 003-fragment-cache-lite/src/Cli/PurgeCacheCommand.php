<?php
    declare(strict_types=1);

    namespace ArchitectureLab\FragmentCacheLite\Cli;

    use ArchitectureLab\FragmentCacheLite\Contracts\FragmentCacheInterface;
    use ArchitectureLab\FragmentCacheLite\Services\CachedLatestPostsRenderer;

    final class PurgeCacheCommand {
        public function __construct(
            private readonly FragmentCacheInterface $cache
        ){}

        public function __invoke(): void {
            $this->cache->delete(CachedLatestPostsRenderer::CACHE_KEY);
            \WP_CLI::success('fragment cache removido com sucesso');
        }
    }