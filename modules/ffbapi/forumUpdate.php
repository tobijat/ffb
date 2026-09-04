<?php

  /**
 * Forum Update Class
 * pack all functions in here to change the forum database
 *
 * @author Gerald Musser
 * @copyright 02/2010
 * @version 1.0
 */

require_once('forumSQLConnect.php');
class forumUpdate 
{
	private $maxWidth = 90;
	private $maxHeight = 90;
    public function __construct()
    {
   	}
   	
	//update the remote avatar from the forum
	//call this function only if the $avatarUrl already exists as a real image
	public function updateAvatar($avatarUrl, $userNick)  {
		$size = getimagesize($avatarUrl);
		if($size) {
			$width 	= $size[0];
			$height = $size[1];
			if($width>$this->maxWidth) {
				//$tmpWidth = $width;
				$ratio	= ($width / $this->maxWidth);
				$width	= $this->maxWidth;
				$height = ( $height / $ratio ); 
			}
			if($height>$this->maxHeight) {
				$ratio	= ($height / $this->maxHeight);
				$height = $this->maxHeight;
				$width	= ( $width / $ratio );				
			}
			$width	= round($width, 0);
			$height = round($height, 0);
			$query = "	UPDATE
							ffb_forum_users
							SET user_avatar='$avatarUrl', 
							user_avatar_type=2, 
							user_avatar_width=$width, 
							user_avatar_height=$height
						WHERE
							(username='$userNick' 
							OR username_clean='$userNick')
						LIMIT 1;";

			$result = send_query($query);
			if($result) {
				return 1;
			} else {
				return -1;
			}
			
		} else {
			return -1;
		}
		
	}  	
   	
   	
    public function __destruct()
    {
        //parent::__destruct();
    }
   	
   	
}

//$tmp = new forumUpdate();
//$tmp=null;