# 🧪 005 — Dependency Container Demo

Esse lab é uma evolução direta do 001-plugin-psr4-starter, nele mudamos de injeção de dependência (DI) manual por um container baseado em services e resolução automática.

---

## 🎯 Objetivo

Objetivo aqui não foi criar um conteiner de alto nivel, mas demonstrar os conceitos fundamentais por trás de um container, containers de alto nivel são encontrados principalmente em flameworks como Laravel.

Demonstração:

* Dependency injection.
* Service container.
* Lazy loading.
* Singleton por instância.
* Service factories.
* Auto wiring via reflection.
* Aliases de serviços.
* Separação entre bootstrap e configuração.

---

## 🧠 Motivação

Demonstrar uma evolução do sistema de DI manual utilizado.

```php
$repository = new OptionRepository();

$messageService = new MessageService(
    $repository
);

$renderer = new NoticeRenderer();

(new AdminNoticeHook(
    $messageService,
    $renderer
))->register();
```

Com o crescimento de uma aplicação o Plugin.php vai acumulando muita responsabilidade, montando muitos objetos e tornando leitura díficil, ao usar conteiners centralizamos resolvendo:

* Registros de dependências.
* Resolução de objetos.
* Gerenciamento de instâncias.
* Contrução automática de dependências.

---

## 🧱 Estrutura

```text
src/
├── Admin/
├── Cli/
├── Container/
│   └── Container.php
├── Contracts/
├── Hooks/
├── Infrastructure/
├── Providers/
│   └── AppServiceProvider.php
├── Services/
└── Plugin.php
```

---

## ⚙️ Conceitos implementados

### Service registration

```php
$container->set(
    OptionRepositoryInterface::class,
    fn (): OptionRepositoryInterface => new OptionRepository()
);
```

---

### Lazy loading

Serviços são criados apenas quando realmente utilizados.

---

### Singleton por instância

Resolvemos o serviço apenas uma vez, após resolvido utilizamos pelo container.

---

### Auto wiring

Utilizamos Reflection no container para resolver automaticamente as dependências:

```php
$container->resolve(AdminNoticeHook::class);
```

---

### Service aliases

O container suporta aliases:

```php
$container->alias(
    'repository',
    OptionRepositoryInterface::class
);
```

---

### Service Provider

Registramos as dependências em:

```text
AppServiceProvider
```

Isso mantem o Plugin.php responsável apenas pelo bootstrap.

---

## 🔄 Fluxo da aplicação

```text
Plugin
    ↓
Container
    ↓
AppServiceProvider
    ↓
Services registrados
    ↓
Hooks/Admin/CLI resolvidos automaticamente
```

---

## 🧬 Testes

Cobertura PHPUnit para:

* lazy loading
* singleton reuse
* auto wiring
* aliases
* exceptions para serviços inexistentes

---

### Rodar os testes

```bash
./vendor/bin/phpunit
```

Docker

```bash
docker compose exec wordpress sh -lc 'cd /var/www/html/wp-content/plugins/005-dependency-container-demo && ./vendor/bin/phpunit'
```

---

### 💡 Conceito importante

O conteiner não é usado para remover o 'new', ele é usado para:

* centralizar configuração da aplicação
* desacoplar bootstrap
* controlar ciclo de vida de dependências
* permitir evolução arquitetural
* simplificar resolução de objetos complexos

---

## 📌 Limitações intencionais

Foi utilizado apenas conceito basíco de containers, deixei de fora proporsitalmente:

* scoped services
* contextual binding
* tagged services
* compiled container
* cache de reflection
* atributos/annotations
* autowiring avançado

Se você esta interessado em containers é importante estudar e compreender esses conjuntos.
