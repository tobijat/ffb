var _userprofile_data = new Array();
var _participation_data = new Array();

function dispUserinfoHead() {
    var string = '';
    //string += '<div id="infobox_name">';
    string += '<div id="infobox_title_image">';
    string += '<img src="' + _userprofile_data['user_details_avatar'] + '" width="40px"> ';
    string += '</div>';
    string += '<div id="infobox_title_text">';
    string += _userprofile_data['user_nickname'];
    string += '</div>';
    string += '<div id="infobox_close">';
    string += '<a title="Schlie&szlig;en" href="javascript:void(0);" onClick="javascript:closeInfoPopup();"><img alt="Schlie&szlig;en" border="0" src="' + server + symbolImages_ + 'delete.png"></a>';
    string += '</div>';
    string += '<div id="listclear"></div>';

    return string;
}

function dispUserinfoTabs() {
    var string = '';
    string += '<div id="infobox_name">';
    string += '<a href="javascript:void(0);" onClick="javascript:dispUserinfoPopup('+_userprofile_data['user_id']+');">Profil</a>';
    //string += '&nbsp;<a href="javascript:void(0);" onClick="javascript:dispUserstatsPopup('+_userprofile_data['user_id']+');">Statistik</a>';
    string += '&nbsp;<a href="javascript:void(0);" onClick="javascript:dispUserawardPopup('+_userprofile_data['user_id']+');">Auszeichnungen</a>';

    string += '</div>';
    string += '<div id="infobox_close"></div>';
    string += '<div id="listclear"></div>';

    return string;
}

function dispUserinfoPopup(user_id) {
	_userprofile_data.clear();
	_participation_data.clear();
	dispPopupWaiting();
    retrieveUserinfo(user_id);
}

function dispUserawardPopup(user_id) {
	dispPopupWaiting();
    dispUserawards_v2(user_id);
}

function retrieveUserinfo(user_id){
    var params = '?user_id=' + user_id;
    var url = server + 'ffb/user/getUserDetails.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
        var xmlResponse=response.responseXML;

		//alert(response.responseText);

        var userinfo = xmlResponse.getElementsByTagName('user');
        _userprofile_data['user_id'] = userinfo[0].getElementsByTagName('user_id')[0].firstChild.nodeValue;
        _userprofile_data['user_ownprofile'] = userinfo[0].getElementsByTagName('user_ownprofile')[0].firstChild.nodeValue;
        _userprofile_data['user_nickname'] = userinfo[0].getElementsByTagName('user_nickname')[0].firstChild.nodeValue;
        _userprofile_data['user_last_activity'] = userinfo[0].getElementsByTagName('user_date_llogin')[0].firstChild.nodeValue;
        _userprofile_data['user_member_since'] = userinfo[0].getElementsByTagName('user_date_register')[0].firstChild.nodeValue;
        _userprofile_data['user_details_avatar'] = userinfo[0].getElementsByTagName('user_details_avatar')[0].firstChild.nodeValue;
        _userprofile_data['user_details_photo'] = userinfo[0].getElementsByTagName('user_details_photo')[0].firstChild.nodeValue;
        _userprofile_data['user_details_favteam_id'] = userinfo[0].getElementsByTagName('user_details_favteam_id')[0].firstChild.nodeValue;
        _userprofile_data['user_details_favteam_name'] = userinfo[0].getElementsByTagName('user_details_favteam_name')[0].firstChild.nodeValue;
        _userprofile_data['user_details_favteam_nationality'] = userinfo[0].getElementsByTagName('user_details_favteam_nationality')[0].firstChild.nodeValue;
        _userprofile_data['user_details_ownteam_id'] = userinfo[0].getElementsByTagName('user_details_ownteam_id')[0].firstChild.nodeValue;
        _userprofile_data['user_details_ownteam_name'] = userinfo[0].getElementsByTagName('user_details_ownteam_name')[0].firstChild.nodeValue;
        _userprofile_data['user_details_ownteam_nationality'] = userinfo[0].getElementsByTagName('user_details_ownteam_nationality')[0].firstChild.nodeValue;
        _userprofile_data['user_name'] = userinfo[0].getElementsByTagName('user_fname')[0].firstChild.nodeValue + ' ' + userinfo[0].getElementsByTagName('user_lname')[0].firstChild.nodeValue;
        _userprofile_data['user_lname'] = userinfo[0].getElementsByTagName('user_lname')[0].firstChild.nodeValue;
        _userprofile_data['user_fname'] = userinfo[0].getElementsByTagName('user_fname')[0].firstChild.nodeValue;
        _userprofile_data['user_gender'] = userinfo[0].getElementsByTagName('user_gender')[0].firstChild.nodeValue;
		_userprofile_data['user_details_zip'] = userinfo[0].getElementsByTagName('user_details_zip')[0].firstChild.nodeValue;
		_userprofile_data['user_details_city'] = userinfo[0].getElementsByTagName('user_details_city')[0].firstChild.nodeValue;
		_userprofile_data['user_details_street'] = userinfo[0].getElementsByTagName('user_details_street')[0].firstChild.nodeValue;
		_userprofile_data['user_details_phone'] = userinfo[0].getElementsByTagName('user_details_phone')[0].firstChild.nodeValue;
		_userprofile_data['user_details_website'] = userinfo[0].getElementsByTagName('user_details_website')[0].firstChild.nodeValue;
		_userprofile_data['user_perm_profile'] = userinfo[0].getElementsByTagName('user_perm_profile')[0].firstChild.nodeValue;

		var participations = xmlResponse.getElementsByTagName('participations')[0].getElementsByTagName('XML_Serializer_Tag');
		//alert(participations.length);
		for(var i=0;i<participations.length;i++) {
			_participation_data[i] = new Object();
        	_participation_data[i]['game_id'] = participations[i].getElementsByTagName('game_id')[0].firstChild.nodeValue;
        	_participation_data[i]['game_title'] = participations[i].getElementsByTagName('game_title')[0].firstChild.nodeValue;
        	_participation_data[i]['game_symbol'] = participations[i].getElementsByTagName('game_symbol')[0].firstChild.nodeValue;
        	_participation_data[i]['score_rm'] = participations[i].getElementsByTagName('score_rm')[0].firstChild.nodeValue;
        	_participation_data[i]['score_wc'] = participations[i].getElementsByTagName('score_wc')[0].firstChild.nodeValue;
        	_participation_data[i]['score_points'] = participations[i].getElementsByTagName('score_points')[0].firstChild.nodeValue;
        	_participation_data[i]['score_start'] = participations[i].getElementsByTagName('score_start')[0].firstChild.nodeValue;
        	_participation_data[i]['score_end'] = participations[i].getElementsByTagName('score_end')[0].firstChild.nodeValue;
        	_participation_data[i]['user_rank'] = participations[i].getElementsByTagName('user_rank')[0].firstChild.nodeValue;
        }

		dispUserInfo();

        },
		onFailure : function(response) {
    	alert('error');
 		},
 		parameters: params
	});
}

function dispUserInfo() {
	var string = '<div id="statsname">';
    string += dispUserinfoHead();
    string += dispUserinfoTabs() + '</div>';
    string += '<div id="infomain">';

    string += '<div id="infopic">';
    string += '<img src="' + _userprofile_data['user_details_photo'] + '" width="100px" style="border:solid red 1px">';
    string += '</div>';

    string += '<div id="infotext">';
	if(parseInt(_userprofile_data['user_perm_profile']) == 1) {
		if(parseInt(_userprofile_data['user_lname']) !=0 || parseInt(_userprofile_data['user_fname']) != 0) {
			string += '<div id="infoline">';
		    string += '<div id="infosymbol">';
		    string += '<img src="' + server + symbolImages_ + 'symbol_profile.png" width="16px" height="16px">';
		    string += '</div>';
		    string += '<div id="infodescr">';
		    string += 'Name:&nbsp;';
		    string += '</div>';
		    string += '<div id="infoamount"><b>';
		    string += _userprofile_data['user_name'];
		    string += '</b></div>';
		    string += '<div style="clear:both;"></div>';
		    string += '</div>';
	    }
	}
	if(parseInt(_userprofile_data['user_details_city']) != 0) {
		string += '<div id="infoline">';
	    string += '<div id="infosymbol">';
	    string += '<img src="' + server + symbolImages_ + 'symbol_home.png" width="16px" height="16px">';
	    string += '</div>';
	    string += '<div id="infodescr">';
	    string += 'kommt aus:&nbsp;';
	    string += '</div>';
	    string += '<div id="infoamount"><b>';
	    string += _userprofile_data['user_details_city'];
	    string += '</b></div>';
	    string += '<div style="clear:both;"></div>';
	    string += '</div>';
	}
	if(parseInt(_userprofile_data['user_details_website']) != 0) {
		string += '<div id="infoline">';
	    string += '<div id="infosymbol">';
	    string += '<img src="' + server + symbolImages_ + 'symbol_globe.png" width="16px" height="16px">';
	    string += '</div>';
	    string += '<div id="infodescr" style="overflow:hidden;">';
	    string += 'Website:&nbsp;';
	    string += '</div>';
	    if(_userprofile_data['user_details_website'].length > 23) {
	    	var ws = 'klicken';
	    } else {
	    	var ws = _userprofile_data['user_details_website'];
	    }
	    string += '<div id="infoamount"><b><a title="Zur Website gehen" class="nolink" target="_blank" href="';
	    string += _userprofile_data['user_details_website'];
	    string += '">';
	    //string += _userprofile_data['user_details_website'];
	    string += ws;
	    string += '</a></b></div>';
	    string += '<div style="clear:both;"></div>';
	    string += '</div>';
	}
	if(parseInt(_userprofile_data['user_perm_profile']) == 1) {
		if(parseInt(_userprofile_data['user_details_phone']) != 0) {
			string += '<div id="infoline">';
		    string += '<div id="infosymbol">';
		    string += '<img src="' + server + symbolImages_ + 'symbol_phone.png" width="16px" height="16px">';
		    string += '</div>';
		    string += '<div id="infodescr">';
		    string += 'Telefon:&nbsp;';
		    string += '</div>';
		    string += '<div id="infoamount"><b>';
		    string += _userprofile_data['user_details_phone'];
		    string += '</b></div>';
		    string += '<div style="clear:both;"></div>';
		    string += '</div>';
	    }
	}
	string += '<div id="infoline">';
    string += '<div id="infosymbol">';
    string += '<img src="' + server + symbolImages_ + 'calendar.png" width="16px" height="16px">';
    string += '</div>';
    string += '<div id="infodescr">';
    string += 'Mitglied seit:&nbsp;';
    string += '</div>';
    string += '<div id="infoamount"><b>';
    string += _userprofile_data['user_member_since'];
    string += '</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';

    string += '<div id="infoline">';
    string += '<div id="infosymbol">';
    string += '<img src="' + server + symbolImages_ + 'stats_time.png" width="16px" height="16px">';
    string += '</div>';
    string += '<div id="infodescr">';
    string += 'letzte Aktivit&auml;t:&nbsp;';
    string += '</div>';
    string += '<div id="infoamount"><b>';
    string += _userprofile_data['user_last_activity'];
    string += '</b></div>';
    string += '<div style="clear:both;"></div>';
    string += '</div>';

	if(parseInt(_userprofile_data['user_details_favteam_id']) != 0) {
	    string += '<div id="infoline">';
	    string += '<div id="infosymbol">';
	    string += '<img src="' + server + symbolImages_ + 'symbol_shoes.png" width="16px" height="16px">';
	    string += '</div>';
	    string += '<div id="infodescr">';
	    string += 'Lieblingsteam:&nbsp;';
	    string += '</div>';
	    string += '<div id="infoamount"><b>';
	    string += _userprofile_data['user_details_favteam_name'];
	    string += '</b></div>';
	    string += '<div style="clear:both;"></div>';
	    string += '</div>';
    }


    string += '</div>';
    string += '<div style="clear:both;"></div>';
	string += '<hr>';
    string += '<div id="infoline" style="text-align:center; font-size:10pt;"><b>Teilnahmen</b></div>';

    string += '<div id="inforounds">';
    string += '<table class="inforounds" style="width:100%;"><thead><tr>';

	string += '<th>' + '<img src="' + server + flagImages_ + 'aut.gif" width="16px">&ensp;' + '<b>Liga</b>' + '&ensp;<img src="' + server + flagImages_ + 'aut.gif" width="16px">' + '</th>';
    string += '<th>' + '<img src="' + server + symbolImages_ + 'calendar.png" width="16px" height="16px">&ensp;' + '<b>von - bis</b>' + '&ensp;<img src="' + server + symbolImages_ + 'calendar.png" width="16px" height="16px">' + '</th>';
	//string += '<th style="min-width:50px;"><b>WeltCup</b></th>';
    string += '<th style="min-width:50px;"><b>Punkte (WC)</b></th>';
    //string += '<th style="white-space:nowrap;">' + '<img src="' + server + symbolImages_ + 'stats_point.png" width="16px" height="16px">&ensp;' + '<b>Platz</b></th>';
    string += '<th style="white-space:nowrap;"><b>Platz</b></th>';
    string += '</tr></thead><tbody>';

	//alert(_participation_data.length);
	var style = '';
    for(var i=0;i<_participation_data.length;i++) {
    	style = '';
        string += '<tr>';
        string += '<td style="text-align:left;">';
        if(parseInt(_participation_data[i]['game_symbol']) != 0) {
        	string += '<img src="' + server + symbolImages_ + _participation_data[i]['game_symbol'] + '" width="16px">&ensp;';
        }
		string += _participation_data[i]['game_title'] + '</td>';
        string += '<td>';
		string += _participation_data[i]['score_start'];
		string += ' - ';
		string += _participation_data[i]['score_end'];
		string += '</td>';
		if(_participation_data[i]['score_rm'] == 'wc') {
			//string += '<td><b>' + _participation_data[i]['score_wc'] + '</b></td>';
			string += '<td>' + _participation_data[i]['score_points'] + ' (<b>' + _participation_data[i]['score_wc'] + '</b>)</td>';
		} else {
			//string += '<td>' + _participation_data[i]['score_wc'] + '</td>';
			string += '<td><b>' + _participation_data[i]['score_points'] + '</b> (' + _participation_data[i]['score_wc'] + ')</td>';
		}

        style = '';
        if(parseInt(_participation_data[i]['user_rank']) == 1) {
        	style = ' style="background-color:#ffd700;"';
        } else if(parseInt(_participation_data[i]['user_rank']) == 2) {
        	style = ' style="background-color:#cccccc;"';
        } else if(parseInt(_participation_data[i]['user_rank']) == 3) {
        	style = ' style="background-color:#cd853f;"';
        }
        string += '<td' + style + '><b>' + _participation_data[i]['user_rank'] + '</b></td>';
        string += '</tr>';
    }

    string += '</table>';
    string += '</div>';
    string += '<div style="clear:both;"></div>';


    string += '</div>';

    displayInfoPopup(string, 520);
}

function dispUserawards_v2(userid) {
	var url = server + 'ffb/awards/getAllUserAwards.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse	=	response.responseXML;
 		//var round = xmlResponse.getElementsByTagName('matchrounds');
		//alert(response.responseText);
		var awards		=	xmlResponse.getElementsByTagName('group');
		var numGroups	=	xmlResponse.getElementsByTagName('awardGroupCount');
		numGroups		=	numGroups[0].firstChild.nodeValue;
		//var toDisplay	=	dispPlayerinfoHead();
		var string = '<div id="statsname">';
	    string += dispUserinfoHead();
	    string += dispUserinfoTabs() + '</div>';
	    string += '<div id="infomain">';
	    string += '<div id="award_line">';
		string += '<div id="award_title_symbol"><b>Auszeichnung</b></div>';
		string += '<div id="award_title_elements"><b>Rang</b></div>';
		string += '<div style="clear:both;"></div>';
		string += '</div>';
		//string += '<hr>';
		for(var i=0;i<numGroups;i++) {
			string += '<div id="award_line">';
			string += '<div id="award_symbol">';
			string += '<img title="' + awards[i].getElementsByTagName('gdescr')[0].firstChild.nodeValue	+ '" src="' + server + images_ + awards[i].getElementsByTagName('gimg')[0].firstChild.nodeValue + '" width="35px"><br>';
			string += '<em>' + awards[i].getElementsByTagName('gname')[0].firstChild.nodeValue + '</em>';
			string += '</div>';

			var awardCnt = awards[i].getElementsByTagName('awardcount')[0].firstChild.nodeValue;
			var award = awards[i].getElementsByTagName('award');
			//alert(awardCnt);
			for(var j=0;j<awardCnt;j++) {
				string += '<div id="award_element">';
				var awardImg = award[0].getElementsByTagName('img')[j].firstChild.nodeValue;
				var title = 'Ausgezeichnet mit ' + award[0].getElementsByTagName('name')[j].firstChild.nodeValue.toUpperCase() + '!';
				//alert(1);
				if(award[0].getElementsByTagName('finished')[j].firstChild.nodeValue != 1) {
					var imgEnding = awardImg.substr( (awardImg.length-4), 4);
					awardImg = awardImg.substr(0, (awardImg.length-4)) + "_disabled" + imgEnding;
					title = award[0].getElementsByTagName('name')[j].firstChild.nodeValue.toUpperCase() + ' - ' + award[0].getElementsByTagName('descr')[j].firstChild.nodeValue;
				}
	 			string += '<img title="' + title + '" src="' + server + images_ + awardImg + '" height="45px">';
				string += '</div>';
			}

			string += '<div style="clear:both;"></div>';
			string += '</div>';
			//string += '<hr>';
		}
		string += '</div>';
		displayInfoPopup(string, 520);
		},
		onFailure : function(response) {
    	alert('error retrieving matchround-details');
 		},
 		parameters: '?user_id=' + userid
	});
}

function dispUserawards(userid) {
	var url = server + 'ffb/awards/getAllUserAwards.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse	=	response.responseXML;
 		//var round = xmlResponse.getElementsByTagName('matchrounds');
		alert(response.responseText);
		var awards		=	xmlResponse.getElementsByTagName('group');
		var numGroups	=	xmlResponse.getElementsByTagName('awardGroupCount');
		numGroups		=	numGroups[0].firstChild.nodeValue;
		//var toDisplay	=	dispPlayerinfoHead();
		var toDisplay = '<div id="statsname">';
	    toDisplay += dispUserinfoHead();
	    toDisplay += dispUserinfoTabs() + '</div>';
		//var toDisplay = '';
		for(var i=0;i<numGroups;i++) {
			toDisplay	+=	'<div style="font-size:12px;"><img src="' + server + images_ +awards[i].getElementsByTagName('gimg')[0].firstChild.nodeValue +
							'" style="float:left; width:55px;">'	+
							'Titel: <b>'	+	awards[i].getElementsByTagName('gname')[0].firstChild.nodeValue	+	"</b><br/>\r\n"	+
							'Beschreibung: <b>'	+	awards[i].getElementsByTagName('gdescr')[0].firstChild.nodeValue	+	"</b><br/><br/>\r\n</div><div><ol style=\" margin-left:20px; vertical-align: bottom; border-bottom:black solid 1px;\">\r\n";

			var awardCnt=	awards[i].getElementsByTagName('awardcount')[0].firstChild.nodeValue;
			var award	=	awards[i].getElementsByTagName('award');
			for(var j=0;j<awardCnt;j++) {
			 	toDisplay	+=	'<li style="border-bottom: 1px silver dashed;margin-bottom:5px; list-style-type:none; font-size:12px;"><img src="' + server + images_;
	 			if(award[0].getElementsByTagName('finished')[j].firstChild.nodeValue==1) {
	 				toDisplay += award[0].getElementsByTagName('img')[j].firstChild.nodeValue;
				} else {
					var awardImg = award[0].getElementsByTagName('img')[j].firstChild.nodeValue;
					var imgEnding = awardImg.substr( (awardImg.length-4), 4);
					toDisplay += awardImg.substr(0, (awardImg.length-4));
					toDisplay += "_disabled" + imgEnding;
				}
				toDisplay +=	'" style="float:left;">'	+
								'Rang: <b>'	+	award[0].getElementsByTagName('name')[j].firstChild.nodeValue	+	"</b><br/>\r\n"	+
								'Beschreibung: <b>'	+	award[0].getElementsByTagName('descr')[j].firstChild.nodeValue	+	"</b><br/>\r\n"	+
								'Eledigt: <input type="checkbox" disabled style="width:12px; height:12px; font-weight:bold; color:black; background-color:#CCFFCC;" ';
				if(award[0].getElementsByTagName('finished')[j].firstChild.nodeValue==1)
					toDisplay	+=	'checked="true"';
				toDisplay	+=	'><br/></li>';
			}
			toDisplay	+=	"</ol></div>\r\n";
		}
		displayInfoPopup(toDisplay, 520);
		},
		onFailure : function(response) {
    	alert('error retrieving matchround-details');
 		},
 		parameters: '?user_id=' + userid
	});
}
