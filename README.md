# Odin V2

Odin V2 is a Laravel 12 + MySQL resource management platform with:

- Authentication (Breeze)
- Roles and permissions (`admin`, `editor`, `viewer`)
- Link sharing with pivot permissions (`read`, `edit`)
- Activity logs (events/listeners)
- Soft delete + restore + force delete (admin only)
- Form Request validation with normalized URL uniqueness per user
- Favorites
- Notifications (database + optional email)
- REST API (`/api/v1`) with Sanctum tokens
- CSV export command and endpoint
- Optional full-text search (MySQL/MariaDB)

## Requirements

- PHP 8.2+
- Composer
- MySQL 8+
- Node.js 18+ (for frontend assets)

## Setup

```bash
cp .env.example .env
composer install
php artisan key:generate
npm install
npm run build
php artisan migrate --seed
php artisan optimize:clear
```

## Default Admin

- Email: `admin@example.com`
- Password: `12345678`

## Run locally

```bash
php artisan serve
php artisan queue:work
```

## API Auth (Sanctum token)

`POST /api/v1/auth/token`

```json
{
  "email": "admin@example.com",
  "password": "12345678",
  "device_name": "postman"
}
```

Use returned bearer token for `/api/v1/*` endpoints.

## Important commands

```bash
php artisan test
php artisan route:list
php artisan links:export --user=1 --from=2026-01-01 --to=2026-12-31 --path=storage/app/exports/links.csv
```

## Linux deployment (Nginx + PHP-FPM + Supervisor)

1. Deploy code to `/var/www/odin-v2`
2. Run:

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force --seed
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Configure Supervisor worker:

```ini
[program:odin-v2-worker]
command=php /var/www/odin-v2/artisan queue:work --sleep=3 --tries=3 --timeout=90
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/supervisor/odin-v2-worker.log
```

4. Scheduler cron:

```cron
* * * * * www-data php /var/www/odin-v2/artisan schedule:run >> /dev/null 2>&1
```

5. Add SSL with Certbot.
