<?php
declare(strict_types=1);

use ArchitectureLab\DependencyContainerDemo\Container\Container;
use RuntimeException;
use PHPUnit\Framework\TestCase;

final class ContainerTest extends TestCase {
    private function test_lazyloaded(): void {
        $container = new Container();

        $created = false;

        $container->set('service', function () use (&$created): object {
            $created = true;
            return new stdClass();
        });

        $this->assertFalse($created);
        $container->get('service');
        $this->assertTrue($created);
    }

    public function test_reuse_after_first_call(): void {
        $container = new Container();

        $container->set('service', fn (): object => new stdClass());

        $first = $container->get('service');
        $second = $container->get('service');
        
        $this->assertSame($first, $second);
    }

    public function test_throws_for_unknown(): void {
        $container = new Container();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Service "missing" não está registrado.');

        $container->get('missing');
    }
}