# XML API contract tests

Protect Prototype AJAX `.xml` contracts before larger refactors.

## Setup

1. Local MySQL with the FFB schema/data (defaults match `config.php` / `ffb-conf.php`).
2. PHP 8.1+ with `curl`, `dom`, `pdo_mysql`.
3. Install test tooling (kept separate from app `vendor/`):

```bash
php composer.phar install
```

## Run

```bash
php composer.phar test
# or
php tests/vendor/bin/phpunit
```

Each suite run:
1. Ensures permanent user `ffb_contract_tester` / `testpass123` (kept between runs)
2. Creates a fresh marked game (`game_description = ffb_contract_test_fixture`) with default options (cloned from latest options row), past + future matchrounds, and matches using existing teams/players
3. Points the tester at that game and grants `ffb_admin` for it
4. Tears the whole game world down on shutdown (user stays)

PHPUnit auto-starts `php -S 127.0.0.1:8765 tests/router.php` if that port is free.

## Layout

- `tests/catalog/player-endpoints.md` — JS ↔ URL ↔ expected tags
- `tests/Contract/` — PHPUnit HTTP contract tests
- `tests/Support/FixtureManager.php` — ephemeral game fixtures + teardown
- `tests/router.php` — pretty-URL router for the built-in server
