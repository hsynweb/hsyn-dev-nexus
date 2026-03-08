# HSYN Nexus Product Blueprint

## Vizyon
HSYN Nexus, `hsyn.dev` icin musteri iliskileri, aktif hizmetler, tahsilat takibi, destek akislari ve sunucu gozetimini tek yuzde birlestiren premium bir operasyon panelidir.

## Cekirdek moduller
1. Lead ve iletisim yonetimi
2. Musteri kaydi ve rol yapisi
3. Proje ve aktif hizmet paneli
4. Fatura, borc ve odeme bildirimi takibi
5. Ticket ve mesajlasma sistemi
6. Admin komuta merkezi
7. Sunucu / site / kaynak gozetimi

## Fazlama
### Faz 1
- Laravel cekirdegi
- Veri modeli ve temel rota yapisi
- Landing, admin preview ve musteri portal preview
- Marka dili ve premium arayuz sistemi

### Faz 2
- Kimlik dogrulama
- Admin ve musteri yetkilendirme katmani
- Gercek CRUD akislar: musteri, hizmet, fatura, ticket
- Odeme bildirimi formlari

### Faz 3
- Sunucu agent kurulumu
- Telemetry ingestion endpointleri
- Alarm, saglik skoru, servis durumlari
- Sunucuda bulunan sitelerin ve temel proseslerin goruntulenmesi

### Faz 4
- Oto-iyilestirme ve operasyon otomasyonu
- Script tabanli uzaktan aksiyonlar
- Kural motoru ve playbook yapisi

## Tasarim dili
- Estetik yon: mission control + luxury industrial
- Ana renkler: obsidian, kum beji, bakir, petrol yesili
- Hedef his: teknik, premium, akilda kalici, guclu

## Teknik kararlar
- Framework: Laravel 12
- UI: Blade + Vite + ozel CSS sistemi
- Ilk veri katmani: SQLite ile hizli prototipleme
- Sonraki faz: MySQL/PostgreSQL gecisi kolay olacak sekilde migration-first tasarim
