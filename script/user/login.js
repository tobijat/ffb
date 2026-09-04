_login_formtitle = '';
_login_forminput = '';

function authenticate() {
    var url = server + 'users/login/loginAjax.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
 		var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.nodeValue;
 		if(status==200) {
            var dest = xmlResponse.getElementsByTagName('administration_destination')[0].firstChild.nodeValue;
			window.location.href = dest;
 		} else {
 		    var errors = xmlResponse.getElementsByTagName('errors');
 		    errors = errors[0].getElementsByTagName('XML_Serializer_Tag');
 		    var answer = '<div id="login_formerror">';
 		    answer += '<b>Es sind Fehler aufgetreten:</b><br>';
 		    for(var i=0;i<errors.length;i++) {
 		        answer += errors[i].firstChild.nodeValue + '<br>';
 		    }
 		    answer += '</div>';
 		    dropLineW3('answer', answer);
 		}

		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: Form.serialize($('login'))
	});
	return false;
}

function getPassword() {
    var url = server + 'users/registration/getPassword.xml';
    var fpwb = document.getElementById('forgotten_pw_button');
    fpwb.disabled = true;

	new Ajax.Request(url, {
 		onSuccess : function(response) {
		fpwb.disabled = false;
 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);

 		var status = xmlResponse.getElementsByTagName('user_status')[0].firstChild.nodeValue;

 		if(status==200) {
            var answer_text = xmlResponse.getElementsByTagName('user_answer')[0].firstChild.nodeValue;
            var answer = '<div id="login_formanswer">';
 		    answer += answer_text;
 		    answer += '</div>';
 		    dispLogin();
 		    dropLineW3('answer', answer);
 		} else {
 		    var errors = xmlResponse.getElementsByTagName('errors');
 		    errors = errors[0].getElementsByTagName('XML_Serializer_Tag');
 		    var answer = '<div id="login_formerror">';
 		    answer += '<b>Es sind Fehler aufgetreten:</b><br>';
 		    for(var i=0;i<errors.length;i++) {
 		        answer += errors[i].firstChild.nodeValue + '<br>';
 		    }
 		    answer += '</div>';
 		    dropLineW3('answer', answer);
 		}

		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: Form.serialize($('login'))
	});
	return false;
}

function forgottenPassword() {
	var div = document.getElementById('formtitle');
	var login_formtitle = div.innerHTML;
	var div = document.getElementById('loginforminput');
	var login_forminput = div.innerHTML;
	_login_formtitle = login_formtitle;
	_login_forminput = login_forminput;
	//alert(login_text);

	var pw_formtitle = '';
	pw_formtitle += 'Passwort vergessen';

	var pw_forminput = '';
	pw_forminput += '<form name="login" id="login" method="POST" onsubmit="return false" enctype="multipart/form-data">';
	pw_forminput += '<div id="formline">';
	pw_forminput += '<div id="formdescr">* Nickname:</div>';
	pw_forminput += '<div id="forminput">';
	pw_forminput += '<input type="text" name="user_nickname">';
	pw_forminput += '</div>';
	pw_forminput += '<div id="formclear"></div>';
	pw_forminput += '</div>';
	pw_forminput += '<div id="formline">';
	pw_forminput += '<div id="formdescr">* E-Mail:</div>';
	pw_forminput += '<div id="forminput">';
	pw_forminput += '<input type="text" name="user_email" value="">';
	pw_forminput += '</div>';
	pw_forminput += '<div id="formclear"></div>';
	pw_forminput += '</div>';
	pw_forminput += '<div id="formline_center">';
	pw_forminput += '<input type="hidden" name="destination">';
	pw_forminput += '<input id="forgotten_pw_button" type="submit" value="Neues Passwort anfordern" onclick="javascript:getPassword();">';
	pw_forminput += '</div>';
	pw_forminput += '<div id="formline_center">';
	pw_forminput += '<a href="javascript:void(0);" onclick="javascript:dispLogin();">zur&uuml;ck zum Login</a>';
	pw_forminput += '</div>';
	pw_forminput += '<input type="hidden" name="users_registration_getpassword" value="1">';
	pw_forminput += '</form>';

	dropLineW3('formtitle', pw_formtitle);
	dropLineW3('loginforminput', pw_forminput);
	dropLineW3('answer', '');
}

function dispLogin() {
	dropLineW3('formtitle', _login_formtitle);
	dropLineW3('loginforminput', _login_forminput);
	dropLineW3('answer', '');
}
