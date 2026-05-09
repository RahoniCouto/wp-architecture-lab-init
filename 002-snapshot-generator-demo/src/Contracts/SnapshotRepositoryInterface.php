<?php
declare(strict_types=1);

namespace ArchitectureLab\SnapshotGeneratorDemo\Contracts;

interface SnapshotRepositoryInterface{
    public function save(string $key, array $data): void;
    public function get(string $key): array;
    public function delete(string $key): void;
}