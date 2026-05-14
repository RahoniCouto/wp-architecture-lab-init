<?php
declare(strict_types=1);

namespace ArchitectureLab\GutenbergPostOnlyDemo\Services;

use ArchitectureLab\GutenbergPostOnlyDemo\Contracts\EditorPolicyInterface;

final class EditorPolicyService {
    /**
     * @param EditorPolicyInterface[] $policies
     */
    public function __construct(
        private readonly array $policies
    ){}

    public function canUseBlockEditor(string $postType): bool {
        foreach($this->policies as $policy){
            $decision = $policy->canUseBlockEditor($postType);

            if($decision !== null){
                return $decision;
            }
        }
       
        return false;
    }
}