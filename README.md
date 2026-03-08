# HSYN Nexus

HSYN Nexus, `hsyn.dev` icin musteri, hizmet, tahsilat, destek ve sunucu gozetimini tek merkezde toplayan Laravel tabanli operasyon platformudur.

## Hazir olan alanlar
- Public landing ve lead toplama formu
- Kayit, giris, cikis akislari
- Rol bazli admin paneli ve musteri paneli
- Musteri, proje, hizmet, fatura, ticket ve sunucu yonetim ekranlari
- Odeme bildirimi akisi
- Agent heartbeat API endpointi
- Demo veri seeding

## Yerel kurulum
```bash
composer install
npm install
php artisan key:generate
New-Item -ItemType File -Path database/database.sqlite -Force
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

## Demo hesaplar
- Admin: `admin@hsyn.dev` / `Admin123456!`
- Musteri: `client@hsyn.dev` / `Client123456!`

## Ana rotalar
- `/`
- `/login`
- `/register`
- `/admin`
- `/portal`

## Agent endpointi
`POST /api/agent/heartbeat`

Kurulum scripti:
```bash
chmod +x ops/install-agent.sh
sudo ./ops/install-agent.sh http://127.0.0.1:8000/api/agent/heartbeat hsyn-agent-dev-token fra-core-01
```

Header:
```text
X-Agent-Token: hsyn-agent-dev-token
```

Ornek payload:
```json
{
  "server": {
    "name": "fra-core-01",
    "provider": "Hetzner",
    "region": "Falkenstein",
    "ip_address": "192.0.2.10",
    "os_name": "Ubuntu 24.04",
    "status": "active",
    "cpu_load": 44.2,
    "ram_usage": 68.1,
    "disk_usage": 57.0
  },
  "sites": [
    {
      "domain": "client-demo.hsyn.dev",
      "framework": "Laravel",
      "php_version": "8.4",
      "ssl_status": "valid",
      "status": "active",
      "deploy_path": "/var/www/client-demo"
    }
  ],
  "metrics": [
    {
      "metric": "php_fpm_workers",
      "unit": "count",
      "value": 12
    }
  ]
}
```
