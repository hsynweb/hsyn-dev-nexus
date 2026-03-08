@extends('layouts.app', ['title' => 'HSYN Nexus | Kayit'])

@section('content')
    <div class="auth-shell">
        <div class="section-card auth-card reveal is-visible">
            <span class="eyebrow">Client onboarding</span>
            <h1>Hesap olustur</h1>
            <p class="lede">Musteri paneline erismek ve hizmetlerini yonetmek icin kayit ol.</p>

            <form class="app-form" method="POST" action="{{ route('register.store') }}">
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
                <label>
                    <span>Sifre</span>
                    <input name="password" type="password" required>
                </label>
                <label>
                    <span>Sifre tekrar</span>
                    <input name="password_confirmation" type="password" required>
                </label>
                <button class="button button-primary" type="submit">Kayit ol</button>
                <a class="button button-secondary" href="{{ route('login') }}">Zaten hesabim var</a>
            </form>
        </div>
    </div>
@endsection
