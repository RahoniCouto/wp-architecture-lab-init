<?php
declare(strict_types=1);

namespace ArchitectureLab\GutenbergPostOnlyDemo\Policies;

use ArchitectureLab\GutenbergPostOnlyDemo\Contracts\EditorPolicyInterface;

final class PostOnlyEditorPolicy implements EditorPolicyInterface {
    private const ALLOWED_POST_TYPES = [
        'post',
    ];

    public function canUseBlockEditor(string $postType): ?bool {
        if($postType === 'post'){
            return true;
        }

        if($postType === 'page'){
            return false;
        }

        return null;
    }
}