<?php
declare(strict_types=1);

namespace ArchitectureLab\GutenbergPostOnlyDemo\Hooks;

use ArchitectureLab\GutenbergPostOnlyDemo\Services\EditorPolicyService;

final class GutenbergHook {
    public function __construct(
        private readonly EditorPolicyService $policyService
    ){}

    public function register(): void {
        add_filter(
            'use_block_editor_for_post_type',
            [$this, 'handle'],
            10,
            2
        );
    }

    public function handle(bool $useBlockEditor, string $postType): bool {
       return $this->policyService->canUseBlockEditor($postType);
    }
}