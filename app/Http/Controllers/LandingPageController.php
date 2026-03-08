<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'metrics' => [
                ['value' => '01', 'label' => 'Tek merkezden musteri, proje ve altyapi kontrolu'],
                ['value' => '07', 'label' => 'Ilk fazda hedeflenen ana modul'],
                ['value' => '24/7', 'label' => 'Sunucu, servis ve odeme akislarina gorunurluk'],
            ],
            'modules' => [
                [
                    'eyebrow' => 'Client Hub',
                    'title' => 'Musteri deneyimi tek yuzde toplanir',
                    'body' => 'Kayit, aktif hizmetler, proje durumu, borc bakiyesi, odeme bildirimleri ve ticket gecmisi ayni panelde bulusur.',
                    'accent' => 'amber',
                ],
                [
                    'eyebrow' => 'Admin Deck',
                    'title' => 'Senin icin yonetici komuta merkezi',
                    'body' => 'Yeni talepler, tahsilat riski, destek kuyrugu, onboarding, servis aktivasyonu ve operasyon dagilimi tek ekranda okunur.',
                    'accent' => 'teal',
                ],
                [
                    'eyebrow' => 'Infra Watch',
                    'title' => 'Sunucu ve site gorunurlugu canli hale gelir',
                    'body' => 'Tek dosya veya script ile kurulan agent sayesinde CPU, RAM, disk, servisler, host edilen siteler ve kritik durumlar gorulur.',
                    'accent' => 'copper',
                ],
            ],
            'capabilities' => [
                'Iletisim formlarindan gelen talepleri lead kaydina donusturme',
                'Musteri odakli proje ve hizmet yasam dongusu',
                'Borclu hesaplar, odeme bildirimi ve tahsilat takibi',
                'Ticket sistemi ve mesajlasma akislarinin teklesmesi',
                'Sunucularda siteler, prosesler ve kaynak kullanimlarinin izlenmesi',
                'Ileriki fazlarda otomatik sunucu yonetimi ve aksiyon motoru',
            ],
            'phases' => [
                [
                    'name' => 'Faz 1',
                    'title' => 'Temel platform + premium vitrin',
                    'body' => 'Laravel tabanli cekirdek, rol yapisi, moduller icin veri modeli, dikkat cekici landing ve kontrol merkezi prototipleri.',
                ],
                [
                    'name' => 'Faz 2',
                    'title' => 'Gercek musteri akislarinin acilmasi',
                    'body' => 'Kimlik dogrulama, musteri paneli, admin paneli, ticket akisi, odeme bildirimleri ve hizmet yonetimi.',
                ],
                [
                    'name' => 'Faz 3',
                    'title' => 'Agent + telemetry + otomasyon',
                    'body' => 'Sunucu agent kurulumu, kaynak toplama, site listesi, alarm altyapisi ve yarin icin otomatik yonetim motoru.',
                ],
            ],
            'workflows' => [
                [
                    'label' => 'Lead to Client',
                    'items' => ['Form talebi', 'Lead skoru', 'Teklif / onboarding', 'Aktif musteri'],
                ],
                [
                    'label' => 'Service to Billing',
                    'items' => ['Servis aktivasyonu', 'Kullanim / plan', 'Fatura takibi', 'Odeme bildirimi'],
                ],
                [
                    'label' => 'Server to Action',
                    'items' => ['Agent verisi', 'Durum analizi', 'Alarm olusumu', 'Gelecekte otomatik aksiyon'],
                ],
            ],
        ]);
    }

    public function controlCenter(): View
    {
        return view('preview.control-center', [
            'serverStats' => [
                ['label' => 'Aktif sunucu', 'value' => '12', 'delta' => '+2 yeni node'],
                ['label' => 'Izlenen site', 'value' => '38', 'delta' => '4 riskli domain'],
                ['label' => 'Kritik alarm', 'value' => '03', 'delta' => '1 disk, 2 servis'],
                ['label' => 'Aylik tahsilat', 'value' => 'TL 184K', 'delta' => '%91 toplandi'],
            ],
            'deployments' => [
                ['name' => 'api.hsyn.dev', 'host' => 'fra-core-01', 'status' => 'Canli', 'note' => 'Nginx + PHP-FPM saglikli'],
                ['name' => 'musteri-paneli', 'host' => 'ist-app-02', 'status' => 'Inceleme', 'note' => 'Deploy sonrasi kuyruk gecikmesi var'],
                ['name' => 'ticket-worker', 'host' => 'fra-jobs-01', 'status' => 'Risk', 'note' => 'Supervisor yeniden baslatilmali'],
            ],
            'finance' => [
                ['client' => 'Arctis Studio', 'balance' => 'TL 18.400', 'state' => 'Gecikmis'],
                ['client' => 'Mondeo Dental', 'balance' => 'TL 0', 'state' => 'Guncel'],
                ['client' => 'Northline Hosting', 'balance' => 'TL 7.250', 'state' => 'Bildirim gonderildi'],
            ],
            'tickets' => [
                ['subject' => 'Mail teslim problemi', 'priority' => 'Yuksek', 'owner' => 'Destek ekibi'],
                ['subject' => 'Sunucu tasima talebi', 'priority' => 'Orta', 'owner' => 'Operasyon'],
                ['subject' => 'Yeni e-ticaret kurulum onayi', 'priority' => 'Dusuk', 'owner' => 'Satis'],
            ],
            'serverTimeline' => [
                ['time' => '09:10', 'event' => 'Agent fra-core-01 RAM kullanimi %81 bildirdi'],
                ['time' => '09:18', 'event' => 'Ticket #412 odeme bekliyor durumuna cekildi'],
                ['time' => '09:26', 'event' => 'Yeni musteri lead kaydi otomatik pipelinea akti'],
                ['time' => '09:42', 'event' => 'Nginx servisi ist-app-02 uzerinde yeniden yuklendi'],
            ],
        ]);
    }

    public function clientHub(): View
    {
        return view('preview.client-hub', [
            'services' => [
                ['name' => 'Yonetilen VPS', 'plan' => 'Business Managed', 'status' => 'Aktif'],
                ['name' => 'Bakim + Guvenlik', 'plan' => 'Retainer', 'status' => 'Aktif'],
                ['name' => 'Aylik Gelistirme Havuzu', 'plan' => '24 saat', 'status' => 'Yenileniyor'],
            ],
            'billing' => [
                ['title' => 'Mart 2026 faturasi', 'amount' => 'TL 9.800', 'status' => 'Son 3 gun'],
                ['title' => 'Odeme bildirimi', 'amount' => 'TL 4.200', 'status' => 'Onay bekliyor'],
            ],
            'projects' => [
                ['name' => 'Yeni landing page', 'progress' => 'Tasarim onayi alindi'],
                ['name' => 'CRM entegrasyonu', 'progress' => 'API baglantisi gelisiyor'],
                ['name' => 'Sunucu sertlestirme', 'progress' => 'Test raporu hazirlaniyor'],
            ],
            'tickets' => [
                ['subject' => 'SSL yenileme', 'status' => 'Cozuldu'],
                ['subject' => 'Odeme dekont kontrolu', 'status' => 'Acik'],
                ['subject' => 'Yeni domain yonlendirme', 'status' => 'Cevap bekleniyor'],
            ],
        ]);
    }
}
