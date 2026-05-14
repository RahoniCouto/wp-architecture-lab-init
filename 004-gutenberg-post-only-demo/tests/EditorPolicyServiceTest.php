<?php
declare(strict_types=1);

use ArchitectureLab\GutenbergPostOnlyDemo\Contracts\EditorPolicyInterface;
use ArchitectureLab\GutenbergPostOnlyDemo\Services\EditorPolicyService;
use PHPUnit\Framework\TestCase;

final class EditorPolicyServiceTest extends TestCase {
    public function test_returns_policy_decisions(): void {
        $firstPolicy = new class implements EditorPolicyInterface {
            public function canUseBlockEditor(string $postType): ?bool {
                return null;
            }
        };

        $secondPolicy = new class implements EditorPolicyInterface {
            public function canUseBlockEditor(string $postType): ?bool {
                return true;
            }
        };

        $service = new EditorPolicyService([
            $firstPolicy,
            $secondPolicy,
        ]);

        $this->assertTrue($service->canUseBlockEditor('product'));
    }

    public function test_fallback_no_police_decisions(): void {
        $policy = new class implements EditorPolicyInterface {
            public function canUseBlockEditor(string $postType): ?bool {
                return null;
            }
        };

        $service = new EditorPolicyService([$policy]);

        $this->assertFalse($service->canUseBlockEditor('unknown'));
    }

    public function Test_stop_after_desicision(): void {
        $firstPolicy = new class implements EditorPolicyInterface {
            public function canUseBlockEditor(string $postType): ?bool {
                return false;
            }
        };

        $secondPolicy = new class implements EditorPolicyInterface {
            public function canUseBlockEditor(string $postType): ?bool {
                throw new RuntimeException('Second policy should not be called.');
            }
        };

        $service = new EditorPolicyService([
            $firstPolicy,
            $secondPolicy,
        ]);

        $this->assertTrue($service->canUseBlockEditor('post'));
    }    
}