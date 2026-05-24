# 🧪 006 — Event Dispatcher Demo

Nesse Lab evoluimos o 002-snapshot-generator-demo aplicando uma camada simples de Event Dispatcher.

A ideia foi substituir chamadas diretas entre hooks e serviços por eventos internos, mantendo o fluxo mais desacoplado e mais fácil de testar.

Este lab tem escopo limitado, ideia não é propor Event Dispatcher como solução para concorrência, mensageria distribuida, filas, isolamento de recursos ou processamento em larga escala dentro do WordPress.

---

## 🎯 Objetivo

Demonstrar:

* event dispatcher.
* domain events simples.
* event listeners.
* event subscribers.
* listener priorities.
* stoppable events.
* propagação de eventos.
* desacoplamento entre hooks e serviços.
* limites de event-driven architecture aplicada ao WordPress.

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

Isso permite adicionar varias ações ao mesmo fluxo sem alterar diretamente o hook inicial.

Exemplos:

* gerar snapshot.
* validar se o evento deve continuar.
* registrar logs simples.
* disparar outros efeitos colaterais locais e baratos.

---

## ✅ Quando esse padrão faz sentido no WordPress

Um Event Dispatcher local pode fazer sentido quando o objetivo é organizar código dentro de um plugin ou módulo WordPress.

Uso recomendado:

* separar intenção de execução;
* evitar que hooks chamem muitos serviços diretamente;
* centralizar reações relacionadas a um mesmo evento interno;
* melhorar testabilidade;
* permitir múltiplos listeners pequenos e independentes;
* organizar efeitos colaterais locais, rápidos e previsíveis.

Exemplo de uso aceitável:

```text
save_post
↓
SnapshotRequestedEvent
├── valida se o post deve gerar snapshot
├── registra um log simples
└── chama o serviço de regeneração local
```

Nesse cenário, o dispatcher é apenas uma camada de orquestração interna.

---

## ❌ Quando esse padrão não deve ser usado como solução principal

Event Dispatcher dentro do WordPress não deve ser tratado como solução para:

* concorrência real;
* processamento distribuído;
* filas robustas;
* tarefas longas;
* workloads com alto volume de eventos;
* consistência transacional entre múltiplos serviços;
* isolamento forte de recursos;
* comunicação entre sistemas críticos;
* processamento assíncrono confiável em larga escala.

O WordPress roda em cima de um modelo predominantemente síncrono por request. Além disso, o sistema de hooks do WordPress é global, baseado em prioridade e pode ser afetado por plugins, temas e integrações externas.

Isso torna difícil garantir uma árvore de execução previsível em ambientes grandes, especialmente quando muitos plugins disputam banco, cache, CPU, memória e hooks globais.

---

## ⚠️ Limites específicos no contexto WordPress/PHP

Esse lab reconhece algumas limitações importantes:

* PHP tradicional em WordPress não oferece, por padrão, um modelo nativo de atores, processos leves ou concorrência refinada como Go, Elixir ou Scala.
* WordPress não fornece isolamento forte de recursos entre plugins.
* Hooks globais podem criar fluxos difíceis de prever.
* Prioridades precisam ser controladas manualmente.
* Estado persistente e compartilhado exige muito cuidado.
* Efeitos colaterais pesados dentro de requests podem prejudicar performance e disponibilidade.

Por isso, este lab não tenta transformar o WordPress em uma plataforma event-driven completa.

A proposta é mais modesta: estudar como um dispatcher pode melhorar organização local de código em cenários pequenos e controlados.

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
* permitir varias reações independentes.
* separar intenção de execução.
* facilitar extensibilidade controlada.
* melhorar testabilidade.
* manter hooks mais finos.
* estudar limites arquiteturais dentro do WordPress.

---

## 🧭 Como pensar em produção

Para projetos pequenos e médios, um dispatcher local pode ser suficiente para organizar efeitos colaterais simples.

Para ambientes maiores, críticos ou com alto volume, o dispatcher não deve executar trabalho pesado diretamente. Ele pode, no máximo, registrar a intenção de trabalho e delegar o processamento para uma camada operacional mais adequada.

Em um cenário WordPress mais robusto, considere:

* eventos idempotentes.
* deduplicação por hash.
* locks com expiração.
* TTL para evitar trabalhos órfãos.
* logs e métricas.
* comandos WP-CLI para reprocessamento.
* cron real do sistema quando fizer sentido.
* filas externas quando houver volume ou criticidade.
* workers fora do request principal.
* serviços dedicados para workloads realmente concorrentes ou distribuídos.

Ferramentas internas do ecossistema WordPress podem ser úteis em alguns cenários, mas não devem ser assumidas como resposta universal para carga alta. Em produção, a escolha entre Action Scheduler, WP-Cron, cron do sistema, fila externa ou serviço dedicado precisa considerar volume, memória, tempo de execução, tolerância a falhas, retentativas, observabilidade e controle operacional.

---

## 📌 Limitações intencionais

Nesse lab não foi implementado coisas como:

* async queue.
* event persistence.
* retry strategy.
* transactional events.
* distributed events.
* wildcard listeners.
* queued listeners.
* locks.
* deduplication hash.
* TTL.
* external workers.
* observability layer.

A ideia é manter o dispatcher simples.

---

## ✅ Resumo da posição técnica

Event Dispatcher em WordPress pode ser útil como padrão de organização local.

Ele ajuda a desacoplar hooks, serviços e efeitos colaterais simples.

Mas ele não resolve os problemas estruturais do WordPress em larga escala: concorrência, previsibilidade de execução, isolamento de recursos, processamento assíncrono confiável e arquitetura distribuída.

Quando o problema deixa de ser organização de código e passa a ser escala operacional, o caminho mais seguro geralmente é mover o processamento pesado para uma fila, worker, cron controlado ou serviço externo apropriado.