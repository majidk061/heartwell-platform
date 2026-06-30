# Implementation planning — GitHub issues

These files map the **June 2026 Creative Brief** gap analysis to trackable GitHub issues.

## Create all issues on GitHub

1. Install and authenticate GitHub CLI:

```bash
sudo apt install gh
gh auth login
```

2. Bootstrap labels (automatic when using bulk script):

```bash
./scripts/gh-bootstrap-labels.sh
```

3. From the repo root:

```bash
./scripts/create-implementation-issues.sh
```

## Task-wise execution (after issues exist)

```bash
./scripts/gh-task.sh next              # next P0/P1 open issue
./scripts/gh-task.sh list p0-launch-blocker
./scripts/gh-task.sh close 1 --comment "Done: summary"
```

Optional dry run (prints commands only):

```bash
DRY_RUN=1 ./scripts/create-implementation-issues.sh
```

## Labels used

| Label | Meaning |
|-------|---------|
| `p0-launch-blocker` | Must fix before launch |
| `p1-brief-critical` | Required for brief compliance |
| `p2-maturity` | Post-launch workflow maturity |
| `p3-polish` | QA, performance, polish |
| `bug` | Broken or misaligned behavior |
| `content` | Client copy / assets |
| `integrations` | Acuity, Hydreight, Mailchimp, SMS |
| `automation` | Email/SMS sequences, rules engine |
| `compliance` | NJ clinical clearance, HIPAA handoff |
| `ux` | Client-facing experience |

## Issue index

See [IMPLEMENTATION_ISSUES.md](../../../docs/IMPLEMENTATION_ISSUES.md) for the full checklist with brief section references.
