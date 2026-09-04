<?php

/**
 * ADMIN - AWARDS-Klasse;
 * AWARDS hinzufügen/ändern/löschen - an facebook senden
 *
 * @author Gerald Musser
 * @copyright 10/2009
 * @version 0.5
 *
 */
require_once('modules/ffbapi/facebook-platform/php/facebook.php');

class awards extends FFB_Auth_AdminFfb {

	private $automaticUpdates 		=	1;
	private $fbAppKey	 			= 	FFB_FACEBOOK_API_KEY;
	private $fbAppSecret 			=	FFB_FACEBOOK_APP_SECRET;
	private $fbConnect				= 	null;
	private $localUserUpdates		=	0;
	private $goalFinishers			= 	null;
	private $fbInfiniteSessionKey	=	FFB_FACEBOOK_SESSION_KEY;
	private $fbSessionExpires		=	0;
	private $fbUserId				=	FFB_FACEBOOK_USER_ID;

	public function __construct() {
        parent::__construct();

        $this->htmlFile = 'awards.php';
        $this->goalFinishers =	array();
        if($_POST['newgroupawardname']) {
        	$this->createNewAwardGroup();
        	$this->getAwardGroups();
        }


        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(WebUserPeer::USER_ID);
        $this->allusers = WebUserPeer::doSelect($criteria);

    }

    private function getAwardGroups() {
    	$criteria = new Criteria();
    	$criteria->addAscendingOrderByColumn(FfbUserAwardPeer::USER_AWARD_NAME);
    	$this->awardGroups = FfbUserAwardPeer::doSelect($criteria);
    }

    public function getAwards() {
        $awardTitle 			= 	FfbUserAwardPeer::retrieveByPK($_REQUEST['user_award_id']);
        if(!$awardTitle)
        	return;
		$tmp[0]['name']			=	$awardTitle->getUserAwardName();
		$tmp[0]['id']			= 	$awardTitle->getUserAwardId();
		$tmp[0]['description']	=	($awardTitle->getUserAwardDescription())	?	$awardTitle->getUserAwardDescription()	:	" ";
		$tmp[0]['image']		=	($awardTitle->getUserAwardImage())			?	$awardTitle->getUserAwardImage()		:	" ";
		$this->userAward		= 	$tmp;

		$criteria = new Criteria();
		$criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID, $_REQUEST['user_award_id']);
		$criteria->addAscendingOrderByColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK);
		$awardList = FfbUserAwardDefinesPeer::doSelect($criteria);
		$tmp = array();
		$index = 0;
		foreach($awardList AS $listElem) {
			$tmp[$index]['id']		=	$listElem->getUserAwardDefinesId();
			$tmp[$index]['rank']	=	($listElem->getUserAwardDefinesRank()) 			? $listElem->getUserAwardDefinesRank() 			: " ";
			$tmp[$index]['name']	=  	($listElem->getUserAwardDefinesRankName()) 		? $listElem->getUserAwardDefinesRankName() 		: " ";
			$tmp[$index]['aim']		= 	($listElem->getUserAwardDefinesAim()) 			? $listElem->getUserAwardDefinesAim() 			: " ";
			$tmp[$index]['dbtable']	= 	($listElem->getUserAwardDefinesAimDbtable())	? $listElem->getUserAwardDefinesAimDbtable()	: " ";
			$tmp[$index]['operator']= 	($listElem->getUserAwardDefinesAimOperator())	? $listElem->getUserAwardDefinesAimOperator()	: " ";
			$tmp[$index]['count']	= 	($listElem->getUserAwardDefinesAimCount()>0)	? $listElem->getUserAwardDefinesAimCount()  	: 0;
			$tmp[$index]['auto']	= 	($listElem->getUserAwardDefinesAimAutomatic()>0)? $listElem->getUserAwardDefinesAimAutomatic()	: 0;
			$tmp[$index]['image']	= 	($listElem->getUserAwardDefinesImage())			? $listElem->getUserAwardDefinesImage()			: " ";
			$tmp[$index]['descr']	= 	($listElem->getUserAwardDefinesDescription())	? $listElem->getUserAwardDefinesDescription()	: " ";
			$tmp[$index]['fbdescr']	= 	($listElem->getUserAwardDefinesFacebookDescription())?$listElem->getUserAwardDefinesFacebookDescription():" ";
			$index++;
		}
		$this->userAwardDefines		=	$tmp;
		$this->userAwardCounts		=	$index;
    }

    private function createNewAwardGroup() {
    	$groupAwardName = trim($_POST['newgroupawardname']);
    	$errors = array();
    	if(!$groupAwardName) {//leere Gruppenname
    		$errors[] = "Kein Gruppenname angegeben, Abbruch.";
    		$this->errors = $errors;
    		return;
    	}
    	$criteria = new Criteria();
    	$criteria->add(FfbUserAwardPeer::USER_AWARD_NAME, $groupAwardName);
    	$existingAward = FfbUserAwardPeer::doSelect($criteria);
    	if($existingAward) { //Gruppe existiert bereits
    		$errors[] = "Gruppe '$groupAwardName' existiert bereits. Abbruch.";
    		$this->errors = $errors;
    		return;
    	}

    	//Gruppe anlegen
    	$newGroup = new FfbUserAward();
    	$newGroup->setUserAwardName($groupAwardName);
    	$newGroup->save();
    	$this->administration_answer = "Neue Gruppe: '$groupAwardName' erfolgreich angelegt.";



    }

    private function deleteAwardGroup() {

    }

    private function deleteAward() {

    }

    public function getOutstandingAwardsToFb() {
    	//from constructor
		$this->allusers		=	'';

		$criteria	=	new Criteria();

    	$criteria->addJoin(WebUserPeer::USER_ID, FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, Criteria::LEFT_JOIN);
    	$criteria->addJoin(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID, Criteria::INNER_JOIN);
    	$criteria->addJoin(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID, FfbUserAwardPeer::USER_AWARD_ID, Criteria::INNER_JOIN);
		$criteria->add(WebUserPeer::USER_FACEBOOK_ID, NULL, Criteria::NOT_EQUAL);
		$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_FACEBOOK_STREAM_ID, NULL, Criteria::EQUAL);
		$criteria->addAscendingOrderByColumn(FfbUserAwardPeer::USER_AWARD_ID);
		$criteria->addAscendingOrderByColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK);
		$criteria->addAscendingOrderByColumn(WebUserPeer::USER_NICKNAME);

		$notSendAwards	=	FfbUserAwardFinishedPeer::doSelect($criteria);
		$count			=	0;
		$tmp			=	array();
		//print_r($notSendAwards);

		if($notSendAwards[0]->getUserAwardFinishedId()) {
			foreach($notSendAwards AS &$notSend) {
				$webUser				=	$notSend->getWebUser();
				$tmp[$count]['user_id']			=	$webUser->getUserId();
				$tmp[$count]['user_nickname']	=	$webUser->getUserNickname();

				$awardInfo				=	$notSend->getFfbUserAwardDefines();
				$tmp[$count]['award_rank']		=	$awardInfo->getUserAwardDefinesRankName();

				$awardInfo				=	$awardInfo->getFfbUserAward();
				$tmp[$count]['award_name']		=	$awardInfo->getUserAwardName();

				$tmp[$count]['user_award_finished_id']	=	$notSend->getUserAwardFinishedId();

				//'free' memory'
				$notSend				=	null;


				//$tmp[]			=	$notSend->getWebUser();
				$count++;
			}
		}
		//print_r($notSendAwards);

		$this->notSendIndex	=	$count;
		$this->notSendAwards=	$tmp;


		//$this->tmp = $notSendAwards;

		// [wrapped: Could not build SQL for expression: web_user.USER_FACEBOOK_ID NOT_EQUAL NULL]

    }


    public function createAward() {
    	$name 		=	trim($_REQUEST['award_name']);
    	$groupID 	=	trim($_REQUEST['group_award_id']);
    	$rank		=	trim($_REQUEST['award_rank']);
    	$aim		=	trim($_REQUEST['award_aim']);
    	$count		= 	(!trim($_REQUEST['award_count']))	? 0 : trim($_REQUEST['award_count']);
    	$auto		=	(trim($_REQUEST['award_auto'])==1) 	? 1 : 0;
    	$DBTable	=	trim($_REQUEST['award_dbtable']);
    	$operator	= 	trim($_REQUEST['award_operator']);
    	$descr		=	trim($_REQUEST['award_description']);
    	$fbdescr	=	trim($_REQUEST['award_fb_description']);
    	$image		=	trim($_REQUEST['award_image']);
    	$criteria 	=	new Criteria();
    	$criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID, $groupID);
    	$criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK_NAME, $name);
    	$awards 	= 	FfbUserAwardDefinesPeer::doSelect($criteria);

    	if($awards) {
    		if(!$name)
    		$tmp['status']	=	'500';
    		$tmp['text']	=	"Auszeichnung '$name' existiert bereits.<br/>$name / $groupID / $rank / $aim / $count / $auto / $DBTable / $operator /";
    		$this->answer	=	$tmp;
    		return;
    	}

    	if(!$name || !$groupID || !$rank || !$aim || !$DBTable) {
    		$tmp['status']	=	'500';
    		$tmp['text']	=	"Auszeichnung '$name' konnte nicht angelegt werden fehlerhafte (leere) Eingabe.<br/>$name / $groupID / $rank / $aim / $count / $auto / $DBTable / $operator /";
    		$this->answer	=	$tmp;
    		return;
    	}

    	$newAward 	=	new FfbUserAwardDefines();
    	$newAward->setUserAwardDefinesAwardId($groupID);
    	$newAward->setUserAwardDefinesRank($rank);
    	$newAward->setUserAwardDefinesRankName($name);
    	$newAward->setUserAwardDefinesAim($aim);
    	$newAward->setUserAwardDefinesAimCount($count);
    	$newAward->setUserAwardDefinesAimAutomatic($auto);
    	$newAward->setUserAwardDefinesAimDbtable($DBTable);
    	$newAward->setUserAwardDefinesAimOperator($operator);
    	$newAward->setUserAwardDefinesDescription($descr);
    	$newAward->setUserAwardDefinesFacebookDescription($fbdescr);
   		$newAward->setUserAwardDefinesImage($image);
    	$newAward->save();

    	$tmp['status']	=	'201';
   		$tmp['text']	=	"Auszeichnung '$name' angelegt.";
    	$this->answer 	= 	$tmp;

    }

	public function setAwardGroup() {
		$groupId	=	trim($_REQUEST['award_group_id']);
		$descr		=	trim($_REQUEST['award_group_description']);
		$image		=	trim($_REQUEST['award_group_image']);
		$awardGroup	=	FfbUserAwardPeer::retrieveByPK($groupId);
		if(!$awardGroup) {
			$tmp['status']	=	'500';
   			$tmp['text']	=	"Auszeichnungsgruppe nicht gefunden.";
			$this->answer 	= 	$tmp;
			return;
		}
		$awardGroup->setUserAwardDescription($descr);
		$awardGroup->setUserAwardImage($image);
		$awardGroup->save();
		$tmp['status']	=	'201';
		$tmp['text']	=	"Auszeichnungsgruppe aktualisiert.";
		$this->answer 	= 	$tmp;
	}

	public function setAward() {
		$name 		=	trim($_REQUEST['award_name']);
    	$awardID 	=	trim($_REQUEST['award_defines_id']);
    	$rank		=	trim($_REQUEST['award_rank']);
    	$aim		=	trim($_REQUEST['award_aim']);
    	$count		= 	(!trim($_REQUEST['award_count']))	? 0 : trim($_REQUEST['award_count']);
    	$auto		=	(trim($_REQUEST['award_auto'])==1) 	? 1 : 0;
    	$DBTable	=	trim($_REQUEST['award_dbtable']);
    	$operator	= 	trim($_REQUEST['award_operator']);
    	$descr		=	trim($_REQUEST['award_description']);
    	$fbdescr	=	trim($_REQUEST['award_fb_description']);
    	$image		=	trim($_REQUEST['award_image']);

    	if(!$name || !$awardID || !$rank || !$aim || !$DBTable) {
    		$tmp['status']	=	'500';
    		$tmp['text']	=	"Auszeichnung '$name' konnte nicht aktualisiert werden fehlerhafte (leere) Eingabe.<br/>$name / $awardID / $rank / $aim / $count / $auto / $DBTable / $operator /";
    		$this->answer	=	$tmp;
    		return;
    	}

    	$oldAward 	=	FfbUserAwardDefinesPeer::retrieveByPK($awardID);

    	if($oldAward) {
    		//$oldAward 	=	$oldAward[0];
    		$oldAward->setUserAwardDefinesRank($rank);
    		$oldAward->setUserAwardDefinesRankName($name);
    		$oldAward->setUserAwardDefinesAim($aim);
    		$oldAward->setUserAwardDefinesAimCount($count);
    		$oldAward->setUserAwardDefinesAimAutomatic($auto);
    		$oldAward->setUserAwardDefinesAimDbtable($DBTable);
    		$oldAward->setUserAwardDefinesAimOperator($operator);
    		$oldAward->setUserAwardDefinesDescription($descr);
    		$oldAward->setUserAwardDefinesFacebookDescription($fbdescr);
    		$oldAward->setUserAwardDefinesImage($image);

    		$oldAward->save();

    		$tmp['status']	=	'201';
   			$tmp['text']	=	"Auszeichnung '$name' aktualisiert.";
    		$this->answer 	= 	$tmp;
    	}

	}

	public function testCalculateAllAwards() {
		echo 'start calculating all awards<br>';
		$start		=	explode(" ", microtime());

		$this->calculateAllAwards();

		$end		=	explode(" ", microtime());
		$duration	=	( (float)$end[1] - (float)$start[1]) . "Sekunden " . ( (float)$end[0] - (float)$start[0]) . "Millisekunden" ;
		$this->duration	=	$duration;
		echo $duration.'<br>';
		exit();
	}

	public function calculateAllAwards() {
		$this->allusers	=	null;
		$start		=	explode(" ", microtime());
		$criteria	=	new Criteria();
		$criteria->addSelectColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID);
		//$criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID, 0, CRITERIA::GREATER_THAN);
		$definedAwards	= FfbUserAwardDefinesPeer::doSelect(new Criteria());
		$index = 0;
		$duration	=	array();
		foreach($definedAwards AS $definedAward) {
			$loopIn	=	explode(" ", microtime());
			$this->calculateAward($definedAward->getUserAwardDefinesId());
			$end		=	explode(" ", microtime());
			$duration[$index]['award']		=	$definedAward->getUserAwardDefinesDescription();
			$duration[$index]['duration']	=	((float)$end[1] - (float)$loopIn[1]);
			$index++;
		}
		$end		=	explode(" ", microtime());
		$duration[$index]['award']		=	'Gesamt';
		$duration[$index]['duration']	= ( (float)$end[1] - (float)$start[1]) . "Sekunden " . ( (float)$end[0] - (float)$start[0]) . "Millisekunden" ;
		$this->duration	=	$duration;
	}


	public function calculateAward($awardID="") {
		//echo 'calc award '.$awardID.'<br>';
		$USER_ID 	=	"USER_ID";
		if(!$awardID)
			$awardID 	=	trim($_REQUEST['award_defines_id']);
		if(!$awardID)
			return;
		$awardToCalculate	=	FfbUserAwardDefinesPeer::retrieveByPK($awardID);
		if(!$awardToCalculate)
			return;

		if($awardToCalculate->getUserAwardDefinesAimAutomatic()==$this->automaticUpdates) {
			$ffbPeer		=	$awardToCalculate->getUserAwardDefinesAimDbtable();
			$peerPos 		=	stripos( $ffbPeer, "Peer::"  );
			$TABLE_NAME		=	substr($ffbPeer, 0, ($peerPos + 6 ));
			$loops			=	$awardToCalculate->getUserAwardDefinesAimCount();
			$aim			=	$awardToCalculate->getUserAwardDefinesAim();
			$aimOperator	=	$awardToCalculate->getUserAwardDefinesAimOperator();
			$ffbOnly		=	substr($TABLE_NAME, 0, $peerPos);
			$tableEntry		=	substr($ffbPeer, ($peerPos+6));
			$tableSingleName=	strtoupper(substr($TABLE_NAME, 3, ($peerPos - 3) ));
			$propelOk		=	"";
			for($i=0;$i<strlen($ffbOnly);$i++) {
				if(ctype_upper($ffbOnly[$i]) && $i!=0)
					$propelOk	.= "_";
				$propelOk	.=	strtolower($ffbOnly[$i]);
			}
			$theUserId		=	$propelOk.".$tableSingleName" ."_" ."$USER_ID";

			//find existing user id's and don't look for them
			$criteria		=	new Criteria();
			$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $awardID);
			//$criteria->addSelectColumn(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID);
			$finishedAwards	=	FfbUserAwardFinishedPeer::doSelect($criteria);
			$criteria		= 	new Criteria();
			foreach($finishedAwards AS $elem) {
				call_user_method_array("add", $criteria, array($theUserId, $elem->getUserAwardFinishedUserId(), "!="));
			}


			//$ffb = call_user_method("USERTEAM_WC_POINTS", FfbUserteamPee())

			//$criteria->add(FfbUserteamPeer::USERTEAM_WC_POINTS, 10, "=");
			call_user_method_array("add", $criteria, array("$propelOk.$tableEntry", $aim, $aimOperator));

			//call_user_method_array("add", $criteria, array("ffb_userteam.USERTEAM_WC_POINTS", $aim, $aimOperator));
			//$criteria->add(FfbUserteamPeer::USERTEAM_WC_POINTS, $aim, "=");

			//$criteria->addGroupByColumn(FfbUserteamPeer::USERTEAM_USER_ID);
			//call_user_method_array("addGroupByColumn", $criteria,   array($theUserId));
			//$c->addAsColumn('numArticles', 'COUNT('.AuthorPeer::ID.')');

			//$criteria->addAsColumn("numWins", "COUNT(".FfbUserteamPeer::USERTEAM_USER_ID.")");
			//call_user_method_array("addAsColumn", $criteria,  array("numWins", "COUNT($theUserId)"));

			//$c->addHaving($c->getNewCriterion(AuthorPeer::ID, 'numArticles=2', Criteria::CUSTOM));
			//$criteria->addHaving(call_user_method_array("getNewCriterion", $criteria, array("$propelOk.$tableEntry", "cnt=$loops", "CUSTOM")));

			//call_user_method_array("addHaving", $criteria, array($criteria));

			call_user_method_array("addAscendingOrderByColumn", $criteria, array($theUserId));

			$funcName		= 	$TABLE_NAME."doSelect";
			$awardWinners	=	call_user_func_array($funcName, array($criteria) );
			//print_r($awardWinners);
			$goalFinishers 	=	array();
			$uid			=	0;
			$uCounts		=	0;
			$tableSingleName=	strtolower($tableSingleName);
			$tableSingleName[0]=strtoupper($tableSingleName[0]);
			//$userUpdates	=	0;
			foreach ($awardWinners AS $winner) {
				//$winner->getUserAwardDefinesAimAutomatic
				$newUid	=	call_user_method("get".$tableSingleName."UserId", $winner);
				if($newUid!=$uid) {
					if($uCounts>=$loops) {
						$tmp['uid']		=	$uid;
						$tmp['aid']		=	$awardID;

						$criteria	=	new Criteria();
						$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, $uid);
						$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $awardID);
						$criteria->setLimit(1);
						$finished	=	FfbUserAwardFinishedPeer::doSelect($criteria);
						if(!$finished[0]) {
							$newAward		=	new FfbUserAwardFinished();
							$newAward->setUserAwardFinishedUserId($uid);
							$newAward->setUserAwardFinishedAwardDefinesId($awardID);
							//YYYY-MM-DD HH:MM:SS
							$newAward->setUserAwardFinishedDate(date("Y-m-d H:i:s"));
							$newAward->save();
							$user	=	WebUserPeer::retrieveByPK($uid);
							$tmp['usernick']=$user->getUserNickname();

							//FACEBOOK NOTIFICATION HERE
							$webUser	=	WebUserPeer::retrieveByPK($uid);
							if($webUser->getUserFacebookId()) {
								$groupAward 	=	FfbUserAwardPeer::retrieveByPK($awardToCalculate->getUserAwardDefinesAwardId());

								$fbComment 		=	$awardToCalculate->getUserAwardDefinesFacebookDescription();
								$fbName			=	$groupAward->getUserAwardName() . ", " . $awardToCalculate->getUserAwardDefinesRankName();
								$fbDescription	=	$awardToCalculate->getUserAwardDefinesDescription();
								$fbImages[]		=	FFB_BASE_PATH.FFB_IMAGE_PATH.$groupAward->getUserAwardImage();
								$fbImages[]		= 	FFB_BASE_PATH.FFB_IMAGE_PATH.$awardToCalculate->getUserAwardDefinesImage();
								$fbAwardFinishedId=$newAward->getUserAwardFinishedId();

								//TODO UNCOMMENT WHEN FINISHED
								//$this->fireFacebookComment($fbComment, $webUser->getUserFacebookId(), $fbName, $fbDescription, $fbImages, $fbAwardFinishedId, 1);
							}
							//END

							$goalFinishers[]=	$tmp;
							$this->localUserUpdates++;
						}

					}
					$uid	=	$newUid;
					$uCounts=	0;

				}
				$uCounts++;
			}
			$this->goalFinishers=	array_merge($this->goalFinishers, $goalFinishers);
			$this->newAwardUser	=	$this->goalFinishers;//$goalFinishers;
			$this->userUpdates	=	$this->localUserUpdates;
			//print_r($awardWinners);
		} else {

			//$awardToCalculate->getUserAwardDefinesAimFunctionName()) {
			$this->calculateAwardByFunction($awardToCalculate);


			//depricated!
			//$awardGroup 	=	FfbUserAwardPeer::retrieveByPK($awardToCalculate->getUserAwardDefinesAwardId());
			//$methodName		= 	"award".str_replace(" ", "", $awardGroup->getUserAwardName());
			//if(method_exists($this, $methodName)) {
			//	call_user_method($methodName, $this, array($awardToCalculate->getUserAwardDefinesId()) );
			//}
		}
		//echo 'calc award '.$awardID.' finished<br>';
	}

	public function testAbc() {
		$definedAward = FfbUserAwardDefinesPeer::retrieveByPK(10);

		$this->calculateAwardByFunction($definedAward);

		exit();
	}

	private function calculateAwardByFunction($definedAward) {
		$function_name = $definedAward->getUserAwardDefinesAimFunctionName();
		//$function_name = 'calcAwardGameWins';
		$game_id = $this->session->game_id_admin;
		$criteria = new Criteria();
		$criteria->addJoin(WebUserPeer::USER_ID, FfbUserscorePeer::USERSCORE_USER_ID, Criteria::INNER_JOIN);
		$criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $game_id);
		$criteria->add(WebUserPeer::USER_STATUS, 'active');
		$criteria->addGroupByColumn(WebUserPeer::USER_ID);

		$users = WebUserPeer::doSelect($criteria);
		foreach($users as $user) {
			//echo $user->getUserNickname().' - ';
			$criteria = new Criteria();
			$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, $user->getUserId());
			$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $definedAward->getUserAwardDefinesId());
			$exist_afs = FfbUserAwardFinishedPeer::doSelect($criteria);
			$exist_af = $exist_afs[0];
			if(call_user_method($function_name, $this, $definedAward, $user->getUserId()) == true) {
				if(!$exist_af) {
					$exist_af = new FfbUserAwardFinished();
					$exist_af->setUserAwardFinishedUserId($user->getUserId());
					$exist_af->setUserAwardFinishedAwardDefinesId($definedAward->getUserAwardDefinesId());
					$exist_af->setUserAwardFinishedDate(date('Y-m-d H:m:i', time()));
					//$exist_af->setUserAwardFinishedFacebookStreamId($user->getUserFacebookId());
					$exist_af->save();

					if($user->getUserFacebookId()!=null) {
								$groupAward 	=	FfbUserAwardPeer::retrieveByPK($definedAward->getUserAwardDefinesAwardId());

								$fbComment 		=	$definedAward->getUserAwardDefinesFacebookDescription();
								$fbName			=	$groupAward->getUserAwardName() . ", " . $definedAward->getUserAwardDefinesRankName();
								$fbDescription	=	$definedAward->getUserAwardDefinesDescription();
								$fbImages[]		=	FFB_BASE_PATH.FFB_IMAGE_PATH.$groupAward->getUserAwardImage();
								$fbImages[]		= 	FFB_BASE_PATH.FFB_IMAGE_PATH.$definedAward->getUserAwardDefinesImage();
								$fbAwardFinishedId=$exist_af->getUserAwardFinishedId();

								//TODO UNCOMMENT WHEN FINISHED
								//$this->fireFacebookComment($fbComment, $user->getUserFacebookId(), $fbName, $fbDescription, $fbImages, $fbAwardFinishedId, 1);
					}

				}
				//echo 'ok<br>';
			} else {
				if($exist_af) {
					//delete entry
					$exist_af->delete();
				}
				//echo 'failed<br>';
			}
		}
	}

	public function getUserAwardsFinished($awardID="") {
		if(!$awardID){
			$awardID	=	$_REQUEST['award_defines_id'];
		}
		if(!$awardID)
			return;
		$numWinners		=	0;


		$criteria		=	new	Criteria();
		$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $awardID);
		//$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, WebUserPeer::USER_ID);
		$awardFinishers	=	FfbUserAwardFinishedPeer::doSelectJoinWebUser($criteria);
		//print_r($awardFinishers);
		if($awardFinishers) {
			$criteria				=	new Criteria();
			$criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID, $awardID);
			$awardInfo				=	FfbUserAwardDefinesPeer::doSelectJoinFfbUserAward($criteria);
			//$tmp['award_descr']		=	$awardInfo->getUserAwardDefinesDescription();
			if($awardInfo) {
				$tmp['award_fb_descr']	=	($awardInfo[0]->getUserAwardDefinesFacebookDescription())?	$awardInfo[0]->getUserAwardDefinesFacebookDescription()	:	" ";
				$tmp['award_descr']		=	($awardInfo[0]->getUserAwardDefinesDescription())		?	$awardInfo[0]->getUserAwardDefinesDescription()	:	" ";
				$tmp['award_image']		=	($awardInfo[0]->getUserAwardDefinesImage())				?	$awardInfo[0]->getUserAwardDefinesImage() : "-";
				$tmp['group_image']		=	($awardInfo[0]->getFfbUserAward()->getUserAwardImage())	?	$awardInfo[0]->getFfbUserAward()->getUserAwardImage() : "-";
				$tmp['title']			=	$awardInfo[0]->getFfbUserAward()->getUserAwardName() . ", " . $awardInfo[0]->getUserAwardDefinesRankName();
				$this->awardInfos		=	$tmp;
			}


			$awardWinners	=	array();

			//print_r($awa)
			foreach($awardFinishers AS $finished) {
				$tmp			=	array();
				$tmp['fbuid']	=	($finished->getWebUser()->getUserFacebookId())	? $finished->getWebUser()->getUserFacebookId() : '-' ;
				$tmp['nick']	=	$finished->getWebUser()->getUserNickname();
				$tmp['date']	=	$finished->getUserAwardFinishedDate();
				$tmp['fid']		=	$finished->getUserAwardFinishedId();
				$awardWinners[]	= 	$tmp;
				$numWinners++;
			}
			$this->awardWinners	=	$awardWinners;
		}
		$this->numWinners	=	$numWinners;

	}

	//0 == alle ansonsten als array
	public function updateUserAwards($matchroundID=0, $awardID=0, $userID=0) {
		$criteria 	= 	new Criteria();
		$criteria->add(FfbUserAwardDefines::USER_AWARD_DEFINES_AIM_AUTOMATIC, $this->automaticUpdates);
		if($awardID) {
			$criteria->add(FfbUserAwardDefines::USER_AWARD_DEFINES_ID, $awardID);
		}

	}

	public function fireFacebookU2UComment($fbComment="", $fbUserId="", $fbName="", $fbDescription="", $fbImages="", $ffbAwardFinishedId="", $fbSenderFbId="") {
		$this->allusers = null;
		if(!$fbComment)
			$fbComment	=	trim($_REQUEST['fbcomment']);
		if(!$fbUserId)
			$fbUserId	=	trim($_REQUEST['fbuid']);
		if(!$fbName)
			$fbName		=	trim($_REQUEST['name']);
		if(!$fbDescription)
			$fbDescription=	trim($_REQUEST['description']);
		if(!$fbImages){
			$fbImages	=	explode(",", trim($_REQUEST['images']));
			//array(	'type' => 'image', 'src' => 'http://ffb.tobijat.at/images/admin/navigation/nav_news.png', 'href' => 'http://ffb.tobijat.at/start')
		}

		if(!$fbSenderFbId) {
			$fbSenderFbId =	trim($_REQUEST['fbsenderid']);
			if(!$fbSenderFbId)
				$fbSenderFbId = $fbUserId;
		}

		if(!$fbComment || !$fbUserId )
			return;
		if($fbImages) {
			foreach($fbImages AS $elem) {
				$tmp2			=	array();
				$tmp2['type']	=	'image';
				$tmp2['src']	=	$elem;
				$tmp2['href']	=	FFB_BASE_PATH ."ffb/facebook/facebookUser.html?fbid=$fbUserId";
				$tmp[]			=	$tmp2;
			}
			$fbImages		=	$tmp;
		}
		//session_start();

		//$this->sid= session_id();
		//$this->decoded = "%22%3A%223c88f7ae6386494fa4a2f7a2-100000079177121%22%2C%22";
		//return;
		if(!$this->fbConnect)
			$this->fbConnect	=	new Facebook($this->fbAppKey, $this->fbAppSecret);
		//$fbUID = $this->fbConnect->require_login();

		if(!isset($_REQUEST["application"]))
			$result			=	$this->fbConnect->api_client->stream_publish($fbComment, null, null, $fbUserId, $fbSenderFbId);
		else {
			//$this->fbConnect->api_client->user = $this->fbUserId;
			$this->fbConnect->api_client->session_key = $this->fbInfiniteSessionKey;
			$this->fbConnect->api_client->expires = 0;

			$title = $_REQUEST['name'];
			if(!$title)
				$title = ' ';

			$result			=	$this->fbConnect->api_client->stream_publish(trim($title."\r\n".$fbComment),null, null,$fbUserId, $fbUserId);
			//100000079177121
			$this->result	=	$result;

		}


	}

	public function fbReturn() {
		$this->htmlFile = 'dummy.php';
		$this->request = $_REQUEST;
	}



	public function fireFacebookComment($fbComment="", $fbUserId="", $fbName="", $fbDescription="", $fbImages="", $ffbAwardFinishedId="", $quiet=0, $fbSenderFbId="")	{
		//?fbcomment={*actor*} hat 3 Spielrunden-Siege errungen.
		//&fbuid=100000851709484
		//&name=Tabellenführer, Blech
		//&images=http://soccer.sportsfan.at/images/ffb/awards/ball_brown64x64.png,http://soccer.sportsfan.at/images/ffb/awards/blech48x48.png
		//&description=Gewinne 3 Spielrunden.
		//&ffbAwardFinishedId=374


		if(!$fbComment)
			$fbComment	=	trim($_REQUEST['fbcomment']);
		if(!$fbUserId)
			$fbUserId	=	trim($_REQUEST['fbuid']);
		if(!$fbName)
			$fbName		=	trim($_REQUEST['name']);
		if(!$fbDescription)
			$fbDescription=	trim($_REQUEST['description']);
		if(!$fbImages){
			$fbImages	=	explode(",", trim($_REQUEST['images']));
			//array(	'type' => 'image', 'src' => 'http://ffb.tobijat.at/images/admin/navigation/nav_news.png', 'href' => 'http://ffb.tobijat.at/start')
		}

		if(!$fbSenderFbId) {
			$fbSenderFbId =	trim($_REQUEST['fbsenderid']);
			if(!$fbSenderFbId)
				$fbSenderFbId = $fbUserId;
		}


		if(!$ffbAwardFinishedId)
			$ffbAwardFinishedId		=	trim($_REQUEST['ffbAwardFinishedId']);
		$tmp 		=	array();
		foreach($fbImages AS $elem) {
			$tmp2			=	array();
			$tmp2['type']	=	'image';
			$tmp2['src']	=	$elem;
			$tmp2['href']	=	FFB_BASE_PATH ."ffb/pub/facebookAwards.html?fbid=$fbUserId";
			$tmp[]			=	$tmp2;
		}
		$fbImages		=	$tmp;
		$awardFinished	=	FfbUserAwardFinishedPeer::retrieveByPK($ffbAwardFinishedId);
		$ffbUserId		=	$awardFinished->getUserAwardFinishedUserId();
		$ffbPermissions	=	WebUserPermissionsPeer::retrieveByPK($ffbUserId);



		//$this->img = array_chunk($fbImages, 1, false);
		//return;

		if(!$fbComment || !$fbUserId || ($ffbPermissions->getUserPermissionsFacebookConnected() == 0) || (strcmp($ffbPermissions->getUserPermissionsFfbFacebook(), "0") == 0  ) )
			return;

		if(!$this->fbConnect)
			$this->fbConnect	=	new Facebook($this->fbAppKey, $this->fbAppSecret, true);
		/*
		$secret = $this->fbAppSecret; // where 'Secret Key' is your application secret key
		$args = array(
			'argument1' => $fbUserId,
			'argument2' => $fbComment); // insert the actual arguments for your request in place of these example args
			$request_str = '';
			foreach ($args as $key => $value) {
				$request_str .= $key . '=' . $value; // Note that there is no separator.
			}
		$sig = $request_str . $secret;
		$sig = md5($sig);	*/
		  /**
   * Publish a post to the user's stream.
   *
   * @param $message        the user's message
   * @param $attachment     the post's attachment (optional)
   * @param $action links   the post's action links (optional)
   * @param $target_id      the user on whose wall the post will be posted
   *                        (optional)
   * @param $uid            the actor (defaults to session user)
   * @return string the post id
   */
		$attachment = array(
      		'name' => $fbName, //name: The title of the post. The post should fit on one line in a user's stream; make sure you account for the width of any thumbnail. gaanz oben
      		'href' => FFB_BASE_PATH."ffb/pub/facebookAwards.html?fbid=$fbUserId", //href: The URL to the source of the post referenced in the name. The URL should not be longer than 1024 characters.
      		'caption' => $fbComment, //caption: A subtitle for the post that should describe why the user posted the item or the action the user took. This field can contain plain text only, as well as the {*actor*} token, which gets replaced by a link to the profile of the session user. The caption should fit on one line in a user's stream; make sure you account for the width of any thumbnail.
      		'description' => $fbDescription, //'Die Auszeichnung bekommt man bei xxx und yyy', //description: Descriptive text about the story. This field can contain plain text only and should be no longer than is necessary for a reader to understand the story.

      		//properties: An array of key/value pairs that provide more information about the post. The properties array can contain plain text and links only. To include a link, the value of the property should be a dictionary with 'text' and 'href' attributes.
      		//'properties' => array(	'category' => array('text' => 'Fantasy Football ffb.tobijat.at', 'href' => 'http://ffb.tobijat.at/ffb')	  ),
			//media: Rich media that provides visual content for the post. media is an array that contains one of the following types: image, flash, mp3, or video, which are described below. Make sure you specify only one of these types in your post.
			'media' => array( $fbImages[1], $fbImages[0] )
			);
		//$action_links = array(
		//	array('text' => 'Recaption this', 'href' => 'http://mine.icanhascheezburger.com/default.aspx?tiid=1192742&recap=1#step2'));
   		//$action_links = array(    'text' => 'Fantasy Football ffb.tobijat.at',  'href' => 'http://apps.facebook.com/ffbtobijat/' );
//parameter uid or session key required
   		$result			=	$this->fbConnect->api_client->stream_publish($fbName, $attachment, null, $fbUserId, $fbSenderFbId);
		//$response		=	$this->fbConnect->api_client->notifications_send($this->fbAppiKey, microtime(true), $sig, "1.0", $fbUserId, $fbComment, session_id(), "XML", "", 'app_to_user');
		if($quiet==0)
			$this->response	=	$result;
		if($ffbAwardFinishedId) {
			$awardFinished->setUserAwardFinishedFaceBookStreamId($result);
			$awardFinished->save();
		}



	}



	public function retrieveAwardInfosAndSendToFB() {
		$this->allusers =	null;
		$finishedID		=	intval($_REQUEST['user_award_finished_id']);
		if(!$finishedID)
			return;
		$finishedAward	=	FfbUserAwardFinishedPeer::retrieveByPK($finishedID);
		if(!$finishedAward)
			return;
		$user			=	WebUserPeer::retrieveByPK($finishedAward->getUserAwardFinishedUserId());
		$definedAward	=	FfbUserAwardDefinesPeer::retrieveByPK($finishedAward->getUserAwardFinishedAwardDefinesId());


		if($user->getUserFacebookId()!=null) {
			$groupAward 	=	FfbUserAwardPeer::retrieveByPK($definedAward->getUserAwardDefinesAwardId());
			$fbComment 		=	$definedAward->getUserAwardDefinesFacebookDescription();
			$fbName			=	$groupAward->getUserAwardName() . ", " . $definedAward->getUserAwardDefinesRankName();
			$fbDescription	=	$definedAward->getUserAwardDefinesDescription();
			$fbImages[]		=	FFB_BASE_PATH.FFB_IMAGE_PATH.$groupAward->getUserAwardImage();
			$fbImages[]		= 	FFB_BASE_PATH.FFB_IMAGE_PATH.$definedAward->getUserAwardDefinesImage();
			//$fbAwardFinishedId=$exist_af->getUserAwardFinishedId();

			//TODO UNCOMMENT WHEN FINISHED
			$this->fireFacebookComment($fbComment, $user->getUserFacebookId(), $fbName, $fbDescription, $fbImages, $finishedID, 1);
		}
		$finishedAward	=	FfbUserAwardFinishedPeer::retrieveByPK($finishedID);
		$fbStreamID		=	$finishedAward->getUserAwardFinishedFacebookStreamId();
		if($fbStreamID)
			$this->fbStreamId	=	$fbStreamID;
		else
			$this->fbStreamId	=	'error';
	}


	public function __default() {
		$this->getAwardGroups();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //defined functions for specific awards

    //calculate Award "Erfolgstrainer"
    private function calcAwardGameWins($award_define, $user_id) {
    	$aim_count = $award_define->getUserAwardDefinesAimCount();
    	$aim = $award_define->getUserAwardDefinesAim();

    	$criteria = new Criteria();
    	$criteria->add(FfbGamePeer::GAME_STATUS, 1);
    	$criteria->add(FfbGamePeer::GAME_ARCHIVE, 1);
    	$games = FfbGamePeer::doSelect($criteria);
		$count = 0;
    	foreach($games as $game) {
			$options = $game->getFfbOptionss();
	   		$rm = $options[0]->getOptionsGameRankmode();
			if($this->calculateUserRank($user_id, $game->getGameId(), $rm) == $aim) {
				$count++;
			}
		}

		if($count >= $aim_count) {
			return true;
		} else {
			return false;
		}
	}

	//calculate Award "Tabellenführer" und "Ewiger Zweiter"
    private function calcAwardRoundWins($award_define, $user_id) {
    	$aim_count = $award_define->getUserAwardDefinesAimCount();
    	$aim = $award_define->getUserAwardDefinesAim();

    	$criteria = new Criteria();
    	$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, Criteria::INNER_JOIN);
    	$criteria->addJoin(FfbGamePeer::GAME_ID, FfbMatchroundPeer::MATCHROUND_GAME_ID);
    	$criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, date('Y-m-d H:i:s', time()), Criteria::LESS_THAN);
		$criteria->add(FfbGamePeer::GAME_STATUS, 1);
		$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $user_id);

    	$uts = FfbUserteamPeer::doSelect($criteria);
		$count = 0;
		//echo count($uts);
		//exit();
    	foreach($uts as $ut) {
			if(($rank = $this->calculateUserteamRank($ut->getUserteamId(), $ut->getUserteamMatchroundId())) == $aim) {
				$count++;
			}
			//echo $rank.'.) '.$ut->getFfbMatchround()->getMatchroundTitle().'<br>';
		}

		if($count >= $aim_count) {
			return true;
		} else {
			return false;
		}
	}

	//returns the users rank for given user_id, game_id and rankmode
	private function calculateUserRank($user_id, $game_id, $rm) {
		$criteria = new Criteria();
		$criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $game_id);
		if($rm == 'wc') {
			$criteria->addDescendingOrderByColumn(FfbUserscorePeer::USERSCORE_WC_POINTS);
			$criteria->addDescendingOrderByColumn(FfbUserscorePeer::USERSCORE_TOTAL);
		} elseif($rm == 'points') {
			$criteria->addDescendingOrderByColumn(FfbUserscorePeer::USERSCORE_TOTAL);
		}
		$uc_items = FfbUserscorePeer::doSelect($criteria);
		if($uc_items) {
			$last_score = 10000;
			$last_points = 10000;
			$rank = 1;
			$i = 0;
			foreach($uc_items as $item) {
				$i++;
				if($rm == 'wc') {
					if($item->getUserscoreWcPoints() < $last_score) {
						$last_score = $item->getUserscoreWcPoints();
						$last_points = $item->getUserscoreTotal();
						$rank = $i;
					} else {
						if($item->getUserscoreTotal() < $last_points) {
							$last_points = $item->getUserscoreTotal();
							$rank = $i;
						}
					}
				} elseif($rm == 'points') {
					if($item->getUserscoreTotal() < $last_score) {
						$last_score = $item->getUserscoreTotal();
						$rank = $i;
					}
				}
				if($item->getUserscoreUserId() == $user_id) {
					return $rank;
				}
			}
		}
		return 0;
	}

	//returns the userteam rank for given userteam_id and matchround_id
	private function calculateUserteamRank($userteam_id, $matchround_id) {
		$criteria = new Criteria();
		$criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $matchround_id);
		$criteria->addDescendingOrderByColumn(FfbUserteamPeer::USERTEAM_SCORE);
		$ut_items = FfbUserteamPeer::doSelect($criteria);

		if($ut_items) {
			$last_score = 10000;
			$rank = 1;
			$i = 0;
			foreach($ut_items as $item) {
				$i++;
				if($item->getUserteamScore() < $last_score) {
					$last_score = $item->getUserteamScore();
					$rank = $i;
				}
				if($item->getUserteamId() == $userteam_id) {
					return $rank;
				}
			}
		}
		return 0;
	}

	//calculate Award "Bierkiste"
    private function calcAwardBeer($award_define, $user_id) {
    	$aim_count = $award_define->getUserAwardDefinesAimCount();
    	$game_id = $award_define->getUserAwardDefinesAim();
    	$game = FfbGamePeer::retrieveByPK($game_id);

		$options = $game->getFfbOptionss();
   		$rm = $options[0]->getOptionsGameRankmode();
		if($game->getGameArchive() == 1 && $this->calculateUserRank($user_id, $game_id, $rm) == 1) {
			return true;
		} else {
			return false;
		}
	}
	//-----

	//calculate Award "Topscorer"
	private function calcAwardTopscorer($award_define, $user_id) {
		$points = $award_define->getUserAwardDefinesAim();
		$criteria = new Criteria();
		$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $user_id);
		$criteria->add(FfbUserteamPeer::USERTEAM_SCORE, $points, Criteria::GREATER_EQUAL);
		$result = FfbUserteamPeer::doCount($criteria);

		if($result) {
			return true;
		} else {
			return false;
		}
	}
	//-----


	public function testFacebook() {
		$facebook = new Facebook($this->fbAppKey, $this->fbAppSecret);
		$infinite_key_array = $facebook->api_client->auth_getSession('NLTCC8');
		$this->infiniteKey=$infinite_key_array;
		//print_r($infinite_key_array);
	}
}