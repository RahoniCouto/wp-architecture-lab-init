# 🚀 WordPress Architecture Labs

Projeto focado em arquitetura para WordPress, ideia aqui é explorar alguns padrões, desacoplamento, organização e testabilidade de código. Cada pasta representa um lab independente com em uma questão arquitetural específica.

Este repositório é um laboratório de estudo. Ele não propõe que todos esses padrões devam ser aplicados em qualquer plugin, tema ou projeto WordPress em produção.

---

## Objetivos

Demonstrar o uso:

* PSR-4.
* Dependency Injection.
* Service Layer.
* Contracts|Interfaces.
* Arquitetura de Snapshots.
* Fragment Cache.
* Policy Chain.
* Event Dispatcher local.
* Containers.
* Testabilidade PHPUnit.

---

## ⚠️ Escopo e limites do projeto

O objetivo desses labs não é transformar WordPress em Laravel, Symfony, Go, Elixir ou em uma plataforma distribuída.

WordPress possui características próprias:

* ciclo de execução síncrono por request;
* hooks globais baseados em prioridade;
* concorrência limitada pelo modelo PHP/servidor;
* muitos plugins disputando os mesmos recursos;
* estado compartilhado via banco, options, post meta, transients e cache;
* baixa previsibilidade quando múltiplos plugins alteram o mesmo fluxo.

Por isso, os padrões apresentados aqui devem ser entendidos como ferramentas de organização interna, não como solução automática de escala, concorrência, mensageria, processamento distribuído ou isolamento de recursos.

Em ambientes críticos ou de grande escala, tarefas pesadas, concorrentes, recorrentes ou sensíveis a falhas devem ser avaliadas fora do runtime principal do WordPress, usando filas, workers, locks, TTLs, idempotência, observabilidade e, quando fizer sentido, serviços dedicados em tecnologias mais apropriadas para processamento assíncrono ou concorrente.

---

## 📚 Laboratórios

### 🧪 001 — Plugin PSR-4 Starter

Base de um plugin moderno.

* PSR-4
* Composer
* Services
* Repository
* Dependency Injection manual
* Organização arquitetural

---

### 🧪 002 — Snapshot Generator Demo

Demonstração de pré-computação de dados com snapshots.

* geração antecipada
* separação entre geração e consumo
* persistência de snapshots
* redução de processamento em runtime

---

### 🧪 003 — Fragment Cache Lite

Sistema simples de fragment cache focado em HTML renderizado.

* Fragment Cache
* TTL
* Lazy Regeneration
* WP-CLI
* Contracts
* Cache abstraction
* PHPUnit
* isolamento de WP_Query via provider

---

### 🧪 004 — Gutenberg Post Only Demo

Controle arquitetural do editor Gutenberg usando policies desacopladas.

* Policy Chain
* Feature Flags
* Regras por ambiente
* Regras por capability
* Services
* Hooks desacoplados
* PHPUnit
* arquitetura editorial

---

### 🧪 005 — Dependency Container Demo

Explorar containers de dependência e resolução automática de serviços. 
Todos os outros Labs podem evoluir com o uso de containers.

* Service Container
* Bindings
* Singleton
* Service Resolution
* Container-driven architecture

---

### 🧪 006 — Event Dispatcher Demo

Explorar o uso limitado de um Event Dispatcher dentro do WordPress para desacoplar efeitos colaterais locais.

* Event Dispatcher local.
* Subscribers.
* Domain Events simples.
* Listener priorities
* Stoppable events
* Desacoplamento entre hooks e serviços
* Limites de event-driven architecture dentro do WordPress

Este lab não apresenta Event Dispatcher como solução para concorrência, filas, mensageria distribuída ou processamento em larga escala. O objetivo é apenas estudar como separar intenção e reação dentro de um plugin WordPress.

---

## 🏛️ Filosofia do projeto

A intenção do projeto não é criar plugins prontos para produção, mas sim demonstrar arquitetura de software aplicada ao WordPress de forma incremental.

Cada laboratório tenta resolver um problema específico enquanto introduz novas camadas arquiteturais, deixando claro o motivo da existência de cada abordagem e como a complexidade evolui ao longo do projeto.

O ponto principal é entender quando uma abstração ajuda, quando ela atrapalha e quais limites o próprio WordPress impõe antes de uma solução precisar sair do ecossistema WordPress.