<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Pictory')</title>
    <style>
        :root {
            --bg: #f4f1ea;
            --ink: #1a1a1a;
            --accent: #2f5d50;
            --muted: #5c5c5c;
            --card: #fffdf8;
            --line: #d9d2c5;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", system-ui, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(47, 93, 80, 0.12), transparent 40%),
                linear-gradient(180deg, #efe8dc, var(--bg));
            min-height: 100vh;
        }
        .wrap { max-width: 40rem; margin: 0 auto; padding: 2rem 1.25rem 3rem; }
        header { margin-bottom: 2rem; }
        .brand { font-size: 1.75rem; font-weight: 700; letter-spacing: 0.02em; color: var(--accent); }
        .tag { color: var(--muted); margin: 0.35rem 0 0; }
        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 0.75rem;
            padding: 1.25rem 1.35rem;
        }
        h1 { font-size: 1.35rem; margin: 0 0 0.75rem; }
        p { line-height: 1.5; }
        .muted { color: var(--muted); }
        label { display: block; margin: 0.75rem 0 0.25rem; font-size: 0.9rem; }
        input, select {
            width: 100%;
            padding: 0.55rem 0.65rem;
            border: 1px solid var(--line);
            border-radius: 0.4rem;
            font: inherit;
            background: #fff;
        }
        .row { display: grid; gap: 0.75rem; grid-template-columns: 1fr 1fr; }
        @media (max-width: 560px) { .row { grid-template-columns: 1fr; } }
        .actions { margin-top: 1.25rem; display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center; }
        button, .btn {
            appearance: none;
            border: 0;
            background: var(--accent);
            color: #fff;
            padding: 0.6rem 1rem;
            border-radius: 0.4rem;
            font: inherit;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-ghost { background: transparent; color: var(--accent); border: 1px solid var(--accent); }
        .errors, .ok {
            padding: 0.75rem 0.9rem;
            border-radius: 0.4rem;
            margin-bottom: 1rem;
        }
        .errors { background: #fde8e8; color: #7a1f1f; }
        .ok { background: #e7f4ec; color: #1f5b38; }
        nav a { color: var(--accent); margin-right: 1rem; }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <div class="brand">Pictory</div>
            <p class="tag">Bildergalerie-Stub — gemeinsamer Login mit SoccerSportsfan</p>
        </header>
        @yield('content')
    </div>
</body>
</html>
