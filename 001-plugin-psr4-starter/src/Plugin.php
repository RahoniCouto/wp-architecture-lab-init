<?php

declare( strict_types=1);

namespace ArquitectureLab\PluginInicial;

use ArquitectureLab\PluginInicial\Hooks\AdminNoticeHook;
use ArquitectureLab\PluginInicial\Infrastructure\OptionRepository;
use ArquitectureLab\PluginInicial\Services\MessageService;

final class Plugin{
    public static function init(): void {
        $optionRepository = new OptionRepository();
        $messageService = new MessageService( $optionRepository );

        (new AdminNoticeHook($messageService))->register();
    }
}