<?php
declare(strict_types=1);

namespace ArchitectureLab\FragmentCacheLite\Contracts;

interface FragmentCacheInterface{
    public function set(string $key, string $html, int $ttl = 3600): void;
    public function get(string $key): array;
    public function delete(string $key): void;
}