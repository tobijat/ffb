//var ffbFacebookSubmitButton_	=	'<input type="submit" name="action" value="Vernetzen!" />';
/*
function doFfbFacebookConnect() {
	var url 	=	server + 'ffb/ffbfacebook/ffbConnectFacebook.xml';
	//alert(Form.serialize($('ffbfacebookconn')));
		new Ajax.Request(url, {
		method: 'get',
 		onSuccess : function(response) {
		alert(response.responseText);
 		var xmlResponse	=	response.responseXML;
		var toDisplay 	=	'<h3>Du hast dich erfolgreich vernetzt, du bekommst jetzt direkt in deinen Facebook Account Auszeichnungen angezeigt sobald du neu Ziele erreicht hast!</h3>';
		var success		=	xmlResponse.getElementsByTagName('userRegistered')[0].firstChild.nodeValue;
		if(success) {
			dropLineW3('facebookwelcome', toDisplay);
		} else {
			dropLineW3('facebookwelcome', '<div style="background-color:red;">Leider ist ein Fehler aufgetreten, es konte keine Vernetzung vorgenommen werden, eventuell hast du ein falsches Passwor / Benutzernamen angegeben.</div>');
		}
		
		},

		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters :	Form.serialize($('ffbfacebookconn'))
	});
	
}

function changeSubmit() {
	dropLineW3('ffbfbsubmit', '<input type="submit" name="action" value="Vernetzen!" />');
	dropLineW3('ffbfbsubmit', ffbFacebookSubmitButton_);
	alert("ffb");
}*/