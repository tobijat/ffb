<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2008
 */
define('IN_PHPBB',true);
class tools_copyFfb2Board extends FFB_Auth_No
{
    public function __construct()
    {
        parent::__construct();
        require_once('ffb/FfbUser.php');
        require_once(INCLUDE_PATH.'utf/utf_normalizer.php');
        require_once(INCLUDE_PATH.'utf/utf_tools.php');
        $phpEx='php';
        global $phpEx;
    }

    public function __default()
    {
        $connection = mysqli_connect(FFB_BOARD_DB_SERVER, FFB_BOARD_DB_USER, FFB_BOARD_DB_PASSWORD)
            	      or die ("Cannot establish connection to server.");
      	$db = mysqli_select_db($connection, FFB_BOARD_DB_NAME)
              or die ("Cannot find database.");
        $criteria = new Criteria();
        $users = WebUserPeer::doSelect($criteria);
        if($users) {
            foreach($users as $user) {
                $sql_ary = array(
                    'username' => $user->getUserNickname(),
                    'user_ip' => $user->getUserIp(),
                    'user_regdate' => strtotime($user->getUserDateRegister()),
                    'username_clean' => utf8_clean_string($user->getUserNickname()),
                    'user_password' => md5($user->getUserPassword()),
                    'user_passchg' => time(),
                    'user_email' => strtolower($user->getUserEmail()),
                    'user_email_hash' => crc32(strtolower($user->getUserEmail())).strlen($user->getUserEmail()),
                    'group_id' => 2,
                    'user_dateformat' => 'D, d.M Y H:i',
                    'user_lang' => 'de',
                    'user_style' => 8,
                    'user_timezone' => 1,
                    'user_form_salt' => substr(md5($user->getUserNickname().time()),4 ,16),
                );
                $username = $user->getUserNickname();
                $query = "SELECT * FROM ffb_forum_users WHERE username='$username'";
                echo $query.'<br>';
                $result = mysqli_query($connection, $query)
                          or die ("Cannot fetch data for USER. Database problem.");
                $found_rows = mysqli_num_rows($result);

                echo $user->getUserNickname().': '.$found_rows.'<br>';
                print_r($sql_ary);
                echo '<br>';

                if($found_rows == 0) {
                    $insert_request='INSERT INTO ffb_forum_users
                    (
                         group_id,
                         user_ip,
                         user_regdate,
                         username,
                         username_clean,
                         user_password,
                         user_passchg,
                         user_email,
                         user_email_hash,
                         user_lang,
                         user_style,
                         user_dateformat,
                         user_form_salt,
                         user_timezone
                    )

                    VALUES(
                      '.$sql_ary['group_id'].',
                      "'.$sql_ary['user_ip'].'",
                      "'.$sql_ary['user_regdate'].'",
                      "'.$sql_ary['username'].'",
                      "'.$sql_ary['username_clean'].'",
                      "'.$sql_ary['user_password'].'",
                      "'.$sql_ary['user_passchg'].'",
                      "'.$sql_ary['user_email'].'",
                      "'.$sql_ary['user_email_hash'].'",
                      "'.$sql_ary['user_lang'].'",
                      "'.$sql_ary['user_style'].'",
                      "'.$sql_ary['user_dateformat'].'",
                      "'.$sql_ary['user_form_salt'].'",
                      "'.$sql_ary['user_timezone'].'"
                      )';


                      //echo $insert_request.'<br>';
                    /*
                      mysqli_query($connection, $insert_request)
                      or die ("Cannot insert USER. Database problem.");
                      $newuser_id = mysqli_insert_id($connection);

                      $insert_request = 'INSERT INTO ffb_forum_user_group (group_id, user_id, group_leader, user_pending) VALUES(2, '.$newuser_id.',0,0)';
                      mysqli_query($connection, $insert_request)
                      or die ("Cannot insert USER into GROUP. Database problem.");
                    */
                }

            }
        }
        mysqli_close($connection);
        exit();
    }
}

?>