var players_home_db = new Array();
var players_home_fifa = new Array();
var players_guest_db = new Array();
var players_guest_fifa = new Array();
var players_combine = new Array();
var players_update = new Array();
var _db_home_marked = 0;
var _db_guest_marked = 0;
var _fifa_home_marked = 0;
var _fifa_guest_marked = 0;
var _db_home_index = -1;
var _db_guest_index = -1;
var _fifa_home_index = -1;
var _fifa_guest_index = -1;
var _match_id = 0;
var _mode = '';

function init() {
    dispWait('select_matchround');
    loadMatchround();
}

function clearVars() {
    players_home_db = new Array();
    players_home_fifa = new Array();
    players_guest_db = new Array();
    players_guest_fifa = new Array();
    players_combine = new Array();
    players_update = new Array();
    _db_home_marked = 0;
    _db_guest_marked = 0;
    _fifa_home_marked = 0;
    _fifa_guest_marked = 0;
    _db_home_index = -1;
    _db_guest_index = -1;
    _fifa_home_index = -1;
    _fifa_guest_index = -1;
    _match_id = 0;
}

function loadMatchround() {
	var url = server + 'administration/matchround/getList.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		var round = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		var toDisplay='<div id="formline"><div id="formdescr">Matchround: </div><div id="forminput"><select name="round" onChange="javascript:loadMatchroundGames(this.value);"><option>Runde selektieren</option>';
 		for(var i=0;i<round.length;i++) {
 			toDisplay += '<option value="' + round[i].getElementsByTagName('matchround_id')[0].firstChild.nodeValue +
 						 '">' + round[i].getElementsByTagName('matchround_title')[0].firstChild.nodeValue + '</option>\r\n';
		}
		toDisplay += '</select></div></div><div id="formclear"></div>';
		dropLineW3('select_matchround', toDisplay);
		},

		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});
}

function reloadMatch() {
    loadMatchData(_match_id);
}

function loadMatchroundGames(round_id) {
    clearVars();
    deleteAllDiv();
    dispWait('select_match');

	var url = server + 'administration/match/getMatchesForRound.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
 		var matches = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		var toDisplay='<div id="formline"><div id="formdescr">Match: </div><div id="forminput">' +
                      '<select name="match" onChange="javascript:loadMatchData(this.value);"><option>Spiel selektieren</option>';
 		for(var i=0;i<matches.length;i++) {
 		    if(matches[i].getElementsByTagName('match_homescore')[0].firstChild.nodeValue < 0) {
 		        var homescore = '-';
 		        var guestscore = '-';
 		    } else {
 		        var homescore = matches[i].getElementsByTagName('match_homescore')[0].firstChild.nodeValue;
 		        var guestscore = matches[i].getElementsByTagName('match_guestscore')[0].firstChild.nodeValue;
 		    }
 		    toDisplay += '<option value="' + matches[i].getElementsByTagName('match_id')[0].firstChild.nodeValue + '">' +
                         matches[i].getElementsByTagName('match_hometeam_name')[0].firstChild.nodeValue +
                         ' ' + homescore + ':' + guestscore + ' ' +
                         matches[i].getElementsByTagName('match_guestteam_name')[0].firstChild.nodeValue +
                         '</option>\r\n';
		}
		toDisplay += '</select><a href="javascript:reloadMatch()">reload</a></div></div><div id="formclear"></div>';
		dropLineW3('select_match', toDisplay);
		},

		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},

		parameters: 'matchround_id='+round_id
	});
}

function loadMatchData(match_id) {
    if(players_update.length > 0) {
        clearVars();
        deleteAllDiv();
    } else {
        deleteDiv('formerror');
        deleteDiv('formanswer');
        deleteDiv('playerlist_update');
        deleteDiv('send_updates_button_div');
    }
    dispWait('select_result');
    dispWait('playerlist_home_db');
    dispWait('playerlist_guest_db');
    _match_id = match_id;
    var url = server + 'administration/match/getMatchForId.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
 		var matches = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		var hometeam_id = matches[0].getElementsByTagName('match_hometeam_id')[0].firstChild.nodeValue;
 		var minutes = matches[0].getElementsByTagName('match_minutes')[0].firstChild.nodeValue;
 		var guestteam_id = matches[0].getElementsByTagName('match_guestteam_id')[0].firstChild.nodeValue;
 		var hometeam_name = matches[0].getElementsByTagName('match_hometeam_name')[0].firstChild.nodeValue;
 		var guestteam_name = matches[0].getElementsByTagName('match_guestteam_name')[0].firstChild.nodeValue;
 		var homescore = matches[0].getElementsByTagName('match_homescore')[0].firstChild.nodeValue;
 		var guestscore = matches[0].getElementsByTagName('match_guestscore')[0].firstChild.nodeValue;
 		var homescore_penalty = matches[0].getElementsByTagName('match_homescore_penalty')[0].firstChild.nodeValue;
 		var guestscore_penalty = matches[0].getElementsByTagName('match_guestscore_penalty')[0].firstChild.nodeValue;
 		var match_url = matches[0].getElementsByTagName('match_url')[0].firstChild.nodeValue;
		//drop match-url
		if(match_url != 0) {
			var target = document.getElementById('web_url');
			//alert(target);
			target.value = match_url;
		}

        //display SELECT-FORMS for result
 		var toDisplay='<div id="formline"><div id="formdescr">Result: </div><div id="forminput">' +
                      '<select name="matchresult_home" id="matchresult_home">';
        for(var i=0;i<15;i++) {
            var selected = '';
            if(i==homescore) {
                selected = ' selected';
            }
            toDisplay += '<option' + selected + ' value="' + i + '">' + i + '</option>';
        }
        toDisplay += '</select> : ' + '<select name="matchresult_guest" id="matchresult_guest">';
        for(var i=0;i<15;i++) {
            var selected = '';
            if(i==guestscore) {
                selected = ' selected';
            }
            toDisplay += '<option' + selected + ' value="' + i + '">' + i + '</option>';
        }
        toDisplay += '</select>';
        toDisplay += ' Minutes: <select name="matchresult_minutes" id="matchresult_minutes">';
        var selected = '';
        if(minutes == 90) {
            selected = ' selected';
        }
        toDisplay += '<option' + selected + ' value="90">90 min</option>';
        selected = '';
        if(minutes == 120) {
            selected = ' selected';
        }
        toDisplay += '<option' + selected + ' value="120">120 min</option></select>';

        toDisplay += '</div></div><div id="formclear"></div>';

		//display SELECT-FORMS for penaltyshootout
        toDisplay += '<div id="formline"><div id="formdescr">Penalty: </div><div id="forminput">' +
                         '<select name="matchresult_penalty_home" id="matchresult_penalty_home">';
        toDisplay += '<option value="-1">--</option>';
		for(var i=0;i<15;i++) {
            var selected = '';
            if(i==homescore_penalty) {
                selected = ' selected';
            }
            toDisplay += '<option' + selected + ' value="' + i + '">' + i + '</option>';
        }
        toDisplay += '</select> : ' + '<select name="matchresult_penalty_guest" id="matchresult_penalty_guest">';
        toDisplay += '<option value="-1">--</option>';
		for(var i=0;i<15;i++) {
            var selected = '';
            if(i==guestscore_penalty) {
                selected = ' selected';
            }
            toDisplay += '<option' + selected + ' value="' + i + '">' + i + '</option>';
        }
        toDisplay += '</select>';

        toDisplay += '</div></div><div id="formclear"></div>';
        dropLineW3('select_result', toDisplay);
        // *****

        //load and display playerlists for HOME and GUEST
        var url = server + 'administration/matchpoints/getPlayerStatsForTeam.xml';
        new Ajax.Request(url, {
     		onSuccess : function(response) {
     		var xmlResponse=response.responseXML;
     		//alert(response.responseText);
     		var players = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
     		var toDisplay = '';
     		for(var i=0;i<players.length;i++) {
     		    var player_id = players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue;
     		    var player_name = players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue + ' ' + players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue;
     		    if(players[i].getElementsByTagName('player_name_fid_foe')[0].firstChild.nodeValue != 0) {
     		        var player_name_fid_foe = players[i].getElementsByTagName('player_name_fid_foe')[0].firstChild.nodeValue;
     		    } else {
                    var player_name_fid_foe = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue + ' ' + players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
     		    }
     		    if(players[i].getElementsByTagName('player_name_fid_fifa')[0].firstChild.nodeValue != 0) {
     		        var player_name_fid_fifa = players[i].getElementsByTagName('player_name_fid_fifa')[0].firstChild.nodeValue;
     		    } else {
                    var player_name_fid_fifa = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue + ' ' + players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
     		    }
     		    if(players[i].getElementsByTagName('player_name_fid_tm')[0].firstChild.nodeValue != 0) {
     		        var player_name_fid_tm = players[i].getElementsByTagName('player_name_fid_tm')[0].firstChild.nodeValue;
     		    } else {
                    var player_name_fid_tm = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue + ' ' + players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
     		    }
     		    if(players[i].getElementsByTagName('player_name_fid_uefa')[0].firstChild.nodeValue != 0) {
     		        var player_name_fid_uefa = players[i].getElementsByTagName('player_name_fid_uefa')[0].firstChild.nodeValue;
     		    } else {
                    var player_name_fid_uefa = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue + ' ' + players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
     		    }
     		    if(players[i].getElementsByTagName('player_name_fid_wf')[0].firstChild.nodeValue != 0) {
     		        var player_name_fid_wf = players[i].getElementsByTagName('player_name_fid_wf')[0].firstChild.nodeValue;
     		    } else {
                    var player_name_fid_wf = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue + ' ' + players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
     		    }
                var player_minutes = players[i].getElementsByTagName('playerstats_minutes')[0].firstChild.nodeValue;
                var player_assists = players[i].getElementsByTagName('playerstats_assists')[0].firstChild.nodeValue;
     		    var player_minute_in = players[i].getElementsByTagName('playerstats_minute_in')[0].firstChild.nodeValue;
     		    var player_minute_out = players[i].getElementsByTagName('playerstats_minute_out')[0].firstChild.nodeValue;
     		    var playerteam_id = players[i].getElementsByTagName('playerteam_id')[0].firstChild.nodeValue;
     		    var playerteam_status = players[i].getElementsByTagName('playerteam_status')[0].firstChild.nodeValue;
     		    var playerteam_position = players[i].getElementsByTagName('playerteam_player_position')[0].firstChild.nodeValue;
     		    players_home_db[i] = new Object();
     		    players_home_db[i]['playerteam_id'] = playerteam_id;
     		    players_home_db[i]['player_name'] = player_name;
     		    players_home_db[i]['player_assists'] = player_assists;
     		    players_home_db[i]['player_name_fid_foe'] = player_name_fid_foe;
     		    players_home_db[i]['player_name_fid_fifa'] = player_name_fid_fifa;
     		    players_home_db[i]['player_name_fid_tm'] = player_name_fid_tm;
     		    players_home_db[i]['player_name_fid_uefa'] = player_name_fid_uefa;
     		    players_home_db[i]['player_name_fid_wf'] = player_name_fid_wf;
                var div_color = '#C0C0C0';
                if(player_minutes>0) {
                    div_color = '#00FF00';
                }
                //if(playerteam_status == 1) {
                if(playerteam_status == 1 || playerteam_status == 0) {
                    toDisplay += '<div class="player_div" id="db_homeplayer_'+i+'" style="background-color:'+div_color+';" ' +
                                 'onClick="javascript:markDbHome(' + i + ')"' + '>' +
                                 '<b>' + player_name+'</b> ('+playerteam_position+')</div>';
                }
     		}
     		dropLineW3('playerlist_home_db', toDisplay);

    		},

    		 onFailure : function(response) {
        	alert("Oops, there's been an error.");
     		},

    		parameters: 'id='+hometeam_id+'&match_id='+match_id+'&all_players=1'
    	});

    	var url = server + 'administration/matchpoints/getPlayerStatsForTeam.xml';
        new Ajax.Request(url, {
     		onSuccess : function(response) {
     		var xmlResponse=response.responseXML;
     		//alert(response.responseText);
     		var players = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
     		var toDisplay = '';
     		for(var i=0;i<players.length;i++) {
     		    var player_id = players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue;
     		    var player_name = players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue + ' ' + players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue;
     		    if(players[i].getElementsByTagName('player_name_fid_foe')[0].firstChild.nodeValue != 0) {
     		        var player_name_fid_foe = players[i].getElementsByTagName('player_name_fid_foe')[0].firstChild.nodeValue;
     		    } else {
                    var player_name_fid_foe = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue + ' ' + players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
     		    }
     		    if(players[i].getElementsByTagName('player_name_fid_fifa')[0].firstChild.nodeValue != 0) {
     		        var player_name_fid_fifa = players[i].getElementsByTagName('player_name_fid_fifa')[0].firstChild.nodeValue;
     		    } else {
                    var player_name_fid_fifa = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue + ' ' + players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
     		    }
     		    if(players[i].getElementsByTagName('player_name_fid_tm')[0].firstChild.nodeValue != 0) {
     		        var player_name_fid_tm = players[i].getElementsByTagName('player_name_fid_tm')[0].firstChild.nodeValue;
     		    } else {
                    var player_name_fid_tm = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue + ' ' + players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
     		    }
     		    if(players[i].getElementsByTagName('player_name_fid_uefa')[0].firstChild.nodeValue != 0) {
     		        var player_name_fid_uefa = players[i].getElementsByTagName('player_name_fid_uefa')[0].firstChild.nodeValue;
     		    } else {
                    var player_name_fid_uefa = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue + ' ' + players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
     		    }
     		    if(players[i].getElementsByTagName('player_name_fid_wf')[0].firstChild.nodeValue != 0) {
     		        var player_name_fid_wf = players[i].getElementsByTagName('player_name_fid_wf')[0].firstChild.nodeValue;
     		    } else {
                    var player_name_fid_wf = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue + ' ' + players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
     		    }
     		    var player_minutes = players[i].getElementsByTagName('playerstats_minutes')[0].firstChild.nodeValue;
     		    var player_assists = players[i].getElementsByTagName('playerstats_assists')[0].firstChild.nodeValue;
     		    var player_minute_in = players[i].getElementsByTagName('playerstats_minute_in')[0].firstChild.nodeValue;
     		    var player_minute_out = players[i].getElementsByTagName('playerstats_minute_out')[0].firstChild.nodeValue;
     		    var playerteam_id = players[i].getElementsByTagName('playerteam_id')[0].firstChild.nodeValue;
     		    var playerteam_status = players[i].getElementsByTagName('playerteam_status')[0].firstChild.nodeValue;
     		    var playerteam_position = players[i].getElementsByTagName('playerteam_player_position')[0].firstChild.nodeValue;
     		    players_guest_db[i] = new Object();
     		    players_guest_db[i]['playerteam_id'] = playerteam_id;
     		    players_guest_db[i]['player_name'] = player_name;
     		    players_guest_db[i]['player_assists'] = player_assists;
     		    players_guest_db[i]['player_name_fid_foe'] = player_name_fid_foe;
     		    players_guest_db[i]['player_name_fid_fifa'] = player_name_fid_fifa;
     		    players_guest_db[i]['player_name_fid_tm'] = player_name_fid_tm;
     		    players_guest_db[i]['player_name_fid_uefa'] = player_name_fid_uefa;
     		    players_guest_db[i]['player_name_fid_wf'] = player_name_fid_wf;
                var div_color = '#C0C0C0';
                if(player_minutes>0) {
                    div_color = '#00FF00';
                }
                //if(playerteam_status == 1) {
                if(playerteam_status == 1 || playerteam_status == 0) {
                    toDisplay += '<div class="player_div" id="db_guestplayer_'+i+'" style="background-color:'+div_color+';" ' +
                                 'onClick="javascript:markDbGuest(' + i + ')"' + '>' +
                                 '<b>' + player_name+'</b> ('+playerteam_position+')</div>';
                }
     		}
     		dropLineW3('playerlist_guest_db', toDisplay);

    		},

    		 onFailure : function(response) {
        	alert("Oops, there's been an error.");
     		},

    		parameters: 'id='+guestteam_id+'&match_id='+match_id+'&all_players=1'
    	});

        // *****

		},

		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},

		parameters: 'match_id='+match_id
	});
}

function loadUrlData(type) {
        //load and display website-data for HOME and GUEST
        _mode = type;
        var min_div = document.getElementById('matchresult_minutes');
        if(min_div) {
        	var match_minutes = min_div.options[min_div.options.selectedIndex].value;
        } else {
        	alert('Please load the match first and choose the match minutes!');
        	return;
        }
        if(type == 'fifa') {
            var url = server + 'administration/fifa_playermanagement/getFifaMatchData.xml';
        } else if(type == 'foe') {
            var url = server + 'administration/fifa_playermanagement/getFoeMatchData.xml';
        } else if(type == 'wf') {
            var url = server + 'administration/fifa_playermanagement/getWfMatchData.xml';
        }
        var web_url = $('web_url').value;
        if(!web_url) {
            alert('Enter an URL for this Match!');
            return;
        }
    if(players_update.length > 0) {
        clearVars();
        deleteAllDiv();
    } else {
        deleteDiv('formerror');
        deleteDiv('formanswer');
        deleteDiv('playerlist_update');
        deleteDiv('send_updates_button_div');
    }
    dispWait('playerlist_home_fifa');
    dispWait('playerlist_guest_fifa');
        new Ajax.Request(url, {
     		onSuccess : function(response) {
     		var xmlResponse=response.responseXML;
     		//alert(response.responseText);
			var match_minutes = parseInt(xmlResponse.getElementsByTagName('match_minutes')[0].firstChild.nodeValue);
			console.log(match_minutes);
     		var list = xmlResponse.getElementsByTagName('playerlist_home');
     		var home_players = list[0].getElementsByTagName('XML_Serializer_Tag');
     		var list = xmlResponse.getElementsByTagName('playerlist_guest');
     		var guest_players = list[0].getElementsByTagName('XML_Serializer_Tag');
     		var toDisplay = '';
			var homescore = 0;
			var guestscore = 0;
			var homescore_penalty = 0;
			var guestscore_penalty = 0;
			var penaltyshootout = false;
     		for(var i=0;i<home_players.length;i++) {
     		    var player_name = home_players[i].getElementsByTagName('player_name')[0].firstChild.nodeValue;
     		    var player_minutes = home_players[i].getElementsByTagName('player_minutes')[0].firstChild.nodeValue;
     		    var player_minute_in = home_players[i].getElementsByTagName('player_change_in')[0].firstChild.nodeValue;
     		    var player_minute_out = home_players[i].getElementsByTagName('player_change_out')[0].firstChild.nodeValue;
     		    var player_num_goals = parseInt(home_players[i].getElementsByTagName('player_num_goals')[0].firstChild.nodeValue);
     		    var player_goals = home_players[i].getElementsByTagName('player_goal')[0].firstChild.nodeValue;
     		    var player_num_owngoals = parseInt(home_players[i].getElementsByTagName('player_num_owngoals')[0].firstChild.nodeValue);
     		    var player_owngoals = home_players[i].getElementsByTagName('player_owngoal')[0].firstChild.nodeValue;
     		    var player_num_assists = home_players[i].getElementsByTagName('player_num_assists')[0].firstChild.nodeValue;
     		    var player_cards = home_players[i].getElementsByTagName('player_cards')[0].firstChild.nodeValue;
     		    var player_penaltyshootout = parseInt(home_players[i].getElementsByTagName('player_penaltyshootout')[0].firstChild.nodeValue);
     		    var player_penalties_hit = parseInt(home_players[i].getElementsByTagName('player_penalties_hit')[0].firstChild.nodeValue);
     		    var player_penalties_fail = parseInt(home_players[i].getElementsByTagName('player_penalties_fail')[0].firstChild.nodeValue);
                if(player_minute_out == 0) {
                    player_minute_out = parseInt(player_minute_in) + parseInt(player_minutes);
                }
                if(player_minute_in == 0) {
                    player_minute_in = 1;
                }
     		    players_home_fifa[i] = new Object();
     		    players_home_fifa[i]['player_name'] = player_name;
     		    players_home_fifa[i]['player_minutes'] = player_minutes;
     		    players_home_fifa[i]['player_minute_in'] = player_minute_in;
     		    players_home_fifa[i]['player_minute_out'] = player_minute_out;
     		    players_home_fifa[i]['player_goals'] = player_goals;
     		    players_home_fifa[i]['player_owngoals'] = player_owngoals;
     		    players_home_fifa[i]['player_num_assists'] = player_num_assists;
     		    players_home_fifa[i]['player_cards'] = player_cards;
     		    players_home_fifa[i]['player_penaltyshootout'] = player_penaltyshootout;
     		    players_home_fifa[i]['player_penalties_hit'] = player_penalties_hit;
     		    players_home_fifa[i]['player_penalties_fail'] = player_penalties_fail;

				homescore += player_num_goals;
				guestscore += player_num_owngoals;
				homescore_penalty += player_penalties_hit;
				if(player_penaltyshootout !== 0 && penaltyshootout === false) {
					penaltyshootout = true;
				}

                var div_color = '#C0C0C0';

                toDisplay += '<div class="player_div" id="fifa_homeplayer_'+i+'" style="background-color:'+div_color+';" ' +
                             'onClick="javascript:markFifaHome(' + i + ')"' +
                             '>' + '<b>' + player_name + '</b><br>' +
                             '<img src="' + server + symbolImages_ + 'stats_time.png" width="16px" height="16px" alt="minutes" title="minutes"> ' + player_minutes +
                             ' <img src="' + server + symbolImages_ + 'stats_change_in.gif" width="16px" height="16px" alt="change_in" title="change_in"> ' + player_minute_in +
                             ' <img src="' + server + symbolImages_ + 'stats_change_out.gif" width="16px" height="16px" alt="change_out" title="change_out"> ' + player_minute_out +
                             '<br><img src="' + server + symbolImages_ + 'stats_goal.gif" width="16px" height="16px" alt="goals" title="goals"> ' + player_goals +
                             ' <img src="' + server + symbolImages_ + 'stats_owngoal.gif" width="16px" height="16px" alt="owngoals" title="owngoals"> ' + player_owngoals +
                             ' <img src="' + server + symbolImages_ + 'stats_assist.gif" width="16px" height="16px" alt="assists" title="assists"> ' + player_num_assists +
                             '<br><img src="' + server + symbolImages_ + 'stats_card_yr.gif" width="16px" height="16px" alt="card" title="card"> ' + player_cards;
				if(player_penaltyshootout == 1) {
                	toDisplay += '<br><img src="' + server + symbolImages_ + 'stats_ps_hit.png" width="16px" height="16px" alt="PS: hits" title="PS: hits"> ' + player_penalties_hit +
                				 ' <img src="' + server + symbolImages_ + 'stats_ps_fail.png" width="16px" height="16px" alt="PS: fails" title="PS: fails"> ' + player_penalties_fail;
                }
				toDisplay += '</div>';
				//console.log(i);
     		}
     		dropLineW3('playerlist_home_fifa', toDisplay);

            var toDisplay = '';
     		for(var i=0;i<guest_players.length;i++) {
     		    var player_name = guest_players[i].getElementsByTagName('player_name')[0].firstChild.nodeValue;
     		    var player_minutes = guest_players[i].getElementsByTagName('player_minutes')[0].firstChild.nodeValue;
     		    var player_minute_in = guest_players[i].getElementsByTagName('player_change_in')[0].firstChild.nodeValue;
     		    var player_minute_out = guest_players[i].getElementsByTagName('player_change_out')[0].firstChild.nodeValue;
     		    var player_num_goals = parseInt(guest_players[i].getElementsByTagName('player_num_goals')[0].firstChild.nodeValue);
     		    var player_goals = guest_players[i].getElementsByTagName('player_goal')[0].firstChild.nodeValue;
     		    var player_num_owngoals = parseInt(guest_players[i].getElementsByTagName('player_num_owngoals')[0].firstChild.nodeValue);
     		    var player_owngoals = guest_players[i].getElementsByTagName('player_owngoal')[0].firstChild.nodeValue;
				var player_num_assists = guest_players[i].getElementsByTagName('player_num_assists')[0].firstChild.nodeValue;
     		    var player_cards = guest_players[i].getElementsByTagName('player_cards')[0].firstChild.nodeValue;
     		    var player_penaltyshootout = parseInt(guest_players[i].getElementsByTagName('player_penaltyshootout')[0].firstChild.nodeValue);
     		    var player_penalties_hit = parseInt(guest_players[i].getElementsByTagName('player_penalties_hit')[0].firstChild.nodeValue);
     		    var player_penalties_fail = parseInt(guest_players[i].getElementsByTagName('player_penalties_fail')[0].firstChild.nodeValue);

     		    if(player_minute_out == 0) {
                    player_minute_out = parseInt(player_minute_in) + parseInt(player_minutes);
                }
                if(player_minute_in == 0) {
                    player_minute_in = 1;
                }

     		    players_guest_fifa[i] = new Object();
     		    players_guest_fifa[i]['player_name'] = player_name;
     		    players_guest_fifa[i]['player_minutes'] = player_minutes;
     		    players_guest_fifa[i]['player_minute_in'] = player_minute_in;
     		    players_guest_fifa[i]['player_minute_out'] = player_minute_out;
     		    players_guest_fifa[i]['player_goals'] = player_goals;
     		    players_guest_fifa[i]['player_owngoals'] = player_owngoals;
				players_guest_fifa[i]['player_num_assists'] = player_num_assists;
     		    players_guest_fifa[i]['player_cards'] = player_cards;
     		    players_guest_fifa[i]['player_penaltyshootout'] = player_penaltyshootout;
     		    players_guest_fifa[i]['player_penalties_hit'] = player_penalties_hit;
     		    players_guest_fifa[i]['player_penalties_fail'] = player_penalties_fail;

				guestscore += player_num_goals;
				homescore += player_num_owngoals;
				guestscore_penalty += player_penalties_hit;
				if(player_penaltyshootout !== 0 && penaltyshootout === false) {
					penaltyshootout = true;
				}

                var div_color = '#C0C0C0';
                toDisplay += '<div class="player_div" id="fifa_guestplayer_'+i+'" style="background-color:'+div_color+';" ' +
                             'onClick="javascript:markFifaGuest(' + i + ')"' +
                             '>' + '<b>' + player_name + '</b><br>' +
                             '<img src="' + server + symbolImages_ + 'stats_time.png" width="16px" height="16px" alt="minutes" title="minutes"> ' + player_minutes +
                             ' <img src="' + server + symbolImages_ + 'stats_change_in.gif" width="16px" height="16px" alt="change_in" title="change_in"> ' + player_minute_in +
                             ' <img src="' + server + symbolImages_ + 'stats_change_out.gif" width="16px" height="16px" alt="change_out" title="change_out"> ' + player_minute_out +
                             '<br><img src="' + server + symbolImages_ + 'stats_goal.gif" width="16px" height="16px" alt="goals" title="goals"> ' + player_goals +
                             ' <img src="' + server + symbolImages_ + 'stats_owngoal.gif" width="16px" height="16px" alt="owngoals" title="owngoals"> ' + player_owngoals +
                             ' <img src="' + server + symbolImages_ + 'stats_assist.gif" width="16px" height="16px" alt="assists" title="assists"> ' + player_num_assists +
                             '<br><img src="' + server + symbolImages_ + 'stats_card_yr.gif" width="16px" height="16px" alt="card" title="card"> ' + player_cards;
				if(player_penaltyshootout == 1) {
                	toDisplay += '<br><img src="' + server + symbolImages_ + 'stats_ps_hit.png" width="16px" height="16px" alt="PS: hits" title="PS: hits"> ' + player_penalties_hit +
                				 ' <img src="' + server + symbolImages_ + 'stats_ps_fail.png" width="16px" height="16px" alt="PS: fails" title="PS: fails"> ' + player_penalties_fail;
                }
				toDisplay += '</div>';
     		}
     		dropLineW3('playerlist_guest_fifa', toDisplay);

			$('matchresult_home').selectedIndex = parseInt(homescore);
			$('matchresult_guest').selectedIndex = parseInt(guestscore);
			if(penaltyshootout === true) {
				$('matchresult_penalty_home').selectedIndex = parseInt(homescore_penalty)+1;
				$('matchresult_penalty_guest').selectedIndex = parseInt(guestscore_penalty)+1;
			} else {
				$('matchresult_penalty_home').selectedIndex = 0;
				$('matchresult_penalty_guest').selectedIndex = 0;
			}
			if(match_minutes == 120) {
				$('matchresult_minutes').selectedIndex = 1;
			} else {
				$('matchresult_minutes').selectedIndex = 0;
			}

    		},

    		 onFailure : function(response) {
        	alert("Oops, there's been an error.");
     		},

    		parameters: '?url='+web_url+'&match_minutes='+match_minutes
    	});

        // *****
}

function markDbHome(index) {
    if(_db_home_marked) {
        if(_db_home_index == index) {
            _db_home_marked = 0;
            unmarkDiv('db_homeplayer_'+index);
        } else {
            alert('ERROR');
        }
    } else {
        _db_home_marked = 1;
        _db_home_index = index;
        markDiv('db_homeplayer_'+index);
        checkPair();
    }
}

function markDbGuest(index) {
    if(_db_guest_marked) {
        if(_db_guest_index == index) {
            _db_guest_marked = 0;
            unmarkDiv('db_guestplayer_'+index);
        } else {
            alert('ERROR');
        }
    } else {
        _db_guest_marked = 1;
        _db_guest_index = index;
        markDiv('db_guestplayer_'+index);
        checkPair();
    }
}

function markFifaHome(index) {
    if(_fifa_home_marked) {
        if(_fifa_home_index == index) {
            _fifa_home_marked = 0;
            unmarkDiv('fifa_homeplayer_'+index);
        } else {
            alert('ERROR');
        }
    } else {
        _fifa_home_marked = 1;
        _fifa_home_index = index;
        markDiv('fifa_homeplayer_'+index);
        checkPair();
    }
}

function markFifaGuest(index) {
    if(_fifa_guest_marked) {
        if(_fifa_guest_index == index) {
            _fifa_guest_marked = 0;
            unmarkDiv('fifa_guestplayer_'+index);
        } else {
            alert('ERROR');
        }
    } else {
        _fifa_guest_marked = 1;
        _fifa_guest_index = index;
        markDiv('fifa_guestplayer_'+index);
        checkPair();
    }
}

function checkPair() {
    if(_db_home_marked && _fifa_home_marked) {
        addUpdate(players_home_db[_db_home_index], players_home_fifa[_fifa_home_index]);
        _db_home_marked = 0;
        _fifa_home_marked = 0;
        deleteDiv('db_homeplayer_'+_db_home_index);
        deleteDiv('fifa_homeplayer_'+_fifa_home_index);
        return;
    }
    if(_db_guest_marked && _fifa_guest_marked) {
        addUpdate(players_guest_db[_db_guest_index], players_guest_fifa[_fifa_guest_index]);
        _db_guest_marked = 0;
        _fifa_guest_marked = 0;
        deleteDiv('db_guestplayer_'+_db_guest_index);
        deleteDiv('fifa_guestplayer_'+_fifa_guest_index);
        return;
    }
}

function addUpdate(player_db, player_fifa) {
    var update_length = players_update.length;
    //alert(update_length);
    players_update[update_length] = new Object();
    players_update[update_length]['send_update'] = true;
    players_update[update_length]['playerteam_id'] = player_db['playerteam_id'];
    players_update[update_length]['player_name_db'] = player_db['player_name'];
    //players_update[update_length]['playerstats_assists'] = player_db['player_assists'];
    players_update[update_length]['playerstats_assists'] = player_fifa['player_num_assists'];
    players_update[update_length]['player_name_fifa'] = player_fifa['player_name'];
    players_update[update_length]['playerstats_minutes'] = player_fifa['player_minutes'];
    players_update[update_length]['playerstats_minute_in'] = player_fifa['player_minute_in'];
    players_update[update_length]['playerstats_minute_out'] = player_fifa['player_minute_out'];
    players_update[update_length]['playerstats_goals'] = player_fifa['player_goals'];
    players_update[update_length]['playerstats_owngoals'] = player_fifa['player_owngoals'];

    if(player_fifa['player_cards'] == 'Y') {
        players_update[update_length]['playerstats_cards'] = 'y';
    } else if(player_fifa['player_cards'] == 'YR') {
        players_update[update_length]['playerstats_cards'] = 'yr';
    } else if(player_fifa['player_cards'] == 'R') {
        players_update[update_length]['playerstats_cards'] = 'r';
    } else {
        players_update[update_length]['playerstats_cards'] = 0;
    }

    players_update[update_length]['playerstats_penaltieslost'] = 0;
    players_update[update_length]['playerstats_penaltiessaved'] = 0;
    players_update[update_length]['playerstats_penaltyshootout_save'] = 0;
    players_update[update_length]['playerstats_penaltyshootout_lost'] = player_fifa['player_penalties_fail'];
    players_update[update_length]['playerstats_penaltyshootout_hit'] = player_fifa['player_penalties_hit'];

    players_update[update_length]['playerstats_match_id'] = _match_id;

    //alert('addUpdate');
    //alert(player_db['player_name'] + '=' + player_fifa['player_name']);
    dispUpdateRow(update_length);
}


function markDiv(div) {
    var target = document.getElementById(div);
    target.style.border = 'solid red 2px';
}
function unmarkDiv(div) {
    var target = document.getElementById(div);
    target.style.border = 'solid black 1px';
}
function deleteDiv(div) {
    //alert('deleteDiv: ' + div);
    var target = document.getElementById(div);
    if(target) {
        target.style.border = 'none';
        target.innerHTML = '';
    }
}
function dispWait(div) {
    var string = '<div style="text-align:center"><img src="' + server + images_ + 'loading/ajax_loader_small_bar.gif" alt="loading..." width="100px" height="16px"></div>';
    var target = document.getElementById(div);
    target.innerHTML = string;
}

function deleteAllDiv() {
    deleteDiv('playerlist_home_db');
    deleteDiv('playerlist_guest_db');
    deleteDiv('playerlist_home_fifa');
    deleteDiv('playerlist_guest_fifa');
    deleteDiv('formerror');
    deleteDiv('formanswer');
    deleteDiv('playerlist_update');
    deleteDiv('send_updates_button_div');
}


function dispUpdateRow(index) {
    var checkbox = '<input type="checkbox" id="update_checkbox_' + index + '" checked>&ensp;';
    var update_div = document.getElementById('playerlist_update');

    var string = 'UPDATE STATS: ' + players_update[index]['player_name_db'] + ' == ' + players_update[index]['player_name_fifa'];
    var html = '<div id="update_div_row_' + index + '">' + checkbox + string + '</div>';

    update_div.innerHTML += html;

    if(!document.getElementById('send_updates_button')) {
        var button = '<input id="send_updates_button" type="button" value="send Updates" onclick="javascript:sendUpdates();">';
        dropLineW3('send_updates_button_div', button);
    }
}

function sendUpdates() {
    //alert($('matchresult_guest').options[$('matchresult_guest').selectedIndex].value);
    //return;
    //first send the match-result:
    var url = server + 'administration/matchpoints/setMatchresult.xml';
    //var match_homescore_penalty = -1;
    //var match_guestscore_penalty = -1;
    var match_url = document.getElementById('web_url').value;
    new Ajax.Request(url, {
 		     onSuccess : function(response) {
 		        var xmlResponse = response.responseXML;
 		        //alert(response.responseText);
 		     	var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.data;
             	if(status == 200 || status == 201) {
             	    var text = xmlResponse.getElementsByTagName('administration_answer')[0].firstChild.data;
             	    //sendStats();
             	    sendGoals();
             	} else {
             	    var text = xmlResponse.getElementsByTagName('administration_error')[0].firstChild.data;
             	}
             	dispAnswer(status, 0, players_update.length, text);
		    },

		    onFailure : function(response) {
    	    	alert("Oops, there's been an error.");
 		    },
	        parameters: '?playerstats_match_id='+_match_id+
                        '&match_homescore='+$('matchresult_home').options[$('matchresult_home').selectedIndex].value+
                        '&match_guestscore='+$('matchresult_guest').options[$('matchresult_guest').selectedIndex].value+
                        '&match_minutes='+$('matchresult_minutes').options[$('matchresult_minutes').selectedIndex].value+
                        '&match_homescore_penalty='+$('matchresult_penalty_home').options[$('matchresult_penalty_home').selectedIndex].value+
                        '&match_guestscore_penalty='+$('matchresult_penalty_guest').options[$('matchresult_penalty_guest').selectedIndex].value+
                        '&match_url='+match_url
	});
}

function sendGoals() {
    var answer_counter = 0;
    var num_checked = 0;
    for(var i=0;i<players_update.length;i++) {
        var checked = document.getElementById('update_checkbox_'+i);
        if(checked.checked) {
            num_checked++;
        }
    }
    for(var i=0;i<players_update.length;i++) {
        //var modus = players_update[i]['update_modus'];
        var checked = document.getElementById('update_checkbox_'+i);
        if(checked.checked) {
            var url = server + 'administration/matchpoints/setGoalData.xml';
            //alert(players_update[i]['playerstats_goals']);
            new Ajax.Request(url, {
             	onSuccess : function(response) {
             	var xmlResponse=response.responseXML;
             	answer_counter++;
             	//alert(response.responseText);
             	var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.data;
             	if(status == 200 || status == 201) {
             	    var text = xmlResponse.getElementsByTagName('administration_answer')[0].firstChild.data;
             	} else {
             	    var text = xmlResponse.getElementsByTagName('administration_error')[0].firstChild.data;
             	}
             	//alert(text);
             	dispAnswer(status, answer_counter,num_checked, text);
             	if(answer_counter == num_checked) {
             	    //alert('sending goals finished!');
             	    sendStats();
             	}
            	},
            	onFailure : function(response) {
                alert("Oops, there's been an error.");
             	},
             	parameters: '?playerteam_id='+players_update[i]['playerteam_id']+
                            '&playerstats_match_id='+players_update[i]['playerstats_match_id']+
                            '&playerstats_goals='+players_update[i]['playerstats_goals']+
                            '&playerstats_owngoals='+players_update[i]['playerstats_owngoals']+
                            '&playerstats_penaltyshootout_hit='+players_update[i]['playerstats_penaltyshootout_hit']+
                            '&playerstats_penaltyshootout_lost='+players_update[i]['playerstats_penaltyshootout_lost']
            });
        }
    }
}

function sendStats() {
    if(document.getElementById('send_updates_button')) {
        dropLineW3('send_updates_button_div', '');
    }
    var answer_counter = 0;
    var num_checked = 0;
    for(var i=0;i<players_update.length;i++) {
        var checked = document.getElementById('update_checkbox_'+i);
        if(checked.checked) {
            num_checked++;
        }
    }
    for(var i=0;i<players_update.length;i++) {
        var modus = players_update[i]['update_modus'];
        var checked = document.getElementById('update_checkbox_'+i);
        if(checked.checked) {
            var url = server + 'administration/matchpoints/setPlayerStats.xml';
            new Ajax.Request(url, {
             	onSuccess : function(response) {
             	var xmlResponse=response.responseXML;
             	answer_counter++;
             	//alert(response.responseText);
             	var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.data;
             	if(status == 200 || status == 201) {
             	    var text = xmlResponse.getElementsByTagName('administration_answer')[0].firstChild.data;
             	} else {
             	    var text = xmlResponse.getElementsByTagName('administration_error')[0].firstChild.data;
             	}
             	//alert(text);
             	dispAnswer(status, answer_counter, num_checked, text);
            	},
            	onFailure : function(response) {
                alert("Oops, there's been an error.");
             	},
             	parameters: '?playerteam_id='+players_update[i]['playerteam_id']+
                            '&playerstats_match_id='+players_update[i]['playerstats_match_id']+
                            '&playerstats_minutes='+players_update[i]['playerstats_minutes']+
                            '&playerstats_minute_in='+players_update[i]['playerstats_minute_in']+
                            '&playerstats_minute_out='+players_update[i]['playerstats_minute_out']+
                            '&playerstats_goals='+players_update[i]['playerstats_goals']+
                            '&playerstats_assists='+players_update[i]['playerstats_assists']+
                            '&playerstats_cards='+players_update[i]['playerstats_cards']+
                            '&playerstats_owngoals='+players_update[i]['playerstats_owngoals']+
                            '&playerstats_penaltieslost='+players_update[i]['playerstats_penaltieslost']+
                            '&playerstats_penaltiessaved='+players_update[i]['playerstats_penaltiessaved']+
                            '&playerstats_penaltyshootout_lost='+players_update[i]['playerstats_penaltyshootout_lost']+
                            '&playerstats_penaltyshootout_save='+players_update[i]['playerstats_penaltyshootout_save']+
                            '&playerstats_penaltyshootout_hit='+players_update[i]['playerstats_penaltyshootout_hit']+
                            '&playerfid_name='+players_update[i]['player_name_fifa']+
                            '&playerfid_mode='+_mode
            });
        }
    }
}

function dispAnswer(status, answer_counter, length, text) {
    //alert(text);
    if(status == 200 || status == 201) {
        var answer = '<b>Job ' + answer_counter + '/' + length + ':</b> ' + text;
        var answer_div = document.getElementById('formanswer');
        answer_div.style.visibility = 'visible';
        answer_div.innerHTML = answer + '<br>';
		console.log("blubb");
    } else {
        var error = '<b>Job ' + answer_counter + '/' + length + ':</b> ' + text;
        var error_div = document.getElementById('formerror');
        error_div.style.visibility = 'visible';
        error_div.innerHTML += error + '<br>';
    }
}

function findMatchingPlayers() {
    var count = 0;
    for(var i=0;i<players_home_fifa.length;i++) {
        var f_player_string = players_home_fifa[i]['player_name'];
        for(var j=0;j<players_home_db.length;j++) {
            if(_mode == 'foe') {
                var db_player_string = players_home_db[j]['player_name_fid_foe'];
            } else if(_mode == 'fifa') {
                var db_player_string = players_home_db[j]['player_name_fid_fifa'];
            } else if(_mode == 'tm') {
                var db_player_string = players_home_db[j]['player_name_fid_tm'];
            } else if(_mode == 'uefa') {
                var db_player_string = players_home_db[j]['player_name_fid_uefa'];
            } else if(_mode == 'wf') {
                var db_player_string = players_home_db[j]['player_name_fid_wf'];
            }
            if(f_player_string.toLowerCase() == db_player_string.toLowerCase()) {
                count++;
                //alert('FOUND: '+f_player_string+' = '+db_player_string);
                addUpdate(players_home_db[j], players_home_fifa[i]);
                _db_home_marked = 0;
                _fifa_home_marked = 0;
                deleteDiv('db_homeplayer_'+j);
                deleteDiv('fifa_homeplayer_'+i);
                break;
            }
        }
    }

    for(var i=0;i<players_guest_fifa.length;i++) {
        var f_player_string = players_guest_fifa[i]['player_name'];
        for(var j=0;j<players_guest_db.length;j++) {
            if(_mode == 'foe') {
                var db_player_string = players_guest_db[j]['player_name_fid_foe'];
            } else if(_mode == 'fifa') {
                var db_player_string = players_guest_db[j]['player_name_fid_fifa'];
            } else if(_mode == 'tm') {
                var db_player_string = players_guest_db[j]['player_name_fid_tm'];
            } else if(_mode == 'uefa') {
                var db_player_string = players_guest_db[j]['player_name_fid_uefa'];
            } else if(_mode == 'wf') {
                var db_player_string = players_guest_db[j]['player_name_fid_wf'];
            }
            if(f_player_string.toLowerCase() == db_player_string.toLowerCase()) {
                count++;
                //alert('FOUND: '+f_player_string+' = '+db_player_string);
                addUpdate(players_guest_db[j], players_guest_fifa[i]);
                _db_guest_marked = 0;
                _fifa_guest_marked = 0;
                deleteDiv('db_guestplayer_'+j);
                deleteDiv('fifa_guestplayer_'+i);
                break;
            }
        }
    }
    if(count == 0) {
        alert('No matching Names found!');
    }
}


