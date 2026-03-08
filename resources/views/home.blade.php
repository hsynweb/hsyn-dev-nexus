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
                <a href="#contact">Yeni is</a>
                @auth
                    <a href="{{ route('dashboard.redirect') }}">Panel</a>
                @else
                    <a href="{{ route('login') }}">Giris</a>
                    <a href="{{ route('register') }}">Kayit</a>
                @endauth
            </nav>
        </header>

        <main class="section-stack">
            <section class="hero-grid section-card">
                <div class="masthead reveal">
                    <span class="eyebrow">HSYN DEV OPERATIONS SUITE</span>
                    <h1>Projeler, musteriler, tahsilat ve sunucular tek komuta yuzeyinde.</h1>
                    <p class="lede">
                        HSYN Nexus artik sadece vitrin degil. Public lead topluyor, musteri girisi veriyor, admin
                        operasyonlarini yonetiyor ve agent endpointi ile sunucu heartbeat kabul ediyor.
                    </p>

                    <div class="cta-group">
                        @auth
                            <a class="button button-primary" href="{{ route('dashboard.redirect') }}">Panele git</a>
                        @else
                            <a class="button button-primary" href="{{ route('register') }}">Musteri kayit</a>
                            <a class="button button-secondary" href="{{ route('login') }}">Admin / musteri giris</a>
                        @endauth
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
                    <div class="screen">
                        <div class="screen-header">
                            <span class="chip">Live system</span>
                            <span class="screen-dotset">
                                <i></i><i></i><i></i>
                            </span>
                        </div>

                        <div class="screen-layout">
                            <section class="screen-panel screen-panel--tall">
                                <p class="screen-label">What is live now</p>
                                <div class="radar-grid">
                                    <span>Public lead intake</span>
                                    <span>Client auth</span>
                                    <span>Admin workflows</span>
                                    <span>Agent heartbeat</span>
                                </div>
                            </section>

                            <section class="screen-panel">
                                <p class="screen-label">Core routes</p>
                                <ul class="list-tight">
                                    <li>/admin</li>
                                    <li>/portal</li>
                                    <li>/api/agent/heartbeat</li>
                                </ul>
                            </section>

                            <section class="screen-panel">
                                <p class="screen-label">Demo accounts</p>
                                <ul class="list-tight">
                                    <li>admin@hsyn.dev</li>
                                    <li>client@hsyn.dev</li>
                                </ul>
                            </section>
                        </div>
                    </div>

                    <div class="floating-note floating-note--amber">
                        <span class="eyebrow">Agent install</span>
                        <strong>`X-Agent-Token` ile heartbeat kabul eder</strong>
                    </div>

                    <div class="floating-note floating-note--teal">
                        <span class="eyebrow">Lead flow</span>
                        <strong>Form -> lead -> admin dashboard</strong>
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
                    <span class="eyebrow">Ready to try</span>
                    <h3>Yerelde acildiginda dogrudan denenebilir</h3>
                    <p>Kayit ol, admin olarak gir, musteri olustur, fatura ekle, ticket ac, odeme bildirimi yolla ve agent heartbeat post et.</p>
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
                    <h2>Bu fazdan sonra auth var, veri akisi var, altyapi kapisi var.</h2>
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

            <section id="contact" class="section-card split-grid">
                <div class="reveal">
                    <div class="section-heading">
                        <span class="eyebrow">New business intake</span>
                        <h2>Yeni is talebini buradan dusur.</h2>
                    </div>
                    <p class="lede">Bu form admin panelinde lead olarak gorunur. Boylece public vitrin ile operasyon paneli ayni sistemde birlesir.</p>
                </div>

                <form class="app-form reveal" method="POST" action="{{ route('lead.store') }}">
                    @csrf
                    <label>
                        <span>Ad soyad</span>
                        <input name="name" type="text" value="{{ old('name') }}" required>
                    </label>
                    <label>
                        <span>Sirket</span>
                        <input name="company_name" type="text" value="{{ old('company_name') }}">
                    </label>
                    <label>
                        <span>E-posta</span>
                        <input name="email" type="email" value="{{ old('email') }}" required>
                    </label>
                    <label>
                        <span>Telefon</span>
                        <input name="phone" type="text" value="{{ old('phone') }}">
                    </label>
                    <label class="span-2">
                        <span>Talep detayi</span>
                        <textarea name="message" rows="5" required>{{ old('message') }}</textarea>
                    </label>
                    <button class="button button-primary" type="submit">Lead olustur</button>
                </form>
            </section>
        </main>
    </div>
@endsection
