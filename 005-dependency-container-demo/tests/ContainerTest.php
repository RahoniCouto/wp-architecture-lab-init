<?php
declare(strict_types=1);

use ArchitectureLab\DependencyContainerDemo\Container\Container;
use PHPUnit\Framework\TestCase;

interface DummyRepositoryInterface{}

final class DummyRepository implements DummyRepositoryInterface{}

final class DummyService{
    public function __construct(
        public readonly DummyRepositoryInterface $repository
    ){}
}

final class ContainerTest extends TestCase {

    public function test_lazyloaded(): void {
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

    public function test_auto_wire(): void {
        $container = new Container();

        $container->set(
            DummyRepositoryInterface::class,
            fn (): DummyRepositoryInterface => new DummyRepository()
        );

        $service = $container->resolve(DummyService::class);

        $this->assertInstanceOf(DummyService::class, $service);
    }

    public function test_resolve_alias(): void {
        $container = new Container();

        $instance = new stdClass();

        $container->set(
            'service',
            fn (): object => $instance
        );

        $container->alias(
            'alias-service',
            'service'
        );

        $resolved = $container->get('alias-service');

        $this->assertSame($instance, $resolved);
    }
}