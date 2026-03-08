@extends('layouts.app', ['title' => 'HSYN Nexus | Client Hub', 'bodyClass' => 'dashboard-body'])

@section('content')
    <div class="client-shell">
        <header class="client-topbar reveal is-visible">
            <a class="brand-mark" href="{{ route('home') }}">
                <span class="brand-mark__pulse"></span>
                <span>HSYN Nexus</span>
            </a>

            <div class="header-actions">
                <a class="button button-secondary" href="{{ route('home') }}">Landing</a>
                @if (auth()->user()->isAdmin())
                    <a class="button button-secondary" href="{{ route('admin.dashboard') }}">Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="button button-primary" type="submit">Cikis</button>
                </form>
            </div>
        </header>

        <main class="client-main">
            <section class="section-card split-grid reveal is-visible">
                <div>
                    <span class="eyebrow">Client portal</span>
                    <h1>Hizmetlerini, faturalarini ve destek akislarini tek bakista gor.</h1>
                    <p class="lede">
                        Burasi musteri paneli. Aktif hizmetler, proje ilerleyisi, acik faturalar ve odeme bildirimleri
                        tek yuzeyde.
                    </p>
                </div>

                <div class="invoice-hero">
                    <span class="eyebrow">Open balance</span>
                    <strong>TL {{ number_format((float) $billing->whereIn('status', ['sent', 'overdue'])->sum('amount'), 2, ',', '.') }}</strong>
                    <p>{{ $billing->where('status', 'paid')->count() }} odendi, {{ $billing->where('status', 'overdue')->count() }} gecikmede.</p>
                </div>
            </section>

            <section class="dashboard-grid">
                <article class="dashboard-panel reveal is-visible">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Active services</span>
                            <h2>Hizmetlerin</h2>
                        </div>
                    </div>

                    <ul class="stack-list">
                        @forelse ($services as $service)
                            <li>
                                <div>
                                    <strong>{{ $service->name }}</strong>
                                    <span>{{ $service->plan ?: $service->category }}</span>
                                </div>
                                <strong>{{ $service->status }}</strong>
                            </li>
                        @empty
                            <li><div><strong>Henuz hizmet yok</strong><span>Admin panelinden eklenebilir.</span></div></li>
                        @endforelse
                    </ul>
                </article>

                <article class="dashboard-panel reveal is-visible">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Billing</span>
                            <h2>Odeme bildirimi gonder</h2>
                        </div>
                    </div>

                    <form class="app-form compact-form" method="POST" action="{{ route('client.payments.store') }}">
                        @csrf
                        <label class="span-2">
                            <span>Fatura</span>
                            <select name="invoice_id" required>
                                @foreach ($billing as $invoice)
                                    <option value="{{ $invoice->id }}">{{ $invoice->invoice_number }} · TL {{ number_format((float) $invoice->amount, 2, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label><span>Tutar</span><input name="amount" type="number" step="0.01" required></label>
                        <label><span>Kanal</span><input name="channel" type="text" value="bank-transfer" required></label>
                        <label class="span-2"><span>Referans</span><input name="reference_code" type="text"></label>
                        <label class="span-2"><span>Not</span><textarea name="payload" rows="3"></textarea></label>
                        <button class="button button-primary" type="submit">Odeme bildir</button>
                    </form>

                    <div class="stack-list">
                        @foreach ($paymentNotifications->take(5) as $notification)
                            <li>
                                <div>
                                    <strong>{{ $notification->reference_code ?: 'Referans yok' }}</strong>
                                    <span>{{ $notification->status }} · {{ $notification->channel }}</span>
                                </div>
                                <strong>TL {{ number_format((float) $notification->amount, 2, ',', '.') }}</strong>
                            </li>
                        @endforeach
                    </div>
                </article>

                <article class="dashboard-panel dashboard-panel--wide reveal is-visible">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Projects</span>
                            <h2>Islerin nerede oldugu</h2>
                        </div>
                    </div>

                    <div class="table-stack">
                        @forelse ($projects as $project)
                            <div class="table-row">
                                <div>
                                    <strong>{{ $project->name }}</strong>
                                    <span>{{ $project->status }} · {{ $project->priority }}</span>
                                </div>
                                <div><strong>%{{ $project->progress }}</strong></div>
                            </div>
                        @empty
                            <div class="table-row"><div><strong>Henuz proje yok</strong></div></div>
                        @endforelse
                    </div>
                </article>

                <article class="dashboard-panel dashboard-panel--wide reveal is-visible">
                    <div class="panel-heading">
                        <div>
                            <span class="eyebrow">Support</span>
                            <h2>Destek talebi olustur</h2>
                        </div>
                    </div>

                    <form class="app-form compact-form" method="POST" action="{{ route('client.tickets.store') }}">
                        @csrf
                        <label>
                            <span>Hizmet</span>
                            <select name="service_id">
                                <option value="">Genel</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label><span>Konu</span><input name="subject" type="text" required></label>
                        <label><span>Oncelik</span><input name="priority" type="text" value="normal" required></label>
                        <label class="span-2"><span>Mesaj</span><textarea name="message" rows="4" required></textarea></label>
                        <button class="button button-primary" type="submit">Ticket ac</button>
                    </form>

                    <div class="table-stack">
                        @foreach ($tickets as $ticket)
                            <div class="table-row">
                                <div>
                                    <strong>{{ $ticket->subject }}</strong>
                                    <span>{{ $ticket->status }} · {{ $ticket->priority }}</span>
                                </div>
                                <div><strong>{{ optional($ticket->created_at)->format('d.m.Y') }}</strong></div>
                            </div>
                        @endforeach
                    </div>
                </article>
            </section>
        </main>
    </div>
@endsection
