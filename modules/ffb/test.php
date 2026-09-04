<?php

/**
 * FFB-Module - USER AWARDS-Klasse;
 *
 * @author Gerald Musser
 * @copyright 09/2009
 * @version 0.1
 *
 */

class test extends FFB_Auth_No {

	public function __construct() {
        parent::__construct();
        //require_once('adallocator.php');
        $this->HTMLFile	=	"test.php";
        //$this->ads	=	new adallocator();
    }

    public function __default() {
    }
    
    public function testme() {
      $ads[]  = $this->advert->getAd('CommentsText');
      $ads[]  = $this->advert->getAd('CommentsText');
      $ads[]  = $this->advert->getAd('CommentsText');
      $this->adCommentText  = $ads;
       
      require_once('comments.php');
      comments::loadInto($this, 'myteam', null, DEFAULT_COMMENT_NUMBER, false);
      //require_once('adallocator.php');
        
      //print_r($this->adCommentText);
    }

	public function localhost() {
		print("hello");
		die();
	}

	public function userList()
    {
        $key = $_REQUEST["key"];
        if ($key !== "42f57f7d66c8d08e53eb1f5f0ddc59bc") {
            header('Location: http://soccer.sportsfan.at');
            die();
        }
        $criteria = new Criteria();
        $criteria->add(WebUserPeer::USER_DATE_LACTION, "2016-05-30", Criteria::GREATER_THAN);
        $criteria->addDescendingOrderByColumn(WebUserPeer::USER_DATE_LACTION);
        $users = WebUserPeer::doSelect($criteria);

        echo "(" . count($users) . ") Active Users since 2016-05-30<br><br>";
        echo "<b>last action / username</b><br>";
        foreach ($users as $user) {
            echo $user->getUserDateLaction() . " / " . utf8_decode($user->getUserNickname()) . "<br>";
        }

        echo "<br><hr><br>";
        $criteria = new Criteria();
        $criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID);

        if ($_GET["mrid"]) {
            $matchround_id = $_GET["mrid"];
        } else {
            $matchround_id = 276;
        }
		$criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $matchround_id);
		$userteams = FfbUserteamPeer::doSelect($criteria);

		echo "(" . count($userteams) . ") Lineups for round $matchround_id<br><br>";
		echo "<b>username</b><br>";
		foreach($userteams as $team) {
			echo utf8_decode($team->getWebUser()->getUserNickname()) . "<br>";
		}
		die();
	}
}

?>