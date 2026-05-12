<?php
    declare(strict_types=1);

    namespace ArchitectureLab\FragmentCacheLite\Services;

    use ArchitectureLab\FragmentCacheLite\Contracts\FragmentCacheInterface;

    final class CachedLatestPostsRenderer {
        public const CACHE_KEY = 'latest-post-html';

        public function __construct(
            private readonly FragmentCacheInterface $cache
        ){}

        public function render(int $limit = 5): string {
            $cached = $this->cache->get(self::CACHE_KEY);

            if($cached !== null){
                return $cached;
            }

            $html = $this->renderLatestPosts($limit);

            $this->cache->set(self::CACHE_KEY, $html, 3600);

            return $html;
        }

        private function renderLatestPosts(int $limit): string {
            $query = new \WP_Query([
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => $limit,
                'no_found_rows' => true,
                'ignore_sticky_posts' => true,
            ]);

            if(!$query->have_posts()){
                return '<p>Posts não encontrados.</p>';
            }

            $html = '<ul class="fragment-cache-latest-posts">';

            while($query->have_posts()){
                $query->the_post();
                
                $html .= '<li>';
                $html .= '<a href="' . esc_url(get_permalink()) . '">';
                $html .= esc_html(get_the_title());
                $html .= '</a>';
                $html .= '</li>';
            }

            wp_reset_postdata();

            $html .= '</ul>';

            return $html;
        }
    }