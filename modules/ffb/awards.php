<?php

/**
 * FFB-Module - USER AWARDS-Klasse;
 *
 * @author Gerald Musser
 * @copyright 09/2009
 * @version 0.1
 *
 */

class awards extends FFB_Auth_No {

	public function __construct() {
        parent::__construct();
    }

    public function __default() {
    }


    public function getAllUserAwards() {
    	$userid		=	$_REQUEST['user_id'];
    	//$criteria	=	new Criteria();
    	//$criteria->add(FfbUserAwardFinishedPeer::UserAwardFinishedUserId, $userid);
    	//$criteria->addJoin(FfbUserAwardFinishedPeer::UserAwardFinishedAwardDefinesId, FfbUserAwardDefinesPeer::UserAwardDefinesId, Criteria::LEFT_JOIN);
    	//$criteria->addJoin(FfbUserAwardPeer::UserAwardId, FfbUserAwardDefinesPeer::UserAwardDefinesAwardId, Criteria::LEFT_JOIN);
    	//$criteria->addAscendingOrderByColumn(FfbUserAwardPeer::UserAwardId);
    	//$criteria->add()

    	$criteria	=	new Criteria();
    	$criteria->addDescendingOrderByColumn(FfbUserAwardPeer::USER_AWARD_SORTFLAG);
    	$criteria->addAscendingOrderByColumn(FfbUserAwardPeer::USER_AWARD_ID);
    	$awards		=	FfbUserAwardPeer::doSelect($criteria);
    	$myAwards	=	array();
    	$awardIndex	=	0;
    	foreach($awards AS $award) {
    		$tmp		=	array();
    		$myAwards[$awardIndex]['group']['gname']	=	$award->getUserAwardName();
    		$myAwards[$awardIndex]['group']['gimg']	=	$award->getuserAwardImage();
    		$myAwards[$awardIndex]['group']['gdescr']	=	$award->getUserAwardDescription();
    		$myAwards[$awardIndex]['group']['gid']	=	$award->getUserAwardId();

    		$criteria	=	new Criteria();
    		$criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID, $award->getUserAwardId());
    		$criteria->addAscendingOrderByColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK);
    		$awardDefines	=	FfbUserAwardDefinesPeer::doSelect($criteria);
    		$definesIndex	=	0;
    		foreach($awardDefines AS $defined) {
    			$myAwards[$awardIndex]['group']['award'][$definesIndex]['name']	=	$defined->getUserAwardDefinesRankName();
    			$myAwards[$awardIndex]['group']['award'][$definesIndex]['descr']	=	$defined->getUserAwardDefinesDescription();
    			$myAwards[$awardIndex]['group']['award'][$definesIndex]['rank']	=	$defined->getUserAwardDefinesRank();
    			$myAwards[$awardIndex]['group']['award'][$definesIndex]['img']		=	$defined->getUserAwardDefinesImage();
    			$myAwards[$awardIndex]['group']['award'][$definesIndex]['id']		=	$defined->getUserAwardDefinesId();
    			$criteria		=	new Criteria();
    			$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, $userid);
    			$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $defined->getUserAwardDefinesId());
    			$criteria->setLimit(1);
    			$finished		=	FfbUserAwardFinishedPeer::doSelect($criteria);
    			if($finished) {
    				$myAwards[$awardIndex]['group']['award'][$definesIndex]['finished']	=	1;
    			} else {
    				$myAwards[$awardIndex]['group']['award'][$definesIndex]['finished']	=	0;
    			}
    			$definesIndex++;
    		}
    		$myAwards[$awardIndex]['group']['awardcount']	=	$definesIndex;
    		$awardIndex++;
    	}
    	$this->userAwards		=	$myAwards;
    	$this->awardGroupCount	=	$awardIndex;
    }

 	public function __destruct()
    {
        parent::__destruct();
    }
}