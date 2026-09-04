function feedback() {
	var url = 'http://www.gemura.com/feedback/feedback.php';

	new Ajax.Request(url, {
 		onSuccess : function(response) {
 				alert('feedback');
 			alert(esponse.responseText);
			 //dropLineW3('Leftteam', response.responseText);
		},
		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});
}

function sendFeedback() {
	var url = 'http://www.gemura.com/feedback/feedback.php';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 			dropLineW3('Leftteam', 'Danke  f&uuml;r deine Meinung!');
		},
		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters : Form.serialize($('feedback'))
	});
}

function dropLineW3(divName,content)
{
  var xlayer = document.getElementById(divName);
  xlayer.innerHTML=content;
}