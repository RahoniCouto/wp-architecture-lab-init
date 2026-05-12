<?php
declare(strict_types=1);

namespace ArchitectureLab\FragmentCacheLite;

use ArchitectureLab\FragmentCacheLite\Frontend\CachedShortcode;
use ArchitectureLab\FragmentCacheLite\Infrastructure\FragmentCache;
use ArchitectureLab\FragmentCacheLite\Services\CachedLatestPostsRenderer;
use ArchitectureLab\FragmentCacheLite\Hooks\SavePostHook;
use ArchitectureLab\FragmentCacheLite\Cli\PurgeCacheCommand;
use ArchitectureLab\FragmentCacheLite\Infrastructure\WpPostsProvider;

final class Plugin {
    public static function init(): void {
       $cache = new FragmentCache();
       $postsProvider = new WpPostsProvider();

       $renderer = new CachedLatestPostsRenderer($cache, $postsProvider );

       (new CachedShortcode($renderer))->register();
       (new SavePostHook($cache))->register();

       if (defined('WP_CLI') && WP_CLI) {
            \WP_CLI::add_command(
                'fragment-cache purge',
                new PurgeCacheCommand($cache)
            );
        }
    }
}