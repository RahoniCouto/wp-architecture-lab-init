<?php

declare(strict_types=1);

namespace ArquitectureLab\PluginInicial;

use ArquitectureLab\PluginInicial\Admin\SettingsPage;
use ArquitectureLab\PluginInicial\Hooks\AdminNoticeHook;
use ArquitectureLab\PluginInicial\Infrastructure\OptionRepository;
use ArquitectureLab\PluginInicial\Services\MessageService;
use ArquitectureLab\PluginInicial\Services\NoticeRenderer;

final class Plugin{
    public static function init(): void {
        $repository = new OptionRepository();
        $messageService = new MessageService( $repository );
        $renderer = new NoticeRenderer();

        (new AdminNoticeHook($messageService, $renderer))->register();
        (new SettingsPage($repository))->register();
    }
}