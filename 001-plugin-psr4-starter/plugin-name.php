<?php
/**
 * Plugin Name: Architecture Lab - PSR-4 Inicial
 * Description: Plugin Wordpress reduzido, sando PSR-4 e autoload
 * Version: 0.1.0
 * Author: Rahoni Couto
 */

declare(strict_types=1);

if ( !defined('ABSPATH') ){
    exit;
}

$autoload = __DIR__ . '/vendor/autoload.php';

if ( !file_exists($autoload) ){
    add_action('admin_notices', static function(): void {
        echo '<div class="notice notice-error"><p>';
        echo esc_html__('Architecture Lab PSR-4 Inicial: Composer autoload não encontrado. Run composer dump-autoload.', 'architecture-lab');
        echo '</p></div>';
    });

    return;
}

require_once $autoload;

ArquitectureLab\PluginInicial\Plugin::init();