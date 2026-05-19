<?php
declare(strict_types=1);

namespace ArchitectureLab\EventDispatcherDemo\Contracts;

interface EventSubscriberInterface{
    /**
     * @return array<string, array{
     *      method: string,
     *      priority?: int
     * }>
     */
    public static function getSubscribedEvents(): array;
}