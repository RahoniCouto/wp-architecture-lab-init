# 🧪 001 — Plugin PSR-4 Inicial (WordPress Architecture Lab)

Este projeto faz parte de um laboratório de arquitetura WordPress focado em boas práticas modernas de desenvolvimento.
O objetivo não é criar um plugin funcional complexo, mas demonstrar uma base sólida de arquitetura, organização de código e separação de responsabilidades.

---

## 🎯 Objetivo

Demonstrar como estruturar um plugin WordPress moderno utilizando:

* Composer + PSR-4 (autoload)
* Namespaces
* Injeção de dependência (DI)
* Separação de responsabilidades (Hook / Service / Repository)
* Encapsulamento da Options API
* Admin UI com segurança (nonce + capability)
* Código testável e escalável

---

## 🧱 Estrutura do projeto

```
src/
├── Admin/
│   └── SettingsPage.php
├── Hooks/
│   └── AdminNoticeHook.php
├── Infrastructure/
│   └── OptionRepository.php
├── Services/
│   ├── MessageService.php
│   └── NoticeRenderer.php
└── Plugin.php
```

---

## 🧠 Arquitetura

### 🔹 Plugin (Bootstrap)

Responsável por montar as dependências e inicializar o sistema.

```php
$repository = new OptionRepository();
$messageService = new MessageService($repository);
$renderer = new NoticeRenderer();

(new AdminNoticeHook($messageService, $renderer))->register();
(new SettingsPage($repository))->register();
```

---

### 🔹 Hooks

Responsáveis por integrar com o WordPress (actions/filters).

```txt
AdminNoticeHook → escuta admin_notices
```

Sem lógica de negócio.

---

### 🔹 Services

Responsáveis pela lógica da aplicação.

```txt
MessageService → decide conteúdo e visibilidade
NoticeRenderer → renderiza HTML do notice
```

---

### 🔹 Infrastructure

Camada que fala com o WordPress.

```txt
OptionRepository → encapsula get_option / update_option
```

Evita espalhar dependência do WordPress pelo código.

---

## 🔄 Fluxo de execução

```
WordPress admin
    ↓
AdminNoticeHook
    ↓
MessageService
    ↓
OptionRepository
    ↓
NoticeRenderer
    ↓
HTML do notice
```

---

## ⚙️ Funcionalidades

* Mensagem customizável via admin
* Tipo de aviso configurável:
  * success
  * error
  * warning
  * info
* Opção de exibir apenas no Dashboard
* Validação de segurança com nonce
* Controle de acesso com capability (`manage_options`)

---

## 🧪 Interface administrativa

Disponível em:

```
WP Admin → Tools → Architecture Lab
```

Permite:

* Definir mensagem do aviso
* Escolher tipo de alerta
* Restringir exibição ao dashboard

---

## 🧠 Conceitos demonstrados

### ✔ PSR-4 Autoload

Elimina `require` manual e organiza o projeto por namespaces.

---

### ✔ Dependency Injection (DI)

Classes recebem dependências ao invés de criar internamente.

```php
new MessageService($repository);
```

---

### ✔ Separação das preocupações

| Camada     | Responsabilidade |
| ---------- | ---------------- |
| Plugin     | Bootstrap        |
| Hook       | Integração WP    |
| Service    | Regra de negócio |
| Repository | Acesso a dados   |

---

### ✔ Encapsulamento da Options API

```php
get_option → OptionRepository
```

Evita acoplamento direto com WordPress.

---

## 🚀 Como rodar

```bash
composer install
```

Ativar o plugin no WordPress:

```
Plugins → Architecture Lab - PSR-4 Inicial
```