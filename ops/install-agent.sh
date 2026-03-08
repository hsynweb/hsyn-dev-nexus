#!/usr/bin/env bash

set -euo pipefail

if [[ "${EUID}" -ne 0 ]]; then
    echo "Bu script root olarak calismalidir."
    exit 1
fi

ENDPOINT="${1:-}"
TOKEN="${2:-}"
SERVER_NAME="${3:-$(hostname)}"
PROVIDER="${PROVIDER:-manual}"
REGION="${REGION:-manual}"
INSTALL_DIR="/opt/hsyn-agent"
TIMER_NAME="hsyn-agent.timer"
SERVICE_NAME="hsyn-agent.service"

if [[ -z "${ENDPOINT}" || -z "${TOKEN}" ]]; then
    echo "Kullanim: sudo ./install-agent.sh <endpoint> <token> [server_name]"
    echo "Ornek: sudo ./install-agent.sh https://panel.example.com/api/agent/heartbeat hsyn-agent-dev-token fra-core-01"
    exit 1
fi

mkdir -p "${INSTALL_DIR}"

cat > "${INSTALL_DIR}/collect-and-send.sh" <<EOF
#!/usr/bin/env bash
set -euo pipefail

ENDPOINT="${ENDPOINT}"
TOKEN="${TOKEN}"
SERVER_NAME="${SERVER_NAME}"
PROVIDER="${PROVIDER}"
REGION="${REGION}"

IP_ADDRESS="\$(hostname -I 2>/dev/null | awk '{print \$1}')"
OS_NAME="\$(awk -F= '/^PRETTY_NAME=/{gsub(/"/, "", \$2); print \$2}' /etc/os-release 2>/dev/null || uname -srm)"
CPU_LOAD="\$(awk -v l="\$(cut -d' ' -f1 /proc/loadavg 2>/dev/null || echo 0)" -v c="\$(nproc 2>/dev/null || echo 1)" 'BEGIN { if (c == 0) c = 1; printf "%.2f", (l / c) * 100 }')"
RAM_USAGE="\$(free 2>/dev/null | awk '/Mem:/ {printf "%.2f", (\$3/\$2) * 100}' || echo 0)"
DISK_USAGE="\$(df -P / 2>/dev/null | awk 'NR==2 {gsub("%", "", \$5); print \$5}' || echo 0)"

discover_sites() {
    local roots=("/var/www" "/srv/www" "/home")
    local first=1
    printf '['

    for root in "\${roots[@]}"; do
        [[ -d "\${root}" ]] || continue

        while IFS= read -r -d '' candidate; do
            local site_dir
            site_dir="\$(dirname "\${candidate}")"
            local domain
            domain="\$(basename "\${site_dir}")"
            local framework="static"
            local php_version
            php_version="\$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;' 2>/dev/null || echo unknown)"

            if [[ -f "\${site_dir}/artisan" ]]; then
                framework="Laravel"
            elif [[ -f "\${site_dir}/wp-config.php" ]]; then
                framework="WordPress"
            elif [[ -f "\${site_dir}/composer.json" ]]; then
                framework="PHP App"
            fi

            if [[ \${first} -eq 0 ]]; then
                printf ','
            fi

            printf '{"domain":"%s","framework":"%s","php_version":"%s","ssl_status":"unknown","status":"active","deploy_path":"%s"}' \
                "\${domain}" "\${framework}" "\${php_version}" "\${site_dir}"

            first=0
        done < <(find "\${root}" -maxdepth 3 -type f \\( -name artisan -o -name wp-config.php -o -name composer.json \\) -print0 2>/dev/null)
    done

    printf ']'
}

SITES_JSON="\$(discover_sites)"

PAYLOAD=\$(cat <<JSON
{
  "server": {
    "name": "\${SERVER_NAME}",
    "provider": "\${PROVIDER}",
    "region": "\${REGION}",
    "ip_address": "\${IP_ADDRESS}",
    "os_name": "\${OS_NAME}",
    "status": "active",
    "cpu_load": \${CPU_LOAD},
    "ram_usage": \${RAM_USAGE},
    "disk_usage": \${DISK_USAGE}
  },
  "sites": \${SITES_JSON},
  "metrics": [
    { "metric": "cpu_load", "unit": "%", "value": \${CPU_LOAD} },
    { "metric": "ram_usage", "unit": "%", "value": \${RAM_USAGE} },
    { "metric": "disk_usage", "unit": "%", "value": \${DISK_USAGE} }
  ]
}
JSON
)

curl --fail --silent --show-error \\
    -H "Content-Type: application/json" \\
    -H "X-Agent-Token: \${TOKEN}" \\
    -X POST "\${ENDPOINT}" \\
    -d "\${PAYLOAD}"
EOF

chmod +x "${INSTALL_DIR}/collect-and-send.sh"

cat > "/etc/systemd/system/${SERVICE_NAME}" <<EOF
[Unit]
Description=HSYN Agent heartbeat sender
After=network-online.target
Wants=network-online.target

[Service]
Type=oneshot
ExecStart=${INSTALL_DIR}/collect-and-send.sh
EOF

cat > "/etc/systemd/system/${TIMER_NAME}" <<EOF
[Unit]
Description=Run HSYN Agent every 5 minutes

[Timer]
OnBootSec=2min
OnUnitActiveSec=5min
Unit=${SERVICE_NAME}

[Install]
WantedBy=timers.target
EOF

systemctl daemon-reload
systemctl enable --now "${TIMER_NAME}"
systemctl start "${SERVICE_NAME}"

echo "Kurulum tamamlandi."
echo "Toplayici script: ${INSTALL_DIR}/collect-and-send.sh"
echo "Service: ${SERVICE_NAME}"
echo "Timer: ${TIMER_NAME}"
