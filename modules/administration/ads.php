<?php

/**
 * ADMIN - Ads-Klasse;
 * Werbung hinzufügen/ändern/User aus der Werbung rausnehmen
 *
 * @author Gerald Musser
 * @copyright 10/2008
 * @version 0.1
 *
 */

class ads extends FFB_Auth_AdminFfb {
	    
	public function __construct() {
        parent::__construct();
        $this->htmlFile = 'ads.php';
        if($_POST['action']=='newslot') {
        	$this->newAdsSlot();
        	$this->getAdsSlots();
        	$this->getAds();
        }
        if($_POST['action']=='newad') {
        	$this->newAd();
        	$this->getAdsSlots();
        	$this->getAds();
        }
    }
    
    
    
    public function updateAdSlotEntry() {
   		$startDate	=	date("Y-m-d");
    	$endDate	=	date("Y-m-d", ( time() + (20 * 12 * 4 * 7 * 24 * 60 * 60 ) ));	//today + ~20 Years
    	$slotAllocId=	intval($_POST['slot_alloc_id']) >0	?	intval($_POST['slot_alloc_id'])	:	0;
    	$max		=	intval($_POST['ad_max']) >0	?	intval($_POST['ad_max'])	:	0;
    	$count		=	intval($_POST['ad_count'])>0?	intval($_POST['ad_count'])	:	0;
    	$priority	=	intval($_POST['ad_prio'])>0	?	intval($_POST['ad_prio'])	:	0;
    	$startDate	=	trim($_POST['ad_start'])	? 	trim($_POST['ad_start'])	: 	$startDate;
    	$endDate	=	trim($_POST['ad_end'])		? 	trim($_POST['ad_end'])		: 	$endDate;
    	$gameId		=	intval($_POST['game_id'])>0	?	intval($_POST['game_id'])	:	0;
    	
    	if(!$slotAllocId) {
    		$tmp['status']	=	'300';
    		$tmp['text']	=	"Fehler: keine WErbeblock ID gefunden (1)";
    		$this->msg	=	$tmp;
    		return;
    	}
    	
    	$slotAlloc	=	FfbAdsAllocationPeer::retrieveByPK($slotAllocId);
    	if(!$slotAlloc) {
    		$tmp['status']	=	'300';
    		$tmp['text']	=	"Fehler: keine Werbeblock ID gefunden (2)";
    		$this->msg	=	$tmp;
    		return;
    	}
    	
    	$slotAlloc->setAdsAllocationAdCount($count);
    	$slotAlloc->setAdsAllocationAdMax($max);
    	$slotAlloc->setAdsAllocationAdPriority($priority);	
    	$slotAlloc->setAdsAllocationStart($startDate);
    	$slotAlloc->setAdsAllocationEnd($endDate);
    	$slotAlloc->setAdsAllocationGameId($gameId);
    	$slotAlloc->save();
    	$tmp['status']	=	'201';
   		$tmp['text']	=	"Werbeblock mit ID $slotAllocId erfolgreich upgedeted.";
   		$this->msg	=	$tmp;
    	
    	
    }
    
    public function addAd() {
    	$startDate	=	date("Y-m-d");
    	$endDate	=	date("Y-m-d", ( time() + (20 * 12 * 4 * 7 * 24 * 60 * 60 ) ));	//today + ~20 Years
    	$adId		=	intval($_POST['add_id']) >0	?	intval($_POST['add_id'])	:	0;
    	$slotId		=	intval($_POST['slot_id'])>0	?	intval($_POST['slot_id'])	:	0;
    	$max		=	intval($_POST['ad_max']) >0	?	intval($_POST['ad_max'])	:	0;
    	$priority	=	intval($_POST['ad_prio'])>0	?	intval($_POST['ad_prio'])	:	0;
    	$startDate	=	trim($_POST['ad_start'])	? 	trim($_POST['ad_start'])	: 	$startDate;
    	$endDate	=	trim($_POST['ad_end'])		? 	trim($_POST['ad_end'])		: 	$endDate;
    	$gameId		=	intval($_POST['game_id'])>0	?	intval($_POST['game_id'])	:	0;
    	if(!$adId) {
    		$tmp['status']	=	'300';
    		$tmp['text']	=	"Fehler: keine Ad ID gefunden (1)";
    		$this->msg	=	$tmp;
    		return;
    	}
    	if(!$slotId) {
    		$tmp['status']	=	'300';
    		$tmp['text']	=	"Fehler: keine Slot ID gefunden (1)";
    		$this->msg	=	$tmp;
    		return;
    	}
    	$adAllocation	=	new FfbAdsAllocation();
    	$adAllocation->setAdsAllocationAdsId($adId);
    	$adAllocation->setAdsAllocationSlotId($slotId);
    	$adAllocation->setAdsAllocationAdmax($max);
    	$adAllocation->setAdsAllocationAdPriority($priority);
    	$adAllocation->setAdsAllocationStart($startDate);
    	$adAllocation->setAdsAllocationEnd($endDate);
    	$adAllocation->setAdsAllocationgameId($gameId);
    	$adAllocation->save();
    	$tmp['status']	=	'201';
   		$tmp['text']	=	"Neuer Werbeblock hinzugef&uuml;gt.";
    	$this->msg	=	$tmp;
    	
    }
    
    public function getSlotAllocation() {
    	$slotId	=	intval($_POST['slot_id']);
   		$criteria	=	new Criteria();
    	$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID, $slotId);
    	$slotAlloc	=	FfbAdsAllocationPeer::doSelect($criteria);
    	
    	$slots	=	array();
    	$index = 0;
    	if($slotAlloc) {
    		foreach($slotAlloc AS $slot) {
    			$ad 	=	FfbAdsPeer::retrieveByPK($slot->getAdsAllocationAdsId());
						
    			$slots[$index]['allocId']		=	$slot->getAdsAllocationId();
    			$slots[$index]['allocAdsId']	=	$slot->getAdsAllocationAdsId();
    			$slots[$index]['allocAdCount']	=	$slot->getAdsAllocationAdCount();
    			$slots[$index]['allocAdMax']	=	$slot->getAdsAllocationAdMax();
    			$slots[$index]['allocAdPri']	=	$slot->getAdsAllocationAdPriority();
    			$slots[$index]['allocAdStart']	=	$slot->getAdsAllocationStart() 	? $slot->getAdsAllocationStart(): "null";
    			$slots[$index]['allocAdEnd']	=	$slot->getAdsAllocationEnd() 	? $slot->getAdsAllocationEnd()	: "null";
    			$slots[$index]['allocAdGameId']	=	$slot->getAdsAllocationGameId();
    			$slots[$index]['allocAdName']	=	$ad->getAdsName();
    			$index++;
    		}
    		$this->slotAllocactions	=	$slots;
    	}
    	$this->slotAllocationCount	=	$index;
    	$tmp	=	array();
    	$tmp['status']		=	'201';
    	$this->msg			=	$tmp;
    }
    
    public function getSlotInfo() {
    	$slotId	=	intval($_POST['slot_id']);

    	if(!$slotId) {
    		$tmp['status']	=	'300';
    		$tmp['text']	=	"Fehler: keine Slot ID gefunden (1)";
    		$this->msg	=	$tmp;
    		return;
    	}

    	$slotInfo	=	FfbAdsSlotPeer::retrieveByPK($slotId);

    	if(!$slotInfo) {
    		$tmp['status']	=	'300';
    		$tmp['text']	=	"Fehler: keine Slot ID gefunden (2)";
    		$this->msg	=	$tmp;
    		return;
    	}

    	$tmp['slotId']		=	$slotInfo->getAdsSlotId();
    	$tmp['slotName']	=	$slotInfo->getAdsSlotName();
    	$tmp['slotCss']		=	$slotInfo->getAdsSlotCssClass() ? $slotInfo->getAdsSlotCssClass() : " ";
    	$this->slotInfo		=	$tmp;
    	$tmp	=	array();
    	$tmp['status']		=	'201';
    	$this->msg			=	$tmp;
    }
    
    
    public function setAd() {
    	$oldAd	=	FfbAdsPeer::retrieveByPK($_POST['ad_id']);
    	if(!$oldAd) {
    		$tmp['status']	=	'300';
    		$tmp['text']	=	"Fehler: kein Werbe ID gefunden";
    		$this->msg	=	$tmp;
    		return;
   		}
    	if(trim($_POST['ad_name'])) {
    		$oldAd->setAdsName(trim($_POST['ad_name']));
    	} else {
    		$tmp['status']	=	'301';
    		$tmp['text']	=	"Fehler: kein Name angegeben";
    		$this->msg	=	$tmp;
    		return;
    	}
    	
   		$oldAd->setAdsCode(trim($_POST['ad_code']));
   		$oldAd->save();
   		$tmp['status']	=	'201';
   		$tmp['text']	=	"Werbeblock upgedated!";
    	$this->msg	=	$tmp;
    }
    
    
    private function newAd() {
    	$adsName	=	trim($_POST['newadname']);
    	if(!$adsName) {
    		$tmp 			=	array();
    		$tmp[]			=	'Leerer Werbeblock Name.';
    		$this->errors	=	$tmp;
    		return;	
    	}
		$criteria	=	new Criteria();
    	$criteria->add(FfbAdsPeer::ADS_NAME, $adsName);
    	$criteria->setLimit(1);		
    	$adExists	= FfbAdsPeer::doSelect($criteria);
    	if($adExists[0]!=null) {
    		$tmp 			=	array();
    		$tmp[]			=	'Werbeblock Name existiert bereits.';
    		$this->errors	=	$tmp;
    		return;	
    	}
    	
    	$newAd	=	new FfbAds();
    	$newAd->setAdsName($adsName);
    	$newAd->save();
    	$this->administration_answer = "Neuer Werbeblock: '$adsName' erfolgreich angelegt.";
    }
    
    public function getAdInfo() {
    	$adInfo	= 	FfbAdsPeer::retrieveByPK(trim($_REQUEST['ads_id']));
    	if($adInfo!=null) {
    		$ad			=	array();
    		$ad['id']	=	$adInfo->getAdsId();
    		$ad['name']	=	$adInfo->getAdsName();
    		$ad['code']	=	$adInfo->getAdsCode()	?	$adInfo->getAdsCode() : " ";
    		$this->adInfo	=	$ad;
    	}
    	
    }
    
    public function getAds() {
    	$criteria = new Criteria();
    	$criteria->addAscendingOrderByColumn(FfbAdsPeer::ADS_NAME);
    	$ads = FfbAdsPeer::doSelect($criteria);
    	$ad	= array();
    	$index = 0;
    	foreach($ads AS $elem) {
    		$ad[$index]['id'] 	=	$elem->getAdsId();
    		$ad[$index]['name']	=	$elem->getAdsName();
    		$ad[$index]['code']	=	$elem->getAdsCode() ? $elem->getAdsCode() : " ";
    		$index++;
    	}
    	$this->adsCount	=	$index;
    	$this->ads 		=	$ad;
    }
    
    
    private function newAdsSlot() {
    	$slotname	=	trim($_POST['newslotname']);
    	$slotcss	=	trim($_POST['newslotcss']);
    	if(!$slotname) {
    		$tmp 			=	array();
    		$tmp[]			=	'Leerer Slot Name.';
    		$this->errors	=	$tmp;
    		return;	
    	}
    	
    	$criteria 	=	new Criteria();
    	$criteria->add(FfbAdsSlotPeer::ADS_SLOT_NAME, $slotname);
    	$criteria->setLimit(1);
    	$slotExists	= FfbAdsSlotPeer::doSelect($criteria);
    	if($slotExists[0]!=null) {
    		$tmp 			=	array();
    		$tmp[]			=	'Slot Name existiert bereits.';
    		$this->errors	=	$tmp;
    		return;
    	}
    	
    	$newSlot	=	new FfbAdsSlot();
    	$newSlot->setAdsSlotName($slotname);
    	$newSlot->setAdsSlotCssClass($slotcss);
    	$newSlot->save();
    	$this->administration_answer = "Neuer Werbeslot: '$slotname' (CSS: $slotcss) erfolgreich angelegt.";
    }
    
    public function getAdsSlots() {
    	$criteria = new Criteria();
    	$criteria->addAscendingOrderByColumn(FfbAdsSlotPeer::ADS_SLOT_NAME);
    	$adsSlots = FfbAdsSlotPeer::doSelect($criteria);
    	$index		=	0;
		if($adsSlots) {
    		$allSlots	=	array();
    		foreach($adsSlots AS $slot) {
    			$tmp		=	array();
    			$tmp['id']	=	$slot->getAdsSlotId();
    			$tmp['name']=	$slot->getAdsSlotName();
    			$allSlots[]	=	$tmp;
    			$index++;
    		}
    		$this->adsSlots	=	$allSlots;
    	}
    	$this->slotCount	=	$index;
    }
    
    public function __default() {
		$this->getAdsSlots();
		$this->getAds();
    }
    
    public function __destruct()
    {
        parent::__destruct();
    }
		
	
}