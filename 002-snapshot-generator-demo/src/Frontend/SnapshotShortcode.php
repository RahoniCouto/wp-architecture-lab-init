<?php
declare(strict_types=1);

namespace ArchitectureLab\SnapshotGeneratorDemo\Frontend;

use ArchitectureLab\SnapshotGeneratorDemo\Contracts\SnapshotRepositoryInterface;
use ArchitectureLab\SnapshotGeneratorDemo\Services\SnapshotRegenerator;

final class SnapshotShortcode {
    public function __construct(
        private readonly SnapshotRepositoryInterface $repository
    ){}

    public function register(): void {
        add_shortcode('snapshot_latest_posts', [$this, 'render']);
    }

    public function render(): string {
        $items = $this->repository->get(SnapshotRegenerator::SNAPSHOT_KEY);

        if($items === []){
            return '<p>Nenhum snapshot disponivel.</p>';
        }

        $html = '<ul class="snapshot-latest-posts">';

        foreach($items as $item){
            $title = isset($item['title']) ? (string) $item['title'] : '';
            $permalink = isset($item['permalink']) ? (string) $item['permalink'] : '';
        
            if($title === '' || $permalink === '') {
                continue;
            }

            $html .= '<li>';
            $html .= '<a href="' . esc_url($permalink) . '">';
            $html .= esc_html($title);
            $html .= '</a>';
            $html .= '</li>';

        }

        $html .= '</ul>';

        return $html;
    }
}