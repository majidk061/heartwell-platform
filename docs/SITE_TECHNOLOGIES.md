# HeartWell Website — Technologies & Integrations Inventory

**Purpose:** Support privacy-policy review by documenting site-side technologies currently installed, configured, or embedded in the HeartWell platform codebase.

**Last reviewed:** 2026-06-29 (PoC codebase audit)

---

## Analytics & measurement

| Technology | Status | Notes |
|------------|--------|-------|
| Google Analytics 4 (GA4) | **Conditional** | Loaded in `resources/views/layouts/app.blade.php` when `HEARTWELL_GA4_MEASUREMENT_ID` is set in environment / Site Settings |
| Google Tag Manager | Not installed | — |
| Google Ads conversion tracking | Not installed | — |
| Meta Pixel | Not installed | — |
| Microsoft Clarity | Not installed | — |
| Hotjar | Not installed | — |
| Session recording / heatmap tools | Not installed | — |
| Advertising / remarketing pixels | Not installed | — |

---

## Scheduling & booking

| Technology | Status | Notes |
|------------|--------|-------|
| Acuity Scheduling | **Optional** | Embed iframe on Contact page when `ACUITY_EMBED_URL` configured; webhook at `POST /webhooks/acuity` |
| Embedded calendars (other) | Not installed | — |

---

## Forms, CRM & email

| Technology | Status | Notes |
|------------|--------|-------|
| HeartWell public forms | **Active** | Waitlist, Private Wellness Conversation, group gathering inquiry — Laravel POST handlers |
| Mailchimp | **Optional** | Automation channel for waitlist subscribe when enabled in Integrations |
| SendGrid | **Optional** | Transactional templated email when enabled |
| reCAPTCHA | Not installed | Honeypot + CSRF used on public forms |
| Third-party form processors | Not used | Forms submit to HeartWell application |

---

## Clinical workflow (separate from marketing site)

| Technology | Status | Notes |
|------------|--------|-------|
| Hydreight clinical workflow | **Referenced only** | Public `/clinical-intake` handoff page; required intake is **not** collected on general marketing forms |
| Acuity | Scheduling only | Does not replace Hydreight clinical intake |

---

## Framework, cookies & sessions

| Technology | Status | Notes |
|------------|--------|-------|
| Laravel session cookies | **Active** | Standard framework session for admin and public browsing |
| CSRF tokens | **Active** | Laravel CSRF on POST routes; webhooks excluded (`webhooks/*`) |
| Cookie consent banner | Not installed | — |

---

## Social, chat, payments & media

| Technology | Status | Notes |
|------------|--------|-------|
| Embedded social-media widgets | Not installed | Footer may link to Instagram/Facebook URLs from Site Settings |
| Chat widgets | Not installed | — |
| Payment tools | Not installed | — |
| Embedded video players | Not installed | — |

---

## Content & admin

| Technology | Status | Notes |
|------------|--------|-------|
| Filament admin (`/admin`) | **Active** | Staff CMS; not public-facing |
| Redis | **Optional** | Queue/cache per deployment configuration |
| MySQL | **Active** | Application database |

---

## Items flagged for client follow-up

1. **Recovery & Hydration pathway image** — replacement image proposed per creative direction; **not published** pending client approval.
2. **Production GA4** — confirm whether `HEARTWELL_GA4_MEASUREMENT_ID` is set in production `.env`.
3. **Mailchimp / SendGrid / Acuity** — confirm which integrations are enabled in production Integrations settings.

---

## Not in scope of this codebase

- Hydreight-hosted clinical privacy notices and EMR/charting
- Hosting provider (cPanel/server) logs and infrastructure monitoring
- Email delivery outside configured Mailchimp/SendGrid/Laravel mail

Additional privacy language may be added after legal review of this inventory and production configuration.
