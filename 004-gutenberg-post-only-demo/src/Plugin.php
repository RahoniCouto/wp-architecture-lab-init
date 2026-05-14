<?php
declare(strict_types=1);

namespace ArchitectureLab\GutenbergPostOnlyDemo;

use ArchitectureLab\GutenbergPostOnlyDemo\Hooks\GutenbergHook;
use ArchitectureLab\GutenbergPostOnlyDemo\Policies\PostOnlyEditorPolicy;
use ArchitectureLab\GutenbergPostOnlyDemo\Policies\ConfiguredPostTypesPolicy;
use ArchitectureLab\GutenbergPostOnlyDemo\Policies\FeatureFlagPolicy;
use ArchitectureLab\GutenbergPostOnlyDemo\Policies\EnvironmentPolicy;
use ArchitectureLab\GutenbergPostOnlyDemo\Services\EditorPolicyService;
use ArchitectureLab\GutenbergPostOnlyDemo\Admin\AdminStatusPage;

final class Plugin {
    public static function init(): void {
        $currentEnvironment = defined('WP_ENV') ? (string) WP_ENV : 'production';

        $policyService = new EditorPolicyService([
            new EnvironmentPolicy(
                $currentEnvironment,
                ['local', 'staging']
            ),
            new FeatureFlagPolicy(true),
            new ConfiguredPostTypesPolicy([
                'post' => true,
                'page' => false,
            ]),
            new PostOnlyEditorPolicy(),
        ]);

        (new GutenbergHook($policyService))->register();
        (new AdminStatusPage($policyService))->register();
    }
}