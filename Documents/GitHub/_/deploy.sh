#!/usr/bin/env bash
set -euo pipefail

LOCAL_DIR="/Users/adancastillo/Documents/GitHub/_/sistema_yustre/"
REMOTE_HOST="access-5019769191.webspace-host.com"
REMOTE_USER="a1829003"
REMOTE_PATH="/sistema_yustre/"
SFTP_PASS="Changludo12345xdHJAWI24021"

echo "[deploy] Sincronizando sistema_yustre → IONOS..."

lftp -u "$REMOTE_USER,$SFTP_PASS" "sftp://$REMOTE_HOST" << EOF
mirror --reverse --delete --verbose \
  --exclude .git/ \
  --exclude .DS_Store \
  --exclude deploy.sh \
  --exclude .deploy.env \
  "$LOCAL_DIR" "$REMOTE_PATH"
bye
EOF

echo "[deploy] Listo."
