# FFB - Fantasy Football

Laravel app on top of the FFB MySQL database.

## Requirements

- PHP 8.4+
- Composer
- MySQL / MariaDB

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` with an **empty** MySQL database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

Import the seed dump (schema + data):

```bash
mysql -h 127.0.0.1 -u your_db_user -p your_db_name < database/seed/ffb_seed.sql
```

### Seed logins


| Nickname    | Password   | Notes |
| ----------- | ---------- | ----- |
| `AdminUser` | `password` | Admin |
| `Ronaldo`   | `password` |       |
| `Haaland`   | `password` |       |


## Run

```bash
php artisan serve
```

App URL: `http://localhost:8000` (or `APP_URL` from `.env`).

## Tests

```bash
php artisan test
```

