var server = location.protocol + '//' + location.host + '/';
var flagImages_ = 'images/ffb/flags/';
var symbolImages_ = 'images/ffb/symbols/';
var symbolImagesPictory_ = 'images/pictory/symbols/';
var shirtImages_ = 'images/ffb/shirts/';
var images_ = 'images/ffb/';
var imagesPictory_ = 'images/pictory/';

var GOALIE = 0;
var DEFENCE = 1;
var MIDFIELD = 2;
var STRIKER = 3;

var SERVER_STATUS_OK = 200;
var SERVER_STATUS_INSERT_OK = 201;
var SERVER_STATUS_UPDATE_OK = 202;
var SERVER_STATUS_DELETE_OK = 203;
var SERVER_STATUS_ERROR = 500;

var BIG_LOAD = '<img id="bigload" src="'+server+'images/ffb/loading/ajax-loader-big.gif" alt="loading">';
var MEDIUM_LOAD = '<img id="mediumload" src="'+server+'images/ffb/loading/ajax-loader-medium.gif" alt="loading">';
var SMALL_LOAD = '<img id="smallload" src="'+server+'images/ffb/loading/ajax-loader-small.gif" alt="loading">';
var BIG_BAR_LOAD = '<img id="bigbarload" src="'+server+'images/ffb/loading/ajax-loader-bar-big.gif" alt="loading">';
var IN_PROGRESS_LOAD = '<img id="inprogressload" src="'+server+'images/ffb/loading/ajax-loader-in-progress.gif" alt="loading">';

//***** write html to site *****
function dropLineW3(divName,content)
{
  var xlayer = document.getElementById(divName);
  xlayer.innerHTML=content;
}

//***** append html to site *****
function appendLineW3(divName,content)
{
  var xlayer = document.getElementById(divName);
  xlayer.innerHTML+=content;
}

//***** Error Handler für Ajax-Errors *****
function handleAjaxError()
{
  alert("Sorry, an error has been detected.\nClick OK and try to reload the site!");
}