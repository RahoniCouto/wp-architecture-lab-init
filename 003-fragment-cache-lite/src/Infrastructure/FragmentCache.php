<?php
/**
 * Lab de fragment cache usando Transients API.
 * 
 * Foi escolhido Transients API para manter simples, pequeno e fácil de
 * entender
 * 
 * Em larga escala deve evoluir para algo como:
 * - Redis
 * - Object cache
 * - Provedor de cache externo.
 * - outros
 */

declare(strict_types=1);

namespace ArchitectureLab\FragmentCacheLite\Infrastructure;

use ArchitectureLab\FragmentCacheLite\Contracts\FragmentCacheInterface;

final class FragmentCache implements FragmentCacheInterface {
    private const CACHE_PREFIX = 'architeture_lab_fragment_';

    public function set(string $key, string $html, int $ttl = 3600): void {
        set_transient(
            $this->buildkey($key),
            $html,
            $ttl
        );
    }

    public function get(string $key): ?string {
        $value = get_transient($this->buildkey($key));

        if(!is_string($value)){return null;}

        return $value;
    } 

    public function delete(string $key): void {
        delete_transient($this->buildkey($key));
    }

    private function buildkey(string $key): string {
        return self::CACHE_PREFIX . sanitize_key($key);
    } 
}

