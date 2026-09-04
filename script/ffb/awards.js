function showUserAwards(userid) {
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
		var toDisplay = '';
		for(var i=0;i<numGroups;i++) {
			toDisplay	+=	'<div><img src="' + server + images_ +awards[i].getElementsByTagName('gimg')[0].firstChild.nodeValue + '" style="float:left; width:55px;">'	+
							'Titel: <b>'	+	awards[i].getElementsByTagName('gname')[0].firstChild.nodeValue	+	"</b><br/>\r\n"	+
							'Beschreibung: <b>'	+	awards[i].getElementsByTagName('gdescr')[0].firstChild.nodeValue	+	"</b><br/><br/>\r\n</div><div><ol style=\"  vertical-align: bottom; border-bottom:black solid 1px;\">\r\n";

			var awardCnt=	awards[i].getElementsByTagName('awardcount')[0].firstChild.nodeValue;
			var award	=	awards[i].getElementsByTagName('award');

			for(var j=0;j<awardCnt;j++) {
			 	toDisplay	+=	'<br style="border-bottom: 1px silver dashed;margin-bottom:5px;"><img src="' + server + images_ + award[0].getElementsByTagName('img')[j].firstChild.nodeValue + '" style="float:left;">'	+
								'Rang: <b>'	+	award[0].getElementsByTagName('name')[j].firstChild.nodeValue	+	"</b><br/>\r\n"	+
								'Beschreibung: <b>'	+	award[0].getElementsByTagName('descr')[j].firstChild.nodeValue	+	"</b><br/>\r\n"	+
								'Eledigt: <input type="checkbox" disabled style="width:12px; height:12px; font-weight:bold; color:black; background-color:#CCFFCC;" ';
				if(award[0].getElementsByTagName('finished')[j].firstChild.nodeValue==1)
					toDisplay	+=	'checked="true"';
				toDisplay	+=	'><br/>';
			}
			toDisplay	+=	"</ol></div>\r\n";
		}
		displayInfoPopup(toDisplay, 640);
		},
		onFailure : function(response) {
    	alert('error retrieving matchround-details');
 		},
 		parameters: '?user_id=' + userid
	});
}
/*
function dispPlayerinfoHead() {
    var string = '';
    string += '<div id="infobox_name">';
    string += '<div id="infobox_close">';
    string += '<a href="javascript:void(0);" onClick="javascript:clearPlayerstatsPopup();">schlie&szlig;en</a>';
    string += '</div>';
    string += '<div id="listclear"></div>';

    return string;
}
*/
/*
function displayInfoPopup(string) {
    if(document.getElementById('mouseinfo_div')) {
        document.getElementById('Mainleft').removeChild(document.getElementById('mouseinfo_div'));
    }
    var newDiv = document.createElement('div');
    newDiv.id = 'mouseinfo_div';
    newDiv.style.position 		=	'absolute';
    newDiv.style.border 		=	'solid black 1px';
    newDiv.style.backgroundColor=	'#99CC99';

    if(window.pageYOffset) {
        newDiv.style.top = (0+window.pageYOffset-100)+'px';
    } else {
        newDiv.style.top = 20+document.documentElement.scrollTop+'px';
    }

    newDiv.style.left = '80px';
    newDiv.style.width = '700px';
    //newDiv.style.height = '400px';
    newDiv.style.fontSize = '11px';
    newDiv.innerHTML = string;
    newDiv.style.zIndex = '100';

    //document.getElementById('soccer_field').appendChild(newDiv);
    document.getElementById('Mainleft').appendChild(newDiv);
}
*/
