@extends('layouts.app', ['title' => 'HSYN Nexus | Control Center', 'bodyClass' => 'dashboard-body'])

@section('content')
    <div class="dashboard-shell">
        <aside class="dashboard-sidebar">
            <a class="brand-mark" href="{{ route('home') }}">
                <span class="brand-mark__pulse"></span>
                <span>HSYN Nexus</span>
            </a>

            <div class="sidebar-block">
                <span class="eyebrow">Command deck</span>
                <a class="sidebar-link is-active" href="{{ route('control-center') }}">Overview</a>
                <a class="sidebar-link" href="{{ route('client-hub') }}">Client Hub</a>
                <a class="sidebar-link" href="{{ route('home') }}#roadmap">Roadmap</a>
            </div>

            <div class="sidebar-block sidebar-block--status">
                <span class="eyebrow">Live posture</span>
                <strong>Operational with pressure</strong>
                <p>2 servis izlenmeli, 1 disk alarmi kritik seviyede.</p>
            </div>
        </aside>

        <main class="dashboard-main">
            <header class="dashboard-header reveal">
                <div>
                    <span class="eyebrow">Admin preview</span>
                    <h1>Control Center</h1>
                    <p>Musteri, destek, tahsilat ve altyapi akislarini ayni anda okutan yonetici yuzeyi.</p>
                </div>

                <div class="header-actions">
                    <a class="button button-secondary" href="{{ route('home') }}">Landing</a>
                    <a class="button button-primary" href="{{ route('client-hub') }}">Client Hub</a>
                </div>
            </header>

            <section class="kpi-grid">
                @foreach ($serverStats as $stat)
                    <article class="metric-panel reveal">
                        <span>{{ $stat['label'] }}</span>
                        <strong>{{ $stat['value'] }}</strong>
                        <small>{{ $stat['delta'] }}</small>
                    </article>
                @endforeach
            </section>

            <section class="dashboard-grid">
                <article class="dashboard-panel dashboard-panel--wide reveal">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Deployment radar</span>
                            <h2>Sunucular ve canli yuzeyler</h2>
                        </div>
                        <span class="chip">Agent feed</span>
                    </div>

                    <div class="table-stack">
                        @foreach ($deployments as $deployment)
                            <div class="table-row">
                                <div>
                                    <strong>{{ $deployment['name'] }}</strong>
                                    <span>{{ $deployment['host'] }}</span>
                                </div>
                                <div>
                                    <strong>{{ $deployment['status'] }}</strong>
                                    <span>{{ $deployment['note'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="dashboard-panel reveal">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Billing pressure</span>
                            <h2>Tahsilat odagi</h2>
                        </div>
                    </div>

                    <ul class="stack-list">
                        @foreach ($finance as $item)
                            <li>
                                <div>
                                    <strong>{{ $item['client'] }}</strong>
                                    <span>{{ $item['state'] }}</span>
                                </div>
                                <strong>{{ $item['balance'] }}</strong>
                            </li>
                        @endforeach
                    </ul>
                </article>

                <article class="dashboard-panel reveal">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Support queue</span>
                            <h2>Ticket ritmi</h2>
                        </div>
                    </div>

                    <ul class="stack-list">
                        @foreach ($tickets as $ticket)
                            <li>
                                <div>
                                    <strong>{{ $ticket['subject'] }}</strong>
                                    <span>{{ $ticket['owner'] }}</span>
                                </div>
                                <strong>{{ $ticket['priority'] }}</strong>
                            </li>
                        @endforeach
                    </ul>
                </article>

                <article class="dashboard-panel dashboard-panel--wide reveal">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Event stream</span>
                            <h2>Son operasyon hareketleri</h2>
                        </div>
                    </div>

                    <ul class="timeline-list">
                        @foreach ($serverTimeline as $event)
                            <li>
                                <span>{{ $event['time'] }}</span>
                                <p>{{ $event['event'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                </article>
            </section>
        </main>
    </div>
@endsection
