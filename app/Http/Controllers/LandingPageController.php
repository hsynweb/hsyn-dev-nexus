<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

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

    public function storeLead(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        Lead::create([
            ...$validated,
            'channel' => 'landing-contact',
            'status' => 'new',
            'score' => 'warm',
        ]);

        return redirect()
            ->route('home')
            ->with('status', 'Talebin kaydedildi. Bu lead admin paneline dustu.');
    }

    public function dashboardRedirect(): RedirectResponse
    {
        $user = request()->user();

        return $user && $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('client.dashboard');
    }

    public function controlCenter(): RedirectResponse
    {
        return redirect()->route('admin.dashboard');
    }

    public function clientHub(): RedirectResponse
    {
        return redirect()->route('client.dashboard');
    }
}
