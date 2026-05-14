# 🧪 004 — Gutenberg Post Only Demo

Ideia desse lab é demonstra uma forma mais organizada de controlar o uso do Gutenberg por post type no WordPress, evitando regras soltas diretamente em hooks e filtros, separando a decisão em policies testáveis.

---

## 🎯 Objetivo

* Demonstrar controle sobre habilitar Gutenberg.
* Separar regra de hook do WordPress.
* Trabalhar com cadeia de policies.
* Testar sem depender do WordPress

---

## 🧠 Conceito principal

No lugar de toda lógica no filtro:

```php
use_block_editor_for_post_type
```

Usamos camadas:

```text
WordPress Hook
    ↓
GutenbergHook
    ↓
EditorPolicyService
    ↓
Policy chain
    ↓
true / false
```

Cada uma podendo controlar:

```text
true  → permite Gutenberg
false → bloqueia Gutenberg
null  → não opina
```

A policy que retornar a decisão primeiro vence.

---

## 🧱 Estrutura do projeto

```text
src/
├── Admin/
│   └── AdminStatusPage.php
├── Contracts/
│   └── EditorPolicyInterface.php
├── Hooks/
│   └── GutenbergHook.php
├── Policies/
│   ├── CapabilityPolicy.php
│   ├── ConfiguredPostTypesPolicy.php
│   ├── EnvironmentPolicy.php
│   ├── FeatureFlagPolicy.php
│   └── PostOnlyEditorPolicy.php
├── Services/
│   └── EditorPolicyService.php
└── Plugin.php
```

---

## ⚙️ Funcionalidades implementadas

* Gutenberg ativo para post.
* Gutenberg inativo para page.
* Policy chain com (true, false e null).
* Página no admin para visualizar onde Gutenberg está ativo.
* PHPUnit para validações.

---

## 🧩 Policies

### ConfiguredPostTypesPolicy

Controle por post_type.

```php
[
    'post' => true,
    'page' => false,
]
```

---

### PostOnlyEditorPolicy

Policy basica com regra base.

```text
post → true
page → false
outros → null
```

---

### FeatureFlagPolicy

Permite ligar ou desligar o Gutenberg de forma geral. Quando desativada, bloqueia tudo.

---

### EnvironmentPolicy

Controle de regras por ambente.

```text
local/staging → Gutenberg liberado
production → segue a chain normal
```

---

### CapabilityPolicy

Policy criada para demonstrar regra por permissão/capability.

Ela está implementada e testada, mas não fica ativa por padrão no Plugin.php, para preservar o comportamento principal que quero demonstrar aqui.

```text
post → Gutenberg ativo
page → Gutenberg inativo
```

---

## 🔄 Fluxo padrão

```text
GutenbergHook
    ↓
EditorPolicyService
    ↓
EnvironmentPolicy
    ↓
FeatureFlagPolicy
    ↓
ConfiguredPostTypesPolicy
    ↓
PostOnlyEditorPolicy
    ↓
fallback false
```

---

## 🖥️ Página administrativa

```text
Tools → Gutenberg Policy Demo
```

Página mostra quais post_types podem usar Gutenberg.

---

## 🧬 Testes

Testes unitários com PHPUnit.

* PostOnlyEditorPolicy
* ConfiguredPostTypesPolicy
* FeatureFlagPolicy
* EnvironmentPolicy
* CapabilityPolicy
* resolução da chain no EditorPolicyService

Rodar testes:

```bash
./vendor/bin/phpunit
```

Docker:

```bash
docker compose exec wordpress sh -lc 'cd /var/www/html/wp-content/plugins/004-gutenberg-post-only-demo && ./vendor/bin/phpunit'
```

---

## 📌 Observações

Esté lab é intencionalmente simples, foco aqui é demonstrar uma arquitetura limpa para regras no WordPress.

Ideia aqui já suporta:

* Policy chain.
* Regras por usuário.
* Regras por capability.
* Regras por ambiente.
* Feature flags.
* decisões configuráveis por post type
* testes desacoplados do WordPress

Em projetos reais pode evoluir para:

* policies carregadas dinamicamente via banco de dados.
* integração com sistemas de permissões.
* resolução de policies via container de dependências.
* cache de decisões.
* múltiplos providers de configuração
* integração com feature flag services externos
* resolução contextual baseada em usuário, ambiente e conteúdo simultaneamente