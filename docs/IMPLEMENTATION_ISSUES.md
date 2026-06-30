# HeartWell — Implementation issue backlog

Generated from the June 2026 Creative Brief gap analysis vs current PoC codebase.

**Status key:** ❌ Missing · 🟡 Partial · ⚠️ Broken

---

## P0 — Launch blockers

| # | Title | Brief § | Status |
|---|-------|---------|--------|
| 1 | Fix public contact form field mapping | 4, 12 | ⚠️ |
| 2 | Import final client copy and replace placeholder imagery | 19 | ❌ |
| 3 | Production email delivery and APP_URL verification | 15, 20 | 🟡 |
| 4 | Acuity scheduling — embed, webhooks, branded notifications | 12, 13, 15 | 🟡 |
| 5 | Hydreight portal URL and clinical-intake handoff QA | 16, 17 | 🟡 |

## P1 — Brief-critical workflow

| # | Title | Brief § | Status |
|---|-------|---------|--------|
| 6 | SMS provider and HeartWell-branded text reminders | 13, 15 | ❌ |
| 7 | Pre-visit and post-visit email sequences | 15 | ❌ |
| 8 | Clinical clearance tracking and 6-month renewal workflow | 17 | ❌ |
| 9 | Group wellness gathering workflow beyond inquiry | 12, 17 | 🟡 |
| 10 | Avatar self-identification on lead capture forms | 8, 4 | ❌ |
| 11 | Admin alerts when booking lacks completed clinical intake | 17 | ❌ |

## P2 — Relationship and automation maturity

| # | Title | Brief § | Status |
|---|-------|---------|--------|
| 12 | Nurture / drip email campaigns | 13, 15 | ❌ |
| 13 | CRM follow-up automation tied to lead status | 13, 15 | 🟡 |
| 14 | Hydreight API and clinical status webhooks | 16 | ❌ |
| 15 | Deduplicate event listeners vs automation rules | 13 | ❌ |
| 16 | Client post-booking relationship hub (phase 2) | 4, 14 | ❌ |
| 17 | Seamless Hydreight handoff UX (reduce “leaving HeartWell” feel) | 16 | 🟡 |

## P3 — Polish and QA

| # | Title | Brief § | Status |
|---|-------|---------|--------|
| 18 | Contact page UX simplification | 5, 11 | 🟡 |
| 19 | Design QA against creative brief visual rules | 2 | 🟡 |
| 20 | Performance — lazy loading, CDN, Lighthouse pass | 20 | ❌ |
| 21 | Production SEO, sitemap, and GA4 analytics | 20 | 🟡 |
| 22 | Accessibility audit (WCAG) | 20 | ❌ |
| 23 | End-to-end conversion journey QA | 11 | ❌ |

## Content and brand (client-dependent)

| # | Title | Brief § | Status |
|---|-------|---------|--------|
| 24 | Final founder biography and photography | 18 | ❌ |
| 25 | Final logo, favicon, and brand asset pack | 2, 19 | 🟡 |
| 26 | Secondary audience content (recovery, hosts, partners) | 3 | ❌ |
| 27 | Support Pathways — final educational copy pass | 9, 10 | 🟡 |
| 28 | Real client testimonials in CMS | 7, 19 | ❌ |

## UX and navigation gaps

| # | Title | Brief § | Status |
|---|-------|---------|--------|
| 29 | Global header — persistent Request Consultation access | 11 | 🟡 |
| 30 | Educational bridge popouts/modals (pathway → booking) | 10 | ❌ |
| 31 | Nav label — Contact / Waitlist | 6 | 🟡 |
| 32 | Multi-avatar recognition UX (optional multi-select) | 8 | ❌ |

## Integrations hardening

| # | Title | Brief § | Status |
|---|-------|---------|--------|
| 33 | Acuity → CRM booking sync hardening | 13 | 🟡 |
| 34 | Seed automation rule for group inquiry submitted | 13 | ❌ |
| 35 | HeartWell-branded Acuity confirmation/reminder emails | 15 | ❌ |
| 36 | Mailchimp nurture campaign configuration (external) | 13, 15 | 🟡 |

## Already in good shape (no issue needed)

- 7-page site architecture + CMS admin
- Design tokens (white, blush, dusty blue, taupe)
- Avatar framework (Depleted / Frustrated / Confidence) + 5 Support Pathways
- HeartWell email template system
- CRM, leads, bookings admin
- Compliance copy foundation (footer, group intake notes)
- Hydreight backend-only brand separation pattern
