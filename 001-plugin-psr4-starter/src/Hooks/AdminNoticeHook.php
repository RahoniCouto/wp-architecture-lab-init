<?php

declare( strict_types=1);

namespace ArquitectureLab\PluginInicial\Hooks;

use ArquitectureLab\PluginInicial\Services\MessageService;
use ArquitectureLab\PluginInicial\Services\NoticeRenderer;


final class adminNoticeHook {
    public function __construct(
        private readonly MessageService $messageService,
        private readonly NoticeRenderer $renderer
    ){

    }

    public function register(): void {
        add_action('admin_notices', [$this, 'handle']);
    }

    public function handle(): void {
        if(!$this->messageService->showOrHide()){
            return;
        }

        $this->renderer->render(
            $this->messageService->getMessage(),
            $this->messageService->getType()
        );
    }
}