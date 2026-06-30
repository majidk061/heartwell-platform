# HeartWell — QA & launch checklist

Use before production launch and after major content changes.

## Conversion journey (P3 #23)

- [ ] Home → Support Pathways → pathway CTA → Contact → waitlist submit
- [ ] Home → Contact → consultation submit
- [ ] Contact → Book tab (Acuity embed when configured)
- [ ] Contact → group inquiry submit
- [ ] Post-booking email → `/clinical-intake` → portal handoff
- [ ] `/my-visit` hub shows three steps and intake CTA

## Design QA (P3 #19)

- [ ] White page backgrounds on all public pages
- [ ] Blush / dusty blue / taupe palette only (no off-brand colors)
- [ ] Typography: heading font on titles, body on paragraphs
- [ ] Mobile nav and CTAs meet 44px touch targets
- [ ] Avatar cards and pathway imagery use client assets when available

## SEO & analytics (P3 #21)

- [ ] `php artisan heartwell:sitemap` and verify `/sitemap.xml`
- [ ] `robots.txt` disallows `/admin` and references sitemap
- [ ] GA4 measurement ID in Admin → Site Settings → SEO
- [ ] Each page has unique meta title and description in CMS

## Performance (P3 #20)

- [ ] CMS images use lazy loading
- [ ] `php artisan heartwell:preflight --strict` passes in production
- [ ] Lighthouse mobile score ≥ 80 on Home and Contact

## Accessibility (P3 #22)

- [ ] Skip-to-content link works
- [ ] Form fields have associated labels
- [ ] Accordion buttons expose `aria-expanded`
- [ ] Modal dialogs use `role="dialog"` and focus trap (pathway bridge)
- [ ] Color contrast on blush/dusty-blue buttons

## Automation & integrations

- [ ] Waitlist/consultation emails fire once (automation rules, not duplicated listeners)
- [ ] Acuity webhook creates/updates booking idempotently
- [ ] Hydreight webhook updates clinical clearance when configured
- [ ] Twilio stub logs SMS when credentials absent

## Mailchimp nurture (P2 #36 — external)

Configure in Mailchimp admin (not in Laravel):

- Audience tags: `heartwell`, avatar buckets (`avatar-depleted`, etc.)
- Welcome series for waitlist segment
- Re-engagement for leads with expired clinical clearance (export from CRM)
