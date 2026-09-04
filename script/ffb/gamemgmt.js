var _act_game_id = 0;
var _disp_archive = 0;

function dispArchiveGames(archive, admin){
	_disp_archive = archive;
	checkSelectedGame(admin);
}

function checkSelectedGame(admin){
    if(admin) {
    	var url = server + 'administration/game/checkSelectedGame.xml';
    } else {
    	var url = server + 'ffb/game/checkSelectedGame.xml';
    }
    dispCheckGameLoading('Verf&uuml;gbare Spiele laden...<br><br>');
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		    var xmlResponse=response.responseXML;
 		    //alert(response.responseText);
 		    var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.nodeValue;
 		    var game_id = xmlResponse.getElementsByTagName('selected_game_id')[0].firstChild.nodeValue;
 		    if(status == 200) {
 		    	_act_game_id = game_id;
 		    	dispGameSelection(admin);
            } else {
                dropLineW3('gm_selected_game', xmlResponse.getElementsByTagName('administration_error')[0].firstChild.nodeValue);
            }
		},
		onFailure : function(response) {
    		alert("Oops, there's been an error.");
 		}
	});
}

function dispCheckGameLoading(str){
	var text = '<div class="gm_game_list_main">';
 	text += '<div class="gm_game_list_title">';
 	text += str;
 	//text += '<img src=' + server + images_ + 'loading/ajax-loader-in-progress.gif height="10px">';
 	text += MEDIUM_LOAD;
 	text += '</div>';
 	dropLineW3('gm_game_list', text);
 	return;
}

function dispGameSelection(admin) {
    if(admin) {
        var url = server + 'administration/game/getGamesForAdmin.xml';
        var max_games_per_row = 3;
    } else {
    	if(_disp_archive == 1) {
    		var url = server + 'ffb/game/getPastGames.xml';
    	} else {
        	var url = server + 'ffb/game/getGameList.xml';
        }
        var max_games_per_row = 3;
    }
    new Ajax.Request(url, {
 		onSuccess : function(response) {
 		    //alert(response.responseText);
 		    var xmlResponse=response.responseXML;
 		    var numResults = xmlResponse.getElementsByTagName('num_results')[0].firstChild.nodeValue;
 		    if(numResults>0) {
 		        var games = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		        var text = '<div class="gm_game_list_main">';
 		        text += '<div class="gm_game_list_title">';
 		        if(_act_game_id == 0) {
 		        	text += 'Du hast noch kein Spiel ausgew&auml;hlt!';
 		        	text += '<br><span style="font-size:8pt;">Klick ein Spiel an, um es auszuw&auml;hlen.</span>';
 		        } else {
 		        	text += 'Verf&uuml;gbare Spiele';
 		        	if(_disp_archive == 1 && !admin) {
 		        		text += '<br><span style="font-size:9pt; font-weight:none; font-family:Verdana Sans Serif;"><a href="javascript:void(0);" onClick="javascript:dispArchiveGames(0,'+admin+');">';
 		        		text += '(zu aktuellen Spielen wechseln)</a></span>';
 		        	} else if(_disp_archive == 0 && !admin) {
 		        		text += '<br><span style="font-size:9pt; font-weight:none; font-family:Verdana Sans Serif;"><a href="javascript:void(0);" onClick="javascript:dispArchiveGames(1,'+admin+');">';
 		        		text += '(zu vergangenen Spielen wechseln)</a></span>';
 		        	}
 		        	text += '<br><span style="font-size:8pt; font-weight:none;">Das rot hinterlegte Spiel ist ausgew&auml;hlt.</span>';
 		        }
 		        text += '</div>';
 		        var div_id = '';
 		        var j = 0;
 		        for(var i=0;i<games.length;i++) {
 		        	var game_id = games[i].getElementsByTagName('game_id')[0].firstChild.nodeValue;
 		        	var game_title = games[i].getElementsByTagName('game_title')[0].firstChild.nodeValue;
 		        	var game_symbol = server + symbolImages_ + games[i].getElementsByTagName('game_symbol')[0].firstChild.nodeValue;
 		        	var game_archive = games[i].getElementsByTagName('game_archive')[0].firstChild.nodeValue;
 		        	var game_visible = games[i].getElementsByTagName('game_visible')[0].firstChild.nodeValue;
 		        	var selected = '';
 		        	var select_style = '';
 		        	var div_class = 'class="roundcorner_light"';
 		        	//if((game_visible==1 && game_archive==0) || admin) {
 		        		if(_act_game_id == game_id) {
 		        			select_style = ' style="background-color:#FF0000;"';
 		        			div_class = 'class="roundcorner_red"';
 		        		}
 		        		text += '<div class="rounddiv_gm_game_list_element" style="min-height:0px;">';
						text += '<div ' + div_class + '>';
						text += '<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>';

						if(_act_game_id != game_id) {
	 		        		text += '<a title="Spiel anklicken um es auszuw&auml;hlen" class="nolink" href="javascript:void(0);" onclick="javascript:setSelectedGame('+admin+','+game_id+');">';
						}
						text += '<div class="gm_game_list_element"'+select_style+'>';
						text += '<div class="gm_game_list_element_image"><img src="' + game_symbol + '" height="100px" border="0"></div>';
						text += '<div class="gm_game_list_element_text">' + game_title + selected + '</div>';
						text += '</div>';
						if(_act_game_id != game_id) {
							text += '</a>';
						}

						text += '<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>';
						text += '</div>';
						text += '</div>';

	 		        	if(j%max_games_per_row == (max_games_per_row-1)) {
	 		        		text += '<div style="clear:both; height:5px;"></div>';
	 		        	}
	 		        	j++;
 		        	//}
 		        }
 		        text += '<div style="clear:both;"></div>';
 		        text += '</div>';
	        	dropLineW3('gm_game_list', text);
            } else {
                dropLineW3('gm_selected_game', 'Keine Ligen vorhanden!');
            }
		},
		onFailure : function(response) {
    		alert("Oops, there's been an error.");
 		}
	});
}

function setSelectedGame(admin, game_id){
	dispCheckGameLoading('Spiel ausw&auml;hlen...<br><br>');
	if(admin) {
		var url = server + 'administration/game/setSelectedGame.xml';
	} else {
		var url = server + 'ffb/game/setSelectedGame.xml';
	}
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		    var xmlResponse=response.responseXML;
 		    //alert(response.responseText);
 		    var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.nodeValue;
 		    if(status == 200) {
 		    	_act_game_id = game_id;
 		    	dispGameSelection(admin);
 		    	if(!admin) {
					//initNews();
					loadPolls();
				}
            } else {
                dropLineW3('gm_selected_game', xmlResponse.getElementsByTagName('administration_error')[0].firstChild.nodeValue);
            }
		},
		onFailure : function(response) {
    		alert("Oops, there's been an error.");
 		},
		parameters: '?game_id='+game_id
	});
}

