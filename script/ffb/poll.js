function loadPolls() {
    var url = server + 'ffb/poll/getPolls.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
 		var text_poll = xmlResponse.getElementsByTagName('text_poll');

 		if(text_poll[0].firstChild.data == '0') {
 			initNews();
 		} else {
 			dispTextPollString(text_poll);
 		}

 		var select_poll = xmlResponse.getElementsByTagName('select_poll');
 		if(parseInt(select_poll[0].firstChild.data) == 0) {
			dropLineW3('rounddiv_selectpoll', '');
 		} else {
 			if(parseInt(select_poll[0].getElementsByTagName('poll_answers')[0].firstChild.data) == 0) {
 				dispSelectPollResult(select_poll);
 			} else {
 				dispSelectPollString(select_poll);
 			}
 		}

		},

		onFailure : function(response) {
    		handleAjaxError();
 		}
	});
}

function dispTextPollString(poll) {
	var answers = poll[0].getElementsByTagName('poll_answers')[0].getElementsByTagName('XML_Serializer_Tag');
	var poll_answer_id = answers[0].getElementsByTagName('poll_answer_id')[0].firstChild.data;
	var poll_id = poll[0].getElementsByTagName('poll_id')[0].firstChild.data;
	var string = '';
	string += '<div id="poll_main">';
	string += '<div id="poll_line">';
	string += '<div id="poll_textfield">';
	string += '<ul><li><em>' + answers[0].getElementsByTagName('poll_answer_title')[0].firstChild.data + '</em><br>';
	string += '<textarea cols="30" rows="6" name="poll_text" id="poll_text_answer"></textarea></li></ul></div>';
	string += '<div id="poll_button"><input type="button" id="poll_text_button" onClick="javascript:sendAnswer('+poll_id+','+poll_answer_id+');" name="send_poll_text_answer" value="Abschicken"></div>';
	string += '</div>';
	string += '</div>';

	var title = '<b>Umfrage: </b>' + poll[0].getElementsByTagName('poll_title')[0].firstChild.data;
	dropLineW3('news_items_title', title);
	dropLineW3('news_items', string);
}

function dispSelectPollString(poll) {
	//alert('poll');
	var answers = poll[0].getElementsByTagName('poll_answers')[0].getElementsByTagName('XML_Serializer_Tag');
	var poll_id = poll[0].getElementsByTagName('poll_id')[0].firstChild.data;
	var poll_title = poll[0].getElementsByTagName('poll_title')[0].firstChild.data;
	var poll_type = poll[0].getElementsByTagName('poll_type')[0].firstChild.data;
	var string = '';
	string += '<div class="roundcorner_light">';
	string += '<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>';
	string += '<div id="select_poll">';
	string += '<div id="poll_title">';
	string += '<b><u>'+poll_title+'</u></b>';
	string += '</div>';
	for(var i=0;i<answers.length;i++) {
		var poll_answer_id = answers[i].getElementsByTagName('poll_answer_id')[0].firstChild.data;
		var poll_answer = answers[i].getElementsByTagName('poll_answer_title')[0].firstChild.data;
		string += '<a title="Diese Antwort absenden" class="nolink" href="javascript:void(0);" onclick="javascript:sendSelectAnswer('+poll_id+','+poll_answer_id+');">';
		string += '<div id="poll_line">';
		string += '<div id="poll_radio"><img border="0" src="'+server+symbolImages_+'symbol_bullet_p.png"></div>';
		string += '<div id="poll_radio_description">' + poll_answer + '</div>';
		string += '<div style="clear:both;"></div>';
		string += '</div>';
		string += '</a>';
	}
	string += '<div id="poll_info">';
	string += 'Umfrage l&auml;uft bis ';
	string += poll[0].getElementsByTagName('poll_end')[0].firstChild.data;
	string += '</div>';

	string += '<div id="poll_info">';
	if(parseInt(poll[0].getElementsByTagName('poll_prev_poll_id')[0].firstChild.data) != 0) {
		string += '<a href="javascript:void(0);" onclick="javascript:changePoll(';
		string += poll[0].getElementsByTagName('poll_prev_poll_id')[0].firstChild.data;
		string += ');">vorherige</a>';
	} else {
		string += 'vorherige';
	}
	string += '&ensp;';
	if(parseInt(poll[0].getElementsByTagName('poll_next_poll_id')[0].firstChild.data) != 0) {
		string += '<a href="javascript:void(0);" onclick="javascript:changePoll(';
		string += poll[0].getElementsByTagName('poll_next_poll_id')[0].firstChild.data;
		string += ');">n&auml;chste</a>';
	} else {
		string += 'n&auml;chste';
	}
	string += '</div>';

	string += '</div>';
	string += '<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>';
	string += '</div>';

	dropLineW3('rounddiv_selectpoll', string);
	return;
}

function dispSelectPollResult(poll) {
	var answers = poll[0].getElementsByTagName('poll_result')[0].getElementsByTagName('XML_Serializer_Tag');
	var poll_id = poll[0].getElementsByTagName('poll_id')[0].firstChild.data;
	var poll_title = poll[0].getElementsByTagName('poll_title')[0].firstChild.data;
	var string = '';
	string += '<div class="roundcorner_light">';
	string += '<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>';
	string += '<div id="select_poll">';
	string += '<div id="poll_title">';
	string += '<b><u>'+poll_title+'</u></b>';
	string += '</div>';
	for(var i=0;i<answers.length;i++) {
		var poll_answer_id = answers[i].getElementsByTagName('poll_answer_id')[0].firstChild.data;
		var poll_answer = answers[i].getElementsByTagName('poll_answer_title')[0].firstChild.data;
		var poll_answer_width = answers[i].getElementsByTagName('poll_answer_percent_round')[0].firstChild.data;
		var poll_answer_percent = answers[i].getElementsByTagName('poll_answer_percent')[0].firstChild.data;
		string += '<div id="poll_answer_line" style="width:'+poll_answer_width+'%;">';
		//string += '<div id="poll_radio"><img border="0" src="'+server+symbolImages_+'symbol_bullet_p.png"></div>';
		string += '<span style="padding-left:2px;">'+poll_answer_percent+'%&ensp;'+poll_answer+'</span>';
		//string += '<div style="clear:both;"></div>';
		string += '</div>';
	}
	if(parseInt(poll[0].getElementsByTagName('poll_over')[0].firstChild.data) == 1) {
		string += '<div id="poll_info">';
		string += 'Umfrage beendet. Teilnehmer: ';
		string += poll[0].getElementsByTagName('poll_num_answers')[0].firstChild.data;
		string += '</div>';
	} else {
		string += '<div id="poll_info">';
		string += 'Umfrage l&auml;uft bis ';
		string += poll[0].getElementsByTagName('poll_end')[0].firstChild.data;
		string += '</div>';
	}
	string += '<div id="poll_info">';
	if(parseInt(poll[0].getElementsByTagName('poll_prev_poll_id')[0].firstChild.data) != 0) {
		string += '<a href="javascript:void(0);" onclick="javascript:changePoll(';
		string += poll[0].getElementsByTagName('poll_prev_poll_id')[0].firstChild.data;
		string += ');">vorherige</a>';
	} else {
		string += 'vorherige';
	}
	string += '&ensp;';
	if(parseInt(poll[0].getElementsByTagName('poll_next_poll_id')[0].firstChild.data) != 0) {
		string += '<a href="javascript:void(0);" onclick="javascript:changePoll(';
		string += poll[0].getElementsByTagName('poll_next_poll_id')[0].firstChild.data;
		string += ');">n&auml;chste</a>';
	} else {
		string += 'n&auml;chste';
	}
	string += '</div>';

	string += '</div>';
	string += '<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>';
	string += '</div>';

	dropLineW3('rounddiv_selectpoll', string);
	return;
}

function changePoll(poll_id) {
	dispSelectPollWaiting();
	var url = server + 'ffb/poll/getSelectPollById.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);

 		var select_poll = xmlResponse.getElementsByTagName('select_poll');
 		if(parseInt(select_poll[0].firstChild.data) == 0) {
			dropLineW3('rounddiv_selectpoll', '');
 		} else {
 			if(parseInt(select_poll[0].getElementsByTagName('poll_answers')[0].firstChild.data) == 0) {
 				dispSelectPollResult(select_poll);
 			} else {
 				dispSelectPollString(select_poll);
 			}
 		}

		},

		onFailure : function(response) {
    		handleAjaxError();
 		},
 		parameters: '?poll_id=' + poll_id
	});
}

function sendSelectAnswer(poll_id, poll_answer_id) {
	//alert(poll_id+'/'+poll_answer_id);
	dispSelectPollWaiting();
	var url = server + 'ffb/poll/savePollSelectAnswer.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
 		loadStart(1,0);

		},

		onFailure : function(response) {
    		handleAjaxError();
 		},
 		parameters: '?poll_id=' + poll_id + '&poll_answer_id=' + poll_answer_id
	});
}

function sendAnswer(poll_id, poll_answer_id) {
	//alert(poll_id+'/'+poll_answer_id);
	var button = document.getElementById('poll_text_button');
	button.disabled = true;
	var textarea = document.getElementById('poll_text_answer');
	var poll_answer = textarea.value;

	var url = server + 'ffb/poll/savePollTextAnswer.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
 		loadStart();

		},

		onFailure : function(response) {
    		handleAjaxError();
 		},
 		parameters: '?poll_id=' + poll_id + '&poll_answer_id=' + poll_answer_id + '&poll_answer=' + poll_answer
	});
}

function dispSelectPollWaiting() {
	var string = '';
	string += '<div style="width:100%; text-align:center;">'+MEDIUM_LOAD+'</div>';
	//string += '&ensp;Laden...';
	dropLineW3('rounddiv_selectpoll', string);
}