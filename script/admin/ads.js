var allAds_	=	new Array();

function showBlockage() {
	dropLineW3('formanswer', ' ');
	dropLineW3('formerror', ' ');

	var url = server + 'administration/blockads/showBlockade.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse	=	response.responseXML;
 			if(xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('status')[0].firstChild.nodeValue!='201') {
 				dropLineW3('formerror', xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('text')[0].firstChild.nodeValue);
 			} else {
 				dropLineW3('formanswer', xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('text')[0].firstChild.nodeValue);
 				dropLineW3('blockageinfo', response.responseText);
 			}
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?' + Form.serialize($('changeblockages'))
	});	
}

function addBlockade() {
	dropLineW3('formanswer', ' ');
	dropLineW3('formerror', ' ');

	var url = server + 'administration/blockads/addBlockade.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse	=	response.responseXML;
 			if(xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('status')[0].firstChild.nodeValue!='201') {
 				dropLineW3('formerror', xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('text')[0].firstChild.nodeValue);
 			} else {
 				dropLineW3('formanswer', xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('text')[0].firstChild.nodeValue);
 				getSlotAllocation();
 			}
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?' + Form.serialize($('newblockade')) + '&slot_id='+$('slot_id').value
	});
}

function initBlockAds() {
	dropLineW3('formanswer', ' ');
	dropLineW3('formerror', ' ');
	var toDisplay	=	'<h2>Werbebl&ouml;cke f&uuml;r Benutzer/IP blockieren</h2><hr /><h3>neue Regel Erstellen:</h3><div id="blockadsoutput0" style="clear:both;">'+
						'<div id="addLeft" style="float:left; width:100%;"></div><div id="addRight" style="float:right; width:100%;"></div>' +
						'</div><br/><h3>vorhandene Regeln bearbeiten:</h3><div id="blockadsoutput1"></div>';
	dropLineW3('adsoutput', toDisplay);
	var url = server + 'administration/blockads/loadAllUser.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse	=	response.responseXML;
 			var count	=	xmlResponse.getElementsByTagName('count')[0].firstChild.nodeValue;
 			toDisplay		=	'<form name="newblockade" id="newblockade">' + 
			 					'<div class="adInput"><b>Benutzer (3.):</b><br/><select class="stdSelect" name="user_id" id="user_id" size="1">';
 			if(count>0) {
 				var ids		=	xmlResponse.getElementsByTagName('id');
 				var nicks	=	xmlResponse.getElementsByTagName('nick');
 				var startLetter	= '';
 				for(var i=0;i<count;i++) {
 					var nick	=	nicks[i].firstChild.nodeValue
 					var achar	=	nick.charAt(0);
 					achar 		=	achar.toUpperCase();
 					if( startLetter!= achar ) {
 						if(startLetter.length)
 							toDisplay	+=	'</optgroup>';
 						toDisplay	+=	'<optgroup class="selectOptgroup"  label="' + achar.toString() + "\">\n\n";
 						startLetter	= achar;
 					}
 					toDisplay	+=	'<option class="stdSelect' + (i%2) +'" value="' + ids[i].firstChild.nodeValue + '" label="' + nick +'" >' + nick + "</option>\n";
 				}
 				toDisplay	+=	'</optgroup></select></div>' +
				 				'<div class="adInput"><b>oder IP Adresse(2.):</b><br /><input class="adInputAll" type="text" name="user_ip" /></div>'	+
				 				'<div class="adInput"><b>oder Nickname (1.):</b><br /><input class="adInputAll" type="text" name="user_nick" /></div>';
 				dropLineW3('addLeft', toDisplay);
 			}	 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});
	
	var url = server + 'administration/ads/getAdsSlots.xml'; 
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse	=	response.responseXML;
 			var count	=	xmlResponse.getElementsByTagName('slotCount')[0].firstChild.nodeValue;
 			var toDisplay2	=	'<div class="adInput"><b>zu blockierender Slot:</b><br/><select class="stdSelect" name="no_ads_id" id="no_ads_id" size="1">' +
 								'<option value="0" selected class="stdSelect1">-- alle --</option>';
 			if(count>0) {
 				var ids		=	xmlResponse.getElementsByTagName('id');
 				var name	=	xmlResponse.getElementsByTagName('name');
 				for(var i=0;i<count;i++) {
 					toDisplay2	+=	'<option class="stdSelect' + (i%2) +'" value="' + ids[i].firstChild.nodeValue + '" >' + name[i].firstChild.nodeValue + "</option>\n";
 				}
 				toDisplay2	+=	'</select></div>' +
				 				'<input class="adInputOpt" type="button" value="eintragen" onclick="javascript:addBlockade();" />' +
				 				'</form><br/><div class="clear"> </div><br/><hr />';
 				dropLineW3('addRight', toDisplay2);
 			}	 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});	
	
	var url = server + 'administration/blockads/getBlockedUsers.xml'; 
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse	=	response.responseXML;
 			var count	=	xmlResponse.getElementsByTagName('count')[0].firstChild.nodeValue;
 			var toDisplay3	=	'<div style="width:95%;"><div class="adInput"><form id="changeblockages">Werbeblockade ausw&auml;hlen: ' +
			 					'<select class="stdSelect" name="slot_blockage_id" id="slot_blockage_id" size="1" >' +
 								'<option selected value="0" class="stdSelect1">... Auswahl:</option>';
 			if(count>0) {
 				var s_ids	=	xmlResponse.getElementsByTagName('slot_id');
 				var s_names	=	xmlResponse.getElementsByTagName('slot_name');
 				var u_names	=	xmlResponse.getElementsByTagName('u_name');
 				var u_id_ips=	xmlResponse.getElementsByTagName('u_ip_id');
 				var b_ids	=	xmlResponse.getElementsByTagName('blockage_id');
 				 
 				for(var i=0;i<count;i++) {
 					toDisplay3	+=	'<option class="stdSelect' + (i%2) +'" value="' + b_ids[i].firstChild.nodeValue + '" >' + u_names[i].firstChild.nodeValue + ' ' + s_names[i].firstChild.nodeValue +  "</option>\n";
 				}
 				
 			}
		 	toDisplay3	+=	'</select>' +
				 			'<input class="adInputOpt" type="button" value="l&ouml;schen (not impl)" onclick="javascript:delBlockage();" />' +
			 				'<input class="adInputOpt" type="button" value="ansehen (not impl)" onclick="showBlockage();"/>' +
				 			'</form></div><div class="adInputAll" id="blockageinfo">.</div><div class="clear"></div></div><br/>';
			dropLineW3('blockadsoutput1', toDisplay3);	 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});	
	
		
}


function loadAllAds() {
	var url = server + 'administration/ads/getAds.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse	=	response.responseXML;
 			var adsCount	=	xmlResponse.getElementsByTagName('adsCount')[0].firstChild.nodeValue;
 			if(adsCount>0) {
 				var ids		=	xmlResponse.getElementsByTagName('id');
 				var names	=	xmlResponse.getElementsByTagName('name');
 				var codes	=	xmlResponse.getElementsByTagName('code');
 				for(var i=0;i<adsCount;i++) {
 					allAds_[i]		= 	new Object();
 					allAds_[i]['id']	=	ids[i].firstChild.nodeValue;
			 		allAds_[i]['name']	=	names[i].firstChild.nodeValue;
				 	allAds_[i]['code']	=	codes[i].firstChild.nodeValue;
 				}
 			}	 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});		
}

function addAd() {
	var url = server + 'administration/ads/addAd.xml';
	if($('selectaddad').value<1||!$('selectaddad').value) {
		alert('keine Werbung selektiert.');
		return;
	}
	
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse	=	response.responseXML;
	 		if(xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('status')[0].firstChild.nodeValue!='201') {
 				dropLineW3('formerror', xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('text')[0].firstChild.nodeValue);
 			} else {
 				dropLineW3('formanswer', xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('text')[0].firstChild.nodeValue);
 				getSlotAllocation();
 			}
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?' + Form.serialize($('addadform'))
	});		
}


function updateAdSlotEntry(allocId) {
	var tmp = 'slotalloc' + allocId.toString();
	var url = server + 'administration/ads/updateAdSlotEntry.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse	=	response.responseXML;
	 		if(xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('status')[0].firstChild.nodeValue!='201') {
 				dropLineW3('formerror', xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('text')[0].firstChild.nodeValue);
 				$('slot'+allocId).setStyle({   backgroundColor: 'red' });
 			} else {
 				dropLineW3('formanswer', xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('text')[0].firstChild.nodeValue);
 				$('slot'+allocId).setStyle({   backgroundColor: '#11BB44' });
 			}
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?' + Form.serialize($('slotalloc' + allocId))
	});
}

function getSlotAllocation() {
	var url = server + 'administration/ads/getSlotAllocation.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse	=	response.responseXML;
 			if(xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('status')[0].firstChild.nodeValue!='201') {
 				dropLineW3('formerror', xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('text')[0].firstChild.nodeValue);
 				return;
 			}
 			
 			var slotAllocationCount	=	xmlResponse.getElementsByTagName('slotAllocationCount')[0].firstChild.nodeValue;
			var toDisplay 	=	'<h3>Slotbelegung:</h3>'	+
								'<div class="addEntry">'	+
								'<div 	class="adInput">Name</div>' + 
								'<div	class="adInputSmall" >Aufrufe</div>' + 
								'<div	class="adInputSmall" >Max</div>' + 
								'<div	class="adInputSmall" >Priorit&auml;t</div>' +
								'<div	class="adInput" >Start</div>' +
								'<div	class="adInput"	>Ende</div>' +
								'<div	class="adInputSmall" >GameID</div>' +
								'</div><br />';
								
			
			if(slotAllocationCount>0) {
				var name	=	xmlResponse.getElementsByTagName('allocAdName');
				var count	=	xmlResponse.getElementsByTagName('allocAdCount');
				var max		=	xmlResponse.getElementsByTagName('allocAdMax');
				var pri		=	xmlResponse.getElementsByTagName('allocAdPri');
				var start	=	xmlResponse.getElementsByTagName('allocAdStart');
				var end		=	xmlResponse.getElementsByTagName('allocAdEnd');
				var game	=	xmlResponse.getElementsByTagName('allocAdGameId');
				var id		=	xmlResponse.getElementsByTagName('allocId');
				var total	=	0;
				for(var i=0;i<slotAllocationCount;i++) {
					toDisplay += 	'<div class="adEntry" id="slot' + id[i].firstChild.nodeValue + '"><form name="slotalloc' + id[i].firstChild.nodeValue +'" id="slotalloc' + id[i].firstChild.nodeValue + '">' 	+
									'<div class="adInput">' + name[i].firstChild.nodeValue + '</div>' + 
									'<input	class="adInputSmall"	type="text"		size="2"	name="ad_count"		value="' + count[i].firstChild.nodeValue + '" />' + 
									'<input	class="adInputSmall"	type="text"		size="2"	name="ad_max"		value="' + max[i].firstChild.nodeValue + '" />' + 
									'<input	class="adInputSmall"	type="text"		size="2"	name="ad_prio"		value="' + pri[i].firstChild.nodeValue + '" />' +
									'<input	class="adInput"			type="text"		size="10"	name="ad_start"		value="' + start[i].firstChild.nodeValue + '" />' +
									'<input	class="adInput"			type="text"		size="10"	name="ad_end"		value="' + end[i].firstChild.nodeValue + '" />' +
									'<input	class="adInputSmall"	type="text"		size="2"	name="game_id"		value="' + game[i].firstChild.nodeValue + '" />' +
									'<input type="hidden" name="slot_alloc_id" value="' + id[i].firstChild.nodeValue + '" />'	+
									'</form>' + 
									'<input type="button" class="adInputOpt" onclick="updateAdSlotEntry(\'' + id[i].firstChild.nodeValue + '\');" value="update" /> ' +
									'<input type="button" class="adInputOpt" onclick="deleteAdSlotEntry(\'' + id[i].firstChild.nodeValue + '\');" value="delete (not impl)" /> ' +
									'</div>';
					total	+=	parseInt(count[i].firstChild.nodeValue);	
				}
				toDisplay	+=	'<div class="adEntry"><div class="adInput">Werbeimpressionen:</div><div class="adInputSmall">' + total + '</div></div>';
			}
			dropLineW3('slotalloc', toDisplay);  		 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?slot_id=' + $('adsslotselect').value
	});		
}


function selectAdsSlot() {
	var url = server + 'administration/ads/getSlotInfo.xml';
	dropLineW3('formanswer', ' ');
	dropLineW3('formerror', ' ');
	dropLineW3('adsoutput', '<div id="slot">...loading..</div><div id="addad">...loading..</div><div id="slotalloc">...loading..</div> ');
	getSlotAllocation();
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse	=	response.responseXML;

 			if(xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('status')[0].firstChild.nodeValue!='201') {
 				dropLineW3('formerror', xmlResponse.getElementsByTagName('msg')[0].getElementsByTagName('text')[0].firstChild.nodeValue);
 				return;
 			}
 			
 			var slotInfo		=	xmlResponse.getElementsByTagName('slotInfo');

 			if(slotInfo[0].getElementsByTagName('slotName')[0].firstChild.nodeValue) {
 				//alert(response.responseText);
 				//alert(slotInfo[0].getElementsByTagName('slotCss')[0].firstChild.nodeValue); //IE Error!
 				var toDisplay	=	'<h2>' + slotInfo[0].getElementsByTagName('slotName')[0].firstChild.nodeValue + '</h2>'	+
 									'<hr /><form name="slot" id="slot" onsubmit="javascript:setSlot();">' +
 									'<div class="adInputSmall">Name: </div><input	type="text"		class="adInputSmall"		name="slot_name" value="'	+
 									slotInfo[0].getElementsByTagName('slotName')[0].firstChild.nodeValue +
 									'" /><div class="adInputSmall"> CSS Class: </div><input	type="text"		class="adInputSmall"		name="slot_css" value="'	+
 									//slotInfo[0].getElementsByTagName('slotCss')[0].firstChild.nodeValue + //IE error emty fields
 									'" /><br />'	+
 									'<input type="hidden" name="slot_id" value="' +
 									slotInfo[0].getElementsByTagName('slotId')[0].firstChild.nodeValue +
 									'" />' +
 									'<input class="adInputOpt" type="button" value="Slot update (not impl)" onclick="javascript:setSlot();" />' +
									'</form><hr />';
				
				dropLineW3('slot', toDisplay);
				toDisplay		=	'<h3>Werbeblock hinzuf&uuml;gen:<h3><form name="addadform" id="addadform">'	+
									'<input type="hidden" name="slot_id" value="' + slotInfo[0].getElementsByTagName('slotId')[0].firstChild.nodeValue + '" />' +
									'<select class="stdSelect" style="float:left;" name="add_id" id="selectaddad" size="1" ><option selected value="0">Auswahl...</option>';
				for(var i=0;i<allAds_.length;i++) {
					toDisplay	+=	'<option value="' + allAds_[i]['id'] + '">' + allAds_[i]['name'] + '</option>';
				}
				var today		=	new Date();
				var year		=	today.getFullYear();
				var day			=	today.getDate();
				var month		=	today.getMonth() + 1;
				var endYear		=	today.getFullYear() + 20;
				var start		=	year.toString()    + '-' + month.toString() + '-' + day.toString();
				var end			=	endYear.toString() + '-' + month.toString() + '-' + day.toString();
				toDisplay		+=	'</select> ' +
									'<div class="adInputSmall">Max #:</div><input class="adInputSmall"	type="text"	name="ad_max"	size="2"	value="0" />' +
									'<div class="adInputSmall">Prioriy:</div><input class="adInputSmall"	type="text"	name="ad_prio"	size="2"	value="5" />' +
									'<div class="adInputSmall">Start:</div><input class="adInput"		type="text"	name="ad_start"	size="10"	value="' + start +'" />' +
									'<div class="adInputSmall">Ende:</div><input class="adInput"		type="text"	name="ad_end"	size="10"	value="' + end  +'" />' +
									'<div class="adInputSmall">GameID:</div><input class="adInputSmall"	type="text"	name="game_id"	size="2"	value="0" />' +  
									'<input class="adInputOpt" type="button" value="hinzuf&uuml;gen" onclick="javascript:addAd();" />' + 
									'</form><hr />';
				dropLineW3('addad', toDisplay);				
			}
			else
				dropLineW3('formerror', 'error');		 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?slot_id=' + $('adsslotselect').value
	});	
}



function selectAd() {
	var url = server + 'administration/ads/getAdInfo.xml';
	dropLineW3('formanswer', ' ');
	dropLineW3('formerror', ' ');
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse	=	response.responseXML;
 			var adInfo		=	xmlResponse.getElementsByTagName('adInfo');
 			if(adInfo[0].getElementsByTagName('id')[0].firstChild.nodeValue) {
 				var toDisplay	=	'<h2>' + adInfo[0].getElementsByTagName('name')[0].firstChild.nodeValue + '</h2>'	+
 									'<hr /><form name="ad" id="ad" onsubmit="javascript:setAd();">' +
				 					'<b>Name:</b><br />' +
				 					'<input class="adInput" 		type="text" name="ad_name"		id="ad_name"	value="' + 	
				 					adInfo[0].getElementsByTagName('name')[0].firstChild.nodeValue + '" /><br />'	+
				 					'<b>HTML Code:</b><br />' +
				 					'<textarea cols="50" rows="5" name="ad_code" id="ad_code" />';
				if(adInfo[0].getElementsByTagName('code')[0].firstChild.nodeValue!=' ')
					toDisplay +=  adInfo[0].getElementsByTagName('code')[0].firstChild.nodeValue;
				toDisplay 		+=	'</textarea><br />' +
				 					'<input type="hidden" name="ad_id" id="ad_id" value="' + adInfo[0].getElementsByTagName('id')[0].firstChild.nodeValue + '" />' +
				 					'<input class="adInputOpt" type="button" onclick="javascript:setAd();" value="update"/></form><br /><hr/ ><br /><b>Preview:</b><br/>' +
				 					'<div id="adpreview">' + adInfo[0].getElementsByTagName('code')[0].firstChild.nodeValue + '</div>';
				dropLineW3('adsoutput', toDisplay);
			}
			else
				dropLineW3('formerror', 'error');		 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?ads_id=' + $('adselect').value
	});	
}

function setAd() {
	var url = server + 'administration/ads/setAd.xml';
	dropLineW3('formanswer', ' ');
	dropLineW3('formerror', ' ');
	dropLineW3('adpreview', '...updating...');
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse	=	response.responseXML;
 			var msg		=	xmlResponse.getElementsByTagName('msg');
 			if(msg[0].getElementsByTagName('status')[0].firstChild.nodeValue!='201'){
 				dropLineW3('formerror', msg[0].getElementsByTagName('text')[0].firstChild.nodeValue);
 			} else {
 				dropLineW3('formanswer', msg[0].getElementsByTagName('text')[0].firstChild.nodeValue);
 				dropLineW3('adpreview', $('ad_code').value);	
 			}	 	
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?'+ Form.serialize($('ad'))
	});	
}