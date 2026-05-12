<?php
declare(strict_types=1);

use ArchitectureLab\FragmentCacheLite\Contracts\FragmentCacheInterface;
use ArchitectureLab\FragmentCacheLite\Contracts\PostsProviderInterface;
use ArchitectureLab\FragmentCacheLite\Services\CachedLatestPostsRenderer;
use PHPUnit\Framework\TestCase;

final class CachedLatestPostsRendererTest extends TestCase{
    public function test_returns_cached_html(): void {
        $cache = new class implements FragmentCacheInterface {
            public function set(string $key, string $html, int $ttl = 3600): void {}
        
            public function get(string $key): string {
                return '<p>HTML cacheado</p>';
            }

            public function delete(string $key): void {}
        };

        $provider = new class implements PostsProviderInterface {
            public function getLatestPosts(int $limite = 5): array {
                throw new RuntimeException('Provedor não deve ser chamado quando solicitado o cache');
            }
        };

        $renderer = new CachedLatestPostsRenderer($cache, $provider);

        $this->assertSame(
            '<p>HTML cacheado</p>',
            $renderer->render()
        );
    }
}

