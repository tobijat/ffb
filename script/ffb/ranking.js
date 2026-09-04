//stars
var stars_ = new Array();
stars_[0] = new Image ();
stars_[0].src = server + symbolImages_ + "sterntot.gif";
stars_[1] = new Image ();
stars_[1].src = server + symbolImages_ + "sternhalb.gif";
stars_[2] = new Image ();
stars_[2].src = server + symbolImages_ + "sternganz.gif";
stars_[3] = new Image ();
stars_[3].src = server + symbolImages_ + "sternzero.gif";

function buildStars(starRating, display) {

  var disp = new Array();
  disp['field'] = new Object();
  disp['list'] = new Object();
  disp['field'][0] = 12;
  disp['field'][1] = 60;
  disp['list'][0] = 16;
  disp['list'][1] = 80;
  if(starRating<1.0)
    return '<img src="'+stars_[3].src+'" width="'+disp[display][1]+'" alt="" title="Leistung: 0%">';
  if(starRating>=90.0)
    return '<img src="'+server + symbolImages_ +'allstar.gif" width="'+disp[display][1]+'" alt="" title="Leistung: '+starRating+'%">';

  var starLight = '';
  var count = 0;
  var grade = starRating/2.0;

  while(count<5) {
    if(grade>=10.0)
      starLight += '<img src="'+stars_[2].src+'" width="'+disp[display][0]+'" alt="" title="Leistung: '+starRating+'%">';
    else if (grade>=5.0)
      starLight += '<img src="'+stars_[2].src+'" width="'+disp[display][0]+'" alt="" title="Leistung: '+starRating+'%">';
    else if (grade>0.0)
      starLight += '<img src="'+stars_[1].src+'" width="'+disp[display][0]+'" alt="" title="Leistung: '+starRating+'%">';
    else
      starLight += '<img src="'+stars_[0].src+'" width="'+disp[display][0]+'" alt="" title="Leistung: '+starRating+'%">';
    grade = grade-10.0;
    count++;
  }
  return starLight;
}
