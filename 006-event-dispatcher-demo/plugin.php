<?php
/**
 * Plugin Name: Architecture Lab - Event Dispatcher Demo
 * Description: Evolução direta da 002, aqui adicionamos Event Dispatcher para transformar a geração de snapshots em um fluxo orientado a eventos, desacoplando hooks, listeners e regras de regeneração dentro de uma arquitetura event-driven aplicada ao WordPress.
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