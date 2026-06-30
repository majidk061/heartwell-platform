#!/usr/bin/env bash
# HeartWell GitHub issue task helper — plan → issue → execute workflow.
#
# Usage:
#   ./scripts/gh-task.sh next [label]     # Show next open issue (default: p0, then p1…)
#   ./scripts/gh-task.sh list [label]     # List open issues
#   ./scripts/gh-task.sh view <number>    # View issue body
#   ./scripts/gh-task.sh create "Title" "label1,label2" [body-file]
#   ./scripts/gh-task.sh close <number> [--comment "Done: …"]
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

if ! command -v gh >/dev/null 2>&1; then
    echo "Error: gh CLI required. Run: gh auth login"
    exit 1
fi

cmd="${1:-}"
shift || true

case "$cmd" in
    next)
        label="${1:-}"
        if [[ -n "$label" ]]; then
            issue="$(gh issue list --state open --label "$label" --limit 100 --json number,title,url --jq 'sort_by(.number) | .[0]')"
        else
            issue=""
            for priority in p0-launch-blocker p1-brief-critical p2-maturity p3-polish; do
                issue="$(gh issue list --state open --label "$priority" --limit 100 --json number,title,url --jq 'sort_by(.number) | .[0]')"
                if [[ -n "$issue" && "$issue" != "null" ]]; then
                    break
                fi
            done
            if [[ -z "$issue" || "$issue" == "null" ]]; then
                issue="$(gh issue list --state open --limit 100 --json number,title,url --jq 'sort_by(.number) | .[0]')"
            fi
        fi
        if [[ -z "$issue" || "$issue" == "null" ]]; then
            echo "No open issues found."
            exit 0
        fi
        echo "$issue" | jq -r '"Next task: #\(.number) \(.title)\n\(.url)"'
        ;;
    list)
        label="${1:-}"
        if [[ -n "$label" ]]; then
            gh issue list --state open --label "$label" --limit 50
        else
            gh issue list --state open --limit 50
        fi
        ;;
    view)
        num="${1:?Issue number required}"
        gh issue view "$num"
        ;;
    create)
        title="${1:?Title required}"
        labels="${2:?Labels required (comma-separated)}"
        body_file="${3:-}"
        if [[ -n "$body_file" && -f "$body_file" ]]; then
            gh issue create --title "$title" --label "$labels" --body-file "$body_file"
        elif [[ -n "$body_file" && "$body_file" != "-" ]]; then
            gh issue create --title "$title" --label "$labels" --body "$body_file"
        else
            gh issue create --title "$title" --label "$labels" --body "See plan for scope and acceptance criteria."
        fi
        ;;
    close)
        num="${1:?Issue number required}"
        shift || true
        comment=""
        while [[ $# -gt 0 ]]; do
            case "$1" in
                --comment)
                    comment="${2:-}"
                    shift 2
                    ;;
                *)
                    shift
                    ;;
            esac
        done
        if [[ -n "$comment" ]]; then
            gh issue comment "$num" --body "$comment"
        fi
        gh issue close "$num"
        echo "Closed #$num"
        ;;
    *)
        echo "HeartWell GitHub task helper"
        echo ""
        echo "  ./scripts/gh-task.sh next [label]"
        echo "  ./scripts/gh-task.sh list [label]"
        echo "  ./scripts/gh-task.sh view <number>"
        echo "  ./scripts/gh-task.sh create \"Title\" \"labels\" [body-file]"
        echo "  ./scripts/gh-task.sh close <number> [--comment \"…\"]"
        exit 1
        ;;
esac
