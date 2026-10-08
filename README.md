# FFB - Fantasy Football

Laravel app on top of the existing FFB MySQL database.

## Requirements

- PHP 8.4+
- Composer
- MySQL (existing FFB database)

## Setup

Clone the repository, then:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set the database connection:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

Then:

```bash
php artisan migrate
```

Or run the combined setup script (still configure `.env` DB settings afterward if needed):

```bash
composer setup
```

## Run

```bash
php artisan serve
```

App URL: `http://localhost:8000` (or `APP_URL` from `.env`).

## Tests

```bash
php artisan test
```
