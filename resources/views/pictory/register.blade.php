@extends('pictory.layout')

@section('title', 'Registrieren — Pictory')

@section('content')
    <div class="card">
        <h1>Registrieren</h1>
        <nav style="margin-bottom: 1rem;">
            <a href="{{ route('pictory.home') }}">Start</a>
            <a href="{{ route('pictory.login') }}">Anmelden</a>
        </nav>

        @if (! empty($answer))
            <div class="ok">{!! $answer !!}</div>
        @endif

        @if (! empty($errors))
            <div class="errors" role="alert">
                <strong>Fehler:</strong><br>
                {!! implode('<br>', array_map('e', $errors)) !!}
            </div>
        @endif

        @if (empty($answer))
            <form method="post" action="{{ route('pictory.register.store') }}" autocomplete="on">
                @csrf
                <label for="user_nickname">Benutzername</label>
                <input id="user_nickname" name="user_nickname" value="{{ $form['user_nickname'] }}" required minlength="3" maxlength="16">

                <div class="row">
                    <div>
                        <label for="user_password">Passwort</label>
                        <input id="user_password" type="password" name="user_password" required minlength="10" maxlength="32" autocomplete="new-password">
                    </div>
                    <div>
                        <label for="user_password_val">Passwort wiederholen</label>
                        <input id="user_password_val" type="password" name="user_password_val" required minlength="10" maxlength="32" autocomplete="new-password">
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label for="user_email">E-Mail</label>
                        <input id="user_email" type="email" name="user_email" value="{{ $form['user_email'] }}" required>
                    </div>
                    <div>
                        <label for="user_email_val">E-Mail wiederholen</label>
                        <input id="user_email_val" type="email" name="user_email_val" value="{{ $form['user_email_val'] }}" required>
                    </div>
                </div>

                <label style="display:flex; gap:0.5rem; align-items:flex-start; margin-top:1rem;">
                    <input type="checkbox" name="user_tos" value="user_tos_yes" required style="width:auto; margin-top:0.2rem;">
                    <span>Ich akzeptiere die Nutzungsbedingungen.</span>
                </label>

                <div class="actions">
                    <button type="submit">Account anlegen</button>
                </div>
            </form>
        @endif
    </div>
@endsection
