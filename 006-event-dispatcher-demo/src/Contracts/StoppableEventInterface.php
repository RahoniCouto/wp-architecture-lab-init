<?php
declare(strict_types=1);

namespace ArchitectureLab\EventDispatcherDemo\Contracts;

interface StoppableEventInterface{
    public function stopPropagation(): void;
    public function isPropagationStopped(): bool;
}