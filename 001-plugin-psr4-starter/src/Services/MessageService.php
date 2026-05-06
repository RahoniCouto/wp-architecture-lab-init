<?php

declare( strict_types=1);

namespace ArquitectureLab\PluginInicial\Services;

use ArquitectureLab\PluginInicial\Infrastructure\OptionRepository;

final class MessageService {
    private const DEFAULT_MESSAGE = 'PSR-4 Inicial plugin está rodando.';

    public function __construct(
        private readonly OptionRepository $optionRepository
    ){}

    public function getAdminMessage(): string {
        $message = $this->optionRepository->getMessage();

        if( $message === '' ){
            return self::DEFAULT_MESSAGE;
        }

        return $message;
    }
}

