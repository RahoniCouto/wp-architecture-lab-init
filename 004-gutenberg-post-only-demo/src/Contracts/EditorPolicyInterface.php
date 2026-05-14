<?php
declare(strict_types=1);

namespace ArchitectureLab\GutenbergPostOnlyDemo\Contracts;

interface EditorPolicyInterface {
    public function canUseBlockEditor(string $postType): ?bool;
}