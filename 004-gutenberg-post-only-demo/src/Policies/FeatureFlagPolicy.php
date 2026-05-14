<?php
declare(strict_types=1);

namespace ArchitectureLab\GutenbergPostOnlyDemo\Policies;

use ArchitectureLab\GutenbergPostOnlyDemo\Contracts\EditorPolicyInterface;

final class FeatureFlagPolicy implements EditorPolicyInterface {
    public function __construct(
        private readonly bool $enabled
    ){}

    public function canUseBlockEditor(string $postType): ?bool {
        if($this->enabled === false){
            return false;
        }
       
        return null;
    }
}