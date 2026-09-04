var displayLock_=0;
var players_ = new Object();
players_['Home'] = 0;
players_['Guest'] = 0;
var uploadedPlayers_ = new Object();
var INPUTSIZE = 2;
var IMG_UPDATE_YES = '/images/ffb/symbols/change.png';
var IMG_UPDATE_NO = '/images/ffb/symbols/ok.png';
var REQUEST_OK = 'images/ffb/symbols/status_pos.png';
var REQUEST_FAILED = 'images/ffb/symbols/status_neg.png';

var MAXTRYS = 50;
var SLEEPTIMEOUT = 100; //1s == 1000 100 == 0,1s

var duration_ = new Date();

function loadMatchround() {
	var url = server + 'administration/matchround/getList.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		var round = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		var toDisplay='select a round<br><select class="gameselectBox" name="round" onChange="javascript:loadMatchroundGames(this.value);"><option class="selectOptionOld" >Runde selektieren</option>';
 		var timeNow = new Date();
 		var firstOldRound = 0;
 		for(var i=0;i<round.length;i++) {
 			toDisplay += '<option value="' + round[i].getElementsByTagName('matchround_id')[0].firstChild.nodeValue + '" ';
 			//7.11.2009 12:00 Invalid Date
 			var tmpDate = round[i].getElementsByTagName('matchround_startdate')[0].firstChild.nodeValue.split(" ");
 			tmpDate[0] = tmpDate[0].split(".");
 			tmpDate[1] = tmpDate[1].split(":");

 			var roundTime = new Date();
 			roundTime.setFullYear(tmpDate[0][2]);
 			roundTime.setMonth((tmpDate[0][1]-1));
 			roundTime.setDate(tmpDate[0][0]);
 			roundTime.setHours(tmpDate[1][0]);
 			roundTime.setMinutes(tmpDate[1][1]);

 			roundTime.setSeconds(0);
 			roundTime.setMilliseconds(0);

			if(timeNow.getTime()>roundTime.getTime()) {
				toDisplay += ' class="selectOptionOld" ';
				if(firstOldRound==0) {
					toDisplay += ' selected ';
					firstOldRound = round[i].getElementsByTagName('matchround_id')[0].firstChild.nodeValue;
				}
			}

			toDisplay += '>' + round[i].getElementsByTagName('matchround_title')[0].firstChild.nodeValue + ' ' +
 						 round[i].getElementsByTagName('matchround_startdate')[0].firstChild.nodeValue + ' - ' +
 						 round[i].getElementsByTagName('matchround_enddate')[0].firstChild.nodeValue + '</option>\r\n';
		}
		toDisplay += '</select>';
		dropLineW3('Matchround', toDisplay);
		if(firstOldRound>0)
			loadMatchroundGames(firstOldRound);
		},

		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});
}

function loadMatchroundGames(round_id) {
	duration_ = new Date();
	dropLineW3('Mostwanted', '');
	var url = server + 'administration/match/getMatchesForRound.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		//	alert(response.responseText);
 		var xmlResponse=response.responseXML;
 		var matches = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		var toDisplay='<hr>select a match to edit<br><b>\r\n';
 		var twitterMsg = '';
 		if(matches.length)
 		  toDisplay += matches[0].getElementsByTagName('match_round_name')[0].firstChild.nodeValue + '</b><br>';
 		for(var i=0;i<matches.length;i++) {
 		    if(matches[i].getElementsByTagName('match_homescore')[0].firstChild.nodeValue < 0) {
 		        homescore = '-';
 		        guestscore = '-';
 		    } else {
 		        homescore = matches[i].getElementsByTagName('match_homescore')[0].firstChild.nodeValue;
 		        guestscore = matches[i].getElementsByTagName('match_guestscore')[0].firstChild.nodeValue;
 		    }
 			toDisplay += '<a href="javascript:loadMatch(' +
			 			 matches[i].getElementsByTagName('match_id')[0].firstChild.nodeValue + ', ' +
						 matches[i].getElementsByTagName('match_hometeam_id')[0].firstChild.nodeValue + ', ' +
 						 matches[i].getElementsByTagName('match_homescore')[0].firstChild.nodeValue + ', \'' +
 						 matches[i].getElementsByTagName('match_hometeam_name')[0].firstChild.nodeValue + ' ' + matches[i].getElementsByTagName('match_hometeam_nationality')[0].firstChild.nodeValue + '\', ' +
 						 matches[i].getElementsByTagName('match_guestteam_id')[0].firstChild.nodeValue + ', ' +
						 matches[i].getElementsByTagName('match_guestscore')[0].firstChild.nodeValue + ', \'' +
						 matches[i].getElementsByTagName('match_guestteam_name')[0].firstChild.nodeValue + ' ' + matches[i].getElementsByTagName('match_guestteam_nationality')[0].firstChild.nodeValue + '\', ' +
						 //'0,0'+
						 matches[i].getElementsByTagName('match_homescore_penalty')[0].firstChild.nodeValue + ', ' +
						 matches[i].getElementsByTagName('match_guestscore_penalty')[0].firstChild.nodeValue +
						 ');">' +
 						 matches[i].getElementsByTagName('match_hometeam_name')[0].firstChild.nodeValue + ' ' +
                         homescore +
						 ':' + guestscore + ' ' +
						 matches[i].getElementsByTagName('match_guestteam_name')[0].firstChild.nodeValue + '</a><br>';

			 var tmpMsg =   matches[i].getElementsByTagName('match_hometeam_name')[0].firstChild.nodeValue + ' ' +
			 				homescore + ':' + guestscore + ' ' +
			 				matches[i].getElementsByTagName('match_guestteam_name')[0].firstChild.nodeValue + ' ';

			 var spaces = '';
			 var newTwitter = (twitterMsg.length % 140);
			 newTwitter += tmpMsg.length;

			 if( newTwitter>140 ) {
			 	for( var l=0;l<( tmpMsg.length - 1)  ;l++) {
			 		spaces += ' ';
			 	}
			 }
			 twitterMsg +=	spaces + tmpMsg;


		}
		toDisplay += '<div class="Duration">Skriptdauer: ';
		var tmp = new Date();
		var diff = (tmp.getTime()-duration_.getTime()).toString();
		toDisplay += diff + "ms</div><br>\r\n";
		dropLineW3('Match', toDisplay);
		twitterMsg =	'<form name="twitter" id="twitter">Twitter:<br/><textarea name="twittermsg" id="twittermsg" cols="40" rows="5">' + twitterMsg + '</textarea>' +
						'<br/><input type="button" value="send to twitter" onclick="javascript:sendTwitterMsg(); return;" /></form><br/>';
		dropLineW3('Twitter', twitterMsg);


		},

		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},

		parameters: 'matchround_id='+round_id
	});

	url = server + 'administration/matchround/getMostWanted.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		//alert(response.responseText);
 		var xmlResponse=response.responseXML;
 		var teamList = xmlResponse.getElementsByTagName('XML_Serializer_Tag');

 		var toDisplay='<hr>Team/#Aufstellungen<br>\r\n';
 		if(!teamList.length)
		  return;
		else
		  toDisplay += '<ol>';
 		for(var i=0;i<teamList.length;i++) {
 			toDisplay += '<li>' +
			 			 teamList[i].getElementsByTagName('teamname')[0].firstChild.nodeValue + ', ' +
						 teamList[i].getElementsByTagName('players')[0].firstChild.nodeValue +
						 "</li>\r\n";
		}
		toDisplay += "</ol><br>\r\n";
		toDisplay += "Zeit f&uuml;r " + xmlResponse.getElementsByTagName('sort')[0].firstChild.nodeValue + " Sortierungen(php): ";
		toDisplay += xmlResponse.getElementsByTagName('time')[0].firstChild.nodeValue+"ms<br />\r\n";
		toDisplay += "Lineups: " + xmlResponse.getElementsByTagName('sort')[0].firstChild.nodeValue / 11 + "<br />\r\n";
		toDisplay += '<div class="Duration">Skriptdauer: ';
		var tmp = new Date();
		var diff = (tmp.getTime()-duration_.getTime()).toString();
		toDisplay += diff + "ms</div><br>\r\n";
		dropLineW3('Mostwanted', toDisplay);
		},

		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},

		parameters: 'matchround_id='+round_id
	});
}

function loadMatch(matchID, home, scoreHome, nameHome, guest, scoreGuest, nameGuest, penaltyHome, penaltyGuest) {
	$('loadingHome').setStyle({visibility: 'visible'});
	$('loadingGuest').setStyle({visibility: 'visible'});
	$('send').setStyle({visibility: 'hidden'});
	players_['Home'] = 0;
	players_['Guest'] = 0;
	dropLineW3('HomePlayers', '');
	dropLineW3('GuestPlayers', '');
	var currDispalyLoop = ++displayLock_;
	loadPlayers('Home', home, matchID, currDispalyLoop);
	loadPlayers('Guest', guest, matchID, currDispalyLoop);
	var header= '<div id="player"><b><u> # Player <i>Name</i><br><img src="' + server + symbolImages_ + 'stats_time.png" title="time" alt="time" width="15px" height="15px">Time ' +
					' <img src="' + server + symbolImages_ + 'stats_goal.gif" title="goals" alt="goals" width="15px" height="15px">Goals' +
					' <img src="' + server + symbolImages_ + 'stats_assist.gif" title="assists" alt="assists" width="15px" height="15px">Assists' +
					' <img src="' + server + symbolImages_ + 'stats_card_y.gif" title="card yellow = y, yellow and red= yr" alt="card yellow=y yr" width="18px" height="22px">Cards' +
					' <img src="' + server + symbolImages_ + 'stats_owngoal.gif" title="own goal" alt="own goal" width="15px" height="15px">Own Goals<br/>' +
					' <img src="' + server + symbolImages_ + 'stats_penaltylost.png" title="penalty lost" alt="penalty lost" width="15px" height="15px">P. Lost' +
					' <img src="' + server + symbolImages_ + 'stats_penaltysaved.png" title="penalty saved" alt="penalty saved" width="15px" height="15px">P. Saved' +
					' ps_ps_save / ps_ps_lost / ps_ps_hit/' +
					' <img src="' + server + symbolImages_ + 'stats_hourglass_add.png" title="min in" alt="min in" width="16px" height="16px">min in' +
					' <img src="' + server + symbolImages_ + 'stats_hourglass_delete.png" title="min out" alt="min out" width="16px" height="16px">min out' +
					'</div>';
	var homeScore = '<select name="match_homescore" id="homescore" size="1">';
	var guestScore = '<select name="match_guestscore" id="guestscore" size="1">';
	for(var i=0;i<50;i++){
		homeScore += '<option value="'+i+'" ';
		guestScore += '<option value="'+i+'" ';
		if(i%2==0) {
			homeScore += 'class="silver" ';
			guestScore += 'class="silver" ';
		}

 		if(i==scoreHome||i==0)
		  homeScore += 'selected';
		if(i==scoreGuest||i==0)
		  guestScore += 'selected';
		homeScore += '>'+i+'</option>';
		guestScore += '>'+i+'</option>';
	}
	homeScore += '</select>';
	guestScore += '</select>';
	homeScore += ' (penalty shootout score(only): <select name="match_homescore_penalty" id="homescorepenalty" size="1">';
	guestScore += '(penalty shootout score(only)<select name="match_guestscore_penalty" id="guestscorepenalty" size="1">';
	for(var i=-1;i<100;i++){
		homeScore += '<option value="'+i+'" ';
		guestScore += '<option value="'+i+'" ';
		if(i%2==0) {
			homeScore += 'class="silver" ';
			guestScore += 'class="silver" ';
		}
 		if(i==penaltyHome||i==-1)
		  homeScore += 'selected';
		if(i==penaltyGuest||i==-1)
		  guestScore += 'selected';
		homeScore += '>'+i+'</option>';
		guestScore += '>'+i+'</option>';
	}
	homeScore += '</select>)<input type="hidden" name="playerstats_match_id" id="playerstats_match_id" value="' + matchID + '"></form>';
	guestScore += '</select>)</form>';
	dropLineW3('HomeTitle', nameHome+'<form name="home" id="home">Score: '+homeScore+header);
	dropLineW3('GuestTitle', nameGuest+'<form name="guest" id="guest">Score: '+guestScore+header);

}

function loadPlayers(team, teamID, matchID, currDispalyLoop) {

	var url = server + 'administration/matchpoints/getPlayerStatsForTeam.xml';
	var j = 0;
	dropLineW3('SystemInfo0', '<hr/>Updates in Bereitschaft: <input type="button" id="toupdate" value="0" disabled />');
	dropLineW3('SystemInfo1','Updates erledigt: <input type="button" id="updated" value="0" disabled />');

	if(currDispalyLoop==displayLock_) {
		new Ajax.Request(url, {
 			onSuccess : function(response) {
 				$('loading'+team).setStyle({visibility: 'hidden'});
 				//alert(response.responseText);
 				//document.write(response.responseXML);
 				//return;
 				var xmlResponse=response.responseXML;
 				var players = xmlResponse.getElementsByTagName('XML_Serializer_Tag');

 				for(var i=0;i<players.length;i++) {
 					var homePlayers = new Object();
 					homePlayers['player_id'] = players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue;
 					homePlayers['player_name'] = '<b>' + players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue +
					'</b>, <i>' + players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue + '</i> (' +
                    players[i].getElementsByTagName('playerteam_player_position')[0].firstChild.nodeValue.toUpperCase() +')';
 					homePlayers['playerteam_id'] = players[i].getElementsByTagName('playerteam_id')[0].firstChild.nodeValue;

 					if(players[i].getElementsByTagName('playerstats_goals')[0].firstChild.nodeValue!=null)
 						homePlayers['playerstats_goals'] = players[i].getElementsByTagName('playerstats_goals')[0].firstChild.nodeValue;
 					else
						homePlayers['playerstats_goals'] = 0;

					if(players[i].getElementsByTagName('playerstats_assists')[0].firstChild.nodeValue!=null)
						homePlayers['playerstats_assists'] = players[i].getElementsByTagName('playerstats_assists')[0].firstChild.nodeValue;
					else
						homePlayers['playerstats_assists'] = 0;

					if(players[i].getElementsByTagName('playerstats_minutes')[0].firstChild.nodeValue!=null)
						homePlayers['playerstats_minutes'] = players[i].getElementsByTagName('playerstats_minutes')[0].firstChild.nodeValue;
					else
						homePlayers['playerstats_minutes'] = 0;

						homePlayers['playerstats_cards'] = players[i].getElementsByTagName('playerstats_cards')[0].firstChild.nodeValue;

					if(players[i].getElementsByTagName('playerstats_owngoals')[0].firstChild.nodeValue!=null)
						homePlayers['playerstats_owngoals'] = players[i].getElementsByTagName('playerstats_owngoals')[0].firstChild.nodeValue;
					else
						homePlayers['playerstats_owngoals'] = 0;
					if(players[i].getElementsByTagName('playerstats_penaltieslost')[0].firstChild.nodeValue!=null)
						homePlayers['playerstats_penaltieslost'] = players[i].getElementsByTagName('playerstats_penaltieslost')[0].firstChild.nodeValue;
					else
						homePlayers['playerstats_penaltieslost'] = 0;
					if(players[i].getElementsByTagName('playerstats_penaltiessaved')[0].firstChild.nodeValue!=null)
						homePlayers['playerstats_penaltiessaved'] = players[i].getElementsByTagName('playerstats_penaltiessaved')[0].firstChild.nodeValue;
					else
						homePlayers['playerstats_penaltiessaved'] = 0;

          			if(players[i].getElementsByTagName('playerstats_penaltyshootout_hit')[0].firstChild.nodeValue!=null)
					  homePlayers['playerstats_penaltyshootout_hit'] = players[i].getElementsByTagName('playerstats_penaltyshootout_hit')[0].firstChild.nodeValue;
          			else
             			homePlayers['playerstats_penaltyshootout_hit'] = 0;
          			if(players[i].getElementsByTagName('playerstats_penaltyshootout_lost')[0].firstChild.nodeValue!=null)
					  homePlayers['playerstats_penaltyshootout_lost'] = players[i].getElementsByTagName('playerstats_penaltyshootout_lost')[0].firstChild.nodeValue;
          			else
             			homePlayers['playerstats_penaltyshootout_lost'] = 0;
          			if(players[i].getElementsByTagName('playerstats_penaltyshootout_save')[0].firstChild.nodeValue!=null)
					  homePlayers['playerstats_penaltyshootout_save'] = players[i].getElementsByTagName('playerstats_penaltyshootout_save')[0].firstChild.nodeValue;
          			else
             			homePlayers['playerstats_penaltyshootout_save'] = 0;

          			if(players[i].getElementsByTagName('playerstats_minute_in')[0].firstChild.nodeValue!=null)
          				homePlayers['playerstats_minute_in'] = players[i].getElementsByTagName('playerstats_minute_in')[0].firstChild.nodeValue;
   					else
   						homePlayers['playerstats_minute_in'] = 0;
          			if(players[i].getElementsByTagName('playerstats_minute_out')[0].firstChild.nodeValue!=null)
          				homePlayers['playerstats_minute_out'] = players[i].getElementsByTagName('playerstats_minute_out')[0].firstChild.nodeValue;
   					else
   						homePlayers['playerstats_minute_out'] = 0;

					if(currDispalyLoop!=displayLock_)
					 break;
					else {
						$(team+'Players').innerHTML = $(team+'Players').innerHTML + formPlayerString(homePlayers, players_[team]++, team);
						$('send').setStyle({visibility: 'visible'});
					}
				}

			},

			onFailure : function(response) {
    			alert("Oops, there's been an error.");
 			},
			parameters: '?id='+teamID+'&match_id='+matchID
		});
	}
}

//**
// Bastelt fuer jeden Spieler sein Spielergebniss fuer die Runde, mit Toren Minuten karten usw.
//**//

function formPlayerString(playerStats,index,homeOrGuest) {
	var playerstring = '';
	playerstring += '<li><form name="' + homeOrGuest+index + '" accept-charset="UTF-8">' +
					'<div id="player">' +
					playerStats['player_name'] + '<div id="'+homeOrGuest+index+'status" style="diplay:inline"></div>' +"\r\n"+//name
					'<div id="'+homeOrGuest+index+'">' +
					'<img src="' + server + symbolImages_ + 'stats_time.png" title="time" alt="time" width="15px" height="15px">' +
					'<input type="text" name="playerstats_minutes" size="'+INPUTSIZE+'" maxlength="3" id="time'+homeOrGuest+index+'" onChange="javascript:alterScore(\'' +
					homeOrGuest + '\', ' + index + ');" value="' + playerStats['playerstats_minutes'] + '">' +"\r\n"+ //played time
					' <img src="' + server + symbolImages_ + 'stats_goal.gif" title="goals" alt="goals" width="15px" height="15px">' +
					'<input type="text" name="playerstats_goals" size="'+INPUTSIZE+'" maxlength="100" id="goals'+homeOrGuest+index+'" onChange="javascript:alterScore(\'' +
					homeOrGuest + '\', ' + index + ');" value="' + playerStats['playerstats_goals'] + '">' +"\r\n"+ //goals
					' <img src="' + server + symbolImages_ + 'stats_assist.gif" title="assists" alt="assists" width="15px" height="15px">' +
					'<input type="text" name="playerstats_assists" size="'+INPUTSIZE+'" maxlength="2" id="assists'+homeOrGuest+index+'" onChange="javascript:alterScore(\'' +
					homeOrGuest + '\', ' + index + ');" value="' + playerStats['playerstats_assists'] + '">'+"\r\n";// + //assisits

	//cards
    var card = false;
    //yellow
	if(playerStats['playerstats_cards']=='y'){
		playerstring += ' <img src="' + server + symbolImages_ + 'stats_card_y.gif" title="card yellow = y, yellow and red= yr" alt="card yellow=y yr" width="15px">' +
                    '<input type="radio" name="playerstats_cards' + homeOrGuest+index+'" id="cardsy' + homeOrGuest+index+'" onChange="javascript:chCard(\'y\', \''+
                    homeOrGuest+ '\', \''+index +'\');" checked>'+"\r\n";
		card = 'y';
	} else{
		playerstring += ' <img src="' + server + symbolImages_ + 'stats_card_y.gif" title="card yellow = y, yellow and red= yr" alt="card yellow=y yr" width="15px">' +
                    '<input type="radio" name="playerstats_cards' + homeOrGuest+index+'" id="cardsy' + homeOrGuest+index+'" onChange="javascript:chCard(\'y\', \''+
                    homeOrGuest+ '\', \''+index +'\');">'+"\r\n";
	}

	//yellow and then red
	if(playerStats['playerstats_cards']=='yr'){
		playerstring +=  ' <img src="' + server + symbolImages_ + 'stats_card_yr.gif" title="card yellow = y, yellow and red= yr" alt="card yellow=y yr" width="15px">' +
                    '<input type="radio" name="playerstats_cards' + homeOrGuest+index+'" id="cardsyr' + homeOrGuest+index+'" onChange="javascript:chCard(\'yr\', \''+
                    homeOrGuest+ '\', \''+index +'\');" checked>'+"\r\n";
		card = 'yr';
	} else {
		playerstring +=  ' <img src="' + server + symbolImages_ + 'stats_card_yr.gif" title="card yellow = y, yellow and red= yr" alt="card yellow=y yr" width="15px">' +
                    '<input type="radio" name="playerstats_cards' + homeOrGuest+index+'" id="cardsyr' + homeOrGuest+index+'" onChange="javascript:chCard(\'yr\', \''+
                    homeOrGuest+ '\', \''+index +'\');" >'+"\r\n";
	}

	//red
	if(playerStats['playerstats_cards']=='r') {
		playerstring +=  ' <img src="' + server + symbolImages_ + 'stats_card_r.gif" title="card yellow = y, yellow and red= yr" alt="card yellow=y yr" width="15px">' +
                    '<input type="radio" name="playerstats_cards' + homeOrGuest+index+'" id="cardsr' + homeOrGuest+index+'" onChange="javascript:chCard(\'r\', \''+
                    homeOrGuest+ '\', \''+index +'\');" checked>'+"\r\n";
		card = 'r';
	} else{
		playerstring +=  ' <img src="' + server + symbolImages_ + 'stats_card_r.gif" title="card yellow = y, yellow and red= yr" alt="card yellow=y yr" width="15px">' +
                    '<input type="radio" name="playerstats_cards' + homeOrGuest+index+'" id="cardsr' + homeOrGuest+index+'" onChange="javascript:chCard(\'r\', \''+
                    homeOrGuest+ '\', \''+index +'\');" >'+"\r\n";
	}

	//no card
	if(playerStats['playerstats_cards']=='n' || !card) {
		playerstring += ' N' +
                    '<input type="radio" name="playerstats_cards' + homeOrGuest+index+'" id="cardsn' + homeOrGuest+index+'" onChange="javascript:chCard(\'n\', \''+
                    homeOrGuest+ '\', \''+index +'\');" checked>'+"\r\n";
    card = 'n';
	}
	else {
		playerstring += ' N' +
                    '<input type="radio" name="playerstats_cards' + homeOrGuest+index+'" id="cardsn' + homeOrGuest+index+'" onChange="javascript:chCard(\'n\', \''+
                    homeOrGuest+ '\', \''+index +'\');" >'+"\r\n";
	}
	playerstring += '<input type="hidden" id="cards'+ homeOrGuest+index + '" name="playerstats_cards" value="' + card + '">';


  //cards y==yellow yr==yellow/red r==red n==none
	playerstring += ' ' +
					'<img src="' + server + symbolImages_ + 'stats_owngoal.gif" title="own goal" alt="own goal" width="15px" height="15px">' +
					'<input type="text" name="playerstats_owngoals" size="'+INPUTSIZE+'" maxlength="100" id="owngoals'+homeOrGuest+index+'" onChange="javascript:alterScore(\'' +
					homeOrGuest + '\', ' + index + ');" value="' + playerStats['playerstats_owngoals'] + '"><br>' + //own goals
					' <img src="' + server + symbolImages_ + 'stats_penaltylost.png" title="penalty lost" alt="penalty lost" width="15px" height="15px">' +
					'<input type="text" name="playerstats_penaltieslost"  size="'+INPUTSIZE+'" maxlength="2" id="penaltie'+homeOrGuest+index+'" onChange="javascript:alterScore(\'' +
					homeOrGuest + '\', ' + index + ');" value="' + playerStats['playerstats_penaltieslost'] + '">' + //penalities lost
					' <img src="' + server + symbolImages_ + 'stats_penaltysaved.png" title="penalty saved" alt="penalty saved" width="15px" height="15px">' +
					'<input type="text" name="playerstats_penaltiessaved" size="'+INPUTSIZE+'" maxlength="2" id="penaltiesaved'+homeOrGuest+index+'" onChange="javascript:alterScore(\'' +
					homeOrGuest + '\', ' + index + ');" value="' + playerStats['playerstats_penaltiessaved'] + '">' + //penalities saved
					"\r\n" +
					' <img src="' + server + symbolImages_ + 'stats_goal.gif" title="ps: saved" alt="ps: saved" width="15px" height="15px">' +
					'<input type="text" name="playerstats_penaltyshootout_save" size="'+INPUTSIZE+'" maxlength="2" id="pnshootoutsave'+homeOrGuest+index+'" onChange="javascript:alterScore(\'' +
					homeOrGuest + '\', ' + index + ');" value="' + playerStats['playerstats_penaltyshootout_save'] + '">' + //playerstats_penaltyshootout_save
					"\r\n" +
					' <img src="' + server + symbolImages_ + 'stats_goal.gif" title="ps: lost" alt="ps: lost" width="15px" height="15px">' +
					'<input type="text" name="playerstats_penaltyshootout_lost" size="'+INPUTSIZE+'" maxlength="2" id="pnshootoutlost'+homeOrGuest+index+'" onChange="javascript:alterScore(\'' +
					homeOrGuest + '\', ' + index + ');" value="' + playerStats['playerstats_penaltyshootout_lost'] + '">' + //playerstats_penaltyshootout_lost
					"\r\n" +
					' <img src="' + server + symbolImages_ + 'stats_goal.gif" title="ps: hit" alt="ps: hit" width="15px" height="15px">' +
					'<input type="text" name="playerstats_penaltyshootout_hit" size="'+INPUTSIZE+'" maxlength="2" id="pnshootouthit'+homeOrGuest+index+'" onChange="javascript:alterScore(\'' +
					homeOrGuest + '\', ' + index + ');" value="' + playerStats['playerstats_penaltyshootout_hit'] + '">' + //playerstats_penaltyshootout_hit
					' <img src="' + server + symbolImages_ + 'stats_hourglass_add.png" title="player start time" alt="player start time" width="16px" height="16px">' +
					'<input type="text" name="playerstats_minute_in" size="'+INPUTSIZE+'" maxlength="2" id="minin'+ homeOrGuest+index + '" onChange="javascript:alterMinutesPlayed(\'' +
					homeOrGuest + '\', ' + index + ');" value="' + playerStats['playerstats_minute_in'] + '">' + //playerstats_minute_in
					' <img src="' + server + symbolImages_ + 'stats_hourglass_delete.png" title="player start time" alt="player start time" width="16px" height="16px">' +
					'<input type="text" name="playerstats_minute_out" size="'+INPUTSIZE+'" maxlength="2" id="minout'+ homeOrGuest+index + '" onChange="javascript:alterMinutesPlayed(\'' +
					homeOrGuest + '\', ' + index + ');" value="' + playerStats['playerstats_minute_out'] + '">' + //playerstats_minute_out
					' <input type="checkbox" id="ok' + homeOrGuest+index + '" onChange="javascript:updateCheckbox(\'ok' + homeOrGuest+index+'\');" >' + //player id
					'<input type="hidden" name="playerteam_id" id="playerteamid' + homeOrGuest+index + '" value="' + playerStats['playerteam_id'] + '">' +
					'</div></div>' +
					'</form></li>';
	return playerstring;
}

function chCard(card, homeOrGuest, index){
  $('cards' + homeOrGuest+index).value=card;
  alterScore(homeOrGuest,index );
}


function alterMinutesPlayed(homeOrGuest, index){
	var start = Number($('minin'+homeOrGuest+index).value);
	var end = Number($('minout'+homeOrGuest+index).value);
	if(start<end&&start>=0) {
		$('time'+homeOrGuest+index).value = end-start+1;
		alterScore(homeOrGuest, index);
	}
}


function updateCheckbox(checked) {
	if($(checked).checked) {
		$('toupdate').value = Number($('toupdate').value) + 1;
	} else {
		$('toupdate').value = Number($('toupdate').value) - 1;
	}
}


function alterScore(homeOrGuest, index) {
	var time = Number($('time'+homeOrGuest+index).value);
	//var goals = Number($('goals'+homeOrGuest+index).value);
	var assists = Number($('assists'+homeOrGuest+index).value);
	//var owngoals = Number($('owngoals'+homeOrGuest+index).value);
	var penalties = Number($('penaltie'+homeOrGuest+index).value);
	var penaltiessaved = Number($('penaltiesaved'+homeOrGuest+index).value);
	var p1 = Number($('pnshootoutlost'+homeOrGuest+index).value);
	var p2 = Number($('pnshootouthit'+homeOrGuest+index).value);
	var p3 = Number($('pnshootoutsave'+homeOrGuest+index).value);
	//var cards;
  //if($('cardsn'+homeOrGuest+index).value)
    //cards=$('cardsn'+homeOrGuest+index).value;
  //else if($('cardsy'+homeOrGuest+index).value)
    //cards=$('cardsy'+homeOrGuest+index).value;
  //else
    //cards = $('cardsr'+homeOrGuest+index).value;
	var score = time/*+goals*/+assists/*+owngoals*/+penalties+penaltiessaved+p1+p2+p3;
	dropLineW3(homeOrGuest+index+'status', ' ');
	if(!isNaN(score)&&time<=200/*&&goals>=0*/&&assists>=0/*&&owngoals>=0*/&&penalties>=0&&penaltiessaved>=0&&p1>=0&&p2>=0&&p3>=0) {
		if(time>0) {
			$(homeOrGuest+index).setStyle({backgroundColor: 'lime'});
			if($('ok'+homeOrGuest+index).checked == false) {
				$('toupdate').value = Number($('toupdate').value) + 1;
				$('ok'+homeOrGuest+index).checked = true;
			}

		}
	} else {
		$(homeOrGuest+index).setStyle({backgroundColor: 'red'});
		if($('ok'+homeOrGuest+index).checked == true) {
				$('toupdate').value = Number($('toupdate').value) - 1;
		}
		$('ok'+homeOrGuest+index).checked = false;
		var line = parseInt(index)+1;
		alert('Not a valid number in ' + homeOrGuest + ' team in line ' + line);
	}
}

function sendAll() {
	var url = server + 'administration/matchpoints/setMatchresult.xml';
	uploadedPlayers_ = new Object();
    new Ajax.Request(url, {
 		     onSuccess : function(response) {
 		        var xmlResponse = response.responseXML;
 		        //alert(response.responseText);

 		     	var error = xmlResponse.getElementsByTagName('array');
 		     	if(error[0].getElementsByTagName('administration_status')[0].firstChild.nodeValue=='201') {
 		     		//alert('matchresult: ok');
 		     	} else {
					//alert('matchresult: send not ok.');
				}
				var j=-1;
    		 	for(var i=0;i<players_['Home'];i++) {
        			if($('okHome'+i).checked) {
        				j++;
        				dropLineW3('Home'+i+'status', '<img src="'+ server + IMG_UPDATE_YES + '" width="15" height="15">');
						uploadedPlayers_[j] = new Object();
						uploadedPlayers_[j]['post'] = Form.serialize($('Home'+i))+'&playerstats_match_id='+$('playerstats_match_id').value;
						uploadedPlayers_[j]['team'] = 'Home';
						uploadedPlayers_[j]['index'] = i;
						uploadedPlayers_[j]['uploadedGoals'] = false;
						uploadedPlayers_[j]['uploadFinished'] = false;
        				sendPlayerGoals(uploadedPlayers_[j]['post'],j);
	 				}
			 	}
				for(var i=0;i<players_['Guest'];i++) {
					if($('okGuest'+i).checked) {
						j++;
						dropLineW3('Guest'+i+'status', '<img src="'+ server + IMG_UPDATE_YES + '" width="15" height="15">');
						uploadedPlayers_[j] = new Object();
						uploadedPlayers_[j]['post'] = Form.serialize($('Guest'+i))+'&playerstats_match_id='+$('playerstats_match_id').value;
						uploadedPlayers_[j]['team'] = 'Guest';
						uploadedPlayers_[j]['index'] = i;
						uploadedPlayers_[j]['uploadedGoals'] = false;
						uploadedPlayers_[j]['uploadFinished'] = false;
        				sendPlayerGoals(uploadedPlayers_[j]['post'], j);
					}
				}
				sendPlayerStatsWhenGoalsAreFinished(j, 0);
		    },

		    onFailure : function(response) {
    	    	alert("Oops, there's been an error.");
 		    },
	        parameters: Form.serialize($('home'))+'&'+Form.serialize($('guest'))
	});
}


function sendPlayerStatsWhenGoalsAreFinished(index, cnts) {
	var i=0;
	var j=0;
	for(i=0;i<index+1;i++) {
		if(uploadedPlayers_[i]['uploadedGoals']==true) {
			j++;
		}
	}
	if(i==j) {
		for(i=0;i<index+1;i++) {
			if(uploadedPlayers_[i]['uploadedGoals']==true) {
				sendPlayerStats(uploadedPlayers_[i]['team'], uploadedPlayers_[i]['index'], i);
			}
		}
		uploadedPlayers_ = new Object();
	} else {
		var cntss = cnts+1;
		i--;
		if(cntss<MAXTRYS)
			window.setTimeout("sendPlayerStatsWhenGoalsAreFinished("+i+", "+cntss+")",SLEEPTIMEOUT);
		else
			alert('Connection Problem, no Updates');
	}

}


function sendPlayerGoals(post, j) {
	var url = server + 'administration/matchpoints/setGoalData.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse = response.responseXML;
 			uploadedPlayers_[j]['uploadedGoals'] = true;
		},
		onFailure : function(response) {
    		alert("Oops, there's been an error.");
		},
		parameters: "?"+post
	});
}

function sendPlayerStats(team, index, j) {
	//dropLineW3(team+index+'status', '<img src="'+ server + IMG_UPDATE_YES + '" width="15" height="15">');
	var url = server + 'administration/matchpoints/setPlayerStats.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse = response.responseXML;
 			var error = xmlResponse.getElementsByTagName('array');

	 		if(error[0].getElementsByTagName('administration_status')[0].firstChild.nodeValue=='201') {
 				dropLineW3(team+index+'status', '<img src="'+ server + REQUEST_OK + '" width="15" height="15">');
 				$(team+index).setStyle({backgroundColor: 'green'});
				$('ok'+team+index).checked = false;
				$('updated').value = Number($('updated').value) + 1;
			}
			else {
				dropLineW3(team+index+'status', '<img src="'+ server + REQUEST_FAILED + '" width="15" height="15">');
				$('ok'+team+index).checked = false;
			}
		},
		onFailure : function(response) {
    		alert("Oops, there's been an error.");
		},
		parameters: Form.serialize($(team+index))+'&playerstats_match_id='+$('playerstats_match_id').value
	});
}

function sendTwitterMsg() {
	var url = server + 'administration/ffbtwitter/twitterMsg.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse = response.responseXML;
 			//var error = xmlResponse.getElementsByTagName('array');
 			alert(response.responseText);

		},
		onFailure : function(response) {
    		alert("Oops, there's been an error.");
		},
		parameters: Form.serialize($('twitter'))
	});
}