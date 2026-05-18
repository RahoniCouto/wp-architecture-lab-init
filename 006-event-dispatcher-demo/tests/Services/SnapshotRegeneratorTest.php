<?php
declare(strict_types=1);

use ArchitectureLab\EventDispatcherDemo\Contracts\SnapshotRepositoryInterface;
use ArchitectureLab\EventDispatcherDemo\Contracts\LatestPostsGeneratorInterface;
use ArchitectureLab\EventDispatcherDemo\Services\SnapshotRegenerator;
use PHPUnit\Framework\TestCase;

final class SnapshotRegeneratorTest extends TestCase {
    public function test_regenerator_latest_posts_snapshot(): void {
        $generateData = [
            [
                'id' => 1,
                'title' => 'Post 1',
                'permalink' => 'http://localhost/post-1',
                'date' => '2026-05-12T10:00:00-03:00',
            ],
            [
                'id' => 2,
                'title' => 'Post 2',
                'permalink' => 'http://localhost/post-2',
                'date' => '2026-05-12T10:00:00-04:00',
            ],
        ];

        $generator = $this->createMock(LatestPostsGeneratorInterface::class);

        $generator->expects($this->once())->method('generate')->with(5)->willReturn($generateData);

        $repository = $this->createMock(SnapshotRepositoryInterface::class);

        $repository->expects($this->once())->method('save')->with(SnapshotRegenerator::SNAPSHOT_KEY, $generateData);

        $regenerator = new SnapshotRegenerator(
            $generator,
            $repository
        );

        $count = $regenerator->regenerateLatestPosts();

        $this->assertSame(2, $count);
    }
}