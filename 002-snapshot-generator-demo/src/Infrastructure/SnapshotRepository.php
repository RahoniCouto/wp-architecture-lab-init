<?php
/**
 * Este armazenamento foi mantido propositalmente simples.
 * O contrato do repository permite evoluir a camada de armazenamento no futuro
 * sem necessidade de alterar generators ou consumidores de frontend.
 *
 * Possíveis evoluções de armazenamento:
 * - Transients API
 * - Tabela customizada no banco
 * - Redis / object cache
 * - Arquivos JSON
 * 
*/

declare(strict_types=1);

namespace ArchitectureLab\SnapshotGeneratorDemo\Infrastructure;

use ArchitectureLab\SnapshotGeneratorDemo\Contracts\SnapshotRepositoryInterface;

final class SnapshotRepository implements SnapshotRepositoryInterface {
    private const OPTION_PREFIX = "architecture_lab_snapshot_";

    public function save(string $key, array $data): void {
        update_option($this->buildOptionName($key), $data, false);
    }

    public function get(string $key): array {
        $value = get_option($this->buildOptionName($key), []);

        return is_array($value) ? $value : [];
    }

    public function delete(string $key): void {
        delete_option($this->buildOptionName($key));
    }

    public function buildOptionName(string $key): string{
        return self::OPTION_PREFIX . sanitize_key($key);
    }
}