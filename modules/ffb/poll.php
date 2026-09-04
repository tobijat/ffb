<?php

  /**
 * poll.php
 *
 * @author Gritschacher Tobias
 * @copyright 11/2009
 * @version 0.1
 */

class poll extends FFB_Auth_User
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
		$user_id = $this->session->user_id;
		$user = WebUserDetailsPeer::retrieveByPK($user_id);
		$game_id = $user->getUserDetailsFfbSelectedGame();
		$this->text_poll = $this->getTextPoll($game_id);
		$this->select_poll = $this->getSelectPolls($game_id);
	}

	public function savePollTextAnswer() {
		//$game_id = $this->session->game_id_player;
		$user_id = $this->session->user_id;
		$poll_id = isset($_POST['poll_id']) ? $_POST['poll_id'] : null;
		$poll_answer = isset($_POST['poll_answer']) ? $_POST['poll_answer'] : null;
		$poll_answer_id = isset($_POST['poll_answer_id']) ? $_POST['poll_answer_id'] : null;

		$poll_answer_item = FfbPollAnswerPeer::retrieveByPK($poll_answer_id);
		if (!$poll_answer_item) {
			$this->status = 500;
			return;
		}
		$poll_answer_item->setPollAnswerCount($poll_answer_item->getPollAnswerCount()+1);
		$poll_answer_item->save();

		$poll_result_item = new FfbPollResult();
		$poll_result_item->setPollResultUserId($user_id);
		$poll_result_item->setPollResultPollId($poll_id);
		$poll_result_item->setPollResultPollAnswerId($poll_answer_id);
		$poll_result_item->setPollResultText($poll_answer);

		$poll_result_item->save();
		$this->status = 200;
		return;
	}

	public function savePollSelectAnswer() {
		$user_id = $this->session->user_id;
		$poll_id = isset($_POST['poll_id']) ? $_POST['poll_id'] : null;
		$poll_answer_id = isset($_POST['poll_answer_id']) ? $_POST['poll_answer_id'] : null;

		$poll_answer_item = FfbPollAnswerPeer::retrieveByPK($poll_answer_id);
		if (!$poll_answer_item) {
			$this->status = 500;
			return;
		}
		$poll_answer_item->setPollAnswerCount($poll_answer_item->getPollAnswerCount()+1);
		$poll_answer_item->save();

		$poll_result_item = new FfbPollResult();
		$poll_result_item->setPollResultUserId($user_id);
		$poll_result_item->setPollResultPollId($poll_id);
		$poll_result_item->setPollResultPollAnswerId($poll_answer_id);

		$poll_result_item->save();
		$this->status = 200;
		return;
	}

	private function getTextPoll($game_id) {
		//$game_id = $this->session->game_id_player;
		$user_id = $this->session->user_id;

		$criteria = new Criteria();
		$criteria->add(FfbPollPeer::POLL_START, date('Y-m-d H:i:s', time()), Criteria::LESS_THAN);
		$criteria->add(FfbPollPeer::POLL_END, date('Y-m-d H:i:s', time()), Criteria::GREATER_THAN);
		$criteria->add(FfbPollPeer::POLL_TYPE, 'text');
		$c1 = $criteria->getNewCriterion(FfbPollPeer::POLL_GAME_ID, 0);
		$c1->addOr($criteria->getNewCriterion(FfbPollPeer::POLL_GAME_ID, $game_id));
		$criteria->add($c1);

		$polls = FfbPollPeer::doSelect($criteria);
		if($polls) {
			$rand_poll = mt_rand(0, count($polls) - 1);
			$poll = $polls[$rand_poll];
			$criteria = new Criteria();
			$criteria->add(FfbPollResultPeer::POLL_RESULT_USER_ID, $user_id);
			$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ID, $poll->getPollId());
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

	public function getSelectPollById() {
		$user_id = $this->session->user_id;
		$poll_id = isset($_REQUEST['poll_id']) ? $_REQUEST['poll_id'] : null;
		$now = time();
		$poll = FfbPollPeer::retrieveByPK($poll_id);
		if (!$poll) {
			$this->select_poll = 0;
			return;
		}
		if(strtotime($poll->getPollEnd()) <= $now) {
			$poll_array = $this->getPollResult($poll);
		} else {
			$criteria = new Criteria();
			$criteria->add(FfbPollResultPeer::POLL_RESULT_USER_ID, $user_id);
			$finished = count($poll->getFfbPollResults($criteria));
			if(!$finished) {
				$poll_array = $this->getPollData($poll);
			} else {
				$poll_array = $this->getPollResult($poll);
			}
		}

		$poll_array['poll_next_poll_id'] = $this->getNextPrevPolls($poll_id, 'next');
		$poll_array['poll_prev_poll_id'] = $this->getNextPrevPolls($poll_id, 'prev');

		$this->select_poll = $poll_array;
	}

	private function getNextPrevPolls($poll_id, $np) {
		$user_id = $this->session->user_id;
		$user = WebUserDetailsPeer::retrieveByPK($user_id);
		$game_id = $user->getUserDetailsFfbSelectedGame();

		$criteria = new Criteria();
		$criteria->add(FfbPollPeer::POLL_TYPE, 'select');
		if($np == 'next') {
			$criteria->add(FfbPollPeer::POLL_ID, $poll_id, Criteria::GREATER_THAN);
		} else {
			$criteria->add(FfbPollPeer::POLL_ID, $poll_id, Criteria::LESS_THAN);
		}
		$criteria->add(FfbPollPeer::POLL_VISIBLE, 1);
		$criteria->add(FfbPollPeer::POLL_START, date('Y-m-d H:i:s', time()), Criteria::LESS_THAN);
		$c1 = $criteria->getNewCriterion(FfbPollPeer::POLL_GAME_ID, 0);
		$c1->addOr($criteria->getNewCriterion(FfbPollPeer::POLL_GAME_ID, $game_id));
		$criteria->add($c1);
		if($np == 'next') {
			$criteria->addAscendingOrderByColumn(FfbPollPeer::POLL_ID);
		} else {
			$criteria->addDescendingOrderByColumn(FfbPollPeer::POLL_ID);
		}
		$criteria->setLimit(1);
		$polls = FfbPollPeer::doSelect($criteria);

		if($polls) {
			return $polls[0]->getPollId();
		} else {
			return 0;
		}
	}

	public function testSelectPolls() {
		$user_id = $this->session->user_id;
		$user = WebUserDetailsPeer::retrieveByPK($user_id);
		$game_id = $user->getUserDetailsFfbSelectedGame();
		$this->select_polls = $this->getSelectPolls($game_id);
	}

	public function getSelectPolls($game_id) {
		$user_id = $this->session->user_id;

		$criteria = new Criteria();
		$criteria->add(FfbPollPeer::POLL_START, date('Y-m-d H:i:s', time()), Criteria::LESS_THAN);
		$criteria->add(FfbPollPeer::POLL_END, date('Y-m-d H:i:s', time()), Criteria::GREATER_THAN);
		$criteria->add(FfbPollPeer::POLL_TYPE, 'select');
		$criteria->add(FfbPollPeer::POLL_VISIBLE, 1);
		$c1 = $criteria->getNewCriterion(FfbPollPeer::POLL_GAME_ID, 0);
		$c1->addOr($criteria->getNewCriterion(FfbPollPeer::POLL_GAME_ID, $game_id));
		$criteria->add($c1);
		$criteria->addAscendingOrderByColumn(FfbPollPeer::POLL_END);
		$criteria->setLimit(1);

		$polls = FfbPollPeer::doSelect($criteria);

		if($polls) {
			$poll = $polls[0];
			$criteria = new Criteria();
			$criteria->add(FfbPollResultPeer::POLL_RESULT_USER_ID, $user_id);
			$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ID, $poll->getPollId());
			$finished = FfbPollResultPeer::doCount($criteria);
			if(!$finished && $poll->getPollVisible()) {
				$poll_array = $this->getPollData($poll);
			} else {
				$poll_array = $this->getPollResult($poll);
			}
			$poll_array['poll_next_poll_id'] = $this->getNextPrevPolls($poll->getPollId(), 'next');
			$poll_array['poll_prev_poll_id'] = $this->getNextPrevPolls($poll->getPollId(), 'prev');
			return $poll_array;
		} else {
			$criteria = new Criteria();
			$criteria->add(FfbPollPeer::POLL_END, date('Y-m-d H:i:s', time()), Criteria::LESS_THAN);
			$criteria->add(FfbPollPeer::POLL_TYPE, 'select');
			$c1 = $criteria->getNewCriterion(FfbPollPeer::POLL_GAME_ID, 0);
			$c1->addOr($criteria->getNewCriterion(FfbPollPeer::POLL_GAME_ID, $game_id));
			$criteria->add($c1);
			$criteria->addDescendingOrderByColumn(FfbPollPeer::POLL_END);
			$criteria->setLimit(1);
			$polls = FfbPollPeer::doSelect($criteria);
			if($polls) {
				$poll = $polls[0];
				if($poll->getPollVisible()) {
					$poll_array = $this->getPollResult($poll);
				}
				$poll_array['poll_next_poll_id'] = $this->getNextPrevPolls($poll->getPollId(), 'next');
				$poll_array['poll_prev_poll_id'] = $this->getNextPrevPolls($poll->getPollId(), 'prev');
				return $poll_array;
			} else {
				return 0;
			}
		}
	}

	private function getPollData($poll) {
		$pa = array();
		$pa['poll_id'] = $poll->getPollId();
		$pa['poll_title'] = $poll->getPollTitle();
		$pa['poll_start'] = date('d.m.Y H:i', strtotime($poll->getPollStart()));
		$pa['poll_end'] = date('d.m.Y H:i', strtotime($poll->getPollEnd()));
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

	private function getPollResult($poll) {
		$results = $poll->getFfbPollResults();
		$num_results = count($results);
		$answers = $poll->getFfbPollAnswers();
		$poll_array = array();
		$poll_array['poll_id'] = $poll->getPollId();
		$poll_array['poll_title'] = $poll->getPollTitle();
		$poll_array['poll_start'] = date('d.m.Y H:i', strtotime($poll->getPollStart()));
		$poll_array['poll_end'] = date('d.m.Y H:i', strtotime($poll->getPollEnd()));
		if(strtotime($poll->getPollEnd()) < time()) {
			$poll_array['poll_over'] = 1;
			$poll_array['poll_num_answers'] = count($poll->getFfbPollResults());
		} else {
			$poll_array['poll_over'] = 0;
			$poll_array['poll_num_answers'] = 0;
		}
		$ra = array();
		if($answers) {
			$i=0;
			foreach($answers as $answer) {
				$ra[$i]['poll_answer_id'] = $answer->getPollAnswerId();
				$ra[$i]['poll_answer_title'] = $answer->getPollAnswerTitle();
				$ra[$i]['poll_answer_count'] = $answer->getPollAnswerCount();
				if($num_results > 0) {
					$ra[$i]['poll_answer_percent'] = round($answer->getPollAnswerCount()/$num_results*100, 1);
					$ra[$i]['poll_answer_percent_round'] = round($answer->getPollAnswerCount()/$num_results*100, 0);
				} else {
					$ra[$i]['poll_answer_percent'] = 0;
					$ra[$i]['poll_answer_percent_round'] = 0;
				}
				$i++;
			}
		}
		$poll_array['poll_result'] = $ra;
		$poll_array['poll_answers'] = 0;
		if(count($ra)) {
			return $poll_array;
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
  		imagestring($img, $this->pollFontNum, 2, $yIndex/10 , mb_convert_encoding((string)$poll->getPollTitle(), 'ISO-8859-1', 'UTF-8'), $blackText);
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
			imagestring($img, $this->pollFontNum, $pollHalfSideSpace, $yIndex, mb_convert_encoding((string)$answer->getPollAnswerTitle(), 'ISO-8859-1', 'UTF-8'), $color );
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