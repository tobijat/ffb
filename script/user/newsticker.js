<! -- script for news ticker -- !>
<script language="JavaScript">

<!-- Begin
// news ticker function
var newslist=new Array();
var cnt=0;			// current news item
var letterCnt = 0;
var currLetter = 0;
var innerCnt = 0;
var totalLineCount = 0;
var maxLineCnt = 250;
var newsTimeout = 50;

var curr = "";

<?
  //echo "\r\n<!--\r\n";
  //print_r($this->lastResults);
  //echo "\r\n-->\r\n"; 
	if($this->lastResults[0]) {
	$index=0;
	foreach($this->lastResults AS $result) {
		echo "newslist[$index] = new Object();\r\n";
		echo "newslist[$index]['homeTeam'] = '" .$result['homeTeam'] ."';\r\n";
		echo "newslist[$index]['homeScore'] = '" .$result['homeScore'] ."';\r\n";;
		echo "newslist[$index]['homeFlag'] = '" .$result['homeFlag'] ."';\r\n";;
		echo "newslist[$index]['guestTeam'] = '" .$result['guestTeam'] ."';\r\n";;
		echo "newslist[$index]['guestScore'] = '" .$result['guestScore'] ."';\r\n";;
		echo "newslist[$index]['guestFlag'] = '" .$result['guestFlag'] ."';\r\n";;
		echo "newslist[$index]['date'] = '" .$result['date'] ."';\r\n";;
		$index++;
	}
}

?>
//TEXT - URL
//newslist[0]=new Array("Check out the share check javascript","t_sharecheck.html")

function newsticker()
{
alert('neesticker');
  if(cnt < newslist.length)
  {
    switch(innerCnt)
    {
      case 0://date
        curr = ' ' + newslist[cnt]['date'] + ' ';
        break;
      break;
      case 1:// home name
        curr = newslist[cnt]['homeTeam'];
      break;
      case 2:// img home
        curr = ' <img src="' + server + imageFlags_ + newslist[cnt]['homeFlag'] + '" alt="' + newslist[cnt]['homeFlag'] + '" /> ';
        document.getElementById('mtxt').innerHTML += curr;
        totalLineCnt += curr.length;
        setTimeout('newsticker()',newsTimeout);
        return;       
      break;
      case 3://score home
         curr = newslist[cnt]['homeScore'];
      break;
      case 4://sep ':'
        curr = ':';        
      break;
      case 5://guest score
         curr = newslist[cnt]['guestScore'];
      break;
      case 6://img guest
        curr =' <img src="' + server + imageFlags_ + newslist[cnt]['guestFlag'] + '" alt="' + newslist[cnt]['guestFlag'] + '" /> '; 
        document.getElementById('mtxt').innerHTML += curr;
        totalLineCnt += curr.length;
        setTimeout('newsticker()',newsTimeout);
        return;
      break;
      case 7://guest name
         curr = newslist[cnt]['guestTeam'];
      break;
      default:
        cnt++;
        innerCnt = 0;
        currLetter = 0;
        if(totalLineCount > maxLineCnt)
        {
          totalLineCount = 0;
          document.getElementById('mtxt').innerHTML += "\r\n<br/>\r\n";          
        }
        setTimeout('newsticker()',1);
        return;
      break;    
    }
    
    if(currLetter >= curr.length)
    {
      currLetter = 0;
      innerCnt++;
      setTimeout('newsticker()',newsTimeout);
      return;
    }
    
    document.getElementById('mtxt').innerHTML += curr[currLetter];
    currLetter++;
    totalLineCount++;
    setTimeout('newsticker()',newsTimeout); 
  }


}

</script>
