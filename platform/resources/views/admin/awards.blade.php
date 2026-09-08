@extends('layouts.admin')

@section('title', 'Auszeichnungen')

@section('content')
    @php
        $groups = $data['groups'] ?? [];
        $flashErrors = $errors ?: (session('admin_errors') ?: []);
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-awards-title">
        <div class="section-head">
            <h2 id="admin-awards-title">Auszeichnungen</h2>
        </div>

        @if (!empty($flashErrors))
            <div class="account-flash account-flash-error" role="alert">
                <strong>There are errors:</strong>
                <ul>
                    @foreach ($flashErrors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($answer)
            <div class="account-flash account-flash-ok" role="status">{{ $answer }}</div>
        @endif
    </section>

    <section
        class="panel admin-main admin-awards"
        aria-label="Awards verwalten"
        data-group-url-template="{{ url('/admin/awards/groups') }}/__ID__"
        data-update-group-url="{{ route('admin.awards.updateGroup') }}"
        data-create-define-url="{{ route('admin.awards.createDefine') }}"
        data-update-define-url="{{ route('admin.awards.updateDefine') }}"
        data-finished-url-template="{{ url('/admin/awards/defines') }}/__ID__/finished"
        data-calculate-define-url-template="{{ url('/admin/awards/defines') }}/__ID__/calculate"
        data-calculate-all-url="{{ route('admin.awards.calculateAll') }}"
        data-delete-finished-url-template="{{ url('/admin/awards/finished') }}/__ID__"
        data-csrf="{{ csrf_token() }}"
        data-images-base="{{ $legacyBase }}images/ffb/"
    >
        <div class="admin-awards-grid">
            <div class="admin-awards-left" id="admin-awards-detail">
                <p class="muted">Gruppe auswählen, um Auszeichnungen zu bearbeiten.</p>
                <div id="awardoutput" class="admin-awards-output" aria-live="polite"></div>
            </div>

            <div class="admin-awards-right">
                <p><strong>Bestehende Auszeichnungen verwalten:</strong></p>
                <div class="admin-ms-filter">
                    <label for="awardselect">Gruppe</label>
                    <select id="awardselect" class="admin-awards-select">
                        <option value="">Auswahl:</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group['user_award_id'] }}">{{ $group['user_award_name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <hr class="admin-awards-hr">

                <p><strong>Neue Gruppe für Auszeichnungen anlegen:</strong></p>
                <form class="admin-form" method="post" action="{{ route('admin.awards.createGroup') }}" accept-charset="UTF-8">
                    @csrf
                    <div class="admin-ms-filter">
                        <label for="newgroupawardname">Name</label>
                        <input id="newgroupawardname" type="text" name="newgroupawardname" maxlength="255">
                    </div>
                    <div class="admin-actions admin-actions-flush">
                        <button type="submit" class="admin-submit">anlegen</button>
                    </div>
                </form>

                <hr class="admin-awards-hr">

                <div class="admin-actions admin-actions-flush">
                    <button type="button" class="admin-submit" id="admin-awards-calc-all">alle Auszeichnungen berechnen</button>
                </div>

                <div id="formerror" class="admin-awards-flash" aria-live="polite"></div>
                <div id="formanswer" class="admin-awards-flash" aria-live="polite"></div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ url('js/admin-awards.js') }}?v=1" defer></script>
@endpush
