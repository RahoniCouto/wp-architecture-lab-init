<?php
declare(strict_types=1);

namespace ArchitectureLab\SnapshotGeneratorDemo\Generator;

final class LatestPostsGenerator {
    public function generate(int $limit = 5): array {
        $query = new \WP_Query([
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'no_found_rows' => true,
            'ignore_sticky_posts' => true,
        ]);

        $items = [];

        foreach($query->posts as $post){
            $items[] = [
                'id' => (int) $post->ID,
                'title' => get_the_title($post),
                'permalink' => get_permalink($post),
                'data' => get_the_date('d/m/Y', $post),
            ];
        }

        wp_reset_postdata();

        return $items;
    }
}