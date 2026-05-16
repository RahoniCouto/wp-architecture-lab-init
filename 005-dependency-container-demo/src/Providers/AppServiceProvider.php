<?php
declare(strict_types=1);

namespace ArchitectureLab\DependencyContainerDemo\Providers;

use ArchitectureLab\DependencyContainerDemo\Container\Container;
use ArchitectureLab\DependencyContainerDemo\Contracts\OptionRepositoryInterface;
use ArchitectureLab\DependencyContainerDemo\Infrastructure\OptionRepository;

final class AppServiceProvider {
    public function register(Container $container): void {
        $container->set(
            OptionRepositoryInterface::class,
            fn (): OptionRepositoryInterface => new OptionRepository()
        );

        $container->alias('repository', OptionRepositoryInterface::class);
    }
}