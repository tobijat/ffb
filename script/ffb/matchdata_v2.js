function dispMatches(matches, matchtext) {
    if(matches.length < 1) {
        matchtext += '<div id="listline"><div style="width:100%; text-align:center;"><b>noch keine Spiele eingetragen!</b></div>';
        dropLineW3('matchlist', matchtext);
        return;
    }

    var penalty = false;
    //var matchtext = '';
    matchtext += '<div id="matchinfo_list_main">';
    for(var i=0;i<matches.length;i++) {
        matchtext += '<div id="matchinfo_list_line" onmouseover="javascript:rectanglePlayers(';
		matchtext += matches[i].getElementsByTagName('match_hometeam_id')[0].firstChild.nodeValue + ', ' + matches[i].getElementsByTagName('match_guestteam_id')[0].firstChild.nodeValue;
		matchtext += ');">';
        matchtext += '<div id="matchinfo_list_date">';
        matchtext += matches[i].getElementsByTagName('match_date')[0].firstChild.data;
        matchtext += '</div>';

        matchtext += '<div id="matchinfo_list_right">';

        matchtext += '<div id="matchinfo_list_hometeam">';
        matchtext += matches[i].getElementsByTagName('match_hometeam_name')[0].firstChild.data + '&nbsp;';
        matchtext += '<img src="' + server + flagImages_;
        matchtext += matches[i].getElementsByTagName('match_hometeam_nationality')[0].firstChild.data.toLowerCase();
        matchtext += '.gif" width="16px" height="11px" title="';
        matchtext += matches[i].getElementsByTagName('match_hometeam_nationality')[0].firstChild.data;
        matchtext += '">&nbsp;';
        matchtext += '</div>';

        matchtext += '<div id="matchinfo_list_result">';
        matchtext += '<a class="under" href="javascript:void(0);" onClick="javascript:dispMatchInfo(' + matches[i].getElementsByTagName('match_id')[0].firstChild.data+ ');">';
        if(matches[i].getElementsByTagName('match_homescore_penalty')[0].firstChild.data > 0 && matches[i].getElementsByTagName('match_guestscore_penalty')[0].firstChild.data >= 0) {
            matchtext += '<b>' + matches[i].getElementsByTagName('match_homescore_penalty')[0].firstChild.data + '</b>';
            penalty = true;
        } else {
            if(matches[i].getElementsByTagName('match_homescore')[0].firstChild.data >= 0 && matches[i].getElementsByTagName('match_guestscore')[0].firstChild.data >= 0) {
                matchtext += '<b>' + matches[i].getElementsByTagName('match_homescore')[0].firstChild.data + '</b>';
            } else {
                matchtext += '<b>-</b>';
            }
        }
        matchtext += ':';
        if(matches[i].getElementsByTagName('match_homescore_penalty')[0].firstChild.data > 0 && matches[i].getElementsByTagName('match_guestscore_penalty')[0].firstChild.data >= 0) {
            matchtext += '<b>' + matches[i].getElementsByTagName('match_guestscore_penalty')[0].firstChild.data + '*</b>';
        } else {
            if(matches[i].getElementsByTagName('match_homescore')[0].firstChild.data >= 0 && matches[i].getElementsByTagName('match_guestscore')[0].firstChild.data >= 0) {
                matchtext += '<b>' + matches[i].getElementsByTagName('match_guestscore')[0].firstChild.data + '</b>';
            } else {
                matchtext += '<b>-</b>';
            }
        }
        matchtext += '</a>';
        matchtext += '</div>';

        matchtext += '<div id="matchinfo_list_guestteam">';
        matchtext += '&nbsp;<img src="' + server + flagImages_;
        matchtext += matches[i].getElementsByTagName('match_guestteam_nationality')[0].firstChild.data.toLowerCase();
        matchtext += '.gif" width="16px" height="11px" title="';
        matchtext += matches[i].getElementsByTagName('match_guestteam_nationality')[0].firstChild.data;
        matchtext += '">';
        matchtext += '&nbsp;' + matches[i].getElementsByTagName('match_guestteam_name')[0].firstChild.data;
        matchtext += '</div>'; //guestteam
        matchtext += '</div>'; //result
        matchtext += '<div style="clear:both;"></div>';
        matchtext += '</div>'; //line
        //matchtext += '<div style="clear:both;"></div>';

    }
    if(penalty) {
        matchtext += '<div id="matchinfo_list_line">&ensp;</div>';
        matchtext += '<div id="matchinfo_list_line">';
        matchtext += '<div id="matchinfo_list_date"></div>';
        matchtext += '<div id="matchinfo_list_hometeam"></div>';
        matchtext += '<div id="matchinfo_list_result"></div>';
        matchtext += '<div id="matchinfo_list_guestteam">';
        matchtext += '<b>* Elferschie&szlig;en</b>';
        matchtext += '</div></div><div id="listclear"></div>';
    }
    matchtext += '</div>';
    dropLineW3('matchlist', matchtext);
}

function dispMatchInfoHead() {
    var string = '';
    string += '<div id="infobox_name">';
    string += '</div>';
    string += '<div id="infobox_close">';
    string += '<a title="Schlie&szlig;en" href="javascript:void(0);" onClick="javascript:closeInfoPopup();"><img alt="Schlie&szlig;en" border="0" src="' + server + symbolImages_ + 'delete.png"></a>';
    string += '</div>';
    string += '<div id="listclear"></div>';

    return string;
}

function dispMatchInfo(match_id) {
    dispPopupWaiting();

    var params = '?match_id=' + match_id;
    var url = server + 'ffb/matchdata/getMatchData.xml';
    var match_data = new Array();
    var goal_data = new Array();
    var homeplayer_data = new Array();
    var guestplayer_data = new Array();
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
        //alert(response.responseText);
        var matchdata = xmlResponse.getElementsByTagName('match_data');
        match_data['match_id'] = matchdata[0].getElementsByTagName('match_id')[0].firstChild.nodeValue;
        match_data['match_hometeam_id'] = matchdata[0].getElementsByTagName('match_hometeam_id')[0].firstChild.nodeValue;
        match_data['match_guestteam_id'] = matchdata[0].getElementsByTagName('match_guestteam_id')[0].firstChild.nodeValue;
        match_data['match_hometeam_name'] = matchdata[0].getElementsByTagName('match_hometeam_name')[0].firstChild.nodeValue;
        match_data['match_guestteam_name'] = matchdata[0].getElementsByTagName('match_guestteam_name')[0].firstChild.nodeValue;
        match_data['match_hometeam_nationality'] = matchdata[0].getElementsByTagName('match_hometeam_nationality')[0].firstChild.nodeValue;
        match_data['match_guestteam_nationality'] = matchdata[0].getElementsByTagName('match_guestteam_nationality')[0].firstChild.nodeValue;
        match_data['match_hometeam_score'] = matchdata[0].getElementsByTagName('match_hometeam_score')[0].firstChild.nodeValue;
        match_data['match_guestteam_score'] = matchdata[0].getElementsByTagName('match_guestteam_score')[0].firstChild.nodeValue;
        match_data['match_hometeam_score_penalty'] = matchdata[0].getElementsByTagName('match_hometeam_score_penalty')[0].firstChild.nodeValue;
        match_data['match_guestteam_score_penalty'] = matchdata[0].getElementsByTagName('match_guestteam_score_penalty')[0].firstChild.nodeValue;
        match_data['match_minutes'] = matchdata[0].getElementsByTagName('match_minutes')[0].firstChild.nodeValue;
        match_data['match_date'] = matchdata[0].getElementsByTagName('match_date')[0].firstChild.nodeValue;
        match_data['match_game_title'] = matchdata[0].getElementsByTagName('match_game_title')[0].firstChild.nodeValue;
        match_data['match_matchround_name'] = matchdata[0].getElementsByTagName('match_matchround_name')[0].firstChild.nodeValue;
        match_data['match_matchround_id'] = matchdata[0].getElementsByTagName('match_matchround_id')[0].firstChild.nodeValue;
        var homeplayers = matchdata[0].getElementsByTagName('hometeam_players');
        homeplayer_data = homeplayers[0].getElementsByTagName('XML_Serializer_Tag');
        var guestplayers = matchdata[0].getElementsByTagName('guestteam_players');
        guestplayer_data = guestplayers[0].getElementsByTagName('XML_Serializer_Tag');
        var prevmatches = matchdata[0].getElementsByTagName('prev_matches');
        prevmatches_data = prevmatches[0].getElementsByTagName('XML_Serializer_Tag');
        var homeplayer_string = '';
        var guestplayer_string = '';
        var goal_order_string = '';
        var psgoal_string = '';
        var prevmatches_string = '';
        if(homeplayer_data.length>0) {
            var homeplayer_string = calculateHometeam(homeplayer_data, match_data['match_minutes']);
        }
        if(prevmatches_data.length>0) {
            var prevmatches_string = calculatePrevmatches(prevmatches_data);
        }
        if(guestplayer_data.length>0) {
            var guestplayer_string = calculateGuestteam(guestplayer_data, match_data['match_minutes']);
        }
        var goaldata = matchdata[0].getElementsByTagName('goal_data');
        if(goaldata.length > 0) {
            goaldata = goaldata[0].getElementsByTagName('XML_Serializer_Tag');
            if(goaldata.length > 0) {
                goal_order_string = calculateGoalOrder(goaldata, match_data['match_hometeam_id'], match_data['match_guestteam_id']);
            }
        }
        var psgoaldata = matchdata[0].getElementsByTagName('psgoal_data');
        if(psgoaldata.length > 0) {
            psgoaldata = psgoaldata[0].getElementsByTagName('XML_Serializer_Tag');
            if(psgoaldata.length > 0) {
                psgoal_string = dispPenaltyshootout(psgoaldata, match_data['match_hometeam_id'], match_data['match_guestteam_id']);
            }
        }
        //var string = '<div id="statslist">';
		var string = '<div id="statsname">';
        string += dispMatchInfoHead();
        string += '</div>';
        //string += '<div class="rounddiv_matchinfomain">';
		//string += '<div class="roundcorner_dark">';
		//string += '<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>';
        string += '<div id="matchinfo_main">';

        string += '<div id="matchdata_line">';
        string += '<div id="matchdata_hometeam">';
        string += '<img src="' + server + flagImages_;
        string += match_data['match_hometeam_nationality'].toLowerCase();
        string += '.gif" height="20px" title="';
        string += match_data['match_hometeam_nationality'];
        string += '">';
        string += match_data['match_hometeam_name'];
        string += '</div>';
        string += '<div id="matchdata_result">';
        if(parseInt(match_data['match_hometeam_score_penalty']) > -1) {
        	string += '<span title="nach Elfmeterschie&szlig;en">';
			string += match_data['match_hometeam_score_penalty'] + ':' + match_data['match_guestteam_score_penalty'] + ' n.E.</span>';
        	string += '<br>' + '<span style="font-size:12pt;" title="nach regul&auml;rer Spielzeit">(';
			string += match_data['match_hometeam_score'] + ':' + match_data['match_guestteam_score'] + ')</span>';
        } else {
	        if(match_data['match_hometeam_score'] < 0) {
	            string += '-:-';
	        } else {
	            string += match_data['match_hometeam_score'] + ':' + match_data['match_guestteam_score'];
	        }
        }
        string += '</div>';
        string += '<div id="matchdata_guestteam">';
        string += match_data['match_guestteam_name'];
        string += '<img src="' + server + flagImages_;
        string += match_data['match_guestteam_nationality'].toLowerCase();
        string += '.gif" height="20px" title="';
        string += match_data['match_guestteam_nationality'];
        string += '">';
        string += '</div>';
        string += '<div style="clear:both"></div>';
        string += '<div id="matchdata_date">';
        string += match_data['match_game_title'] + ' - ' + match_data['match_matchround_name'] + '<br>' + match_data['match_date'];
        string += '</div>';
        string += '</div>';
        string += '<div style="clear:both"></div>';

        string += '<div id="matchinfo_hr"></div>';

        string += '<div id="matchdata_line">';
        string += '<div id="matchdata_hometeam_players">';
        string += homeplayer_string;
        string += '</div>';
        string += '<div id="matchdata_teamplayers_middle">';
        string += '</div>';
        string += '<div id="matchdata_guestteam_players">';
        string += guestplayer_string;
        string += '</div>';
        string += '</div>';
        string += '<div style="clear:both"></div>';

        string += '<div id="matchinfo_hr"></div>';

        string += '<div id="goalorder">';
        string += goal_order_string;
        string += '</div>';

        string += '<div id="matchinfo_hr"></div>';

        string += '<div id="psgoal">';
        string += psgoal_string;
        string += '</div>';

        string += '<div id="matchinfo_hr"></div>';

        string += '<div id="prevmatches">';
        string += prevmatches_string;
        string += '</div></div>';

		//string += '<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>';
		//string += '</div>';
		//string += '</div>';

        //string += '</div>';

        displayInfoPopup(string, 520);

        //dropLineW3('infobox_name', match_data['match_id']);
    },
		onFailure : function(response) {
    	alert('error');
 		},
 		parameters: params
	});
}

function calculateHometeam(hometeam, match_minutes) {
    var string = '';
    for(var i=0; i<hometeam.length; i++) {
        var card = hometeam[i].getElementsByTagName('player_playerstats_cards')[0].firstChild.nodeValue;
        var goal = hometeam[i].getElementsByTagName('player_playerstats_goals')[0].firstChild.nodeValue;
        var owngoal = hometeam[i].getElementsByTagName('player_playerstats_owngoals')[0].firstChild.nodeValue;
        var playerteam_id = hometeam[i].getElementsByTagName('player_playerteam_id')[0].firstChild.nodeValue;
        var minutes_in = hometeam[i].getElementsByTagName('player_playerstats_minute_in')[0].firstChild.nodeValue;
        var minutes_out = hometeam[i].getElementsByTagName('player_playerstats_minute_out')[0].firstChild.nodeValue;

        string += '<a class="nolink" href="javascript:void(0);" onClick="javascript:dispPlayerinfoPopup(' + playerteam_id + ');">';
        string += '<img border="0" src="' + server + symbolImages_ + 'info.png" height="12px" title="Klicken f&uuml;r Spielerinfos">&nbsp;';
        string += '</a>';
        string += hometeam[i].getElementsByTagName('player_name')[0].firstChild.nodeValue;

        //string += hometeam[i].getElementsByTagName('player_name')[0].firstChild.nodeValue;

        if(minutes_in > 1) {
            string += '&nbsp;<img src="' + server + symbolImages_ + 'stats_change_in.gif" height="12px" title="Einwechslung: ' + minutes_in + '. Minute">';
        }
        if(minutes_out < match_minutes && minutes_out != 0) {
            string += '&nbsp;<img src="' + server + symbolImages_ + 'stats_change_out.gif" height="12px" title="Auswechslung: ' + minutes_out + '. Minute">';
        }

        if(card == 'y') {
            string += '&nbsp;<img src="' + server + symbolImages_ + 'stats_card_y.gif" height="12px" title="Gelbe Karte">';
        } else if(card == 'yr') {
            string += '&nbsp;<img src="' + server + symbolImages_ + 'stats_card_yr.gif" height="12px" title="Gelb-Rote Karte">';
        } else if(card == 'r') {
            string += '&nbsp;<img src="' + server + symbolImages_ + 'stats_card_r.gif" height="12px" title="Rote Karte">';
        }
        if(goal > 0) {
            string += '&nbsp;';
            for(var j=0;j<goal;j++) {
                string += '<img src="' + server + symbolImages_ + 'stats_goal.gif" height="12px" title="Tor">';
            }
        }
        if(owngoal > 0) {
            string += '&nbsp;';
            for(var j=0;j<owngoal;j++) {
                string += '<img src="' + server + symbolImages_ + 'stats_owngoal.gif" height="12px" title="Eigentor">';
            }
        }
        string += '<br>';
    }
    return string;
}

function calculateGuestteam(guestteam, match_minutes) {
    var string = '';
    //alert(match_minutes);
    for(var i=0; i<guestteam.length; i++) {
        var card = guestteam[i].getElementsByTagName('player_playerstats_cards')[0].firstChild.nodeValue;
        var goal = guestteam[i].getElementsByTagName('player_playerstats_goals')[0].firstChild.nodeValue;
        var owngoal = guestteam[i].getElementsByTagName('player_playerstats_owngoals')[0].firstChild.nodeValue;
        var playerteam_id = guestteam[i].getElementsByTagName('player_playerteam_id')[0].firstChild.nodeValue;
        var minutes_in = guestteam[i].getElementsByTagName('player_playerstats_minute_in')[0].firstChild.nodeValue;
        var minutes_out = guestteam[i].getElementsByTagName('player_playerstats_minute_out')[0].firstChild.nodeValue;

        if(goal > 0) {
            for(var j=0;j<goal;j++) {
                string += '<img src="' + server + symbolImages_ + 'stats_goal.gif" height="12px" title="Tor">';
            }
            string += '&nbsp;';
        }
        if(owngoal > 0) {
            for(var j=0;j<owngoal;j++) {
                string += '<img src="' + server + symbolImages_ + 'stats_owngoal.gif" height="12px" title="Eigentor">';
            }
            string += '&nbsp;';
        }

        if(card == 'y') {
            string += '<img src="' + server + symbolImages_ + 'stats_card_y.gif" height="12px" title="Gelbe Karte">&nbsp;';
        } else if(card == 'yr') {
            string += '<img src="' + server + symbolImages_ + 'stats_card_yr.gif" height="12px" title="Gelb-Rote Karte">&nbsp;';
        } else if(card == 'r') {
            string += '<img src="' + server + symbolImages_ + 'stats_card_r.gif" height="12px" title="Rote Karte">&nbsp;';
        }

        if(minutes_in > 1) {
            string += '<img src="' + server + symbolImages_ + 'stats_change_in.gif" height="12px" title="Einwechslung: ' + minutes_in + '. Minute">&nbsp;';
        }
        if(minutes_out < match_minutes && minutes_out != 0) {
            string += '<img src="' + server + symbolImages_ + 'stats_change_out.gif" height="12px" title="Auswechslung: ' + minutes_out + '. Minute">&nbsp;';
        }

        string += guestteam[i].getElementsByTagName('player_name')[0].firstChild.nodeValue;
        string += '<a class="nolink" href="javascript:void(0);" onClick="javascript:dispPlayerinfoPopup(' + playerteam_id + ');">';
        string += '&nbsp;<img border="0" src="' + server + symbolImages_ + 'info.png" height="12px" title="Klicken f&uuml;r Spielerinfos">';
        string += '</a>';

        string += '<br>';
    }
    return string;
}

function calculatePrevmatches(prevmatches) {
    var string = '';
    string += '<div id="prevmatches_line">';
    string += '<div id="prevmatches_title">';
    string += '<b>Bisherige Partien</b>';
    string += '</div></div>';
    for(var i=0; i<prevmatches.length; i++) {
        var match_id = prevmatches[i].getElementsByTagName('match_id')[0].firstChild.nodeValue;
        var pm_round_title = prevmatches[i].getElementsByTagName('match_matchround_name')[0].firstChild.nodeValue;
        var pm_date = prevmatches[i].getElementsByTagName('match_date')[0].firstChild.nodeValue;// + ' - ' +
                      //prevmatches[i].getElementsByTagName('match_game_title')[0].firstChild.nodeValue + ' - ' +
                      //prevmatches[i].getElementsByTagName('match_matchround_name')[0].firstChild.nodeValue;
        var pm_result = '<a class="under" href="javascript:void(0);" onClick="javascript:dispMatchInfo(' +  match_id + ');" titel="Klicken f&uuml;r Matchinfos">';
        if(parseInt(prevmatches[i].getElementsByTagName('match_hometeam_score_penalty')[0].firstChild.nodeValue) > -1) {
            pm_result += prevmatches[i].getElementsByTagName('match_hometeam_score_penalty')[0].firstChild.nodeValue + ':' + prevmatches[i].getElementsByTagName('match_guestteam_score_penalty')[0].firstChild.nodeValue + ' n.E.';
        } else {
			pm_result += prevmatches[i].getElementsByTagName('match_hometeam_score')[0].firstChild.nodeValue + ':' + prevmatches[i].getElementsByTagName('match_guestteam_score')[0].firstChild.nodeValue;
        }
        pm_result += '</a>';
        var pm_hometeam = prevmatches[i].getElementsByTagName('match_hometeam_name')[0].firstChild.nodeValue;
        var pm_guestteam = prevmatches[i].getElementsByTagName('match_guestteam_name')[0].firstChild.nodeValue;

        string += '<div id="prevmatches_line">';
        string += '<div id="prevmatches_round">';
        string += pm_round_title;
        string += '</div>';
        string += '<div id="prevmatches_date">';
        string += pm_date;
        string += '</div>';
        string += '<div id="prevmatches_hometeam">';
        string += pm_hometeam;
        string += '</div>';
        string += '<div id="prevmatches_result">';
        string += pm_result;
        string += '</div>';
        string += '<div id="prevmatches_guestteam">';
        string += pm_guestteam;
        string += '</div>';
        string += '</div>';
        string += '<div style="clear:both"></div>';
    }

    return string;
}

function calculateGoalOrder(goaldata, hometeam_id, guestteam_id) {
    var string = '';
    var homescore = 0;
    var guestscore = 0;
    var goalteam = 0;
    var owngoal = 0;
    string += '<div id="goalorder_line">';
    string += '<div id="goalorder_title">';
    string += '<b>Torfolge</b>';
    string += '</div></div>';
    for(var i=0; i<goaldata.length; i++) {
        string += '<div id="goalorder_line">';
        goalteam_id = goaldata[i].getElementsByTagName('goal_team_id')[0].firstChild.nodeValue;
        owngoal = goaldata[i].getElementsByTagName('goal_owngoal')[0].firstChild.nodeValue;
        var goal_minute = goaldata[i].getElementsByTagName('goal_minute')[0].firstChild.nodeValue;
        var goal_player_name = goaldata[i].getElementsByTagName('goal_player_name')[0].firstChild.nodeValue;
        var goal_playerteam_id = goaldata[i].getElementsByTagName('goal_playerteam_id')[0].firstChild.nodeValue;
        if(goalteam_id == hometeam_id) {
            if(owngoal>0) {
                guestscore++;
            } else {
                homescore++;
            }
        } else if(goalteam_id == guestteam_id) {
            if(owngoal>0) {
                homescore++;
            } else {
                guestscore++;
            }
        }
        string += '<div id="goalorder_minute">';
        string += goal_minute + '. Minute';
        string += '</div>';
        string += '<div id="goalorder_result">';
        string += '  <b>' + homescore + ':' + guestscore + '</b>';
        string += '</div>';
        string += '<div id="goalorder_player">';
        string += ' (<a class="nolink" href="javascript:void(0);" onClick="javascript:dispPlayerinfoPopup(' + goal_playerteam_id + ');">' + goal_player_name + '</a>';
        if(owngoal>0) {
            string += ' / ET';
        }
        string += ')';
        string += '</div></div>';
        string += '<div style="clear:both"></div>';
    }

    return string;
}

function dispPenaltyshootout(psgoaldata, hometeam_id, guestteam_id) {
    var string = '';
    string += '<div id="psgoal_line">';
    string += '<div id="psgoal_title">';
    string += '<b>Elfmeterschie&szlig;en</b>';
    string += '</div></div>';
    var home_string = '';
    var guest_string = '';
    for(var i=0; i<psgoaldata.length; i++) {
        var hit = psgoaldata[i].getElementsByTagName('psgoal_hit')[0].firstChild.nodeValue;
        var fail = psgoaldata[i].getElementsByTagName('psgoal_fail')[0].firstChild.nodeValue;
        var minute = psgoaldata[i].getElementsByTagName('psgoal_minute')[0].firstChild.nodeValue;
        var player_name = psgoaldata[i].getElementsByTagName('psgoal_player_name')[0].firstChild.nodeValue;
        var playerteam_id = psgoaldata[i].getElementsByTagName('psgoal_playerteam_id')[0].firstChild.nodeValue;
        var team_nationality = psgoaldata[i].getElementsByTagName('psgoal_team_nationality')[0].firstChild.nodeValue;
        var team_name = psgoaldata[i].getElementsByTagName('psgoal_team_name')[0].firstChild.nodeValue;
        var team_id = psgoaldata[i].getElementsByTagName('psgoal_team_id')[0].firstChild.nodeValue;

        if(parseInt(hit) == 1) {
        	var symbol = '<img src="' + server + symbolImages_ + 'stats_ps_hit.png' + '" width="16px" height="16px" alt="getroffen" title="getroffen">';
    	} else {
    		var symbol = '<img src="' + server + symbolImages_ + 'stats_ps_fail.png' + '" width="16px" height="16px" alt="nicht getroffen" title="nicht getroffen">';
    	}

		if(team_id == hometeam_id) {
			home_string += '<div id="psgoal_line">';
	        home_string += '<div id="psgoal_player">';
	        home_string += symbol;
	        home_string += '&ensp;<img src="' + server + flagImages_ + team_nationality.toLowerCase() + '.gif" width="16px" height="11px" title="';
			home_string += team_name + '">';
	        home_string += '&ensp;';
			home_string += '<a class="nolink" href="javascript:void(0);" onClick="javascript:dispPlayerinfoPopup(' + playerteam_id + ');">';
			home_string += player_name + '</a>';
	        home_string += '</div>';
	        home_string += '</div>';
	        home_string += '<div style="clear:both"></div>';
        } else if(team_id == guestteam_id) {
        	guest_string += '<div id="psgoal_line">';
	        guest_string += '<div id="psgoal_player">';
	        guest_string += '<a class="nolink" href="javascript:void(0);" onClick="javascript:dispPlayerinfoPopup(' + playerteam_id + ');">';
			guest_string += player_name + '</a>';
	        guest_string += '&ensp;<img src="' + server + flagImages_ + team_nationality.toLowerCase() + '.gif" width="16px" height="11px" title="';
			guest_string += team_name + '">';
	        guest_string += '&ensp;' + symbol;
			guest_string += '</div>';
	        guest_string += '</div>';
	        guest_string += '<div style="clear:both"></div>';
	    }
    }

    string += '<div id="psgoal_home">' + home_string + '</div>';
    string += '<div id="psgoal_guest">' + guest_string + '</div>';
    string += '<div style="clear:both"></div>';

    return string;
}
