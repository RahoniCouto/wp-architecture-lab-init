<?php
declare(strict_types=1);

use ArchitectureLab\GutenbergPostOnlyDemo\Policies\FeatureFlagPolicy;
use PHPUnit\Framework\TestCase;

final class FeatureFlagPolicyTest extends TestCase {
    public function test_feature_is_desabled(): void {
        $policy = new FeatureFlagPolicy(false);
        $this->assertFalse($policy->canUseBlockEditor('post'));
        $this->assertFalse($policy->canUseBlockEditor('page'));
    }

    public function test_feature_is_enabled(): void {
        $policy = new FeatureFlagPolicy(true);
        $this->assertNull($policy->canUseBlockEditor('post'));
    }
}