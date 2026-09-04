<?php

/**
 * FFB-Module - Komentar-Klasse;
 *
 * @author Gerald musser
 * @copyright 07/2010
 * @version 0.1
 *
 */

class comments extends FFB_Auth_User {

    private $locationArray;
    private $maxCommentLength = 1024; //in chara
    private $maxComments      = 100;

    public function __construct() {
        parent::__construct();
        $this->locationArray[1] = 'myteam';
        $this->locationArray[2] = 'bestteam';
        $this->locationArray[3] = 'userscore';
        $this->locationArray[4] = 'lineup';
    }

    public function __default() {

    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function addComment() {
      $user_id  = $this->session->user_id;
      $game_id  = $this->session->game_id_player;
      $location = trim((string)($_POST['location'] ?? ''));
      $comment  = trim((string)($_POST['comment_text'] ?? ''));
      $matchroundId = intval($_POST['matchround_id'] ?? 0);


      if(!$user_id || !$game_id || !$location || !$comment) { //error  empty
        $this->newCommentId = -1;
        return;
      }

      if(strlen($comment)>$this->maxCommentLength) { //comment length exceedet
        $this->newCommentId = -2;
        return;
      }


      $locationOK = 0;
      for($i=1;$i<count($this->locationArray)+1;$i++) {

        if(strncmp($this->locationArray[$i], $location, strlen($this->locationArray[$i])) == 0) {
          $locationOK = $i;
          break;
        }
      }
      if($locationOK==0) {//no valid location for the post
        $this->newCommentId = -3;
        return;
      }

      $location = $this->locationArray[$locationOK];


      //start comment insert
      $newComment = new FfbComments();
      $newComment->setCommentsUserId($user_id);
      $newComment->setCommentsGameId($game_id);
      $newComment->setCommentsLocation($location);
      $newComment->setCommentsText($comment);
      $newComment->setCommentsDate(date('Y-m-d H:i:s', time()));
      if($matchroundId)
        $newComment->setCommentsMatchroundId($matchroundId);
      $newComment->save();

      $this->newCommentId = $newComment->getCommentsId();


    }



    public function getComments() {
      $location = trim((string)($_POST['location'] ?? ''));
      $matchroundID = intval($_POST['matchround_id'] ?? 0);
      $this->getCommentsParam($location, $matchroundID, $this->maxComments, true);
    }

    public function getCommentsParam($location, $matchroundId, $maxComments, $xml) {

      //$location = trim($_POST['location']);
      $game_id  = $this->session->game_id_player;
      //$matchroundID = intval($_POST['matchround_id']);
      if(!$maxComments)
        $maxComments  = $this->maxComments;


      if(!$game_id || !$location ) { //error empty
        $this->numComments = -1;
        return;
      }

      $criteria = new Criteria();

      $criteria->add(FfbCommentsPeer::COMMENTS_GAME_ID, $game_id);
      $criteria->add(FfbCommentsPeer::COMMENTS_LOCATION, $location);
      if($matchroundId>0)
        $criteria->add(FfbCommentsPeer::COMMENTS_MATCHROUND_ID, $matchroundId);

      $criteria->addJoin(FfbCommentsPeer::COMMENTS_USER_ID, WebUserPeer::USER_ID);
      $criteria->addJoin(WebUserPeer::USER_ID, WebUserDetailsPeer::USER_ID);

      $criteria->addDescendingOrderByColumn(FfbCommentsPeer::COMMENTS_DATE);
      $criteria->setLimit(intval($maxComments));


      $tmpComments = FfbCommentsPeer::doSelect($criteria);
      $comments = array();

      $i=0;
      if($tmpComments) {
        for($i=0; ($i<count($tmpComments)) && ($i<=$maxComments)  ;$i++) {
          $comments[$i]['user_nick']    = $tmpComments[$i]->getWebUser()->getUserNickname();
          $comments[$i]['user_avatar']  = $tmpComments[$i]->getWebUser()->getWebUserDetails()->getUserDetailsAvatar();
          $commentText = (string)($tmpComments[$i]->getCommentsText() ?? '');
          if($xml==false)
            $comments[$i]['comment_text'] = nl2br(htmlspecialchars(iconv("UTF-8", "ISO-8859-1", $commentText), ENT_QUOTES));
          else
            $comments[$i]['comment_text'] = nl2br(htmlspecialchars($commentText));
          //$comments[$i]['comment_text'] = utf8_encode(nl2br(htmlspecialchars($tmpComments[$i]->getCommentsText())));
          $comments[$i]['comment_date'] = $tmpComments[$i]->getCommentsDate();
          $comments[$i]['comment_id']   = $tmpComments[$i]->getCommentsId();
          $tmpComments[$i] = null;  //save memory
        }
      }
      $tmpComments  = null;



      $c = new Criteria();
      $c->add(FfbCommentsPeer::COMMENTS_LOCATION, $location);
      if($matchroundId>0)
        $c->add(FfbCommentsPeer::COMMENTS_MATCHROUND_ID, $matchroundId);

      $c->addAsColumn('numComments', 'COUNT('.FfbCommentsPeer::COMMENTS_ID.')');
      $this->numTotalComments = FfbCommentsPeer::doCount($c);

      //print_r($countComments);
      //$this->numComments  = $countComments[0]->getNumComments();//print_r($countComments);
      $c  = null;
      //print_r($this->numComments);

      //print_r($this->numComments);
      $this->numComments    = $i;
      $this->comments       = $comments;




    }

    /**
     * Load comments into another module's data (replaces legacy static calls).
     */
    public static function loadInto(FFB_Module $target, $location, $matchroundId, $maxComments, $xml) {
        $loader = new self();
        $loader->getCommentsParam($location, $matchroundId, $maxComments, $xml);
        foreach ($loader->getData() as $key => $value) {
            $target->$key = $value;
        }
    }


}
?>
