<?php
    declare(strict_types=1);

    namespace ArchitectureLab\FragmentCacheLite\Frontend;

    use ArchitectureLab\FragmentCacheLite\Services\CachedLatestPostsRenderer;

    final class CachedShortcode {
        public function __construct(
            private readonly CachedLatestPostsRenderer $renderer
        ){}

        public function register(): void {
            add_shortcode('fragment_cached_latest_posts', [$this, 'render']);
        }

        public function render(): string {
            return $this->renderer->render();
        }
    }