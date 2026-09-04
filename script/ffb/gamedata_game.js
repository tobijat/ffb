function dispGameSelection() {
    var url = server + 'ffb/game/getPastGames.xml';
    new Ajax.Request(url, {
 		onSuccess : function(response) {
 		    //alert(response.responseText);
 		    var xmlResponse=response.responseXML;
 		    var numResults = xmlResponse.getElementsByTagName('num_results')[0].firstChild.nodeValue;
 		    if(numResults>0) {
 		        var games = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		        var text = '<b>Bitte wähle eine Liga aus:</b><br><br>';
 		        var selectBox = '<select size="1" name="gameSelectBox" id="gameSelectBox">' +
 		        				'<option selected disabled>Liga waehlen..</option>';
 		        for(var i=0;i<games.length;i++) {
 		            var game_id = games[i].getElementsByTagName('game_id')[0].firstChild.nodeValue;
 		            var game_title = games[i].getElementsByTagName('game_title')[0].firstChild.nodeValue;
 		            selectBox += '<option value="'+game_id+'">'+
						 		  game_title+'</option>';

 		        }
 		        selectBox += '</select>';
 		        dropLineW3('selected_game', selectBox);
            } else {
                dropLineW3('selected_game', 'Keine Ligen vorhanden!');
            }
		},
		onFailure : function(response) {
    		alert("Oops, there's been an error.");
 		}
	});
}
