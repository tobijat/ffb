function sendFacebookMsg(senderId, receiverId, msgElementId) {
	var url = server + 'administration/awards/fireFacebookU2UComment.xml';
	//alert(msgElementId);
	//alert(Form.serialize($(msgElementId)) + '&application=toApiWall');
	//return;
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			var xmlResponse = response.responseXML;
 			//var error = xmlResponse.getElementsByTagName('array');
 			alert(response.responseText);
 			//alert(unescape(response.responseText));

		},
		onFailure : function(response) {
    		alert("Oops, there's been an error.");
		},
		parameters: Form.serialize($(msgElementId)) + '&application=toApiWall'
	});
}