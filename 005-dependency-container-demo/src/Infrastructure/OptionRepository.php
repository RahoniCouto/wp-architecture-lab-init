<?php

declare( strict_types=1);

namespace ArchitectureLab\DependencyContainerDemo\Infrastructure;

use ArchitectureLab\DependencyContainerDemo\Contracts\OptionRepositoryInterface;

final class OptionRepository implements OptionRepositoryInterface {
    private const OPTION_KEY = 'architecture_lab_psr4_inicial_options';

    public function getAll(): array{
        $options = get_option(self::OPTION_KEY, []);

        return is_array($options) ? $options : [];
    }

    public function save(array $data): void{
        update_option(self::OPTION_KEY, $data);
    }

    public function getMessage(): string {
        $options = $this->getAll();

        return trim((string) ($options['message'] ?? '') );
    }

    public function getType(): string {
        $options = $this->getAll();

        $type = (string) ($options['type'] ?? 'success');

        return in_array($type, ['success', 'error', 'warning', 'info'], true)
            ? $type
            : 'success';
    }

    public function isDashOnly(): bool {
        $options = $this->getAll();

        return (bool) ($options['dash_only'] ?? false);
    }
}