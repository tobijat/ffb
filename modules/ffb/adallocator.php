<?php

/**
 * ADALLOCATOR-Klasse;
 * Werbung verteilen
 *
 * @author Gerald Musser
 * @copyright 10/2009
 * @version 0.1
 *
 */

class adallocator extends FFB_Auth_No {
	    
	public function __construct() {
        parent::__construct();
    }
    
    public function getAd($slotName="",$slotId="") {
    	
    	$slot	= null;
    	if(trim($slotName)) {
    		$criteria	=	new Criteria();
    		$criteria->add(FfbAdsSlotPeer::ADS_SLOT_NAME, trim($slotName));
    		$criteria->setLimit(1);
    		$slot		= 	FfbAdsSlotPeer::doSelect($criteria);
    		$slot		=	$slot[0];
   		} elseif(intval($slotId)>0) {
   			$slot	=	FfbAdsSlotPeer::retrieveByPK(intval($slotId));
   		}
   		if(!$slot)
   			return;
   		
		//IP und User fuer die werbung geblockt wird filtern	
		$remoteAddr	=	$_SERVER['REMOTE_ADDR'];
    	$userId		=	$this->session->user_id;
    	$criteria	=	new Criteria();
    	$criteria->add(FfbNoAdsPeer::NO_ADS_SLOT_ID, $slot->getAdsSlotId());
    	$criteria->addOr(FfbNoAdsPeer::NO_ADS_SLOT_ID, 0);
		$criteria->addAnd(FfbNoAdsPeer::NO_ADS_USER_ID_IP, $remoteAddr);
    	if($userId)
    		$criteria->addOr(FfbNoAdsPeer::NO_ADS_USER_ID_IP, $userId);
   		$criteria->setLimit(1);
   		$noAd		=	FfbNoAdsPeer::doselect($criteria);
   		if($noAd)
   			return;
   		
   			
		$today 		= date("Y-m-d 00:00:00");
		$criteria	= new Criteria();
		$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID, $slot->getAdsSlotId());
		$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_AD_PRIORITY, 0, Criteria::GREATER_THAN);
		$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_END, $today, Criteria::GREATER_EQUAL);
		$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_START, $today, Criteria::LESS_EQUAL);
		$ads		=	FfbAdsAllocationPeer::doSelect($criteria);
		if(!$ads)
			return;
		$bestCoeff	=	null;
		$possAds	=	array();
		foreach($ads AS $ad) {
			if( ($ad->getAdsAllocationAdMax()==0) || ($ad->getAdsAllocationAdMax()<$ad->getAdsAllocationAdCount()) ) {
				$coeff	=	$ad->getAdsAllocationAdCount() / $ad->getAdsAllocationAdPriority();
				if( ($bestCoeff===null) || ($bestCoeff > $coeff) ) {
					$possAds		=	array();
					$tmp			=	array();
					$tmp['allocId']	=	$ad->getAdsAllocationId();
					$tmp['adId']	=	$ad->getAdsAllocationAdsId();
					$possAds[]		=	$tmp;
					$bestCoeff		=	$coeff;
				} elseif( $bestCoeff==$coeff ) {
					$tmp			=	array();
					$tmp['allocId']	=	$ad->getAdsAllocationId();
					$tmp['adId']	=	$ad->getAdsAllocationAdsId();
					$possAds[]		=	$tmp;
				}
			}
		}
		
		$ad		=	null;
		if(count($possAds)==0) {
			return;
		}
		elseif( count($possAds)==1 ) {
			$adId	=	$possAds[0]['adId'];
			$allocId=	$possAds[0]['allocId'];
			$ad		=	FfbAdsPeer::retrieveByPK($adId);
		} else {
			$rand	=	rand(0, ( count($possAds)-1 ));		
			$adId	=	$possAds[$rand]['adId'];
			$allocId=	$possAds[$rand]['allocId'];
			$ad		=	FfbAdsPeer::retrieveByPK($adId);
		}
		if(!$ad)
			return;
		
		$toDisplay	=	"";
		if(trim($slot->getAdsSlotCssClass()))
			$toDisplay	.=	'<div class="' . trim($slot->getAdsSlotCssClass()) . '">';
		
		$toDisplay	.=	$ad->getAdsCode();
						
		if(trim($slot->getAdsSlotCssClass()))
			$toDisplay	.=	'</div>';
		
		//$winnerAdAlloc	=	FfbAdsAllocationPeer::retrieveByPK($allocId);
		//$winnerAdAlloc->setAdsAllocationAdCount( ($winnerAdAlloc->getAdsAllocationAdCount() +1) );
		//$winnerAdAlloc->save();
		
		return $toDisplay;
		
    }
    
    		
	public function __default() {
		$this->getAd("", 1);
    }
    
}