<?php
/**
 * Plugin Name: Architecture Lab - Snapshot Generator Demo Basic
 * Description: Plugin simples de geração de Snapshot basicoo, usa apenas PSR-4, DI e dados prê computados, não recomendado para alto trafego.
 * Version: 0.1.0
 * Author: Rahoni Couto
 */

declare(strict_types=1);

if( !defined('ABSPATH')) {
    exit;
}

$autoload = __DIR__ . '/vendor/autoload.php';

if(!file_exists($autoload)){
    add_action('admin_notices', static function (): void {
        ?>
            <div class="notice notice-error">
                <p><?php echo esc_html__(
                            'Composer autoload não foi encontrado. Run composer install.','architecture-lab'); 
                    ?>
                </p>
            </div>
        <?php
    });

    return;
}

require_once $autoload;

ArchitectureLab\EventDispatcherDemo\Plugin::init();