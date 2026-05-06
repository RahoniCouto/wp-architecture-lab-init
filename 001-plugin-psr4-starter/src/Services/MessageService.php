<?php

declare( strict_types=1);

namespace ArquitectureLab\PluginInicial\Services;

use ArquitectureLab\PluginInicial\Infrastructure\OptionRepository;

final class MessageService {
    private const DEFAULT_MESSAGE = 'PSR-4 Inicial plugin está rodando.';

    public function __construct(
        private readonly OptionRepository $repository
    ){}

    public function getMessage(): string {
        $message = $this->repository->getMessage();

        return $message !== '' ? $message : self::DEFAULT_MESSAGE;
    }

    public function getType(): string {
        return $this->repository->getType();
    }

    public function showOrHide(): bool {
        if( !is_admin() ){
            return false;
        }

        if( $this->repository->isDashOnly() ){
            global $pagenow;

            return $pagenow === 'index.php';
        }

        return true;
    }
}

