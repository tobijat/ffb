//constants
var _options = new Array();
var _matches = new Array();
var _teams = new Array();
var _matchround = new Array();
var _playerlist = new Array();
var _lineuplist = new Array();
var _credits = 0.0;
var _playersDivs;

function initLineup() {
	//retrieve options
	dispWaitMessage('lineup_infoarea_infos', 'lade Spielrunde..');
	dispWaitMessage('matchlist', 'lade Spiele..');
	dispWaitMessage('lineup_select_team', 'lade Spielrunde..');
	retrieveOptions();
}

function retrieveOptions() {
    var url = server + 'ffb/options/getLineupOptions.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		var options = xmlResponse.getElementsByTagName('options');
 		_options['max_players'] = options[0].getElementsByTagName('lineup_max_players')[0].firstChild.nodeValue;
 		_options['max_credits'] = options[0].getElementsByTagName('lineup_max_credits')[0].firstChild.nodeValue;
 		_options['max_players_from_team'] = options[0].getElementsByTagName('lineup_max_players_team')[0].firstChild.nodeValue;
 		_options['min_goalie'] = options[0].getElementsByTagName('lineup_min_g')[0].firstChild.nodeValue;
 		_options['min_defence'] = options[0].getElementsByTagName('lineup_min_d')[0].firstChild.nodeValue;
 		_options['min_midfield'] = options[0].getElementsByTagName('lineup_min_m')[0].firstChild.nodeValue;
 		_options['min_striker'] = options[0].getElementsByTagName('lineup_min_s')[0].firstChild.nodeValue;
 		_options['max_goalie'] = options[0].getElementsByTagName('lineup_max_g')[0].firstChild.nodeValue;
 		_options['max_defence'] = options[0].getElementsByTagName('lineup_max_d')[0].firstChild.nodeValue;
 		_options['max_midfield'] = options[0].getElementsByTagName('lineup_max_m')[0].firstChild.nodeValue;
 		_options['max_striker'] = options[0].getElementsByTagName('lineup_max_s')[0].firstChild.nodeValue;

		loadMatchround();
		},

		 onFailure : function(response) {
    	   handleAjaxError();
 		}
	});
}

function loadMatchround() {
    var url = server + 'ffb/lineup/getMatchroundAndTeams.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
        var matchround = new Array();
 		var xmlResponse=response.responseXML;
        var num_matchrounds = xmlResponse.getElementsByTagName('matchround_id');
        if(num_matchrounds.length<=0) {
 		    dispNoRoundAvailable();
 		    return;
 		}

        var matches = xmlResponse.getElementsByTagName('matches')[0].getElementsByTagName('XML_Serializer_Tag');
        var teams = xmlResponse.getElementsByTagName('teams')[0].getElementsByTagName('XML_Serializer_Tag');
 		matchround['id'] = xmlResponse.getElementsByTagName('matchround_id')[0].firstChild.data;
 		matchround['title'] = xmlResponse.getElementsByTagName('matchround_title')[0].firstChild.data;
 		matchround['status'] = xmlResponse.getElementsByTagName('matchround_status')[0].firstChild.data;
 		matchround['startdate'] = xmlResponse.getElementsByTagName('matchround_startdate')[0].firstChild.data;
 		matchround['enddate'] = xmlResponse.getElementsByTagName('matchround_enddate')[0].firstChild.data;
 		matchround['deadline'] = xmlResponse.getElementsByTagName('matchround_deadline')[0].firstChild.data;

        _matchround = matchround;
 		_matches = matches;
 		_teams = teams;

        dispMatchroundDetails();

 		if(matchround['status']!=1 || matches.length<=0 || teams.length<=0) {
 		    dispRoundNotReady();
 		    return;
 		}

 		dispActionButtons();

        hideMatches();
    	dispTeamSelection();
    	getExistingLineup();

		},

		onFailure : function(response) {
    	   handleAjaxError();
 		}
	});
}

function dispTeamSelection() {
    var team_select = '';
	team_select += '<select class="ffb_select" id="team_selection" size="1" onchange="javascript:changeTeamSelection();"  >';
	team_select += '<option class="ffb_select_1" disabled selected>Mannschaft..</option>';
    for(var i=0;i<_teams.length;i++) {
        team_select += '<option class="ffb_select_' + i%2 + '" value="' + i + '" onmouseover="javascript:rectanglePlayers(' +
						'_teams[this.value].getElementsByTagName(\'team_id\')[0].firstChild.nodeValue, null);">';
        team_select += _teams[i].getElementsByTagName('team_name')[0].firstChild.nodeValue;
        team_select += ' (Preis: ' + _teams[i].getElementsByTagName('team_avg_price')[0].firstChild.nodeValue + ')';
        team_select += '</option>';
    }
    team_select += '</select>';
    dropLineW3('lineup_select_team', team_select);
    return;
}

function changeTeamSelection() {
    dispWaitMessage('lineup_select_player', 'lade Spielerliste..');
    var div = document.getElementById('team_selection');
    var index = div.options[div.options.selectedIndex].value;
    dispTeamDetails(index);
    var team_id = _teams[index].getElementsByTagName('team_id')[0].firstChild.nodeValue;
    var matchround_id = _matchround['id'];

	if(!_playerlist[team_id]) {
        var url = server + 'ffb/team/getTeamPlayers.xml';
    	new Ajax.Request(url, {
     		onSuccess : function(response) {
     		var xmlResponse=response.responseXML;

            //alert(response.responseText);

     		var players = xmlResponse.getElementsByTagName('XML_Serializer_Tag');

            _playerlist[team_id] = new Array();

     		for(var i=0;i<players.length;i++) {
     			_playerlist[team_id][i] = new Object();
     			//player id
     			_playerlist[team_id][i]['player_id'] = players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue;
     			//player first name
    			_playerlist[team_id][i]['player_fname'] = players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
     			//player last name
    			_playerlist[team_id][i]['player_lname'] = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue;
    			//nationality
    			_playerlist[team_id][i]['player_nationality'] = players[i].getElementsByTagName('player_nationality')[0].firstChild.nodeValue;
    			//player status
    			_playerlist[team_id][i]['player_status'] = players[i].getElementsByTagName('player_status')[0].firstChild.nodeValue;
    			//player status description

    			if(players[i].getElementsByTagName('player_status_description')[0].firstChild == null) {
    			    _playerlist[team_id][i]['player_status_description'] = '';
    			} else {
    			    _playerlist[team_id][i]['player_status_description'] = players[i].getElementsByTagName('player_status_description')[0].firstChild.nodeValue;
    			}

                //player position
    			_playerlist[team_id][i]['player_position'] = players[i].getElementsByTagName('playerteam_player_position')[0].firstChild.nodeValue;
    			//player game price
    			_playerlist[team_id][i]['player_price'] = parseFloat(players[i].getElementsByTagName('playerteam_player_price')[0].firstChild.nodeValue);
    			_playerlist[team_id][i]['player_grade'] = players[i].getElementsByTagName('player_grade')[0].firstChild.nodeValue;
    			_playerlist[team_id][i]['player_trend'] = players[i].getElementsByTagName('player_trend')[0].firstChild.nodeValue;
    			//player team
    			_playerlist[team_id][i]['playerteam_team_id'] = team_id;
    			_playerlist[team_id][i]['playerteam_team'] = players[i].getElementsByTagName('playerteam_team')[0].firstChild.nodeValue;
    			_playerlist[team_id][i]['playerteam_team_nationality'] = players[i].getElementsByTagName('playerteam_team_nationality')[0].firstChild.nodeValue;
    			//player team id
    			_playerlist[team_id][i]['playerteam_id'] = players[i].getElementsByTagName('playerteam_id')[0].firstChild.nodeValue;
    		}

            dispPlayerList(team_id);

    		},
    		onFailure : function(response) {
        		handleAjaxError();
     		},
    		parameters: '?id=' + team_id + '&matchround_id=' + matchround_id
    	});
	} else {
	   dispPlayerList(team_id);
	}
}

function dispPlayerList(team_id) {
	playerlist = new Array();
	playerlist = _playerlist[team_id];

	var selectTeamPlayer = '<div id="playerlist">';
    selectTeamPlayer += '<div id="playerline_none">';
    selectTeamPlayer += '<div id="playerlisttrend" title="Tendenz"><b>T.</b></div><div id="playerlistname" style="text-align:center;" title="Name"><b>Name</b></div><div id="playerlistprice" title="Preis"><b>Pr.</b></div><div id="playerliststatus" title="Status"><b>St.</b></div><div id="playerlistinfo" title="Status"><b>I.</b></div><div class="playergrade" title="Leistung"><b>Leistung</b></div></div>';
    selectTeamPlayer += '<div style="clear:both;"></div>';
    selectTeamPlayer_g = '<div id="playerline_pos"><b>Torh&uuml;ter</b></div>';
    selectTeamPlayer_d = '<div id="playerline_pos"><b>Verteidiger</b></div>';
    selectTeamPlayer_m = '<div id="playerline_pos"><b>Mittelfeldspieler</b></div>';
    selectTeamPlayer_s = '<div id="playerline_pos"><b>St&uuml;rmer</b></div>';
 	for(i=0;i<playerlist.length;i++) {
        if(playerlist[i]['player_position'] == 'g') {
            selectTeamPlayer_g += '<div id="playerline_' + i%2 + '">';
            selectTeamPlayer_g += formPlayerString(playerlist[i], i);
            selectTeamPlayer_g += '</div>';
        } else if(playerlist[i]['player_position'] == 'd') {
            selectTeamPlayer_d += '<div id="playerline_' + i%2 + '">';
            selectTeamPlayer_d += formPlayerString(playerlist[i], i);
            selectTeamPlayer_d += '</div>';
        } else if(playerlist[i]['player_position'] == 'm') {
            selectTeamPlayer_m += '<div id="playerline_' + i%2 + '">';
            selectTeamPlayer_m += formPlayerString(playerlist[i], i);
            selectTeamPlayer_m += '</div>';
        } else if(playerlist[i]['player_position'] == 's') {
            selectTeamPlayer_s += '<div id="playerline_' + i%2 + '">';
            selectTeamPlayer_s += formPlayerString(playerlist[i], i);
            selectTeamPlayer_s += '</div>';
        }
	}
	selectTeamPlayer += selectTeamPlayer_g + selectTeamPlayer_d + selectTeamPlayer_m + selectTeamPlayer_s;
	selectTeamPlayer += '</div>';

    dropLineW3('lineup_select_player', selectTeamPlayer);
    return;
}

function formPlayerString(player, index) {
	var player_string = '';
	player_string += '<div id="playerlisttrend">';
	var player_trend = parseInt(player['player_trend']);
	if(player_trend > 0 && player_trend <= 50) {
		player_string += '<img src="' + server + symbolImages_ + 'trend_u.png" width="10px" title="Tendenz: +'+player_trend+'%">';
	} else if(player_trend > 50 && player_trend <= 100) {
		player_string += '<img src="' + server + symbolImages_ + 'trend_uu.png" width="10px" title="Tendenz: +'+player_trend+'%">';
	} else if(player_trend < 0 && player_trend >= (-50)) {
		player_string += '<img src="' + server + symbolImages_ + 'trend_d.png" width="10px" title="Tendenz: '+player_trend+'%">';
	} else if(player_trend < (-50) && player_trend >= (-100)) {
		player_string += '<img src="' + server + symbolImages_ + 'trend_dd.png" width="10px" title="Tendenz: '+player_trend+'%">';
	} else {
		player_string += '&ensp;';
	}
	player_string += '</div>';
    player_string += '<div id="playerlistname">';
	player_string += '<a class="playerlink" href="javascript:void(0);" onClick="javascript:addPlayer('+player['playerteam_team_id']+','+index+');" ' +
						' onMouseOver="javascript:rectanglePlayers(' + player['playerteam_team_id'] +', \'\')">';
	player_string += player['player_fname'] + ' ' + player['player_lname'] + '</a></div>';
    player_string += '<div id="playerlistprice">' +	player['player_price'] + '</div>';
    player_string += '<div id="playerliststatus">' + '<img src="' + server + symbolImages_;
	if(player['player_status'] == 1)
		player_string += 'status_pos.png" width="16px" title="status: ok">';
	else {
		player_string += 'status_hurt.png" width="16px" title="status: ' + player['player_status_description'] + '">';
	}
    player_string += '</div>';
    player_string += '<div id="playerlistinfo">';
    player_string += '<a href="javascript:dispPlayerinfoPopup(' + player['playerteam_id'] + ');" title="Klicken f&uuml;r Spielerinfos">';
    player_string += '<img border="0" src="' + server + symbolImages_ + 'info.png"></a></div>';
    player_string += '<div class="playergrade">' + buildStars(player['player_grade'], 'list') + '</div>';

    player_string += '<div style="clear:both;"></div>';

	return player_string;
}

function dispTeamDetails(index) {
    var i = index;
    var string = '';
    string += '<img src="';
    string += server + flagImages_ + _teams[i].getElementsByTagName('team_nationality')[0].firstChild.nodeValue.toLowerCase() + '.gif" height="20px" alt="Logo">';
    string += '&ensp;<b>' + _teams[i].getElementsByTagName('team_name')[0].firstChild.nodeValue + '</b>&ensp;';
    string += '<img src="';
    string += server + shirtImages_ + 'shirt_' + _teams[i].getElementsByTagName('team_nationality')[0].firstChild.nodeValue + '.png" height="20px" alt="Dress">';
    dropLineW3('lineup_select_selected_team', string);
    return;
}

function dispMatchroundDetails() {
    var string = '';
    string += _matchround['title'];
    string += '<br><span style="font-size:9pt;"><u>Deadline:</u> <em>' + _matchround['deadline'] + '</em></span>';
    dropLineW3('lineup_infoarea_infos', '');
    dropLineW3('lineup_infoarea_title', string);
}

function getExistingLineup() {
    var matchround_id = _matchround['id'];
    var url = server + 'ffb/userteam/getUserteamForRound.xml';
    var credits = _options['max_credits'];
    _credits = credits;
    var lineuplist = new Array();
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;

        //alert(response.responseText);

        var players = xmlResponse.getElementsByTagName('players');
        //alert(players.length);
        if(players.length <= 0) {
            //No Lineup available
            dispEmptyLineup();
            return;
        }
        players = players[0].getElementsByTagName('XML_Serializer_Tag');

        var userteam_price = parseFloat(xmlResponse.getElementsByTagName('userteam_price')[0].firstChild.nodeValue);
        credits -= userteam_price;
        //alert(credits);

		for(var i=0;i<players.length;i++){
		    var playerteam_id = players[i].getElementsByTagName('playerteam_id')[0].firstChild.nodeValue;
			lineuplist[i] = new Object();
			//player id
 			lineuplist[i]['player_id'] = players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue;
 			//player first name
 			if(players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue!=null) {
				lineuplist[i]['player_fname'] = players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
			} else {
				lineuplist[i]['player_fname'] = '';
			}
 			//player last name
			lineuplist[i]['player_lname'] = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue;
			//nationality
			lineuplist[i]['player_nationality'] = players[i].getElementsByTagName('player_nationality')[0].firstChild.nodeValue;
			//player status
			lineuplist[i]['player_status'] = players[i].getElementsByTagName('player_status')[0].firstChild.nodeValue;
			//player status description

			if(players[i].getElementsByTagName('player_status_description')[0].firstChild==null) {
			    lineuplist[i]['player_status_description'] ='';
			} else {
			    lineuplist[i]['player_status_description'] = players[i].getElementsByTagName('player_status_description')[0].firstChild.nodeValue;
			}
            //player position
			lineuplist[i]['playerteam_player_position'] = players[i].getElementsByTagName('playerteam_player_position')[0].firstChild.nodeValue;
			//player game price
			lineuplist[i]['player_price'] = parseFloat(players[i].getElementsByTagName('playerteam_player_price')[0].firstChild.nodeValue);
			//player team
			lineuplist[i]['playerteam_team_id'] = players[i].getElementsByTagName('playerteam_team_id')[0].firstChild.nodeValue;
			lineuplist[i]['playerteam_team'] = players[i].getElementsByTagName('playerteam_team')[0].firstChild.nodeValue;
			lineuplist[i]['playerteam_team_nationality'] = players[i].getElementsByTagName('playerteam_team_nationality')[0].firstChild.nodeValue;
			//playerteam id
			lineuplist[i]['playerteam_id'] = players[i].getElementsByTagName('playerteam_id')[0].firstChild.nodeValue;
		}

		_credits = credits;
		_lineuplist = lineuplist;
		updateLineupDisplay();
		updateCreditsDisplay();
		dispActionButtons();

		},

		onFailure : function(response) {
    	   handleAjaxError();
 		},

		parameters: '?matchround_id=' + matchround_id
	});
}

function updateLineupDisplay() {
    var shirt_blank = '<img style="filter:Alpha(opacity=75, finishopacity=0, style=0);opacity:0.75;-moz-opacity:0.75;" src=' + server + shirtImages_ + 'shirt_BLANK.png width="55" height="50">';
    var shirt_blank_red = '<img style="filter:Alpha(opacity=75, finishopacity=0, style=0);opacity:0.75;-moz-opacity:0.75;" src=' + server + shirtImages_ + 'shirt_BLANK_RED.png width="55" height="50">';
    var tmpGoalieString = '';
    var tmpDefenceString = '';
    var tmpMidfieldString = '';
    var tmpStrikerString = '';
    var num_g = 0;
    var num_d = 0;
    var num_m = 0;
    var num_s = 0;

	for(var i=0; i<_lineuplist.length; i++) {
        var player_string = formPlayerElement(_lineuplist[i]);
        if(_lineuplist[i]['playerteam_player_position'] == 'g') {
            tmpGoalieString += player_string;
            num_g++;
        }
        if(_lineuplist[i]['playerteam_player_position'] == 'd') {
            tmpDefenceString += player_string;
            num_d++;
        }
        if(_lineuplist[i]['playerteam_player_position'] == 'm') {
            tmpMidfieldString += player_string;
            num_m++;
        }
        if(_lineuplist[i]['playerteam_player_position'] == 's') {
            tmpStrikerString += player_string;
            num_s++;
        }
	}

    if(_lineuplist.length < _options['max_players']) {
       var num_red_g = 0;
       if(_options['min_goalie']>num_g) {
           num_red_g = _options['min_goalie']-num_g;
       }
       var num_red_d = 0;
       if(_options['min_defence']>num_d) {
           num_red_d = _options['min_defence']-num_d;
       }
       var num_red_m = 0;
       if(_options['min_midfield']>num_m) {
           num_red_m = _options['min_midfield']-num_m;
       }
       var num_red_s = 0;
       if(_options['min_striker']>num_s) {
           num_red_s = _options['min_striker']-num_s;
       }
	   for(var i=0; i<num_red_g; i++) {
	       tmpGoalieString+= '<div id="line_element">' + shirt_blank_red + '<br>TOR</div>';
	   }
	   for(var i=0; i<(_options['max_goalie']-num_red_g-num_g); i++) {
	       tmpGoalieString+= '<div id="line_element">' + shirt_blank + '<br>TOR</div>';
	   }

	   for(var i=0; i<num_red_d; i++) {
	       tmpDefenceString+= '<div id="line_element">' + shirt_blank_red + '<br>VERTEIDIGUNG</div>';
	   }
	   for(var i=0; i<(_options['max_defence']-num_red_d-num_d); i++) {
	       tmpDefenceString+= '<div id="line_element">' + shirt_blank + '<br>VERTEIDIGUNG</div>';
	   }

	   for(var i=0; i<num_red_m; i++) {
	       tmpMidfieldString+= '<div id="line_element">' + shirt_blank_red + '<br>MITTELFELD</div>';
	   }
	   for(var i=0; i<(_options['max_midfield']-num_red_m-num_m); i++) {
	       tmpMidfieldString+= '<div id="line_element">' + shirt_blank + '<br>MITTELFELD</div>';
	   }

	   for(var i=0; i<num_red_s; i++) {
	       tmpStrikerString+= '<div id="line_element">' + shirt_blank_red + '<br>STURM</div>';
	   }
	   for(var i=0; i<(_options['max_striker']-num_red_s-num_s); i++) {
	       tmpStrikerString+= '<div id="line_element">' + shirt_blank + '<br>STURM</div>';
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
    player_string += '<div class="line_element" id="playerteamPlayerNationalityId' + player['playerteam_team_id'] +  '">';
    player_string += '<a href="javascript:void(0);" onClick="javascript:removePlayer(' + player['playerteam_id'] + ');">';
    player_string += '<img src="' + server + shirtImages_ + 'shirt_'+ player['playerteam_team_nationality'] + '.png" width="55" height="50" border="0" title="Klicken um Spieler zu entfernen"><br>';
    player_string += '</a>';
    player_string += '<a class="playerlink" href="javascript:void(0);" onClick="javascript:removePlayer(' + player['playerteam_id'] + ');" title="Klicken um Spieler zu entfernen">';
    player_string += '<b>' + player['player_fname'] + '<br>' + player['player_lname'] + '</b>';
	player_string += '</a><br>';
    player_string += '<div class="infosymbols" id="infosymbols">';
	player_string += '<div class="price" title="Preis: ' + player['player_price'] + ' Credits">' + player['player_price'] + '</div>';
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

function dispEmptyLineup() {
    var tmpGoalieString = '';
    var tmpDefenceString = '';
    var tmpMidfieldString = '';
    var tmpStrikerString = '';

    var shirt_blank = '<img style="filter:Alpha(opacity=75, finishopacity=0, style=0);opacity:0.75;-moz-opacity:0.75;" src=' + server + shirtImages_ + 'shirt_BLANK.png width="55" height="50">';
    var shirt_blank_red = '<img style="filter:Alpha(opacity=75, finishopacity=0, style=0);opacity:0.75;-moz-opacity:0.75;" src=' + server + shirtImages_ + 'shirt_BLANK_RED.png width="55" height="50">';
    for(var i=0;i<_options['min_goalie'];i++) {
	   tmpGoalieString+= '<div id="line_element">' + shirt_blank_red + '<br>TOR</div>';
	}
	for(var i=0;i<(_options['max_goalie']-_options['min_goalie']);i++) {
	   tmpGoalieString+= '<div id="line_element">' + shirt_blank + '<br>TOR</div>';
	}
	for(var i=0;i<_options['min_defence'];i++) {
	   tmpDefenceString+= '<div id="line_element">' + shirt_blank_red + '<br>VERTEIDIGUNG</div>';
	}
	for(var i=0;i<(_options['max_defence']-_options['min_defence']);i++) {
	   tmpDefenceString+= '<div id="line_element">' + shirt_blank + '<br>VERTEIDIGUNG</div>';
	}
	for(var i=0;i<_options['min_midfield'];i++) {
	   tmpMidfieldString+= '<div id="line_element">' + shirt_blank_red + '<br>MITTELFELD</div>';
	}
	for(var i=0;i<(_options['max_midfield']-_options['min_midfield']);i++) {
	   tmpMidfieldString+= '<div id="line_element">' + shirt_blank + '<br>MITTELFELD</div>';
	}
	for(var i=0;i<_options['min_striker'];i++) {
	   tmpStrikerString+= '<div id="line_element">' + shirt_blank_red + '<br>STURM</div>';
	}
	for(var i=0;i<(_options['max_striker']-_options['min_striker']);i++) {
	   tmpStrikerString+= '<div id="line_element">' + shirt_blank + '<br>STURM</div>';
	}

	dropLineW3('line_elements_g', tmpGoalieString);
	dropLineW3('line_elements_d', tmpDefenceString);
	dropLineW3('line_elements_m', tmpMidfieldString);
	dropLineW3('line_elements_s', tmpStrikerString);

    updateCreditsDisplay();

	return;
}

function addPlayer(team_id, index) {
    removeErrorMessages();
    var player = _playerlist[team_id][index];
    if(!checkLineup(player)) {
        return;
    }

    var credits = _credits;
    var i = _lineuplist.length;
    _lineuplist[i] = new Object();
 	_lineuplist[i]['player_id'] = player['player_id'];
	_lineuplist[i]['player_fname'] = player['player_fname'];
	_lineuplist[i]['player_lname'] = player['player_lname'];
	_lineuplist[i]['player_nationality'] = player['player_nationality'];
	_lineuplist[i]['player_status'] = player['player_status'];
	_lineuplist[i]['player_status_description'] = player['player_status_description'];
	_lineuplist[i]['playerteam_player_position'] = player['player_position'];
    _lineuplist[i]['player_price'] = player['player_price'];
	_lineuplist[i]['playerteam_team_id'] = player['playerteam_team_id'];
	_lineuplist[i]['playerteam_team'] = player['playerteam_team'];
    _lineuplist[i]['playerteam_team_nationality'] = player['playerteam_team_nationality'];
	_lineuplist[i]['playerteam_id'] = player['playerteam_id'];

    credits -= _lineuplist[i]['player_price'];
    _credits = credits;
    updateLineupDisplay();
    updateCreditsDisplay();
    dispActionButtons();

    return;
}

function checkLineup(player) {
    var num_g = 0;
    var num_d = 0;
    var num_m = 0;
    var num_s = 0;
    var num_team = 0;
    if((_lineuplist.length+1) > _options['max_players']) {
        addErrorMessage('Du hast bereits ' + _options['max_players'] + ' Spieler aufgestellt!');
        return false;
    }
    if((Math.round(parseFloat(_credits-player['player_price'])*10)/10) < 0) {
        addErrorMessage('Du hast zuwenig Credits um diesen Spieler zu kaufen!');
        return false;
    }
    for(var i=0; i<_lineuplist.length; i++) {
        if(_lineuplist[i]['playerteam_id'] == player['playerteam_id']) {
            addErrorMessage('Dieser Spieler befindet sich bereits in deiner Aufstellung!');
            return false;
        }
        if(_lineuplist[i]['playerteam_player_position'] == 'g') {
            num_g++;
        }
        if(_lineuplist[i]['playerteam_player_position'] == 'd') {
            num_d++;
        }
        if(_lineuplist[i]['playerteam_player_position'] == 'm') {
            num_m++;
        }
        if(_lineuplist[i]['playerteam_player_position'] == 's') {
            num_s++;
        }
        if(_lineuplist[i]['playerteam_team_id'] == player['playerteam_team_id']) {
            num_team++;
        }
    }

    var players_left = _options['max_players']-_lineuplist.length;

    if(player['player_position'] == 'g' && (num_g+1) > _options['max_goalie']) {
        addErrorMessage('Du hast bereits ' + _options['max_goalie'] + ' Spieler im Tor!');
        return false;
    }
    if(player['player_position'] == 'd' && (num_d+1) > _options['max_defence']) {
        addErrorMessage('Du hast bereits ' + _options['max_defence'] + ' Spieler in der Verteidigung!');
        return false;
    }
    if(player['player_position'] == 'm' && (num_m+1) > _options['max_midfield']) {
        addErrorMessage('Du hast bereits ' + _options['max_midfield'] + ' Spieler im Mittelfeld!');
        return false;
    }
    if(player['player_position'] == 's' && (num_s+1) > _options['max_striker']) {
        addErrorMessage('Du hast bereits ' + _options['max_striker'] + ' Spieler im Sturm!');
        return false;
    }
    if((num_team+1) > _options['max_players_from_team']) {
        addErrorMessage('Du hast bereits ' + _options['max_players_from_team'] + ' Spieler von ' + player['playerteam_team'] + ' aufgestellt!');
        return false;
    }

    if(player['player_position'] == 'g') {
        if((_options['min_defence']-num_d) > (players_left-1) || (_options['min_midfield']-num_m) > (players_left-1) || (_options['min_striker']-num_s) > (players_left-1)) {
            addErrorMessage('Du ben&ouml;tigst noch Spieler an anderen Positionen!');
            return false;
        }
    }
    if(player['player_position'] == 'd') {
        if((_options['min_goalie']-num_g) > (players_left-1) || (_options['min_midfield']-num_m) > (players_left-1) || (_options['min_striker']-num_s) > (players_left-1)) {
            addErrorMessage('Du ben&ouml;tigst noch Spieler an anderen Positionen!');
            return false;
        }
    }
    if(player['player_position'] == 'm') {
        if((_options['min_goalie']-num_g) > (players_left-1) || (_options['min_defence']-num_d) > (players_left-1) || (_options['min_striker']-num_s) > (players_left-1)) {
            addErrorMessage('Du ben&ouml;tigst noch Spieler an anderen Positionen!');
            return false;
        }
    }
    if(player['player_position'] == 's') {
        if((_options['min_goalie']-num_g) > (players_left-1) || (_options['min_midfield']-num_m) > (players_left-1) || (_options['min_defence']-num_d) > (players_left-1)) {
            addErrorMessage('Du ben&ouml;tigst noch Spieler an anderen Positionen!');
            return false;
        }
    }

    return true;
}

function removePlayer(playerteam_id) {
    removeErrorMessages();
    var credits = _credits;
    for(var i=0; i<_lineuplist.length; i++) {
        if(_lineuplist[i]['playerteam_id'] == playerteam_id) {
            credits += _lineuplist[i]['player_price']
            deleteArrayItem(i);
            break;
        }
    }
    _credits = credits;
    updateLineupDisplay();
    updateCreditsDisplay();
    dispActionButtons();

    return;
}

function deleteArrayItem(index) {
    _lineuplist.splice(index, 1);
    return;
}

function saveLineup() {
    removeErrorMessages();
    var string = '';
    string += '<input type="button" value="Bitte warten..." class="ffb_button_disabled" disabled="disabled">';
    dropLineW3('lineup_infoarea_actions', string);

    var lineup = new Array();
	var sum_price = 0;
    for(var i=0; i<_lineuplist.length; i++) {
        lineup[i] = _lineuplist[i]['playerteam_id'];
        sum_price += _lineuplist[i]['player_price'];
    }

    var url = server + 'ffb/teammanagement/saveLineup.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;

        //alert(response.responseText);

        var ffb_status = xmlResponse.getElementsByTagName('ffb_status')[0].firstChild.nodeValue;
        var ffb_answer = xmlResponse.getElementsByTagName('ffb_answer')[0].firstChild.nodeValue;
        var ffb_error = xmlResponse.getElementsByTagName('ffb_error')[0].firstChild.nodeValue;

        if(ffb_status != 200) {
            addErrorMessage(ffb_error);
            dispActionButtons();
            return;
        }

        addAnswerMessage(ffb_answer);
        dispActionButtons();

		},

		onFailure : function(response) {
    	   handleAjaxError();
 		},

		parameters: '?matchround_id=' + _matchround['id'] + '&lineup=' + lineup + '&sum_price=' + sum_price
	});

    return;
}

function updateCreditsDisplay() {
    var needed_players = _options['max_players'] - _lineuplist.length;
    var div = document.getElementById('lineup_infoarea_credits');
    if((Math.round(parseFloat(_credits)*10)/10) < 0) {
        div.style.backgroundColor = '#FF0000';
    } else {
        div.style.backgroundColor = '#99CC99';
    }
    var credits = Math.round(parseFloat(_credits)*10)/10;
    var string = '';
    string += '<div id="lineup_infoarea_credits_img"><img src="' + server + symbolImages_ + 'symbol_credits.png" width="30px"></div>';
    string += '<div id="lineup_infoarea_credits_txt">' + credits + '</div>';
    string += '<div style="clear:both;"></div>';
    if(needed_players > 0) {
        string += '<div id="lineup_infoarea_credits_players">noch <b>' + needed_players + '</b> Spieler</div>';
    }
    string += '<div id="lineup_infoarea_credits_maxplayers" title="Du kannst maximal ' + _options['max_players_from_team'] + ' Spieler vom selben Team aufstellen">max. <b>' + _options['max_players_from_team'] + '</b> Sp./Team</div>';
    dropLineW3('lineup_infoarea_credits', string);

    return;
}

function dispActionButtons() {
    var string = '';
    if(_lineuplist.length == _options['max_players']) {
        string += '<input type="button" value="Aufstellung speichern" class="ffb_button" id="send_lineup_button" ';
        string += 'onClick="javascript:saveLineup();"';
        string += '>';
    } else {
        string += '<input type="button" value="Aufstellung speichern" class="ffb_button_disabled" disabled="disabled">';
    }
    dropLineW3('lineup_infoarea_actions', string);

    return;
}

function addErrorMessage(error) {
    var div = document.getElementById('lineup_infoarea_infos');
    div.style.backgroundColor = '#FF0000';
    div.innerHTML += '<img src="' + server + symbolImages_ + 'symb_err_anim.gif" height="11px">&ensp;<b>' + error +
					 '</b>&ensp;<img src="' + server + symbolImages_ + 'symb_err_anim.gif" height="11px"><br />';
    return;
}

function addAnswerMessage(answer) {
    var div = document.getElementById('lineup_infoarea_infos');
    div.style.backgroundColor = '#00FF00';
    div.innerHTML += '<img src="' + server + symbolImages_ + 'ok.png" height="11px">&ensp;<b>' + answer + '</b><br>';
    return;
}

function removeErrorMessages() {
    var div = document.getElementById('lineup_infoarea_infos');
    div.style.backgroundColor = '';
    dropLineW3('lineup_infoarea_infos', '');
    return;
}

function showMatches() {
    dispWaitMessage('matchlist', 'lade Spiele..');
    var matchtext = '<div style="text-align:center; width:100%; margin-bottom:4px;"><a href="javascript:void(0);" onClick="javascript:hideMatches();">Spiele ausblenden</a></div>';
	dispMatches(_matches, matchtext);
}

function hideMatches() {
    var matchlink = '<div style="text-align:center; width:100%;"><a onClick="javascript:showMatches();" href="javascript:void(0);">Spiele einblenden</a></div>';
    dropLineW3('matchlist', matchlink);
}

function dispWaitMessage(div, text) {
    var image = '<img src=' + server + images_ + 'loading/ajax-loader-in-progress.gif height="10px">';
    var string = '<div style="width:100%; font-size:10pt; text-align:center;">' + image + '&ensp;' + text + '</div>';
    dropLineW3(div, string);
}

function gameOver() {
	dropLineW3('line_goalie', '');
	dropLineW3('line_forward', '');
	dropLineW3('line_midfield', '<img src=' + server + symbolImages_ + 'gameover.png width="500px">');
	dropLineW3('line_defence', '');
	dropLineW3('matchlist','');
	dropLineW3('lineup_select_main','');
	//document.getElementById('lineup_select_main').style.visibility = 'hidden';
	//document.getElementById('matchlist').style.visibility = 'hidden';
	dropLineW3('Mainright', '');
}

function dispRoundNotReady() {
    var string = 'Spielrunde noch nicht bereit!';
    removeErrorMessages();
    var div = document.getElementById('lineup_infoarea_credits');
    div.style.visibility = 'hidden';
    addErrorMessage(string);
	dropLineW3('line_elements_g', '');
	dropLineW3('line_elements_d', '');
	dropLineW3('line_elements_m', '');
	dropLineW3('line_elements_s', '');
	dropLineW3('matchlist','');
	dropLineW3('lineup_select_main','');
	//document.getElementById('lineup_select_main').style.visibility = 'hidden';
	//document.getElementById('matchlist').style.visibility = 'hidden';
	dropLineW3('Mainright', '');
}

function dispNoRoundAvailable() {
    var string = 'Keine weitere Spielrunde vorhanden! Bitte sp&auml;ter nochmal probieren!';
    removeErrorMessages();
    var div = document.getElementById('lineup_infoarea_credits');
    div.style.visibility = 'hidden';
    addErrorMessage(string);
    dropLineW3('line_elements_g', '');
	dropLineW3('line_elements_d', '');
	dropLineW3('line_elements_m', '');
	dropLineW3('line_elements_s', '');
	dropLineW3('matchlist','');
	dropLineW3('lineup_select_main','');
	//document.getElementById('lineup_select_main').style.visibility = 'hidden';
	//document.getElementById('matchlist').style.visibility = 'hidden';
	dropLineW3('Mainright', '');
}



function rectanglePlayers(teamId1, teamId2) {
	if(teamId1) {
		var idStringTeam1	=	'#playerteamPlayerNationalityId' + teamId1;
		var playersDivs = $$(idStringTeam1);
		for(var i=0;i<playersDivs.length;i++) {
			playersDivs[i].setStyle({
				'border' : 'solid black 2px'
			});	
		}
		if(playersDivs.length) 
			setTimeout("unsetBorder('" + idStringTeam1 + "')",1000);
	}
	
	if(teamId2) {
		var idStringTeam2	=	'#playerteamPlayerNationalityId' + teamId2;
		var playersDivs = $$(idStringTeam2);
		for(var i=0;i<playersDivs.length;i++) {
			playersDivs[i].setStyle({
				'border' : 'solid black 2px'
			});	
		}
		if(playersDivs.length) 
			setTimeout("unsetBorder('" + idStringTeam2 + "')",1000);
	}
}


function setBorder(elem, timeout) {
	_playersDivs[elem].setStyle({
				'border' : 'solid black 2px'
			});
	if(timeout) {
		setTimeout('unsetBorder('+elem+')',timeout);
	}
}

function unsetBorder(elem) {
	var playersDivs = $$(elem);
	for(var i=0;i<playersDivs.length;i++) {
		playersDivs[i].setStyle({
			'border' : 'solid black 0px'
		});
	}
}