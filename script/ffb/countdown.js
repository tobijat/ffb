var svr= location.protocol + '//' + location.host + '/';
var nums = svr + 'images/ffb/countdown/';
var imgF = '<img src="'+nums;
var imgB = ' height="12" style="margin-left:1px">';
var lastminute = 43200;//s == 12h
var timeout_ = 1000;
//var ffDummyVar_ = new Array();
var alls_ = new Image()
var allr_ = new Image();
alls_.src = nums + 'alls.gif';
allr_.src = nums + 'allr.gif';

var runme = true;
var deadlines = new Array()

function loadMe() {

	var url = svr + 'ffb/countdown/countdown.xml';
	var numRounds = 0;
    var myTime = 0; //new Date(document.getElementById('now').value*1000);
    var toDisplay = '<div  style="float:left; padding-left:5px;"><img src="' +  nums + 'france16_353.png" height="60px"/></div>'+'<div style="float:left;">' + '<div class="CountdownEntry">';
	toDisplay += '<div class="CountdownLeft" style="font-size:11pt;"><b>Deadlines</b></div>';
	toDisplay += '<div class="CountdownRight" style="font-size:11px;"><i>(DDD/HH/MM/SS)</i></div>';
	toDisplay += '<div style="clear:both;">';
	toDisplay += '</div></div>'+"\r\n";
	new Ajax.Request(url, {
 		onSuccess : function(response) {
 		var xmlResponse=response.responseXML;
 		if(xmlResponse.getElementsByTagName('stop')[0].firstChild.nodeValue=="1")
 		  return;
 		if(xmlResponse.getElementsByTagName('numRounds')[0].firstChild.nodeValue!=0) {
			numRounds = xmlResponse.getElementsByTagName('numRounds')[0].firstChild.nodeValue;
			var time = parseInt(xmlResponse.getElementsByTagName('myTime')[0].firstChild.nodeValue)*1000;
			myTime = new Date(time);
			var round = xmlResponse.getElementsByTagName('XML_Serializer_Tag');
			for(var i=0;i<round.length;i++) {
				toDisplay += '<div class="CountdownEntry" id="cde'+ i+'">';
				toDisplay += '<div class="CountdownLeft">'+ round[i].getElementsByTagName('name')[0].firstChild.nodeValue +'</div>';
				toDisplay += '<div class="CountdownRight">';
				toDisplay += '&nbsp;';
				toDisplay += '<div class="cd" id="3'+i+'"></div>';
				toDisplay += '&nbsp;';
				toDisplay += '<div class="cd" id="2'+i+'">&nbsp;</div>';
				toDisplay += '&nbsp;';
				toDisplay += '<div class="cd" id="1'+i+'"></div>';
				toDisplay += '&nbsp;';
				toDisplay += '<div class="cd" id="01'+i+'"><div class="cd" id="0'+i+'"></div></div>';
				toDisplay += '</div>'+"\r\n";
				toDisplay += '<div style="clear:both;"></div>';
				toDisplay += '</div>';
				var ende = new Date(parseInt(round[i].getElementsByTagName('date')[0].firstChild.nodeValue)*1000);
				var tmp = new Date();
				var localdiff = Math.floor(myTime.getTime() - tmp.getTime());
				deadlines[i] = new Object();
				deadlines[i]['first'] = true;
				deadlines[i]['end'] = new Date(ende.getTime()+localdiff);
				deadlines[i]['name'] = round[i].getElementsByTagName('name')[0].firstChild.nodeValue;
				deadlines[i]['d'] = -1;
				deadlines[i]['h'] = -1;
				deadlines[i]['m'] = -1;
				deadlines[i]['s'] = -1;

			}
			//toDisplay += '<!--br><br><div class="Countdownentry"><a href="javascript:stopMe();" title="Countdown Ausblenden">Countdown ausblenden</a></div-->' + "\r\n";
			dropLineW3('Countdown', toDisplay+'</div>');
			countdown();
		}
		},

		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		}
	});
}


function formatTime(sec, index) {
	var time = sec-1;
	var lminute = 0;
	if(time<0) {
	  dropLineW3("0"+index.toString(), "00");
	  return 0;
	}

	if(time<lastminute){
	  lminute = 1;
	  	var id = 'cde' + index.toString();
		$(id).setStyle({
			backgroundColor: '#CCFFCC',
  			'color': '#d00000'
		});
	} else {
    var id = 'cde' + index.toString();
		$(id).setStyle({
			backgroundColor: '#CCFFCC',
  			'color': '#000000'
		});
  }

	var seconds = time % 60;
	if(seconds!=deadlines[index]['s']) {
		buildTime(seconds,0,index, lminute);
		deadlines[index]['s'] = seconds;
	}
	var minutes = Math.floor((time/60)) % 60;
	if(minutes!=deadlines[index]['m']) {
		buildTime(minutes,1,index, lminute);
		deadlines[index]['m'] = minutes;
	}
	var hours = Math.floor((time/60/60)) % 24;
	if(hours!=deadlines[index]['h']) {
		buildTime(hours,2,index, lminute);
		deadlines[index]['h'] = hours;
	}
	var days = Math.floor((time/60/60/24)) % 365;
	var years = Math.floor((time/60/60/24/365));
	if(years>0) {
		days += years*365;
	}
	if(days!=deadlines[index]['d']) {
		buildTime(days,3,index, lminute);
		deadlines[index]['d'] = days;
	}
	return 1;
}

function buildTime(time, num, index, lminute) {
	toDisplay = '';
	var nm = num;
	var tm = time;
	var nr = tm % 10; // erste Stelle

	if(nm=="0") {
		if(lminute=="1"){
		  if(nr==9){
		  	if(deadlines[index]['first'])
		      toDisplay = imgF + 'allr.gif" id="im'+ index +'" '+imgB + "\r\n";
		    else{
		      $("im"+index).src = allr_.src;
		      return;
		    }
			timeout_ = 10000;
			deadlines[index]['first'] = false;
		  } else {
		    toDisplay = imgF + nr +'ra.gif" id="im'+ index+'" ' +imgB + "\r\n";
		    deadlines[index]['first'] = false;
		  }
		}
		else {
		  if(nr==9){
		  	if(deadlines[index]['first'])
		      toDisplay = imgF + 'alls.gif" id="im'+ index +'" '+imgB + "\r\n";
		    else {
		      $("im"+index).src = alls_.src;
              return;
		    }
			timeout_ = 10000;
			deadlines[index]['first'] = false;
		  } else {
		    toDisplay = imgF + nr +'sa.gif" id="im'+ index+'" ' +imgB + "\r\n";
		    deadlines[index]['first'] = false;
		  }
		}
	}
	else
		toDisplay = nr.toString();//imgF + nr +imgB + "\r\n";
	tm = (tm-nr)/10; // zweite
	nr = tm%10;
	toDisplay = nr.toString()+ toDisplay + "\r\n";//imgF + nr +imgB + toDisplay + "\r\n";
	if(nm=="3") {
		tm = (tm - nr)/10; // dritte
		nr = tm % 10;
		if(nr<1)
	      nr=0;
		toDisplay = nr.toString()+ toDisplay + "\r\n";//imgF + nr +imgB + toDisplay + "\r\n";
	}
	var id = num.toString(10)
	id += index.toString(10);
	dropLineW3(id, toDisplay);

}


function countdown() {
	var count = 0;
	for(var i=0;i<deadlines.length;i++) {
		var tmpTime = new Date();
		var diff = Math.floor((deadlines[i]['end'].getTime()-tmpTime.getTime())/1000);
		if(diff >0) {
			count += formatTime(diff, i);
		}
	}

	if(count && runme)
	 setTimeout('countdown()',timeout_);
	else
	  dropLineW3('Countdown', ' ');

}

function stopMe() {
	runme = false;
	var url = svr + 'ffb/countdown/stopCounter.xml';
	new Ajax.Request(url, {
 		onSuccess : function(response) {
		},
		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters: '?stop=stop'
	});

}


//***** write html to site *****
/*
function dropLineW3(divName,content)
{
    //alert(divName);
  var xlayer = document.getElementById(divName);
  xlayer.innerHTML=content;
}
*/