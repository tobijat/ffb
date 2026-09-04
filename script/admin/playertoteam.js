var playerlist_ = new Array();
var searchPlayerlist_ = new Array();
var editPlayerlist_ = new Array();

var IMG_UPDATE_YES = symbolImages_ + 'change.png';
var IMG_UPDATE_NO = symbolImages_ + 'ok.png';
var REQUEST_OK = symbolImages_ + 'status_pos.png';
var REQUEST_FAILED = symbolImages_ + 'status_neg.png';
var REQUEST_SEND = 10;

function init() {
	var url = server + 'administration/team/getList.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		var teams = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
		var selectTeam='<select name="id" id="team_id" size="1" onchange="javascript:teamPlayers(this.value);"><option disabled selected>choose a Team</option>';
 		for(i=0;i<teams.length;i++) {
 			//team id
 			selectTeam +='<option value="'+teams[i].getElementsByTagName('team_id')[0].firstChild.data+'">';
 			//team name
			selectTeam += teams[i].getElementsByTagName('team_name')[0].firstChild.data;
			//short tag
			selectTeam+=' (' + teams[i].getElementsByTagName('team_nationality')[0].firstChild.data +')</option>'
			}
		selectTeam+='</select>';
		dropLineW3('teamselect', selectTeam);

		},

		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});

}

function teamPlayers(playerteam_team_id) {
	var url = server + 'administration/playertoteam/getTeamPlayers.xml';
	dropLineW3('PlayerToTeams', MEDIUM_LOAD);
	playerlist_.clear();
	var pltt_id =  playerteam_team_id;
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		//alert(response.responseText);
 		var xmlResponse=response.responseXML;
 		if(!xmlResponse) {
 			handleAjaxError();
 			return;
 		}
 		var players = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
		var selectTeamPlayer= '<div id="playerhead"><div id="playerhead_name">Player Name</div>' +
							  '<div id="playerhead_nationality">Nationality</div>' +
							  '<div id="playerhead_position">Position</div>' +
							  '<div id="playerhead_price">Price</div>' +
							  '<div id="playerhead_delete">delete</div>' +
							  '<div id="playerhead_hasaction">update</div>' +
							  '</div><br>';//'<table class="playertable"><tr><th>player name</th><th>position</th><th>price</th><th>info</th><th>delete</th><th>player update</th></tr>';
 		for(i=0;i<players.length;i++) {
 			playerlist_[i] = new Object();
 			//player id
 			playerlist_[i]['player_id'] = players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue;
 			//player first name
			playerlist_[i]['player_fname'] = players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
 			//player last name
			playerlist_[i]['player_lname'] = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue;
			//nationality
			playerlist_[i]['player_nationality'] = players[i].getElementsByTagName('player_nationality')[0].firstChild.nodeValue;
			//player status
			playerlist_[i]['player_status'] = players[i].getElementsByTagName('player_status')[0].firstChild.nodeValue;
			playerlist_[i]['playerteam_status'] = players[i].getElementsByTagName('playerteam_status')[0].firstChild.nodeValue;
			//player status description
			if(players[i].getElementsByTagName('player_status_description')[0].firstChild==null)
			 playerlist_[i]['player_status_description'] ='';
			else
			 playerlist_[i]['player_status_description'] = players[i].getElementsByTagName('player_status_description')[0].firstChild.nodeValue;
			//player position
			playerlist_[i]['player_position'] = players[i].getElementsByTagName('playerteam_player_position')[0].firstChild.nodeValue;
			//player game price
			playerlist_[i]['player_price'] = players[i].getElementsByTagName('playerteam_player_price')[0].firstChild.nodeValue;
			//playerteam id
			playerlist_[i]['playerteam_id'] = players[i].getElementsByTagName('playerteam_id')[0].firstChild.nodeValue;
			if(players[i].getElementsByTagName('playerteam_player_picture')[0].firstChild==null ||
			   players[i].getElementsByTagName('playerteam_player_picture')[0].firstChild.nodeValue == 0)
			    playerlist_[i]['playerteam_player_picture'] = '';
			else
			    playerlist_[i]['playerteam_player_picture'] = players[i].getElementsByTagName('playerteam_player_picture')[0].firstChild.nodeValue;
			//player team id (team he plays for))
			playerlist_[i]['playerteam_team_id'] = pltt_id;
			//team text name
			playerlist_[i]['playerteam_team_name'] = "TODO";
		}
		for(i=0;i<playerlist_.length;i++) {
			selectTeamPlayer += formPlayerString(playerlist_[i], "old", i);
		}
		$('players').setStyle('display:block');
		dropLineW3('PlayerToTeams', selectTeamPlayer);

		},

		 onFailure : function(response) {
    	handleAjaxError();
 		},

		parameters: 'id=' + encodeURIComponent(pltt_id)
	});


}

function searchPlayer() {
	var url = server + 'administration/player/getPartList.xml';
	dropLineW3('playerresult', MEDIUM_LOAD);
	searchPlayerlist_.clear();
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
 		var toDisplay = '';
		var players = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		for(var i=0;i<players.length;i++) {
 			 searchPlayerlist_[i] = new Object();
			 searchPlayerlist_[i]['player_id'] = players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue;
			 searchPlayerlist_[i]['player_fname'] = players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
			 searchPlayerlist_[i]['player_lname'] = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue;
			 searchPlayerlist_[i]['player_nationality'] = players[i].getElementsByTagName('player_nationality')[0].firstChild.nodeValue;
			 searchPlayerlist_[i]['player_team_name'] = players[i].getElementsByTagName('player_team_name')[0].firstChild.nodeValue;
			 if(i==0)
 			   min_id = players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue;
		}
		for(var i=0;i<searchPlayerlist_.length&&i<20;i++) {
			toDisplay += formPlayerString(searchPlayerlist_[i], "new", i);
		}
		if(searchPlayerlist_.length>=20)
		toDisplay = '<p><a href="javascript:changePage(20);">next &gt;&gt;</a></p>' + toDisplay;

		dropLineW3('playerresult', toDisplay);

		},

		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},

		parameters: Form.serialize($("playerselect"))
	});
}


function changePlayer (playerID, action, index) {
	thePlayer  = new Array();
	switch (action) {
		case "insert":
		    if(searchPlayerlist_[index]['player_id']==playerID) {
		    	if(!isPlayerInEditList(searchPlayerlist_[index]['player_id'], $('team_id').value, null, "insert")) {
		    		thePlayer = searchPlayerlist_[index];
		  			tmpObject = new Object();
		  			tmpObject['playerteam_id'] =null;//todo
		  			tmpObject['playerteam_player_id'] = thePlayer['player_id'];
		  			tmpObject['playerteam_team_id'] = $('team_id').value;//correct
		  			tmpObject['playerteam_player_price'] = 5;
		  			tmpObject['playerteam_player_picture'] = '';
		  			tmpObject['playerteam_status'] = 1;
			  		tmpObject['playerteam_player_position'] = 'd';
		  			tmpObject['player_full_name'] = thePlayer['player_lname'] + ' ' + thePlayer['player_fname'];
			  		tmpObject['playerteam_team_name'] = 'new team: ' + $("team_id").text;
			  		tmpObject['action'] = 'insert';
			  		tmpObject['action_done'] = 0;
			  		editPlayerlist_.push(tmpObject);
			  	}
		  	}
		  	break;
		case "update":
			if(playerlist_[index]['player_id']==playerID&&!isPlayerInEditList(playerlist_[index]['player_id'], playerlist_[index]['playerteam_team_id'], playerlist_[index]['playerteam_id'], "update")) {
				thePlayer = playerlist_[index];
				tmpObject = new Object();
				tmpObject['playerteam_id'] =thePlayer['playerteam_id'];//correct
		  		tmpObject['playerteam_player_id'] = thePlayer['player_id'];
		  		tmpObject['playerteam_team_id'] = thePlayer['playerteam_team_id'];//hopefully correct
		  		tmpObject['playerteam_player_price'] = thePlayer['player_price'];
		  		tmpObject['playerteam_player_picture'] = thePlayer['playerteam_player_picture'];
		  		tmpObject['playerteam_status'] = thePlayer['playerteam_status'];
		  		tmpObject['playerteam_player_position'] = thePlayer['player_position'];
		  		tmpObject['player_full_name'] = thePlayer['player_lname'] + ' ' + thePlayer['player_fname'];
		  		tmpObject['playerteam_team_name'] = thePlayer['playerteam_team_name'];
		  		tmpObject['action'] = 'update';
		  		tmpObject['action_done'] = 0;
		  		editPlayerlist_.push(tmpObject);
		  		var warningImg = 'change' + index;
				$(warningImg).src = server + IMG_UPDATE_YES;
		  		$(warningImg).alt = 'in update list';
				//dropLineW3(warningImg, '<img src="' + server +'images/symbols/warning.gif" alt="in update list" width="21" height="21">');
			}
			break;
		case "delete":
			if(playerlist_[index]['player_id']==playerID&&!isPlayerInEditList(playerlist_[index]['player_id'], playerlist_[index]['playerteam_team_id'], playerlist_[index]['playerteam_id'], "delete")) {
				thePlayer = playerlist_[index];
				tmpObject = new Object();
				tmpObject['playerteam_id'] =thePlayer['playerteam_id'];
		  		tmpObject['playerteam_player_id'] = thePlayer['player_id'];
		  		tmpObject['playerteam_team_id'] = thePlayer['playerteam_team_id'];//hopefully correct
		  		tmpObject['playerteam_player_price'] = thePlayer['player_price'];
		  		tmpObject['playerteam_player_picture'] = thePlayer['playerteam_player_picture'];
		  		tmpObject['playerteam_status'] = thePlayer['playerteam_status'];
		  		tmpObject['playerteam_player_position'] = thePlayer['player_position'];
		  		tmpObject['player_full_name'] = thePlayer['player_lname'] + ' ' + thePlayer['player_fname'];
		  		tmpObject['playerteam_team_name'] = thePlayer['playerteam_team_name'];
		  		tmpObject['action'] = 'delete';
		  		tmpObject['action_done'] = 0;
		  		editPlayerlist_.push(tmpObject);
		  		var warningImg = 'change' + index;
		  		$(warningImg).src = server + IMG_UPDATE_YES;
		  		$(warningImg).alt = 'in delete list';
		  		//dropLineW3(warningImg, '<img src="' + server +'images/symbols/warning.gif" alt="in delete list" width="21" height="21">');
			}
			break;
		default:
			break;
	}
	displayChanges();
}

function isPlayerInEditList(playerID, playerTeamTeamID, playerTeamID, action) {
	var inList = false;
	for(var i=0;i<editPlayerlist_.length;i++) {
		if(editPlayerlist_[i]['playerteam_player_id']==playerID&&editPlayerlist_[i]['playerteam_team_id']==playerTeamTeamID&&editPlayerlist_[i]['playerteam_id']==playerTeamID&&!editPlayerlist_[i]['action_done']) {
				alert('warning: player already in list for updates or new players, or to delete, confirm changes before further action.');
				inList = true;
				break;
		}
	}

	if(action=='insert') { // insert player if he's already inteam
		for(var i=0;i<playerlist_.length;i++){
			if(playerlist_[i]['player_id']==playerID&&playerlist_[i]['playerteam_team_id']==playerTeamTeamID) {
				alert('warning: you cannot assign a player twice to this team.');
				inList=true;
				break;
			}

		}
	}
	return inList;
}

function displayChanges() {
	var toDisplay = new Array("","","","");
	var toDisplayPre =  '<table class="edit"><tr><th colspan="9"><a href="javascript:confirmAll();">changes (click here to confirm all)</a></th></tr><tr><th>player name</th><th>team<br>name</th><th>player<br>ID</th><th>player to<br>team<br>ID</th><th>player<br>price</th><th>player<br>picture<br>URL</th><th>player<br>team<br>status</th><th>player<br>position</th><th>confirm<br>action</th></tr>\r\n';
	for(var i=0;i<editPlayerlist_.length;i++) {
		var list = 0;
		if(editPlayerlist_[i]['action']=="update")
		  list = 1;
		if(editPlayerlist_[i]['action']=="delete")
		  list = 2;
		if(editPlayerlist_[i]['action_done']!=0)
		  list = 3;

		toDisplay[list] = 	toDisplay[list] +
        '<tr id="' + list + '">' +
        '<td>' + editPlayerlist_[i]['player_full_name'] +
        '</td><td>' + editPlayerlist_[i]['playerteam_team_name'] +
		'</td><td>' + editPlayerlist_[i]['playerteam_player_id'] +
        '</td><td>' + editPlayerlist_[i]['playerteam_team_id'] +
		'</td><td>' + formPlayerPrice(editPlayerlist_[i]['playerteam_player_price'], i) +
        '</td><td>' + '<input type="text" size="50" maxlangth="150" value="' + editPlayerlist_[i]['playerteam_player_picture'] + '" onchange="javascript:changeImage(this.value,+'+i+');">' +
		'</td><td>' + '<input type="radio" name="radio' + i + '" value="1" onClick="javascript:changeTeamStatus(1,'+i+')"';
		if(editPlayerlist_[i]['playerteam_status']==1) {
			//alert('active!');
            toDisplay[list] = 	toDisplay[list] + ' checked';
		}
		toDisplay[list] = 	toDisplay[list] + '>active <input type="radio" name="radio' + i + '"  value="0" onClick="javascript:changeTeamStatus(0,'+i+')"';
		if(editPlayerlist_[i]['playerteam_status']==0) {
		    //alert('inactive!');
			toDisplay[list] = 	toDisplay[list] + ' checked';
		}
		toDisplay[list] = 	toDisplay[list] + '>inactive' +
        '</td><td>' + formPlayerPosition(editPlayerlist_[i]['playerteam_player_position'], i) +
		'</td><td id="send' + i + '">';
		var status = parseInt(editPlayerlist_[i]['action_done']);
		if(!status) {
			toDisplay[list] = 	toDisplay[list] + '<input type="button" id="' + editPlayerlist_[i]['action'] + i +
								'" value="' + editPlayerlist_[i]['action'] +
								'" onClick="javascript:sendPlayerInfos(' + i +');">';
		} else {
			//var status= parseInt(editPlayerlist_[i]['action']);
			if(status==SERVER_STATUS_OK||status==SERVER_STATUS_INSERT_OK||status==SERVER_STATUS_UPDATE_OK||status==SERVER_STATUS_DELETE_OK)
				toDisplay[list] = 	toDisplay[list] + '<img src="'+server+REQUEST_OK +'" alt="request ok" width="20" height="20">';
			else if (status==REQUEST_SEND)
				toDisplay[list] = 	toDisplay[list] + IN_PROGRESS_LOAD;
			else
				toDisplay[list] = 	toDisplay[list] + '<img src="'+server+REQUEST_FAILED +'" alt="request failed" width="20" height="20">';
		}
		toDisplay[list] = 	toDisplay[list] + '</td></tr>\r\n';
	}
	dropLineW3('Confirm', toDisplayPre+toDisplay[0]+'<tr><td colspan="9"><hr></td></tr>' + toDisplay[1]+'<tr><td colspan="9"><hr></td></tr>' +toDisplay[2]+'<tr><th colspan="9">done<br><hr></th></tr>'+toDisplay[3]+'</table>');
}

function formPlayerString(thePlayer, status, index) {
	var thePlayerString='';
	switch (status) {
		case "old":
			thePlayerString += '<div id="playerinfo">'+
							   '<div id="playerbody_name">' +
							   '<a href="javascript:changePlayer('+thePlayer['player_id'] +
							   ', \'update\', ' + index +');">' + thePlayer['player_lname'] +
							   ' ' + thePlayer['player_fname'] +' </a></div>' + //end playername
							   '<div id="playerbody_nationality"><img id="flagimg" src="' +
							   server + flagImages_ +thePlayer['player_nationality'].toLowerCase() +
							   '.gif" alt="'+ thePlayer['player_nationality'] + '"></div>' +//end player nationality
							   '<div id="playerbody_position">' + thePlayer['player_position'] + '</div>' + //end player position
							   '<div id="playerbody_price">' + thePlayer['player_price'] + '</div>' + //end player price
							   '<div id="playerbody_delete">'+ '<input class="delete" id="delete' + index +
							   '" type="checkbox" onmouseup="javascript:deletePlayer('+ thePlayer['player_id'] + ',' +
							   index+');"></input></div>' + //end player delete
							   '<div id="playerbody_hasaction"><img id="change' + index + '" src="' +
							   server + IMG_UPDATE_NO +'" alt="not changed"></div>' + //end has changed img
							   '</div><br>';
			break;
		case "new":
			thePlayerString += '\r\n<div id="playerlist"><img id="flagimg" src="' +
			server + flagImages_ + thePlayer['player_nationality'].toLowerCase() +
			'.gif" alt="' +
			thePlayer['player_nationality'] + '"><a class="playerlink" href="javascript:changePlayer(' +
			thePlayer['player_id'] + ', \'insert\',' +
			index +');">'+thePlayer['player_fname'] +
			' ' + thePlayer['player_lname'] +
			'</a> <em>'+thePlayer['player_team_name']+'</em> </div>\r\n';
			break;
		default:
			thePlayerSting += 'error, not definded playerstring';
			break;
	}
	return thePlayerString;
}

function formPlayerPosition(position, index) {
	var toReturn = '<select size="1" onchange="javascript:changePosition(this.value, '+ index + ');">\r\n';
	if(position=="d")
	  toReturn += '<option value="d" selected>defence</option>\r\n';
	else
	  toReturn += '<option value="d">defence</option>\r\n';

	if(position=="g")
	  toReturn += '<option value="g" selected>goalie</option>\r\n';
	else
	  toReturn += '<option value="g">goalie</option>\r\n';

	if(position=="m")
	  toReturn += '<option value="m" selected>midfield</option>\r\n';
	else
	  toReturn += '<option value="m">midfield</option>\r\n';

	if(position=="s")
	  toReturn += '<option value="s" selected>striker</option>\r\n';
	else
	  toReturn += '<option value="s">striker</option>\r\n';

	return toReturn + '</select>\r\n';
}

function formPlayerPrice(price, index) {
	var toReturn = '<select size="1" onchange="javascript:changePrice(this.value, '+ index +');">\r\n';
	for(var i=1;i<13;i++) {
		toReturn += '<option value="0' + i;
		if(price==i)
		  toReturn += '" selected>' + i + '</option>\r\n';
		else
		  toReturn += '">' + i +'</option>\r\n';
	}
	toReturn += '</select>';
	return toReturn;
}

function deletePlayer(playerID, index) {
	var value = $('delete'+index).checked;
	//alert(value);
	if(!value) {
		$('delete'+index).checked = true;
		changePlayer(playerID, 'delete', index);
		  //$('delete'+index).checked=false; //todo what if player is already in list ...
	}
	else {//TODO delete player from edit list
		$('delete'+index).checked = false;
	}
}
function changeTeamStatus(newStatus, index) {
    //alert('changeteamstatus');
	editPlayerlist_[index]['playerteam_status'] = newStatus;
}

function changePosition(newPosition, index) {
	editPlayerlist_[index]['playerteam_player_position'] = newPosition;
}

function changePrice(newPrice, index) {
	editPlayerlist_[index]['playerteam_player_price'] = newPrice;
}

function changeImage(imagePath, index) {
	editPlayerlist_[index]['playerteam_player_picture'] = imagePath;
}

function confirmAll() {
	for(var i=0;i<editPlayerlist_.length;i++) {
		if(editPlayerlist_[i]['action_done']==0)
		  sendPlayerInfos(i);
	}
}

function sendPlayerInfos(index) {
	var url = server + 'administration/playertoteam/managePlayers.xml';
	dropLineW3('PlayerToTeams', 'Team infos may have changed.');
	dropLineW3('send'+index, IN_PROGRESS_LOAD);
	editPlayerlist_[index]['action_done'] = REQUEST_SEND;
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
 		var answer = xmlResponse.getElementsByTagName('array');
		var status = answer[0].getElementsByTagName('administration_status')[0].firstChild.nodeValue;
		if(status==SERVER_STATUS_OK||status==SERVER_STATUS_INSERT_OK||status==SERVER_STATUS_UPDATE_OK||status==SERVER_STATUS_DELETE_OK) {
			dropLineW3('send'+index, '<img src="'+server + REQUEST_OK+'" alt="request ok" width="20" height="20">');
			editPlayerlist_[index]['action_done'] = parseInt(status);
		} else {
			dropLineW3('send'+index, '<img src="'+server+ REQUEST_FAILED +'" alt="request failed" width="20" height="20">');
			editPlayerlist_[index]['action_done'] = parseInt(status);
		}
		},

		 onFailure : function(response) {
		 	editPlayerlist_[index]['action'] = 500;
    	alert("Oops, there's been an error.");
 		},

		parameters: 'action='+editPlayerlist_[index]['action'] +'&playerteam_id='+editPlayerlist_[index]['playerteam_id']+'&playerteam_player_id='+editPlayerlist_[index]['playerteam_player_id']+'&playerteam_team_id='+editPlayerlist_[index]['playerteam_team_id']+'&playerteam_player_picture='+editPlayerlist_[index]['playerteam_player_picture']+'&playerteam_status='+editPlayerlist_[index]['playerteam_status']+'&playerteam_player_price='+editPlayerlist_[index]['playerteam_player_price']+'&playerteam_player_position='+editPlayerlist_[index]['playerteam_player_position']
	});
}

function changePage(index) {
	var toDisplay='';
	for(var i=index;i<searchPlayerlist_.length&&i<(21+index);i++) {
			toDisplay += formPlayerString(searchPlayerlist_[i], "new", i);
		}
	var min_id= index-20;
	var max_id= index+20;
	var tmpLast = '';
	var tmpNxt = '';
	if(min_id>=0)
	  tmpLast = '<a href="javascript:changePage('+min_id+');">&lt;&lt; last</a>&nbsp;&nbsp;';
	if(max_id<=searchPlayerlist_.length)
	  tmpNxt = '<a href="javascript:changePage('+max_id+');">next &gt;&gt;</a>';
	toDisplay = '<p>'+tmpLast+tmpNxt+'</p>' + toDisplay;

	dropLineW3('playerresult', toDisplay);
}