# Admin XML contract tests

Protect administration AJAX `.xml` contracts. Player UI contracts were retired with the Laravel platform.

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
4. Logs in via `/platform/public/login` (sets Laravel session + legacy PHPSESSID bridge)
5. Tears the whole game world down on shutdown (user stays)

PHPUnit auto-starts `php -S 127.0.0.1:8765 tests/router.php` if that port is free.

## Layout

- `tests/Contract/AdminContractTest.php` — administration XML contracts
- `tests/Support/FixtureManager.php` — ephemeral game fixtures + teardown
- `tests/Support/XmlApiClient.php` — HTTP client (platform login + admin XML)
- `tests/router.php` — pretty-URL router for the built-in server
