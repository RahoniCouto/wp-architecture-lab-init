# 🧪 006 — Event Dispatcher Demo

Nesse Lab evoluimos o 002-snapshot-generator-demo aplicando arquitetura orientada a eventos, ideia foi usar um Event Dispatcher simples para substituir chamadas entre hooks e serviços por eventos desacoplatos.

---

## 🎯 Objetivo

Demonstrar:

* event dispatcher
* domain events
* event listeners
* event subscribers
* listener priorities
* stoppable events
* propagação de eventos
* desacoplamento entre hooks e serviços
* arquitetura event-driven aplicada ao WordPress

---

## 🧠 Motivação

Na 002 o fluxo era:

```text
save_post
↓
SnapshotRegenerator
```

O hook usava diretamente o serviço usado para geração do snapshot, após a 006 o fluxo evoluiu para:

```text
save_post
↓
SnapshotRequestedEvent
↓
EventDispatcher
↓
Listeners/Subscribers
↓
SnapshotRegenerator
```

Dessa forma o hook não sabe mais quem reage ao evento.

---

## 🧱 Estrutura

```text
src/
├── Contracts/
│   ├── EventSubscriberInterface.php
│   └── StoppableEventInterface.php
├── Dispatcher/
│   └── EventDispatcher.php
├── Events/
│   └── SnapshotRequestedEvent.php
├── Hooks/
│   └── SavePostHook.php
├── Listeners/
│   ├── GenerateLatestPostsSnapshotListener.php
│   ├── LogSnapshotGenerationListener.php
│   └── ValidationListener.php
```

---

## Event Dispatcher

O dispatcher permite:

```php
$dispatcher->listen(
    SnapshotRequestedEvent::class,
    [$listener, 'handle']
);
```

```php
$dispatcher->dispatch(
    new SnapshotRequestedEvent($postId)
);
```

---

## 🧩 Subscribers

Aqui fazemos o registro automático de:

* Eventos.
* Métodos.
* Prioridade.

```php

public static function getSubscribedEvents(): array
{
    return [
        SnapshotRequestedEvent::class => [
            'method' => 'handle',
            'priority' => 100,
        ],
    ];
}
```

---

## ⏱️ Prioridades

Suporte a prioridade:

```text
100 → executa primeiro
0   → padrão
-10 → executa por último
```


---

## 🛑 Stoppable Events

Método para interromper a propagação:

```php
$event->stopPropagation();
```

Isso permite:

* validações.
* bloqueios.
* interrupção de pipeline.
* cancelamento de side effects.

---

## 🔄 Fluxo da aplicação

```text
SavePostHook
↓
SnapshotRequestedEvent
↓
EventDispatcher
├── ValidationListener
├── LogSnapshotGenerationListener
└── GenerateLatestPostsSnapshotListener
```

---

## 🧬 Testes

Foram criados testes em PHPUnit para:

* listener priorities
* subscriber registration
* stoppable events
* propagation stop
* dispatcher behavior

---

### Rodar os testes

```bash
./vendor/bin/phpunit
```

Docker

```bash
docker compose exec wordpress sh -lc 'cd /var/www/html/wp-content/plugins/006-event-dispatcher-demo && ./vendor/bin/phpunit'
```

---

## 💡 Conceito importante

Ideia do Event Dispatcher não é apenas para chamar listeners, proposta é:

* reduzir acoplamento.
* permitir varias reações independentes
* separar intenção de execução
* facilitar extensibilidade
* melhorar testabilidade
* permitir evolução para filas e processamento assíncrono

---

## 📌 Limitações intencionais

Nesse lab não foi implementado coisas como:

* async queue
* event persistence
* retry strategy
* transactional events
* distributed events
* wildcard listeners
* queued listeners

A ideia é manter o dispatcher simples.