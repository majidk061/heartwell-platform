# HeartWell Aesthetics & Wellness — Platform

Nurse-led wellness website and business platform built with **Laravel 10**, **Filament 3**, and **MySQL 8**. Includes public website (7 pages), CMS, CRM, lead capture forms, booking hooks, and automation engine.

**Domain:** [heartwellwellness.com](https://heartwellwellness.com) (production)

---

## Table of Contents

- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Quick Start (Local)](#quick-start-local)
- [Database Setup (MySQL)](#database-setup-mysql)
- [Running the Application](#running-the-application)
- [Admin Panel (Filament)](#admin-panel-filament)
- [Public Website Pages](#public-website-pages)
- [Contact Forms & CRM](#contact-forms--crm)
- [Environment Variables](#environment-variables)
- [Docker Setup](#docker-setup)
- [Frontend Assets & Design System](#frontend-assets--design-system)
- [Integrations](#integrations)
- [Automation & Scheduled Tasks](#automation--scheduled-tasks)
- [Project Structure](#project-structure)
- [Useful Commands](#useful-commands)
- [Troubleshooting](#troubleshooting)
- [Development Notes](#development-notes)

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 10, PHP 8.1+ |
| Admin / CMS | Filament 3 |
| Database | MySQL 8 (utf8mb4) |
| Frontend | Blade, Tailwind CSS 3, Alpine.js, Vite |
| Queue / Cache | Redis (optional; sync/file for local dev) |
| Email (dev) | Mailpit (Docker) or `MAIL_MAILER=log` |
| Integrations | Acuity, Mailchimp, SendGrid, Hydreight |

---

## Requirements

- **PHP** 8.1 or higher (8.2+ recommended for Docker)
- **Composer** 2.x
- **Node.js** 18+ and **npm**
- **MySQL** 8.0
- PHP extensions: `pdo_mysql`, `mbstring`, `xml`, `curl`, `zip`, `gd`, `bcmath`

Optional:

- Docker & Docker Compose (for containerized MySQL, Redis, Mailpit)
- Redis (production queues/cache)

---

## Quick Start (Local)

```bash
# 1. Clone / enter project
cd heartwell-platform

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies and build assets
npm install
npm run build

# 4. Environment file
cp .env.example .env
php artisan key:generate

# 5. Configure database in .env (see below)

# 6. Create database, migrate, and seed
php artisan migrate --seed

# 7. Start the server
php artisan serve
```

Open:

- **Website:** http://127.0.0.1:8000
- **Admin:** http://127.0.0.1:8000/admin

---

## Database Setup (MySQL)

### 1. Create database

```sql
CREATE DATABASE heartwell_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Or via CLI:

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS heartwell_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Configure `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=heartwell_db
DB_USERNAME=root
DB_PASSWORD=your_password
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

### 3. Migrate and seed

```bash
php artisan migrate --seed
```

This creates all tables and seeds:

- 7 CMS pages with placeholder content
- 5 support pathways
- Automation rules
- Admin user (see below)

### Reset database (development only)

```bash
php artisan migrate:fresh --seed
```

---

## Running the Application

### Production build (single terminal)

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Visit http://127.0.0.1:8000

### Development (recommended — two terminals)

**Terminal 1 — Laravel:**

```bash
php artisan serve
```

**Terminal 2 — Vite (live CSS/JS reload):**

```bash
npm run dev
```

### Build assets for production

```bash
npm run build
```

---

## Admin Panel (Filament)

| Item | Value |
|------|-------|
| URL | http://127.0.0.1:8000/admin |
| Email | `admin@heartwellwellness.com` |
| Password | `password` |

> Change the admin password after first login in production.

The admin panel uses a custom HeartWell enterprise theme (`resources/css/filament/admin/theme.css`). After changing admin styles, rebuild assets:

```bash
npm run build
```

### CMS workflow (admin controls website content)

| Admin area | Controls |
|------------|----------|
| **Pages** → edit page → **Page Sections** tab | Hero, intro, journey steps, founder block, images per section |
| **Avatar Cards** | Home page “Which feels like you?” cards (text + image) |
| **Site Settings** | Brand name/tagline, navigation, CTA labels, footer compliance text |
| **Support Pathways / FAQs / Testimonials** | Pathway content, FAQ accordions, quotes + photos |

Upload images via **File Upload** fields (stored in `storage/app/public/cms/`). Run once:

```bash
php artisan storage:link
```

### Demo data (pagination testing)

`DemoDataSeeder` adds ~35 leads, 30 waitlist entries, 25 consultations, 20 group inquiries, 15 FAQs, and 12 testimonials.

```bash
php artisan db:seed --class=DemoDataSeeder
```

For a clean reset: `php artisan migrate:fresh --seed`

### Admin sections

| Group | Manage |
|-------|--------|
| **Website Content** | Pages, Support Pathways, Testimonials, FAQs |
| **Leads & CRM** | Leads (pipeline), Waitlist, Consultations, Group Inquiries |
| **Automation** | Automation Rules, logs |
| **Bookings** | Bookings (when Acuity is connected) |

### What you can edit in admin

- Page titles, hero text, sections, SEO meta
- Support pathway accordions (5 pathways)
- Lead status (New → Contacted → Consultation → Booked → Completed → Follow-up)
- Automation triggers (waitlist, consultation, booking emails)

---

## Public Website Pages

| # | Page | URL | Description |
|---|------|-----|-------------|
| 1 | Home | `/` | Hero, avatar cards, pathways, founder teaser, CTAs |
| 2 | Support Pathways | `/support-pathways` | 5 pathway accordions |
| 3 | Your Experience | `/your-experience` | Client journey overview |
| 4 | Why HeartWell | `/why-heartwell` | Brand differentiators |
| 5 | Wellness Journey | `/wellness-journey` | Educational content |
| 6 | Meet the Founder | `/meet-the-founder` | Jacquie Wilson bio & credentials |
| 7 | Contact / Waitlist | `/contact` | Waitlist, consultation, booking, group inquiry forms |

**Additional:**

| Page | URL |
|------|-----|
| Clinical intake handoff | `/clinical-intake` |
| Robots.txt | `/robots.txt` |
| Sitemap | `/sitemap.xml` (after generation) |

All page copy is stored in **MySQL** and editable via Filament admin (except brand constants in `config/heartwell.php`).

---

## Contact Forms & CRM

Forms on `/contact` submit to Laravel and create CRM records:

| Form | POST route | Creates |
|------|------------|---------|
| Waitlist | `POST /contact/waitlist` | Waitlist entry + Lead |
| Consultation | `POST /contact/consultation` | Consultation request + Lead |
| Group inquiry | `POST /contact/group-inquiry` | Group inquiry + Lead |

**Lead pipeline statuses:**

`NewLead` → `Contacted` → `ConsultationScheduled` → `Booked` → `Completed` → `FollowUp`

View and manage leads in **Admin → Leads & CRM → Leads**.

---

## Environment Variables

### Application

| Variable | Description | Example |
|----------|-------------|---------|
| `APP_NAME` | Application name | `HeartWell` |
| `APP_URL` | Base URL | `http://localhost:8000` |
| `APP_DEBUG` | Debug mode (false in production) | `true` |

### Database

| Variable | Description |
|----------|-------------|
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | Database host |
| `DB_PORT` | `3306` |
| `DB_DATABASE` | Database name |
| `DB_USERNAME` | Database user |
| `DB_PASSWORD` | Database password |

### Mail (local dev)

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=hello@heartwellwellness.com
MAIL_FROM_NAME="${APP_NAME}"
```

With Docker Mailpit:

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
```

Mailpit UI: http://127.0.0.1:8025

### Integrations

| Variable | Service |
|----------|---------|
| `ACUITY_USER_ID` | Acuity Scheduling |
| `ACUITY_API_KEY` | Acuity API |
| `ACUITY_EMBED_URL` | Booking embed iframe URL |
| `ACUITY_WEBHOOK_SECRET` | Webhook verification |
| `MAILCHIMP_API_KEY` | Mailchimp marketing |
| `MAILCHIMP_SERVER_PREFIX` | e.g. `us21` |
| `MAILCHIMP_AUDIENCE_ID` | Audience/list ID |
| `SENDGRID_API_KEY` | Transactional email |
| `SENDGRID_FROM_EMAIL` | Sender email |
| `HYDREIGHT_PORTAL_URL` | Clinical intake portal |
| `HEARTWELL_GA4_MEASUREMENT_ID` | Google Analytics 4 |

Integration config files: `config/integrations.php`, `config/heartwell.php`

---

## Docker Setup

Start MySQL, Redis, and Mailpit:

```bash
docker compose up -d
```

| Service | Port | Purpose |
|---------|------|---------|
| MySQL | `3307` (host) → `3306` | Database |
| Redis | `6379` | Queue/cache |
| Mailpit | `8025` (UI), `1025` (SMTP) | Email testing |

**Docker `.env` example:**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=heartwell
DB_USERNAME=heartwell
DB_PASSWORD=secret
```

Then run:

```bash
php artisan migrate --seed
php artisan serve
```

Build and run full app container (optional):

```bash
docker compose up app
```

---

## Frontend Assets & Design System

Brand colors, typography, and spacing live in one place:

```
resources/css/tokens/
├── _colors.css
├── _typography.css
├── _spacing.css
├── _components.css
└── index.css
```

**Layout utility classes:**

| Class | Usage |
|-------|-------|
| `.hw-container` | Full-width page container (max 72rem) |
| `.hw-container-narrow` | Content/forms column (max 48rem) |
| `.hw-container-form` | Form width (max 36rem) |

After changing tokens or Tailwind:

```bash
npm run build        # production
npm run dev          # development with hot reload
```

---

## Integrations

| Service | Purpose | Status without keys |
|---------|---------|---------------------|
| **Acuity** | Individual appointment booking embed | Placeholder message on Contact page |
| **Mailchimp** | Waitlist / lead list sync | Logs only (stub) |
| **SendGrid** | Transactional emails | Logs only (stub) |
| **Hydreight** | Clinical intake portal (backend only) | Handoff page without redirect URL |

**Webhook endpoint (Acuity):**

```
POST /webhooks/acuity
```

**Clinical intake handoff:**

```
GET /clinical-intake
```

HeartWell remains the client-facing brand; Hydreight is clinical backend only.

---

## Automation & Scheduled Tasks

Process automation rules manually:

```bash
php artisan heartwell:process-automation
```

**Production cron (example):**

```cron
* * * * * cd /path/to/heartwell-platform && php artisan schedule:run >> /dev/null 2>&1
```

Automation rules are managed in **Admin → Automation → Automation Rules**.

Default seeded rules:

- Waitlist join → welcome email + Mailchimp
- Consultation request → acknowledgement email
- Lead booked → booking confirmation

---

## Project Structure

```
heartwell-platform/
├── app/
│   ├── Domains/
│   │   ├── Content/       # Pages, pathways, CMS models & actions
│   │   ├── CRM/           # Leads, waitlist, consultations, pipeline
│   │   ├── Booking/       # Acuity bookings
│   │   ├── Automation/    # Rule engine
│   │   └── Integrations/  # Acuity, Mailchimp, SendGrid, Hydreight
│   ├── Filament/          # Admin panel resources
│   └── Http/Controllers/Web/
├── config/
│   ├── heartwell.php      # Brand, nav, CTAs, compliance copy
│   └── integrations.php   # External service config
├── database/
│   ├── migrations/
│   └── seeders/HeartWellSeeder.php
├── resources/
│   ├── css/tokens/        # Design system (single source of truth)
│   └── views/
│       ├── layouts/
│       ├── pages/
│       └── components/
├── routes/web.php
├── .cursor/rules/         # AI/coding standards
├── AGENTS.md              # Developer quick reference
└── docker-compose.yml
```

**Architecture pattern:**

```
Request → Form Request → Policy → Domain Action → Event → Job → Response
```

Business logic lives in `app/Domains/*/Actions/` — not in controllers or Blade views.

---

## Useful Commands

```bash
# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Database
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:show

# Routes
php artisan route:list

# Code style
./vendor/bin/pint

# Tests
php artisan test

# Queue worker (production)
php artisan queue:work

# Generate sitemap (Spatie)
php artisan sitemap:generate
```

---

## Troubleshooting

### `Address already in use` on port 8000

Another server is running. Either use it or stop it:

```bash
# Find process on port 8000
ss -tlnp | grep 8000

# Or use a different port
php artisan serve --port=8001
```

### Database connection failed

- Verify MySQL is running: `systemctl status mysql`
- Check `.env` credentials match your MySQL user
- Ensure database exists: `SHOW DATABASES LIKE 'heartwell_db';`
- Test: `php artisan db:show`

### `could not find driver` (SQLite)

Install SQLite extension or use MySQL:

```bash
sudo apt install php8.1-sqlite3   # if using SQLite
```

### CSS/JS changes not visible

```bash
npm run build
php artisan view:clear
# Hard refresh browser: Ctrl+Shift+R
```

### Migration error on fresh install

```bash
php artisan config:clear
php artisan migrate:fresh --seed
```

### Admin login not working

Re-seed admin user:

```bash
php artisan db:seed --class=HeartWellSeeder
```

Credentials: `admin@heartwellwellness.com` / `password`

---

## Development Notes

- **PoC project** — corporate `infrastructure/` / `yaml-pipelines` folders not required unless deploying to enterprise Azure pipeline.
- **Placeholder content** — seeded copy is for development; replace via Filament admin or update `HeartWellSeeder.php`.
- **Page completion** — Home, Support Pathways, and Contact are the most complete; other inner pages have basic CMS content and may need richer layouts and client-final copy/images.
- **Security** — set `APP_DEBUG=false` in production; change default admin password; never commit `.env` to git.
- **Cursor rules** — see `.cursor/rules/` for coding standards used by AI assistants on this project.

---

## License

MIT (PoC / internal development)
