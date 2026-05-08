<?php
declare(strict_types=1);

namespace ArchitectureLab\SnapshotGeneratorDemo\Contracts;

interface SnapshotRepositoryInterface{
    public function save(sting $key, array $data): void;
    public function gat(string $key): array;
    public function delete(sting $key): void;
}