var _teams = new Array();
var _leagues = new Array();
var _startTime = 0;
var _inetzTime = 0;
var CONST_TEAM = 0;
var CONST_PLAYER = 1;
var CONST_LEAGUE = 2;
var _ROUND = 100;
var _TEAM_AMOUNT = 2;
var _PLAYER_AMOUNT = 3;
var _playerStats = new Array();
var _imgLINEUP = '<img class="img0" src="' + server + symbolImages_ + 'stats_lineup.png" alt="Einsatz"/>Eins&auml;tze<br/>';
var _imgPOINTS = '<img class="img1" src="' + server + symbolImages_ + 'stats_point.png" alt="Punkte" />Punkte<br/>';
var _imgGOALS = '<img class="img0" src="' + server + symbolImages_ + 'stats_goal.gif" alt="Tore" />Tore<br/>';
var _imgCARDSYR = '<img class="img1" src="' + server + symbolImages_ + 'stats_card_yr.gif" alt="gelbrote Karte" /><br/>';
var _imgCARDSY = '<img class="img0" src="' + server + symbolImages_ + 'stats_card_y.gif" alt="gelbe Karte" />';
var _imgCARDSR = '<img class="img1" src="' + server + symbolImages_ + 'stats_card_r.gif" alt="rote Karte" />';
var _imgMINUTES = '<img class="img0" src="' + server + symbolImages_ + 'stats_time.png" alt="Minuten" />Minuten<br/>';
var _imgASSIST = '<img class="img1" src="' + server + symbolImages_ + 'stats_assist.gif" alt="Assists" />Assists<br/>';
var _imgEFFICIENCY = '<img class="img0" src="' + server + symbolImages_ + 'symbol_effectivity.png" alt="Effizienz" />Effizienz ';
var _imgOWNGOAL = '<img class="img1" src="' + server + symbolImages_ + 'stats_owngoal.gif" alt="Eigentor" />Eigentore<br/>';
var _imgPSLOST = '<img class="img0" src="' + server + symbolImages_ + 'stats_penaltylost.png" alt="Elfmeter verschossen" />Elfmeter verschossen<br/>';
var _imgPSSAVE = '<img class="img0" src="' + server + symbolImages_ + 'stats_penaltysaved.png" alt="Elfmeter gehalten" />Elfmeter gehalten<br/>';

function loadStatsMenu() {
	loadTeams();
	loadLeagues();
	loadCountrys();
}

function loadTeams(){
	_startTime = new Date().getTime();
	_inetzTime = new Date().getTime();
    var url = server + 'ffb/stats/getTeams.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
		dropLineW3('durationNW', 'NWDauer:'  + (((new Date().getTime() - _inetzTime)/1000.0) - xmlResponse.getElementsByTagName('duration')[0].firstChild.nodeValue) + 's');
        var teams = xmlResponse.getElementsByTagName('teams')[0].getElementsByTagName('XML_Serializer_Tag');

        if(teams.length<=0) {
 		    alert('error: no teams found!');
 		    return;
 		}
		_teams = teams;
		//dropLineW3('duration', teams_);
		dropLineW3('durationPHP', 'PHPDauer:' + xmlResponse.getElementsByTagName('duration')[0].firstChild.nodeValue + 's');
		dropLineW3('durationJS', 'JSDauer:' + ((new Date().getTime() - _startTime)/1000.0) + 's(=total)');
        dispTeams();
		},

		onFailure : function(response) {
    	   handleAjaxError();
 		}
	});
}

function loadLeagues() {
	_startTime = new Date().getTime();
	_inetzTime = new Date().getTime();
    var url = server + 'ffb/stats/getLeagues.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
		dropLineW3('durationNW', 'NWDauer:'  + (((new Date().getTime() - _inetzTime)/1000.0) - xmlResponse.getElementsByTagName('duration')[0].firstChild.nodeValue) + 's');
        var leagues = xmlResponse.getElementsByTagName('leagues')[0].getElementsByTagName('XML_Serializer_Tag');

        if(leagues.length<=0) {
 		    alert('error: no teams found!');
 		    return;
 		}
		_leagues = leagues;
		//dropLineW3('duration', teams_);
		dropLineW3('durationPHP', 'PHPDauer:' + xmlResponse.getElementsByTagName('duration')[0].firstChild.nodeValue + 's');
		dropLineW3('durationJS', 'JSDauer:' + ((new Date().getTime() - _startTime)/1000.0) + 's(=total)');
        dispLeagues();
		},

		onFailure : function(response) {
    	   handleAjaxError();
 		}
	});
}

function loadCountrys() {
	return;
}

function dispTeams() {
	var teams = '<select size="1" name="teamSelect" id="teamSelect" onChange="javascript:updateDisplay('+CONST_TEAM+');"><option value="-1">alle</option>';
	for(var i=0;i<_teams.length;i++) {
		teams += '<option class="ffb_select_' + i%2 + '" value="' + _teams[i].getElementsByTagName('team_id')[0].firstChild.nodeValue + '" id="tS' + _teams[i].getElementsByTagName('team_id')[0].firstChild.nodeValue + '">';
		teams += _teams[i].getElementsByTagName('team_name')[0].firstChild.nodeValue;
		teams += "</option>\r\n";
	}
	teams += "</select>";   
	dropLineW3('teamStatsSelect', teams);
	dropLineW3('durationJS', 'JSDauer:' + ((new Date().getTime() - _startTime)/1000.0) + 's(=total)');	
}

function dispLeagues()
{
	var leagues = '<select size="1" name="leagueSelect" id="leagueSelect" onChange="javascript:updateDisplay('+CONST_LEAGUE+');"><option value="-1">alle</option>';;
	for(var i=0;i<_leagues.length;i++) {
		leagues += '<option class="ffb_select_' + i%2 + '" value="' + _leagues[i].getElementsByTagName('game_id')[0].firstChild.nodeValue + '" id="lS' + _leagues[i].getElementsByTagName('game_id')[0].firstChild.nodeValue + '">';
		leagues += _leagues[i].getElementsByTagName('game_name')[0].firstChild.nodeValue;
		leagues += "</option>\r\n";
	}
	leagues += '</select>';
	dropLineW3('leagueStatsSelect', leagues);
	dropLineW3('durationJS', 'JSDauer:' + ((new Date().getTime() - _startTime)/1000.0) + 's(=total)');	
}

function displayTeamPlayers(team_id)
{
	if(team_id<=0)
		return;
	_startTime = new Date().getTime();
	_inetzTime = new Date().getTime();
    var url = server + 'ffb/stats/getTeamPlayers.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
		var xmlResponse=response.responseXML;
		dropLineW3('durationNW', 'NWDauer:'  + (((new Date().getTime() - _inetzTime)/1000.0) - xmlResponse.getElementsByTagName('duration')[0].firstChild.nodeValue) + 's');
        var players = xmlResponse.getElementsByTagName('players')[0].getElementsByTagName('XML_Serializer_Tag');
		
        if(players.length<=0) {
 		    dropLineW3('statsmain', ' <h2>' + $('tS'+team_id).innerHTML +'</h2>No Players found.');
			return;
 		}
		setDisplayableItems();
		
		var playerIds = new Array();
		var playerHTML = ' <h2>' + $('tS'+team_id).innerHTML +'</h2><div>';
		var i=0;
		for(i=0;i<players.length;i++) {
			if( (i%_PLAYER_AMOUNT)==0 )
				playerHTML += "<div><div class=\"statsPlayerLine\">\r\n";
			playerHTML += '<div class="statsPlayerItem"><img class="playerImg" src="' + server + 'images/ffb/players/' + team_id + '/' + players[i].getElementsByTagName('playerteam_player_picture')[0].firstChild.nodeValue +'" />'; 
			playerHTML += "<div class=\"statsPlayerName\">";
			playerHTML += players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue + ' ';
			playerHTML += players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue + ' ';
			playerHTML += "</div>\r\n";
			playerHTML += "<div class=\"overallStats\" id=\"pIdOS" 	+ players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue + "\">" + SMALL_LOAD + "<br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/>  </div>\r\n";
			playerHTML += "<div onclick=\"javascript:getPlayerLineups("+ players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue +")\" class=\"playerLineups\" id=\"pIdLu" + players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue + "\"></div>";
			playerHTML += "</div>\r\n";
			if( (i%_PLAYER_AMOUNT)==(_PLAYER_AMOUNT-1))
				playerHTML += "</div></div>\r\n";
			addPlayerStats(players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue);
			playerIds.push(players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue);
		}
		playerHTML += '</div><br/>';
		dropLineW3('palyers_stats',playerHTML);
		var elems = $$('div.statsPlayerItem');
		var width = Math.round( 100 / _PLAYER_AMOUNT  ) - 3;		
		for(var z=0;z<elems.length;z++)
		{
			elems[z].setStyle({
				width: width + '%'
			});
		}		
		var elem;
		while(elem = playerIds.shift())
			displayTeamPlayerOverallStats(elem);

		dropLineW3('durationPHP', 'PHPDauer:' + xmlResponse.getElementsByTagName('duration')[0].firstChild.nodeValue + 's');
		dropLineW3('durationJS', 'JSDauer:' + ((new Date().getTime() - _startTime)/1000.0) + 's(=total)');
		},

		onFailure : function(response) {
    	   handleAjaxError();
 		},
		parameters: 'team_id='+team_id
	});	
}

function displayTeamPlayerOverallStats(playerId) {
	if(playerId<=0)
		return;
    var url = server + 'ffb/stats/getPlayerOverallStats.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
		var xmlResponse=response.responseXML;
        var playerStats = xmlResponse.getElementsByTagName('playerOverallStats')[0];
		var statsHTML = "";
	
		statsHTML += playerStats.getElementsByTagName('playedMatches')[0].firstChild.nodeValue +_imgLINEUP;
		statsHTML += playerStats.getElementsByTagName('playedMinutes')[0].firstChild.nodeValue + _imgMINUTES;
		statsHTML += playerStats.getElementsByTagName('cards')[0].getElementsByTagName('y')[0].firstChild.nodeValue + _imgCARDSY;
		statsHTML += playerStats.getElementsByTagName('cards')[0].getElementsByTagName('r')[0].firstChild.nodeValue + _imgCARDSR;
		statsHTML += playerStats.getElementsByTagName('cards')[0].getElementsByTagName('yr')[0].firstChild.nodeValue + _imgCARDSYR;
		statsHTML += playerStats.getElementsByTagName('goals')[0].firstChild.nodeValue + _imgGOALS;
		statsHTML += playerStats.getElementsByTagName('owngoals')[0].firstChild.nodeValue + _imgOWNGOAL;
		statsHTML += playerStats.getElementsByTagName('penaltiesLost')[0].firstChild.nodeValue + _imgPSLOST;
		statsHTML += playerStats.getElementsByTagName('penaltiesSaved')[0].firstChild.nodeValue + _imgPSSAVE;
		statsHTML += playerStats.getElementsByTagName('assists')[0].firstChild.nodeValue + _imgASSIST;
		statsHTML += playerStats.getElementsByTagName('score')[0].firstChild.nodeValue + _imgPOINTS;
		var efficiency = playerStats.getElementsByTagName('score')[0].firstChild.nodeValue /  playerStats.getElementsByTagName('playedMatches')[0].firstChild.nodeValue;
		if(efficiency != NaN)
		{
			efficiency *= _ROUND;
			efficiency = Math.round(efficiency);
			efficiency /= _ROUND;
		}
		statsHTML += efficiency + _imgEFFICIENCY + '/Match<br/>';
		efficiency = playerStats.getElementsByTagName('score')[0].firstChild.nodeValue /  playerStats.getElementsByTagName('playedMinutes')[0].firstChild.nodeValue;
		if(efficiency != NaN)
		{
			efficiency *= _ROUND;
			efficiency = Math.round(efficiency);
			efficiency /= _ROUND;
		}		
		statsHTML += efficiency + _imgEFFICIENCY + '/Minute<br/>';

		dropLineW3('pIdOS'+playerId, statsHTML);
		
		},
		onFailure : function(response) {
    	   handleAjaxError();
 		},
		parameters: 'player_id='+playerId
	});
}

function addPlayerStats(player_id) {
	for(var i=0; i<_playerStats.lenght; i++) {
		if(_playerStats[i]['player_id'] == player_id) {
			return false;
		}
	}
	var obj = new Object();
	obj["player_id"]= player_id;
	obj["info"]		= false;
	_playerStats.unshift(obj);
	return true;
}

function displayLeagueTeams(game_id)
{
	if(game_id<=0)
		return;
	_startTime = new Date().getTime();
	_inetzTime = new Date().getTime();
    var url = server + 'ffb/stats/getLeagueMatches.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
		var xmlResponse=response.responseXML;
		dropLineW3('durationNW', 'NWDauer:'  + (((new Date().getTime() - _inetzTime)/1000.0) - xmlResponse.getElementsByTagName('duration')[0].firstChild.nodeValue) + 's');
        var matches = xmlResponse.getElementsByTagName('matches')[0].getElementsByTagName('round');

        if(matches.length<=0) {
 		    dropLineW3('statsmain', ' <h2>' + $('lS'+game_id).innerHTML +'</h2>No Matches found.');
			return;
 		}
		setDisplayableItems();		
		var matchHTML = ' <h2>' + $('lS'+game_id).innerHTML +"</h2>\r\n<div>\r\n";
		var i=0;
		for(i=0;i<matches.length;i++) {
			if( (i%_TEAM_AMOUNT)==0 )
				matchHTML += "<div><div class=\"statsLeagueLine\">\r\n";
				
			matchHTML += '<div class="statsLeagueItem">';
			matchHTML += '<div class="statsLeagueHead">' + matches[i].getElementsByTagName('mr_sdate')[0].firstChild.nodeValue + ' ';
			matchHTML += matches[i].getElementsByTagName('mr_title')[0].firstChild.nodeValue + '</div>';
			
			matchHTML += '<div class="statsLeagueBody">'; 
			var matchround = matches[i].getElementsByTagName('match')[0].getElementsByTagName('XML_Serializer_Tag');
			for(var j=0;j<matchround.length;j++) {
				matchHTML += '<div class="statsLeagueBodyElem' + (j%2) + '">';  
				matchHTML += matchround[j].getElementsByTagName('m_date')[0].firstChild.nodeValue + ': ';
				var h_score = matchround[j].getElementsByTagName('h_score')[0].firstChild.nodeValue;
				var g_score = matchround[j].getElementsByTagName('g_score')[0].firstChild.nodeValue;
				if(h_score > g_score)
					matchHTML += '<b>';
				matchHTML += matchround[j].getElementsByTagName('h_name')[0].firstChild.nodeValue + ' ';
				if(h_score >= 0)
					matchHTML += h_score + ':';
				else
					matchHTML += "-:";
				if(h_score > g_score)
					matchHTML += '</b>';
				if(h_score < g_score)
					matchHTML += '<b>';
				
				if(g_score >= 0)
					matchHTML += g_score + ' ';
				else
					matchHTML += "- ";
				matchHTML += matchround[j].getElementsByTagName('g_name')[0].firstChild.nodeValue + ' ';
				if(h_score < g_score)
					matchHTML += '</b>';
				matchHTML += '(~' + matchround[j].getElementsByTagName('match_minutes')[0].firstChild.nodeValue + 'min)';
				matchHTML += "</div>\r\n";
			}
			
			matchHTML += "</div>\r\n</div>\r\n";
			if( (i%_TEAM_AMOUNT)==(_TEAM_AMOUNT-1))
				matchHTML += "</div></div>\r\n";
		}
		//if((i%2)==1)
			//matchHTML += '<div class="statsLeagueItem"> </div></div><br/>';
		//else
			matchHTML += '</div><br/>';
			//alert(matchHTML);
		dropLineW3('league_stats',matchHTML);
		var elems = $$('div.statsLeagueItem');
		var width = Math.round( 100 / _TEAM_AMOUNT  ) - 3;		
		for(var z=0;z<elems.length;z++)
		{
			elems[z].setStyle({
				width: width + '%'
			});
		}

		dropLineW3('durationPHP', 'PHPDauer:' + xmlResponse.getElementsByTagName('duration')[0].firstChild.nodeValue + 's');
		dropLineW3('durationJS', 'JSDauer:' + ((new Date().getTime() - _startTime)/1000.0) + 's(=total)');
		},

		onFailure : function(response) {
    	   handleAjaxError();
 		},
		parameters: 'game_id='+game_id
	});	
}


function updateDisplay(what)
{
	switch (what)
	{
		case CONST_TEAM:
			dropLineW3('statsmain', '<div id="team_stats"></div><div id="palyers_stats"></div>');
			displayTeamPlayers($('teamSelect').value);
		
		break;
		
		case CONST_LEAGUE:
			dropLineW3('statsmain', '<div id="league_stats">');
			displayLeagueTeams($('leagueSelect').value);
		
		break;
		
		case CONST_PLAYER:
		
		break;
		
		default:
		return;
		break;
	}
}

function setDisplayableItems() {
	_TEAM_AMOUNT = Math.min(10,Math.max(1,( Math.round(window.outerWidth/400)-2 )));
	_PLAYER_AMOUNT = Math.min(10,Math.max(1,( Math.round(window.outerWidth/400)-1 )));
}

