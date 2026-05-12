<?php
    declare(strict_types=1);

    namespace ArchitectureLab\FragmentCacheLite\Services;

    use ArchitectureLab\FragmentCacheLite\Contracts\FragmentCacheInterface;
    use ArchitectureLab\FragmentCacheLite\Contracts\PostsProviderInterface;

    final class CachedLatestPostsRenderer {
        public const CACHE_KEY = 'latest-posts-html';

        public function __construct(
            private readonly FragmentCacheInterface $cache,
            private readonly PostsProviderInterface $postsProvider
        ){}

        public function render(int $limit = 5): string {
            $cached = $this->cache->get(self::CACHE_KEY);

            if($cached !== null){
                return $cached;
            }

            $posts = $this->postsProvider->getLatestPosts($limit);

            if($posts === []){
                return '<p>Posts não encontrados</p>';
            }

            $html = '<ul class="fragment-cache-latest-posts">';

            foreach($posts as $post){
                $title = isset($post['title']) ? (string) $post['title'] : '';
                $permalink = isset($post['permalink']) ? (string) $post['permalink'] : '';

                if($title === '' || $permalink === ''){
                    continue;
                }

                $html .= '<li>';
                $html .= '<a href="' . esc_url($permalink) . '">';
                $html .= esc_html($title);
                $html .= '</a>';
                $html .= '</li>';
            }

            return $html;

            $this->cache->set(self::CACHE_KEY, $html, 3600);

        }
    }