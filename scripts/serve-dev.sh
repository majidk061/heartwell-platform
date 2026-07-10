#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

exec php \
  -d upload_max_filesize=12M \
  -d post_max_size=12M \
  "${ROOT}/artisan" serve "$@"
