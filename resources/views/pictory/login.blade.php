@extends('pictory.layout')

@section('title', 'Anmelden — Pictory')

@section('content')
    <div class="card">
        <h1>Anmelden</h1>
        <nav style="margin-bottom: 1rem;">
            <a href="{{ route('pictory.home') }}">Start</a>
            <a href="{{ route('pictory.register') }}">Registrieren</a>
        </nav>

        @if (! empty($message))
            <div class="ok">{!! $message !!}</div>
        @endif

        @if (! empty($errors))
            <div class="errors" role="alert">
                <strong>Fehler:</strong><br>
                {!! implode('<br>', array_map('e', $errors)) !!}
            </div>
        @endif

        <form method="post" action="{{ route('pictory.login.store') }}" autocomplete="on">
            @csrf
            <label for="user_nickname">Benutzername</label>
            <input id="user_nickname" name="user_nickname" value="{{ $nickname }}" required maxlength="16" autocomplete="username">

            <label for="user_password">Passwort</label>
            <input id="user_password" type="password" name="user_password" required maxlength="32" autocomplete="current-password">

            <div class="actions">
                <button type="submit">Einloggen</button>
            </div>
        </form>
    </div>
@endsection
