<?php

declare( strict_types=1);

final class OptionRepository {
    private const OPTION_MESSAGE = 'architecture_lab_psr4_inicial_message';

    public function getMessage(): string {
        $value = get_option( self::OPTION_MESSAGE, '');

        if ( !is_string($value) ){
            return '';
        }

        return trim( $value );
    }

    public function updateMessage( string $message): void {
        update_option( self::OPTION_MESSAGE, sanitize_text_field( $message ) );
    }
}