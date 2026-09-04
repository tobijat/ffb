var pictures = new Array();

function init() {
    addCategories();
    getPictureList();
}

function getPictureList() {
    pictures.clear();
    var url = server + 'administration/pic_uploadHandler/displayTempImages.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		//alert(response.responseText);
 		var items = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		var string = '';
 		var pic_count = 0;
        var last_fid = 0;
        var div = document.createElement("div");
 		div.id = 'thumbrow';
 		var maindiv = document.getElementById('mainthumbs');
 		if(items.length<1) {
 		    var divhead = document.createElement("div");
 		    divhead.style.width = '100%';
 		    divhead.innerHTML = 'No IMAGES found!';
 		    maindiv.appendChild(divhead);
 		}
 		for(var i=0;i<items.length;i++) {
 		    if(items[i].getElementsByTagName('image_fid')[0].firstChild.data != last_fid) {
 		        var divclear = document.createElement("div");
 		        divclear.style.clear = 'both';
 		        maindiv.appendChild(divclear);
 		        var divhead = document.createElement("div");
 		        divhead.style.width = '100%';
 		        divhead.innerHTML = 'IMAGES uploaded - ' + items[i].getElementsByTagName('image_uploaddate')[0].firstChild.data;
 		        maindiv.appendChild(divhead);
 		        var div = document.createElement("div");
 		        div.id = 'thumbrow';
 		        maindiv.appendChild(divclear);
 		        pic_count = 0;
 		    }
 		    last_fid = items[i].getElementsByTagName('image_fid')[0].firstChild.data;
            pictures[i] = new Object();
            pictures[i]['image_name'] = items[i].getElementsByTagName('image_name')[0].firstChild.data;
            pictures[i]['image_id'] = items[i].getElementsByTagName('image_id')[0].firstChild.data;

 		    var image = new Image();
 		    image.src = server + 'images/pictory/upload/' + items[i].getElementsByTagName('image_name')[0].firstChild.data + '.jpg';
 		    image.width = 150;
 		    image.id = 'image_' + items[i].getElementsByTagName('image_name')[0].firstChild.data;
 		    image.name = 'image_' + items[i].getElementsByTagName('image_name')[0].firstChild.data;

 		    var divthumbnail = document.createElement("div");
 		    divthumbnail.id = 'thumbnail';
 		    divthumbnail.appendChild(image);
 		    var options_string = '';
 		    options_string += '<div style="width:100%; text-align:left; font-size:9pt;"><br>';
 		    options_string += 'DateTime:<br>';
 		    if(items[i].getElementsByTagName('image_date')[0].firstChild.data == 0) {
 		        options_string += '<input style="background-color:#FF0000;" type="input" id="input_date_' + items[i].getElementsByTagName('image_name')[0].firstChild.data + '" value="' + items[i].getElementsByTagName('image_date')[0].firstChild.data + '"><br>';
 		    } else {
                options_string += '<input style="background-color:#00FF00;" type="input" id="input_date_' + items[i].getElementsByTagName('image_name')[0].firstChild.data + '" value="' + items[i].getElementsByTagName('image_date')[0].firstChild.data + '"><br>';
            }
            options_string += 'Location:<br>';
            if(items[i].getElementsByTagName('image_location')[0].firstChild.data != 0) {
                options_string += '<input type="input" id="input_location_' + items[i].getElementsByTagName('image_name')[0].firstChild.data + '" value="' + items[i].getElementsByTagName('image_location')[0].firstChild.data + '"><br>';
            } else {
                options_string += '<input type="input" id="input_location_' + items[i].getElementsByTagName('image_name')[0].firstChild.data + '" value=""><br>';
            }
            options_string += 'Mastercomment:<br>';
            if(items[i].getElementsByTagName('image_comment')[0].firstChild.data != 0) {
                options_string += '<textarea rows="5" cols="15" id="input_comment_' + items[i].getElementsByTagName('image_name')[0].firstChild.data + '">' + items[i].getElementsByTagName('image_comment')[0].firstChild.data + '</textarea><br>';
            } else {
                options_string += '<textarea rows="5" cols="15" id="input_comment_' + items[i].getElementsByTagName('image_name')[0].firstChild.data + '"></textarea><br>';
            }
            options_string += '<input type="checkbox" id="checkbox_add_' + items[i].getElementsByTagName('image_name')[0].firstChild.data + '" checked> add to album<br>';
 		    options_string += '<input type="checkbox" id="checkbox_symbol_' + items[i].getElementsByTagName('image_name')[0].firstChild.data + '"> set as symbol<br>';
 		    options_string += '<a href="javascript:deleteImage(' + items[i].getElementsByTagName('image_id')[0].firstChild.data + ');"><img src="'+server+'images/pictory/symbols/delete.png" border="0"></a> delete';
 		    options_string += '</div>';
            divthumbnail.innerHTML += options_string;
 		    div.appendChild(divthumbnail);
 		    maindiv.appendChild(div);

 		    if(pic_count == 5) {
 		        var div = document.createElement("div");
 		        div.id = 'thumbrow';
 		        var divclear = document.createElement("div");
 		        divclear.style.clear = 'both';
 		        maindiv.appendChild(divclear);
 		        pic_count = 0;
 		    }
            pic_count++;
		}

		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});
}

function uncheckAll() {
    for(var i=0;i<pictures.length;i++) {
        var box = document.getElementById('checkbox_add_'+pictures[i]['image_name']);
        box.checked = false;
	}
}

function checkAll() {
    for(var i=0;i<pictures.length;i++) {
        var box = document.getElementById('checkbox_add_'+pictures[i]['image_name']);
        box.checked = true;
	}
}

function deleteAllImages() {
    check = confirm('Do you really want to delete ALL images?');
    if(check) {
        var del_counter = 0;
        for(var i=0;i<pictures.length;i++) {
            var id = pictures[i]['image_id'];
            var url = server + 'administration/pic_uploadHandler/deleteImage.xml';
	        new Ajax.Request(url, {
 		        onSuccess : function(response) {
 		        var xmlResponse=response.responseXML;
 		        //alert(response.responseText);
 		        var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.nodeValue;
 		        if(status == 203) {
 		            del_counter++;
 		        }
 		        if(del_counter == pictures.length) {
                    var maindiv = document.getElementById('mainthumbs');
 		            maindiv.innerHTML = '';
 		            getPictureList();
 		        }
            },

		    onFailure : function(response) {
    	    alert("Oops, there's been an error.");
 		    },
 		    parameters: '?pid='+id
	        });
	    }
    }
}

function deleteImage(id) {
    check = confirm('Do you really want to delete this image?');
    if(check) {
        var url = server + 'administration/pic_uploadHandler/deleteImage.xml';
	    new Ajax.Request(url, {
 		    onSuccess : function(response) {
 		    var maindiv = document.getElementById('mainthumbs');
 		    maindiv.innerHTML = '';
 		    getPictureList();

        },

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?pid='+id
	    });
    }
}

function addCategories() {
    var url = server + 'administration/pic_category/getList.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {

 		var xmlResponse=response.responseXML;
 		var categories = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
 		for(var i=0;i<categories.length;i++) {
 		    if(document.create_album_form.album_category_post.value == categories[i].getElementsByTagName('category_id')[0].firstChild.data)
                selected = true;
            else
                selected = false;

 		    newOption = new Option(categories[i].getElementsByTagName('category_title')[0].firstChild.data, categories[i].getElementsByTagName('category_id')[0].firstChild.data, false, selected);
 		    document.create_album_form.album_category.options[document.create_album_form.album_category.length] = newOption;
		}

		},

		onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});
}

function validateAlbumData () {
    var errorstring = '';
    var answerdiv = document.getElementById('createalbum_answer');
    answerdiv.innerHTML = 'please wait...';

    var symbol_count = 0;
    var image_count = 0;
    for(var i=0;i<pictures.length;i++) {
        if(document.getElementById('checkbox_symbol_'+pictures[i]['image_name']).checked)
            symbol_count++;
        if(document.getElementById('checkbox_add_'+pictures[i]['image_name']).checked)
            image_count++;
	}
	//check symbol
	if(symbol_count != 1) {
	   errorstring += '* You have to choose exactly one image as album-symbol!<br>';
	}
	//check added images
	if(image_count < 1) {
	   errorstring += '* You have to choose at least one image to add!<br>';
	}
    var album_title = document.create_album_form.album_title.value;
    var album_date_day = document.create_album_form.album_date_day.options[document.create_album_form.album_date_day.selectedIndex].value;
    var album_date_month = document.create_album_form.album_date_month.options[document.create_album_form.album_date_month.selectedIndex].value;
    var album_date_year = document.create_album_form.album_date_year.options[document.create_album_form.album_date_year.selectedIndex].value;
    var album_category = document.create_album_form.album_category.options[document.create_album_form.album_category.selectedIndex].value;
    //alert(album_title + ' ' + album_date_day + ' ' + album_date_month + ' ' + album_date_year + ' ' + album_category);
    //check for needed fields
    if(!album_title || !album_date_day || !album_date_month || !album_date_year || !album_category) {
        errorstring += '* You have to fill out all fields marked with a *!<br>';
    }

    if(errorstring) {
 	    var error = document.createElement("div");
 	    error.id = 'formerror';
 	    error.innerHTML = errorstring;
 	    answerdiv.innerHTML = '';
 	    answerdiv.appendChild(error);
    } else {
        //addImagesToAlbum();
        createAlbum();
    }
}

function addImagesToAlbum(album_id, album_name) {
 	var string = '';
 	var pic_count = 1;
 	var url = server + 'administration/pic_uploadHandler/addImageToAlbum.xml';
 	for(var i=0;i<pictures.length;i++) {
 	    var checked = document.getElementById('checkbox_add_' + pictures[i]['image_name']).checked;
 	    if(checked) {
 	      var comment = document.getElementById('input_comment_' + pictures[i]['image_name']).value;
 	      var location = document.getElementById('input_location_' + pictures[i]['image_name']).value;
 	      var date = document.getElementById('input_date_' + pictures[i]['image_name']).value;
 	      if(document.getElementById('checkbox_symbol_' + pictures[i]['image_name']).checked) {
 	          var symbol = 1;
 	      } else {
 	          var symbol = 0;
 	      }
 	      var params = '?image_id='+pictures[i]['image_id']+'&image_album='+album_id+'&sortflag='+i+'&image_album_name='+album_name+'&location='+location+'&comment='+comment+'&date='+date+'&symbol='+symbol;
 	      new Ajax.Request(url, {
     		    onSuccess : function(response) {
     		    alert(response.responseText);
     		    var xmlResponse=response.responseXML;
     		    string += pic_count + '.) ' + xmlResponse.getElementsByTagName('administration_answer')[0].firstChild.nodeValue + '<br>';

         	    if(pic_count == pictures.length) {
 	                var answerdiv = document.getElementById('createalbum_answer');
         	        var answer = document.createElement("div");
                    answer.id = 'formanswer';
         		    answer.innerHTML = string;
                    answerdiv.innerHTML = '';
     	            answerdiv.appendChild(answer);
     	            getPictureList();
         	    }
         	    pic_count++;
            },

    		onFailure : function(response) {
        	alert("Oops, there's been an error.");
     		},
     		parameters: params
    	    });
 	    }
 	}
}

function createAlbum() {
    var url = server + 'administration/pic_uploadHandler/createNewAlbum.xml';
    new Ajax.Request(url, {
     	onSuccess : function(response) {
     	    var xmlResponse=response.responseXML;
 		    //alert(response.responseText);
 		    var status = xmlResponse.getElementsByTagName('administration_status')[0].firstChild.nodeValue;
 		    if(status == 200) {
 		        var album_id = xmlResponse.getElementsByTagName('new_id')[0].firstChild.nodeValue;
 		        var album_name = xmlResponse.getElementsByTagName('album_name')[0].firstChild.nodeValue;
 		        addImagesToAlbum(album_id, album_name);
 		    } else {
 		        var answerdiv = document.getElementById('createalbum_answer');
     	        var error = document.createElement("div");
                error.id = 'formerror';
     		    var string = xmlResponse.getElementsByTagName('administration_answer')[0].firstChild.nodeValue;
     		    error.innerHTML = string;
                answerdiv.innerHTML = '';
 	            answerdiv.appendChild(error);
 	        }
        },
    	onFailure : function(response) {
        alert("Oops, there's been an error.");
     	},
     	parameters: Form.serialize($("create_album_form"))
    });
}
