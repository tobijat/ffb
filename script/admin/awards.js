

function selectAwardGroup() {
    var url = server + 'administration/awards/getAwards.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
		 	var xmlResponse		=	response.responseXML;
 			var awardGroup 		= 	xmlResponse.getElementsByTagName('userAward');
 			var awardGroupId	=	awardGroup[0].getElementsByTagName('id')[0].firstChild.nodeValue;
 			var awardGroupName 	=	awardGroup[0].getElementsByTagName('name')[0].firstChild.nodeValue;
 			var toDisplay 		= 	'<u>Auszeichnungs Gruppe: <b>' + awardGroupName + '</b></u><br/><hr/>'									+ 
 									'<div><div class="awardEntry"><div class="awardInputSmall"><img width="64" src="'+ server + images_ +awardGroup[0].getElementsByTagName('image')[0].firstChild.nodeValue+'" /></div>'								+
			 						'<form name="awardGroupInfo"  id="awardGroupInfo">'														+
		 							'<input type="hidden" name="award_group_id" value="'+awardGroupId+'" />'								+
			 						'<div class="awardInput">Beschreibung:<br/><textarea name="award_group_description">'					+
									 awardGroup[0].getElementsByTagName('description')[0].firstChild.nodeValue								+
			 						'</textarea></div><div class="awardInputSmall"> </div><div class="awardInput">Bildpfad:<br/><input  name="award_group_image"  type="text" value="'		+
									 awardGroup[0].getElementsByTagName('image')[0].firstChild.nodeValue+'" /></div><div class="awardInputSmall"> </div><div class="awardinput">'+
			 						'<input type="button" value="update" onclick="javascript:awardGroupUpdate();" /></div></form></div></div>'			+
									 '<br/><br/><hr/> Neue Auszeichnung anlegen:<br/>' 	+
 									'<div class="awardEntry"><div class="awardInputSmall">&nbsp;&nbsp;&nbsp;Rang</div>'						+
			 						'<div class="awardInput">Titel</div>'																	+
			 						'<div class="awardInputSmall">&nbsp;&nbsp;Kriterium</div>'												+
			 						'<div class="awardDummy">&nbsp;</div><div class="awardInput">DB Table</div><div class="awardInputSmall">Operator</div>'				+
			 						'<div class="awardInputSmall">Loops</div>'																+
			 						'<div class="awardInputSmall">AutoAW</div><div class="awardInput">Optionen</div></div>'					+
			 						'<div class="awardEntry">'																				+
			 						'<div class="awardInput">Facebook Beschreibung</div>'													+
			 						'<div class="awardInput">Beschreibung</div><div class="awardInput">Bildpfad</div>'						+
			 						'</div>'																								+
			 						'<form name="newaward" id="newaward" onsubmit="javascript:createAward();"><div class="awardEntry">'		+
			 						'<input type="hidden" name="group_award_id" value="' + awardGroupId + '" />'							+
			 						'<input class="awardInputSmall" type="text" name="award_rank" 		id="award_rank" 	/>'				+
			 						'<input class="awardInput" 		type="text" name="award_name"		id="award_name" 	/>'				+			
			 						'<input class="awardInputSmall" type="text" name="award_aim" 		id="award_aim" 		/>'				+
			 						'<input class="awardInput" 		type="text" name="award_dbtable" 	id="award_dbtable" 	/>'				+
			 						'<input class="awardInputSmall" type="text" name="award_operator" 	id="award_operator" />'				+
			 						'<input class="awardInputSmall" type="text" name="award_count" 		id="award_count" 	/>'				+
			 						'<input class="awardInputSmall" type="checkbox" name="award_auto" id="award_auto" value="1" /></div>'	+
			 						'<div class="awardEntry">'																				+
			 						'<input class="awardInput" 		type="text" name="award_fb_description"		id="award_fb_description" value="{*actor*}"/>'+
			 						'<input class="awardInput" 		type="text" name="award_description"		id="award_description" 	/>'	+
			 						'<input class="awardInput" 		type="text" name="award_image"		id="award_image" 	/>'				+
			 						'</div>'																								+
			 						'<input class="awardInputOpt" type="button" onclick="javascript:createAward();" value="anlegen"/>'	+
			 						'</form><br/>'																					+
			 						'<hr /><br /><u>Vorhandene Auszeichnungen:</u><br />';
			 						

			var existingAwards	=	xmlResponse.getElementsByTagName('userAwardDefines');
			var cnts 			= 	xmlResponse.getElementsByTagName('userAwardCounts');
			if(cnts[0].firstChild.nodeValue) {
				for(var i=0;i<cnts[0].firstChild.nodeValue;i++) {
					toDisplay		+=	'<form id="oldaward' + i + '"><div class="awardEntry">'											+
										'<input class="awardInputSmall" type="text" name="award_rank" value="' + existingAwards[0].getElementsByTagName('rank')[i].firstChild.nodeValue + '" />'	+
										'<input class="awardInput" type="text" name="award_name" value="' + existingAwards[0].getElementsByTagName('name')[i].firstChild.nodeValue + '" />'	+
										'<input class="awardInputSmall" type="text" name="award_aim" value="' + existingAwards[0].getElementsByTagName('aim')[i].firstChild.nodeValue + '" />'		+
										'<input class="awardInput" type="text" name="award_dbtable" id="award_dbtable" value="' + existingAwards[0].getElementsByTagName('dbtable')[i].firstChild.nodeValue + '" />'	+
			 							'<input class="awardInputSmall" type="text" name="award_operator" id="award_operator" value="' + existingAwards[0].getElementsByTagName('operator')[i].firstChild.nodeValue + '" />' +
			 							'<input class="awardInputSmall" type="text" name="award_count" id="award_count" value="' + existingAwards[0].getElementsByTagName('count')[i].firstChild.nodeValue + '" />'	+
			 							'<input class="awardInputSmall" type="checkbox" name="award_auto" id="award_auto" value="1" ';
					if(existingAwards[0].getElementsByTagName('auto')[i].firstChild.nodeValue==1)
						toDisplay	+=	' checked ';
					toDisplay		+=	' /></div>'	+
										'<div class="awardEntry">'+
										'<input	class="awardInput"	type="text" name="award_fb_description" value="' + existingAwards[0].getElementsByTagName('fbdescr')[i].firstChild.nodeValue + '"/>' +
										'<input	class="awardInput"	type="text" name="award_description" value="' + existingAwards[0].getElementsByTagName('descr')[i].firstChild.nodeValue + '"/>' +
										'<input	class="awardInput"	type="text" name="award_image" value="' + existingAwards[0].getElementsByTagName('image')[i].firstChild.nodeValue + '"/>' +
										'<img style="float:left;"	width="18"	src="' + server + images_ + existingAwards[0].getElementsByTagName('image')[i].firstChild.nodeValue + '" />'	+
										'</div>'+
										'<input type="hidden" name="award_defines_id" value="' + existingAwards[0].getElementsByTagName('id')[i].firstChild.nodeValue + '" />'			+
										'<input class="awardInputOpt" type="button" value="update" onclick="javascript:updateAward(' + i + ');" />'	+
										'<input class="awardInputOpt" type="button" value="berechnen" onclick="javascript:calcAward(' + existingAwards[0].getElementsByTagName('id')[i].firstChild.nodeValue + ');" />'	+
										'<input class="awardInputOpt" type="button" value="anzeigen" onclick="javascript:showAwardWinners(' + existingAwards[0].getElementsByTagName('id')[i].firstChild.nodeValue + ');" />'	+
										'<input class="awardInputOpt" type="button" value="delete" onclick="javascript:deleteAward(' + existingAwards[0].getElementsByTagName('id')[i].firstChild.nodeValue + ');" /></form></div>';
									
 				}
 			}
 			dropLineW3('Mainleft', toDisplay+'<br/><hr /><br /><div id="awardoutput"></div>');

		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?user_award_id=' + $('awardselect').value
	});
}

function showAwardWinners(id) {
	var url = server + 'administration/awards/getUserAwardsFinished.xml';
	dropLineW3('formanswer', ' ');
	dropLineW3('formerror', ' ');
	dropLineW3('awardoutput', ' ');
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse		=	response.responseXML;
 			var awardWinners	=	xmlResponse.getElementsByTagName('awardWinners');
 			var numWinners		=	xmlResponse.getElementsByTagName('numWinners');
 			var awardInfos		=	xmlResponse.getElementsByTagName('awardInfos');
 			numWinners			=	numWinners[0].firstChild.nodeValue;
 			var toDisplay		=	'<div class="awardEntry"><div class="awardInput">Name</div><div class="awardInput">Datum</div><div class="awardInput">Facebook</div><div class="awardInput">Optionen</div></div>';
	 		var images		=	new Array(2);
 			if(awardInfos[0].getElementsByTagName('group_image')[0].firstChild.nodeValue!='-')	{
 				images[0]	=	server + images_+awardInfos[0].getElementsByTagName('group_image')[0].firstChild.nodeValue;
 			}
 			if(awardInfos[0].getElementsByTagName('award_image')[0].firstChild.nodeValue!='-')	{
 				images[1]	=	server + images_+awardInfos[0].getElementsByTagName('award_image')[0].firstChild.nodeValue;
 			}

		 	for(var i=0;i<numWinners;i++) {

		 		
		 		toDisplay		+=	'<div class="awardEntry">';
 				toDisplay 		+=	'<div class="awardInput">' + awardWinners[0].getElementsByTagName('nick')[i].firstChild.nodeValue + '</div>'		+"\n"+
 									'<div class="awardInput">' + awardWinners[0].getElementsByTagName('date')[i].firstChild.nodeValue + '</div>'		+"\n";
				if(awardWinners[0].getElementsByTagName('fbuid')[i].firstChild.nodeValue!='-') {
 					toDisplay	+=	'<div class="awardInput">' + 
					 				'<a href="#" onclick="javascript:fireFacebookNotification(' +
									awardWinners[0].getElementsByTagName('fbuid')[i].firstChild.nodeValue + ',\''+
									awardInfos[0].getElementsByTagName('award_fb_descr')[0].firstChild.nodeValue +
									'\', \''	+
									awardInfos[0].getElementsByTagName('title')[0].firstChild.nodeValue +
									'\', \'' +
									images +
									'\',\'' + awardInfos[0].getElementsByTagName('award_descr')[0].firstChild.nodeValue  + '\',' + 
									awardWinners[0].getElementsByTagName('fid')[i].firstChild.nodeValue+ 
									');"><img width="16" src="' + server + images_ + 'symbols/Facebook-32.png" /></a>'+
									'<!--input class="awardInputOpt1" type="button" onclick="javascript:fireFacebookNotification(' +
									awardWinners[0].getElementsByTagName('fbuid')[i].firstChild.nodeValue + ',\''+
									awardInfos[0].getElementsByTagName('award_fb_descr')[0].firstChild.nodeValue +
									'\', \''	+
									awardInfos[0].getElementsByTagName('title')[0].firstChild.nodeValue +
									'\', \'' +
									images +
									'\',\'' + awardInfos[0].getElementsByTagName('award_descr')[0].firstChild.nodeValue  + '\',' + 
									awardWinners[0].getElementsByTagName('fid')[i].firstChild.nodeValue+ 
									');" value="fb"/-->' + '</div>'		+"\n";
			 	}
 				else
 					toDisplay	+=	'<div class="awardInput"> </div>'																					+"\n";
				toDisplay		+=	'<div class="awardInput"><div class="awardInputSmall"><input class="awardInputOpt1" type="button" value="delete" onclick="javascript:deleteFinishedAward(' + 
									awardWinners[0].getElementsByTagName('fid')[i].firstChild.nodeValue + ');"/></div></div>'			+"\n";
				toDisplay		+=	'</div>';
 			}
			 dropLineW3('awardoutput', toDisplay);		 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: "?award_defines_id="	+	id
	});		
}

function fireFacebookNotification(fbid, comment, name, images, awardDescr, ffbid) {
	var url = server + 'administration/awards/fireFacebookComment.xml';	
	alert('?fbcomment=' + comment + '&fbuid=' + fbid + '&name=' + name + '&images=' + images + '&description=' + awardDescr + '&ffbAwardFinishedId=' + ffbid);
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			alert(response.responseText);
 			var xmlResponse		=	response.responseXML;

 				 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?fbcomment=' + comment + '&fbuid=' + fbid + '&name=' + name + '&images=' + images + '&description=' + awardDescr + '&ffbAwardFinishedId=' + ffbid
	});		
}

function updateAward(id) {
	var url = server + 'administration/awards/setAward.xml';
	dropLineW3('formanswer', ' ');
	dropLineW3('formerror', ' ');
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse		=	response.responseXML;
 			var oldAward		=	xmlResponse.getElementsByTagName('answer');
 			if(oldAward[0].getElementsByTagName('status')[0].firstChild.nodeValue==201) {
 				dropLineW3('formanswer', oldAward[0].getElementsByTagName('text')[0].firstChild.nodeValue);
				selectAwardGroup();
			}
			else
				dropLineW3('formerror', oldAward[0].getElementsByTagName('text')[0].firstChild.nodeValue);		 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: Form.serialize($('oldaward' + id.toString()))
	});	
}


function calcAward(id) {
	var url = server + 'administration/awards/calculateAward.xml';
	dropLineW3('formanswer', ' ');
	dropLineW3('formerror', ' ');

	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			alert(response.responseText);
 			var xmlResponse		=	response.responseXML;
 			var newAwardUser	=	xmlResponse.getElementsByTagName('newAwardUser');
			var updates			= 	xmlResponse.getElementsByTagName('userUpdates');
 			var toDisplay		=	"Updates insgesamt: <b>" + updates[0].firstChild.nodeValue + "</b><br/><ul>";
 			for(var i=0;i<updates[0].firstChild.nodeValue;i++) {
 				toDisplay 		+=	"<li>" + newAwardUser[0].getElementsByTagName('usernick')[i].firstChild.nodeValue + "</li>\n";
 			}
 			dropLineW3("awardoutput", toDisplay+"</ul>");
	 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?award_defines_id=' + id
	});	
}

function deleteAward() {
	var check = confirm("Auszeichnung wirklich loeschen?");
	if (check == false)
  		return;
	alert("lol, bohr dir ein Loch ins Knie und loesch es selbst!");
}

function awardGroupUpdate() {
	var url = server + 'administration/awards/setAwardGroup.xml';
	dropLineW3('formanswer', ' ');
	dropLineW3('formerror', ' ');
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse		=	response.responseXML;
 			var newAward		=	xmlResponse.getElementsByTagName('answer');
 			if(newAward[0].getElementsByTagName('status')[0].firstChild.nodeValue==201) {
 				dropLineW3('formanswer', newAward[0].getElementsByTagName('text')[0].firstChild.nodeValue);
				selectAwardGroup();
			}
			else
				dropLineW3('formerror', newAward[0].getElementsByTagName('text')[0].firstChild.nodeValue);
		 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: Form.serialize($('awardGroupInfo'))
	});	
}

function calcAllAwards() {
	var url = server + 'administration/awards/calculateAllAwards.xml';
	dropLineW3("awardoutput", 'berechnung l&auml;uft...');
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse		=	response.responseXML;
 			dropLineW3("awardoutput", response.responseText);
 			var duration		=	xmlResponse.getElementsByTagname('duration');
 			
			var updates			= 	xmlResponse.getElementsByTagName('userUpdates');
 			var toDisplay		=	"Updates insgesamt: <b>" + updates[0].firstChild.nodeValue + "</b><br/><ul>";
 			if(updates[0].firstChild.nodeValue)
 				var newAwardUser	=	xmlResponse.getElementsByTagName('newAwardUser');
 			for(var i=0;i<updates[0].firstChild.nodeValue;i++) {
 				toDisplay 		+=	"<li>" + newAwardUser[0].getElementsByTagName('usernick')[i].firstChild.nodeValue + "</li>\n";
 			}
 			dropLineW3("awardoutput", toDisplay+"</ul><br/>Dauer(php):"+xmlResponse.getElementsByTagName('duration')[0].firstChild.nodeValue);
	 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});	
}


function createAward() {
	var url = server + 'administration/awards/createAward.xml';
	dropLineW3('formanswer', ' ');
	dropLineW3('formerror', ' ');
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse		=	response.responseXML;
 			var newAward		=	xmlResponse.getElementsByTagName('answer');

 			if(newAward[0].getElementsByTagName('status')[0].firstChild.nodeValue==201) {
 				dropLineW3('formanswer', newAward[0].getElementsByTagName('text')[0].firstChild.nodeValue);
				selectAwardGroup();
			}
			else
				dropLineW3('formerror', newAward[0].getElementsByTagName('text')[0].firstChild.nodeValue);
		 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: Form.serialize($('newaward'))
	});	
}


function sendNewAwardsToFb() {
	//alert('send ... send ... send ...');
	
		var url = server + 'administration/awards/getOutstandingAwardsToFb.xml';
		new Ajax.Request(url, {
 		onSuccess : function(response) {
 			//alert(response.responseText);
 			var xmlResponse		=	response.responseXML;
 			
 			var countIndex		=	xmlResponse.getElementsByTagName('notSendIndex')[0].firstChild.nodeValue;
			//var updates			= 	xmlResponse.getElementsByTagName('userUpdates');
 			var toDisplay		=	"Nicht gesendete Facbook Awards: <b>" + countIndex + "</b><br/>";
 			toDisplay += 	'<div class="awardEntry">';
			toDisplay +=	'<div class="awardInputSmall">UserID</div>';
 			toDisplay +=	'<div class="awardInputSmall">Nickn.</div>';
 			toDisplay +=	'<div class="awardInput">AwardName</div>';
 			toDisplay +=	'<div class="awardInputSmall">A-Rang</div>';
 			toDisplay +=	'<div class="awardInputSmall">FinishedID</div>';
 			toDisplay +=	'<div class="awardInputSmall">FB-send-status</div>';
 			toDisplay +=	'</div><br/><br/>';
 			if(countIndex>0) {
 				var notSendUsers	=	xmlResponse.getElementsByTagName('notSendAwards');
 				var entry	=	notSendUsers[0].getElementsByTagName('XML_Serializer_Tag');
 				for(var i=0;i<countIndex;i++) {
 					
				 	//alert(entry[0].getElementsByTagName('user_award_finished_id')[0].firstChild.nodeValue);
				 	toDisplay += 	'<form id="FBSendAward' + i + '"  class="awardEntry">';
 					toDisplay +=	'<div class="awardInputSmall">' + entry[i].getElementsByTagName('user_id')[0].firstChild.nodeValue + '</div>';
 					toDisplay +=	'<div class="awardInputSmall">' + entry[i].getElementsByTagName('user_nickname')[0].firstChild.nodeValue + '</div>';
 					toDisplay +=	'<div class="awardInput">' + entry[i].getElementsByTagName('award_name')[0].firstChild.nodeValue + '</div>';
 					toDisplay +=	'<div class="awardInputSmall">' + entry[i].getElementsByTagName('award_rank')[0].firstChild.nodeValue + '</div>';
 					toDisplay +=	'<div class="awardInputSmall"><input type="button" class="awardInputOpt" id="user_award_finished_id' + i + '" value="' + 
					 				entry[i].getElementsByTagName('user_award_finished_id')[0].firstChild.nodeValue + '" onclick="javascript:fireFacebookNotification2(' + entry[i].getElementsByTagName('user_award_finished_id')[0].firstChild.nodeValue + ')"  /></div>';
 					toDisplay +=	'<div class="awardInputSmall" id="sendStatus' + entry[i].getElementsByTagName('user_award_finished_id')[0].firstChild.nodeValue + '" >X</div>';
 					
 					toDisplay += 	'</form>';
 				}
 				toDisplay += '<br/><hr/><br/><input type="button" value="alle senden" onclick="javascript:sendAllOutstandingFBAwards(' + countIndex + ');return;"><input type="button" id="sendInfoCounter" value="' + countIndex + '" />';
 			}
 				
 			dropLineW3("awardoutput", toDisplay);
	 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});	
}

function sendAllOutstandingFBAwards(counter) {
	for(var i=0;i<counter;i++) {
		var id	= $('user_award_finished_id'+i).value;
		fireFacebookNotification2(id);
	}
}


function fireFacebookNotification2(user_award_finished_id) {
		var url = server + 'administration/awards/retrieveAwardInfosAndSendToFB.xml';
		new Ajax.Request(url, {
 		onSuccess : function(response) {
 			//alert(response.responseText);
 			var xmlResponse		=	response.responseXML;
			var fbStreamId		=	xmlResponse.getElementsByTagName('fbStreamId')[0].firstChild.nodeValue;
 			var toDisplay		=	'<div>';
		 	if(fbStreamId != 'error') {
		 		$('sendInfoCounter').value	=	$('sendInfoCounter').value - 1;
	 		}
			  
		 	toDisplay 			+= fbStreamId + '</div>';
 				
 			dropLineW3("sendStatus" + user_award_finished_id, toDisplay);
	 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?user_award_finished_id=' + user_award_finished_id
	});	
}