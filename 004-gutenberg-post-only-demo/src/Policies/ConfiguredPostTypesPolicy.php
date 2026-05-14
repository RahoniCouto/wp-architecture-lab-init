<?php
declare(strict_types=1);

namespace ArchitectureLab\GutenbergPostOnlyDemo\Policies;

use ArchitectureLab\GutenbergPostOnlyDemo\Contracts\EditorPolicyInterface;

final class ConfiguredPostTypesPolicy implements EditorPolicyInterface {
    /**
     * @param array<string, bool> $postTypeRules
     */
    public function __construct(
        private readonly array $postTypeRules
    ){}

    public function canUseBlockEditor(string $postType): ?bool {
        foreach($this->policies as $policy){
            if(!array_key_exists($postType, $this->postTypeRules)){
                return null;
            }
        }
       
        return $this->postTypeRules[$postType];
    }
}