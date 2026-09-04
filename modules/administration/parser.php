<?php

/**
 * ADMIN - PARSER-Klasse;
 * verschiedene Dinge parsen
 *
 * @author Gritschacher Tobias
 * @copyright 09/2008
 * @version 0.1
 *
 */

class parser extends FFB_Auth_AdminFfb {

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'parser.php';
    }

    public function __default() {
        echo "please call desired function!";
        exit();
    }

    public function parseNamefiles() {
        //echo 'do not call this!';
        //exit();

        $path = '/www/htdocs/w005c0bf/ffb_onlinetest/parserfiles/namefiles/';
        $dir = opendir($path);
        $sum_found = 0;
        $sum_notfound = 0;
        while(($file = readdir($dir)) !== false) {
            if($file != '.' && $file != '..') {
                $dateiname = $path.$file;
                echo $dateiname.'<br>';

                $datei = fopen( $dateiname , "r" );
                $inhalt = fread($datei ,filesize($dateiname));
                $items = explode(';', trim($inhalt));
                $exist_items = 0;
                $new_items = 0;
                foreach($items as $line) {
                    $elements = explode(',',trim($line));
                    $fname = $elements[1];
                    $lname = $elements[0];
                    $nationality = $elements[2];
                    $image = $elements[3];
                    if($image != 'nobody.jpg' && $image != 'somebody.jpg') {
                        $criteria = new Criteria();
                        $criteria->add(FfbPlayerPeer::PLAYER_FNAME, $fname);
                        $criteria->add(FfbPlayerPeer::PLAYER_LNAME, $lname);
                        $criteria->add(FfbPlayerPeer::PLAYER_NATIONALITY, $nationality);
                        $criteria->setLimit(1);
                        $exist_item = FfbPlayerPeer::doSelect($criteria);
                        if($exist_item) {
                            $playerteams = $exist_item[0]->getFfbPlayerteams();
                            if($playerteams) {
                                $i=0;
                                foreach($playerteams as $playerteam) {
                                    $playerteam_data[$i]['id'] = $playerteam->getPlayerteamId();
                                    $playerteam_data[$i]['team'] = $playerteam->getPlayerteamTeamId();
                                    $playerteam_data[$i]['picture'] = $playerteam->getPlayerteamPlayerPicture();
                                    $i++;
                                }
                            }

                            foreach($playerteam_data as $data) {
                                if($data['picture'] == '' || $data['picture'] == NULL || $data['picture'] == 'null') {
                                    echo 'FOUND: '.mb_convert_encoding((string)$fname, 'ISO-8859-1', 'UTF-8').' '.mb_convert_encoding((string)$lname, 'ISO-8859-1', 'UTF-8').' - '.$nationality.'<br>';
                                    $orig_image = '/www/htdocs/w005c0bf/ffb_onlinetest/images/ffb/players/orig/'.$image;
                                    if(!is_dir('/www/htdocs/w005c0bf/ffb_onlinetest/images/ffb/players/'.$data['team']))
                                        mkdir('/www/htdocs/w005c0bf/ffb_onlinetest/images/ffb/players/'.$data['team']);
                                    $new_image = '/www/htdocs/w005c0bf/ffb_onlinetest/images/ffb/players/'.$data['team'].'/'.$data['id'].'.jpg';
                                    $picture = imagecreatefromjpeg($orig_image);
                                    if(imagejpeg($picture, $new_image)) {
                                        $playerteam_item = FfbPlayerteamPeer::retrieveByPK($data['id']);
                                        $playerteam_item->setPlayerteamPlayerPicture($data['id'].'.jpg');
                                        $playerteam_item->save();
                                        echo 'done: '.$new_image;
                                    } else {
                                        echo 'ERROR: '.$new_image;
                                    }
                                    echo '<br>';
                                    $exist_items++;
                                }
                            }
                        } else {
                            echo '<b>NOT FOUND:</b> '.mb_convert_encoding((string)$fname, 'ISO-8859-1', 'UTF-8').' '.mb_convert_encoding((string)$lname, 'ISO-8859-1', 'UTF-8').' - '.$nationality.'<br>';
                            $new_items++;
                        }
                    }
                }
                $sum_found += $exist_items;
                $sum_notfound += $new_items;
                echo '<em>FOUND: '.$exist_items.' NOT FOUND: '.$new_items.'<br><br></em>';
            }
        }
        echo 'SUM FOUND: '.$sum_found.' SUM NOT FOUND: '.$sum_notfound.'<br><br>';
        exit();
    }
}
?>
