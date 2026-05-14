<?php
declare(strict_types=1);

namespace ArchitectureLab\GutenbergPostOnlyDemo\Policies;

use ArchitectureLab\GutenbergPostOnlyDemo\Contracts\EditorPolicyInterface;

final class EnvironmentPolicy implements EditorPolicyInterface {
    /**
     * @param string[] $allowedEnviroments
     */
    public function __construct(
        private readonly string $currentEnvironment,
        private readonly array $allowedEnvironments
    ){}

    public function canUseBlockEditor(string $postType): ?bool {
        if(in_array(
            $this->currentEnvironment,
            $this->allowedEnvironments,
            true
        )){
            return true;
        }
       
        return null;
    }
}