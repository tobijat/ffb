
function getComments(location, matchround_id) {
    var location = location;
    var matchround_id = matchround_id;
    var url = server + 'ffb/comments/getComments.xml';
    dropLineW3('comments_1', '');
    dropLineW3('comments_2', '');
    dropLineW3('comments_3', '');
    dropLineW3('comments_4', '');

    //alert(url +'?location=' + location + '&matchround_id=' + matchround_id);
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
    //alert(response.responseText);
    var numComments = xmlResponse.getElementsByTagName('numComments')[0].firstChild.nodeValue;
    //alert(numComments);
    if(numComments<0) {
      alert('Fehlercode: ' + numComments);
      return;//Fehler
    }

    //alert(response.responseText);
    var comments;
    if(numComments>0) {
      comments  = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
    }

    var htmlOutput  = ' ';/*<div class="userComment" id="comment_box">';
        htmlOutput  +=  '<div class="userCommentHead">';
        htmlOutput  +=  '<a href="javascript:void(0);" onclick="javascript:getComments(\'' + location + '\', ' + matchround_id + ');return;">Community Meinungen aktualisieren</a><br>';
        htmlOutput  +=  ' Kommentar verfassen:</div><div class="userCommentBody">';
        htmlOutput  +=  '  <form name="addCommentForm" id="addCommentForm" >';
        htmlOutput  +=  '   <textarea name="comment_text" cols="30" rows="2" id="comment_text"></textarea></form>';
        htmlOutput  +=  '</div><div class="userCommentFooter">';
        htmlOutput  +=  '<input type="button" onclick="javascript:addComment(\'' + location + '\', ' + matchround_id + ');" value="Meinung teilen"/></div></div>';
        htmlOutput  +=  '<div id="all_comments">';                                                                                                                */

    //alert("1:" + $('comments_1').innerHTML + "2:" + $('comments_2').innerHTML + "3:" + $('comments_3').innerHTML + "4:" + $('comments_4').innerHTML +"'"); 

    for(var i=0;i<comments.length;i++) {

      var output  =  '<div class="userComment'+ i%2 + '"><div class="userCommentHead">';
      output  +=  '<img src="' + server + images_ + 'profiles/avatar/' + comments[i].getElementsByTagName('user_avatar')[0].firstChild.nodeValue +'" width="25px" /> ';
      output  +=  comments[i].getElementsByTagName('user_nick')[0].firstChild.nodeValue + ':</div>';
      output  +=  '<div class="userCommentBody">' + comments[i].getElementsByTagName('comment_text')[0].firstChild.nodeValue + '</div>';
      output  +=  '<div class="userCommentFooter">' + comments[i].getElementsByTagName('comment_date')[0].firstChild.nodeValue + '</div></div>';
      if(i<3)
        $('comments_1').innerHTML  =  $('comments_1').innerHTML + output;
      else if(i<7)
        $('comments_2').innerHTML  =  $('comments_2').innerHTML + output;
      else if(i<15)
        $('comments_3').innerHTML  =  $('comments_3').innerHTML + output;
      else
        $('comments_4').innerHTML  =  $('comments_4').innerHTML + output;
    }
    if(numComments<=0) {
      htmlOutput  +=  '<div class="userComment"><div class="userCommentHead"></div><div class="userCommentBody">keine Kommentare vorhanden</div><div class="userCommentFooter"></div></div>';
    //htmlOutput  +=  '</div>';
      dropLineW3('all_comments_1', htmlOutput);
    }


		},

		 onFailure : function(response) {
    	   handleAjaxError();
 		},
    		parameters: '?location=' + location + '&matchround_id=' + matchround_id
	});
}



function addComment(location, matchround_id) {
    var url           = server + 'ffb/comments/addComment.xml';
    var comment       = Form.serialize($('addCommentForm'));
    var comment_text  = $('comment_text').value;
    dropLineW3('comment_box', 'senden...');
    var location = location;
    var matchround_id = matchround_id;
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
 		var commentId = xmlResponse.getElementsByTagName('newCommentId')[0].firstChild.nodeValue;

    if(commentId<=0) {
      if(commentId==-1)
        dropLineW3('comment_box', 'Senden leider fehlgeschlagen, leere Kommentare sind nicht erlaubt (' + commentId + ')');
      dropLineW3('comment_box', 'Senden leider fehlgeschlagen, versuch es sp&auml;ter bitte erneut (' + commentId + ')');
      return; //Fehler
    }
    /*var comments  = $('comments_1').innerHTML;
    //alert(comments);
    var cmd  =  '<div class="userComment"><div class="userCommentHead">' +
                  'dein Kommentar:</div>' +
                  '<div class="userCommentBody">' + comment_text + '</div>' +
                  '<div class="userCommentFooter">jetzt</div></div>' + comments;

    $('comments_1').innerHTML = cmd;
    */
    var htmlOutput  = '<div class="userComment" id="comment_box">';
        htmlOutput  +=  '<div class="userCommentHead">';
        htmlOutput  +=  '<a href="javascript:void(0);" onclick="javascript:getComments(\'' + location + '\', ' + matchround_id + ');return;">Community Meinungen aktualisieren</a><br>';
        htmlOutput  +=  ' Kommentar verfassen:</div><div class="userCommentBody">';
        htmlOutput  +=  '  <form name="addCommentForm" id="addCommentForm" >';
        htmlOutput  +=  '   <textarea name="comment_text" cols="30" rows="2" id="comment_text"></textarea></form>';
        htmlOutput  +=  '</div><div class="userCommentFooter">';
        htmlOutput  +=  '<input type="button" onclick="javascript:addComment(\'' + location + '\', ' + matchround_id + ');" value="Meinung teilen"/></div></div>';
    dropLineW3('comment_box', htmlOutput);
    //load all comments eventually someone posted at same time ...
    getComments(location, matchround_id);
		},

		 onFailure : function(response) {
    	   handleAjaxError();
 		},
    		parameters: '?' + comment + '&location=' + location  + '&matchround_id=' + matchround_id
	});
}