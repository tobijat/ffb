@extends('layouts.admin')

@section('title', 'DB Cleanup')

@section('content')
    @php
        $sections = is_array($data['sections'] ?? null) ? $data['sections'] : [];
        $runUrl = (string) ($data['run_url'] ?? route('admin.dbCleanup.run'));
        $okSrc = $legacyBase.'images/ffb/symbols/ok.png';
        $failSrc = $legacyBase.'images/ffb/symbols/delete.png';
        $idleSrc = $legacyBase.'images/ffb/symbols/info.png';
    @endphp

    <section class="panel admin-main" aria-labelledby="admin-db-cleanup-title">
        <div class="section-head">
            <h2 id="admin-db-cleanup-title">DB Cleanup</h2>
        </div>
        <p class="hint">Werkzeuge zur Prüfung und Bereinigung der Datenbank. Aufgaben starten einzeln und laufen ohne Seiten-Reload.</p>
    </section>

    <div
        id="admin-db-cleanup"
        data-run-url="{{ $runUrl }}"
        data-csrf="{{ csrf_token() }}"
        data-ok-src="{{ $okSrc }}"
        data-fail-src="{{ $failSrc }}"
        data-idle-src="{{ $idleSrc }}"
    >
        @foreach ($sections as $section)
            <section class="panel admin-main admin-dashboard-section" data-task="{{ $section['key'] }}">
                <details class="admin-dashboard-details">
                    <summary class="admin-dashboard-summary">
                        <img
                            class="admin-dashboard-status"
                            src="{{ $idleSrc }}"
                            alt="noch nicht geprüft"
                            title="noch nicht geprüft"
                            width="16"
                            height="16"
                            loading="lazy"
                            data-status-icon
                        >
                        <h2 class="admin-dashboard-title">{{ $section['title'] }}</h2>
                    </summary>
                    <div class="admin-dashboard-body admin-db-cleanup-body">
                        <p class="hint">{{ $section['hint'] }}</p>
                        <div class="admin-db-cleanup-actions">
                            <button type="button" class="admin-submit" data-run-task="{{ $section['key'] }}">Start</button>
                            <span class="muted admin-db-cleanup-status" data-run-status hidden></span>
                        </div>
                        <div class="admin-db-cleanup-result" data-run-result hidden></div>
                    </div>
                </details>
            </section>
        @endforeach
    </div>
@endsection

@push('scripts')
<script>
(function () {
    const root = document.getElementById('admin-db-cleanup');
    if (!root) return;

    const runUrl = root.getAttribute('data-run-url') || '';
    const csrf = root.getAttribute('data-csrf') || '';
    const okSrc = root.getAttribute('data-ok-src') || '';
    const failSrc = root.getAttribute('data-fail-src') || '';
    const idleSrc = root.getAttribute('data-idle-src') || '';

    function setStatus(section, text, isError) {
        const el = section.querySelector('[data-run-status]');
        if (!el) return;
        el.hidden = !text;
        el.textContent = text || '';
        el.classList.toggle('is-error', !!isError);
    }

    function setIcon(section, state) {
        const img = section.querySelector('[data-status-icon]');
        if (!img) return;
        if (state === 'ok') {
            img.src = okSrc;
            img.alt = 'ok';
            img.title = 'ok';
        } else if (state === 'fail') {
            img.src = failSrc;
            img.alt = 'offen';
            img.title = 'offen';
        } else {
            img.src = idleSrc;
            img.alt = 'noch nicht geprüft';
            img.title = 'noch nicht geprüft';
        }
    }

    async function runTask(section, task) {
        const button = section.querySelector('[data-run-task]');
        const resultBox = section.querySelector('[data-run-result]');
        if (!button || !resultBox || !runUrl) return;

        button.disabled = true;
        setStatus(section, 'Läuft …', false);
        resultBox.hidden = true;
        resultBox.innerHTML = '';

        try {
            const response = await fetch(runUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ task: task }),
            });
            const payload = await response.json().catch(function () { return null; });
            if (!response.ok || !payload || !payload.ok) {
                const message = (payload && payload.error) ? String(payload.error) : 'Aufgabe fehlgeschlagen.';
                setStatus(section, message, true);
                setIcon(section, 'fail');
                return;
            }

            setStatus(section, String(payload.summary || ''), false);
            setIcon(section, payload.clean ? 'ok' : 'fail');
            resultBox.innerHTML = String(payload.html || '');
            resultBox.hidden = false;

            const details = section.querySelector('details');
            if (details && !details.open) {
                details.open = true;
            }
        } catch (err) {
            setStatus(section, 'Netzwerkfehler beim Starten der Aufgabe.', true);
            setIcon(section, 'fail');
        } finally {
            button.disabled = false;
        }
    }

    root.addEventListener('click', function (event) {
        const button = event.target.closest('[data-run-task]');
        if (!button || !root.contains(button)) return;
        const section = button.closest('[data-task]');
        if (!section) return;
        event.preventDefault();
        runTask(section, button.getAttribute('data-run-task') || '');
    });
})();
</script>
@endpush
