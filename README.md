# HSYN Nexus

HSYN Nexus, `hsyn.dev` icin tasarlanan yeni nesil musteri, hizmet, tahsilat, destek ve sunucu gozetim platformunun ilk kurulumudur. Bu repo ilk fazda hem urun yonunu hem de arayuz dilini gorunur hale getirir.

## Bu turda hazirlananlar
- Laravel 12 tabanli proje iskeleti
- GitHub reposu: [hsynweb/hsyn-dev-nexus](https://github.com/hsynweb/hsyn-dev-nexus)
- Premium landing sayfasi
- Admin komuta merkezi preview
- Musteri paneli preview
- Cekirdek veri modeli migration'lari
- Ilk roadmap dokumani

## Moduller
- Lead / iletisim talepleri
- Musteri kaydi ve rol sistemi
- Projeler ve aktif hizmetler
- Borc, fatura ve odeme bildirimi
- Ticket sistemi
- Admin operasyon merkezi
- Sunucu, site ve kaynak gozetimi

## Yerel kurulum
```bash
composer install
npm install
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

## Onizleme rotalari
- `/`
- `/control-center`
- `/client-hub`

## Sonraki adim
Bir sonraki gelisim turunda auth, roller, admin CRUD akislari ve agent veri toplama endpointleri eklenecek.
