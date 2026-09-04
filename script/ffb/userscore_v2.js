var MAX_ENTRIES_PER_SITE = 75;
var _matchround = new Array();
var _matchround_list = new Array();
var _matchround_select_index = -1;
var _user_id = 0;
var _matchround_id = 0;
var _sort_flag = '';
var _sort_dir = '';
var _score_list = new Array();

function initUserscore(user_id) {
 	_user_id = user_id;
    dispWaitMessage('lineup_select_round', 'Lade Spielrunden..');
    dispWaitMessage('lineup_infoarea_title', 'Lade Spielrunde..');
    dispWaitMessage('UserscoreMain', 'Lade Rangliste..');
 	loadMatchrounds();
 	getRanking();
}

function loadMatchrounds() {
    var url = server + 'ffb/matchround/getPastAndRunningMatchrounds.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;

        //alert(response.responseText);

 		var matchround_ids = xmlResponse.getElementsByTagName('matchround_id');
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
 		var matchround_futures = xmlResponse.getElementsByTagName('matchround_future');
 		var matchround_matches = xmlResponse.getElementsByTagName('matches');

		//adding gesamtrangliste to list
		_matchround_list[0] = new Object;
        _matchround_list[0]['matchround_id'] = 0;
        _matchround_list[0]['matchround_title'] = 'Gesamtrangliste';
        _matchround_list[0]['matchround_actual'] = 1;
        _matchround_list[0]['matchround_running'] = 0;
        _matchround_list[0]['matchround_future'] = 0;
        _matchround_list[0]['matchround_matches'] = 0;
        // *****

 		for(var i=0; i<matchround_ids.length; i++) {
 		    _matchround_list[i+1] = new Object;
            _matchround_list[i+1]['matchround_id'] = matchround_ids[i].firstChild.nodeValue;
            _matchround_list[i+1]['matchround_title'] = matchround_titles[i].firstChild.nodeValue;
 		    _matchround_list[i+1]['matchround_start'] = matchround_starts[i].firstChild.nodeValue;
 		    _matchround_list[i+1]['matchround_end'] = matchround_ends[i].firstChild.nodeValue;
            _matchround_list[i+1]['matchround_actual'] = matchround_actuals[i].firstChild.nodeValue;
            _matchround_list[i+1]['matchround_running'] = matchround_runnings[i].firstChild.nodeValue;
            _matchround_list[i+1]['matchround_future'] = matchround_futures[i].firstChild.nodeValue;
            _matchround_list[i+1]['matchround_matches'] = matchround_matches[i].getElementsByTagName('XML_Serializer_Tag');
        }
        _matchround_list[0]['matchround_start'] = _matchround_list[i]['matchround_start'];
		_matchround_list[0]['matchround_end'] = _matchround_list[1]['matchround_end'];
		_matchround = _matchround_list[0];
		_matchround_select_index = 0;

        dispMatchroundDetails();
        dispMatchroundSelection();

        //disableSelection();
        return;

    },
		onFailure : function(response) {
    	handleAjaxError();
 		}
	});
}

function dispMatchroundSelection() {
    var string = '';
    var selected = '';
    string += '<select class="ffb_select" id="matchround_selection" size="1" onchange="javascript:changeMatchroundSelection();">';
    string += '<option class="ffb_select_1" disabled selected>Spielrunde..</option>';

    for(var i=0; i<_matchround_list.length; i++) {
        if(i == 0) {
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

function dispMatchroundDetails() {
    var string = '';
    string += _matchround['matchround_title'];
    if(parseInt(_matchround['matchround_running']) == 1) {
    	string += ' (aktuelle Runde)';
    } else if(parseInt(_matchround['matchround_future']) == 1) {
    	string += ' (n&auml;chste Runde)';
    }
    if(_matchround['matchround_start'] == _matchround['matchround_end']) {
        string += '<br><span style="font-size:9pt;"><em>' + _matchround['matchround_start'] + '</em></span>';
    } else {
        string += '<br><span style="font-size:9pt;"><em>' + _matchround['matchround_start'] + ' bis ' + _matchround['matchround_end'] + '</em></span>';
    }

    dropLineW3('lineup_infoarea_title', string);
    return;
}

function changeMatchroundSelection() {
	var selection = document.getElementById('matchround_selection');
	var index = selection.options[selection.selectedIndex].value;
	_matchround = _matchround_list[index];
	var matchround_id = _matchround['matchround_id'];
	_matchround_id = matchround_id;
	dispMatchroundDetails();
	disableSelection();
	getRanking();

    if(_matchround_id > 0) {
        dispMatches(_matchround['matchround_matches'], '');
    } else {
        dropLineW3('matchlist', '');
    }
}

function getRanking() {
	dispWaitMessage('UserscoreMain', 'Lade Rangliste..');
    if(_matchround_id) {
        var url = server + 'ffb/userscore/getUserscoresForRound.xml';
    } else {
        var url = server + 'ffb/userscore/getUserscore.xml';
    }

	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		//alert(response.responseText);

        var xmlResponse=response.responseXML;
        var rankMode = xmlResponse.getElementsByTagName('rankMode')[0].firstChild.nodeValue;

        var scores = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
        _score_list.clear();
        for(var i=0;i<scores.length;i++) {
        	_score_list[i] = new Object();
			_score_list[i]['user_rank'] = scores[i].getElementsByTagName('user_rank')[0].firstChild.data;
			_score_list[i]['user_id'] = scores[i].getElementsByTagName('user_id')[0].firstChild.data;
			_score_list[i]['user_favourite_team_nationality'] = scores[i].getElementsByTagName('user_favourite_team_nationality')[0].firstChild.data;
			_score_list[i]['user_nickname'] = scores[i].getElementsByTagName('user_nickname')[0].firstChild.data;
			_score_list[i]['participations'] = scores[i].getElementsByTagName('participations')[0].firstChild.data;
			_score_list[i]['matchround_wins'] = scores[i].getElementsByTagName('matchround_wins')[0].firstChild.data;
			_score_list[i]['user_score'] = scores[i].getElementsByTagName('user_score')[0].firstChild.data;
			_score_list[i]['user_wc_points'] = scores[i].getElementsByTagName('user_wc_points')[0].firstChild.data;
        }


        if(rankMode == 'points' || _matchround_id) {
            var string = displayRanking(0, 'po');
        } else if(rankMode == 'wc') {
            var string = displayRanking(0, 'wc');
        }

        dropLineW3('UserscoreMain', string);
        activateSelection();

		},
		onFailure : function(response) {
    	alert('error retrieving matchround-details');
 		},
 		parameters: '?matchround_id=' + _matchround_id + '&sort_flag=' + _sort_flag + '&sort_dir=' + _sort_dir
	});
}

function sortRanking(sort_flag, sort_dir) {
	dispWaitMessage('UserscoreMain', 'Lade Rangliste..');
	disableSelection();
	_sort_flag = sort_flag;
	_sort_dir = sort_dir;
	getRanking();
}

function changeSite(start, mode) {
	var string = displayRanking(start, mode);
    dropLineW3('UserscoreMain', string);
}

function displayRanking(start, mode) {
	if(_score_list.length < (start+MAX_ENTRIES_PER_SITE)) {
		var end = _score_list.length;
	} else {
		var end = start+MAX_ENTRIES_PER_SITE;
	}
	var sites = '';
	var num_sites = Math.ceil(_score_list.length/MAX_ENTRIES_PER_SITE);
	if(num_sites > 1) {
		sites += 'Seite ';
		for(var i=0;i<num_sites;i++) {
			var site_start = MAX_ENTRIES_PER_SITE*i;
			var site_title = 'Eintrag ' + (site_start+1) + ' bis ' + (site_start+MAX_ENTRIES_PER_SITE);
			sites += '<a title="' + site_title + '" style="font-family:Verdana;" href="javascript:void(0);" onClick="javascript:changeSite('+(site_start)+',\''+mode+'\');">'+(i+1)+'</a>&ensp;';
		}
	}
    var string = '';
    if(num_sites > 1) {
	    string += '<div id="userscore_sites">';
	    string += sites;
	    string += '</div>';
    }
        string += '<div id="userscore">';
        string += '<div id="scoretitle" style="background-color:#99BB99;">';
        string += '<div id="scoreplace"><b>Rang</b> <a class="nolink" href="javascript:void(0);" onClick="javascript:sortRanking(\'r\', \'asc\');" title="Aufsteigend sortieren">&uarr;</a><a class="nolink" href="javascript:void(0);" onClick="javascript:sortRanking(\'r\', \'desc\');" title="Absteigend sortieren">&darr;</a></div>';
        string += '<div id="scoreflag">&ensp;</div>';
        string += '<div id="scorenickname"><b>Name</b> <a class="nolink" href="javascript:void(0);" onClick="javascript:sortRanking(\'n\', \'asc\');" title="Aufsteigend sortieren">&uarr;</a><a class="nolink" href="javascript:void(0);" onClick="javascript:sortRanking(\'n\', \'desc\');" title="Absteigend sortieren">&darr;</a></div>';
        string += '<div id="scorepart"><b>Teiln.</b> <a class="nolink" href="javascript:void(0);" onClick="javascript:sortRanking(\'p\', \'asc\');" title="Aufsteigend sortieren">&uarr;</a><a class="nolink" href="javascript:void(0);" onClick="javascript:sortRanking(\'p\', \'desc\');" title="Absteigend sortieren">&darr;</a></div>';
        string += '<div id="scorewins"><b>Siege</b> <a class="nolink" href="javascript:void(0);" onClick="javascript:sortRanking(\'w\', \'asc\');" title="Aufsteigend sortieren">&uarr;</a><a class="nolink" href="javascript:void(0);" onClick="javascript:sortRanking(\'w\', \'desc\');" title="Absteigend sortieren">&darr;</a></div>';
        if(mode == 'wc') {
			string += '<div id="score"><b>WeltCup</b> <a class="nolink" href="javascript:void(0);" onClick="javascript:sortRanking(\'wc\', \'asc\');" title="Aufsteigend sortieren">&uarr;</a><a class="nolink" href="javascript:void(0);" onClick="javascript:sortRanking(\'wc\', \'desc\');" title="Absteigend sortieren">&darr;</a></div>';
        } else {
  			string += '<div id="score"><b>Punkte</b> <a class="nolink" href="javascript:void(0);" onClick="javascript:sortRanking(\'po\', \'asc\');" title="Aufsteigend sortieren">&uarr;</a><a class="nolink" href="javascript:void(0);" onClick="javascript:sortRanking(\'wc\', \'desc\');" title="Absteigend sortieren">&darr;</a></div>';
        }
		string += '<div id="scoreclear"></div>';
		string += '</div>';

        for(var i=start;i<end;i++) {
        	var points_sum = 0;
        	if(mode == 'wc') {
	            points_sum += _score_list[i]['user_wc_points'];
            } else {
	            points_sum +=  _score_list[i]['user_score'];
            }
			if(parseInt(_score_list[i]['user_rank']) == 1 && parseInt(_matchround['matchround_running'])!=1 &&
			   parseInt(_matchround['matchround_future'])!=1 && points_sum > 0) {
              string += '<div id="rank1">';
            } else if (parseInt(_score_list[i]['user_rank']) == 2 && parseInt(_matchround['matchround_running'])!=1 &&
			   		   parseInt(_matchround['matchround_future'])!=1 && points_sum > 0) {
              string += '<div id="rank2">';
            } else if (parseInt(_score_list[i]['user_rank']) == 3 && parseInt(_matchround['matchround_running'])!=1 &&
					   parseInt(_matchround['matchround_future'])!=1 && points_sum > 0) {
              string += '<div id="rank3">';
            } else if (i%2 == 0) {
                string += '<div id="scoreline">';
            } else {
                string += '<div id="scoreline_div">';
            }

            string += '<div id="scoreplace" style="text-align:center;">';
            if(_score_list[i]['user_id'] == _user_id) {
				string += '<b>' + _score_list[i]['user_rank'] + '</b>';
			} else {
				string += _score_list[i]['user_rank'];
			}
            string += '</div>';
            string += '<div id="scoreflag">';
            if(_score_list[i]['user_favourite_team_nationality'] != 0) {
                string += '<img width="16px" src="'+server+flagImages_+_score_list[i]['user_favourite_team_nationality'].toLowerCase()+'.gif" title="'+_score_list[i]['user_favourite_team_nationality']+'">';
            } else {
                string += '<img width="16px" src="'+server+flagImages_+'aut.gif" title="AUT">';
            }
            string += '</div>';

	        string += '<div id="scorenickname">';
	        string += '<a class="nolink" href="javascript:void(0);" onclick="javascript:dispUserinfoPopup(' + _score_list[i]['user_id'] + ');">';
	        if(_score_list[i]['user_id'] == _user_id) {
			    string += '<b>' + _score_list[i]['user_nickname'] + '</b>';
			} else {
				string += _score_list[i]['user_nickname'];
			}
			string += '</a>';
	        string += '</div>';

            string += '<div id="scorepart">';
            string += _score_list[i]['participations'];
            string += '</div>';
            string += '<div id="scorewins">';
            string += _score_list[i]['matchround_wins'];
            string += '</div>';
            if(mode == 'wc') {
	            string += '<div id="score">';
	            string += '<span style="font-size:6pt;">(Punkte: ';
	            string += _score_list[i]['user_score'];
	            string += ')</span>&ensp;<b>';
	            string += _score_list[i]['user_wc_points'];
	            string += '</b></div>';
            } else {
	            string += '<div id="score">';
	            string += '<span style="font-size:6pt;">(WeltCup: ';
	            string += _score_list[i]['user_wc_points'];
	            string += ')</span>&ensp;<b>';
	            string += _score_list[i]['user_score'];
	            string += '</b></div>';
            }
            string += '<div id="scoreclear"></div></div>';
        }
        string += '</div>';
        if(num_sites > 1) {
		    string += '<div id="userscore_sites">';
		    string += sites;
		    string += '</div>';
    	}
        //}
        return string;
}

function disableSelection() {
    var round_select = document.getElementById('matchround_selection');
    if(round_select) {
        round_select.disabled = true;
    }
    return;
}

function activateSelection() {
    var round_select = document.getElementById('matchround_selection');
    if(round_select) {
        round_select.disabled = false;
    }
    return;
}

function dispWaitMessage(div, text) {
    var image = '<img src=' + server + images_ + 'loading/ajax-loader-in-progress.gif height="10px">';
    var string = '<div style="width:100%; font-size:10pt; text-align:center;">' + image + '&ensp;' + text + '</div>';
    dropLineW3(div, string);
}

function addErrorMessage(error) {
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
    var string = 'Noch keine Spielrunden gespielt! Bitte sp&auml;ter nochmal probieren!';
    addErrorMessage(string);
	dropLineW3('lineup_infoarea_title', '');
	dropLineW3('UserscoreMain', '');
	//document.getElementById('lineup_select_main').style.visibility = 'hidden';
	//document.getElementById('matchlist').style.visibility = 'hidden';
	dropLineW3('Mainright', '');
	return;
}
