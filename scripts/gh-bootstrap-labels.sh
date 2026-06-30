#!/usr/bin/env bash
# Create standard GitHub labels for HeartWell issue tracking.
set -euo pipefail

if ! command -v gh >/dev/null 2>&1; then
    echo "Error: gh CLI required. Run: gh auth login"
    exit 1
fi

labels=(
    "p0-launch-blocker:Critical launch blocker (P0):d73a4a"
    "p1-brief-critical:Brief-critical workflow (P1):fb8500"
    "p2-maturity:Post-launch maturity (P2):1d76db"
    "p3-polish:Polish and QA (P3):0e8a16"
    "bug:Something is broken:d73a4a"
    "content:Client copy and assets:cfd3d7"
    "integrations:External integrations:5319e7"
    "automation:Email/SMS automation:a2eeef"
    "compliance:Compliance and clinical clearance:fef2c0"
    "ux:Client-facing UX:0075ca"
)

for entry in "${labels[@]}"; do
    name="${entry%%:*}"
    rest="${entry#*:}"
    desc="${rest%%:*}"
    color="${rest##*:}"
    gh label create "$name" --description "$desc" --color "$color" --force >/dev/null
    echo "  label: $name"
done

echo "GitHub labels ready."
