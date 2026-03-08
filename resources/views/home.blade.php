@extends('layouts.app', ['title' => 'HSYN Nexus | Command Surface'])

@section('content')
    <div class="page-shell">
        <header class="topbar">
            <a class="brand-mark" href="{{ route('home') }}">
                <span class="brand-mark__pulse"></span>
                <span>HSYN Nexus</span>
            </a>

            <nav class="topbar-nav">
                <a href="#modules">Moduller</a>
                <a href="#roadmap">Roadmap</a>
                <a href="{{ route('control-center') }}">Control Center</a>
                <a href="{{ route('client-hub') }}">Client Hub</a>
            </nav>
        </header>

        <main class="section-stack">
            <section class="hero-grid section-card">
                <div class="masthead reveal">
                    <span class="eyebrow">HSYN DEV OPERATIONS SUITE</span>
                    <h1>Projeler, musteriler, tahsilat ve sunucular tek komuta yuzeyinde.</h1>
                    <p class="lede">
                        HSYN Nexus; iletisim formundan gelen talebi, aktif hizmeti, borc bilgisini, ticket kaydini
                        ve sunucu durumunu birbirinden kopuk ekranlardan alip tek ve premium bir operasyon diline
                        cevirir.
                    </p>

                    <div class="cta-group">
                        <a class="button button-primary" href="{{ route('control-center') }}">Admin preview</a>
                        <a class="button button-secondary" href="{{ route('client-hub') }}">Musteri preview</a>
                    </div>

                    <div class="stat-grid">
                        @foreach ($metrics as $metric)
                            <article class="stat-card reveal">
                                <strong>{{ $metric['value'] }}</strong>
                                <span>{{ $metric['label'] }}</span>
                            </article>
                        @endforeach
                    </div>
                </div>

                <div class="hero-visual reveal">
                    <div class="screen screen-glow">
                        <div class="screen-header">
                            <span class="chip">Mission Feed</span>
                            <span class="screen-dotset">
                                <i></i><i></i><i></i>
                            </span>
                        </div>

                        <div class="screen-layout">
                            <section class="screen-panel screen-panel--tall">
                                <p class="screen-label">Ops radar</p>
                                <div class="radar-grid">
                                    <span>12 server</span>
                                    <span>38 site</span>
                                    <span>7 yeni is</span>
                                    <span>3 kritik alarm</span>
                                </div>
                            </section>

                            <section class="screen-panel">
                                <p class="screen-label">Finance pulse</p>
                                <div class="mini-bars">
                                    <i style="height: 48%"></i>
                                    <i style="height: 70%"></i>
                                    <i style="height: 58%"></i>
                                    <i style="height: 92%"></i>
                                    <i style="height: 81%"></i>
                                </div>
                            </section>

                            <section class="screen-panel">
                                <p class="screen-label">Live queue</p>
                                <ul class="list-tight">
                                    <li>Lead skoru yukseliyor</li>
                                    <li>Odeme bildirimi bekliyor</li>
                                    <li>Deploy sonrasi health-check gecti</li>
                                </ul>
                            </section>
                        </div>
                    </div>

                    <div class="floating-note floating-note--amber">
                        <span class="eyebrow">New job intake</span>
                        <strong>Iletisim formu -> lead -> teklif</strong>
                    </div>

                    <div class="floating-note floating-note--teal">
                        <span class="eyebrow">Infra watch</span>
                        <strong>Tek agent ile sunucu ve siteler gorunur</strong>
                    </div>
                </div>
            </section>

            <section id="modules" class="section-card">
                <div class="section-heading reveal">
                    <span class="eyebrow">Modules</span>
                    <h2>Parcalanmis operasyonu sahne tasarimi gibi tek arayuze topluyoruz.</h2>
                </div>

                <div class="module-grid">
                    @foreach ($modules as $module)
                        <article class="module-card reveal accent-{{ $module['accent'] }}">
                            <span class="eyebrow">{{ $module['eyebrow'] }}</span>
                            <h3>{{ $module['title'] }}</h3>
                            <p>{{ $module['body'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="section-card split-grid">
                <div class="reveal">
                    <div class="section-heading">
                        <span class="eyebrow">Core capability map</span>
                        <h2>Ilk gunden sonra nereye buyuyecegimiz net.</h2>
                    </div>

                    <ul class="feature-list">
                        @foreach ($capabilities as $capability)
                            <li>{{ $capability }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="blueprint-card reveal">
                    <span class="eyebrow">Design mood</span>
                    <h3>Mission control + luxury industrial</h3>
                    <p>
                        Obsidian zemin, kum beji kontrastlar, bakir parlama ve petrol yesili vurgularla klasik hosting
                        paneli gorunumunden cikiyoruz.
                    </p>
                    <div class="palette-row">
                        <span class="palette-swatch swatch-obsidian"></span>
                        <span class="palette-swatch swatch-sand"></span>
                        <span class="palette-swatch swatch-copper"></span>
                        <span class="palette-swatch swatch-teal"></span>
                    </div>
                </div>
            </section>

            <section id="roadmap" class="section-card">
                <div class="section-heading reveal">
                    <span class="eyebrow">Roadmap</span>
                    <h2>Proje ilk gunden sonra hangi sirayla acilacak, burada sabit.</h2>
                </div>

                <div class="phase-grid">
                    @foreach ($phases as $phase)
                        <article class="phase-card reveal">
                            <span class="phase-name">{{ $phase['name'] }}</span>
                            <h3>{{ $phase['title'] }}</h3>
                            <p>{{ $phase['body'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="section-card">
                <div class="section-heading reveal">
                    <span class="eyebrow">Workflows</span>
                    <h2>Sistemin kalbi olan uc ana akis.</h2>
                </div>

                <div class="workflow-grid">
                    @foreach ($workflows as $workflow)
                        <article class="workflow-card reveal">
                            <h3>{{ $workflow['label'] }}</h3>
                            <ol>
                                @foreach ($workflow['items'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ol>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="section-card final-cta reveal">
                <div>
                    <span class="eyebrow">Next move</span>
                    <h2>Temel atildi. Bir sonraki turda auth, roller, CRUD ve agent endpointleri acilir.</h2>
                </div>

                <div class="cta-group">
                    <a class="button button-primary" href="{{ route('control-center') }}">Admin deneyimi</a>
                    <a class="button button-secondary" href="{{ route('client-hub') }}">Musteri deneyimi</a>
                </div>
            </section>
        </main>
    </div>
@endsection
