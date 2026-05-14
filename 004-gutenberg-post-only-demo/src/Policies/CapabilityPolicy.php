<?php
declare(strict_types=1);

namespace ArchitectureLab\GutenbergPostOnlyDemo\Policies;

use ArchitectureLab\GutenbergPostOnlyDemo\Contracts\EditorPolicyInterface;

final class CapabilityPolicy implements EditorPolicyInterface {
    /**
     * @param string[] $grantedCapabilities
     */
    public function __construct(
        private readonly array $grantedCapabilities,
        private readonly string $requiredCapability
    ){}

    public function canUseBlockEditor(string $postType): ?bool {
        if(in_array(
            $this->requiredCapability,
            $this->grantedCapabilities,
            true
        )){
            return true;
        }
       
        return null;
    }
}