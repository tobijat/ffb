var _player_data = new Array();
var _pricemode = '';
var _MAX_ROUNDS_PER_SITE = 15;

function dispPlayerinfoHead() {
    var string = '';
    string += '<div id="infobox_name">';
    string += '<img src="' + server + flagImages_ + _player_data['player_team_nationality'].toLowerCase() + '.gif" width="16px" height="11px" title="' + _player_data['player_team_nationality'] + '"> ';
    string += '<a class="nolink" href="javascript:void(0);" onclick="javascript:dispPlayerinfoPopup(';
	string += _player_data['player_playerteam_id'] + ');">' + _player_data['player_name'] + '</a>';
    string += ' - <em>' +  _player_data['player_team'] + '</em></div>';
    string += '<div id="infobox_close">';
    string += '<a title="Schlie&szlig;en" href="javascript:void(0);" onClick="javascript:closeInfoPopup();"><img alt="Schlie&szlig;en" border="0" src="' + server + symbolImages_ + 'delete.png"></a>';
    string += '</div>';
    string += '<div id="listclear"></div>';

    return string;
}

function dispPlayerinfoTabs() {
    var string = '';
    string += '<div id="infobox_name">';
    string += '<a href="javascript:void(0);" onClick="javascript:dispPlayerinfoPopup('+_player_data['player_playerteam_id']+');">Info</a>&nbsp;';
    string += '<a href="javascript:void(0);" onClick="javascript:dispPlayergraphicPopup('+_player_data['player_playerteam_id']+');">Grafik</a>';
    if(_pricemode == 'dynamic') {
        string += '&nbsp;<a href="javascript:void(0);" onClick="javascript:dispPlayerpricePopup('+_player_data['player_playerteam_id']+');">Preisverlauf</a>';
    }

    string += '</div>';
    string += '<div id="infobox_close"></div>';
    string += '<div id="listclear"></div>';

    return string;
}

function dispPlayerinfoPopup(playerteam_id, show_all_rounds) {
	dispPopupWaiting();
    var url = server + 'ffb/options/getLineupOptions.xml';
    new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		var options = xmlResponse.getElementsByTagName('options');
 		_pricemode = options[0].getElementsByTagName('game_pricemode')[0].firstChild.nodeValue;

        retrievePlayerInfo(playerteam_id, show_all_rounds);
		},

		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});
}

function dispPlayergraphicPopup(playerteam_id) {
    dispPopupWaiting();
    retrievePlayerGraphic(playerteam_id);
}

function dispPlayerpricePopup(playerteam_id) {
    dispPopupWaiting();
    retrievePlayerPrice(playerteam_id);
}

function dispPlayerstatsPopup(playerteam_id, matchround_id) {
    dispPopupWaiting();
    retrievePlayerStats(playerteam_id, matchround_id);
}

function retrievePlayerGraphic(playerteam_id){
    var rnr = Math.floor(Math.random()*11);
    var string = '<div id="statsname">';
    string += dispPlayerinfoHead();
    string += dispPlayerinfoTabs() + '</div>';

    var params = '?playerteam_id=' + playerteam_id + '&rnr=' + rnr;
    var playerImgStats  = new Image();
    playerImgStats.src = server + 'ffb/player/getPlayerInfoImg.img' + params;
    string += '<div id="infomain">';
    string += 	'<center><img id="playerstatsimg" src="' + server +'images/ffb/loading/ajax_loader_small_bar.gif" title="Spielerchart: ' +
				_player_data['player_name'] +
				'" />' +
				'<div class="caption">' +
				'<div class="captionitem"><div class="captioncolorpoints">&nbsp;&nbsp;&nbsp;</div>' +
				' <div class="captioncolorpointsyellow">&nbsp;&nbsp;&nbsp;</div>'+
				' <div class="captioncolorpointsred">&nbsp;&nbsp;&nbsp;</div>' +
			    '<div class="captiontext">&nbsp;FFB Punkte &amp; gelbe/rote Karte</div></div>' +
				'<div class="captionitem"><div class="captioncolorcurve">&nbsp;&nbsp;&nbsp;</div><div class="captiontext">&nbsp;Spielminuten</div></div>' +
				'<div class="captionitem"><div class="captioncolor"><img src="'+server+'images/ffb/symbols/stats_goal.gif" title="Tor" width="14px"></div><div class="captiontext">&nbsp;Tore</div></div>' +
				'<div class="captionitem"><div class="captioncolor"><img src="'+server+'images/ffb/symbols/stats_assist.gif" title="Assists" width="14px"></div><div class="captiontext">&nbsp;Assits</div></div>' +
				'<div class="captionitem"><div class="captioncolorgray">&nbsp;&nbsp;&nbsp;</div><div class="captiontext">&nbsp;nicht gespielt</div></div>';
	string += '</div></center></div>';

    displayInfoPopup(string, 520);

    $('playerstatsimg').src=playerImgStats.src;
}

function retrievePlayerPrice(playerteam_id){
    var rnr = Math.floor(Math.random()*11);
    var string = '<div id="statsname">';
    string += dispPlayerinfoHead();
    string += dispPlayerinfoTabs() + '</div>';

    var params = '?playerteam_id=' + playerteam_id + '&type=dynamic' + '&rnr=' + rnr;
    var playerImgStats  = new Image();
    playerImgStats.src = server + 'ffb/player/getPlayerInfoImg.img' + params;

    string += '<div id="infomain">';
    string += 	'<center><img id="playerstatsimg" src="' + server +'images/ffb/loading/ajax_loader_small_bar.gif" title="Spielerchart: ' +
				_player_data['player_name'] +
				'" />' + "\n" +
				'<div class="caption">' + "\n"+
				'<div class="captionitem">' +
				' <div class="captioncolorcurve">&nbsp;&nbsp;&nbsp;</div>'+
				'<div class="captiontext">&nbsp;Preiskurve / <font style="color:black;"><b>&#216; - - -</b></font></div></div>' + "\n"+
				'<div class="captionitem">' +
				' <div class="captioncolorpointsred">&nbsp;&nbsp;&nbsp;</div>' +
			    '<div class="captiontext">&nbsp;Leistungskurve / <font style="color:red;"><b>&#216; - - -</b></font></div></div>' + "\n" +
			    '<div class="captionitem">' +
			    ' <div class="captioncoloravpower"><b>&nbsp;&#216;&nbsp;</b></div>' +
			    '<div class="captiontext">&nbsp; Leistung selbe Position</div></div>';
	string += '</div></center></div>';

    displayInfoPopup(string, 520);

    $('playerstatsimg').src=playerImgStats.src;
}

function retrievePlayerInfo(playerteam_id, show_all_rounds){
    var params = '?playerteam_id=' + playerteam_id;
    var url = server + 'ffb/player/getPlayerInfo.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
        var xmlResponse=response.responseXML;
        //alert(response.responseText);

        var playerinfo = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
        _player_data['player_name'] = playerinfo[0].getElementsByTagName('player_fname')[0].firstChild.nodeValue + ' ' + playerinfo[0].getElementsByTagName('player_lname')[0].firstChild.nodeValue;
        _player_data['player_team'] = playerinfo[0].getElementsByTagName('player_team_name')[0].firstChild.nodeValue;
        _player_data['player_team_nationality'] = playerinfo[0].getElementsByTagName('player_team_nationality')[0].firstChild.nodeValue;
        _player_data['player_nationality'] = playerinfo[0].getElementsByTagName('player_nationality')[0].firstChild.nodeValue;
        _player_data['player_playerteam_id'] = playerteam_id;

        var string = '<div id="statsname">';
        string += dispPlayerinfoHead();
        string += dispPlayerinfoTabs() + '</div>';
        string += '<div id="infomain">';
        string += '<div id="infopic">';
        string += '<img src="' + playerinfo[0].getElementsByTagName('player_picture')[0].firstChild.nodeValue + '" width="100px" style="border:solid red 1px">';
            string += '</div>';
            string += '<div id="infotext">';
            string += '<div id="infoline">';
            string += '<div id="infosymbol">';
            string += '<img src="' + server + symbolImages_ + 'stats_lineup.png" width="16px" height="16px">';
            string += '</div>';
            string += '<div id="infodescr">';
            string += 'Aufstellungen gesamt:&nbsp;';
            string += '</div>';
            string += '<div id="infoamount"><b>';
            string += playerinfo[0].getElementsByTagName('num_lineups')[0].firstChild.nodeValue;
            string += 'x</b></div>';
            string += '<div id="listclear"></div>';
            string += '</div>';

            string += '<div id="infoline">';
            string += '<div id="infosymbol">';
            string += '<img src="' + server + symbolImages_ + 'stats_point.png" width="16px" height="16px">';
            string += '</div>';
            string += '<div id="infodescr">';
            string += 'Punkte gesamt/&#216;:&nbsp;';
            string += '</div>';
            string += '<div id="infoamount"><b>';
            string += playerinfo[0].getElementsByTagName('sum_score')[0].firstChild.nodeValue;
            string += '/';
            string += playerinfo[0].getElementsByTagName('av_score')[0].firstChild.nodeValue;
            string += ' Punkte</b></div>';
            string += '<div id="listclear"></div>';
            string += '</div>';

            string += '<div id="infoline">';
            string += '<div id="infosymbol">';
            string += '<img src="' + server + symbolImages_ + 'stats_goal.gif" width="16px" height="16px">';
            string += '</div>';
            string += '<div id="infodescr">';
            string += 'Tore gesamt/&#216;:&nbsp;';
            string += '</div>';
            string += '<div id="infoamount"><b>';
            string += playerinfo[0].getElementsByTagName('sum_goals')[0].firstChild.nodeValue;
            string += '/';
            string += playerinfo[0].getElementsByTagName('av_goals')[0].firstChild.nodeValue;
            string += ' Tore</b></div>';
            string += '<div id="listclear"></div>';
            string += '</div>';

            string += '<div id="infoline">';
            string += '<div id="infosymbol">';
            string += '<img src="' + server + symbolImages_ + 'stats_assist.gif" width="16px" height="16px">';
            string += '</div>';
            string += '<div id="infodescr">';
            string += 'Assists gesamt/&#216;:&nbsp;';
            string += '</div>';
            string += '<div id="infoamount"><b>';
            string += playerinfo[0].getElementsByTagName('sum_assists')[0].firstChild.nodeValue;
            string += '/';
            string += playerinfo[0].getElementsByTagName('av_assists')[0].firstChild.nodeValue;
            string += ' Assists</b></div>';
            string += '<div id="listclear"></div>';
            string += '</div>';

            string += '<div id="infoline">';
            string += '<div id="infosymbol">';
            string += '<img src="' + server + symbolImages_ + 'stats_card_yr.gif" width="16px" height="16px">';
            string += '</div>';
            string += '<div id="infodescr">';
            string += 'Karten (G/GR/R):&nbsp;';
            string += '</div>';
            string += '<div id="infoamount"><b>';
            string += playerinfo[0].getElementsByTagName('sum_cards_y')[0].firstChild.nodeValue + '/';
            string += playerinfo[0].getElementsByTagName('sum_cards_yr')[0].firstChild.nodeValue + '/';
            string += playerinfo[0].getElementsByTagName('sum_cards_r')[0].firstChild.nodeValue;
            string += ' Karten</b></div>';
            string += '<div id="listclear"></div>';
            string += '</div>';

            string += '<div id="infoline">';
            string += '<div id="infosymbol">';
            string += '<img src="' + server + symbolImages_ + 'stats_time.png" width="16px" height="16px">';
            string += '</div>';
            string += '<div id="infodescr">';
            string += 'Einsatz gesamt/&#216;:&nbsp;';
            string += '</div>';
            string += '<div id="infoamount"><b>';
            string += playerinfo[0].getElementsByTagName('sum_minutes')[0].firstChild.nodeValue;
            string += '/';
            string += playerinfo[0].getElementsByTagName('av_minutes')[0].firstChild.nodeValue;
            string += ' Minuten</b></div>';
            string += '<div id="listclear"></div>';
            string += '</div>';

            string += '<div id="infoline">';
            string += '<div id="infosymbol">';
            string += '<img src="' + server + symbolImages_ + 'symbol_effectivity.png" width="16px" height="16px">';
            string += '</div>';
            string += '<div id="infodescr">';
            string += 'Effektivit&auml;t:&nbsp;';
            string += '</div>';
            string += '<div id="infoamount"><b>';
            var tmpEfficiency = "-";
            if(playerinfo[0].getElementsByTagName('sum_minutes')[0].firstChild.nodeValue && playerinfo[0].getElementsByTagName('sum_minutes')[0].firstChild.nodeValue!=0) {
            	tmpEfficiency = Math.round((playerinfo[0].getElementsByTagName('sum_score')[0].firstChild.nodeValue/playerinfo[0].getElementsByTagName('sum_minutes')[0].firstChild.nodeValue)*10000)/100;
            }
            string += tmpEfficiency;
            string += ' Punkte</b></div>';
            string += '<div id="listclear"></div>';
            string += '</div>';

            string += '</div>';
            string += '<div style="clear:both"></div>';

            string += '<div id="inforounds">';
            var minutes_played = xmlResponse.getElementsByTagName('matchrounds')[0].getElementsByTagName('XML_Serializer_Tag');
            if(minutes_played.length > 0) {
                string += '<table class="inforounds"><thead><tr>';
                string += '<th><b>Runde</b></th>';
                string += '<th><b>Ergebnis</b></th>';
                string += '<th><img src="' + server + symbolImages_ + 'stats_lineup.png" width="16px" height="16px" title="Anzahl Aufstellungen"></th>';
                string += '<th><img src="' + server + symbolImages_ + 'stats_time.png" width="16px" height="16px" title="Einsatz"></th>';
                string += '<th><img src="' + server + symbolImages_ + 'stats_goal.gif" width="16px" height="16px" title="Tore"></th>';
                string += '<th><img src="' + server + symbolImages_ + 'stats_assist.gif" width="16px" height="16px" title="Assists"></th>';
                string += '<th><img src="' + server + symbolImages_ + 'stats_card_yr.gif" width="16px" height="16px" title="Karten"></th>';
                string += '<th><img src="' + server + symbolImages_ + 'stats_point.png" width="16px" height="16px" title="Punkte"></th>';
                string += '</tr></thead><tbody>';
            }
			var counter=0;
			//alert(minutes_played.length);
            for(var i=0;i<minutes_played.length;i++) {
            	counter++;
            	if(!show_all_rounds && counter>_MAX_ROUNDS_PER_SITE) {
            		break;
            	}
            	string += '<tr>';
            	var title = 'Datum: ' + minutes_played[i].getElementsByTagName('match_date')[0].firstChild.nodeValue;
            	if(parseInt(minutes_played[i].getElementsByTagName('matchround_running')[0].firstChild.nodeValue) == 1) {
					string += '<td style="color:#FF0000;" title="' + title + '">';
            	} else {
            		string += '<td title="' + title + '">';
            	}
                string += minutes_played[i].getElementsByTagName('matchround_title')[0].firstChild.nodeValue + '</td>';
                string += '<td style="min-width:150px;">';
				if(parseInt(minutes_played[i].getElementsByTagName('match_id')[0].firstChild.nodeValue)>0) {
	                string += minutes_played[i].getElementsByTagName('matchround_hometeam_name')[0].firstChild.nodeValue + ' ';
	                string += '<a class="under" href="javascript:void(0);" onClick="javascript:dispMatchInfo(' + minutes_played[i].getElementsByTagName('match_id')[0].firstChild.nodeValue + ');">';
	                if(parseInt(minutes_played[i].getElementsByTagName('matchround_hometeam_score_penalty')[0].firstChild.nodeValue)>-1) {
		                string += minutes_played[i].getElementsByTagName('matchround_hometeam_score_penalty')[0].firstChild.nodeValue + ':';
		                string += minutes_played[i].getElementsByTagName('matchround_guestteam_score_penalty')[0].firstChild.nodeValue + ' n.E.';
	                } else {
						if(minutes_played[i].getElementsByTagName('matchround_hometeam_score')[0].firstChild.nodeValue<0) {
		                	string +=  '-:';
		               	} else {
		                	string += minutes_played[i].getElementsByTagName('matchround_hometeam_score')[0].firstChild.nodeValue + ':';
		               	}
						if(minutes_played[i].getElementsByTagName('matchround_guestteam_score')[0].firstChild.nodeValue<0) {
		                	string +=  '-';
		               	} else {
		                	string += minutes_played[i].getElementsByTagName('matchround_guestteam_score')[0].firstChild.nodeValue;
		                }
	                }
	                string += '</a> ';
	                string += minutes_played[i].getElementsByTagName('matchround_guestteam_name')[0].firstChild.nodeValue;
                } else {
                	string += '<em>nicht eingesetzt</em>';
                }

                string += '</td>';
                string += '<td>' + minutes_played[i].getElementsByTagName('matchround_num_lineups')[0].firstChild.nodeValue + '</td>';
                string += '<td>' + minutes_played[i].getElementsByTagName('matchround_minutes_played')[0].firstChild.nodeValue + '</td>';
                string += '<td>' + minutes_played[i].getElementsByTagName('matchround_goals')[0].firstChild.nodeValue + '</td>';
                string += '<td>' + minutes_played[i].getElementsByTagName('matchround_assists')[0].firstChild.nodeValue + '</td>';
                var card = minutes_played[i].getElementsByTagName('matchround_cards')[0].firstChild.nodeValue;
                if(card=='y') {
                    string += '<td><img src="' + server + symbolImages_ + 'stats_card_y.gif" width="16px" height="16px"></td>';
                } else if(card=='yr') {
                    string += '<td><img src="' + server + symbolImages_ + 'stats_card_yr.gif" width="16px" height="16px"></td>';
                } else if(card=='r') {
                    string += '<td><img src="' + server + symbolImages_ + 'stats_card_r.gif" width="16px" height="16px"></td>';
                } else {
                    string += '<td>-</td>';
                }
                string += '<td>';
				string += '<a class="nolink" href="javascript:dispPlayerstatsPopup('+playerteam_id+','+minutes_played[i].getElementsByTagName('matchround_id')[0].firstChild.nodeValue+');">';
				string += '<b><u>' + minutes_played[i].getElementsByTagName('matchround_score')[0].firstChild.nodeValue + '</u></b></a></td>';

                string += '</tr>';
            }

        string += '</tbody></table>';
        //alert(minutes_played.length+'/'+_MAX_ROUNDS_PER_SITE);
		if(!show_all_rounds && minutes_played.length>_MAX_ROUNDS_PER_SITE) {
	        string += '<div style="width:100%; text-align:center; padding-top:2px;">';
			string += '<a href="javascript:void(0);" onclick="javascript:dispPlayerinfoPopup('+playerteam_id+', 1);">alle Runden anzeigen</a>';
			string += '</div>';
		}

        string += '</div>';

        var past_matches = xmlResponse.getElementsByTagName('pastmatches')[0].getElementsByTagName('XML_Serializer_Tag');
        if(past_matches.length > 0) {
            string += '<div style="width:100%; text-align:center;"><b>Vergangene Spiele</b></div>';
        	string += '<div id="inforounds">';
            string += '<table class="inforounds"><thead><tr>';
            string += '<th><b>Runde</b></th>';
            string += '<th><b>Ergebnis</b></th>';
            string += '<th><img src="' + server + symbolImages_ + 'stats_lineup.png" width="16px" height="16px" title="Anzahl Aufstellungen"></th>';
            string += '<th><img src="' + server + symbolImages_ + 'stats_time.png" width="16px" height="16px" title="Einsatz"></th>';
            string += '<th><img src="' + server + symbolImages_ + 'stats_goal.gif" width="16px" height="16px" title="Tore"></th>';
            string += '<th><img src="' + server + symbolImages_ + 'stats_assist.gif" width="16px" height="16px" title="Assists"></th>';
            string += '<th><img src="' + server + symbolImages_ + 'stats_card_yr.gif" width="16px" height="16px" title="Karten"></th>';
            string += '<th><img src="' + server + symbolImages_ + 'stats_point.png" width="16px" height="16px" title="Punkte"></th>';
            string += '</tr></thead><tbody>';

			var counter=0;
			//alert(minutes_played.length);
            for(var i=0;i<past_matches.length;i++) {
            	counter++;
            	string += '<tr>';
            	string += '<td title="Datum: ' + past_matches[i].getElementsByTagName('match_date')[0].firstChild.nodeValue + '">';
                string += past_matches[i].getElementsByTagName('matchround_title')[0].firstChild.nodeValue + '</td>';
                string += '<td style="min-width:150px;">';
				if(parseInt(past_matches[i].getElementsByTagName('match_id')[0].firstChild.nodeValue)>0) {
	                string += past_matches[i].getElementsByTagName('matchround_hometeam_name')[0].firstChild.nodeValue + ' ';
	                string += '<a class="under" href="javascript:void(0);" onClick="javascript:dispMatchInfo(' + past_matches[i].getElementsByTagName('match_id')[0].firstChild.nodeValue + ');">';
	                if(parseInt(past_matches[i].getElementsByTagName('matchround_hometeam_score_penalty')[0].firstChild.nodeValue)>-1) {
		                string += past_matches[i].getElementsByTagName('matchround_hometeam_score_penalty')[0].firstChild.nodeValue + ':';
		                string += past_matches[i].getElementsByTagName('matchround_guestteam_score_penalty')[0].firstChild.nodeValue + ' n.E.';
	                } else {
						if(past_matches[i].getElementsByTagName('matchround_hometeam_score')[0].firstChild.nodeValue<0) {
		                	string +=  '-:';
		               	} else {
		                	string += past_matches[i].getElementsByTagName('matchround_hometeam_score')[0].firstChild.nodeValue + ':';
		               	}
						if(past_matches[i].getElementsByTagName('matchround_guestteam_score')[0].firstChild.nodeValue<0) {
		                	string +=  '-';
		               	} else {
		                	string += past_matches[i].getElementsByTagName('matchround_guestteam_score')[0].firstChild.nodeValue;
		                }
	                }
	                string += '</a> ';
	                string += past_matches[i].getElementsByTagName('matchround_guestteam_name')[0].firstChild.nodeValue;
                } else {
                	string += '<em>nicht eingesetzt</em>';
                }

                string += '</td>';
                string += '<td>' + past_matches[i].getElementsByTagName('matchround_num_lineups')[0].firstChild.nodeValue + '</td>';
                string += '<td>' + past_matches[i].getElementsByTagName('matchround_minutes_played')[0].firstChild.nodeValue + '</td>';
                string += '<td>' + past_matches[i].getElementsByTagName('matchround_goals')[0].firstChild.nodeValue + '</td>';
                string += '<td>' + past_matches[i].getElementsByTagName('matchround_assists')[0].firstChild.nodeValue + '</td>';
                var card = past_matches[i].getElementsByTagName('matchround_cards')[0].firstChild.nodeValue;
                if(card=='y') {
                    string += '<td><img src="' + server + symbolImages_ + 'stats_card_y.gif" width="16px" height="16px"></td>';
                } else if(card=='yr') {
                    string += '<td><img src="' + server + symbolImages_ + 'stats_card_yr.gif" width="16px" height="16px"></td>';
                } else if(card=='r') {
                    string += '<td><img src="' + server + symbolImages_ + 'stats_card_r.gif" width="16px" height="16px"></td>';
                } else {
                    string += '<td>-</td>';
                }
                string += '<td>';
				string += '<a class="nolink" href="javascript:dispPlayerstatsPopup('+playerteam_id+','+past_matches[i].getElementsByTagName('matchround_id')[0].firstChild.nodeValue+');">';
				string += '<b><u>' + past_matches[i].getElementsByTagName('matchround_score')[0].firstChild.nodeValue + '</u></b></a></td>';

                string += '</tr>';
            }

        	string += '</tbody></table>';
        	string += '</div>';
		}

        string += '</div>';

        displayInfoPopup(string, 520);

        },
		onFailure : function(response) {
    	alert('error');
 		},
 		parameters: params
	});
}

function retrievePlayerStats(playerteam_id, matchround_id){
    var params = '?matchround_id=' + matchround_id + '&playerteam_id=' + playerteam_id;
    var url = server + 'ffb/player/getPlayerStats.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
        var xmlResponse=response.responseXML;
        //alert(response.responseText);
        var playerstats = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
        _player_data['player_name'] = playerstats[0].getElementsByTagName('player_fname')[0].firstChild.nodeValue + ' ' + playerstats[0].getElementsByTagName('player_lname')[0].firstChild.nodeValue;
        _player_data['player_team'] = playerstats[0].getElementsByTagName('player_team_name')[0].firstChild.nodeValue;
        _player_data['player_team_nationality'] = playerstats[0].getElementsByTagName('player_team_nationality')[0].firstChild.nodeValue;
        _player_data['player_nationality'] = playerstats[0].getElementsByTagName('player_nationality')[0].firstChild.nodeValue;
        _player_data['player_playerteam_id'] = playerteam_id;

        if(xmlResponse.getElementsByTagName('played')[0].firstChild.nodeValue==1) {
 		    var player_team_name = playerstats[0].getElementsByTagName('player_team_name')[0].firstChild.nodeValue;
 		    var playerstats_goals =  playerstats[0].getElementsByTagName('playerstats_goals')[0].firstChild.nodeValue;
 		    var playerstats_minutes = playerstats[0].getElementsByTagName('playerstats_minutes')[0].firstChild.nodeValue;
 		    var playerstats_minute_in = playerstats[0].getElementsByTagName('playerstats_minute_in')[0].firstChild.nodeValue;
 		    var playerstats_minute_out = playerstats[0].getElementsByTagName('playerstats_minute_out')[0].firstChild.nodeValue;
 		    var playerstats_oppgoals = playerstats[0].getElementsByTagName('playerstats_oppgoals')[0].firstChild.nodeValue;
 		    var player_fname = playerstats[0].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
 		    var player_lname = playerstats[0].getElementsByTagName('player_lname')[0].firstChild.nodeValue;

            var string = '<div id="statsname">';
            string += dispPlayerinfoHead() + '</div>';

            string += '<div id="infomain">';
            string += '<div id="infopic">';
            string += '<img src="' + playerstats[0].getElementsByTagName('player_picture')[0].firstChild.nodeValue + '" width="100px" style="border:solid red 1px">';
            string += '</div>';
            string += '<div id="infotext">';
            string += '<div id="statsline">';
            string += '<div id="statssymbol">';
            string += '<img src="' + server + symbolImages_ + 'stats_time.png" width="16px" height="16px">';
            string += '</div>';
            string += '<div id="statsdescr">';
            string += 'Zeit: ';
            if(playerstats_minute_in>0 && playerstats_minute_out>0) {
                string += '<img src="' + server + symbolImages_ + 'stats_change_in.gif" width="16px" height="11px">';
                string += playerstats_minute_in + '. ';
                string += '<img src="' + server + symbolImages_ + 'stats_change_out.gif" width="16px" height="11px">';
                string += playerstats_minute_out + '.';
            }
            string += ':&nbsp;';
            string += '</div>';
            string += '<div id="statsamount">';
            string += playerstats[0].getElementsByTagName('playerstats_minutes')[0].firstChild.nodeValue + ' Min';
            string += '</div>';
            string += '<div id="statspoints"><b>+';
            string += playerstats[0].getElementsByTagName('playerstats_score_minutes')[0].firstChild.nodeValue;
            string += ' Punkte';
            string += '</b></div>';
            string += '<div id="listclear"></div>';
            string += '</div>';

            if(playerstats[0].getElementsByTagName('playerstats_goals')[0].firstChild.nodeValue>0) {
                string += '<div id="statsline">';
                string += '<div id="statssymbol">';
                string += '<img src="' + server + symbolImages_ + 'stats_goal.gif" width="16px" height="16px">';
                string += '</div>';
                string += '<div id="statsdescr">';
                string += 'Tore:&nbsp;';
                string += '</div>';
                string += '<div id="statsamount">';
                string += playerstats[0].getElementsByTagName('playerstats_goals')[0].firstChild.nodeValue;
                string += '</div>';
                string += '<div id="statspoints"><b>+';
                string += playerstats[0].getElementsByTagName('playerstats_score_goals')[0].firstChild.nodeValue;
                string += ' Punkte';
                string += '</b></div>';
                string += '<div id="listclear"></div>';
                string += '</div>';
            }

            if(playerstats[0].getElementsByTagName('playerstats_assists')[0].firstChild.nodeValue>0) {
                string += '<div id="statsline">';
                string += '<div id="statssymbol">';
                string += '<img src="' + server + symbolImages_ + 'stats_assist.gif" width="16px" height="16px">';
                string += '</div>';
                string += '<div id="statsdescr">';
                string += 'Assists:&nbsp;';
                string += '</div>';
                string += '<div id="statsamount">';
                string += playerstats[0].getElementsByTagName('playerstats_assists')[0].firstChild.nodeValue;
                string += '</div>';
                string += '<div id="statspoints"><b>+';
                string += playerstats[0].getElementsByTagName('playerstats_score_assists')[0].firstChild.nodeValue;
                string += ' Punkte';
                string += '</b></div>';
                string += '<div id="listclear"></div>';
                string += '</div>';
            }

            if(playerstats[0].getElementsByTagName('playerstats_penaltiessaved')[0].firstChild.nodeValue>0) {
                string += '<div id="statsline">';
                string += '<div id="statssymbol">';
                string += '<img src="' + server + symbolImages_ + 'stats_penaltysaved.png" width="16px" height="16px">';
                string += '</div>';
                string += '<div id="statsdescr">';
                string += 'Elfer gehalten:&nbsp;';
                string += '</div>';
                string += '<div id="statsamount">';
                string += playerstats[0].getElementsByTagName('playerstats_penaltiessaved')[0].firstChild.nodeValue;
                string += '</div>';
                string += '<div id="statspoints"><b>+';
                string += playerstats[0].getElementsByTagName('playerstats_score_penaltiessaved')[0].firstChild.nodeValue;
                string += ' Punkte';
                string += '</b></div>';
                string += '<div id="listclear"></div>';
                string += '</div>';
            }

            if(playerstats[0].getElementsByTagName('playerstats_penaltyshootout_save')[0].firstChild.nodeValue>0) {
                string += '<div id="statsline" title="Elfmeterschie&szlig;en - gehalten">';
                string += '<div id="statssymbol">';
                string += '<img src="' + server + symbolImages_ + 'stats_ps_hit.png" width="16px" height="16px">';
                string += '</div>';
                string += '<div id="statsdescr">';
                string += 'Elfmeterschie&szlig;en:&nbsp;';
                string += '</div>';
                string += '<div id="statsamount">';
                string += playerstats[0].getElementsByTagName('playerstats_penaltyshootout_save')[0].firstChild.nodeValue;
                string += '</div>';
                string += '<div id="statspoints"><b>+';
                string += playerstats[0].getElementsByTagName('playerstats_score_penaltyshootout_save')[0].firstChild.nodeValue;
                string += ' Punkte';
                string += '</b></div>';
                string += '<div id="listclear"></div>';
                string += '</div>';
            }

            if(playerstats[0].getElementsByTagName('playerstats_penaltyshootout_hit')[0].firstChild.nodeValue>0) {
                string += '<div id="statsline" title="Elfmeterschie&szlig;en - getroffen">';
                string += '<div id="statssymbol">';
                string += '<img src="' + server + symbolImages_ + 'stats_ps_hit.png" width="16px" height="16px">';
                string += '</div>';
                string += '<div id="statsdescr">';
                string += 'Elfmeterschie&szlig;en:&nbsp;';
                string += '</div>';
                string += '<div id="statsamount">';
                string += playerstats[0].getElementsByTagName('playerstats_penaltyshootout_hit')[0].firstChild.nodeValue;
                string += '</div>';
                string += '<div id="statspoints"><b>+';
                string += playerstats[0].getElementsByTagName('playerstats_score_penaltyshootout_hit')[0].firstChild.nodeValue;
                string += ' Punkte';
                string += '</b></div>';
                string += '<div id="listclear"></div>';
                string += '</div>';
            }

            if(playerstats[0].getElementsByTagName('playerstats_score_nooppgoals')[0].firstChild.nodeValue!=0) {
                string += '<div id="statsline">';
                string += '<div id="statssymbol">';
                string += '<img src="' + server + symbolImages_ + 'stats_oppgoal.gif" width="16px" height="16px">';
                string += '</div>';
                string += '<div id="statsdescr">';
                string += 'Gegentore:&nbsp;';
                string += '</div>';
                string += '<div id="statsamount">';
                string += playerstats[0].getElementsByTagName('playerstats_oppgoals')[0].firstChild.nodeValue;
                string += '</div>';
                string += '<div id="statspoints"><b>+';
                string += playerstats[0].getElementsByTagName('playerstats_score_nooppgoals')[0].firstChild.nodeValue;
                string += ' Punkte';
                string += '</b></div>';
                string += '<div id="listclear"></div>';
                string += '</div>';
            }

            if(playerstats[0].getElementsByTagName('playerstats_score_oppgoals')[0].firstChild.nodeValue!=0) {
                if(playerstats[0].getElementsByTagName('playerstats_player_oppgoals_string')[0].firstChild.nodeValue!=0) {
                    string += '<div id="statsline" title="Minuten: ' + playerstats[0].getElementsByTagName('playerstats_player_oppgoals_string')[0].firstChild.nodeValue + '">';
                } else {
                    string += '<div id="statsline">';
                }
                string += '<div id="statssymbol">';
                string += '<img src="' + server + symbolImages_ + 'stats_oppgoal.gif" width="16px" height="16px">';
                string += '</div>';
                string += '<div id="statsdescr">';
                string += 'Gegentore:&nbsp;';
                string += '</div>';
                string += '<div id="statsamount">';
                string += playerstats[0].getElementsByTagName('playerstats_player_oppgoals')[0].firstChild.nodeValue;
                string += '</div>';
                string += '<div id="statspoints"><b>';
                string += playerstats[0].getElementsByTagName('playerstats_score_oppgoals')[0].firstChild.nodeValue;
                string += ' Punkte';
                string += '</b></div>';
                string += '<div id="listclear"></div>';
                string += '</div>';
            }

            if(playerstats[0].getElementsByTagName('playerstats_cards')[0].firstChild.nodeValue!='n') {
                string += '<div id="statsline">';
                string += '<div id="statssymbol">';
                if(playerstats[0].getElementsByTagName('playerstats_cards')[0].firstChild.nodeValue=='y') {
                    string += '<img src="' + server + symbolImages_ + 'stats_card_y.gif" width="16px" height="16px">';
                } else if(playerstats[0].getElementsByTagName('playerstats_cards')[0].firstChild.nodeValue=='r') {
                    string += '<img src="' + server + symbolImages_ + 'stats_card_r.gif" width="16px" height="16px">';
                } else if(playerstats[0].getElementsByTagName('playerstats_cards')[0].firstChild.nodeValue=='yr') {
                    string += '<img src="' + server + symbolImages_ + 'stats_card_yr.gif" width="16px" height="16px">';
                }
                string += '</div>';
                string += '<div id="statsdescr">';
                string += 'Karten:&nbsp;';
                string += '</div>';
                string += '<div id="statsamount">';
                if(playerstats[0].getElementsByTagName('playerstats_cards')[0].firstChild.nodeValue=='y') {
                    string += 'GELB';
                } else if(playerstats[0].getElementsByTagName('playerstats_cards')[0].firstChild.nodeValue=='r') {
                    string += 'ROT';
                } else if(playerstats[0].getElementsByTagName('playerstats_cards')[0].firstChild.nodeValue=='yr') {
                    string += 'GELB-ROT';
                }
                //string += playerstats[0].getElementsByTagName('playerstats_cards')[0].firstChild.nodeValue.toUpperCase();
                string += '</div>';
                string += '<div id="statspoints"><b>';
                string += playerstats[0].getElementsByTagName('playerstats_score_cards')[0].firstChild.nodeValue;
                string += ' Punkte';
                string += '</b></div>';
                string += '<div id="listclear"></div>';
                string += '</div>';
            }

            if(playerstats[0].getElementsByTagName('playerstats_owngoals')[0].firstChild.nodeValue>0) {
                string += '<div id="statsline">';
                string += '<div id="statssymbol">';
                string += '<img src="' + server + symbolImages_ + 'stats_owngoal.gif" width="16px" height="16px">';
                string += '</div>';
                string += '<div id="statsdescr">';
                string += 'Eigentore:&nbsp;';
                string += '</div>';
                string += '<div id="statsamount">';
                string += playerstats[0].getElementsByTagName('playerstats_owngoals')[0].firstChild.nodeValue;
                string += '</div>';
                string += '<div id="statspoints"><b>';
                string += playerstats[0].getElementsByTagName('playerstats_score_owngoals')[0].firstChild.nodeValue;
                string += ' Punkte';
                string += '</b></div>';
                string += '<div id="listclear"></div>';
                string += '</div>';
            }

            if(playerstats[0].getElementsByTagName('playerstats_penaltieslost')[0].firstChild.nodeValue>0) {
                string += '<div id="statsline">';
                string += '<div id="statssymbol">';
                string += '<img src="' + server + symbolImages_ + 'stats_penaltylost.png" width="16px" height="16px">';
                string += '</div>';
                string += '<div id="statsdescr">';
                string += 'Elfer verschossen:&nbsp;';
                string += '</div>';
                string += '<div id="statsamount">';
                string += playerstats[0].getElementsByTagName('playerstats_penaltieslost')[0].firstChild.nodeValue;
                string += '</div>';
                string += '<div id="statspoints"><b>';
                string += playerstats[0].getElementsByTagName('playerstats_score_penaltieslost')[0].firstChild.nodeValue;
                string += ' Punkte';
                string += '</b></div>';
                string += '<div id="listclear"></div>';
                string += '</div>';
            }

            if(playerstats[0].getElementsByTagName('playerstats_penaltyshootout_lost')[0].firstChild.nodeValue>0) {
                string += '<div id="statsline" title="Elfmeterschie&szlig;en - nicht getroffen">';
                string += '<div id="statssymbol">';
                string += '<img src="' + server + symbolImages_ + 'stats_ps_fail.png" width="16px" height="16px">';
                string += '</div>';
                string += '<div id="statsdescr">';
                string += 'Elfmeterschie&szlig;en:&nbsp;';
                string += '</div>';
                string += '<div id="statsamount">';
                string += playerstats[0].getElementsByTagName('playerstats_penaltyshootout_lost')[0].firstChild.nodeValue;
                string += '</div>';
                string += '<div id="statspoints"><b>';
                string += playerstats[0].getElementsByTagName('playerstats_score_penaltyshootout_lost')[0].firstChild.nodeValue;
                string += ' Punkte';
                string += '</b></div>';
                string += '<div id="listclear"></div>';
                string += '</div>';
            }

            string += '<div id="statsline">';
            string += '<div id="statssymbol">';
            string += '&nbsp;'
            string += '</div>';
            string += '<div id="statsdescr">';
            string += '<b>Summe</b>';
            string += '</div>';
            string += '<div id="statsamount">';
            string += '&nbsp;'
            string += '</div>';
            string += '<div id="statspoints"><b>';
            if(playerstats[0].getElementsByTagName('playerstats_score')[0].firstChild.nodeValue>=0) {
                string += '+';
            }
            string += playerstats[0].getElementsByTagName('playerstats_score')[0].firstChild.nodeValue;
            string += ' Punkte';
            string += '</b></div>';
            string += '<div id="listclear"></div>';
            string += '</div>';

        } else {
            var playerstats = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		    var player_team_name = playerstats[0].getElementsByTagName('player_team_name')[0].firstChild.nodeValue;
 		    var player_fname = playerstats[0].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
 		    var player_lname = playerstats[0].getElementsByTagName('player_lname')[0].firstChild.nodeValue;
 		    var string = '<div id="statsname">';
            string += dispPlayerinfoHead() + '</div>';
            string += '<div id="listclear"></div>';
            string += '<div id="statsline">';
            string += '<div id="nostats">';
            string += 'nicht eingesetzt';
            string += '</div>';
            string += '</div>';
        }
        string += '</div>';
        string += '<div style="clear:both"></div>';
        string += '</div>';

        displayInfoPopup(string, 520);

        },
		onFailure : function(response) {
    	alert('error');
 		},
 		parameters: params
	});
}
