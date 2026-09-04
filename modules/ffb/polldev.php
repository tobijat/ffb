<?php

  /**
 * poll.php
 *
 * @author Gritschacher Tobias
 * @copyright 11/2009
 * @version 0.1
 */

class polldev extends FFB_Auth_User
{
	private $pollPicWidth	=	150;
	private $pollMaxWidth	=	768;
	private	$poll100Index	=	120;
	private	$pollSideSpace	=	60;
	private $pollBarThickness=	10;
	private $pollFontNum	=	3;
	
    public function __construct() {
        parent::__construct();
    }

    public function __default() {

    }

	public function getPolls() {
		$this->text_poll = $this->getTextPoll();
		$this->select_poll = $this->getSelectPoll();
	}

	public function savePollTextAnswer() {
		$game_id = $this->session->game_id_player;
		$user_id = $this->session->user_id;
		$poll_id = $_POST['poll_id'];
		$poll_answer = $_POST['poll_answer'];
		$poll_answer_id = $_POST['poll_answer_id'];

		$poll_answer_item = FfbPollAnswerPeer::retrieveByPK($poll_answer_id);
		$poll_answer_item->setPollAnswerCount($poll_answer_item->getPollAnswerCount()+1);

		$poll_result_item = new FfbPollResult();
		$poll_result_item->setPollResultUserId($user_id);
		$poll_result_item->setPollResultPollId($poll_id);
		$poll_result_item->setPollResultPollAnswerId($poll_answer_id);
		$poll_result_item->setPollResultText($poll_answer);

		$poll_result_item->save();
		return;
	}

	private function getTextPoll() {
		$game_id = $this->session->game_id_player;
		$user_id = $this->session->user_id;

		$criteria = new Criteria();
		$criteria->add(FfbPollPeer::POLL_START, date('Y-m-d H:i:s', time()), Criteria::LESS_THAN);
		$criteria->add(FfbPollPeer::POLL_END, date('Y-m-d H:i:s', time()), Criteria::GREATER_THAN);
		$criteria->add(FfbPollPeer::POLL_TYPE, 'text');
		$c1 = $criteria->getNewCriterion(FfbPollPeer::POLL_GAME_ID, 0);
		$c1->addOr($criteria->getNewCriterion(FfbPollPeer::POLL_GAME_ID, $game_id));
		$criteria->add($c1);
		//TODO: random einbauen?
		$criteria->setLimit(1);
		//**
		$polls = FfbPollPeer::doSelect($criteria);
		if($polls) {
			$poll = $polls[0];
			$criteria = new Criteria();
			$criteria->add(FfbPollResultPeer::POLL_RESULT_USER_ID, $user_id);
			$finished = FfbPollResultPeer::doCount($criteria);
			if(!$finished && $poll->getPollVisible()) {
				$poll_array = $this->getPollData($poll);
			} else {
				return 0;
			}

			return $poll_array;
		} else {
			return 0;
		}
	}

	private function getSelectPoll() {
		$game_id = $this->session->game_id_player;
		$user_id = $this->session->user_id;

		$criteria = new Criteria();
		$criteria->add(FfbPollPeer::POLL_START, date('Y-m-d H:i:s', time()), Criteria::LESS_THAN);
		$criteria->add(FfbPollPeer::POLL_END, date('Y-m-d H:i:s', time()), Criteria::GREATER_THAN);
		$criteria->add(FfbPollPeer::POLL_TYPE, 'select');
		$c1 = $criteria->getNewCriterion(FfbPollPeer::POLL_GAME_ID, 0);
		$c1->addOr($criteria->getNewCriterion(FfbPollPeer::POLL_GAME_ID, $game_id));
		$criteria->add($c1);
		//TODO: random einbauen?
		$criteria->setLimit(1);
		//**
		$polls = FfbPollPeer::doSelect($criteria);
		if($polls) {
			$poll = $polls[0];
			$criteria = new Criteria();
			$criteria->add(FfbPollResultPeer::POLL_RESULT_USER_ID, $user_id);
			$finished = FfbPollResultPeer::doCount($criteria);
			if(!$finished && $poll->getPollVisible()) {
				$poll_array = $this->getPollData($poll);
			} else {
				return 0;
			}

			return $poll_array;
		} else {
			return 0;
		}
	}

	private function getPollData($poll) {
		$pa = array();
		$pa['poll_id'] = $poll->getPollId();
		$pa['poll_title'] = $poll->getPollTitle();
		$pa['poll_start'] = $poll->getPollStart();
		$pa['poll_end'] = $poll->getPollEnd();
		$pa['poll_type'] = $poll->getPollType();
		$pa['poll_location'] = $poll->getPollLocation();
		$pa['poll_answers'] = $this->getPollAnswers($poll);

		return $pa;
	}

	private function getPollAnswers($poll) {
		$answers = $poll->getFfbPollAnswers();
		$answer_array = array();
		if($answers) {
			$i=0;
			foreach($answers as $answer) {
				$answer_array[$i]['poll_answer_id'] = $answer->getPollAnswerId();
				$answer_array[$i]['poll_answer_title'] = $answer->getPollAnswerTitle();
				$answer_array[$i]['poll_answer_count'] = $answer->getPollAnswerCount();
				$i++;
			}
		}
		if(count($answer_array)) {
			return $answer_array;
		} else {
			return 0;
		}

	}
	
	public function getPollImage() {
		$pollId	=	intval($_GET['poll_id']);
		if(intval($_GET['poll_index']) && (intval($_GET['poll_index']) < ($this->pollMaxWidth-$this->pollSideSpace) ) ) {
			$this->poll100Index	=	intval($_GET['poll_index']);
			$this->pollPicWidth	=	intval($_GET['poll_index']) + $this->pollSideSpace;
		}
		
		if(!$pollId)
			return;
		$poll	=	FfbPollPeer::retrieveByPK($pollId);
		if(!$poll)
			return;
		$criteria	=	new Criteria();
		$criteria->add(FfbPollAnswerPeer::POLL_ANSWER_POLL_ID, $poll->getPollId());
		$criteria->addDescendingOrderByColumn(FfbPollAnswerPeer::POLL_ANSWER_COUNT);
		$answers	=	FfbPollAnswerPeer::doselect($criteria);
		$totalCount	=	0;
		$answerCount=	0;
		foreach($answers AS $answer) {
			$totalCount	+=	intval($answer->getPollAnswerCount());
			$answerCount++;
		}
		
		
		$pollHalfSideSpace	=	intval ($this->pollSideSpace / 2.0);
		$yIndex	=	30;
		$index	=	1;
		
		
		$img = imagecreatetruecolor ( $this->pollPicWidth, ($answerCount*($this->pollBarThickness+5)*$this->pollFontNum+50) );
		$redText = imagecolorallocate ( $img ,210 , 0 , 0);
		$redBg = imagecolorallocate ( $img ,240 , 0 , 0);
		$blackText = imagecolorallocate ( $img ,0 , 0 , 0);
		$darkBlueBG = imagecolorallocate ( $img ,25 , 25 , 225);
		$someKindBlueBG = imagecolorallocate ( $img ,175 , 240 , 240);
  		$filled = imagefill ( $img , 0 , 0 , $someKindBlueBG );
  		imagestring($img, $this->pollFontNum, 2, $yIndex/10 , $poll->getPollTitle(), $blackText);
  		imageline($img, $pollHalfSideSpace, $yIndex-3, $this->pollPicWidth-$pollHalfSideSpace, $yIndex-3, $darkBlueBG);
  		
		
  		foreach($answers AS $answer) {
  			
  			if($totalCount)
  				$percentage	=	$answer->getPollAnswerCount()	/	$totalCount;
			else
				$percentage	=	0;
  			$fillSize	= max(1 , intval($percentage * $this->poll100Index) ); //draw atleast 1px
  			$color	=	$darkBlueBG;
  			if($index==1)
  				$color	=	$redBg;
			imagestring($img, $this->pollFontNum, 5, $yIndex, "$index.", $color);
			imagestring($img, $this->pollFontNum, $pollHalfSideSpace, $yIndex, $answer->getPollAnswerTitle(), $color );
			$yIndex		+=	10;
  			imagefilledrectangle($img, $pollHalfSideSpace, $yIndex+$this->pollFontNum, $pollHalfSideSpace+$fillSize, $yIndex+$this->pollBarThickness+$this->pollFontNum, $color );
  			$percentage	=	round($percentage*100,0);
  			imagestring($img, $this->pollFontNum, $pollHalfSideSpace+$fillSize+3, $yIndex+$this->pollFontNum/2, "$percentage% (" . $answer->getPollAnswerCount() . ")" , $color);
  			
  			$yIndex		+= $this->pollBarThickness+5+$this->pollFontNum*3;
  			$index++;
  		}
  		imageline($img, $pollHalfSideSpace, $yIndex+5, $this->pollPicWidth-$pollHalfSideSpace, $yIndex+5, $darkBlueBG);
  		imagestring($img, $this->pollFontNum, 2, $yIndex+10, "Gesamt $totalCount Stimme(n)", $blackText);
  		
    	$this->pollImg = $img;		
	}

}

?>