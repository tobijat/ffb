function initNews() {
	dropLineW3('news_items_title', '<b>Ank&uuml;ndigungen</b>');
    getNews(1);
}

function getNews(selected_site) {
    var url = server + 'ffb/news/getNewsList.xml';
    dropLineW3('news_items', MEDIUM_LOAD);
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
 		var news = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		var num_sites = xmlResponse.getElementsByTagName('num_sites')[0].firstChild.data;
 		//alert(num_sites);
 		if(num_sites > 1) {
 		    var sites = 'Seite&ensp;';
 		} else {
 		    var sites = '';
 		}
 		if(num_sites>1) {
 			var disp_sites = num_sites;
 			if(num_sites > 9) {
 				disp_sites = 9;
 			}
 		    for(var i=0;i<disp_sites;i++) {
 		        sites += '<a style="font-family:Verdana;" href="javascript:void(0);" onClick="javascript:getNews('+(i+1)+');">'+(i+1)+'</a>&ensp;';
 		    }
 		}
        dispNews(news, sites);
		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: 'selected_site='+selected_site
	});
}

function dispNews(news, sites) {
    //alert(sites);
    var string = '<div>' + sites + '</div>';
    for(var i=0;i<news.length;i++) {
        string += '<div id="news_item">';
        if(news[i].getElementsByTagName('news_symbol')[0].firstChild.data != 0) {
            string += '<div style="float:left;">';
            string += '<img src="'+server+symbolImages_+news[i].getElementsByTagName('news_symbol')[0].firstChild.data+'" height="18px">&ensp;';
            string += '</div>';
        }
        string += '<div style="float:left;"><b>';
        string += news[i].getElementsByTagName('news_title')[0].firstChild.data;
        string += '</b></div><div style="float:right;">';
        string += news[i].getElementsByTagName('news_date')[0].firstChild.data;
        string += '</div><div style="clear:both;"></div>';
        string += news[i].getElementsByTagName('news_text')[0].firstChild.data;
        string += '</div>';
	}
	string += '<div>' + sites + '</div>';
	dropLineW3('news_items', string);
}
