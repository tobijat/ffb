//var baseurl = server + 'ffbapi/table/getTableForGame.xml';


function init(league) {
  var baseurl = 'http://ffb.gemura.com/ffbstats/ffb_table_grab.php';
  var start = new Date();
	var url = baseurl;
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		 var xmlResponse=response.responseXML;
 		 //alert(response.responseText);

 		 var table_row = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		 //alert(table_row.length);
 		 var string = '<table><tr>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Platz</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Mannschaft</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Spiele</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Siege</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Remis</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Niederl.</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Torverh&auml;ltnis</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Punkte</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Punkteausbeute</td>';
 		 string += '</tr>';
 		 for(var i=0;i<table_row.length;i++) {
             string += '<tr>';
             string += '<td style="padding-right:2px;font-weight:bold;text-align:left;">'+(i+1)+'</td>';
 		     string += '<td style="padding-right:2px;text-align:left;">'+table_row[i].getElementsByTagName('team_name')[0].firstChild.nodeValue+'</td>';
 		     string += '<td style="padding-right:2px;text-align:center;">'+table_row[i].getElementsByTagName('team_num_matches')[0].firstChild.nodeValue+'</td>';
 		     string += '<td style="padding-right:2px;text-align:center;">'+table_row[i].getElementsByTagName('team_wins')[0].firstChild.nodeValue+'</td>';
 		     string += '<td style="padding-right:2px;text-align:center;">'+table_row[i].getElementsByTagName('team_equals')[0].firstChild.nodeValue+'</td>';
 		     string += '<td style="padding-right:2px;text-align:center;">'+table_row[i].getElementsByTagName('team_fails')[0].firstChild.nodeValue+'</td>';
 		     string += '<td style="padding-right:2px;text-align:center;">'+table_row[i].getElementsByTagName('team_goals_shot')[0].firstChild.nodeValue+':'+table_row[i].getElementsByTagName('team_goals_got')[0].firstChild.nodeValue+'</td>';
 		     string += '<td style="padding-right:2px;text-align:center;">'+table_row[i].getElementsByTagName('team_points')[0].firstChild.nodeValue+'</td>';
 		     if(table_row[i].getElementsByTagName('team_num_matches')[0].firstChild.nodeValue > 0) {
 		     	var points = parseInt(table_row[i].getElementsByTagName('team_wins')[0].firstChild.nodeValue)*3.0+parseInt(table_row[i].getElementsByTagName('team_equals')[0].firstChild.nodeValue);
 		     	points /= (parseInt(table_row[i].getElementsByTagName('team_num_matches')[0].firstChild.nodeValue)*3.0);
 		     	points = Math.round( (points * 10000.0) ) / 100.0;
 		     	string += '<td style="padding-right:2px;text-align:center;">'+ points.toString() + '%</td>';
 		     }
 		     else
 		     	string += '<td style="padding-right:2px;text-align:center;">00.00%</td>';
 		     string += '</tr>';
 		 }
 		 string += '</table>';
 		 dropLineW3('table', string);
 		 //alert(string);
		},

		 onFailure : function(response) {
    	 alert("Oops, there's been an error.");
 		},
 		parameters: '?pin=baa4cace2a697bcec6a7a0a9f32c6ae1&game_id=' + league
	});
}

//same as init just with destination where to drop the table
function loadTable(league, dropTo) {
  var baseurl = 'http://ffb.gemura.com/ffbstats/ffb_table_grab.php';
  var start = new Date();
	var url = baseurl;
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		 var xmlResponse=response.responseXML;
 		 //alert(response.responseText);

 		 var table_row = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		 //alert(table_row.length);
 		 var string = '<table><tr>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Platz</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Mannschaft</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Spiele</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Siege</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Remis</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Niederl.</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Torverh&auml;ltnis</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Punkte</td>';
 		 string += '<td style="padding-right:2px;font-weight:bold;text-align:center;">Punkteausbeute</td>';
 		 string += '</tr>';
 		 for(var i=0;i<table_row.length;i++) {
             string += '<tr>';
             string += '<td style="padding-right:2px;font-weight:bold;text-align:left;">'+(i+1)+'</td>';
 		     string += '<td style="padding-right:2px;text-align:left;">'+table_row[i].getElementsByTagName('team_name')[0].firstChild.nodeValue+'</td>';
 		     string += '<td style="padding-right:2px;text-align:center;">'+table_row[i].getElementsByTagName('team_num_matches')[0].firstChild.nodeValue+'</td>';
 		     string += '<td style="padding-right:2px;text-align:center;">'+table_row[i].getElementsByTagName('team_wins')[0].firstChild.nodeValue+'</td>';
 		     string += '<td style="padding-right:2px;text-align:center;">'+table_row[i].getElementsByTagName('team_equals')[0].firstChild.nodeValue+'</td>';
 		     string += '<td style="padding-right:2px;text-align:center;">'+table_row[i].getElementsByTagName('team_fails')[0].firstChild.nodeValue+'</td>';
 		     string += '<td style="padding-right:2px;text-align:center;">'+table_row[i].getElementsByTagName('team_goals_shot')[0].firstChild.nodeValue+':'+table_row[i].getElementsByTagName('team_goals_got')[0].firstChild.nodeValue+'</td>';
 		     string += '<td style="padding-right:2px;text-align:center;">'+table_row[i].getElementsByTagName('team_points')[0].firstChild.nodeValue+'</td>';
 		     if(table_row[i].getElementsByTagName('team_num_matches')[0].firstChild.nodeValue > 0) {
 		     	var points = parseInt(table_row[i].getElementsByTagName('team_wins')[0].firstChild.nodeValue)*3.0+parseInt(table_row[i].getElementsByTagName('team_equals')[0].firstChild.nodeValue);
 		     	points /= (parseInt(table_row[i].getElementsByTagName('team_num_matches')[0].firstChild.nodeValue)*3.0);
 		     	points = Math.round( (points * 10000.0) ) / 100.0;
 		     	string += '<td style="padding-right:2px;text-align:center;">'+ points.toString() + '%</td>';
 		     }
 		     else
 		     	string += '<td style="padding-right:2px;text-align:center;">00.00%</td>';
 		     string += '</tr>';
 		 }
 		 string += '</table>';
 		 dropLineW3(dropTo, string);
 		 //alert(string);
		},

		 onFailure : function(response) {
    	 alert("Oops, there's been an error.");
 		},
 		parameters: '?pin=baa4cace2a697bcec6a7a0a9f32c6ae1&game_id=' + league
	});
}

function dropLineW3(divName,content)
{
  var xlayer = document.getElementById(divName);
  xlayer.innerHTML=content;
}