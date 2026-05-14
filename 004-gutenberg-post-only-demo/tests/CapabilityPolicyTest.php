<?php
declare(strict_types=1);

use ArchitectureLab\GutenbergPostOnlyDemo\Policies\CapabilityPolicy;
use PHPUnit\Framework\TestCase;

final class CapabilityPolicyTest extends TestCase {
    public function test_capabilities_exists(): void {
        $policy = new CapabilityPolicy(
            ['manage_options'],
            'manage_options'
        );
        $this->assertTrue($policy->canUseBlockEditor('page'));
    }

    public function test_capabilities_missing(): void {
        $policy = new CapabilityPolicy(
            ['edit_posts'],
            'manage_options'
        );
        $this->assertNull($policy->canUseBlockEditor('page'));
    }
}