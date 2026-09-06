<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Impressum — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/help.css?v=2">
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $nav = $data['navigation'];
    @endphp

    <header class="dash-top">
        <div class="dash-top-main">
            @include('partials.brand')
            <nav class="dash-nav" aria-label="Hauptnavigation">
                @foreach ($nav as $item)
                    <a class="nav-big" href="{{ $item['link'] }}" title="{{ $item['name'] }}">
                        <img src="{{ $legacyBase }}images/ffb/navigation/{{ $item['symbol'] }}" alt="" width="40" height="40" loading="lazy">
                        <span>{{ $item['name'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        @if ($user)
            @include('partials.user-card')
        @endif
    </header>

    <main class="dash-main">
        <article class="panel help-content reference-page" id="top">
            <h1 class="help-title">Impressum / Referenzen</h1>

            <section class="help-chapter" id="impressum">
                <h2 class="help-chapter-title">Impressum</h2>
                <p>
                    Inhaber der Seite:<br>
                    Tobias Gritschacher<br>
                    9753 Lind<br>
                    Austria<br>
                    webmaster -ät- tobijat.at<br>
                    +436509393636
                </p>
                <p>
                    Diese Webseite entstand aus einem Universitätsprojekt an der Technischen Universität Graz im Sommersemester 2008.
                    Die Webseite verfolgt keinerlei kommerzielle Ziele und ist vollkommen kostenlos.
                    Sollten Sie Probleme oder Fragen irgendeiner Art in Verbindung mit dieser Webseite haben, wenden Sie sich bitte an obenstehende Email-Adresse.
                </p>
            </section>

            <section class="help-chapter" id="bilder">
                <h2 class="help-chapter-title">Bilder</h2>
                <ul>
                    <li>Ladebalken: <a href="http://ajaxload.info" target="_blank" rel="noopener">ajaxload.info</a></li>
                    <li>Symbole: <a href="http://www.famfamfam.com/lab/icons" target="_blank" rel="noopener">famfamfam.com</a></li>
                    <li>Navigations-Icons (1): <a href="http://www.iconspedia.com" target="_blank" rel="noopener">iconspedia.com</a></li>
                    <li>Navigations-Icons (2): <a href="http://www.vistaicons.com/index.htm" target="_blank" rel="noopener">vistaicons.com</a></li>
                    <li>Team Dresses (1): <a href="http://www.tillintallin.de" target="_blank" rel="noopener">tillintallin.de</a></li>
                    <li>Team Dresses (2): Copyright by UEFA</li>
                    <li>Team Dresses (3): Copyright by FIFA</li>
                </ul>
            </section>

            <section class="help-chapter" id="links">
                <h2 class="help-chapter-title">Links</h2>
                <ul>
                    <li>Tobias Gritschacher: <a href="http://www.tobijat.at" target="_blank" rel="noopener">tobijat.at</a></li>
                    <li>Gerald Musser: <a href="http://www.gemura.com" target="_blank" rel="noopener">gemura.com</a></li>
                    <li>SV Lind: <a href="http://website.svlind.at" target="_blank" rel="noopener">svlind.at</a></li>
                    <li>Webhosting: <a href="http://www.all-inkl.com/index.php?partner=189340" target="_blank" rel="noopener">all-inkl.com</a></li>
                </ul>
            </section>
        </article>
    </main>

    @include('partials.footer')
</body>
</html>
