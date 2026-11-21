@extends('pictory.layout')

@section('title', 'Pictory')

@section('content')
    <div class="card">
        <h1>Willkommen bei Pictory</h1>
        <p class="muted">
            Dies ist eine Testseite für den zweiten Service auf einem eigenen Host
            (z.&nbsp;B. <code>pictory.test</code> / <code>pictory.tobijat.at</code>).
            Accounts teilen sich <code>web_user</code> mit dem Fantasy-Fußball.
        </p>

        @if ($user)
            <p>Angemeldet als <strong>{{ $user['user_nickname'] }}</strong>.</p>
            <div class="actions">
                <form method="post" action="{{ route('pictory.logout') }}">
                    @csrf
                    <button type="submit">Abmelden</button>
                </form>
            </div>
        @else
            <p>Du bist nicht angemeldet.</p>
            <div class="actions">
                <a class="btn" href="{{ route('pictory.login') }}">Anmelden</a>
                <a class="btn btn-ghost" href="{{ route('pictory.register') }}">Registrieren</a>
            </div>
        @endif
    </div>
@endsection
