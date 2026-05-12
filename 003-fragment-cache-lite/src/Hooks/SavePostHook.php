<?php
declare(strict_types=1);

namespace ArchitectureLab\FragmentCacheLite\Hooks;

use ArchitectureLab\FragmentCacheLite\Contracts\FragmentCacheInterface;
use ArchitectureLab\FragmentCacheLite\Services\CachedLatestPostsRenderer;

final class SavePostHook {
    public function __construct(
        private readonly FragmentCacheInterface $cache
    ){}

    public function register(): void {
        add_action('save_post_post', [$this, 'handle'], 10, 3);
    }

    public function handle(int $postId, \WP_Post $post, bool $update): void {
        if(wp_is_post_autosave($postId) || wp_is_post_revision($postId)){
            return;
        }

        if($post->post_status !== 'publish'){
            return;
        }

        $this->cache->delete(CachedLatestPostsRenderer::CACHE_KEY);
    }
}