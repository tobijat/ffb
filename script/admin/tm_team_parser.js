var players_found = new Array();
var players_found_in_db = new Array();
var players_dbase = new Array();
var players_tm = new Array();
var players_combine = new Array();
var players_update = new Array();
var _teams = new Array();
var _source = 'wf';
var _kader = 'friend';

function init(){
    addTeams();
}

function addTeams() {
    var url = server + 'administration/team/getTeamListForGame.xml';
    //var url = server + 'administration/team/getList.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
 		var teams = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
		for(var i=0;i<teams.length;i++) {
			var team_id = teams[i].getElementsByTagName('team_id')[0].firstChild.data
			_teams[team_id] = new Object();
			_teams[team_id]['team_id'] = teams[i].getElementsByTagName('team_id')[0].firstChild.data;
			_teams[team_id]['team_name'] = teams[i].getElementsByTagName('team_name')[0].firstChild.data;
			_teams[team_id]['team_num_players'] = teams[i].getElementsByTagName('team_num_players')[0].firstChild.data;
			_teams[team_id]['teamfid_fid_foe'] = teams[i].getElementsByTagName('teamfid_fid_foe')[0].firstChild.data;
			_teams[team_id]['teamfid_fid_tm'] = teams[i].getElementsByTagName('teamfid_fid_tm')[0].firstChild.data;
			_teams[team_id]['teamfid_fid_wf'] = teams[i].getElementsByTagName('teamfid_fid_wf')[0].firstChild.data;
			_teams[team_id]['teamfid_name_foe'] = teams[i].getElementsByTagName('teamfid_name_foe')[0].firstChild.data;
			_teams[team_id]['teamfid_name_tm'] = teams[i].getElementsByTagName('teamfid_name_tm')[0].firstChild.data;
			_teams[team_id]['teamfid_name_wf'] = teams[i].getElementsByTagName('teamfid_name_wf')[0].firstChild.data;
			_teams[team_id]['teamfid_url_foe'] = teams[i].getElementsByTagName('teamfid_url_foe')[0].firstChild.data;
			_teams[team_id]['teamfid_url_tm'] = teams[i].getElementsByTagName('teamfid_url_tm')[0].firstChild.data;
			_teams[team_id]['teamfid_url_wf'] = teams[i].getElementsByTagName('teamfid_url_wf')[0].firstChild.data;
    		if(document.administration_form.player_team_post.value == teams[i].getElementsByTagName('team_id')[0].firstChild.data)
                var selected = true;
            else
                var selected = false;
 		    newOption = new Option(teams[i].getElementsByTagName('team_name')[0].firstChild.data +
			 			' (' + teams[i].getElementsByTagName('team_nationality')[0].firstChild.data +
						', ' + teams[i].getElementsByTagName('team_id')[0].firstChild.data +
						', ' + teams[i].getElementsByTagName('team_num_players')[0].firstChild.data +
						' Plz)', teams[i].getElementsByTagName('team_id')[0].firstChild.data, false, selected);
 		    document.administration_form.player_team.options[document.administration_form.player_team.length] = newOption;
		}
		},
		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});
}

function changeTeam() {
    var team_id = document.administration_form.player_team.options[document.administration_form.player_team.selectedIndex].value;
    var source = document.administration_form.source.options[document.administration_form.source.selectedIndex].value;
    var kader = document.administration_form.kader.options[document.administration_form.kader.selectedIndex].value;
    _source = source;
    if(!team_id) {
    	alert('Please choose a Team!');
    	return;
    }
	if(source != 'wf' && source != 'tm') {
		alert('Choose a source!');
		return;
	}

	document.administration_form.playermanagement_tm_url.value = getTeamUrl(team_id, source, kader);
}

function getTeamUrl(team_id, source, kader) {
    if(source == 'wf') {
    	var kader_url = '';
    	if(kader == 'friend2010') {
    		kader_url = 'freundschaft-2010/2/';
    	} else if(kader == 'wm2010') {
    		kader_url = 'wm-2010-in-suedafrika/2/';
    	} else if(kader == 'emquali2012') {
    		kader_url = 'em-qualifikation-2010-2011/2/';
    	} else if(kader == 'wmqualieuropa2014') {
    		kader_url = 'wm-quali-europa-2012-2013/2/';
    	} else if(kader == 'wm2014') {
    		kader_url = 'wm-2014-in-brasilien/2/';
    	} else if(kader == 'em2016') {
    		kader_url = 'em-2016-in-frankreich/2/';
    	} else if(kader == 'emquali2016') {
    		kader_url = 'em-qualifikation-2014-2015/2/';
    	} else {
			alert('Choose a kader!');
			return;
		}
    	return _teams[team_id]['teamfid_url_wf'] + kader_url;
    } else {
		return '';
	}
}

function loadSite() {
    players_update.clear();
    dropLineW3('playerlist_found', 'loading...');
    dropLineW3('playerlist_tm', '');
    dropLineW3('playerlist_db', '');
    dropLineW3('playerlist_update', '');
    dropLineW3('formanswer', '');
    dropLineW3('formerror', '');
    var div = document.getElementById('formanswer');
    div.style.visibility = 'hidden';
    var div = document.getElementById('formerror');
    div.style.visibility = 'hidden';
    if(document.getElementById('send_updates_button')) {
        dropLineW3('send_updates_button_div', '');
    }

    dropLineW3('playerlist_update','');
    var tm_url = document.administration_form.playermanagement_tm_url.value;
    var team_id = document.administration_form.player_team.options[document.administration_form.player_team.selectedIndex].value;
    var source = document.administration_form.source.options[document.administration_form.source.selectedIndex].value;
    var url = server + 'administration/tmTeamParser/loadPlayerlistFromUrl.xml';
    new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;

 		//alert(response.responseText);

 		var playerlist_found = xmlResponse.getElementsByTagName('playerlist_found');
 	    players_found = playerlist_found[0].getElementsByTagName('XML_Serializer_Tag');
 	    var playerlist_found_in_db = xmlResponse.getElementsByTagName('playerlist_found_in_db');
 	    players_found_in_db = playerlist_found_in_db[0].getElementsByTagName('XML_Serializer_Tag');
 	    var playerlist_db = xmlResponse.getElementsByTagName('playerlist_db');
 	    players_dbase = playerlist_db[0].getElementsByTagName('XML_Serializer_Tag');
 	    var playerlist_tm = xmlResponse.getElementsByTagName('playerlist_tm');
 	    players_tm = playerlist_tm[0].getElementsByTagName('XML_Serializer_Tag');
        dispResult();
		},
		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?tm_url='+tm_url+'&team_id='+team_id+'&source='+source
	});
}

function dispResult() {
 	var list = '<b>found:</b><br>';
 	for(var i=0;i<players_found.length;i++) {
 		list += '(' + players_found[i].getElementsByTagName('playerteam_player_position')[0].firstChild.data + ') ';
 		list += '(ID ' + players_found[i].getElementsByTagName('playerteam_id')[0].firstChild.data + ') ';
 		list += '(Profile: ' + players_found[i].getElementsByTagName('player_profile')[0].firstChild.data + ') ';
 		list += players_found[i].getElementsByTagName('player_fname')[0].firstChild.data + ' ' + players_found[i].getElementsByTagName('player_lname')[0].firstChild.data;
 		if(players_found[i].getElementsByTagName('playerteam_status')[0].firstChild.data == 0) {
 		    list += ' (returned from retire!)';
 		    update_length = players_update.push(new Object());
            players_update[update_length-1]['update_modus'] = 'status';
            players_update[update_length-1]['playerteam_id'] = players_found[i].getElementsByTagName('playerteam_id')[0].firstChild.data;
            players_update[update_length-1]['playerteam_status'] = 1;

            var string = '<b>UPDATE STATUS:</b> ' + players_found[i].getElementsByTagName('player_fname')[0].firstChild.data + ' ' + players_found[i].getElementsByTagName('player_lname')[0].firstChild.data + ': change status to active (ID: ' + players_found[i].getElementsByTagName('playerteam_id')[0].firstChild.data + ')';
            addUpdateRow(update_length-1, string);
 		}
        if(players_found[i].getElementsByTagName('new_image')[0].firstChild.data == 1) {
 		    list += ' (new image available!)';
 		    update_length = players_update.push(new Object());
            players_update[update_length-1]['update_modus'] = 'image';
            players_update[update_length-1]['playerteam_id'] = players_found[i].getElementsByTagName('playerteam_id')[0].firstChild.data;
            players_update[update_length-1]['playerteam_player_picture'] = players_found[i].getElementsByTagName('playerteam_player_picture')[0].firstChild.data;;

            var string = '<b>UPDATE IMAGE:</b> ' + players_found[i].getElementsByTagName('player_fname')[0].firstChild.data + ' ' + players_found[i].getElementsByTagName('player_lname')[0].firstChild.data + ': ' + players_found[i].getElementsByTagName('playerteam_player_picture')[0].firstChild.data + ' (ID: ' + players_found[i].getElementsByTagName('playerteam_id')[0].firstChild.data + ')';
            addUpdateRow(update_length-1, string);
 		}
 		if(players_found[i].getElementsByTagName('new_foreign_id')[0].firstChild.data == 1) {
 		    list += ' (new FID available!)';
 		    update_length = players_update.push(new Object());
            players_update[update_length-1]['update_modus'] = 'fid';
            players_update[update_length-1]['playerteam_id'] = players_found[i].getElementsByTagName('playerteam_id')[0].firstChild.data;
            players_update[update_length-1]['player_foreign_id'] = players_found[i].getElementsByTagName('player_foreign_id')[0].firstChild.data;;

            var string = '<b>UPDATE FID:</b> ' + players_found[i].getElementsByTagName('player_fname')[0].firstChild.data + ' ' + players_found[i].getElementsByTagName('player_lname')[0].firstChild.data + ': ' + players_found[i].getElementsByTagName('player_foreign_id')[0].firstChild.data + ' (ID: ' + players_found[i].getElementsByTagName('playerteam_id')[0].firstChild.data + ')';
            addUpdateRow(update_length-1, string);
 		}
 		if(players_found[i].getElementsByTagName('player_status_update')[0].firstChild.data == 1) {
 		    list += ' (new status available!)';
 		    if(players_found[i].getElementsByTagName('player_status_description')[0].firstChild.data !=0) {
 		        var new_status = players_found[i].getElementsByTagName('player_status_description')[0].firstChild.data;
 		    } else {
 		        var new_status = '';
 		    }
 		    update_length = players_update.push(new Object());
            players_update[update_length-1]['update_modus'] = 'description';
            players_update[update_length-1]['playerteam_id'] = players_found[i].getElementsByTagName('playerteam_id')[0].firstChild.data;
            players_update[update_length-1]['player_status_description'] = new_status;

            var string = '<b>UPDATE STATUSDESCRIPTION:</b> ' + players_found[i].getElementsByTagName('player_fname')[0].firstChild.data + ' ' + players_found[i].getElementsByTagName('player_lname')[0].firstChild.data + ': ' + new_status + ' (ID: ' + players_found[i].getElementsByTagName('playerteam_id')[0].firstChild.data + ')';
            addUpdateRow(update_length-1, string);
/*
            if(!document.getElementById('send_updates_button')) {
                var button = '<input id="send_updates_button" type="button" value="send Updates" onclick="javascript:sendUpdates();">';
                dropLineW3('send_updates_button_div', button);
            }
*/
 		}
        list += '<br>';
 	}

 	dropLineW3('playerlist_found', list);

    list = '<b>found in Team but NOT at Transfermark.at:</b><br>';
 	for(var i=0;i<players_dbase.length;i++) {
 	    list += '<div id="player_db_' + i + '">';
 	    if(players_dbase[i].getElementsByTagName('playerteam_status')[0].firstChild.data == 1) {
 	        list += '<a href="javascript:addUpdateStatus(' + i + ',0);">';
 	        list += '<img src="' + server + symbolImages_ + 'status_neg.png" width="16px" border="0" title="Set Status to disabled">';
 	        list += '</a>';
 	    } else {
 	        list += '<a href="javascript:addUpdateStatus(' + i + ',1);">';
 	        list += '<img src="' + server + symbolImages_ + 'status_pos.png" width="16px" border="0" title="Set Status to enabled">';
 	        list += '</a>';
 	    }
 		list += '(' + players_dbase[i].getElementsByTagName('playerteam_player_position')[0].firstChild.data + ') ';
 		list += '(ID ' + players_dbase[i].getElementsByTagName('playerteam_id')[0].firstChild.data + ') ';
 		list += '<a href="javascript:combinePlayer(' + i + ',\'db\');">';
 		list += players_dbase[i].getElementsByTagName('player_fname')[0].firstChild.data + ' ' + players_dbase[i].getElementsByTagName('player_lname')[0].firstChild.data;
        list += '</a></div>';
 	}

 	dropLineW3('playerlist_db', list);

    list = '';
    if(players_found_in_db.length > 0) {
        list += '<b>found in Database and Transfermarkt but NOT in Team:</b><br>';
     	for(var i=0;i<players_found_in_db.length;i++) {
     	    list += '<div id="players_found_in_db_' + i + '">';
     		list += '<a href="javascript:combinePlayer(' + i + ',\'tm\');">';
     		list += players_found_in_db[i].getElementsByTagName('player_fname')[0].firstChild.data + ' ' + players_found_in_db[i].getElementsByTagName('player_lname')[0].firstChild.data;
            list += '</a>';
            list += ' (PID:' + players_found_in_db[i].getElementsByTagName('player_id')[0].firstChild.data + ')';
            list += ' (' + players_found_in_db[i].getElementsByTagName('playerteam_player_position')[0].firstChild.data + ')';
            list += ' (' + players_found_in_db[i].getElementsByTagName('playerteam_team_name')[0].firstChild.data + ')';
            list += '<a href="javascript:addInsertIntoPlayerteam(' + i + ');">';
     	    list += '<img src="' + server + symbolImages_ + 'change.png" width="16px" border="0" title="add from PLAYERTEAM">';
     	    list += '</a>';
     	    list += '&ensp;';
     	    list += '<a href="javascript:addInsertNew(' + i + ');">';
     	    list += '<img src="' + server + symbolImages_ + 'status_pos.png" width="16px" border="0" title="add NEW from TRANSFERMARKT">';
     	    list += '</a>';
            list += '</div>';
     	}
 	}

 	list += '<br><b>found at Transfermarkt but NOT in Database:</b><br>';
 	for(var i=0;i<players_tm.length;i++) {
 	    list += '<div id="player_tm_' + i + '">';
 		list += '<a href="javascript:combinePlayer(' + i + ',\'tm\');">';
 		list += players_tm[i].getElementsByTagName('player_fname')[0].firstChild.data + ' ' + players_tm[i].getElementsByTagName('player_lname')[0].firstChild.data;
        list += '</a>';
        list += ' (' + players_tm[i].getElementsByTagName('playerteam_player_position')[0].firstChild.data + ')';
        list += '<a href="javascript:addInsert(' + i + ');">';
 	    list += '<img src="' + server + symbolImages_ + 'status_pos.png" width="16px" border="0">';
 	    list += '</a>';
        list += '</div>';
 	}
    dropLineW3('playerlist_tm', list);
}

function combinePlayer(index, array) {
    if(array == 'db') {
        var players = players_dbase;
    } else if(array == 'tm') {
        var players = players_tm;
    }

    var combine_index = players_combine.length;
    if(combine_index == 1) {
        if(players_combine[0]['type'] == array && players_combine[0]['index'] == index) {
            var div = document.getElementById('player_'+array+'_'+index);
            div.style.backgroundColor = '';
            players_combine.clear();
            return;
        } else if(players_combine[0]['type'] == array && players_combine[0]['index'] != index) {
            alert('You cannot combine 2 players from the same Column!');
            return;
        }
    }

    var div = document.getElementById('player_'+array+'_'+index);
    div.style.backgroundColor = '#FF0000';

    //alert(players_combine.length);

    players_combine[combine_index] = new Object();
    players_combine[combine_index]['player_fname'] = players[index].getElementsByTagName('player_fname')[0].firstChild.data;
    players_combine[combine_index]['player_lname'] = players[index].getElementsByTagName('player_lname')[0].firstChild.data;
    players_combine[combine_index]['player_status_description'] = players[index].getElementsByTagName('player_status_description')[0].firstChild.data;
    players_combine[combine_index]['playerteam_player_position'] = players[index].getElementsByTagName('playerteam_player_position')[0].firstChild.data;
    players_combine[combine_index]['playerteam_player_picture'] = players[index].getElementsByTagName('playerteam_player_picture')[0].firstChild.data;
    players_combine[combine_index]['type'] = array;
    players_combine[combine_index]['index'] = index;
    if(array == 'db') {
        players_combine[combine_index]['playerteam_id'] = players[index].getElementsByTagName('playerteam_id')[0].firstChild.data;
    }

    if(players_combine.length == 2) {
        if(players_combine[0]['type'] == 'db') {
            var fname_new = players_combine[1]['player_fname'];
            var fname_old = players_combine[0]['player_fname'];
            var lname_new = players_combine[1]['player_lname'];
            var lname_old = players_combine[0]['player_lname'];
            var player_status_new = players_combine[1]['player_status_description'];
            var player_status_old = players_combine[0]['player_status_description'];
            var position_new = players_combine[1]['playerteam_player_position'];
            var position_old = players_combine[0]['playerteam_player_position'];
            var picture_new = players_combine[1]['playerteam_player_picture'];
            var picture_old = players_combine[0]['playerteam_player_picture'];
            var playerteam_id = players_combine[0]['playerteam_id'];
        } else if(players_combine[0]['type'] == 'tm') {
            var fname_new = players_combine[0]['player_fname'];
            var fname_old = players_combine[1]['player_fname'];
            var lname_new = players_combine[0]['player_lname'];
            var lname_old = players_combine[1]['player_lname'];
            var player_status_new = players_combine[0]['player_status_description'];
            var player_status_old = players_combine[1]['player_status_description'];
            var position_new = players_combine[0]['playerteam_player_position'];
            var position_old = players_combine[1]['playerteam_player_position'];
            var picture_new = players_combine[0]['playerteam_player_picture'];
            var picture_old = players_combine[1]['playerteam_player_picture'];
            var playerteam_id = players_combine[1]['playerteam_id'];
        }

        var div = document.getElementById('player_'+players_combine[0]['type']+'_'+players_combine[0]['index']);
        div.innerHTML = '';
        var div = document.getElementById('player_'+players_combine[1]['type']+'_'+players_combine[1]['index']);
        div.innerHTML = '';
        players_combine.clear();

        update_length = players_update.push(new Object());
        players_update[update_length-1]['update_modus'] = 'name';
        players_update[update_length-1]['playerteam_id'] = playerteam_id;
        players_update[update_length-1]['player_fname'] = fname_new;
        players_update[update_length-1]['player_lname'] = lname_new;
        players_update[update_length-1]['playerteam_player_position'] = position_new;
        if(picture_old == 0 && picture_new != 0) {
            players_update[update_length-1]['playerteam_player_picture'] = picture_new;
        } else {
            players_update[update_length-1]['playerteam_player_picture'] = 0;
        }
        if(player_status_old == 0 && player_status_new != 0) {
            players_update[update_length-1]['player_status_description'] = player_status_new;
        } else {
            players_update[update_length-1]['player_status_description'] = '';
        }

        var string = '<b>UPDATE NAME:</b> ' + fname_old + ' ' + lname_old + ' (' + position_old + ') --> ' + fname_new + ' ' + lname_new + ' (' + position_new + ')';
        if(players_update[update_length-1]['playerteam_player_picture']) {
            string += ': ' + players_update[update_length-1]['playerteam_player_picture'];
        }
        if(players_update[update_length-1]['player_status_description']) {
            string += ' Status: ' + players_update[update_length-1]['player_status_description'];
        }
        string += ' (ID: ' + playerteam_id + ')';
        addUpdateRow(update_length-1, string);
    }
}

function addUpdateRow(index, string) {
    //alert('update_checkbox_' + index);

    var checkbox = '<input type="checkbox" id="update_checkbox_' + index + '" checked>&ensp;';
    var update_div = document.getElementById('playerlist_update');

    var html = '<div id="update_div_row_' + index + '">' + checkbox + string + '</div>';

    update_div.innerHTML += html;

    if(!document.getElementById('send_updates_button')) {
        var button = '<input id="send_updates_button" type="button" value="send Updates" onclick="javascript:sendUpdates();">';
        dropLineW3('send_updates_button_div', button);
    }
}

function addUpdateStatus(index, status) {
    if(players_combine.length > 0) {
        alert('Please deselect all players first!');
        return;
    }
    update_length = players_update.push(new Object());
    players_update[update_length-1]['update_modus'] = 'status';
    players_update[update_length-1]['playerteam_id'] = players_dbase[index].getElementsByTagName('playerteam_id')[0].firstChild.data;
    players_update[update_length-1]['playerteam_status'] = status;

    var string = '<b>UPDATE STATUS:</b> ' + players_dbase[index].getElementsByTagName('player_fname')[0].firstChild.data + ' ' + players_dbase[index].getElementsByTagName('player_lname')[0].firstChild.data + ': change Status to '+ status + '. (ID: ' + players_dbase[index].getElementsByTagName('playerteam_id')[0].firstChild.data + ')';
    addUpdateRow(update_length-1, string);

    var div = document.getElementById('player_db_'+index);
    div.innerHTML = '';
}

function addInsert(index) {
    if(players_combine.length > 0) {
        alert('Please deselect all players first!');
        return;
    }
    update_length = players_update.push(new Object());
    players_update[update_length-1]['update_modus'] = 'insert';
    players_update[update_length-1]['playerteam_team_id'] = document.administration_form.player_team.options[document.administration_form.player_team.selectedIndex].value;
    players_update[update_length-1]['player_fname'] = players_tm[index].getElementsByTagName('player_fname')[0].firstChild.data;
    players_update[update_length-1]['player_lname'] = players_tm[index].getElementsByTagName('player_lname')[0].firstChild.data;
    players_update[update_length-1]['player_foreign_id'] = players_tm[index].getElementsByTagName('player_foreign_id')[0].firstChild.data;
    players_update[update_length-1]['playerteam_player_picture'] = players_tm[index].getElementsByTagName('playerteam_player_picture')[0].firstChild.data;
    players_update[update_length-1]['playerteam_player_position'] = players_tm[index].getElementsByTagName('playerteam_player_position')[0].firstChild.data;

    var string = '<b>INSERT NEW:</b> ' + players_tm[index].getElementsByTagName('player_fname')[0].firstChild.data + ' ' + players_tm[index].getElementsByTagName('player_lname')[0].firstChild.data + ' (' + players_tm[index].getElementsByTagName('playerteam_player_position')[0].firstChild.data + ')';
    if(players_tm[index].getElementsByTagName('playerteam_player_picture')[0].firstChild.data != 0) {
        string += ': ' + players_tm[index].getElementsByTagName('playerteam_player_picture')[0].firstChild.data;
    }
    //string += '<br>';
    addUpdateRow(update_length-1, string);

    var div = document.getElementById('player_tm_'+index);
    div.innerHTML = '';
}

function addInsertNew(index) {
    if(players_combine.length > 0) {
        alert('Please deselect all players first!');
        return;
    }
    update_length = players_update.push(new Object());
    players_update[update_length-1]['update_modus'] = 'insert';
    players_update[update_length-1]['playerteam_team_id'] = document.administration_form.player_team.options[document.administration_form.player_team.selectedIndex].value;
    players_update[update_length-1]['player_fname'] = players_found_in_db[index].getElementsByTagName('player_fname')[0].firstChild.data;
    players_update[update_length-1]['player_lname'] = players_found_in_db[index].getElementsByTagName('player_lname')[0].firstChild.data;
    players_update[update_length-1]['player_foreign_id'] = players_found_in_db[index].getElementsByTagName('player_foreign_id')[0].firstChild.data;
    players_update[update_length-1]['playerteam_player_picture'] = players_found_in_db[index].getElementsByTagName('playerteam_player_picture')[0].firstChild.data;
    players_update[update_length-1]['playerteam_player_position'] = players_found_in_db[index].getElementsByTagName('playerteam_player_position')[0].firstChild.data;

    var string = '<b>INSERT NEW:</b> ' + players_found_in_db[index].getElementsByTagName('player_fname')[0].firstChild.data + ' ' + players_found_in_db[index].getElementsByTagName('player_lname')[0].firstChild.data + ' (' + players_found_in_db[index].getElementsByTagName('playerteam_player_position')[0].firstChild.data + ')';
    if(players_found_in_db[index].getElementsByTagName('playerteam_player_picture')[0].firstChild.data != 0) {
        string += ': ' + players_found_in_db[index].getElementsByTagName('playerteam_player_picture')[0].firstChild.data;
    }
    //string += '<br>';
    addUpdateRow(update_length-1, string);

    var div = document.getElementById('players_found_in_db_'+index);
    div.innerHTML = '';
}

function addInsertIntoPlayerteam(index) {
    if(players_combine.length > 0) {
        alert('Please deselect all players first!');
        return;
    }
    update_length = players_update.push(new Object());
    players_update[update_length-1]['update_modus'] = 'insert_pt';
    players_update[update_length-1]['playerteam_team_id'] = document.administration_form.player_team.options[document.administration_form.player_team.selectedIndex].value;
    players_update[update_length-1]['player_fname'] = players_found_in_db[index].getElementsByTagName('player_fname')[0].firstChild.data;
    players_update[update_length-1]['player_lname'] = players_found_in_db[index].getElementsByTagName('player_lname')[0].firstChild.data;
    players_update[update_length-1]['player_id'] = players_found_in_db[index].getElementsByTagName('player_id')[0].firstChild.data;
    players_update[update_length-1]['player_foreign_id'] = players_found_in_db[index].getElementsByTagName('player_foreign_id')[0].firstChild.data;
    //players_update[update_length-1]['playerteam_player_picture'] = players_found_in_db[index].getElementsByTagName('playerteam_player_picture')[0].firstChild.data;
    players_update[update_length-1]['playerteam_player_position'] = players_found_in_db[index].getElementsByTagName('playerteam_player_position')[0].firstChild.data;

    var string = '<b>INSERT FROM PLAYER TO PLAYERTEAM:</b> ' + players_found_in_db[index].getElementsByTagName('player_fname')[0].firstChild.data +
                ' ' + players_found_in_db[index].getElementsByTagName('player_lname')[0].firstChild.data + ' (' +
                players_found_in_db[index].getElementsByTagName('playerteam_player_position')[0].firstChild.data + ')' +
                ' (PID:' + players_found_in_db[index].getElementsByTagName('player_id')[0].firstChild.data + ')' +
                ' (FID:' + players_found_in_db[index].getElementsByTagName('player_foreign_id')[0].firstChild.data + ')' +
                ' (TID:' + document.administration_form.player_team.options[document.administration_form.player_team.selectedIndex].value + ')';
    /*
    if(players_found_in_db[index].getElementsByTagName('playerteam_player_picture')[0].firstChild.data != 0) {
        string += ': ' + players_found_in_db[index].getElementsByTagName('playerteam_player_picture')[0].firstChild.data;
    }
    */
    //string += '<br>';
    addUpdateRow(update_length-1, string);

    var div = document.getElementById('players_found_in_db_'+index);
    div.innerHTML = '';
}

function sendUpdates() {
    if(document.getElementById('send_updates_button')) {
        dropLineW3('send_updates_button_div', '');
    }
    var answer_counter = 0;
    for(var i=0;i<players_update.length;i++) {
        var modus = players_update[i]['update_modus'];
        var checked = document.getElementById('update_checkbox_'+i);
        if(checked.checked) {
            if(modus == 'insert') {
                var url = server + 'administration/teamParserHelpers/insertNewPlayer.xml';
                new Ajax.Request(url, {
             		onSuccess : function(response) {
             		var xmlResponse=response.responseXML;
             		answer_counter++;
             		//alert(response.responseText);
             		var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.data;
             	    if(status == 200) {
             	        var text = xmlResponse.getElementsByTagName('administration_answer')[0].firstChild.data;
             	    } else {
             	        var text = xmlResponse.getElementsByTagName('administration_error')[0].firstChild.data;
             	    }
             	    dispAnswer(status, answer_counter, players_update.length, text);
            		},
            		onFailure : function(response) {
                	alert("Oops, there's been an error.");
             		},
             		parameters: '?playerteam_team_id='+players_update[i]['playerteam_team_id']+
                                '&player_fname='+players_update[i]['player_fname']+
                                '&player_lname='+players_update[i]['player_lname']+
                                '&player_foreign_id='+players_update[i]['player_foreign_id']+
                                '&playerteam_player_position='+players_update[i]['playerteam_player_position']+
                                '&playerteam_player_picture='+players_update[i]['playerteam_player_picture']+
                                '&source='+_source
            	});
            } else if(modus == 'insert_pt') {
                var url = server + 'administration/teamParserHelpers/insertPlayerToPlayerteam.xml';
                new Ajax.Request(url, {
             		onSuccess : function(response) {
             		var xmlResponse=response.responseXML;
             		answer_counter++;
             		//alert(response.responseText);
             		var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.data;
             	    if(status == 200) {
             	        var text = xmlResponse.getElementsByTagName('administration_answer')[0].firstChild.data;
             	    } else {
             	        var text = xmlResponse.getElementsByTagName('administration_error')[0].firstChild.data;
             	    }
             	    dispAnswer(status, answer_counter, players_update.length, text);
            		},
            		onFailure : function(response) {
                	alert("Oops, there's been an error.");
             		},
             		parameters: '?playerteam_team_id='+players_update[i]['playerteam_team_id']+
                                '&player_fname='+players_update[i]['player_fname']+
                                '&player_lname='+players_update[i]['player_lname']+
                                '&player_id='+players_update[i]['player_id']+
                                '&player_foreign_id='+players_update[i]['player_foreign_id']+
                                '&playerteam_player_position='+players_update[i]['playerteam_player_position']+
                                '&source='+_source
            	});
            } else if(modus == 'status') {
                var url = server + 'administration/teamParserHelpers/updatePlayerteamStatus.xml';
                new Ajax.Request(url, {
             		onSuccess : function(response) {
             		var xmlResponse=response.responseXML;
             		answer_counter++;
             		//alert(response.responseText);
             		var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.data;
             	    if(status == 200) {
             	        var text = xmlResponse.getElementsByTagName('administration_answer')[0].firstChild.data;
             	    } else {
             	        var text = xmlResponse.getElementsByTagName('administration_error')[0].firstChild.data;
             	    }
             	    dispAnswer(status, answer_counter, players_update.length, text);
            		},
            		onFailure : function(response) {
                	alert("Oops, there's been an error.");
             		},
             		parameters: '?playerteam_id='+players_update[i]['playerteam_id']+
                                '&playerteam_status='+players_update[i]['playerteam_status']+
                                '&source='+_source
            	});
            } else if(modus == 'description') {
                var url = server + 'administration/teamParserHelpers/updateStatusDescription.xml';
                new Ajax.Request(url, {
             		onSuccess : function(response) {
             		var xmlResponse=response.responseXML;
             		answer_counter++;
             		//alert(response.responseText);
             		var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.data;
             	    if(status == 200) {
             	        var text = xmlResponse.getElementsByTagName('administration_answer')[0].firstChild.data;
             	    } else {
             	        var text = xmlResponse.getElementsByTagName('administration_error')[0].firstChild.data;
             	    }
             	    dispAnswer(status, answer_counter, players_update.length, text);
            		},
            		onFailure : function(response) {
                	alert("Oops, there's been an error.");
             		},
             		parameters: '?playerteam_id='+players_update[i]['playerteam_id']+
                                '&player_status_description='+players_update[i]['player_status_description']+
                                '&source='+_source
            	});

            } else if(modus == 'image') {
                //alert('image');
                var url = server + 'administration/teamParserHelpers/updateImage.xml';
                var image_index = i;
                new Ajax.Request(url, {
             		onSuccess : function(response) {
             		var xmlResponse=response.responseXML;
             		answer_counter++;
             		//alert(response.responseText);
             		var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.data;
             	    if(status == 200) {
             	        var text = xmlResponse.getElementsByTagName('administration_answer')[0].firstChild.data;
             	    } else {
             	        var text = xmlResponse.getElementsByTagName('administration_error')[0].firstChild.data;
             	    }
             	    dispAnswer(status, answer_counter, players_update.length, text);
            		},
            		onFailure : function(response) {
                	alert("Oops, there's been an error.");
             		},
             		parameters: '?playerteam_id='+players_update[i]['playerteam_id']+
                                '&playerteam_player_picture='+players_update[i]['playerteam_player_picture']+
                                '&source='+_source
            	});

            } else if(modus == 'fid') {
                //alert('image');
                var url = server + 'administration/teamParserHelpers/updateForeignId.xml';
                //var image_index = i;
                new Ajax.Request(url, {
             		onSuccess : function(response) {
             		var xmlResponse=response.responseXML;
             		answer_counter++;
             		//alert(response.responseText);
             		var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.data;
             	    if(status == 200) {
             	        var text = xmlResponse.getElementsByTagName('administration_answer')[0].firstChild.data;
             	    } else {
             	        var text = xmlResponse.getElementsByTagName('administration_error')[0].firstChild.data;
             	    }
             	    dispAnswer(status, answer_counter, players_update.length, text);
            		},
            		onFailure : function(response) {
                	alert("Oops, there's been an error.");
             		},
             		parameters: '?playerteam_id='+players_update[i]['playerteam_id']+
                                '&player_foreign_id='+players_update[i]['player_foreign_id']+
                                '&source='+_source
            	});

            } else if(modus == 'name') {
                var url = server + 'administration/teamParserHelpers/updateName.xml';
                //alert(source);
                new Ajax.Request(url, {
             		onSuccess : function(response) {
             		var xmlResponse=response.responseXML;
             		answer_counter++;
             		//alert(response.responseText);
             		var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.data;
             	    if(status == 200) {
             	        var text = xmlResponse.getElementsByTagName('administration_answer')[0].firstChild.data;
             	    } else {
             	        var text = xmlResponse.getElementsByTagName('administration_error')[0].firstChild.data;
             	    }
             	    dispAnswer(status, answer_counter, players_update.length, text);
            		},
            		onFailure : function(response) {
                	alert("Oops, there's been an error.");
             		},
             		parameters: '?playerteam_id='+players_update[i]['playerteam_id']+
                                '&player_fname='+players_update[i]['player_fname']+
                                '&player_lname='+players_update[i]['player_lname']+
                                '&player_status_description='+players_update[i]['player_status_description']+
                                '&playerteam_player_position='+players_update[i]['playerteam_player_position']+
                                '&playerteam_player_picture='+players_update[i]['playerteam_player_picture']+
                                '&source='+_source
            	});
            }
        }
    }
}

function dispAnswer(status, answer_counter, length, text) {
    if(status == 200) {
        var answer = '<b>Job ' + answer_counter + '/' + length + ':</b> ' + text;
        var answer_div = document.getElementById('formanswer');
        answer_div.style.visibility = 'visible';
        answer_div.innerHTML += answer + '<br>';
    } else {
        var error = '<b>Job ' + answer_counter + '/' + length + ':</b> ' + text;
        var error_div = document.getElementById('formerror');
        error_div.style.visibility = 'visible';
        error_div.innerHTML += error + '<br>';
    }
}
