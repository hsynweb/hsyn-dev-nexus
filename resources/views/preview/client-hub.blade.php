@extends('layouts.app', ['title' => 'HSYN Nexus | Client Hub', 'bodyClass' => 'dashboard-body'])

@section('content')
    <div class="client-shell">
        <header class="client-topbar reveal">
            <a class="brand-mark" href="{{ route('home') }}">
                <span class="brand-mark__pulse"></span>
                <span>HSYN Nexus</span>
            </a>

            <div class="header-actions">
                <a class="button button-secondary" href="{{ route('home') }}">Landing</a>
                <a class="button button-primary" href="{{ route('control-center') }}">Admin preview</a>
            </div>
        </header>

        <main class="client-main">
            <section class="section-card split-grid reveal">
                <div>
                    <span class="eyebrow">Client preview</span>
                    <h1>Musterinin gordugu alan sakin ama guclu olmali.</h1>
                    <p class="lede">
                        Hizmetlerini, acik borclarini, odeme bildirimlerini ve destek akislarini tek bakista goren
                        sade ama premium bir musteri paneli.
                    </p>
                </div>

                <div class="invoice-hero">
                    <span class="eyebrow">Open balance</span>
                    <strong>TL 14.000</strong>
                    <p>2 fatura acik, 1 odeme bildirimi onay bekliyor.</p>
                </div>
            </section>

            <section class="dashboard-grid">
                <article class="dashboard-panel reveal">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Active services</span>
                            <h2>Hizmetlerin</h2>
                        </div>
                    </div>

                    <ul class="stack-list">
                        @foreach ($services as $service)
                            <li>
                                <div>
                                    <strong>{{ $service['name'] }}</strong>
                                    <span>{{ $service['plan'] }}</span>
                                </div>
                                <strong>{{ $service['status'] }}</strong>
                            </li>
                        @endforeach
                    </ul>
                </article>

                <article class="dashboard-panel reveal">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Billing</span>
                            <h2>Odeme takibi</h2>
                        </div>
                    </div>

                    <ul class="stack-list">
                        @foreach ($billing as $item)
                            <li>
                                <div>
                                    <strong>{{ $item['title'] }}</strong>
                                    <span>{{ $item['status'] }}</span>
                                </div>
                                <strong>{{ $item['amount'] }}</strong>
                            </li>
                        @endforeach
                    </ul>
                </article>

                <article class="dashboard-panel dashboard-panel--wide reveal">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Project lane</span>
                            <h2>Islerin nerede oldugu</h2>
                        </div>
                    </div>

                    <div class="table-stack">
                        @foreach ($projects as $project)
                            <div class="table-row">
                                <div>
                                    <strong>{{ $project['name'] }}</strong>
                                </div>
                                <div>
                                    <strong>{{ $project['progress'] }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="dashboard-panel dashboard-panel--wide reveal">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Support lane</span>
                            <h2>Destek gecmisi</h2>
                        </div>
                    </div>

                    <div class="table-stack">
                        @foreach ($tickets as $ticket)
                            <div class="table-row">
                                <div>
                                    <strong>{{ $ticket['subject'] }}</strong>
                                </div>
                                <div>
                                    <strong>{{ $ticket['status'] }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            </section>
        </main>
    </div>
@endsection
