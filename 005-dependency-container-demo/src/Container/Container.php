<?php

declare( strict_types=1);

namespace ArchitectureLab\DependencyContainerDemo\Container;

use ReflectionClass;
use ReflectionNamedType;
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

    /**
     * @var array<string, string>
     */
    private array $aliases = [];

    public function set(string $id, callable $factory): void {
        $this->factories[$id] = $factory;
    }

    public function get(string $id): mixed {
        if(isset($this->aliases[$id])){
            $id = $this->aliases[$id];
        }

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

    public function resolve(string $id): object {
        if(isset($this->aliases[$id])){
            $id = $this->aliases[$id];
        }
        
        if(isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if(isset($this->factories[$id])) {
            return $this->get($id);
        }

        if(!class_exists($id)){
            throw new RuntimeException(
                sprintf('Service "%s" não está registrado.', $id)
            );
        }

        $reflection = new ReflectionClass($id);

        $constructor = $reflection->getConstructor();

        if($constructor === null){
            $instance = new $id();
            $this->instances[$id] = $instance;

            return $instance;
        }

        $dependencies = [];

        foreach($constructor->getParameters() as $parameter){
            $type = $parameter->getType();

            if(!$type instanceof ReflectionNamedType){
                throw new RuntimeException(
                    sprintf(
                        'Não foi possível resolver o parâmetro "$%s" em "%s".', 
                        $parameter->getName(),
                        $id
                    )
                );
            }

            if($type->isBuiltin()){
                throw new RuntimeException(
                    sprintf(
                        'O parâmetro embutido "$%s" não pode ser automaticamente conectado em "%s".', 
                        $parameter->getName(),
                        $id
                    )
                );
            }

            $dependencies[] = $this->resolve($type->getName());
        }

        $instance = $reflection->newInstanceArgs($dependencies);

        $this->instances[$id] = $instance;

        return $instance;
    }

    public function alias(string $alias, string $target): void {
        $this->aliases[$alias] = $target;
    }
}