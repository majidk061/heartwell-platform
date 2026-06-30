#!/usr/bin/env bash
# Create GitHub issues for HeartWell creative brief implementation backlog.
# Requires: gh auth login
# Usage: ./scripts/create-implementation-issues.sh
#        DRY_RUN=1 ./scripts/create-implementation-issues.sh

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

DRY_RUN="${DRY_RUN:-0}"

bootstrap_labels() {
    if [[ "$DRY_RUN" == "1" ]]; then
        echo "Would run: ./scripts/gh-bootstrap-labels.sh"
        return 0
    fi
    "$ROOT/scripts/gh-bootstrap-labels.sh"
}

if [[ "$DRY_RUN" != "1" ]] && ! command -v gh >/dev/null 2>&1; then
    echo "Error: GitHub CLI (gh) is not installed."
    echo ""
    echo "Install and authenticate, then re-run:"
    echo "  sudo apt install gh   # or: snap install gh"
    echo "  gh auth login"
    echo "  ./scripts/create-implementation-issues.sh"
    echo ""
    echo "Issue definitions are documented in:"
    echo "  docs/IMPLEMENTATION_ISSUES.md"
    echo "  .github/planning/issues/README.md"
    exit 1
fi

create_issue() {
    local title="$1"
    local labels="$2"
    local body="$3"

    if [[ "$DRY_RUN" == "1" ]]; then
        echo "---"
        echo "gh issue create --title \"$title\" --label \"$labels\""
        echo "$body" | head -5
        echo "..."
        return 0
    fi

    gh issue create --title "$title" --label "$labels" --body "$body"
}

echo "Creating HeartWell implementation issues..."
echo ""

bootstrap_labels
echo ""

# ─── P0 Launch blockers ───────────────────────────────────────────────────────

create_issue \
    "P0: Fix public contact form field mapping (waitlist, consultation, group)" \
    "p0-launch-blocker,bug,ux" \
    "$(cat <<'EOF'
## Summary
Public contact forms submit field names that do not match backend Form Request validation. Submissions may fail silently or with validation errors.

## Brief reference
§4 Website Goals (lead capture), §12 Dual Booking Paths

## Current problem
| Form | UI fields | Backend expects |
|------|-----------|-----------------|
| Waitlist | `name` | `first_name`, `last_name` |
| Consultation | `name` | `first_name`, `last_name` |
| Group inquiry | `email`, `phone`, `event_details` | `host_email`, `host_phone`, `message`, `event_name` |

**Files:** `resources/views/pages/partials/contact-forms.blade.php`, `app/Http/Requests/CRM/*StoreRequest.php`

## Acceptance criteria
- [ ] Waitlist form submits successfully and creates CRM entry
- [ ] Consultation form submits successfully
- [ ] Group inquiry form submits successfully with correct field mapping
- [ ] Feature tests cover all three POST endpoints with public form payloads
- [ ] Optional: split single `name` into first/last or add `prepareForValidation()` normalizer

## Priority
P0 — launch blocker
EOF
)"

create_issue \
    "P0: Import final client copy and replace placeholder imagery" \
    "p0-launch-blocker,content" \
    "$(cat <<'EOF'
## Summary
Brief §19 states client will provide copy, founder images, and primary imagery. PoC uses seeded placeholder text and Unsplash URLs in `HeartWellSeeder`.

## Brief reference
§7 Homepage, §18 Founder, §19 Content & Assets

## Scope
- [ ] Replace all Unsplash placeholder URLs with client-approved assets
- [ ] Import final homepage, pathway, and page copy from Jacquie
- [ ] Update Meet the Founder page with final bio + pronunciation note
- [ ] Upload real testimonials (or hide section until provided)
- [ ] CMS review: publish only client-approved content

## Files / areas
- `database/seeders/HeartWellSeeder.php` (dev seed only — production via admin CMS)
- Admin → Website Content (Pages, Section Library, Support Pathways, Testimonials)

## Acceptance criteria
- [ ] No stock Unsplash images on production site
- [ ] All 7 public pages reflect client-final copy
- [ ] Client sign-off documented

## Priority
P0 — launch blocker (content-dependent)
EOF
)"

create_issue \
    "P0: Production email delivery and APP_URL verification" \
    "p0-launch-blocker,integrations,automation" \
    "$(cat <<'EOF'
## Summary
Transactional email and admin invite links require correct SMTP/SendGrid config and matching `APP_URL`.

## Brief reference
§15 Communication Ownership, §20 Technical Notes

## Scope
- [ ] Configure Email / SMTP admin page (or SendGrid — not both)
- [ ] Set production `APP_URL` to match public domain (`heartwellwellness.com`)
- [ ] Verify test recipient saves and test sends deliver
- [ ] Verify admin invite / password reset signed URLs work on production host
- [ ] Document ops checklist in README or Admin Guide

## Acceptance criteria
- [ ] Waitlist, consultation, booking confirmation emails deliver in production
- [ ] Admin team invite emails contain working reset links
- [ ] No default `MAIL_MAILER=log` in production

## Priority
P0 — launch blocker
EOF
)"

create_issue \
    "P0: Acuity scheduling — embed, webhooks, and HeartWell-branded notifications" \
    "p0-launch-blocker,integrations" \
    "$(cat <<'EOF'
## Summary
Individual wellness visit booking depends on Acuity. PoC has embed + webhook stub; production needs full configuration and HeartWell-branded client communications.

## Brief reference
§12 Dual Booking Paths, §13 Scheduling & Automation, §15 Communication Ownership

## Scope
- [ ] Configure Acuity embed URL in Integrations admin
- [ ] Register production webhook `POST /webhooks/acuity`
- [ ] Verify booking sync creates CRM lead/booking records
- [ ] Configure Acuity account emails to use HeartWell branding (external Acuity settings)
- [ ] HeartWell `booking_confirmation` template fires on booked status

## Acceptance criteria
- [ ] Book tab visible on Contact when Acuity configured
- [ ] Webhook creates/updates booking in admin
- [ ] Client receives HeartWell-branded confirmation (not third-party default)

## Priority
P0 — launch blocker
EOF
)"

create_issue \
    "P0: Hydreight portal URL and clinical-intake handoff QA" \
    "p0-launch-blocker,integrations,compliance" \
    "$(cat <<'EOF'
## Summary
Hydreight is backend clinical infrastructure only. Clients reach it via HeartWell-branded `/clinical-intake` handoff page.

## Brief reference
§16 Hydreight Integration, §17 Compliance

## Scope
- [ ] Set Hydreight portal URL in Admin → Integrations
- [ ] QA handoff page copy (HeartWell brand, no Hydreight name on public site)
- [ ] Verify booking confirmation email links to `/clinical-intake`
- [ ] Test external portal link opens correctly on mobile

## Acceptance criteria
- [ ] `/clinical-intake` renders HeartWell messaging
- [ ] Portal button appears when URL configured; fallback message when not
- [ ] Compliance copy visible in footer and contact/group forms

## Priority
P0 — launch blocker (config + QA)
EOF
)"

# ─── P1 Brief-critical ────────────────────────────────────────────────────────

create_issue \
    "P1: SMS provider and HeartWell-branded text reminders" \
    "p1-brief-critical,integrations,automation" \
    "$(cat <<'EOF'
## Summary
Brief §15 requires appointment and text reminders under HeartWell brand. No SMS integration exists in codebase.

## Brief reference
§13 Scheduling & Automation, §15 Communication Ownership

## Scope
- [ ] Select SMS provider (Twilio or similar)
- [ ] Add integration config + admin settings (encrypted)
- [ ] Send appointment reminder texts via queued jobs
- [ ] HeartWell sender ID / message templates
- [ ] Opt-in/consent capture on forms where required

## Acceptance criteria
- [ ] Reminder SMS sends before appointment (configurable timing)
- [ ] Messages reference HeartWell, not Hydreight or Acuity
- [ ] Admin can test SMS from settings

## Priority
P1 — brief-critical
EOF
)"

create_issue \
    "P1: Pre-visit and post-visit email communication sequences" \
    "p1-brief-critical,automation" \
    "$(cat <<'EOF'
## Summary
Brief §15 lists pre-visit and follow-up communication under HeartWell. Currently only single transactional emails exist.

## Brief reference
§15 Communication Ownership

## Scope
- [ ] Pre-visit sequence: booking confirmation → clinical intake reminder → day-before reminder
- [ ] Post-visit follow-up email (thank you + next steps)
- [ ] Automation rules with delay_minutes support (or scheduled jobs)
- [ ] Email templates in admin for each step

## Acceptance criteria
- [ ] Sequences trigger from booking/lead events
- [ ] All emails use `emails/heartwell` branded layout
- [ ] Runs logged in Automation Logs

## Priority
P1 — brief-critical
EOF
)"

create_issue \
    "P1: Clinical clearance tracking and 6-month renewal workflow" \
    "p1-brief-critical,compliance,integrations" \
    "$(cat <<'EOF'
## Summary
NJ compliance requires clinical intake, screening, and clearance before treatment, renewed every 6 months. Copy exists; operational tracking does not.

## Brief reference
§17 Compliance Requirement

## Scope
- [ ] Lead/booking fields: clearance status, cleared_at, expires_at
- [ ] Admin UI to view clearance status
- [ ] Renewal reminder automation (email/SMS) before expiry
- [ ] Block or warn on booking when clearance expired (policy TBD with client)
- [ ] Future: sync status from Hydreight webhooks

## Acceptance criteria
- [ ] Admin can see clearance status per lead/client
- [ ] Renewal reminders sent at configurable interval
- [ ] Group gathering: per-guest clearance tracked separately

## Priority
P1 — brief-critical (compliance ops)
EOF
)"

create_issue \
    "P1: Group wellness gathering workflow beyond inquiry form" \
    "p1-brief-critical,integrations,ux" \
    "$(cat <<'EOF'
## Summary
Brief §12 requires separate workflows for Single Wellness Visit vs Group Wellness Gathering. Group path is inquiry-only today.

## Brief reference
§12 Dual Booking Paths, §17 Group Wellness Gatherings

## Scope
- [ ] Define group booking flow (Acuity group event type or manual coordinator workflow)
- [ ] Host booking + guest list management in admin
- [ ] Per-guest clinical intake reminders
- [ ] Group-specific email templates and automation rules
- [ ] Fix group form field mapping (see P0 form bug issue)

## Acceptance criteria
- [ ] Host can complete group booking/inquiry end-to-end
- [ ] Each guest receives individual intake requirement communication
- [ ] Admin can track group event + participant clearance

## Priority
P1 — brief-critical
EOF
)"

create_issue \
    "P1: Avatar self-identification on waitlist and consultation forms" \
    "p1-brief-critical,ux" \
    "$(cat <<'EOF'
## Summary
Three Avatar Framework (Depleted / Frustrated / Confidence) is on homepage cards but not captured during lead submission.

## Brief reference
§8 Three Avatar Framework, §4 Website Goals

## Scope
- [ ] Optional avatar selector on waitlist form (“Which feels most like you?”)
- [ ] Support multi-select or “more than one applies” (brief allows overlap)
- [ ] Store `avatar_type` on waitlist/consultation CRM records
- [ ] Pass to automation/Mailchimp segmentation

## Acceptance criteria
- [ ] Visitor can optionally self-identify with avatar bucket(s)
- [ ] CRM lead shows avatar selection
- [ ] Copy reinforces emotional recognition, not rigid categorization

## Priority
P1 — brief-critical
EOF
)"

create_issue \
    "P1: Admin alerts when booking lacks completed clinical intake" \
    "p1-brief-critical,compliance,automation" \
    "$(cat <<'EOF'
## Summary
Compliance requires clinical clearance before treatment. Admin needs visibility when appointments are booked without completed intake.

## Brief reference
§17 Compliance Requirement

## Scope
- [ ] Flag bookings/leads with pending clearance in admin dashboard
- [ ] Email alert to admin when booking created without clearance
- [ ] Optional: client reminder email with clinical-intake link

## Acceptance criteria
- [ ] Admin sees clearance gap on booking/lead records
- [ ] Notification fires on new booking + pending clearance

## Priority
P1 — brief-critical
EOF
)"

# ─── P2 Maturity ──────────────────────────────────────────────────────────────

create_issue \
    "P2: Nurture and drip email campaigns" \
    "p2-maturity,automation" \
    "$(cat <<'EOF'
## Summary
Brief §15 includes nurture emails. Mailchimp subscribe exists on waitlist; no in-app drip sequences.

## Brief reference
§13 Scheduling & Automation, §15 Communication Ownership

## Scope
- [ ] Define nurture tracks (waitlist, post-consultation, lapsed leads)
- [ ] Mailchimp automation OR in-app delayed automation rules
- [ ] HeartWell-branded templates for each touchpoint

## Acceptance criteria
- [ ] Waitlist join triggers nurture sequence
- [ ] Unsubscribe/consent respected
- [ ] All communication under HeartWell brand

## Priority
P2 — post-launch maturity
EOF
)"

create_issue \
    "P2: CRM follow-up automation tied to lead status" \
    "p2-maturity,automation" \
    "$(cat <<'EOF'
## Summary
CRM has `next_follow_up_at` and lead statuses but no automated follow-up communications.

## Brief reference
§13 Scheduling & Automation, §15 Follow-up communication

## Scope
- [ ] Automation rules on lead status changes (Contacted, Follow-up, etc.)
- [ ] Scheduled job for overdue follow-ups
- [ ] Admin notification + optional client email

## Acceptance criteria
- [ ] Status change can trigger templated email
- [ ] Overdue follow-up surfaces in admin widget/filter

## Priority
P2 — maturity
EOF
)"

create_issue \
    "P2: Hydreight API integration and clinical status webhooks" \
    "p2-maturity,integrations,compliance" \
    "$(cat <<'EOF'
## Summary
Hydreight today is URL handoff only. Brief §16 expects backend support for intake, health history, screening, consent, EMR, compliance.

## Brief reference
§16 Hydreight Integration

## Scope
- [ ] `HydreightService` + interface in `app/Domains/Integrations`
- [ ] Webhook endpoint for intake completed / clearance granted / expired
- [ ] Sync clearance status to CRM (no PHI in marketing DB — metadata only)
- [ ] Queued jobs for API calls per architecture rules

## Acceptance criteria
- [ ] Clearance status updates automatically when Hydreight sends webhook
- [ ] No PHI stored in public marketing tables
- [ ] Admin sees sync timestamp and status

## Priority
P2 — depends on Hydreight API availability
EOF
)"

create_issue \
    "P2: Deduplicate event listeners and automation rules" \
    "p2-maturity,automation,bug" \
    "$(cat <<'EOF'
## Summary
PoC sends some emails both from event listeners (e.g. `HandleWaitlistJoined`) AND matching automation rules — risk of duplicate client emails.

## Brief reference
§13 Scheduling & Automation

## Scope
- [ ] Audit all listeners vs seeded automation rules
- [ ] Single source of truth: either listener dispatches rules only, or rules only
- [ ] Document pattern in AGENTS.md

## Acceptance criteria
- [ ] Waitlist join sends exactly one welcome email
- [ ] Consultation request sends exactly one acknowledgement
- [ ] Tests verify no duplicate sends

## Priority
P2 — production hardening
EOF
)"

create_issue \
    "P2: Client post-booking relationship hub (phase 2)" \
    "p2-maturity,ux" \
    "$(cat <<'EOF'
## Summary
Brief §4 positions website as client relationship hub. No client-facing portal exists post-booking.

## Brief reference
§4 Website Goals, §14 Client Ownership Rule

## Scope
- [ ] Define phase 2 client portal scope (appointments, intake status, messages)
- [ ] HeartWell-branded login (not Hydreight-facing)
- [ ] Link to clinical portal only when intake required

## Acceptance criteria
- [ ] Client can view upcoming appointment and intake status
- [ ] HeartWell remains primary brand throughout

## Priority
P2 — phase 2 feature
EOF
)"

create_issue \
    "P2: Seamless Hydreight handoff UX" \
    "p2-maturity,ux,integrations" \
    "$(cat <<'EOF'
## Summary
Clinical portal opens in new tab (`target="_blank"`). Brief §16: clients should never feel they are leaving HeartWell.

## Brief reference
§16 Hydreight Integration

## Scope
- [ ] Evaluate embedded iframe vs same-tab handoff with HeartWell wrapper (security/HIPAA constraints)
- [ ] Pre-handoff interstitial with HeartWell reassurance copy
- [ ] Return path back to HeartWell contact/scheduling
- [ ] Mobile UX review

## Acceptance criteria
- [ ] Client journey feels continuous with HeartWell as primary relationship
- [ ] Legal/compliance review of embed vs redirect approach

## Priority
P2 — UX improvement
EOF
)"

# ─── P3 Polish ────────────────────────────────────────────────────────────────

create_issue \
    "P3: Contact page UX simplification for overwhelmed users" \
    "p3-polish,ux" \
    "$(cat <<'EOF'
## Summary
Brief §5: ideal client is exhausted/overwhelmed — contact page with 4 tabs may feel heavy.

## Brief reference
§5 Critical UX Principle, §11 Navigation & Conversion Paths

## Scope
- [ ] UX review with simplified default path (e.g. guided “How can we help?” first step)
- [ ] Progressive disclosure instead of 4 equal tabs on mobile
- [ ] User testing with target demographic if possible

## Acceptance criteria
- [ ] Primary path obvious within 3 seconds
- [ ] Recognition → Understanding → Confidence → Action journey supported

## Priority
P3 — polish
EOF
)"

create_issue \
    "P3: Design QA pass against creative brief visual rules" \
    "p3-polish,content,ux" \
    "$(cat <<'EOF'
## Summary
Verify site does not read as med spa, IV menu, overly feminine boutique, or overly corporate medical.

## Brief reference
§2 Brand Visual Direction

## Checklist
- [ ] Pure white backgrounds dominant (not cream page backgrounds)
- [ ] Body text charcoal, navy headings sparingly
- [ ] No coral/peach accent drift
- [ ] Approved logo only — no decorative hearts/leaves/waves
- [ ] Custom-built feel vs generic template

## Priority
P3 — design QA before launch
EOF
)"

create_issue \
    "P3: Performance optimization — lazy loading, CDN, Lighthouse" \
    "p3-polish" \
    "$(cat <<'EOF'
## Summary
Brief §20 requires fast load speed. PoC uses external images without systematic optimization.

## Scope
- [ ] Lazy-load CMS images
- [ ] CDN or optimized storage for production assets
- [ ] Lighthouse performance pass (mobile)
- [ ] Font loading optimization (Source Sans 3, Cormorant)

## Acceptance criteria
- [ ] Mobile Lighthouse performance score ≥ 90 (target TBD)
- [ ] LCP under 2.5s on 4G (target TBD)

## Priority
P3 — polish
EOF
)"

create_issue \
    "P3: Production SEO, sitemap, and GA4 analytics" \
    "p3-polish" \
    "$(cat <<'EOF'
## Summary
SEO fields exist in CMS; production analytics and sitemap need verification.

## Brief reference
§20 Technical Notes

## Scope
- [ ] Configure GA4 measurement ID in Site Settings
- [ ] Verify sitemap generation and robots.txt
- [ ] Canonical URLs and OG images per page
- [ ] Google Search Console setup (ops)

## Acceptance criteria
- [ ] Sitemap includes all 7 public pages + clinical-intake if indexed
- [ ] GA4 receiving pageview events in production

## Priority
P3 — polish
EOF
)"

create_issue \
    "P3: Accessibility audit (WCAG)" \
    "p3-polish,ux" \
    "$(cat <<'EOF'
## Summary
Mobile-first site needs WCAG 2.1 AA audit for public pages and forms.

## Scope
- [ ] Keyboard navigation (header, accordions, contact tabs)
- [ ] Form labels and error messages
- [ ] Color contrast on blush/taupe accents
- [ ] Skip link verification (`layouts/app.blade.php`)

## Acceptance criteria
- [ ] No critical a11y violations on core conversion paths
- [ ] Document known exceptions

## Priority
P3 — polish
EOF
)"

create_issue \
    "P3: End-to-end conversion journey QA" \
    "p3-polish,ux" \
    "$(cat <<'EOF'
## Summary
Test all non-linear journeys from brief §11.

## Journeys to test
- [ ] Home → Book
- [ ] Home → Support Pathways → Book
- [ ] Home → Support Pathways → Consultation
- [ ] Home → Meet the Founder → Consultation
- [ ] Home → Waitlist
- [ ] Book → return later → Consultation
- [ ] Group inquiry → admin follow-up

## Acceptance criteria
- [ ] Each path completes without errors
- [ ] CRM records created correctly
- [ ] Emails fire (or queue) as expected

## Priority
P3 — QA before launch
EOF
)"

# ─── Content & brand ──────────────────────────────────────────────────────────

create_issue \
    "Content: Final founder biography and photography" \
    "content,p1-brief-critical" \
    "$(cat <<'EOF'
## Summary
Founder section scaffold exists; final content and imagery from Jacquie pending.

## Brief reference
§18 Founder Section

## Scope
- [ ] Final bio emphasizing BSN, RN, MBA, healthcare background, trust
- [ ] Professional photography upload to CMS
- [ ] Pronunciation note: Jack-Kwa
- [ ] Home founder teaser + Meet the Founder page updated

## Acceptance criteria
- [ ] No placeholder Unsplash on founder sections
- [ ] Client-approved copy live

## Priority
Content — client dependency
EOF
)"

create_issue \
    "Content: Final logo, favicon, and brand asset pack" \
    "content,p0-launch-blocker" \
    "$(cat <<'EOF'
## Summary
Approved HeartWell logo in CMS; verify final production asset pack from client.

## Brief reference
§2 Logo Usage Rules, §19 Content & Assets

## Scope
- [ ] Final logo files (trimmed, white background variants)
- [ ] Favicon
- [ ] Email logo asset
- [ ] OG default image

## Acceptance criteria
- [ ] Only approved logo used site-wide and in emails
- [ ] No decorative heart clipart added

## Priority
Content — client dependency
EOF
)"

create_issue \
    "Content: Secondary audience messaging blocks" \
    "content" \
    "$(cat <<'EOF'
## Summary
Brief §3 lists secondary audiences: recovery clients, wellness gathering hosts, partner communities.

## Scope
- [ ] Dedicated callout or section copy where appropriate
- [ ] Group gathering host messaging on Contact
- [ ] Optional partner/community page section (within 7-page limit — use expandable sections)

## Acceptance criteria
- [ ] Secondary audiences feel addressed without expanding page count

## Priority
Content
EOF
)"

create_issue \
    "Content: Support Pathways final educational copy pass" \
    "content" \
    "$(cat <<'EOF'
## Summary
Five pathways exist with accordion UI. Content is PoC seed — needs client-final educational copy.

## Brief reference
§9 Support Pathways, §10 Educational Bridge

## Scope
- [ ] Recovery & Hydration, Energy & Wellness, Metabolic/Weight, Advanced Cellular, Confidence & Aesthetic
- [ ] Ensure tone is guidance not IV/treatment menu
- [ ] Expand accordion content as needed

## Acceptance criteria
- [ ] Client sign-off on all pathway copy
- [ ] CTAs route to appropriate book/consultation paths

## Priority
Content — client dependency
EOF
)"

create_issue \
    "Content: Real client testimonials in CMS" \
    "content" \
    "$(cat <<'EOF'
## Summary
Testimonials section exists on homepage; needs real client quotes and permissions.

## Brief reference
§7 Homepage, §19 Content & Assets

## Scope
- [ ] Collect testimonials with written consent
- [ ] Add via Admin → Testimonials
- [ ] Hide section if none available at launch

## Acceptance criteria
- [ ] No fabricated placeholder quotes in production

## Priority
Content — client dependency
EOF
)"

# ─── UX & navigation ──────────────────────────────────────────────────────────

create_issue \
    "UX: Global header — persistent Request Consultation access" \
    "ux,p3-polish" \
    "$(cat <<'EOF'
## Summary
Brief §11: consultation should remain accessible throughout the website. Header currently emphasizes Book only.

## Scope
- [ ] Add consultation link to header (desktop + mobile)
- [ ] Or combined “Book / Consult” dropdown
- [ ] Maintain simple, non-overwhelming nav

## Acceptance criteria
- [ ] Consultation reachable from every page in ≤2 clicks

## Priority
P3 — UX
EOF
)"

create_issue \
    "UX: Educational bridge popouts/modals for pathway → booking" \
    "ux,p2-maturity" \
    "$(cat <<'EOF'
## Summary
Brief §10 prefers popouts/modals for educational bridge. Current implementation uses accordions and inline sections.

## Scope
- [ ] Modal or slide-over for deeper pathway education before CTA
- [ ] Alpine.js accessible modal pattern
- [ ] Mobile-friendly

## Acceptance criteria
- [ ] User can learn enough to decide without leaving pathway context
- [ ] CTA to book/consultation inside modal

## Priority
P2/P3 — UX enhancement
EOF
)"

create_issue \
    "UX: Update nav label to Contact / Waitlist" \
    "ux,p3-polish" \
    "$(cat <<'EOF'
## Summary
Brief §6 lists page 7 as "Contact / Waitlist"; navigation currently shows "Contact" only.

## Scope
- [ ] Update `config/heartwell.php` navigation label
- [ ] Or configurable in Site Settings

## Acceptance criteria
- [ ] Nav matches approved brief wording

## Priority
P3 — minor
EOF
)"

create_issue \
    "UX: Multi-avatar recognition (optional multi-select)" \
    "ux,p1-brief-critical" \
    "$(cat <<'EOF'
## Summary
Brief §8: visitors may identify with two or all three avatar buckets. No multi-select UX exists.

## Scope
- [ ] Optional multi-select on avatar cards or forms
- [ ] Copy: “Many women relate to more than one — that’s okay”
- [ ] Store multiple interests/avatars on lead if useful for segmentation

## Acceptance criteria
- [ ] UX supports overlap without forcing single bucket

## Priority
P1 — aligns with avatar framework issue
EOF
)"

# ─── Integrations hardening ───────────────────────────────────────────────────

create_issue \
    "Integrations: Acuity → CRM booking sync hardening" \
    "integrations,p2-maturity" \
    "$(cat <<'EOF'
## Summary
Webhook stub dispatches `BookingSynced` event; production needs robust sync, idempotency, and error handling.

## Scope
- [ ] Validate webhook signature (Acuity secret)
- [ ] Idempotent booking upsert
- [ ] Link booking to lead by email
- [ ] Admin visibility for sync failures

## Acceptance criteria
- [ ] Duplicate webhooks do not duplicate bookings
- [ ] Failed sync logged and alertable

## Priority
P2 — integrations
EOF
)"

create_issue \
    "Integrations: Seed automation rule for group inquiry submitted" \
    "integrations,automation" \
    "$(cat <<'EOF'
## Summary
Group inquiry emails send via listener; no matching seeded automation rule (unlike waitlist/consultation).

## Scope
- [ ] Add `group_inquiry_submitted` rule to seeder
- [ ] Align with deduplication issue (listener vs rules)

## Acceptance criteria
- [ ] Group inquiry automation visible and manageable in admin

## Priority
P2 — consistency
EOF
)"

create_issue \
    "Integrations: HeartWell-branded Acuity confirmation and reminder emails" \
    "integrations,p1-brief-critical" \
    "$(cat <<'EOF'
## Summary
Acuity sends its own emails by default — may violate §15 Communication Ownership unless reconfigured.

## Scope
- [ ] Document Acuity email settings for HeartWell branding
- [ ] Disable Acuity default emails where HeartWell templates replace them
- [ ] Verify reminder timing aligns with HeartWell sequences

## Acceptance criteria
- [ ] Client receives Acuity-scheduled comms under HeartWell brand only

## Priority
P1 — ops + configuration
EOF
)"

create_issue \
    "Integrations: Mailchimp nurture campaign configuration" \
    "integrations,automation,p2-maturity" \
    "$(cat <<'EOF'
## Summary
Waitlist triggers Mailchimp subscribe job. Nurture campaigns live in Mailchimp externally — need HeartWell-branded templates and tags.

## Brief reference
§13, §15

## Scope
- [ ] Configure Mailchimp audience tags (`heartwell`, avatar buckets)
- [ ] Build nurture automations in Mailchimp with HeartWell copy
- [ ] Document in Admin Guide (Integrations vs Automation)

## Acceptance criteria
- [ ] New waitlist member enters correct nurture journey
- [ ] All emails HeartWell-branded

## Priority
P2 — marketing ops
EOF
)"

echo ""
if [[ "$DRY_RUN" == "1" ]]; then
    echo "Dry run complete. Re-run without DRY_RUN=1 after 'gh auth login' to create issues."
else
    echo "Done. View issues: gh issue list --label p0-launch-blocker"
fi
