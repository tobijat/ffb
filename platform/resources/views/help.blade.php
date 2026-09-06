<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Regeln — SoccerSportsfan</title>
    <link rel="stylesheet" href="css/start.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/help.css?v=1">
</head>
<body class="dash-body">
    @php
        $user = $data['user'];
        $nav = $data['navigation'];
        $o = $data['options'];
        $wc = $data['wc_points'];
        $usingDefaults = $data['using_defaults'];
        $pointsMode = $o['options_game_pointsmode'] ?? 'new';
        $wcBestCount = max(0, count($wc) - 1);
        $wcRest = $wc[$wcBestCount] ?? 1;
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
            <div class="user-card">
                <a href="/platform/profile" title="Profil bearbeiten">
                    <img class="user-photo" src="{{ $user['photo_url'] }}" alt="Foto {{ $user['user_nickname'] }}" width="48" height="48">
                </a>
                <div>
                    <p class="hello">Hallo <strong>{{ $user['user_nickname'] }}</strong></p>
                    <p class="muted">Du bist angemeldet.</p>
                </div>
            </div>
        @endif
    </header>

    <main class="dash-main help-layout">
        <aside class="help-toc panel" aria-label="Kapitelauswahl">
            <h1 class="help-title">Kapitelauswahl</h1>
            <ul class="help-toc-list">
                <li><u><a class="nolink" href="#about">Was ist das?</a></u></li>
                <li><u><a class="nolink" href="#register">Registrierung</a></u></li>
                <li><u><a class="nolink" href="#goal">Spielziel</a></u></li>
                <li><u><a class="nolink" href="#ranks">Rangliste</a></u></li>
                <li><u><a class="nolink" href="#lineup">Aufstellung</a></u></li>
                <li><u><a class="nolink" href="#prices">Spieler-Preise</a></u></li>
                <li><u><a class="nolink" href="#points">Spieler-Punkte</a></u></li>
                <li><u><a class="nolink" href="#grade">Spieler-Leistung</a></u></li>
                <li><u><a class="nolink" href="#trend">Spieler-Tendenz</a></u></li>
            </ul>
        </aside>

        <article class="help-content panel" id="top">
            <h1 class="help-title">Regeln</h1>

            <section class="help-chapter" id="about">
                <h2 class="help-chapter-title">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Was ist das? --</h2>
                <div class="help-chapter-text">
                    Das ist ein <b>Fantasy-Sportspiel</b> bei dem es um <b>Fußball</b> geht. Der Spieler übernimmt sozusagen die <b>Rolle</b> des <b>Trainers</b> oder
                    <b>Managers</b> eines Fußballteams und versucht für <b>jede Runde</b> ein möglichst <b>gutes Team</b> zusammenzustellen. Die <b>Basis</b>
                    für dieses Spiel sind <b>reale Fußball-Ligen</b> auf der ganzen Welt.
                </div>
            </section>

            <section class="help-chapter" id="register">
                <h2 class="help-chapter-title">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Registrierung --</h2>
                <div class="help-chapter-text">
                    Wenn du teilnehmen möchtest, musst du dich <b>registrieren</b>. Die Registrierung sowie das gesamte Spiel sind <b>kostenlos</b>. Um dich zu
                    registrieren, musst du auf den <b>"Registrieren"-Button</b> klicken und das <b>Anmeldeformular ausfüllen</b>. Benötigte Felder sind mit
                    einem <b>*</b> gekennzeichnet. Nach der Anmeldung bekommst du einen <b>Aktivierungs-Link</b> an deine <b>Email-Adresse</b> gesendet. Nachdem du diesen
                    <b>Link angeklickt</b> hast, kannst du dich auf der Seite mit deinem <b>Benutzernamen und Passwort anmelden</b>.
                </div>
            </section>

            <section class="help-chapter" id="goal">
                <h2 class="help-chapter-title">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Spielziel --</h2>
                <div class="help-chapter-text">
                    Das <b>Ziel</b> des Spiels ist es, die <b>besten Spieler</b> für deine Aufstellung <b>auszuwählen</b>. Das Spiel kann in mehreren <b>realen Ligen</b>
                    gespielt werden. In welcher Liga du spielen möchtest, kannst du <b>auf der Startseite auswählen</b>. Die <b>Matches</b> werden in
                    sogenannten <b>Spielrunden zusammengefasst</b>. Du kannst <b>für eine Spielrunde</b> immer <b>genau eine Mannschaft</b> aufstellen. Nach einer
                    Spielrunde, also wenn alle Matches gespielt wurden, <b>bekommen alle Spieler Punkte entsprechend ihrer Leistung im realen Match</b>. Auf diese
                    Weise sammelt deine Mannschaft Punkte. Wie gut du im <b>Vergleich mit den anderen</b> warst, kannst du dann unter <b>"Rangliste"</b> nachsehen.
                </div>
            </section>

            <section class="help-chapter" id="ranks">
                <h2 class="help-chapter-title">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Rangliste --</h2>
                <div class="help-chapter-text">
                    @if ($usingDefaults)
                        <em class="help-note">Hinweis: Die hier angegebenen Werte sind die Standard-Einstellung. Diese können jedoch je nach Liga anders sein. Um die
                        Werte für deine Liga zu sehen, musst du nach dem Einloggen auf der Startseite eine Liga auswählen und dann diese Seite aufrufen.</em>
                    @endif
                    Die <b>Plazierung</b> in der Rangliste errechnet sich aus den <b>erreichten WeltCup-Punkten</b>. WeltCup-Punkte
                    bekommst du immer, wenn dein Team in einer <b>Spielrunde</b> unter den <b>besten Mannschaften</b> ist.<br>
                    Die <b>{{ $wcBestCount }} besten Mannschaften</b> in jeder Runde bekommen folgende Anzahl an <b>WeltCup-Punkten</b>:
                    <ul>
                        @for ($i = 0; $i < $wcBestCount; $i++)
                            <li>{{ $i + 1 }}. Platz: <b>{{ $wc[$i] }}</b> Punkte</li>
                        @endfor
                    </ul>
                    <b>Alle restlichen</b> Spieler bekommen <b>{{ $wcRest }}</b> Punkt(e).<br>
                    Dein Ziel ist es also während einer Liga <b>so oft wie möglich</b> unter die <b>besten {{ $wcBestCount }} Mannschaften</b>
                    zu kommen, um am Ende der Saison die meisten Weltcup-Punkte gesammelt zu haben. Die <b>aktuelle Rangliste</b> kannst du immer
                    unter <b>"Rangliste"</b> anschauen.
                </div>
            </section>

            <section class="help-chapter" id="lineup">
                <h2 class="help-chapter-title">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Aufstellung --</h2>
                <div class="help-chapter-text">
                    @if ($usingDefaults)
                        <em class="help-note">Hinweis: Die hier angegebenen Werte sind die Standard-Einstellung. Diese können jedoch je nach Liga anders sein. Um die
                        Werte für deine Liga zu sehen, musst du nach dem Einloggen auf der Startseite eine Liga auswählen und dann diese Seite aufrufen.</em>
                    @endif
                    <b>Deine Aufstellung</b> kannst du unter <b>"Aufstellung"</b> eingeben. Du hast Zeit <b>bis zur Deadline</b> der jeweiligen Spielrunde,
                    die direkt unter dem Spielrundentitel angezeigt wird. Bis zur <b>Deadline</b> kannst du deine <b>Aufstellung</b> natürlich auch
                    <b>jederzeit ändern</b>. Unter <b>"Mannschaft"</b> kannst du die <b>Mannschaften</b> und die <b>erreichten Punkte</b> aller <b>anderen Mitspieler</b>
                    für alle <b>vergangenen Spielrunden</b> anschauen.<br>
                    Bei der <b>Aufstellung</b> deiner Mannschaft gibt es <b>einige Einschränkungen</b>:
                    <ul>
                        <li><b>{{ $o['options_lineup_max_players'] }}</b> Spieler gesamt</li>
                        <li><b>{{ $o['options_lineup_min_g'] }}</b> Tormann</li>
                        <li><b>{{ $o['options_lineup_min_d'] }}-{{ $o['options_lineup_max_d'] }}</b> Verteidiger</li>
                        <li><b>{{ $o['options_lineup_min_m'] }}-{{ $o['options_lineup_max_m'] }}</b> Mittelfeldspieler</li>
                        <li><b>{{ $o['options_lineup_min_s'] }}-{{ $o['options_lineup_max_s'] }}</b> Angreifer</li>
                        <li><b>{{ $o['options_lineup_max_credits'] }}</b> Credits um Spieler zu kaufen</li>
                        <li><b>max. {{ $o['options_lineup_max_players_team'] }}</b> Spieler des selben Teams</li>
                    </ul>
                    Auf der <b>Aufstellungsseite</b> findest du <b>rechts</b> eine <b>Liste der Mannschaften</b> die an dieser Spielrunde teilnehmen. Du kannst dort
                    eine Mannschaft auswählen und siehst eine <b>Liste der einzelnen Spieler</b> der Mannschaft. Wenn du den <b>Namen eines Spielers
                    anklickst</b>, wird er zu deiner <b>Aufstellung hinzugefügt</b>. Vorraussetzung ist natürlich, dass du <b>genügend Credits</b>
                    übrig hast. Um <b>nähere Informationen zu einem Spieler</b> zu bekommen, klick einfach auf das <b>blaue Info-Symbol</b> neben dem
                    Namen des Spielers. Das <b>Ranking</b> zeigt dir an, <b>wie gut</b> ein Spieler in der <b>bisherigen Meisterschaft</b> gespielt hat. Mehr <b>Sterne</b>
                    bedeuten eine <b>bessere Leistung</b>.
                </div>
            </section>

            <section class="help-chapter" id="prices">
                <h2 class="help-chapter-title">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Spieler-Preise --</h2>
                <div class="help-chapter-text">
                    Alle <b>Spieler</b> haben einen <b>virtuellen Preis</b>. Dieser <b>Preis</b> richtet sich nach der <b>Stärke der Mannschaft</b> in
                    der ein Spieler spielt. Die Preise der Spieler <b>können</b> (müssen aber nicht) sich nach jeder
                    Spielrunde <b>entsprechend der Leistung</b> des Spielers <b>ändern</b>. Dabei ist eine <b>Schwankung</b> von
                    <b>+/-2 Credits</b> über bzw. unter dem Mannschaftspreis möglich.
                </div>
            </section>

            <section class="help-chapter" id="points">
                <h2 class="help-chapter-title">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Spieler-Punkte --</h2>
                <div class="help-chapter-text">
                    @if ($usingDefaults)
                        <em class="help-note">Hinweis: Die hier angegebenen Werte sind die Standard-Einstellung. Diese können jedoch je nach Liga anders sein. Um die
                        Werte für deine Liga zu sehen, musst du nach dem Einloggen auf der Startseite eine Liga auswählen und dann diese Seite aufrufen.</em>
                    @endif
                    Deine <b>Spieler</b> bekommen <b>Plus- und Minus-Punkte</b> für folgende Kriterien (ausgenommen Elferschießen):

                    <div class="help-points-wrap">
                        <table class="help-points">
                            <tr>
                                <th><b>Kriterium</b></th>
                                <th><b>Torwart</b></th>
                                <th><b>Verteidigung</b></th>
                                <th><b>Mittelfeld</b></th>
                                <th><b>Angriff</b></th>
                            </tr>
                            @if ($pointsMode === 'new')
                                <tr>
                                    <td><b>mind. 1 und weniger als {{ $o['options_score_minutes_treshold'] }}<br>Minuten gespielt</b></td>
                                    <td>+{{ $o['options_score_minutes_lt30'] }}</td>
                                    <td>+{{ $o['options_score_minutes_lt30'] }}</td>
                                    <td>+{{ $o['options_score_minutes_lt30'] }}</td>
                                    <td>+{{ $o['options_score_minutes_lt30'] }}</td>
                                </tr>
                                <tr>
                                    <td><b>zwischen {{ $o['options_score_minutes_treshold'] }} und {{ $o['options_score_minutes'] }}<br>Minuten gespielt</b></td>
                                    <td>+{{ $o['options_score_minutes_lt'] }}</td>
                                    <td>+{{ $o['options_score_minutes_lt'] }}</td>
                                    <td>+{{ $o['options_score_minutes_lt'] }}</td>
                                    <td>+{{ $o['options_score_minutes_lt'] }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td><b>weniger als {{ $o['options_score_minutes'] }}<br>Minuten gespielt</b></td>
                                    <td>+{{ $o['options_score_minutes_lt'] }}</td>
                                    <td>+{{ $o['options_score_minutes_lt'] }}</td>
                                    <td>+{{ $o['options_score_minutes_lt'] }}</td>
                                    <td>+{{ $o['options_score_minutes_lt'] }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td><b>mindestens {{ $o['options_score_minutes'] }}<br>Minuten gespielt</b></td>
                                <td>+{{ $o['options_score_minutes_gt'] }}</td>
                                <td>+{{ $o['options_score_minutes_gt'] }}</td>
                                <td>+{{ $o['options_score_minutes_gt'] }}</td>
                                <td>+{{ $o['options_score_minutes_gt'] }}</td>
                            </tr>
                            <tr>
                                <td><b>geschossenes Tor</b></td>
                                <td>+{{ $o['options_score_goals_g'] }}</td>
                                <td>+{{ $o['options_score_goals_d'] }}</td>
                                <td>+{{ $o['options_score_goals_m'] }}</td>
                                <td>+{{ $o['options_score_goals_s'] }}</td>
                            </tr>
                            @if ($o['options_score_assists'] > 0)
                                <tr>
                                    <td><b>Assist</b></td>
                                    <td>+{{ $o['options_score_assists'] }}</td>
                                    <td>+{{ $o['options_score_assists'] }}</td>
                                    <td>+{{ $o['options_score_assists'] }}</td>
                                    <td>+{{ $o['options_score_assists'] }}</td>
                                </tr>
                            @endif
                            @if ($pointsMode === 'new')
                                <tr>
                                    <td><b>kein Gegentor und mind.<br>{{ $o['options_score_minutes_treshold'] }} Minuten gespielt</b></td>
                                    <td>+{{ $o['options_score_no_oppgoals_g'] }}</td>
                                    <td>+{{ $o['options_score_no_oppgoals_d'] }}</td>
                                    <td>+{{ $o['options_score_no_oppgoals_m'] }}</td>
                                    <td>--</td>
                                </tr>
                            @else
                                <tr>
                                    <td><b>kein Gegentor</b></td>
                                    <td>+{{ $o['options_score_no_oppgoals_g'] }}</td>
                                    <td>+{{ $o['options_score_no_oppgoals_d'] }}</td>
                                    <td>+{{ $o['options_score_no_oppgoals_m'] }}</td>
                                    <td>--</td>
                                </tr>
                            @endif
                            @if ($o['options_score_penalty_saved'] > 0)
                                <tr>
                                    <td><b>gehaltener Elfer<br>während der Spielzeit</b></td>
                                    <td>+{{ $o['options_score_penalty_saved'] }}</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                            @endif
                            @if ($o['options_score_penalty_lost'] != 0)
                                <tr>
                                    <td><b>verschossener Elfer<br>während der Spielzeit</b></td>
                                    <td>{{ $o['options_score_penalty_lost'] }}</td>
                                    <td>{{ $o['options_score_penalty_lost'] }}</td>
                                    <td>{{ $o['options_score_penalty_lost'] }}</td>
                                    <td>{{ $o['options_score_penalty_lost'] }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td><b>gelbe Karte</b></td>
                                <td>{{ $o['options_score_card_y'] }}</td>
                                <td>{{ $o['options_score_card_y'] }}</td>
                                <td>{{ $o['options_score_card_y'] }}</td>
                                <td>{{ $o['options_score_card_y'] }}</td>
                            </tr>
                            <tr>
                                <td><b>gelb-rote Karte</b></td>
                                <td>{{ $o['options_score_card_yr'] }}</td>
                                <td>{{ $o['options_score_card_yr'] }}</td>
                                <td>{{ $o['options_score_card_yr'] }}</td>
                                <td>{{ $o['options_score_card_yr'] }}</td>
                            </tr>
                            <tr>
                                <td><b>rote Karte</b></td>
                                <td>{{ $o['options_score_card_r'] }}</td>
                                <td>{{ $o['options_score_card_r'] }}</td>
                                <td>{{ $o['options_score_card_r'] }}</td>
                                <td>{{ $o['options_score_card_r'] }}</td>
                            </tr>
                            @if ($pointsMode === 'new')
                                <tr>
                                    <td><b>je 2 Gegentore während<br>der Spielzeit des Spielers</b></td>
                                    <td>{{ $o['options_score_oppgoals_g'] }}</td>
                                    <td>{{ $o['options_score_oppgoals_d'] }}</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                            @else
                                <tr>
                                    <td><b>je 2 Gegentore</b></td>
                                    <td>{{ $o['options_score_oppgoals_g'] }}</td>
                                    <td>{{ $o['options_score_oppgoals_d'] }}</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                            @endif
                            <tr>
                                <td><b>Eigentor</b></td>
                                <td>{{ $o['options_score_owngoals'] }}</td>
                                <td>{{ $o['options_score_owngoals'] }}</td>
                                <td>{{ $o['options_score_owngoals'] }}</td>
                                <td>{{ $o['options_score_owngoals'] }}</td>
                            </tr>
                            @if ($o['options_score_penaltyshootout_save'] != 0)
                                <tr>
                                    <td><b>gehaltener Elfer<br>im Elferschießen</b></td>
                                    <td>+{{ $o['options_score_penaltyshootout_save'] }}</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                            @endif
                            @if ($o['options_score_penaltyshootout_hit'] != 0)
                                <tr>
                                    <td><b>erfolgreicher Elfer<br>im Elferschießen</b></td>
                                    <td>+{{ $o['options_score_penaltyshootout_hit'] }}</td>
                                    <td>+{{ $o['options_score_penaltyshootout_hit'] }}</td>
                                    <td>+{{ $o['options_score_penaltyshootout_hit'] }}</td>
                                    <td>+{{ $o['options_score_penaltyshootout_hit'] }}</td>
                                </tr>
                            @endif
                            @if ($o['options_score_penaltyshootout_lost'] != 0)
                                <tr>
                                    <td><b>verschossener Elfer<br>im Elferschießen</b></td>
                                    <td>{{ $o['options_score_penaltyshootout_lost'] }}</td>
                                    <td>{{ $o['options_score_penaltyshootout_lost'] }}</td>
                                    <td>{{ $o['options_score_penaltyshootout_lost'] }}</td>
                                    <td>{{ $o['options_score_penaltyshootout_lost'] }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </section>

            <section class="help-chapter" id="grade">
                <h2 class="help-chapter-title">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Spieler-Leistung --</h2>
                <div class="help-chapter-text">
                    Die <b>goldenen Sterne</b> neben jedem Spieler in der <b>Spielerliste</b> unter <b>"Aufstellung"</b> zeigen die <b>Leistung</b>
                    des jeweiligen Spielers an. Diese errechnet sich aus verschiedenen Faktoren. Je <b>besser</b> ein Spieler im <b>Vergleich zum Durchschnitt</b>
                    seiner Spielerposition ist, desto <b>höher</b> wird er eingestuft. Eine <b>schlechte <a class="nolink" href="#trend"><u>Tendenz</u></a></b> beeinflusst seine Leistung <b>negativ</b>,
                    eine <b>gute <a class="nolink" href="#trend"><u>Tendenz</u></a></b> beeinflusst sie <b>positiv</b>. So kann es vorkommen, dass auch ein sehr guter Spieler etwas schlechter eingestuft wird, wenn
                    er im vergangenen Match überraschend schlecht gespielt hat. <b>Aber Achtung:</b> viele Sterne bedeuten nicht unbedingt, dass der Spieler im
                    nächsten Spiel auch wirklich spielt!
                </div>
            </section>

            <section class="help-chapter" id="trend">
                <h2 class="help-chapter-title">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Spieler-Tendenz --</h2>
                <div class="help-chapter-text">
                    Die <b>grünen und roten Pfeile</b> vor dem Namen des Spielers in der <b>Spielerliste</b> unter <b>"Aufstellung"</b> zeigen die <b>Tendenz</b>
                    des jeweiligen Spielers an. Diese <b>errechnet</b> sich daraus, wie gut der Spieler in den <b>vergangenen Spielen</b>
                    gespielt hat. Spielt ein <b>sehr guter Spieler</b> in einem oder mehreren Matches relativ <b>schlecht</b>, dann hat er
                    eine <b>negative Tendenz</b>. Und umgekehrt, spielt ein Spieler der lange schlecht oder garnicht gespielt hat
                    auf einmal gut, dann hat er eine positive Tendenz. Die <b>Tendenz</b> kann sich also <b>von Spielrunde zu Spielrunde
                    ändern</b>. Die Tendenz <b>beeinflusst</b> auch die <b><a class="nolink" href="#grade"><u>Leistungs</u></a>-Einstufung</b> des Spielers.
                </div>
            </section>
        </article>
    </main>


    @include('partials.footer')
</body>
</html>
