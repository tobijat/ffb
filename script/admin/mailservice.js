var _game_id = 0;
var _choosen_game_id = 0;
var _matchround_id = 0;
var _userlist = new Array();
var _flaglist = new Array();

function initMailservice() {
	retrieveGames();
}

function changeChoosenGame() {
	var target = document.getElementById('ms_search_desired_game');
	var index = target.selectedIndex;
	var choosen_game_id = parseInt(target.options[target.selectedIndex].value);
	_choosen_game_id = choosen_game_id;
	if(index == 0) {
		document.getElementById('ms_search_select_game').disabled = false;
		if(document.getElementById('ms_search_select_matchround')) {
			document.getElementById('ms_search_select_matchround').disabled = false;
		}
	} else {
		document.getElementById('ms_search_select_game').disabled = true;
		if(document.getElementById('ms_search_select_matchround')) {
			document.getElementById('ms_search_select_matchround').disabled = true;
		}
	}
}

function changeGame() {
	var target = document.getElementById('ms_search_select_game');
	var game_id = parseInt(target.options[target.selectedIndex].value);
	_game_id = game_id;
	if(parseInt(game_id) != 0) {
		retrieveMatchrounds(game_id);
	} else {
		_matchround_id = 0;
		dropLineW3('ms_search_matchround', '');
	}
}

function changeMatchround() {
	var target = document.getElementById('ms_search_select_matchround');
	var matchround_id = parseInt(target.options[target.selectedIndex].value);
	_matchround_id = matchround_id;
}

function retrieveGames() {
	var url = server + 'administration/mailservice/getGameList.xml';
    new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
        if(xmlResponse.getElementsByTagName('numResults')[0].firstChild.nodeValue>0) {
 			var games = xmlResponse.getElementsByTagName('XML_Serializer_Tag');

 			//for game selection
 			var string = '';
 			string += '<select onchange="javascript:changeGame();" id="ms_search_select_game" style="width:100px;">';
 			string += '<option value="0">select Game..</option>';
 			for(var i=0;i<games.length;i++) {
 				string += '<option value="'+games[i].getElementsByTagName('game_id')[0].firstChild.nodeValue+'">'+games[i].getElementsByTagName('game_title')[0].firstChild.nodeValue+'</option>';
 			}
 			string += '</select>';
 			string += ' Game';
 			dropLineW3('ms_search_game', string);

 			//for choosen game selection
 			var string = '';
 			string += '<select onchange="javascript:changeChoosenGame();" id="ms_search_desired_game" style="width:100px;">';
 			string += '<option value="0">select Game..</option>';
 			for(var i=0;i<games.length;i++) {
 				string += '<option value="'+games[i].getElementsByTagName('game_id')[0].firstChild.nodeValue+'">'+games[i].getElementsByTagName('game_title')[0].firstChild.nodeValue+'</option>';
 			}
 			string += '</select>';
 			string += ' selected Game';
 			dropLineW3('ms_search_choosen_game', string);
        }
	},
	    onFailure : function(response) {
   	    alert("Oops, there's been an error.");
		}
    });
}

function retrieveMatchrounds(game_id) {
	var url = server + 'administration/mailservice/getMatchroundList.xml';
    new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
        if(xmlResponse.getElementsByTagName('numResults')[0].firstChild.nodeValue>0) {
 			var mrs = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 			var string = '';
 			string += '<select onchange="javascript:changeMatchround();" id="ms_search_select_matchround" style="width:100px;">';
 			string += '<option value="0">select Matchround..</option>';
 			for(var i=0;i<mrs.length;i++) {
 				string += '<option value="'+mrs[i].getElementsByTagName('matchround_id')[0].firstChild.nodeValue+'">'+mrs[i].getElementsByTagName('matchround_title')[0].firstChild.nodeValue+'</option>';
			}
 			string += '</select>';
 			string += ' Matchround';
 			dropLineW3('ms_search_matchround', string);
        }
	},
		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?game_id='+game_id
	});
}

function retrieveUsers() {
	clearUserlist();
	var target = document.getElementById('ms_search_select_userstatus');
	var userstatus = target.options[target.selectedIndex].value;
	var target = document.getElementById('ms_search_select_mstype');
	var mailservice = target.options[target.selectedIndex].value;
	if(_game_id == 0) {
		game_id = '';
	} else {
		game_id = _game_id;
	}
	if(_matchround_id == 0) {
		matchround_id = '';
	} else {
		matchround_id = _matchround_id;
	}

	var params = '?game_id='+game_id+'&choosen_game_id='+_choosen_game_id+'&matchround_id='+matchround_id+'&userstatus='+userstatus+'&mailservice='+mailservice;
	var url = server + 'administration/mailservice/getUserList.xml';
    new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
 		loadUserList(xmlResponse);
	},
		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: params
	});
}

function clearUserlist() {
	for(var i=0; i<_userlist.length; i++) {
		_userlist[i]['display'] = 0;
	}
}

function loadUserList(xmlResponse){
	if(xmlResponse.getElementsByTagName('numResults')[0].firstChild.nodeValue>0) {
 		var users = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		var index = _userlist.length;
 		var j = 0;
 		var user_id = 0;
		for(var i=0;i<users.length;i++) {
			user_id = users[i].getElementsByTagName('user_id')[0].firstChild.nodeValue;
			if(!_flaglist[user_id]) {
	 			_userlist[index+j] = new Object();
	 			_userlist[index+j]['user_id'] = user_id;
	 			_userlist[index+j]['user_nickname'] = users[i].getElementsByTagName('user_nickname')[0].firstChild.nodeValue;
	 			_userlist[index+j]['user_email'] = users[i].getElementsByTagName('user_email')[0].firstChild.nodeValue;
	 			_userlist[index+j]['display'] = 1;
	 			_userlist[index+j]['send'] = 0;
	 			_flaglist[user_id] = new Object();
	 			_flaglist[user_id]['index'] = (index+j);
	 			j++;
 			} else {
 				if(_userlist[_flaglist[user_id]['index']]['send'] == 0) {
 					_userlist[_flaglist[user_id]['index']]['display'] = 1;
 				}
 			}
 		}
 		updateDisplay();
    } else {
       	updateDisplay();
    }
}

function loadMail(mail_id){
	clearUserlist();
	var params = '?mail_id='+mail_id;
	var url = server + 'administration/mailservice/getMailById.xml';
    new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
 		var target = document.getElementById('ms_input_subject');
		target.value = xmlResponse.getElementsByTagName('mail_subject')[0].firstChild.nodeValue;
		target = document.getElementById('ms_input_text');
		target.value = xmlResponse.getElementsByTagName('mail_text')[0].firstChild.nodeValue;
		target = document.getElementById('ms_select_mailtype');
		for(var i=0;i<target.options.length;i++) {
			if(target.options[i].value == xmlResponse.getElementsByTagName('mail_criteria')[0].firstChild.nodeValue) {
				target.options[i].selected = true;
			}
		}
 		loadUserList(xmlResponse);
	},
		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: params
	});
}

function updateDisplay() {
	var u_string = '';
	var a_string = '';
	var u_string_title = '';
	var a_string_title = '';
	var u_count = 0;
	var a_count = 0;
	for(var i=0;i<_userlist.length;i++) {
		if(_userlist[i]['send'] == 1) {
			a_count++;
			a_string += '<a title="'+_userlist[i]['user_email']+'" href="javascript:remUser('+i+');">' + _userlist[i]['user_nickname'] + '</a>; ';
		}
		if(_userlist[i]['display'] == 1) {
			u_count++;
			u_string += '<a title="'+_userlist[i]['user_email']+'" href="javascript:addUser('+i+');">' + _userlist[i]['user_nickname'] + '</a><br>';
		}
	}
	a_string_title += '<a title="Remove all Users" href="javascript:remAllUsers();"><img border="0" src="'+server+symbolImages_+'status_neg.png"></a>';
	a_string_title += '&ensp;To ('+a_count+'): ';
	u_string_title += '<b>'+u_count+' Available Users</b>';
	u_string_title += '&ensp;<a title="Add all Users" href="javascript:addAllUsers();"><img border="0" src="'+server+symbolImages_+'status_pos.png"></a><br>';

	u_string = u_string_title+u_string;
	a_string = a_string_title+a_string;
	dropLineW3('ms_mail_to', a_string);
	dropLineW3('ms_search_userlist', u_string);
}

function addAllUsers() {
	for(var i=0;i<_userlist.length;i++) {
		if(_userlist[i]['display'] == 1) {
			_userlist[i]['display'] = 0;
			_userlist[i]['send'] = 1;
		}
	}
	updateDisplay();
}
function remAllUsers() {
	for(var i=0;i<_userlist.length;i++) {
		if(_userlist[i]['send'] == 1) {
			_userlist[i]['display'] = 1;
			_userlist[i]['send'] = 0;
		}
	}
	updateDisplay();
}
function addUser(index) {
	_userlist[index]['display'] = 0;
	_userlist[index]['send'] = 1;
	updateDisplay();
}
function remUser(index) {
	_userlist[index]['display'] = 1;
	_userlist[index]['send'] = 0;
	updateDisplay();
}

function checkMailSend() {
	var user_ids = '';
	var count = 0;
	for(var i=0; i<_userlist.length; i++) {
		if(_userlist[i]['send'] == 1) {
			if(count == 0) {
				user_ids += _userlist[i]['user_id'];
			} else {
				user_ids += ','+_userlist[i]['user_id'];
			}
			count++;
		}
	}
	var target = document.getElementById('ms_input_subject');
	var subject = target.value;
	target = document.getElementById('ms_input_text');
	var text = target.value;
	target = document.getElementById('ms_select_mailtype');
	var type = target.options[target.selectedIndex].value;

	if(!subject || !text) {
		alert('You have to provide Subject and Text!');
		return;
	}
	if(!user_ids) {
		alert('You have to add Users to the Addresslist!');
		return;
	}
	if(confirm('You are going to send this Email to '+count+' Users. Really?')) {
		//sendMail(user_ids, escape(subject), escape(text), type);
		sendMail(user_ids, subject, text, type);
		return;
	} else {
		return;
	}
}

function sendMail(user_ids, subject, text, type) {
	var params = '?user_ids='+user_ids+'&subject='+subject+'&text='+text+'&type='+type;
	var url = server + 'administration/mailservice/sendMail.xml';
    new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
 		alert(response.responseText);
 		var string = '';
 		var status = xmlResponse.getElementsByTagName('status')[0].firstChild.nodeValue;
 		if(status == 500) {
 			string += '<div id="formerror">';
 			string += xmlResponse.getElementsByTagName('answer')[0].firstChild.nodeValue;
 			string += '</div>';
 		} else if(status == 200) {
 			string += '<div id="formanswer">';
 			string += xmlResponse.getElementsByTagName('answer')[0].firstChild.nodeValue;
 			string += '</div>';
 		}
 		dropLineW3('ms_answers', string);

	},
		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: params
	});
}
