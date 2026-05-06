<?php

declare( strict_types=1);

namespace ArquitectureLab\PluginInicial\Hooks;

use ArquitectureLab\PluginInicial\Services\MessageService;


final class adminNoticeHook {
    public function __construct(
        private readonly MessageService $messageService
    ){

    }

    public function register(): void {
        add_action('admin_notices', [$this, 'render']);
    }

    public function render(): void {
        echo '<div class="notice notice-success"><p>';
        echo esc_html($this->messageService->getAdminMessage());
        echo '</p></div>';
    }
}