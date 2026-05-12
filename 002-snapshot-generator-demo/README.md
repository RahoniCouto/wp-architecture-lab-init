# 🧪 002 — Snapshot Generator Demo (WordPress)

Este laboratório demonstra uma abordagem simples de pré-computação de dados utilizando snapshots em WordPress.

A ideia principal é evitar geração de dados pesados durante a requisição do frontend, movendo esse custo para momentos controlados como salvamento de posts ou execução manual via WP-CLI.

---

## 🎯 Objetivo

Mostrar:
* Geração antecipada de dados
* Separação entre geração e consumo
* arquitetura orientada a serviços
* redução de queries no frontend

---

## 🧠 Conceito principal

No lugar de executar consultas repetidas e muitas vezes custosas durante a requisição do frontend

```text
request
    ↓
WP_Query
    ↓
processamento
    ↓
renderização
```

Ideia é trabalhar com Snapshots pré-computados:

```text
save_post
    ↓
geração do snapshot
    ↓
persistência
    ↓
frontend consome
```

---

## 🧱 Estrutura do projeto

```
src/
├── Cli/
│   └── RegenerateCommand.php
├── Contracts/
│   └── SnapshotRepositoryInterface.php
├── Frontend/
│   └── SnapshotShortcode.php
├── Generator/
│   └── LatestPostsGenerator.php
├── Hooks/
│   └── SavePostHook.php
├── Infrastructure/
│   └── SnapshotRepository.php
├── Services/
│   └── SnapshotRegenerator.php
└── Plugin.php
```

---

## 🔄 Fluxo geral

```text
save_post 
    ↓
SavePostHook
    ↓
SnapshotRegenerator
    ↓
LatestPostsGenerator
    ↓
SnapshotRepository
    ↓
update_option()
```

No frontend:

```text
Shortcode
    ↓
SnapshotRepository
    ↓
renderização rápida
```

---

## ⚙️ Funcionalidades implementadas

* Gera o snapshot dos últimos posts já publicados
* Persistência utilizando a WordPress Options API.
* Regeneração automática ao salvar posts
* Shortcode consumindo o snapshot.
* Comando WP-CLI

---

## 🧩 Responsabilidades da arquitetura

### LatestPostsGenerator

Responsável apenas por gerar os dados do snapshot.

Não possui responsabilidade sobre:

* Armazenamento
* Frontend
* Hooks

---

### SnapshotRepository

Responsável apenas pela persistência dos snapshots

utiliza:
* Options API

Pode evoluir para:
* Transients API
* tabela customizada
* Redis/object cache
* arquivos JSON

---

### SnapshotRegenerator

Responsável pela orquestração da regeneração dos snapshots

Orquestra:
* Geração
* Persistência

Centraliza a lógica de regeneração do snapshot.

---

### SavePostHook

Escuta os eventos do WordPress e dispara a regeneração quando necessário.

---

### SnapshotShortcode

Consome o snapshot pronto no frontend sem usar o WP_Query.

---

## 🔌 Desacoplamento

Uso de contratos/interfaces possibilita evitar acoplamento direto entre geração, persistência e consumo dos snapshots

Permitindo:

* Trocar implementações futuras de forma simples.
* Simplificar os testes.
* Reutilizar serviços.
* Evoluir a persistência sem ter que mudar toda a aplicação.

---

## ✨ Por que o frontend fica mais leve?

Porque o shortcode não irá executar consultas no momento que é requisitado, ele apenas ira ler o snapshot já pronto e renderizar, reduzindo as consultas repetidas, processamento em runtime e custos por requisição.

```text
frontend
    ↓
snapshot pronto
    ↓
renderização
```

---

## 🚀 Shortcode

```text
[snapshot_latest_posts]
```

---

## 🚀 WP-CLI

Regenerar snapshot manualmente:

```bash
wp snapshot-demo regenerate
```

Definir limite:

```bash
wp snapshot-demo regenerate --limit=10
```

---

## 💡 Performance

O principal objetivo é mostrar a diferença entre:
* gerar em runtime
* Apenas consumir dados

Mesmo simples, essa arquitetura utilizada pode ser usada em sites de alto tráfego onde acontecem diversas consultas que podem ser custosas.

## 🧬 Testes

Testes com PHPUnit para validação sem depender do runtime completo do WordPress.

* Execução do gerador.
* Persistência.
* Integração entre os Services.
* Retorno da geração.

Executar:

```bash
./vendor/bin/phpunit
```

Docker:

```bash
docker compose exec wordpress sh -lc 'cd /var/www/html/wp-content/plugins/002-snapshot-generator-demo && ./vendor/bin/phpunit'
```

---

### Frontend sem WP_Query

O shortcode não executa consulta em runtime:

```text
frontend
    ↓
repository
    ↓
snapshot pronto
```

Usa uma abordagem baseada no consumo de dados pré-computados.

