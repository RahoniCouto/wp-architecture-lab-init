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

        $container->set(
            MessageService::class,
            fn (Container $container) => new MessageService(
                $container->get(OptionRepositoryInterface::class)
            )
        );

        $container->set(
            NoticeRenderer::class,
            fn () => new NoticeRenderer()
        );

        $container->set(
            AdminNoticeHook::class,
            fn (Container $container) => new AdminNoticeHook(
                $container->get(NoticeRenderer::class),
                $container->get(NoticeRenderer::class)
            )
        );

        $container->set(
            SettingsPage::class,
            fn (Container $container) => new SettingsPage(
                $container->get(OptionRepositoryInterface::class)
            )
        );

        $container->set(
            StatusCommand::class,
            fn (Container $container) => new StatusCommand(
                $container->get(OptionRepositoryInterface::class)
            )
        );

        $container->get(AdminNoticeHook::class)->register();
        $container->get(SettingsPage::class)->register();

        if(defined('WP_CLI') && WP_CLI){
            \WP_CLI::add_command('architecture-lab status', $container->get(StatusCommand::class));
        }
    }
}