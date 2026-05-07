<?php

declare(strict_types=1);

namespace ArchitectureLab\PluginInicial\Contracts;

interface OptionRepositoryInterface {
    public function getMessage(): string;
    public function getType(): string;
    public function isDashOnly(): bool;
}