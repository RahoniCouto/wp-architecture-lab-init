# 🚀 WordPress Architecture Labs

Projeto focado em arquitetura para WordPress, ideia aqui é explorar alguns padrões, desacoplamento, organização e testabilidade de código. Cada pasta representa um lab independente com em uma questão arquitetural específica.

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
* Event Driven Architecture.
* Conteiners.
* Testabilidade PHPUnit.

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

## 🚧 Planejados

Ideias já planejadas, será disponibilizado em breve.

### 🧪 005 — Dependency Container Demo

Explorar containers de dependência e resolução automática de serviços. 
Todos os outros Labs podem evoluir com o uso de conteiners.

* Service Container
* Bindings
* Singleton
* Service Resolution
* Container-driven architecture

---

### 🧪 006 — Event Dispatcher Demo

Usar arquitetura orientada a eventos dentro do WordPress.

* Event Dispatcher
* Subscribers
* Domain Events
* Event-driven flows
* Desacoplamento entre módulos

---

## 🏛️ Filosofia do projeto

A intenção do projeto não é criar plugins prontos para produção, mas sim demonstrar arquitetura de software aplicada ao WordPress de forma incremental.

Cada laboratório tenta resolver um problema específico enquanto introduz novas camadas arquiteturais, deixando claro o motivo da existência de cada abordagem e como a complexidade evolui ao longo do projeto.