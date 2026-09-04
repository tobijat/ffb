<?php

/**
 * ADMIN - BlockAds-Klasse;
 * Werbeblöcke für Benutzer/IP blockieren
 *
 * @author Gerald Musser
 * @copyright 10/2008
 * @version 0.1
 *
 */

class blockads extends FFB_Auth_AdminFfb {
	    
	public function __construct() {
        parent::__construct();
    }
    public function __default() {
    	;
    }
    
    
    public function getBlockedUsers() {
    	$criteria	=	new Criteria();
    	$criteria->addAscendingOrderByColumn(FfbNoAdsPeer::NO_ADS_USER_ID_IP);
    	$blockages	=	FfbNoAdsPeer::doSelect($criteria);
    	$index		=	0;
    	if($blockages) {
    		$blockedSlots	=	array();
    		foreach($blockages AS $blockage) {
    			$tmp 	= array();
    			$tmp['u_ip_id']	=	$blockage->getNoAdsUserIdIp();
    			
				if(!is_numeric($blockage->getNoAdsUserIdIp()) ) { //ip adress
    				$tmp['u_name']	=	$blockage->getNoAdsUserIdIp();
    			} else {
    				$user	=	WebUserPeer::retrieveByPK(intval($blockage->getNoAdsUserIdIp()));
    				if($user)
    					$tmp['u_name']	=	$user->getUserNickname();
   					else
   						$tmp['u_name']	=	'? error user undefined ?';
    			}
    			
    			$slot	=	FfbAdsSlotPeer::retrieveByPK($blockage->getNoAdsSlotId());
    			if($slot)
    				$tmp['slot_name']	=	$slot->getAdsSlotName();
   				else
   					$tmp['slot_name']	=	'--alle--';
    			$tmp['slot_id']		=	$blockage->getNoAdsSlotId();
    			$tmp['blockage_id']	=	$blockage->getNoAdsId();
    			$blockedSlots[]		= $tmp;
    			$index++;    			
    		}
    		
    		$this->slotBlockers	=	$blockedSlots;
    	}
    	
    	$this->count=$index;
    }
    
    public function loadAllUser() {
    	$criteria	=	new Criteria();
    	$criteria->addAscendingOrderByColumn(WebUserPeer::USER_NICKNAME);
    	$allUsers	=	WebUserPeer::doSelect($criteria);
    	$userList	=	array();
    	$cnt		=	0;
    	foreach($allUsers AS $user) {
    		$tmp 	=	array();
    		$tmp['nick']	=	$user->getUserNickname();
    		$tmp['id']		=	$user->getUserId();
    		$userList[]		=	$tmp;
    		$cnt++;
    	}
    	$this->count		=	$cnt;
    	$this->userList		=	$userList;    	
    }
    
    public function addBlockade() {
    	$ipId	=	null;
		if(trim($_REQUEST['user_nick'])) {
    		$criteria	=	new Criteria();
    		$criteria->add(WebUserPeer::USER_NICKNAME, trim($_REQUEST['user_nick']));
    		$criteria->setLimit(1);
    		$user		=	WebUserPeer::doSelect($criteria);
    		if($user)
    			$ipId	=	$user[0]->getUserId();
   			else {
   				$tmp['status']	=	'301';
   				$tmp['text']	=	'Keinen Benutzer "' . trim($_POST['user_nick']) . '" gefunden.';
   				$this->msg		=	$tmp;
   				return;
   			}
   			
    	}
    	if(!$ipId)    	
    		$ipId		=	trim($_REQUEST['user_ip']);
    	if(!$ipId)
    		$ipId		=	intval($_REQUEST['user_id']);
   		
  		if($ipId===null) {
   			$tmp['status']	=	'301';
   			$tmp['text']	=	'Keinen Benutzer gefunden.';
   			$this->msg		=	$tmp;
   			return;
   		}
   		
    	$slotIdToBlock	=	intval($_REQUEST['slot_id'])	?	intval($_REQUEST['slot_id']) : 0;
    	
    	//$this->answer 	=	"slotID: ". $slotIdToBlock . " ipId: $ipId";
    	$blockage		= new FfbNoAds();
    	$blockage->setNoAdsUserIdIp($ipId);
    	$blockage->setNoAdsSlotId($slotIdToBlock);
    	$blockage->save();
    	$tmp['status']	=	'201';
    	$tmp['text']	=	"Werbeausnahme UserId: $ipId f&uuml;r Slot: $slotIdToBlock angelegt.";
    	$this->msg		=	$tmp;
    	
    }
    
    public function showBlockade() {
    	if(!intval($_POST['slot_blockage_id'])) {
    		$tmp['status']	=	'301';
   			$tmp['text']	=	'error: you now that I know that this is not right (1).';
   			$this->msg		=	$tmp;
   			return;	
    	}
    	
		$blockage	=	FfbNoAdsPeer::retrieveByPK(intval($_POST['slot_blockage_id']));
    	
		if(!$blockage) {
    		$tmp['status']	=	'301';
   			$tmp['text']	=	'error: you now that I know that this is not right (2).';
   			$this->msg		=	$tmp;
   			return;
    	}
    	$blockageInfos['noAdId']		=	$blockage->getNoAdsId();
    	$blockageInfos['noAdsSlotId']	=	$blockage->getNoAdsSlotId();
    	
    	$blockageInfos['userNick']		=	$blockage->getNoAdsUserIdIp();
    	
		if(is_numeric($blockage->getNoAdsUserIdIp())) {
    		$user	=	WebUserPeer::retrieveByPK(intval($blockage->getNoAdsUserIdIp()));
    		if(!$user) {
    			$blockageInfos['userNick']	=	$blockage->getNoAdsUserIdIp();
    		} else {
    			$blockageInfos['userNick']	=	$user->getUserNickname();
    		}
    	}

		$blockageInfos['noAdsSlotName']	=	'-- alle --';
    	
    	if($blockage->getNoAdsSlotId()) {
    		$slotName	=	FfbAdsSlotPeer::retrieveByPk($blockage->getNoAdsSlotId());
    		if($slotName)
    			$blockageInfos['noAdsSlotName']	=	$slotName->getAdsSlotName();
    	}

    	$tmp['status']	=	'201';
		$tmp['text']	=	'aha not what you were looking for?';
   		$this->msg		=	$tmp;
    	
    	$this->blockageInfos	=	$blockageInfos;
    }
	
 	public function __destruct()
    {
        parent::__destruct();
    }    
    
}