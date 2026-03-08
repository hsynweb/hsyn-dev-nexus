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
                <a class="sidebar-link is-active" href="{{ route('admin.dashboard') }}">Overview</a>
                <a class="sidebar-link" href="#customers">Musteriler</a>
                <a class="sidebar-link" href="#billing">Faturalar</a>
                <a class="sidebar-link" href="#servers">Sunucular</a>
            </div>

            <div class="sidebar-block sidebar-block--status">
                <span class="eyebrow">Signed in</span>
                <strong>{{ auth()->user()->name }}</strong>
                <p>{{ auth()->user()->email }}</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="button button-secondary full-width" type="submit">Cikis yap</button>
            </form>
        </aside>

        <main class="dashboard-main">
            <header class="dashboard-header reveal is-visible">
                <div>
                    <span class="eyebrow">Admin operations</span>
                    <h1>Control Center</h1>
                    <p>Musteri, destek, tahsilat ve altyapi akislarini ayni anda yoneten calisan panel.</p>
                </div>

                <div class="header-actions">
                    <a class="button button-secondary" href="{{ route('home') }}">Landing</a>
                </div>
            </header>

            <section class="kpi-grid">
                @foreach ($serverStats as $stat)
                    <article class="metric-panel reveal is-visible">
                        <span>{{ $stat['label'] }}</span>
                        <strong>{{ $stat['value'] }}</strong>
                        <small>{{ $stat['delta'] }}</small>
                    </article>
                @endforeach
            </section>

            <section class="dashboard-grid">
                <article class="dashboard-panel dashboard-panel--wide reveal is-visible">
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

                <article class="dashboard-panel reveal is-visible" id="customers">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Customers</span>
                            <h2>Musteri ekle</h2>
                        </div>
                    </div>

                    <form class="app-form compact-form" method="POST" action="{{ route('admin.customers.store') }}">
                        @csrf
                        <label><span>Ad</span><input name="name" type="text" required></label>
                        <label><span>Sirket</span><input name="company_name" type="text"></label>
                        <label><span>E-posta</span><input name="email" type="email" required></label>
                        <label><span>Telefon</span><input name="phone" type="text"></label>
                        <label class="span-2"><span>Gecici sifre</span><input name="password" type="text" placeholder="Bos birakilirsa Temp123456!"></label>
                        <button class="button button-primary" type="submit">Musteri olustur</button>
                    </form>

                    <div class="table-stack">
                        @foreach ($customers->take(6) as $customer)
                            <div class="table-row">
                                <div>
                                    <strong>{{ $customer->name }}</strong>
                                    <span>{{ $customer->company_name ?: $customer->email }}</span>
                                </div>
                                <div><strong>{{ $customer->email }}</strong></div>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="dashboard-panel reveal is-visible">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Projects</span>
                            <h2>Proje ac</h2>
                        </div>
                    </div>

                    <form class="app-form compact-form" method="POST" action="{{ route('admin.projects.store') }}">
                        @csrf
                        <label>
                            <span>Musteri</span>
                            <select name="customer_id" required>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label><span>Proje adi</span><input name="name" type="text" required></label>
                        <label><span>Durum</span><input name="status" type="text" value="discovery" required></label>
                        <label><span>Oncelik</span><input name="priority" type="text" value="normal" required></label>
                        <label><span>Baslangic</span><input name="starts_on" type="date"></label>
                        <label><span>Bitis</span><input name="due_on" type="date"></label>
                        <label class="span-2"><span>Ozet</span><textarea name="summary" rows="3"></textarea></label>
                        <button class="button button-primary" type="submit">Proje ekle</button>
                    </form>

                    <div class="stack-list">
                        @foreach ($projects->take(5) as $project)
                            <li>
                                <div>
                                    <strong>{{ $project->name }}</strong>
                                    <span>{{ $project->customer?->name }} · {{ $project->status }}</span>
                                </div>
                                <strong>%{{ $project->progress }}</strong>
                            </li>
                        @endforeach
                    </div>
                </article>

                <article class="dashboard-panel dashboard-panel--wide reveal is-visible">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Services</span>
                            <h2>Hizmetler</h2>
                        </div>
                    </div>

                    <form class="app-form compact-form" method="POST" action="{{ route('admin.services.store') }}">
                        @csrf
                        <label>
                            <span>Musteri</span>
                            <select name="customer_id" required>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span>Proje</span>
                            <select name="project_id">
                                <option value="">Bagimsiz</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label><span>Hizmet</span><input name="name" type="text" required></label>
                        <label><span>Kategori</span><input name="category" type="text" value="managed-service" required></label>
                        <label><span>Plan</span><input name="plan" type="text"></label>
                        <label><span>Durum</span><input name="status" type="text" value="active" required></label>
                        <label><span>Dongu</span><input name="billing_cycle" type="text" value="monthly" required></label>
                        <label><span>Tutar</span><input name="monthly_amount" type="number" min="0" step="0.01" value="0"></label>
                        <label class="span-2"><span>Not</span><textarea name="notes" rows="3"></textarea></label>
                        <button class="button button-primary" type="submit">Hizmet ekle</button>
                    </form>

                    <div class="table-stack">
                        @foreach ($services->take(8) as $service)
                            <div class="table-row">
                                <div>
                                    <strong>{{ $service->name }}</strong>
                                    <span>{{ $service->customer?->name }} · {{ $service->plan ?: $service->category }}</span>
                                </div>
                                <div><strong>{{ $service->status }}</strong></div>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="dashboard-panel dashboard-panel--wide reveal is-visible" id="billing">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Billing pressure</span>
                            <h2>Fatura olustur ve guncelle</h2>
                        </div>
                    </div>

                    <form class="app-form compact-form" method="POST" action="{{ route('admin.invoices.store') }}">
                        @csrf
                        <label>
                            <span>Musteri</span>
                            <select name="customer_id" required>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span>Hizmet</span>
                            <select name="service_id">
                                <option value="">Secilmedi</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label><span>Tutar</span><input name="amount" type="number" step="0.01" required></label>
                        <label><span>Durum</span><input name="status" type="text" value="sent" required></label>
                        <label><span>Duzenleme</span><input name="issued_on" type="date" required></label>
                        <label><span>Vade</span><input name="due_on" type="date" required></label>
                        <label class="span-2"><span>Not</span><textarea name="notes" rows="3"></textarea></label>
                        <button class="button button-primary" type="submit">Fatura olustur</button>
                    </form>

                    <div class="table-stack">
                        @foreach ($finance->take(8) as $invoice)
                            <div class="table-row table-row--stack">
                                <div>
                                    <strong>{{ $invoice->invoice_number }}</strong>
                                    <span>{{ $invoice->customer?->name }} · TL {{ number_format((float) $invoice->amount, 2, ',', '.') }}</span>
                                </div>
                                <form class="inline-form" method="POST" action="{{ route('admin.invoices.update', $invoice) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status">
                                        @foreach (['draft', 'sent', 'overdue', 'paid'] as $status)
                                            <option value="{{ $status }}" @selected($invoice->status === $status)>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <input name="paid_amount" type="number" step="0.01" value="{{ $invoice->paid_amount }}">
                                    <button class="button button-secondary" type="submit">Guncelle</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="dashboard-panel reveal is-visible">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Support queue</span>
                            <h2>Ticket ac</h2>
                        </div>
                    </div>

                    <form class="app-form compact-form" method="POST" action="{{ route('admin.tickets.store') }}">
                        @csrf
                        <label>
                            <span>Musteri</span>
                            <select name="customer_id" required>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span>Hizmet</span>
                            <select name="service_id">
                                <option value="">Secilmedi</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label><span>Konu</span><input name="subject" type="text" required></label>
                        <label><span>Departman</span><input name="department" type="text" value="support" required></label>
                        <label><span>Oncelik</span><input name="priority" type="text" value="normal" required></label>
                        <label class="span-2"><span>Mesaj</span><textarea name="message" rows="3" required></textarea></label>
                        <button class="button button-primary" type="submit">Ticket olustur</button>
                    </form>

                    <div class="table-stack">
                        @foreach ($tickets->take(6) as $ticket)
                            <div class="table-row table-row--stack">
                                <div>
                                    <strong>{{ $ticket->subject }}</strong>
                                    <span>{{ $ticket->customer?->name }} · {{ $ticket->priority }}</span>
                                </div>
                                <form class="inline-form" method="POST" action="{{ route('admin.tickets.update', $ticket) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status">
                                        @foreach (['open', 'pending', 'resolved'] as $status)
                                            <option value="{{ $status }}" @selected($ticket->status === $status)>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <button class="button button-secondary" type="submit">Durum</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="dashboard-panel reveal is-visible" id="servers">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Infrastructure</span>
                            <h2>Sunucu ekle</h2>
                        </div>
                    </div>

                    <form class="app-form compact-form" method="POST" action="{{ route('admin.servers.store') }}">
                        @csrf
                        <label><span>Sunucu</span><input name="name" type="text" required></label>
                        <label><span>Provider</span><input name="provider" type="text"></label>
                        <label><span>Region</span><input name="region" type="text"></label>
                        <label><span>IP</span><input name="ip_address" type="text"></label>
                        <label><span>OS</span><input name="os_name" type="text"></label>
                        <label><span>Durum</span><input name="status" type="text" value="active" required></label>
                        <button class="button button-primary" type="submit">Sunucu ekle</button>
                    </form>

                    <div class="table-stack">
                        @foreach ($servers->take(8) as $server)
                            <div class="table-row table-row--stack">
                                <div>
                                    <strong>{{ $server->name }}</strong>
                                    <span>{{ $server->provider }} · CPU {{ $server->cpu_load }}% · RAM {{ $server->ram_usage }}%</span>
                                </div>
                                <form class="inline-form" method="POST" action="{{ route('admin.servers.update', $server) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status">
                                        @foreach (['active', 'maintenance', 'degraded'] as $status)
                                            <option value="{{ $status }}" @selected($server->status === $status)>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <select name="agent_status">
                                        @foreach (['online', 'offline', 'awaiting-install'] as $status)
                                            <option value="{{ $status }}" @selected($server->agent_status === $status)>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <button class="button button-secondary" type="submit">Guncelle</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </article>
            </section>
        </main>
    </div>
@endsection
