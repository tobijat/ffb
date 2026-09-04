<?php

/**
 * ADMIN - PLAYERMANAGEMENT-Klasse;
 * Admin-Tools
 *
 * @author Gritschacher Tobias
 * @copyright 9/2009
 * @version 0.1
 *
 */

class admintools extends FFB_Auth_AdminFfb {
    private $options;

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'admintools.php';
        $this->options = new FFB_Options($this->session->game_id_admin);
    }

    public function __default() {
        echo 'please choose tool..';
        exit();
    }

    public function checkCatrowebAlive() {
  	  $hosts = array('http://catroidwebtest.ist.tugraz.at', 'http://catroweb.ist.tugraz.at');

  	  $addr = "studium@tobijat.at;tobi@tobijat.at";
    	$repl = "noreply@tobijat.at";
    	$retr = "-f"."webmaster@tobijat.at";
    	$head = "From: "."Catroweb AliveChecker <noreply@tobijat.at>"."\r\n"."Bcc: ".$addr.";\r\n"."X-Mailer: PHP/".phpversion();

  	  foreach($hosts as $host) {
    	  $url = $host."/catroid/aliveCheckerHost";
    	  $host_ok = $this->checkAlive($url);
        $url = $host."/catroid/aliveCheckerDB";
        $db_ok = $this->checkAlive($url);

        if(!$host_ok || !$db_ok) {
          $subj = "CATROWEB: Problem with Host $host!";
          $text = "";
          $text = "A problem with Catroweb on Host $host was detected at ".date('Y-m-d H:i:s').".\n\n";
      	  if(!$host_ok) {
            $problem = "The host is not reachable!";
          } else if(!$db_ok){
            $problem = "The connection to the Database failed!";
          }
          $text .= "Problem:\n$problem\n\n";

      		mail($repl, $subj, wordwrap($text), $head, $retr);
    		}
  		}
  		exit();
    }

    private function checkAlive($url) {
      $data = fopen($url, "r");
      $resp = stream_get_contents($data);
      if(strcmp($resp, '200') == 0) {
        return true;
      } else {
        return false;
      }
    }

    public function updateUserteamPrice() {
		$matchround_id = $_REQUEST['matchround_id'];
		if(!$matchround_id) {
			echo 'no mid!';
			exit();
		}

		$criteria = new Criteria();
		$criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $matchround_id);
		$uts = FfbUserteamPeer::doSelect($criteria);
		if($uts) {
			foreach($uts as $ut) {
				$ut_price = $ut->getUserteamPrice();
				$ut_price_new = 0;
				for($i=1;$i<12;$i++) {
					$criteria = new Criteria();
					$criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
					$fname = 'getUserteamPlayerId'.$i;
					$criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $ut->$fname());
					$criteria->setLimit(1);
					$pp = FfbPlayerpricePeer::doSelect($criteria);
					if($pp) {
						$new_pp = $pp[0]->getPlayerpricePrice();
					} else {
						$fname = 'getFfbPlayerteamRelatedByUserteamPlayerId'.$i;
						$new_pp = $ut->$fname()->getPlayerteamPlayerPrice();
					}
					$ut_price_new += $new_pp;
					//echo 'new pp: '.$new_pp.'<br>';
				}
				echo 'OLD/NEW USERTEAM PRICE FOR UT '.$ut->getUserteamId().': '.$ut_price.'/'.$ut_price_new.'<br>------<br>';
				$ut->setUserteamPrice($ut_price_new);
				$ut->save();
			}

		}

		exit();
	}

    public function setTransfer() {
		$criteria = new Criteria();
		//$criteria->addJoin(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, FfbTeamPeer::TEAM_ID);
		$criteria->addJoin(FfbMatchroundPeer::MATCHROUND_GAME_ID, FfbGamePeer::GAME_ID);
		$criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID);

		//$c1 = $criteria->getNewCriterion(FfbGamePeer::GAME_ID, 2);

		$c1 = $criteria->getNewCriterion(FfbGamePeer::GAME_ID, 3);
		$c1->addOr($criteria->getNewCriterion(FfbGamePeer::GAME_ID, 7));
		$c1->addOr($criteria->getNewCriterion(FfbGamePeer::GAME_ID, 14));
		$c1->addOr($criteria->getNewCriterion(FfbGamePeer::GAME_ID, 1));


		$criteria->add($c1);
		$matchs = FfbmatchPeer::doSelect($criteria);

		$team_ids = array();
		foreach($matchs as $match) {
			$team1 = $match->getFfbTeamRelatedByMatchHometeamId();
			$team2 = $match->getFfbTeamRelatedByMatchGuestteamId();
			$team_ids[$team1->getTeamId()] = $team1;
			$team_ids[$team1->getTeamId()] = $team2;

			//echo utf8_decode($team1->getTeamName()).'<br>';
			//echo utf8_decode($team2->getTeamName()).'<br>';
		}

		$transfer = '2008-01-01 00:00:00';
		//$transfer = '2010-03-19 00:00:00';
		foreach($team_ids as $id=>$team) {
			//$criteria = new Criteria();
			//$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_ID, 4346, Criteria::GREATER_THAN);
			//$pts = $team->getFfbPlayerteams($criteria);
			$pts = $team->getFfbPlayerteams();
			echo utf8_decode($team->getTeamName()).' '.count($pts).'/';
			$i=0;
			foreach($pts as $pt) {
				$pt->setPlayerteamDateTransfer($transfer);
				$pt->save();
				//echo $pt->getPlayerteamId().': '.utf8_decode($pt->getFfbPlayer()->getPlayerFname()).' '.utf8_decode($pt->getFfbPlayer()->getPlayerLname()).'<br>';
				$i++;
			}
			echo $i.'<br>';

		}
		exit();
	}

    public function lastMatches() {
    	$num_matches = 10;
		$game_id = $session->game_id_player;
    	$criteria = new Criteria();
    	$criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_DATE_TRANSFER);
		$pt_id_items = FfbPlayerteamPeer::retrieveByPK($_REQUEST['ptid'])->getFfbPlayer()->getFfbPlayerteams($criteria);
    	$pt_ids = array();
    	$team_ids = array();
    	$criteria = new Criteria();
    	$criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID);
    	$criteria->addJoin(FfbMatchroundPeer::MATCHROUND_GAME_ID, FfbGamePeer::GAME_ID);
    	$criteria->add(FfbGamePeer::GAME_ID, $game_id, Criteria::NOT_EQUAL);
    	$criteria->add(FfbMatchPeer::MATCH_DATE, date('Y-m-d H:i:s', time()), Criteria::LESS_THAN);
    	$criteria->add(FfbMatchPeer::MATCH_HOMESCORE, -1, Criteria::GREATER_THAN);
    	if($pt_id_items) {
			$i=0;
			foreach($pt_id_items as $item) {
				$pt_ids[] = $item->getPlayerteamId();
				$pt_status = $item->getPlayerteamStatus();
				$team_ids[] = $item->getFfbTeam()->getTeamId();
				$c2 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_HOMETEAM_ID, $item->getFfbTeam()->getTeamId());
				$c2->addOr($criteria->getNewCriterion(FfbMatchPeer::MATCH_GUESTTEAM_ID, $item->getFfbTeam()->getTeamId()));

				if($last_item) {
					$c2->addAnd($criteria->getNewCriterion(FfbMatchPeer::MATCH_DATE, $last_item->getPlayerteamDateTransfer(), Criteria::LESS_THAN));
				} else {
					$c2->addAnd($criteria->getNewCriterion(FfbMatchPeer::MATCH_DATE, $item->getPlayerteamDateTransfer(), Criteria::GREATER_THAN));
				}
				$criteria->addOr($c2);
				$last_item = $item;
				$i++;
			}
		}
		$criteria->addDescendingOrderByColumn(FfbMatchPeer::MATCH_DATE);
		$criteria->addDescendingOrderByColumn(FfbMatchPeer::MATCH_ID);
		$criteria->addGroupByColumn(FfbMatchPeer::MATCH_ROUND);
		$criteria->setLimit($num_matches);

		$match_items = FfbMatchPeer::doSelect($criteria);
		$pm_array = array();
		if($match_items) {
			$i=0;
			foreach($match_items as $item) {
				echo $item->getMatchDate().' ';
				echo $item->getFfbMatchround()->getMatchroundTitle().' ';
				echo utf8_decode($item->getFfbTeamRelatedByMatchHometeamId()->getTeamName()).' '.utf8_decode($item->getFfbTeamRelatedByMatchGuestteamId()->getTeamName()).'<br>';
			}
		}
		print_r($pt_ids);
		exit();
	}

	/*
    public function setTeamFid() {
		//$this->status = 200;
		$team_id = $_POST['team_id'];
		$tm_id = $_POST['tm_id'];
		$tm_name = $_POST['tm_name'];

		$criteria = new Criteria();
		$criteria->add(FfbTeamfidPeer::TEAMFID_TEAM_ID, $team_id);
		$criteria->setLimit(1);
		if($tfid = FfbTeamfidPeer::doSelect($criteria)) {
			$new_tfid = $tfid[0];
		} else {
			$new_tfid = new FfbTeamfid();
			$new_tfid->setTeamfidTeamId($team_id);
		}
		$new_tfid->setTeamfidFidTm($tm_id);
		$new_tfid->setTeamfidNameTm($tm_name);
		$new_tfid->setTeamfidNameWf($tm_name);
		$tm_url = 'http://www.transfermarkt.at/de/'.$tm_name.'/startseite/nationalmannschaft_'.$tm_id.'.html';
		$wf_url = 'http://www.weltfussball.at/teams/'.$tm_name.'-team/';
		$new_tfid->setTeamfidUrlTm($tm_url);
		$new_tfid->setTeamfidUrlWf($wf_url);

		//$new_tfid->save();

		echo $team_id.'/'.$tm_id.'/'.$tm_name;
	}
	*/

    public function setPlayerFidWf() {
		$criteria = new Criteria();
		$criteria->add(FfbPlayerfidPeer::PLAYERFID_NAME_WF, '');
		$pfids = FfbPlayerfidPeer::doSelect($criteria);
		foreach($pfids as $pfid) {
			$pfid->setPlayerfidNameWf($pfid->getPlayerfidNameTm());
			$pfid->save();
		}
		echo count($pfids);
		exit();
	}

    public function generateKey() {
		echo md5(uniqid(time())).'<br>';
		exit();
	}

	public function testMailMime() {
		include('Mail.php');
		include('Mail/mime.php');
		$mime = new Mail_mime("\n");
		exit();
	}

    public function setMailservice() {
		$criteria = new Criteria();
		$criteria->add(WebUserPeer::USER_STATUS, 'active');
		$us = WebUserPeer::doSelect($criteria);
		foreach($us as $item) {
			if(!$item->getUserMailservice()) {
				$up = WebUserPermissionsPeer::retrieveByPK($item->getUserId());
				$up->setUserPermissionsFfbMailserviceReminder(0);
				$up->save();
			}
		}
		echo 'fertig!';
		exit();
	}

	public function checkInconsistentPlayers() {
        $criteria = new Criteria();
        $pts = FfbPlayerteamPeer::doSelect($criteria);
        foreach($pts as $pt) {
			$pl = $pt->getFfbPlayer();
			if($pl) {
				echo 'found: '.$pl->getPlayerFname().' '.$pl->getPlayerLname().'<br>';
			} else {
				echo 'not found: '.$pt->getPlayerteamId().'<br>';
			}
		}
		exit();
    }

    public function createPicMailPermissions() {
    	$criteria = new Criteria();
    	$criteria->addGroupByColumn(PicPermissionPeer::PERMISSION_EMAIL);
    	$perms = PicPermissionPeer::doSelect($criteria);
    	echo 'num: '.count($perms);
    	foreach($perms as $perm) {
			$mp = new PicPermissionMail();
			$mp->setPermissionMailEmail($perm->getPermissionEmail());
			$mp->setPermissionMailKey($perm->getPermissionKey());
			$mp->save();
		}

    	exit();
    }

    public function createPicAlbumPermissions() {
    	$criteria = new Criteria();
    	$perms = PicPermissionPeer::doSelect($criteria);
    	echo 'num: '.count($perms);
    	foreach($perms as $perm) {
			$mp = new PicPermissionAlbum();
			$mp->setPermissionAlbumEmail($perm->getPermissionEmail());
			$mp->setPermissionAlbumAlbumId($perm->getPermissionAlbum());
			$mp->setPermissionAlbumStatus($perm->getPermissionStatus());
			$mp->setPermissionAlbumOwnerKey($perm->getPermissionOwner());
			$mp->setPermissionAlbumDescription($perm->getPermissionDescription());
			$mp->save();
		}

    	exit();
    }

	public function createWebUserDetails() {
		$criteria = new Criteria();
		$users = WebUserPeer::doSelect($criteria);
		$num_perm = 0;
		$num_details = 0;
		$num_users = 0;
		foreach($users as $user) {
			$user_id = $user->getUserId();
			$ex_user_details = WebUserDetailsPeer::retrieveByPk($user_id);
			$ex_user_perm = WebUserPermissionsPeer::retrieveByPk($user_id);
			if(!$ex_user_details) {
				$new_user_details = new WebUserDetails();
				$new_user_details->setUserId($user_id);
				$new_user_details->save();
				$num_details++;
			}
			if(!$ex_user_perm) {
				$user_mailservice_reminder = md5(uniqid(time()));
				$user_mailservice_info = md5(uniqid(time()));
				$user_facebook_ffb = md5(uniqid(time()));
				$user_facebook_pictory = md5(uniqid(time()));
				$new_user_permissions = new WebUserPermissions();
				$new_user_permissions->setUserId($user_id);
				$new_user_permissions->setUserPermissionsFfbMailserviceReminder($user_mailservice_reminder);
				$new_user_permissions->setUserPermissionsFfbMailserviceInfo($user_mailservice_info);
				$new_user_permissions->setUserPermissionsFfbFacebook($user_facebook_ffb);
				$new_user_permissions->setUserPermissionsPictoryFacebook($user_facebook_pictory);
				$new_user_permissions->save();
				$num_perm++;
			}
			$num_users++;
		}
		echo "$num_details WebUserDetails and $num_perm WebUserPermissions cretated from $num_users WebUsers.<br>";
		exit();
	}

	public function testRegistry() {
		echo 'test: '.$this->config->config_file.'<br>';
		echo 'uid: '.$this->advert->getUserId().'<br>';
		exit();
	}

	public function testSubdomain() {
		echo 'module_name: '.$this->moduleName.'<br>';
		echo 'sd_name: '.$this->subdomainName.'<br>';
		exit();
	}

    public function calc_avg_price() {
        $criteria = new Criteria();
        $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYER_POWER, 0, Criteria::GREATER_THAN);
        $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $_REQUEST['matchround']);
        $pp_items = FfbPlayerpricePeer::doSelect($criteria);
        $sum = 0;

        if($pp_items) {
	        foreach($pp_items as $item) {
	            $sum += $item->getPlayerpricePrice();
	        }
        } else {
			echo 'error';
			exit();
		}
        echo 'Price SUM: '.$sum.'<br>';
        echo 'Num Entries: '.count($pp_items).'<br>';
        echo 'AVG Price: '.($sum/count($pp_items)).'<br>';
        echo 'Credits suggestion (ceil(AVG)*11 + 11%): '.(ceil($sum/count($pp_items))*11*1.11).'<br>';
        exit();
        //$c1 = $criteria->getNewCriterion(FfbPlayerprice)
    }

    public function set_userteam_price() {
        $game_id = $this->session->game_id_admin;
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);

        $ut_items = FfbUserteamPeer::doSelectJoinFfbMatchround($criteria);
        $pm = $this->options->options_game_pointsmode;
        foreach($ut_items as $item) {
            $player_price_sum = 0;
            $pt_array = array();
            unset($pt_array);
            $pt_array[0] = $item->getFfbPlayerteamRelatedByUserteamPlayerId1();
            $pt_array[1] = $item->getFfbPlayerteamRelatedByUserteamPlayerId2();
            $pt_array[2] = $item->getFfbPlayerteamRelatedByUserteamPlayerId3();
            $pt_array[3] = $item->getFfbPlayerteamRelatedByUserteamPlayerId4();
            $pt_array[4] = $item->getFfbPlayerteamRelatedByUserteamPlayerId5();
            $pt_array[5] = $item->getFfbPlayerteamRelatedByUserteamPlayerId6();
            $pt_array[6] = $item->getFfbPlayerteamRelatedByUserteamPlayerId7();
            $pt_array[7] = $item->getFfbPlayerteamRelatedByUserteamPlayerId8();
            $pt_array[8] = $item->getFfbPlayerteamRelatedByUserteamPlayerId9();
            $pt_array[9] = $item->getFfbPlayerteamRelatedByUserteamPlayerId10();
            $pt_array[10] = $item->getFfbPlayerteamRelatedByUserteamPlayerId11();
            for($i=0;$i<11;$i++) {
                $criteria = new Criteria();
                $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $pt_array[$i]->getPlayerteamId());
                $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $item->getUserteamMatchroundId());
                $criteria->setLimit(1);
                //echo 'round: '.$item->getUserteamMatchroundId().'<br>';
                $pp_items = FfbPlayerpricePeer::doSelect($criteria);
                //echo 'pp_items: '.count($pp_items).'<br>';
                if($pm == 'new') {
                    if($pp_items) {
                        $player_price_sum += $pp_items[0]->getPlayerpricePrice();
                    } else {
                        $player_price_sum = $pt_array[0]->getPlayerteamPlayerPrice()+$pt_array[1]->getPlayerteamPlayerPrice()+
                                            $pt_array[2]->getPlayerteamPlayerPrice()+$pt_array[3]->getPlayerteamPlayerPrice()+
                                            $pt_array[4]->getPlayerteamPlayerPrice()+$pt_array[5]->getPlayerteamPlayerPrice()+
                                            $pt_array[6]->getPlayerteamPlayerPrice()+$pt_array[7]->getPlayerteamPlayerPrice()+
                                            $pt_array[8]->getPlayerteamPlayerPrice()+$pt_array[9]->getPlayerteamPlayerPrice()+
                                            $pt_array[10]->getPlayerteamPlayerPrice();
                    }
                } else {
                    $player_price_sum = $pt_array[0]->getPlayerteamPlayerPrice()+$pt_array[1]->getPlayerteamPlayerPrice()+
                                        $pt_array[2]->getPlayerteamPlayerPrice()+$pt_array[3]->getPlayerteamPlayerPrice()+
                                        $pt_array[4]->getPlayerteamPlayerPrice()+$pt_array[5]->getPlayerteamPlayerPrice()+
                                        $pt_array[6]->getPlayerteamPlayerPrice()+$pt_array[7]->getPlayerteamPlayerPrice()+
                                        $pt_array[8]->getPlayerteamPlayerPrice()+$pt_array[9]->getPlayerteamPlayerPrice()+
                                        $pt_array[10]->getPlayerteamPlayerPrice();
                }
            }
            echo 'round: '.$item->getUserteamMatchroundId().'<br>';
            echo 'price: '.$player_price_sum.'<br>';

            $item->setUserteamPrice($player_price_sum);
            $item->save();
        }
        echo 'fertig.';
        exit();
    }

    public function setPlayerfidTm() {
        $criteria = new Criteria();
        $playerteam_items = FfbPlayerteamPeer::doSelect($criteria);
        foreach($playerteam_items as $item) {
            $player_name_fid_fifa = $item->getFfbPlayer()->getPlayerFname().' '.$item->getFfbPlayer()->getPlayerLname();
            $criteria = new Criteria();
            $criteria->add(FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID, $item->getPlayerteamId());
            $criteria->setLimit(1);
            $playerfid_items = FfbPlayerfidPeer::doSelect($criteria);
            if($playerfid_items) {
                $update_item = $playerfid_items[0];
            } else {
                $update_item = new FfbPlayerfid();
                $update_item->setPlayerfidPlayerteamId($item->getPlayerteamId());
                $update_item->setPlayerfidTeamId($item->getPlayerteamTeamId());
            }
            $update_item->setPlayerfidNameUefa($player_name_fid_fifa);
            $update_item->save();
        }
        echo 'finished';
        exit();
    }

	public function testMail() {
		$to = array(1);
		$mail = new FFB_Mail($this->config, $to, 'testsubject', 'testmessage', 'force', 'admintools');

		echo 'sent mails: '.$mail->send().'<br>';
		exit;
	}

	public function testHtmlMail() {
		$to = array(1);
		$mail = new FFB_Mail($this->config, $to, 'testsubject', 'testmessage', 'force', 'admintools');

		echo 'sent mails: '.$mail->sendHtml().'<br>';
		exit;
	}
}
?>