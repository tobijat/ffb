function init() {
    addTeams();
}

function addTeams() {
    var url = server + 'ffb/team/getList.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		var teams = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		for(var i=0;i<teams.length;i++) {
 		    if(document.registration.user_favourite_team_post.value == teams[i].getElementsByTagName('team_id')[0].firstChild.data)
                selected = true;
            else
                selected = false;
 		    newOption = new Option(teams[i].getElementsByTagName('team_name')[0].firstChild.data + ' (' + teams[i].getElementsByTagName('team_nationality')[0].firstChild.data + ')', teams[i].getElementsByTagName('team_id')[0].firstChild.data, false, selected);
 		    document.registration.user_favourite_team.options[document.registration.user_favourite_team.length] = newOption;
		}

		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});
}
