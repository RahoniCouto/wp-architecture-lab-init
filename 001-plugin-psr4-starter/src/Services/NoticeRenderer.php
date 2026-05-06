<?php

declare(strict_types=1);

namespace ArquitectureLab\PluginInicial\Services;

final class NoticeRenderer {
    public function render(string $message, string $type): void {
        $allowed = ['success', 'error', 'warning', 'info'];

        if( !in_array($type, $allowed, true)) {
            $type = 'success';
        }

        ?>  
            <div class="notice notice-<?php echo esc_attr($type) ?> is-dismissible">
                <p><?php echo esc_html($message)?></p>
            </div>
        <?php
    }
}