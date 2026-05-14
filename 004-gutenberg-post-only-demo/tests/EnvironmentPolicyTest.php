<?php
declare(strict_types=1);

use ArchitectureLab\GutenbergPostOnlyDemo\Policies\EnvironmentPolicy;
use PHPUnit\Framework\TestCase;

final class EnvironmentPolicyTest extends TestCase {
    public function test_allowed_environment(): void {
        $policy = new EnvironmentPolicy('local', ['local', 'staging']);
        $this->assertTrue($policy->canUseBlockEditor('post'));
        $this->assertTrue($policy->canUseBlockEditor('page'));
    }

    public function test_no_allowed_environment(): void {
        $policy = new EnvironmentPolicy('production', ['local', 'staging']);
        $this->assertNull($policy->canUseBlockEditor('post'));
    }
}