# cPanel deployment — HeartWell Platform

Use this when deploying from a **prepared local environment** to cPanel hosting.

## 1. Prepare locally (golden database)

After code changes are merged and tests pass:

```bash
composer install
npm install && npm run build
php artisan migrate          # or migrate --seed on fresh local DB
php artisan heartwell:sync-client-copy
php artisan heartwell:deploy-client-images
php artisan storage:link
php artisan test
php artisan heartwell:preflight --strict
```

Verify in the browser:

- `/` — home hero + avatar cards with client photos
- `/support-pathways` — text-only minimal hero (no image)
- `/meet-the-founder` — Jacquie photo visible
- `/privacy` — policy content editable preview matches Site Settings

Optional: set final logo, privacy body, and branding in **Admin → Site Settings** before export.

## 2. Export MySQL

Export the full database (structure + data):

```bash
mysqldump -u YOUR_USER -p YOUR_DATABASE \
  --single-transaction --routines --triggers \
  > heartwell_cpanel_deploy.sql
```

Or use phpMyAdmin → **Export** on local.

**Minimum content tables** (included in full dump):

| Table | Purpose |
|-------|---------|
| `content_section_templates` | Section Library (variants, images, copy) |
| `content_pages` | Public pages |
| `content_page_sections` | Page → template links |
| `content_avatar_cards` | Home avatar images |
| `content_support_pathways` | Pathway copy |
| `content_faqs` | FAQ content |
| `site_settings` | Branding, compliance, privacy, nav, theme |
| `users` | Admin login |

Exclude CRM/booking tables from export only if you want a clean production CRM (`crm_*`, `booking_*`, etc.).

## 3. Files to upload (not in SQL)

Database stores **paths only**. Upload these folders/files:

| Local | Server destination |
|-------|-------------------|
| `storage/app/public/cms/` | `storage/app/public/cms/` |
| `public/build/` | `public/build/` |
| Full Laravel app (except `.env`) | project root |

Zip `storage/app/public/cms/` for cPanel File Manager, then extract on the server.

## 4. cPanel server setup

1. Create MySQL database + user; note credentials.
2. Upload application code (git clone, FTP, or zip).
3. Copy `.env.example` → `.env` and configure:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_KEY=...   # copy from local if encrypted_settings is used

DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

4. Import `heartwell_cpanel_deploy.sql` via phpMyAdmin.
5. SSH / Terminal (project root):

```bash
composer install --no-dev --optimize-autoloader
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan heartwell:preflight --strict
```

6. Point the domain document root to `public/`.

## 5. Post-deploy smoke test

- Public: `/`, `/support-pathways`, `/meet-the-founder`, `/privacy`, `/contact`
- Admin: `/admin` — login with exported admin user
- Check images load (not placeholders)
- Send a test email if SMTP is configured

## 6. Repeat deploys

| Change type | Action |
|-------------|--------|
| Code only | Upload code, `composer install`, cache commands, `npm run build` on build machine |
| Content only | Re-export SQL + `cms/` from local, or edit in production Filament |
| Images only | Run `heartwell:deploy-client-images` locally, re-upload `storage/app/public/cms/` |

Do **not** run `migrate:fresh --seed` on production.

## Related docs

- [CLIENT_CONTENT_IMPORT.md](CLIENT_CONTENT_IMPORT.md) — content checklist
- [QA_CHECKLIST.md](QA_CHECKLIST.md) — launch QA
