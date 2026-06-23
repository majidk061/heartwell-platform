# HeartWell Platform — Agent Guide

## Stack

- Laravel 10, Filament 3, MySQL 8, Redis, Tailwind 3, Alpine.js
- Domain modules: `Content`, `CRM`, `Booking`, `Automation`, `Integrations`

## Quick Start

```bash
composer install
npm install && npm run build
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Admin: `/admin` — `admin@heartwellwellness.com` / `password`

## Docker (MySQL + Redis)

```bash
docker compose up -d
# Set DB_HOST=mysql in .env, then migrate --seed
```

## Key Paths

| Path | Purpose |
|------|---------|
| `app/Domains/*/Actions/` | Business logic |
| `resources/css/tokens/` | Design tokens (single source of truth) |
| `config/heartwell.php` | Brand, CTAs, navigation, compliance |
| `config/integrations.php` | Acuity, Mailchimp, SendGrid, Hydreight |
| `.cursor/rules/` | Project coding standards |

## Public Routes

`/`, `/support-pathways`, `/your-experience`, `/why-heartwell`, `/wellness-journey`, `/meet-the-founder`, `/contact`, `/clinical-intake`

## Automation

```bash
php artisan heartwell:process-automation
```

Schedule via cron in production.
