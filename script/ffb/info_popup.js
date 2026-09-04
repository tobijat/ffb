function displayInfoPopup(string, width) {
    closeInfoPopup();

	if(!width) {
		width = 520;
	}
	var round_div = '';
	round_div += '<div class="roundcorner_light">';
	round_div += '<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>';
	round_div += '<div id="statslist">';
	round_div += string;
	round_div += '</div>';
	round_div += '<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>';
	round_div += '</div>';

    var newDiv = document.createElement('div');
    newDiv.id = 'mouseinfo_div';
    newDiv.style.position = 'absolute';
    newDiv.style.border = 'solid black 0px';

    if(window.pageYOffset) {
        newDiv.style.top = (0+window.pageYOffset-100)+'px';
    } else {
        newDiv.style.top = 20+document.documentElement.scrollTop+'px';
    }

    newDiv.style.left = '80px';
    newDiv.style.width = width+'px';
    newDiv.style.fontSize = '10pt';
    newDiv.innerHTML = round_div;
    newDiv.style.zIndex = '1000';

    document.getElementById('Mainleft').appendChild(newDiv);
}
function closeInfoPopup() {
    if(document.getElementById('mouseinfo_div')) {
        document.getElementById('Mainleft').removeChild(document.getElementById('mouseinfo_div'));
    }

    return;
}

function dispPopupWaiting() {
	closeInfoPopup();

	var string = '<div id="statsname">';
    string += '<div id="infobox_name">lade Infos.. bitte warten..</div>';
    string += '<div id="infobox_close"><a title="Schlie&szlig;en" href="javascript:void(0);" onClick="javascript:closeInfoPopup();"><img alt="Schlie&szlig;en" border="0" src="' + server + symbolImages_ + 'delete.png"></a></div>';
    string += '<div id="listclear"></div></div>';
    displayInfoPopup(string, 520);

    return;
}

function switchToPlayerinfo(id) {
    if(document.getElementById('mouseinfo_div')) {
        document.getElementById('Mainleft').removeChild(document.getElementById('mouseinfo_div'));
    }
    dispPlayerinfoPopup(id);
}
