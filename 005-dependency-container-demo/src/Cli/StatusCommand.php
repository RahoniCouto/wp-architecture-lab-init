<?php

declare(strict_types=1);

namespace ArchitectureLab\DependencyContainerDemo\Cli;

use ArchitectureLab\DependencyContainerDemo\Contracts\OptionRepositoryInterface;

final class StatusCommand {
    public function __construct(
        private readonly OptionRepositoryInterface $optionRepository
    ){}

    public function __invoke(): void {
        \WP_CLI::line('Architecture Lab PSR-4');
        \WP_CLI::line('Plugin ativo');
        \WP_CLI::line('Message: ' . $this->optionRepository->getMessage());
        \WP_CLI::line('Type: ' . $this->optionRepository->getType());
        \WP_CLI::line('Dash only: ' . ($this->optionRepository->isDashOnly() ? 'yes' : 'no'));
    }
}