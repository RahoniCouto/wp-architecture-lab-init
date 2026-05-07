<?php
declare(strict_types=1);

use ArquitectureLab\PluginInicial\Contracts\OptionRepositoryInterface;
use ArquitectureLab\PluginInicial\Services\MessageService;
use PHPUnit\Framework\TestCase;

final class MessageServiceTest extends TestCase {
    private function createRepository(array $data): OptionRepositoryInterface {
        return new class($data) implements OptionRepositoryInterface {
            public function __construct( private readonly array $data){}

            public function getMessage(): string {
                return (string) ($this->data['message'] ?? '');
            }

            public function getType(): string {
                return (string) ($this->data['type'] ?? 'success');
            }

            public function isDashOnly(): bool {
                return (bool) ($this->data['dash_only'] ?? false);
            }
        };
    }

    public function test_return_default_when_message_empty(): void {
        $service = new MessageService($this->createRepository(['message' => '']));
        $this->assertSame('PSR-4 Inicial plugin está rodando.', $service->getMessage());
    }

    public function test_return_notice_type(): void {
        $service = new MessageService($this->createRepository(['type' => 'error']));
        $this->assertSame('error', $service->getType());
    }

    public function test_return_custom_message_when_message_exists(): void {
        $service = new MessageService($this->createRepository(['message' => 'Mensagem Custom']));
        $this->assertSame('Mensagem Custom', $service->getMessage());
    }
}