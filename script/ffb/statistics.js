var _stats_user = new Array();
var _stats_round = new Array();

function dispStatistics(matchround_id, user_id, text) {
    text += '<div id="stats_round"></div>';
    text += '<div id="stats_user"></div>';
    dropLineW3('matchlist', text);

    loadRoundStats(matchround_id, user_id);
    if(user_id > 0) {
        loadUserStats(matchround_id, user_id);
    }

    return;
}

function dispRoundStatistics(matchround_id, text) {
    text += '<div id="stats_round"></div>';
    dropLineW3('matchlist', text);

    loadRoundStats(matchround_id, 0);

    return;
}

function loadUserStats(matchround_id, user_id) {
    if(_stats_user[user_id]) {
        if(_stats_user[user_id][matchround_id]) {
            dispUserStats(matchround_id, user_id);
            return;
        }
    } else {
        _stats_user[user_id] = new Object();
    }
    var url = server + 'ffb/statistics/getUserStats.xml';
    new Ajax.Request(url, {
     	onSuccess : function(response) {
     	var xmlResponse=response.responseXML;

     	//alert(response.responseText);

        _stats_user[user_id][matchround_id] = new Object();
        _stats_user[user_id][matchround_id]['goals'] = xmlResponse.getElementsByTagName('goals')[0].firstChild.nodeValue;
        _stats_user[user_id][matchround_id]['owngoals'] = xmlResponse.getElementsByTagName('owngoals')[0].firstChild.nodeValue;
        _stats_user[user_id][matchround_id]['cards_r'] = xmlResponse.getElementsByTagName('cards_r')[0].firstChild.nodeValue;
        _stats_user[user_id][matchround_id]['cards_yr'] = xmlResponse.getElementsByTagName('cards_yr')[0].firstChild.nodeValue;
        _stats_user[user_id][matchround_id]['cards_y'] = xmlResponse.getElementsByTagName('cards_y')[0].firstChild.nodeValue;
        _stats_user[user_id][matchround_id]['minutes'] = xmlResponse.getElementsByTagName('minutes')[0].firstChild.nodeValue;
        _stats_user[user_id][matchround_id]['system'] = xmlResponse.getElementsByTagName('system')[0].firstChild.nodeValue;
        _stats_user[user_id][matchround_id]['price'] = xmlResponse.getElementsByTagName('price')[0].firstChild.nodeValue;
        _stats_user[user_id][matchround_id]['score'] = xmlResponse.getElementsByTagName('score')[0].firstChild.nodeValue;
        _stats_user[user_id][matchround_id]['score_g'] = xmlResponse.getElementsByTagName('score_g')[0].firstChild.nodeValue;
        _stats_user[user_id][matchround_id]['score_d'] = xmlResponse.getElementsByTagName('score_d')[0].firstChild.nodeValue;
        _stats_user[user_id][matchround_id]['score_m'] = xmlResponse.getElementsByTagName('score_m')[0].firstChild.nodeValue;
        _stats_user[user_id][matchround_id]['score_s'] = xmlResponse.getElementsByTagName('score_s')[0].firstChild.nodeValue;
        _stats_user[user_id][matchround_id]['score_per_player'] = xmlResponse.getElementsByTagName('score_per_player')[0].firstChild.nodeValue;
        _stats_user[user_id][matchround_id]['credits_per_point'] = xmlResponse.getElementsByTagName('credits_per_point')[0].firstChild.nodeValue;

        //alert(_stats_user[user_id][matchround_id]['score']);

        dispUserStats(matchround_id, user_id);
        return;

    	},
    	onFailure : function(response) {
        handleAjaxError();
     	},
     	parameters: '?matchround_id=' + matchround_id + '&user_id=' + user_id
    });
}

function loadRoundStats(matchround_id) {
    if(_stats_round[matchround_id]) {
        dispRoundStats(matchround_id);
        return;
    }
    var url = server + 'ffb/statistics/getRoundStats.xml';
    new Ajax.Request(url, {
     	onSuccess : function(response) {
     	var xmlResponse=response.responseXML;

     	//alert(response.responseText);

        _stats_round[matchround_id] = new Object();
        _stats_round[matchround_id]['goals'] = xmlResponse.getElementsByTagName('goals')[0].firstChild.nodeValue;
        _stats_round[matchround_id]['owngoals'] = xmlResponse.getElementsByTagName('owngoals')[0].firstChild.nodeValue;
        _stats_round[matchround_id]['cards_r'] = xmlResponse.getElementsByTagName('cards_r')[0].firstChild.nodeValue;
        _stats_round[matchround_id]['cards_yr'] = xmlResponse.getElementsByTagName('cards_yr')[0].firstChild.nodeValue;
        _stats_round[matchround_id]['cards_y'] = xmlResponse.getElementsByTagName('cards_y')[0].firstChild.nodeValue;
        _stats_round[matchround_id]['minutes'] = xmlResponse.getElementsByTagName('minutes')[0].firstChild.nodeValue;
        _stats_round[matchround_id]['score'] = xmlResponse.getElementsByTagName('score')[0].firstChild.nodeValue;
        _stats_round[matchround_id]['price'] = xmlResponse.getElementsByTagName('credits')[0].firstChild.nodeValue;
        _stats_round[matchround_id]['num_users'] = xmlResponse.getElementsByTagName('num_users')[0].firstChild.nodeValue;
        _stats_round[matchround_id]['num_players'] = xmlResponse.getElementsByTagName('num_players')[0].firstChild.nodeValue;
        _stats_round[matchround_id]['num_matches'] = xmlResponse.getElementsByTagName('num_matches')[0].firstChild.nodeValue;
        _stats_round[matchround_id]['score_per_player'] = xmlResponse.getElementsByTagName('score_per_player')[0].firstChild.nodeValue;
        _stats_round[matchround_id]['credits_per_point'] = xmlResponse.getElementsByTagName('credits_per_point')[0].firstChild.nodeValue;
        if(xmlResponse.getElementsByTagName('top_of_round')[0].firstChild.nodeValue != 0) {
            _stats_round[matchround_id]['top_player'] = xmlResponse.getElementsByTagName('top_of_round')[0];
        } else {
            _stats_round[matchround_id]['top_player'] = 0;
        }
        if(xmlResponse.getElementsByTagName('flop_of_round')[0].firstChild.nodeValue != 0) {
            _stats_round[matchround_id]['flop_player'] = xmlResponse.getElementsByTagName('flop_of_round')[0];
        } else {
            _stats_round[matchround_id]['flop_player'] = 0;
        }

        dispRoundStats(matchround_id);
        return;

    	},
    	onFailure : function(response) {
        handleAjaxError();
     	},
     	parameters: '?matchround_id=' + matchround_id
    });
}

function dispRoundStats(matchround_id) {
    var string = '';
    string += '<div id="stats_main">';
    string += '<div id="stats_line_title">';
    string += '<b>-- Spielrunden Statistik --</b>';
    string += '</div>';

    if(_stats_round[matchround_id]['top_player']) {
        string += '<div id="stats_round_line_title">';
        string += '<b><u>Der TOP Spieler der Runde</u></b>';
        string += '</div>';
        string += '<div id="stats_line">';
        string += '<div id="stats_round_top">';
        string += '<img src="' + server + symbolImages_ + 'stats_top.png" width="16px" height="16px"><b>';
        string += '<a class="nolink" title="Klicken f&uuml;r Spielerinfos" href="javascript:void(0);" onClick="javascript:dispPlayerinfoPopup(';
        string += _stats_round[matchround_id]['top_player'].getElementsByTagName('top_playerteam_id')[0].firstChild.nodeValue + ');">';
        string += _stats_round[matchround_id]['top_player'].getElementsByTagName('top_player_name')[0].firstChild.nodeValue + '</b></a> (<em>';
        string += _stats_round[matchround_id]['top_player'].getElementsByTagName('top_team_name')[0].firstChild.nodeValue + '</em>, ';
        string += _stats_round[matchround_id]['top_player'].getElementsByTagName('top_score')[0].firstChild.nodeValue + ' Punkte)';
        string += '</div>';
        string += '</div>';
    }
    if(_stats_round[matchround_id]['flop_player']) {
        string += '<div id="stats_round_line_title">';
        string += '<b><u>Der FLOP Spieler der Runde</u></b>';
        string += '</div>';
        string += '<div id="stats_line">';
        string += '<div id="stats_round_top">';
        string += '<img src="' + server + symbolImages_ + 'stats_flop.png" width="16px" height="16px"><b>';
        string += '<a class="nolink" title="Klicken f&uuml;r Spielerinfos" href="javascript:void(0);" onClick="javascript:dispPlayerinfoPopup(';
        string += _stats_round[matchround_id]['flop_player'].getElementsByTagName('flop_playerteam_id')[0].firstChild.nodeValue + ');">';
        string += _stats_round[matchround_id]['flop_player'].getElementsByTagName('flop_player_name')[0].firstChild.nodeValue + '</b></a> (<em>';
        string += _stats_round[matchround_id]['flop_player'].getElementsByTagName('flop_team_name')[0].firstChild.nodeValue + '</em>, ';
        string += _stats_round[matchround_id]['flop_player'].getElementsByTagName('flop_score')[0].firstChild.nodeValue + ' Punkte)';
        string += '</div>';
        string += '</div>';
    }

    string += '<div id="stats_round_line_title">';
    string += '<b><u>Statistik</u></b>';
    string += '</div>';
    string += '<div id="stats_line">';
    string += '<div id="stats_round_symbol"><img src="' + server + symbolImages_ + 'symbol_user.png" width="16px" height="16px"></div>';
    string += '<div id="stats_round_descr">Teilnehmer:</div>';
    string += '<div id="stats_round_value"><b>' + _stats_round[matchround_id]['num_users'] + ' Mitspieler</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    string += '<div id="stats_line">';
    string += '<div id="stats_round_symbol"><img src="' + server + symbolImages_ + 'stats_point.png" width="16px" height="16px"></div>';
    string += '<div id="stats_round_descr">Anzahl Spiele:</div>';
    string += '<div id="stats_round_value"><b>' + _stats_round[matchround_id]['num_matches'] + ' Spiele</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    string += '<div id="stats_line">';
    string += '<div id="stats_round_symbol"><img src="' + server + symbolImages_ + 'stats_goal.gif" width="16px" height="16px"></div>';
    string += '<div id="stats_round_descr">gefallene Tore:</div>';
    string += '<div id="stats_round_value"><b>' + _stats_round[matchround_id]['goals'] + ' Tore</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    string += '<div id="stats_line">';
    string += '<div id="stats_round_symbol"><img src="' + server + symbolImages_ + 'stats_owngoal.gif" width="16px" height="16px"></div>';
    string += '<div id="stats_round_descr">gefallene Eigentore:</div>';
    string += '<div id="stats_round_value"><b>' + _stats_round[matchround_id]['owngoals'] + ' Tore</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    string += '<div id="stats_line">';
    string += '<div id="stats_round_symbol"><img src="' + server + symbolImages_ + 'stats_card_yr.gif" width="16px" height="16px"></div>';
    string += '<div id="stats_round_descr">Karten (G/GR/R):</div>';
    string += '<div id="stats_round_value"><b>' + _stats_round[matchround_id]['cards_y']+'/'+_stats_round[matchround_id]['cards_yr']+'/'+_stats_round[matchround_id]['cards_r'] + '</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    string += '<div id="stats_line">';
    string += '<div id="stats_round_symbol"><img src="' + server + symbolImages_ + 'stats_point.png" width="16px" height="16px"></div>';
    string += '<div id="stats_round_descr">Punkte pro Spieler:</div>';
    string += '<div id="stats_round_value"><b>' + _stats_round[matchround_id]['score_per_player'] + ' Punkte</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    /*
    string += '<div id="stats_line">';
    string += '<div id="stats_round_symbol"><img src="' + server + symbolImages_ + 'symbol_credits.png" width="16px" height="16px"></div>';
    string += '<div id="stats_round_descr">Credits pro Punkt:</div>';
    string += '<div id="stats_round_value"><b>' + _stats_round[matchround_id]['credits_per_point'] + ' Credits</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    */

    string += '</div>';

    dropLineW3('stats_round', string);
}

function dispUserStats(matchround_id, user_id) {
    var string = '';
    string += '<div id="stats_main">';
    string += '<div id="stats_line_title">';
    string += '<b>-- Benutzer Statistik --</b>';
    string += '</div>';

    string += '<div id="stats_line">';
    string += '<div id="stats_user_symbol"><img src="' + server + symbolImages_ + 'stats_lineup.png" width="16px" height="16px"></div>';
    string += '<div id="stats_user_descr">Spielsystem:</div>';
    string += '<div id="stats_user_value"><b>' + _stats_user[user_id][matchround_id]['system'] + '</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    string += '<div id="stats_line">';
    string += '<div id="stats_user_symbol"><img src="' + server + symbolImages_ + 'stats_goal.gif" width="16px" height="16px"></div>';
    string += '<div id="stats_user_descr">erzielte Tore:</div>';
    string += '<div id="stats_user_value"><b>' + _stats_user[user_id][matchround_id]['goals'] + ' Tore</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    string += '<div id="stats_line">';
    string += '<div id="stats_user_symbol"><img src="' + server + symbolImages_ + 'stats_owngoal.gif" width="16px" height="16px"></div>';
    string += '<div id="stats_user_descr">erzielte Eigentore:</div>';
    string += '<div id="stats_user_value"><b>' + _stats_user[user_id][matchround_id]['owngoals'] + ' Tore</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    string += '<div id="stats_line">';
    string += '<div id="stats_user_symbol"><img src="' + server + symbolImages_ + 'stats_card_yr.gif" width="16px" height="16px"></div>';
    string += '<div id="stats_user_descr">Karten (G/GR/R):</div>';
    string += '<div id="stats_user_value"><b>' + _stats_user[user_id][matchround_id]['cards_y']+'/'+_stats_user[user_id][matchround_id]['cards_yr']+'/'+_stats_user[user_id][matchround_id]['cards_r'] + '</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    string += '<div id="stats_line">';
    string += '<div id="stats_user_symbol"><img src="' + server + symbolImages_ + 'stats_point.png" width="16px" height="16px"></div>';
    string += '<div id="stats_user_descr">Punkte Abwehr:</div>';
    string += '<div id="stats_user_value"><b>' + (parseInt(_stats_user[user_id][matchround_id]['score_g'])+parseInt(_stats_user[user_id][matchround_id]['score_d'])) + ' Punkte</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    string += '<div id="stats_line">';
    string += '<div id="stats_user_symbol"><img src="' + server + symbolImages_ + 'stats_point.png" width="16px" height="16px"></div>';
    string += '<div id="stats_user_descr">Punkte Mittelfeld:</div>';
    string += '<div id="stats_user_value"><b>' + _stats_user[user_id][matchround_id]['score_m'] + ' Punkte</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    string += '<div id="stats_line">';
    string += '<div id="stats_user_symbol"><img src="' + server + symbolImages_ + 'stats_point.png" width="16px" height="16px"></div>';
    string += '<div id="stats_user_descr">Punkte Angriff:</div>';
    string += '<div id="stats_user_value"><b>' + _stats_user[user_id][matchround_id]['score_s'] + ' Punkte</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    string += '<div id="stats_line">';
    string += '<div id="stats_user_symbol"><img src="' + server + symbolImages_ + 'stats_point.png" width="16px" height="16px"></div>';
    string += '<div id="stats_user_descr">Punkte pro Spieler:</div>';
    string += '<div id="stats_user_value"><b>' + _stats_user[user_id][matchround_id]['score_per_player'] + ' Punkte</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';
    string += '<div id="stats_line">';
    string += '<div id="stats_user_symbol"><img src="' + server + symbolImages_ + 'symbol_credits.png" width="16px" height="16px"></div>';
    string += '<div id="stats_user_descr">Credits pro Punkt:</div>';
    string += '<div id="stats_user_value"><b>' + _stats_user[user_id][matchround_id]['credits_per_point'] + ' Credits</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';

    string += '</div>';

    dropLineW3('stats_user', string);
}