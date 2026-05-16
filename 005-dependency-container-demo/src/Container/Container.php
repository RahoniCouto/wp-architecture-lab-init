<?php

declare( strict_types=1);

namespace ArchitectureLab\DependencyContainerDemo\Container;

use RuntimeException;

final class Container {
    /**
     * @var array<string, callable>
     */
    private array $factories = [];

    /**
     * @var array<string, mixed>
     */
    private array $instances = [];

    public function set(string $id, callable $factory): void {
        $this->factories[$id] = $factory;
    }

    public function get(string $id): mixed {
        if(isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if(!isset($this->factories[$id])) {
            throw new RuntimeException(
                sprintf('Service "%s" não está registrado.', $id)
            );
        }

        $this->instances[$id] = $this->factories[$id]($this);

        return $this->instances[$id];
    }
}