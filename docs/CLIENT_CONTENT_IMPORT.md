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
2. Upload images via Admin (CMS image fields → `storage/app/public/cms/`).
3. Edit content in **Section Library** (single source of truth), then verify pages.
4. Preview draft pages at `/admin/preview/page/{slug}` before publish.
5. Run `php artisan heartwell:preflight` before deploy.

## PoC defaults

Fresh `migrate --seed` uses placeholder copy and stock imagery for development only. Production should use CMS content edited after client delivery.

## Sign-off

| Area | Client approved | Date |
|------|-----------------|------|
| All 7 pages | | |
| Founder section | | |
| Imagery / logo | | |
| Testimonials | | |
