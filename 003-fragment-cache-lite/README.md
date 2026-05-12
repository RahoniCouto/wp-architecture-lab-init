# 🧪 003 — Fragment Cache Lite

A ideia aqui é mostrar como armazenar trechos pesados e repetitivos de HTML, evitando que o wordpress fique reconstruindo milhares de vezes o mesmo conteúdo para atender as requisições.

---

## 🎯 Objetivo

Criar um sistema simples de entender fragment cache usando:

* PSR-4
* Dependency Injection (DI)
* Interface para abstração
* Uso de transients API
* Shortcode para consumo do fragment.
* Invalidação
* Comando WP_CLI para clear.

---

## 🧱 Estrutura do projeto

```
src/
├── Cli/
│   └── PurgeCacheCommand.php
├── Contracts/
│   ├── FragmentCacheInterface.php
│   └── PostsProviderInterface.php
├── Frontend/
│   └── CachedShortcode.php
├── Hooks/
│   └── SavePostHook.php
├── Infrastructure/
│   ├── FragmentCache.php
│   └── WpPostsProvider.php
├── Services/
│   └── CachedLatestPostsRenderer.php
└── Plugin.php
```

---

## 🔄 Fluxo

```
Shortcode
    ↓
CachedLatestPostsRenderer
    ↓
tenta ler do cache
    ↓
se existir, retorna HTML
    ↓
se não existir, executa WP_Query
    ↓
renderiza HTML
    ↓
salva no cache
```

---

## ⚙️ Funcionalidades implementadas

* Shortcode que lista os últimos posts com o HTML cacheado com fragment.
* Cache de fragmento usando Transients API.
* TTL para expiração.
* Invalidação ao salvar post.
* Comando CLI para limpar manualmente.

---

## 🚀 Shortcode

```text
[fragment_cached_latest_posts]
```

---

## 🚀 WP-CLI

Limpar o cache:

```bash
wp fragment-cache purge
```

---

## 🧩 Responsabilidades da arquitetura

### FragmentCacheInterface

Contrato que define como deve funcionar o sistema de cache.

Métodos:

```php
set()
get()
delete()
```

---

### FragmentCache

Implementado usando o Transients API do WordPress.

Foi desenvolvido de forma simples mas pode e deve evoluir para:

* Redis.
* Object Cache.
* Memcached.
* Cache distribuído.
* Provedor externo.

---

### CachedLatestPostsRenderer

Tenta retornar o HTML cacheado, quando não existe gera o HTML, salva e retorna.

---

### CachedShortcode

Aqui ele apenas retorna o HTML, sem saber nada do funcionamento do cache.

---

### SavePostHook

Quando post já existe, ao ser salvo novamente como publicado faz a invalidação do fragment registrado.

Para isso usamos Lazy Regeneration:

```text
salva post
    ↓
remove cache
    ↓
próxima visita recria o HTML
```

---

### PurgeCacheCommand

Cria o comando CLI para limpar o cache manualmente.

---

## 💡 Diferença entre Snapshot e Fragment Cache

### Snapshot

* Armazena os dados.
* Normalmente usa array.
* Reduz as consultas e processamento de dados.
* É consumido por vários renderizadores.

---

### Fragment Cache

* Armazena o HTML.
* Normalmente usa string.
* Reduz o custo de renderização.
* Fica próximo da camada de apresentação.

---

## 📌 Observações

Foi usado Transients API por simplicidade, em projetos maiores e de alto tráfego precisamos ter um backend robusto com Redis ou Object Cache persistentes. Ainda assim esse formato permite fazer a mudança na implementação sem alterar o renderer e shortcode devido a interface.

---

## 🧬 Testes

Adicionado teste unitario basico usado PHPUnit. Ideia não é validar WordPress mas sim a arquitetura e comportamento das classes.

Para isso foi criado um provider para acesso ao "WP_Query", isso permite criar fakes/stubs para testes sem depender do runtime do WordPress.

### Rodar os testes

```bash
./vendor/bin/phpunit
```

Ou com Docker:

```bash
docker compose exec wordpress sh -lc 'cd /var/www/html/wp-content/plugins/003-fragment-cache-lite && ./vendor/bin/phpunit'
```

### O que é validado

* Ao chamar o cache retorna o HTML diretamente.
* Garante que o provider não é chamado se existir cache.
* Valida que o renderer está isolado do WordPress.

### Estrutura de tests

```
tests/
└── CachedLatestPostsRendererTest.php
```