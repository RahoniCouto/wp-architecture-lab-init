# 🧪 002 — Snapshot Generator Demo (WordPress)

Este laboratório demonstra uma abordagem simples de pré-computação de dados utilizando snapshots em WordPress.

A ideia principal é evitar geração de dados pesados durante a requisição do frontend, movendo esse custo para momentos controlados como salvamento de posts ou execução manual via WP-CLI.

---

## 🎯 Objetivo

Mostrar:
* Geração antecipada de dados
* Separação entre geração e consumo
* arquitetura orientada a services
* redução de queries no frontend

---

## 🧠 Conceito principal

No lugar de fazer varias consultas, muitas vezes complexas no front, como:

request
    🠗
WP_QUERY
    🠗
processamento
    🠗
renderização

Ideia é trabalhar com Snapshots pré-computados:

save_post
    🠗
geração do snapshot
    🠗
persistência
    🠗
frontend consome

---

## 🧱 Estrutura do projeto

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

---

## 🔄 Fluxo geral

save_post 
    🠗
SavePostHook
    🠗
SnapshotRegenerator
    🠗
LatestPostsGenerator
    🠗
SnapshotRepository
    🠗
update_option()


No frontend:

Shortcode
    ↓
SnapshotRepository
    ↓
renderização rápida

---

## ⚙️ Funcionalidades implementadas

* Gera o snapshot dos últimos posts já publicados
* Persistência utilizando Options API.
* Regeneração automatica ao salvar posts
* Shortcode consumindo o snapshot.
* Comando WP-CLI

---

## 🧩 Responsabilidades da arquitetura

### LatestPostsGenerator

Responsável apenas por gerar os dados do snapshot.

Não reconhece:

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

Orquestra:
* Geração
* Persistência

Centraliza a lógica de regeneração do snapshot.

---

### SavePostHooks

Escuta os eventos do WordPress e dispara a regeneração quando preciso.

---

### SnapshotShortcode

Consome o snapshot pronto no frontend sem usar o WP_Query.

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
