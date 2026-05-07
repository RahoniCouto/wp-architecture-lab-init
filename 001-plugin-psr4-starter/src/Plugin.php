<?php

declare(strict_types=1);

namespace ArchitectureLab\PluginInicial;

use ArchitectureLab\PluginInicial\Admin\SettingsPage;
use ArchitectureLab\PluginInicial\Hooks\AdminNoticeHook;
use ArchitectureLab\PluginInicial\Infrastructure\OptionRepository;
use ArchitectureLab\PluginInicial\Services\MessageService;
use ArchitectureLab\PluginInicial\Services\NoticeRenderer;
use ArchitectureLab\PluginInicial\Cli\StatusCommand;

final class Plugin{
    public static function init(): void {
        $repository = new OptionRepository();
        $messageService = new MessageService( $repository );
        $renderer = new NoticeRenderer();

        (new AdminNoticeHook($messageService, $renderer))->register();
        (new SettingsPage($repository))->register();

        if(defined('WP_CLI') && WP_CLI){
            \WP_CLI::add_command('architecture-lab status', new StatusCommand($repository));
        }
    }
}