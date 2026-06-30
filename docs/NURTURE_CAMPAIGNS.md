# Mailchimp nurture campaigns (external configuration)

HeartWell Laravel handles transactional email/SMS via automation rules. **Drip nurture sequences** for marketing are configured in Mailchimp.

## Audience setup

1. Use the audience ID from Admin → Integrations → Mailchimp.
2. Default tags applied on waitlist signup: `heartwell`, `website` (configurable via `MAILCHIMP_DEFAULT_TAGS`).
3. Avatar self-ID adds CRM tags like `avatar:depleted` — map these to Mailchimp segments.

## Recommended journeys

| Segment | Trigger | Suggested content |
|---------|---------|-------------------|
| Waitlist | Tag `heartwell` | 3-part welcome series over 7 days |
| Consultation requested | CRM export / manual tag | Educational pathway content + book CTA |
| Clearance expired | CRM filter on clearance status | Renewal reminder with `/clinical-intake` link |

## Platform-side nurture (already seeded)

These run via `automation_rules` + `heartwell:process-automation`:

- Waitlist welcome (immediate)
- Waitlist nurture day 3 / day 7 (delayed rules)
- Group inquiry follow-up (+24h)
- Lead status follow-up emails

Verify templates in Admin → Email templates before enabling rules in Admin → Automation.
