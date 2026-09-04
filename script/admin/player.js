/**
 *
 * @access public
 * @return void
 **/
function init(){
    addTeams();
    searchPlayer();
}

function searchPlayer() {
    var searchPlayerlist_ = new Array();
	var url = server + 'administration/player/getPartList.xml';
	dropLineW3('list', MEDIUM_LOAD);
	searchPlayerlist_.clear();
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
 		var toDisplay = '';
		var players = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		for(var i=0;i<players.length;i++) {
 			 searchPlayerlist_[i] = new Object();
			 searchPlayerlist_[i]['player_id'] = players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue;
			 searchPlayerlist_[i]['player_fname'] = players[i].getElementsByTagName('player_fname')[0].firstChild.nodeValue;
			 searchPlayerlist_[i]['player_lname'] = players[i].getElementsByTagName('player_lname')[0].firstChild.nodeValue;
			 searchPlayerlist_[i]['player_nationality'] = players[i].getElementsByTagName('player_nationality')[0].firstChild.nodeValue;
			 searchPlayerlist_[i]['player_status'] = players[i].getElementsByTagName('player_status')[0].firstChild.nodeValue;
             searchPlayerlist_[i]['player_status_description'] = players[i].getElementsByTagName('player_status_description')[0].firstChild.nodeValue;
             searchPlayerlist_[i]['player_team_name'] = players[i].getElementsByTagName('player_team_name')[0].firstChild.nodeValue;
			 if(i==0)
 			   min_id = players[i].getElementsByTagName('player_id')[0].firstChild.nodeValue;
		}

		for(var i=0;i<searchPlayerlist_.length;i++) {
		    toDisplay += '<div id="listitem">';
            toDisplay += '<div id="listline">';
            toDisplay += '<div id="listdescr">';
            toDisplay += '<div id="playername">';

            if(searchPlayerlist_[i]['player_status']) {
                toDisplay += '<img src="'+server+symbolImages_+'status_pos.png"> ';
            } else {
                toDisplay += '<img src="'+server+symbolImages_+'status_neg.png" title="'+searchPlayerlist_[i]['player_status_description']+'"> ';
            }
            toDisplay += '<img src="'+server+flagImages_+searchPlayerlist_[i]['player_nationality'].toLowerCase()+'.gif" width="20px" height="15px" title="'+searchPlayerlist_[i]['player_nationality']+'"> ';
            toDisplay += '<b>'+searchPlayerlist_[i]['player_fname']+' '+searchPlayerlist_[i]['player_lname']+' ('+searchPlayerlist_[i]['player_id']+')</b> <em>'+searchPlayerlist_[i]['player_team_name']+'</em>';
            toDisplay += '</div>';
            toDisplay += '</div>';
            toDisplay += '</div>';
            toDisplay += '<div id="listclear"></div>';
            toDisplay += '<div id="listline">';
            toDisplay += '<div id="listsymbol">';
            toDisplay += '<form method="POST" action="./player">';
            toDisplay += '<input type="hidden" name="player_id" value="'+searchPlayerlist_[i]['player_id']+'">';
            toDisplay += '<input type="image" src="'+server+symbolImages_+'edit.png" title="edit the entry" value="player_administration_change" name="player_administration_change"> ';
            toDisplay += '<input type="image" src="'+server+symbolImages_+'delete.png" title="delete the entry" value="player_administration_delete" name="player_administration_delete">';
            toDisplay += '</form>';
            toDisplay += '</div>';
            toDisplay += '</div>';
            toDisplay += '<div id="listclear"></div>';
            toDisplay += '</div>';
		}
		dropLineW3('list', toDisplay);

		},

		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},

		parameters: Form.serialize($("playerselect"))
	});
}

function addTeams() {
    var url = server + 'administration/team/getList.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		var teams = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		for(var i=0;i<teams.length;i++) {
 		    var selected = false;
 		    newOption = new Option(teams[i].getElementsByTagName('team_name')[0].firstChild.data + ' (' + teams[i].getElementsByTagName('team_nationality')[0].firstChild.data + ')', teams[i].getElementsByTagName('team_id')[0].firstChild.data, false, selected);
 		    document.playerselect.player_team.options[document.playerselect.player_team.length] = newOption;
		}
		if(document.administration_form.player_team) {
		    //alert('hallo');
		    for(var i=0;i<teams.length;i++) {
    		    if(document.administration_form.player_team_post.value == teams[i].getElementsByTagName('team_id')[0].firstChild.data)
                    var selected = true;
                else
                    var selected = false;
 		        newOption = new Option(teams[i].getElementsByTagName('team_name')[0].firstChild.data + ' (' + teams[i].getElementsByTagName('team_nationality')[0].firstChild.data + ')', teams[i].getElementsByTagName('team_id')[0].firstChild.data, false, selected);
 		        document.administration_form.player_team.options[document.administration_form.player_team.length] = newOption;
		    }
		}

		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});
}

