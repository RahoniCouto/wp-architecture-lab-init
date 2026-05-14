<?php
declare(strict_types=1);

use ArchitectureLab\GutenbergPostOnlyDemo\Policies\PostOnlyEditorPolicy;
use PHPUnit\Framework\TestCase;

final class PostOnlyEditorPolicyTest extends TestCase {
    public function test_allows_gutenberg_for_posts(): void {
        $policy = new PostOnlyEditorPolicy();
        $this->assertTrue($policy->canUseBlockEditor('post'));
    }

    public function test_allows_gutenberg_for_pages(): void {
        $policy = new PostOnlyEditorPolicy();
        $this->assertFalse($policy->canUseBlockEditor('page'));
    }

    public function test_allows_gutenberg_for_others(): void {
        $policy = new PostOnlyEditorPolicy();
        $this->assertNull($policy->canUseBlockEditor('product'));
    }
}