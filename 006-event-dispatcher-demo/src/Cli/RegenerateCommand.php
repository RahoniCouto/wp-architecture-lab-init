<?php 
declare(strict_types=1);

namespace ArchitectureLab\EventDispatcherDemo\Cli;

use ArchitectureLab\EventDispatcherDemo\Services\SnapshotRegenerator;

final class RegenerateCommand {
    public function __construct(
        private readonly SnapshotRegenerator $regenerator
    ){}

    public function __invoke(array $args, array $assocArgs): void {
        $limit = 5;

        if( isset($assocArgs['limit'])) {
            $limit = max(1, (int) $assocArgs['limit']);
        }

        \WP_CLI::log('Gerando o snapshot dos últimos posts...');

        $count = $this->regenerator->regenerateLatestPosts($limit);

        \WP_CLI::success(
            sprintf(
                'Snapshot gerado com sucesso, total de %d posts.',
                $count
            )
        );
    }
}