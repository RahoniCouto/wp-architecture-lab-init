<?php
declare(strict_types=1);

namespace ArchitectureLab\FragmentCacheLite\Infrastructure;

use ArchitectureLab\FragmentCacheLite\Contracts\PostsProviderInterface;

final class WpPostsProvider implements PostsProviderInterface {
    public function getLatestPosts(int $limit = 5): array {
        $query = new \WP_Query([
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'no_found_rows' => true,
            'ignore_sticky_posts' => true,
        ]);

        $items = [];

        while($query_>have_posts()){
            $query->the_post();

            $items[] = [
                'title' => (string) get_the_title(),
                'permalink' => (string) get_permalink(),
            ];
        }

        wp_reset_postdata();

        return $items;
    }
}