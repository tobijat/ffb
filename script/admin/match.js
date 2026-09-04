function init() {
    addMatchrounds();
    addTeams();
}

function addMatchrounds() {
    var url = server + 'administration/matchround/getList.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		var matchrounds = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		for(var i=0;i<matchrounds.length;i++) {
 		    if(document.administration.match_round_post.value == matchrounds[i].getElementsByTagName('matchround_id')[0].firstChild.data)
                selected = true;
            else
                selected = false;

 		    newOption = new Option(matchrounds[i].getElementsByTagName('matchround_title')[0].firstChild.data, matchrounds[i].getElementsByTagName('matchround_id')[0].firstChild.data, false, selected);
 		    document.administration.match_round.options[document.administration.match_round.length] = newOption;
		}

		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});
}

function addTeams() {
    var url = server + 'administration/team/getList.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
    //alert(response.responseText);
 		var xmlResponse=response.responseXML;
 		var teams = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		for(var i=0;i<teams.length;i++) {
 		    if(document.administration.match_hometeam_id_post.value == teams[i].getElementsByTagName('team_id')[0].firstChild.data)
                selected = true;
            else
                selected = false;
 		    newOptionHome = new Option(teams[i].getElementsByTagName('team_name')[0].firstChild.data + ' (' + teams[i].getElementsByTagName('team_nationality')[0].firstChild.data + ')', teams[i].getElementsByTagName('team_id')[0].firstChild.data, false, selected);
 		    if(document.administration.match_guestteam_id_post.value == teams[i].getElementsByTagName('team_id')[0].firstChild.data)
                selected = true;
            else
                selected = false;
 		    newOptionGuest = new Option(teams[i].getElementsByTagName('team_name')[0].firstChild.data + ' (' + teams[i].getElementsByTagName('team_nationality')[0].firstChild.data + ')', teams[i].getElementsByTagName('team_id')[0].firstChild.data, false, selected);
 		    document.administration.match_hometeam_id.options[document.administration.match_hometeam_id.length] = newOptionHome;
 		    document.administration.match_guestteam_id.options[document.administration.match_guestteam_id.length] = newOptionGuest;
		}

		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});
}
