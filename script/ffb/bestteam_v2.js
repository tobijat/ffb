var _matchround = new Array();
var _matchround_list = new Array();
var _user_lineup = new Array();
var _matchround_select_index = -1;
var _team_selected_type = '';
var _display_right = 'stats'; // || "matches"

function initMyteam(user_id) {
    dispLoadbarRoundchange();
    dispWaitMessage('lineup_select_round', 'Lade Spielrunden..');
    loadMatchrounds();
}

function loadMatchrounds() {
    var url = server + 'ffb/matchround/getPastMatchrounds_v2.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;

        //alert(response.responseText);

 		var matchround_ids = xmlResponse.getElementsByTagName('matchround_id');
 		//alert(matchround_ids.length);
 		if(matchround_ids.length <= 0) {
 		    //alert('no matchrounds');
 		    dispNoMatchroundsAvailable();
 		    return;
 		}
 		var matchround_titles = xmlResponse.getElementsByTagName('matchround_title');
 		var matchround_starts = xmlResponse.getElementsByTagName('matchround_startdate');
 		var matchround_ends = xmlResponse.getElementsByTagName('matchround_enddate');
 		var matchround_actuals = xmlResponse.getElementsByTagName('matchround_actual');
 		var matchround_runnings = xmlResponse.getElementsByTagName('matchround_running');
 		var matchround_matches = xmlResponse.getElementsByTagName('matches');

 		for(var i=0; i<matchround_ids.length; i++) {
 		    _matchround_list[i] = new Object;
            _matchround_list[i]['matchround_id'] = matchround_ids[i].firstChild.nodeValue;
            _matchround_list[i]['matchround_title'] = matchround_titles[i].firstChild.nodeValue;
 		    _matchround_list[i]['matchround_start'] = matchround_starts[i].firstChild.nodeValue;
 		    _matchround_list[i]['matchround_end'] = matchround_ends[i].firstChild.nodeValue;
            _matchround_list[i]['matchround_actual'] = matchround_actuals[i].firstChild.nodeValue;
            _matchround_list[i]['matchround_running'] = matchround_runnings[i].firstChild.nodeValue;
            _matchround_list[i]['matchround_matches'] = matchround_matches[i].getElementsByTagName('XML_Serializer_Tag');
            if(_matchround_list[i]['matchround_actual'] == 1) {
                _matchround = _matchround_list[i];
                _matchround_select_index = i;
            }
            _user_lineup[i] = new Object();
        }

        dispMatchroundDetails();
        dispInfoRight();
        dispMatchroundSelection();

        disableSelection();
        dispTeamSelection();
        loadLineup();
        return;

    },
		onFailure : function(response) {
    	handleAjaxError();
 		}
	});
}

function dispMatchroundSelection() {
    var selected = '';
    var string = '';
    string += '<select class="ffb_select" id="matchround_selection" size="1" onchange="javascript:changeMatchroundSelection();">';
    string += '<option class="ffb_select_1" disabled selected>Spielrunde..</option>';

    for(var i=0; i<_matchround_list.length; i++) {
        if(_matchround_list[i]['matchround_actual'] == 1) {
            selected = 'selected="selected" ';
        }
        string += '<option ' + selected + 'class="ffb_select_' + i%2 + '" value="' + i + '">';
        string += _matchround_list[i]['matchround_title'];
        string += '</option>';
        selected = '';
    }

    string += '</select>';
    dropLineW3('lineup_select_round', string);

    return;
}

function dispTeamSelection() {
    var string = '';
    string += '<select class="ffb_select" id="user_selection" size="1" onchange="javascript:changeTeamSelection();">';
    string += '<option class="ffb_select_1" disabled selected>Team..</option>';
    string += '<option selected="selected" class="ffb_select_0" value="top">Top-Team der Runde</option>';
    string += '<option class="ffb_select_1" value="flop">Flop-Team der Runde</option>';
    string += '</option>';
    string += '</select>';

    _team_selected_type = 'top';

    dropLineW3('lineup_select_user', string);

    return;
}

function changeMatchroundSelection() {
    disableSelection();
    dispLoadbarRoundchange();
    var select = document.getElementById('matchround_selection');
    var index = select.options[select.options.selectedIndex].value;
    _matchround_select_index = index;
    _matchround = _matchround_list[index];

    dispMatchroundDetails();
    loadLineup();

    dispInfoRight();

    return;
}

function changeTeamSelection() {
    if(_display_right == 'stats') {
        dropLineW3('matchlist', '');
    }
    disableSelection();
    dispLoadbarUserchange();
    var select = document.getElementById('user_selection');
    var type = select.options[select.options.selectedIndex].value;
    _team_selected_type = type;

    loadLineup();

    dispInfoRight();

    return;
}

function loadLineup() {
    if(_team_selected_type == '') {
        dispMessage('Bitte ein Team ausw&auml;hlen!');
        activateSelection();
        return;
    }

    if(!_user_lineup[_matchround_select_index][_team_selected_type]) {
        //alert('load LINUP from SERVER!');
    	var url = server + 'ffb/bestteam/getBestTeam.xml';
    	new Ajax.Request(url, {
     		onSuccess : function(response) {

     		//alert(response.responseText);

     		var xmlResponse=response.responseXML;
     		var players = xmlResponse.getElementsByTagName('players')[0].getElementsByTagName('XML_Serializer_Tag');
     		var userteam = xmlResponse.getElementsByTagName('userteams');

            _user_lineup[_matchround_select_index][_team_selected_type] = new Object();
            _user_lineup[_matchround_select_index][_team_selected_type]['players'] = new Array();
     		for(var i=0; i<players.length; i++) {
                _user_lineup[_matchround_select_index][_team_selected_type]['players'][i] = new Object();
                _user_lineup[_matchround_select_index][_team_selected_type]['players'][i]['player_fname'] = players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
                _user_lineup[_matchround_select_index][_team_selected_type]['players'][i]['player_lname'] = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue;

				_user_lineup[_matchround_select_index][_team_selected_type]['players'][i]['player_nationality'] = players[i].getElementsByTagName('player_nationality')[0].firstChild.nodeValue;
                _user_lineup[_matchround_select_index][_team_selected_type]['players'][i]['player_status'] = players[i].getElementsByTagName('player_status')[0].firstChild.nodeValue;
                if(players[i].getElementsByTagName('player_status_description')[0].firstChild.nodeValue) {
                    _user_lineup[_matchround_select_index][_team_selected_type]['players'][i]['player_status_description'] = players[i].getElementsByTagName('player_status_description')[0].firstChild.nodeValue;
                }

                _user_lineup[_matchround_select_index][_team_selected_type]['players'][i]['playerteam_id'] = players[i].getElementsByTagName('playerteam_id')[0].firstChild.nodeValue;
                _user_lineup[_matchround_select_index][_team_selected_type]['players'][i]['playerteam_team_id'] = players[i].getElementsByTagName('playerteam_team_id')[0].firstChild.nodeValue;
                _user_lineup[_matchround_select_index][_team_selected_type]['players'][i]['playerteam_team_nationality'] = players[i].getElementsByTagName('playerteam_team_nationality')[0].firstChild.nodeValue;
                _user_lineup[_matchround_select_index][_team_selected_type]['players'][i]['playerteam_team'] = players[i].getElementsByTagName('playerteam_team')[0].firstChild.nodeValue;
                _user_lineup[_matchround_select_index][_team_selected_type]['players'][i]['playerteam_player_position'] = players[i].getElementsByTagName('playerteam_player_position')[0].firstChild.nodeValue;
                _user_lineup[_matchround_select_index][_team_selected_type]['players'][i]['playerteam_status'] = players[i].getElementsByTagName('playerteam_status')[0].firstChild.nodeValue;
                _user_lineup[_matchround_select_index][_team_selected_type]['players'][i]['playerteam_player_price'] = players[i].getElementsByTagName('playerteam_player_price')[0].firstChild.nodeValue;
                //_user_lineup[_matchround_select_index][_team_selected_type]['players'][i]['player_grade'] = players[i].getElementsByTagName('player_grade')[0].firstChild.nodeValue;
                _user_lineup[_matchround_select_index][_team_selected_type]['players'][i]['playerstats_score'] = players[i].getElementsByTagName('playerstats_score')[0].firstChild.nodeValue;

			}
            _user_lineup[_matchround_select_index][_team_selected_type]['userteam'] = new Object();
            //_user_lineup[_matchround_select_index][_team_selected_type]['userteam']['userteam_id'] = userteam[0].getElementsByTagName('userteam_id')[0].firstChild.nodeValue;
            //_user_lineup[_matchround_select_index][_team_selected_type]['userteam']['userteam_matchround_id'] = userteam[0].getElementsByTagName('userteam_matchround_id')[0].firstChild.nodeValue;
            _user_lineup[_matchround_select_index][_team_selected_type]['userteam']['userteam_score'] = userteam[0].getElementsByTagName('userteam_score')[0].firstChild.nodeValue;
            _user_lineup[_matchround_select_index][_team_selected_type]['userteam']['userteam_price'] = userteam[0].getElementsByTagName('userteam_price')[0].firstChild.nodeValue;
            //_user_lineup[_matchround_select_index][_team_selected_type]['userteam']['userteam_username'] = userteam[0].getElementsByTagName('userteam_username')[0].firstChild.nodeValue;

            activateSelection();
            dispTeamDetails();
            dispLineup();
    	    },
    		onFailure : function(response) {
    		   handleAjaxError();
     		},
    		parameters: '?matchround_id=' + _matchround['matchround_id'] + '&type=' + _team_selected_type
    	});
    } else {
        //alert('load LINUP from ARRAY!');
        activateSelection();
        dispTeamDetails();
        dispLineup();
    }

    return;
}


function dispLineup() {
	//alert('LINEUP!!');

    var tmpGoalieString = '';
    var tmpDefenceString = '';
    var tmpMidfieldString = '';
    var tmpStrikerString = '';
    var num_g = 0;
    var num_d = 0;
    var num_m = 0;
    var num_s = 0;
    var score_g = 0;
    var score_d = 0;
    var score_m = 0;
    var score_s = 0;

    var lineuplist = _user_lineup[_matchround_select_index][_team_selected_type]['players'];

	for(var i=0; i<lineuplist.length; i++) {
        var player_string = formPlayerElement(lineuplist[i]);
        if(lineuplist[i]['playerteam_player_position'] == 'g') {
            tmpGoalieString += player_string;
            num_g++;
            score_g += lineuplist[i]['playerstats_score'];
        }
        if(lineuplist[i]['playerteam_player_position'] == 'd') {
            tmpDefenceString += player_string;
            num_d++;
            score_d += lineuplist[i]['playerstats_score'];
        }
        if(lineuplist[i]['playerteam_player_position'] == 'm') {
            tmpMidfieldString += player_string;
            num_m++;
            score_m += lineuplist[i]['playerstats_score'];
        }
        if(lineuplist[i]['playerteam_player_position'] == 's') {
            tmpStrikerString += player_string;
            num_s++;
            score_s += lineuplist[i]['playerstats_score'];
        }
	}

	dropLineW3('line_elements_g', tmpGoalieString);
    dropLineW3('line_elements_d', tmpDefenceString);
    dropLineW3('line_elements_m', tmpMidfieldString);
    dropLineW3('line_elements_s', tmpStrikerString);

	return;
}


function formPlayerElement(player) {
    var flagImage = new Image();
    flagImage.src = server + flagImages_ + player['playerteam_team_nationality'].toLowerCase() + '.gif';
    if(flagImage.height == 11 && flagImage.width == 16) {
        var dim_string = 'height="11px"';
        var pad_string = ' style="padding-top:2px;"';
    } else {
        var dim_string = 'height="16px"';
        var pad_string = '';
    }
    var player_string = '';
    player_string += '<div id="line_element">';
    player_string += '<a href="javascript:void(0);" onClick="javascript:dispPlayerinfoPopup(' + player['playerteam_id'] + ');">';
    player_string += '<img src="' + server + shirtImages_ + 'shirt_'+ player['playerteam_team_nationality'] + '.png" width="55" height="50" border="0" title="Klicken f&uuml;r Spielerinfos"><br>';
    player_string += '</a>';
    player_string += '<b>' + player['player_fname'] + '<br>' + player['player_lname'] + '</b>';

    player_string += '<a href="javascript:void(0);" onClick="javascript:dispPlayerstatsPopup(';
    player_string += player['playerteam_id'] + ',';
    player_string += _matchround['matchround_id'];
    player_string += ');">';
    player_string += '<div class="scores" title="Klicken f&uuml;r Punktedetails">';
    player_string += '<span style="background-color:#0000FF; padding-left:3px; padding-right:3px;">';
    player_string += player['playerstats_score'];
    player_string += ' Punkte</span></div></a>';

    player_string += '<div class="infosymbols" id="infosymbols">';
    player_string += '<div class="nationality"' + pad_string + '><img src="' + server + flagImages_;
	player_string += player['playerteam_team_nationality'].toLowerCase();
	player_string += '.gif" ' + dim_string + ' title="' + player['playerteam_team'];
	player_string += '"></div>';
	player_string += '<div class="status"><img src="' + server + symbolImages_;
	if(player['player_status'] == 1) {
		player_string += 'status_pos.png" width="16px" title="status: Einsatzbereit">';
	} else {
		player_string += 'status_hurt.png" width="16px" title="status: ' +
		player['player_status_description'] + '">';
	}
	player_string += '</div><div class="status">';
    player_string += '<a href="javascript:void(0);" onClick="javascript:dispPlayerinfoPopup(' + player['playerteam_id'] + ');" title="Klicken f&uuml;r Spielerinfos">';
    player_string += '<img border="0" src="' + server + symbolImages_ + 'info.png" width="16px">';
    player_string += '</a></div>';
    player_string += '<div style="clear:both;"></div>';
    player_string += '</div>';
    player_string += '</div>';

    return player_string;
}

function dispMatchroundDetails() {
    var string = '';
    string += _matchround['matchround_title'];
    if(_matchround['matchround_start'] == _matchround['matchround_end']) {
        string += '<br><span style="font-size:9pt;"><em>' + _matchround['matchround_start'] + '</em></span>';
    } else {
        string += '<br><span style="font-size:9pt;"><em>' + _matchround['matchround_start'] + ' bis ' + _matchround['matchround_end'] + '</em></span>';
    }

    dropLineW3('lineup_infoarea_title', string);
    return;
}

function dispTeamDetails() {
    var price = Math.round(parseFloat(_user_lineup[_matchround_select_index][_team_selected_type]['userteam']['userteam_price'])*10)/10;
    var score = _user_lineup[_matchround_select_index][_team_selected_type]['userteam']['userteam_score'];
    var string = '';
    if(_team_selected_type == 'top') {
    	string += 'TOP Team der Runde';
    } else if(_team_selected_type == 'flop') {
    	string += 'FLOP Team der Runde';
    }
    dropLineW3('lineup_infoarea_user', string);

    var string = '';
    string += '<div id="lineup_infoarea_credits_img"><img src="' + server + symbolImages_ + 'symbol_credits.png" width="40px"></div>';
    string += '<div id="lineup_infoarea_credits_txt">' + price + '</div>';
    string += '<div style="clear:both;"></div>';
    dropLineW3('lineup_infoarea_credits', string);

    var string = '';
    string += '<div id="lineup_infoarea_score_img"><img src="' + server + symbolImages_ + 'symbol_score.png" width="40px"></div>';
    string += '<div id="lineup_infoarea_score_txt">' + score + '</div>';
    string += '<div style="clear:both;"></div>';
    dropLineW3('lineup_infoarea_score', string);
    return;
}

function disableSelection() {
    var round_select = document.getElementById('matchround_selection');
    var user_select = document.getElementById('user_selection');
    if(round_select) {
        round_select.disabled = true;
    }
    if(user_select) {
        user_select.disabled = true;
    }
    return;
}

function activateSelection() {
    var round_select = document.getElementById('matchround_selection');
    var user_select = document.getElementById('user_selection');
    if(round_select) {
        round_select.disabled = false;
    }
    if(user_select) {
        user_select.disabled = false;
    }
    return;
}

function addErrorMessage(error) {
    var div = document.getElementById('lineup_infoarea_credits');
    div.style.visibility = 'hidden';
    div = document.getElementById('lineup_infoarea_score');
    div.style.visibility = 'hidden';

    var div = document.getElementById('lineup_infoarea_infos');
    div.style.backgroundColor = '#FF0000';
    div.innerHTML += '<img src="' + server + symbolImages_ + 'symb_err_anim.gif" height="11px">&ensp;<b>' + error +
					 '</b>&ensp;<img src="' + server + symbolImages_ + 'symb_err_anim.gif" height="11px"><br />';
    return;
}

function removeErrorMessages() {
    var div = document.getElementById('lineup_infoarea_infos');
    div.style.backgroundColor = '';
    dropLineW3('lineup_infoarea_infos', '');
    return;
}

function dispNoMatchroundsAvailable() {
    removeErrorMessages();
    var string = 'Keine Spielrunden vorhanden! Bitte sp&auml;ter nochmal probieren!';
    addErrorMessage(string);
    dropLineW3('line_elements_g', '');
	dropLineW3('line_elements_d', '');
	dropLineW3('line_elements_m', '');
	dropLineW3('line_elements_s', '');
	dropLineW3('lineup_infoarea_title', '');
	dropLineW3('lineup_infoarea_user', '');
	dropLineW3('Mainright', '');
}

function dispMessage(string) {
    dropLineW3('line_elements_g', '');
	dropLineW3('line_elements_d', '');
	dropLineW3('line_elements_m', '<b>'+string+'</b>');
	dropLineW3('line_elements_s', '');
	dropLineW3('lineup_infoarea_user', '');
}

function dispLoadbarUserchange() {
    var field = '<img src="' + server + images_ + 'loading/ajax-loader-medium.gif" title="Laden..." alt="Laden..."></img>';
    dispWaitMessage('lineup_infoarea_user', 'Lade Aufstellung..');
    dropLineW3('line_elements_g', '');
	dropLineW3('line_elements_d', '');
	dropLineW3('line_elements_m', field);
	dropLineW3('line_elements_s', '');
	dropLineW3('lineup_infoarea_credits', '');
	dropLineW3('lineup_infoarea_score', '');
}

function dispLoadbarRoundchange() {
    dispLoadbarUserchange();
    dispWaitMessage('matchlist', 'Lade Spiele..');
    dispWaitMessage('lineup_infoarea_title', 'Lade Spielrunde..');
}

function dispWaitMessage(div, text) {
    var image = '<img src=' + server + images_ + 'loading/ajax-loader-in-progress.gif height="10px">';
    var string = '<div style="width:100%; font-size:10pt; text-align:center;">' + image + '&ensp;' + text + '</div>';
    dropLineW3(div, string);
}

function dispInfoRight() {
    if(_display_right == 'matches') {
        dispTabMatches();
        return;
    } else if(_display_right == 'stats' && _matchround['matchround_running'] == 0) {
        dispTabStats();
        return;
    }
    dispTabMatches();
    return;
}

function dispTabMatches() {
    _display_right = 'matches';
    var matchlist_tabs = '<div id="stats_tabs_line">';
    matchlist_tabs += '<div id="stats_tabs_left"><a href="javascript:void(0);" onClick="javascript:dispTabMatches();">Spiele anzeigen</a></div>';
    matchlist_tabs += '<div id="stats_tabs_right"><a href="javascript:void(0);" onClick="javascript:dispTabStats();">Statistiken anzeigen</a></div>';
    matchlist_tabs += '<div style="clear:both;"></div>';
    matchlist_tabs += '</div>';
    dispMatches(_matchround_list[_matchround_select_index]['matchround_matches'], matchlist_tabs);
    return;
}

function dispTabStats() {
    _display_right = 'stats';

    var matchlist_tabs = '<div id="stats_tabs_line">';
    matchlist_tabs += '<div id="stats_tabs_left"><a href="javascript:void(0);" onClick="javascript:dispTabMatches();">Spiele anzeigen</a></div>';
    matchlist_tabs += '<div id="stats_tabs_right"><a href="javascript:void(0);" onClick="javascript:dispTabStats();">Statistiken anzeigen</a></div>';
    matchlist_tabs += '<div style="clear:both;"></div>';
    matchlist_tabs += '</div>';
    dispRoundStatistics(_matchround['matchround_id'], matchlist_tabs);

    return;
}