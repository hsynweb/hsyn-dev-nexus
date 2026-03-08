@extends('layouts.app', ['title' => 'HSYN Nexus | Giris'])

@section('content')
    <div class="auth-shell">
        <div class="section-card auth-card reveal is-visible">
            <span class="eyebrow">Secure entry</span>
            <h1>Giris yap</h1>
            <p class="lede">Admin veya musteri olarak sisteme gir ve rolune gore dogru panele yonlen.</p>

            <form class="app-form" method="POST" action="{{ route('login.store') }}">
                @csrf
                <label class="span-2">
                    <span>E-posta</span>
                    <input name="email" type="email" value="{{ old('email') }}" required>
                </label>
                <label class="span-2">
                    <span>Sifre</span>
                    <input name="password" type="password" required>
                </label>
                <label class="checkbox-row span-2">
                    <input name="remember" type="checkbox" value="1">
                    <span>Beni hatirla</span>
                </label>
                <button class="button button-primary" type="submit">Giris yap</button>
                <a class="button button-secondary" href="{{ route('register') }}">Kayit ol</a>
            </form>
        </div>
    </div>
@endsection
