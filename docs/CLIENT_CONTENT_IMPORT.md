# Client content import checklist

Use this when replacing PoC placeholder content with Jacquie’s final assets (Creative Brief §19).

## Before go-live

- [ ] **Homepage** — hero headline, body, imagery (Admin → Pages → Home → sections)
- [ ] **Support Pathways** — all five pathway titles, intros, accordion copy
- [ ] **Your Experience / Why HeartWell / Wellness Journey** — final page copy
- [ ] **Meet the Founder** — final bio, BSN/RN/MBA credentials, Jack-Kwa pronunciation, professional photos
- [ ] **Contact** — form titles/subtitles if customized
- [ ] **Testimonials** — real quotes + written consent; enable in Site Settings → Home when ready
- [ ] **Logo & favicon** — upload under Site Settings → Branding
- [ ] **Email templates** — review subjects/bodies under Email Templates
- [ ] **Remove Unsplash URLs** — replace any external placeholder images in Section Library / Avatar Cards

## Import path (recommended)

1. Receive files from client (Google Drive / shared folder).
2. Reference mock: [`docs/client-reference/homepage-mock.png`](client-reference/homepage-mock.png) — full homepage comp for Section Library variants.
3. Upload cropped section images via Admin (CMS image fields → `storage/app/public/cms/`).
4. Edit content in **Section Library** — pick a **Design variant** per template, then use **Preview section** before publishing.
5. Preview draft pages at `/admin/preview/page/{slug}` before publish.
6. Run `php artisan heartwell:preflight` before deploy.

## Section Library variants (PoC)

Fresh `migrate --seed` keeps the **classic** home stack. Client-mock layouts are optional templates in Section Library — pick a **Design variant** when you are ready; nothing is forced on seed.

| Optional template | Design variant | Client mock region |
|-------------------|----------------|-------------------|
| Hero — client split (home) | `split_image_right` | Hero banner |
| (hero settings) | `pathway_bar_variant` | Support Options bar |
| Avatar intro — client horizontal | `horizontal_split_cards` | You're Not Alone cards |
| CTA — client pre-footer band | `centered_band` | Pre-footer CTA |

## PoC defaults

Fresh `migrate --seed` uses placeholder copy and stock imagery for development only. Production should use CMS content edited after client delivery.

## Sign-off

| Area | Client approved | Date |
|------|-----------------|------|
| All 7 pages | | |
| Founder section | | |
| Imagery / logo | | |
| Testimonials | | |
