<?php

declare(strict_types=1);

namespace ArchitectureLab\DependencyContainerDemo;

use ArchitectureLab\DependencyContainerDemo\Admin\SettingsPage;
use ArchitectureLab\DependencyContainerDemo\Contracts\OptionRepositoryInterface;
use ArchitectureLab\DependencyContainerDemo\Hooks\AdminNoticeHook;
use ArchitectureLab\DependencyContainerDemo\Infrastructure\OptionRepository;
use ArchitectureLab\DependencyContainerDemo\Services\MessageService;
use ArchitectureLab\DependencyContainerDemo\Services\NoticeRenderer;
use ArchitectureLab\DependencyContainerDemo\Cli\StatusCommand;
use ArchitectureLab\DependencyContainerDemo\Container\Container;

final class Plugin{
    public static function init(): void {
        $container = new Container();

        $container->set(
            OptionRepositoryInterface::class,
            fn () => new OptionRepository()
        );

        $container->resolve(AdminNoticeHook::class)->register();
        $container->resolve(SettingsPage::class)->register();

        if(defined('WP_CLI') && WP_CLI){
            \WP_CLI::add_command('architecture-lab status', $container->resolve(StatusCommand::class));
        }
    }
}