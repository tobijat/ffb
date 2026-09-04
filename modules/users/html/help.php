<?php
/**
 * @author Gritschacher Tobias, Musser Gerald
 * @copyright 02/2010
 */

?>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher Tobias, Musser Gerald">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.FFB_INCLUDE_PATH?>standard.css" type="text/css">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.FFB_INCLUDE_PATH?>help.css" type="text/css">
</head>

<body>
<div id="Container">
    <div class="rounddiv_nav">
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div id="NavbarRound">
				<div id="Navigation">
			        <?php include(FFB_VIEWER_PATH.'navigation.php')?>
			    </div>
			    <div style="clear:both;"></div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div>

	<!--
    <div class="rounddiv_main">
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		    <div id="Main">
	//-->
		    	<div class="rounddiv_mainhelpleft">
					<div class="roundcorner_dark">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					    <div id="Mainhelpleft">
					    	<div id="helptitle">Kapitelauswahl</div>
					    	<br>
					    	<div id="helpchaptermenue"><u><a class="nolink" href="#about">Was ist das?</a></u></div>
					    	<div id="helpchaptermenue"><u><a class="nolink" href="#register">Registrierung</a></u></div>
					    	<div id="helpchaptermenue"><u><a class="nolink" href="#goal">Spielziel</a></u></div>
					    	<div id="helpchaptermenue"><u><a class="nolink" href="#ranks">Rangliste</a></u></div>
					    	<div id="helpchaptermenue"><u><a class="nolink" href="#lineup">Aufstellung</a></u></div>
					    	<div id="helpchaptermenue"><u><a class="nolink" href="#prices">Spieler-Preise</a></u></div>
					    	<div id="helpchaptermenue"><u><a class="nolink" href="#points">Spieler-Punkte</a></u></div>
					    	<div id="helpchaptermenue"><u><a class="nolink" href="#grade">Spieler-Leistung</a></u></div>
					    	<div id="helpchaptermenue"><u><a class="nolink" href="#trend">Spieler-Tendenz</a></u></div>
					    </div>
						<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
				</div>

			    <div class="rounddiv_mainhelpright">
					<div class="roundcorner_dark">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					    <div id="Mainhelpright">
        					<a name="top"><div id="helptitle">Regeln</div></a>
        					<div id="helpchapter">
					            <a name="about"><div id="helpchaptertitle">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Was ist das? --</div></a>
					            <div id="helpchaptertext">
						            Das ist ein <b>Fantasy-Sportspiel</b> bei dem es um <b>Fu&szlig;ball</b> geht. Der Spieler &uuml;bernimmt sozusagen die <b>Rolle</b> des <b>Trainers</b> oder
						            <b>Managers</b> eines Fu&szlig;ballteams und versucht f&uuml;r <b>jede Runde</b> ein m&ouml;glichst <b>gutes Team</b> zusammenzustellen. Die <b>Basis</b>
						            f&uuml;r dieses Spiel sind <b>reale Fu&szlig;ball-Ligen</b> auf der ganzen Welt.
								</div>
					        </div>
					        <br>
					        <div id="helpchapter">
					            <a name="register"><div id="helpchaptertitle">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Registrierung --</div></a>
					            <div id="helpchaptertext">
						            Wenn du teilnehmen m&ouml;chtest, musst du dich <b>registrieren</b>. Die Registrierung sowie das gesamte Spiel sind <b>kostenlos</b>. Um dich zu
						            registrieren, musst du auf den <b>"Registrieren"-Button</b> klicken und das <b>Anmeldeformular ausf&uuml;llen</b>. Ben&ouml;tigte Felder sind mit
						            einem <b>*</b> gekennzeichnet. Nach der Anmeldung bekommst du einen <b>Aktivierungs-Link</b> an deine <b>Email-Adresse</b> gesendet. Nachdem du diesen
						            <b>Link angeklickt</b> hast, kannst du dich auf der Seite mit deinem <b>Benutzernamen und Passwort anmelden</b>.
								</div>
					        </div>
					        <br>
					        <div id="helpchapter">
					            <a name="goal"><div id="helpchaptertitle">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Spielziel --</div></a>
					            <div id="helpchaptertext">
						            Das <b>Ziel</b> des Spiels ist es, die <b>besten Spieler</b> für deine Aufstellung <b>auszuw&auml;hlen</b>. Das Spiel kann in mehreren <b>realen Ligen</b>
						            gespielt werden. In welcher Liga du spielen m&ouml;chtest, kannst du <b>auf der Startseite ausw&auml;hlen</b>. Die <b>Matches</b> werden in
						            sogenannten <b>Spielrunden zusammengefasst</b>. Du kannst <b>f&uuml;r eine Spielrunde</b> immer <b>genau eine Mannschaft</b> aufstellen. Nach einer
						            Spielrunde, also wenn alle Matches gespielt wurden, <b>bekommen alle Spieler Punkte entsprechend ihrer Leistung im realen Match</b>. Auf diese
						            Weise sammelt deine Mannschaft Punkte. Wie gut du im <b>Vergleich mit den anderen</b> warst, kannst du dann unter <b>"Rangliste"</b> nachsehen.
					        	</div>
							</div>
					        <br>
					        <div id="helpchapter">
					            <a name="ranks"><div id="helpchaptertitle">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Rangliste --</div></a>
					            <div id="helpchaptertext">
					            	<?php if($this->session->game_id_player<1) {?>
						                <em>Hinweis: Die hier angegebenen Werte sind die Standard-Einstellung. Diese k&ouml;nnen jedoch je nach Liga anders sein. Um die
						                Werte f&uuml;r deine Liga zu sehen, musst du nach dem Einloggen auf der Startseite eine Liga ausw&auml;hlen und dann diese Seite aufrufen.</em>
						                <br><br>
					            	<?php }?>
						            Die <b>Plazierung</b> in der Rangliste errechnet sich aus den <b>erreichten WeltCup-Punkten</b>. WeltCup-Punkte
						            bekommst du immer, wenn dein Team in einer <b>Spielrunde</b> unter den <b>besten Mannschaften</b> ist.<br>
						            <?php $wcpoints = explode(',', $this->options->options_game_wcpoints);?>
						            Die <b><?= count($wcpoints)-1;?> besten Mannschaften</b> in jeder Runde bekommen folgende Anzahl an <b>WeltCup-Punkten</b>:
						            <ul>
						            	<?php for($i=1;$i<count($wcpoints);$i++) {?>
											<li><?= $i;?>. Platz: <b><?= $wcpoints[$i-1];?></b> Punkte</li>
										<?php }?>
						            </ul>
						            <b>Alle restlichen</b> Spieler bekommen <b><?= $wcpoints[count($wcpoints)-1];?></b> Punkt(e).<br>
						            Dein Ziel ist es also w&auml;hrend einer Liga <b>so oft wie m&ouml;glich</b> unter die <b>besten <?= count($wcpoints)-1;?> Mannschaften</b>
									zu kommen, um am Ende der Saison die meisten Weltcup-Punkte gesammelt zu haben. Die <b>aktuelle Rangliste</b> kannst du immer
									unter <b>"Rangliste"</b> anschauen.
					        	</div>
							</div>
							<br>
					        <div id="helpchapter">
					            <a name="lineup"><div id="helpchaptertitle">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Aufstellung --</div></a>
						            <div id="helpchaptertext">
						            <?php if($this->session->game_id_player<1) {?>
							            <em>Hinweis: Die hier angegebenen Werte sind die Standard-Einstellung. Diese k&ouml;nnen jedoch je nach Liga anders sein. Um die
							            Werte f&uuml;r deine Liga zu sehen, musst du nach dem Einloggen auf der Startseite eine Liga ausw&auml;hlen und dann diese Seite aufrufen.</em>
							            <br><br>
						            <?php }?>
						            <b>Deine Aufstellung</b> kannst du unter <b>"Aufstellung"</b> eingeben. Du hast Zeit <b>bis zur Deadline</b> der jeweiligen Spielrunde,
									die direkt unter dem Spielrundentitel angezeigt wird. Bis zur <b>Deadline</b> kannst du deine <b>Aufstellung</b> nat&uuml;rlich auch
									<b>jederzeit &auml;ndern</b>. Unter <b>"Mannschaft"</b> kannst du die <b>Mannschaften</b> und die <b>erreichten Punkte</b> aller <b>anderen Mitspieler</b>
									f&uuml;r alle <b>vergangenen Spielrunden</b> anschauen.<br>
						            Bei der <b>Aufstellung</b> deiner Mannschaft gibt es <b>einige Einschr&auml;nkungen</b>:
						            <ul>
						            	<li><b><?= $this->options->options_lineup_max_players?></b> Spieler gesamt</li>
						            	<li><b><?= $this->options->options_lineup_min_g?></b> Tormann</li>
						            	<li><b><?= $this->options->options_lineup_min_d?>-<?= $this->options->options_lineup_max_d?></b> Verteidiger</li>
						            	<li><b><?= $this->options->options_lineup_min_m?>-<?= $this->options->options_lineup_max_m?></b> Mittelfeldspieler</li>
						            	<li><b><?= $this->options->options_lineup_min_s?>-<?= $this->options->options_lineup_max_s?></b> Angreifer</li>
						            	<li><b><?= $this->options->options_lineup_max_credits?></b> Credits um Spieler zu kaufen</li>
						            	<li><b>max. <?= $this->options->options_lineup_max_players_team?></b> Spieler des selben Teams</li>
						            </ul>
						            Auf der <b>Aufstellungsseite</b> findest du <b>rechts</b> eine <b>Liste der Mannschaften</b> die an dieser Spielrunde teilnehmen. Du kannst dort
						            eine Mannschaft ausw&auml;hlen und siehst eine <b>Liste der einzelnen Spieler</b> der Mannschaft. Wenn du den <b>Namen eines Spielers
						            anklickst</b>, wird er zu deiner <b>Aufstellung hinzugef&uuml;gt</b>. Vorraussetzung ist nat&uuml;rlich, dass du <b>gen&uuml;gend Credits</b>
						            &uuml;brig hast. Um <b>n&auml;here Informationen zu einem Spieler</b> zu bekommen, klick einfach auf das <b>blaue Info-Symbol</b> neben dem
									Namen des Spielers. Das <b>Ranking</b> zeigt dir an, <b>wie gut</b> ein Spieler in der <b>bisherigen Meisterschaft</b> gespielt hat. Mehr <b>Sterne</b>
									bedeuten eine <b>bessere Leistung</b>.
						        </div>
					        </div>
					        <br>
					        <div id="helpchapter">
					            <a name="prices"><div id="helpchaptertitle">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Spieler-Preise --</div></a>
					            <div id="helpchaptertext">
					            	Alle <b>Spieler</b> haben einen <b>virtuellen Preis</b>. Dieser <b>Preis</b> richtet sich nach der <b>St&auml;rke der Mannschaft</b> in
					            	der ein Spieler spielt. Die Preise der Spieler <b>k&ouml;nnen</b> (m&uuml;ssen aber nicht) sich nach jeder
					            	Spielrunde <b>entsprechend der Leistung</b> des Spielers <b>&auml;ndern</b>. Dabei ist eine <b>Schwankung</b> von
									<b>+/-2 Credits</b> &uuml;ber bzw. unter dem Mannschaftspreis m&ouml;glich.
					            </div>
					        </div>
					        <br>
					        <div id="helpchapter">
					            <a name="points"><div id="helpchaptertitle">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Spieler-Punkte --</div></a>
					            <div id="helpchaptertext">
					            	<?php if($this->session->game_id_player<1) {?>
							            <em>Hinweis: Die hier angegebenen Werte sind die Standard-Einstellung. Diese k&ouml;nnen jedoch je nach Liga anders sein. Um die
							            Werte f&uuml;r deine Liga zu sehen, musst du nach dem Einloggen auf der Startseite eine Liga ausw&auml;hlen und dann diese Seite aufrufen.</em>
							            <br><br>
						            <?php }?>
						            Deine <b>Spieler</b> bekommen <b>Plus- und Minus-Punkte</b> f&uuml;r folgende Kriterien (ausgenommen Elferschie&szlig;en):<br>
						            <br>
						            <center>
						            <table class="points">
						                <tr>
						                    <th><b>Kriterium</b></th>
						                    <th><b>Torwart</b></th>
						                    <th><b>Verteidigung</b></th>
						                    <th><b>Mittelfeld</b></th>
						                    <th><b>Angriff</b></th>
						                </tr>
						                <?php if($this->options->options_game_pointsmode == 'new') {?>
						                <tr>
						                    <td><b>mind. 1 und weniger als <?= $this->options->options_score_minutes_treshold?><br>Minuten gespielt</b></td>
						                    <td>+<?= $this->options->options_score_minutes_lt30?></td>
						                    <td>+<?= $this->options->options_score_minutes_lt30?></td>
						                    <td>+<?= $this->options->options_score_minutes_lt30?></td>
						                    <td>+<?= $this->options->options_score_minutes_lt30?></td>
						                </tr>
						                <?php }?>
						                <?php if($this->options->options_game_pointsmode == 'new') {?>
						                <tr>
						                    <td><b>zwischen <?= $this->options->options_score_minutes_treshold?> und <?= $this->options->options_score_minutes?><br>Minuten gespielt</b></td>
						                    <td>+<?= $this->options->options_score_minutes_lt?></td>
						                    <td>+<?= $this->options->options_score_minutes_lt?></td>
						                    <td>+<?= $this->options->options_score_minutes_lt?></td>
						                    <td>+<?= $this->options->options_score_minutes_lt?></td>
						                </tr>
						                <?php } else {?>
						                <tr>
						                    <td><b>weniger als <?= $this->options->options_score_minutes?><br>Minuten gespielt</b></td>
						                    <td>+<?= $this->options->options_score_minutes_lt?></td>
						                    <td>+<?= $this->options->options_score_minutes_lt?></td>
						                    <td>+<?= $this->options->options_score_minutes_lt?></td>
						                    <td>+<?= $this->options->options_score_minutes_lt?></td>
						                </tr>
						                <?php }?>
						                <tr>
						                    <td><b>mindestens <?= $this->options->options_score_minutes?><br>Minuten gespielt</b></td>
						                    <td>+<?= $this->options->options_score_minutes_gt?></td>
						                    <td>+<?= $this->options->options_score_minutes_gt?></td>
						                    <td>+<?= $this->options->options_score_minutes_gt?></td>
						                    <td>+<?= $this->options->options_score_minutes_gt?></td>
						                </tr>
						                <tr>
						                    <td><b>geschossenes Tor</b></td>
						                    <td>+<?= $this->options->options_score_goals_g?></td>
						                    <td>+<?= $this->options->options_score_goals_d?></td>
						                    <td>+<?= $this->options->options_score_goals_m?></td>
						                    <td>+<?= $this->options->options_score_goals_s?></td>
						                </tr>
						                <?php if($this->options->options_score_assists > 0) {?>
						                <tr>
						                    <td><b>Assist</b></td>
						                    <td>+<?= $this->options->options_score_assists?></td>
						                    <td>+<?= $this->options->options_score_assists?></td>
						                    <td>+<?= $this->options->options_score_assists?></td>
						                    <td>+<?= $this->options->options_score_assists?></td>
						                </tr>
						                <?php }?>
						                <?php if($this->options->options_game_pointsmode == 'new') {?>
						                <tr>
						                    <td><b>kein Gegentor und mind.<br><?= $this->options->options_score_minutes_treshold?> Minuten gespielt</b></td>
						                    <td>+<?= $this->options->options_score_no_oppgoals_g?></td>
						                    <td>+<?= $this->options->options_score_no_oppgoals_d?></td>
						                    <td>+<?= $this->options->options_score_no_oppgoals_m?></td>
						                    <td>--</td>
						                </tr>
						                <?php } else {?>
						                <tr>
						                    <td><b>kein Gegentor</b></td>
						                    <td>+<?= $this->options->options_score_no_oppgoals_g?></td>
						                    <td>+<?= $this->options->options_score_no_oppgoals_d?></td>
						                    <td>+<?= $this->options->options_score_no_oppgoals_m?></td>
						                    <td>--</td>
						                </tr>
						                <?php }?>
						                <?php if($this->options->options_score_penalty_saved > 0) {?>
						                <tr>
						                    <td><b>gehaltener Elfer<br>w&auml;hrend der Spielzeit</b></td>
						                    <td>+<?= $this->options->options_score_penalty_saved?></td>
						                    <td>--</td>
						                    <td>--</td>
						                    <td>--</td>
						                </tr>
						                <?php }?>
						                <?php if($this->options->options_score_penalty_lost != 0) {?>
						                <tr>
						                    <td><b>verschossener Elfer<br>w&auml;hrend der Spielzeit</b></td>
						                    <td><?= $this->options->options_score_penalty_lost?></td>
						                    <td><?= $this->options->options_score_penalty_lost?></td>
						                    <td><?= $this->options->options_score_penalty_lost?></td>
						                    <td><?= $this->options->options_score_penalty_lost?></td>
						                </tr>
						                <?php }?>
						                <tr>
						                    <td><b>gelbe Karte</b></td>
						                    <td><?= $this->options->options_score_card_y?></td>
						                    <td><?= $this->options->options_score_card_y?></td>
						                    <td><?= $this->options->options_score_card_y?></td>
						                    <td><?= $this->options->options_score_card_y?></td>
						                </tr>
						                <tr>
						                    <td><b>gelb-rote Karte</b></td>
						                    <td><?= $this->options->options_score_card_yr?></td>
						                    <td><?= $this->options->options_score_card_yr?></td>
						                    <td><?= $this->options->options_score_card_yr?></td>
						                    <td><?= $this->options->options_score_card_yr?></td>
						                </tr>
						                <tr>
						                    <td><b>rote Karte</b></td>
						                    <td><?= $this->options->options_score_card_r?></td>
						                    <td><?= $this->options->options_score_card_r?></td>
						                    <td><?= $this->options->options_score_card_r?></td>
						                    <td><?= $this->options->options_score_card_r?></td>
						                </tr>
						                <?php if($this->options->options_game_pointsmode == 'new') {?>
						                <tr>
						                    <td><b>je 2 Gegentore w&auml;hrend<br>der Spielzeit des Spielers</b></td>
						                    <td><?= $this->options->options_score_oppgoals_g?></td>
						                    <td><?= $this->options->options_score_oppgoals_d?></td>
						                    <td>--</td>
						                    <td>--</td>
						                </tr>
						                <?php } else {?>
						                <tr>
						                    <td><b>je 2 Gegentore</b></td>
						                    <td><?= $this->options->options_score_oppgoals_g?></td>
						                    <td><?= $this->options->options_score_oppgoals_d?></td>
						                    <td>--</td>
						                    <td>--</td>
						                </tr>
						                <?php }?>
						                <tr>
						                    <td><b>Eigentor</b></td>
						                    <td><?= $this->options->options_score_owngoals?></td>
						                    <td><?= $this->options->options_score_owngoals?></td>
						                    <td><?= $this->options->options_score_owngoals?></td>
						                    <td><?= $this->options->options_score_owngoals?></td>
						                </tr>
						                <?php if($this->options->options_score_penaltyshootout_save != 0) {?>
						                <tr>
						                    <td><b>gehaltener Elfer<br>im Elferschie&szlig;en</b></td>
						                    <td>+<?= $this->options->options_score_penaltyshootout_save?></td>
						                    <td>--</td>
						                    <td>--</td>
						                    <td>--</td>
						                </tr>
						                <?php }?>
						                <?php if($this->options->options_score_penaltyshootout_hit != 0) {?>
						                <tr>
						                    <td><b>erfolgreicher Elfer<br>im Elferschie&szlig;en</b></td>
						                    <td>+<?= $this->options->options_score_penaltyshootout_hit?></td>
						                    <td>+<?= $this->options->options_score_penaltyshootout_hit?></td>
						                    <td>+<?= $this->options->options_score_penaltyshootout_hit?></td>
						                    <td>+<?= $this->options->options_score_penaltyshootout_hit?></td>
						                </tr>
						                <?php }?>
						                <?php if($this->options->options_score_penaltyshootout_lost != 0) {?>
						                <tr>
						                    <td><b>verschossener Elfer<br>im Elferschie&szlig;en</b></td>
						                    <td><?= $this->options->options_score_penaltyshootout_lost?></td>
						                    <td><?= $this->options->options_score_penaltyshootout_lost?></td>
						                    <td><?= $this->options->options_score_penaltyshootout_lost?></td>
						                    <td><?= $this->options->options_score_penaltyshootout_lost?></td>
						                </tr>
						                <?php }?>
						            </table>
						            </center>
						        </div>
					        </div>
					        <br>
					        <div id="helpchapter">
					            <a name="grade"><div id="helpchaptertitle">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Spieler-Leistung --</div></a>
					            <div id="helpchaptertext">
					            	Die <b>goldenen Sterne</b> neben jedem Spieler in der <b>Spielerliste</b> unter <b>"Aufstellung"</b> zeigen die <b>Leistung</b>
									des jeweiligen Spielers an. Diese errechnet sich aus verschiedenen Faktoren. Je <b>besser</b> ein Spieler im <b>Vergleich zum Durchschnitt</b>
									seiner Spielerposition ist, desto <b>h&ouml;her</b> wird er eingestuft. Eine <b>schlechte <a class="nolink" href="#trend"><u>Tendenz</u></a></b> beeinflusst seine Leistung <b>negativ</b>,
									eine <b>gute <a class="nolink" href="#trend"><u>Tendenz</u></a></b> beeinflusst sie <b>positiv</b>. So kann es vorkommen, dass auch ein sehr guter Spieler etwas schlechter eingestuft wird, wenn
									er im vergangenen Match überraschend schlecht gespielt hat. <b>Aber Achtung:</b> viele Sterne bedeuten nicht unbedingt, dass der Spieler im
									n&auml;chsten Spiel auch wirklich spielt!
					            </div>
					        </div>
					        <br>
					        <div id="helpchapter">
					            <a name="trend"><div id="helpchaptertitle">-- <a title="Seitenanfang" class="nolink" href="#top">&uarr;</a> Spieler-Tendenz --</div></a>
					            <div id="helpchaptertext">
					            	Die <b>gr&uuml;nen und roten Pfeile</b> vor dem Namen des Spielers in der <b>Spielerliste</b> unter <b>"Aufstellung"</b> zeigen die <b>Tendenz</b>
									des jeweiligen Spielers an. Diese <b>errechnet</b> sich daraus, wie gut der Spieler in den <b>vergangenen Spielen</b>
									gespielt hat. Spielt ein <b>sehr guter Spieler</b> in einem oder mehreren Matches relativ <b>schlecht</b>, dann hat er
									eine <b>negative Tendenz</b>. Und umgekehrt, spielt ein Spieler der lange schlecht oder garnicht gespielt hat
									auf einmal gut, dann hat er eine positive Tendenz. Die <b>Tendenz</b> kann sich also <b>von Spielrunde zu Spielrunde
									&auml;ndern</b>. Die Tendenz <b>beeinflusst</b> auch die <b><a class="nolink" href="#grade"><u>Leistungs</u></a>-Einstufung</b> des Spielers.
					            </div>
					        </div>
					        <br>
					        <!--
					        <div id="helpchapter">
					            <a name="ranking"><div id="helpchaptertitle">Turnierranking</div></a>
					            Das Turnierranking, ablesbar an den goldenen Sternen neben den Spielern im Aufstellungsmodus bzw. unter den Spielern
					            im Mannschaftsmodus, zeigt an, wie gut ein Spieler bisher in der ausgewählten Liga gespielt hat. Der Wert für das
					            Turnierranking kann zwischen 0 und 100 liegen und errechnet sich aus den gespielten Minuten, den Durchschnittspunkten und
					            der Beliebtheit des Spielers, d.h. wie oft er aufgestellt wurde. Durch das Turnierranking soll es leichter fallen, einen
					            guten Spieler innerhalb eines Teams zu erkennen.
					            <br>
					            Achtung: ein gutes Turnierranking bedeutet aber keinesfalls, dass der Spieler im
					            nächsten Spiel tatsächlich eingesetzt wird.
					        </div>
					        //-->
					    </div>
					    <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
				</div>
				<div style="clear:both;"></div>
	<!--
		    </div>
    		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div>
	//-->

    <div class="rounddiv_footer">
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div id="Footer">
			    <?php include(FFB_VIEWER_PATH.'footer.php')?>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div>

</div>
