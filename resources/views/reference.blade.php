<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Impressum — SoccerSportsfan</title>
    @php $assetBase = rtrim(config('ffb.home_path'), '/'); @endphp
    <link rel="stylesheet" href="{{ $assetBase }}/css/start.css?v=32">
    <link rel="stylesheet" href="{{ $assetBase }}/css/dashboard.css?v=25">
    <link rel="stylesheet" href="{{ $assetBase }}/css/help.css?v=4">
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $nav = $data['navigation'];
    @endphp

    @include('partials.dash-top')

    <main class="dash-main">
        <article class="panel help-content reference-page" id="top">
            <h1 class="help-title">Impressum / Referenzen</h1>

            <section class="help-chapter" id="impressum">
                <h2 class="help-chapter-title">Impressum</h2>
                <p>
                    Inhaber der Seite:<br>
                    Tobi Gritschacher<br>
                    Austria<br>
                    webmaster -ät- sportsfan.at<br>
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
                    <li>Spieler-Fotos: <a href="https://commons.wikimedia.org/wiki/Main_Page" target="_blank" rel="noopener">Wikimedia Commons</a></li>
                    <li>Diverse Symbole und Icons: <a href="https://github.com/legacy-icons" target="_blank" rel="noopener">famfamfam</a> (auf GitHub)</li>
                    <li>Navigation-Icons (1): <a href="https://www.iconspedia.com" target="_blank" rel="noopener">www.iconspedia.com</a>  (offline)</li>
                    <li>Navigation-Icons (2): <a href="http://www.vistaicons.com/index.htm" target="_blank" rel="noopener">vistaicons.com</a>  (offline)</li>
                    <li>Team Dresses (neu): erstellt mit <a href="https://github.com/tobijat/kit-kitchen" target="_blank" rel="noopener">Kit Kitchen</a> (auf GitHub)</li>
                    <li>Team Dresses (alt): <a href="https://www.tillintallin.de" target="_blank" rel="noopener">www.tillintallin.de</a></li>
                    <li>Team Dresses (alt): Copyright by UEFA</li>
                    <li>Team Dresses (alt): Copyright by FIFA</li>
                    <li>Liga- und Team-Badges: <a href="https://www.footylogos.com/" target="_blank" rel="noopener">FootyLogos.com</a></li>
                    <li>Länder-Flaggen: <a href="https://github.com/lipis/flag-icons" target="_blank" rel="noopener">flag-icons</a> (auf GitHub)</li>
                </ul>
            </section>

            <section class="help-chapter" id="links">
                <h2 class="help-chapter-title">Links</h2>
                <ul>
                    <li>Tobi Gritschacher: <a href="https://www.tobijat.at" target="_blank" rel="noopener">tobijat.at</a></li>
                    <li>Gerald Musser: <a href="https://www.gemura.com" target="_blank" rel="noopener">gemura.com</a></li>
                    <li>Webhosting: <a href="https://all-inkl.com/PA9494CB73B7D0" target="_blank" rel="noopener">all-inkl.com</a></li>
                    <li>Code: <a href="https://github.com/tobijat/ffb" target="_blank" rel="noopener">GitHub.com</a></li>
                </ul>
            </section>
        </article>
    </main>

    @include('partials.footer')
    @include('partials.guest-login-scripts')
</body>
</html>
