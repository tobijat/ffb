function init() {
    addGames();
}

function addGames() {
    //alert('hi');
    var url = server + 'administration/game/getGameList.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
 		var games = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		for(var i=0;i<games.length;i++) {
 		    if(document.administration.game_id_post.value == games[i].getElementsByTagName('game_id')[0].firstChild.data)
                selected = true;
            else
                selected = false;

 		    newOption = new Option(games[i].getElementsByTagName('game_title')[0].firstChild.data, games[i].getElementsByTagName('game_id')[0].firstChild.data, false, selected);
 		    document.administration.news_game_id.options[document.administration.news_game_id.length] = newOption;
		}

		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});
}

