<?php

/**
 * FFB_Mail.php
 *
 * @author Gritschacher Tobias
 * @copyright 10/2009
 * @version 0.1
 *
 * Klasse zum Senden von Email-Messages
 */

class FFB_Mail {
    private $_RETURN;
	private $_REPLY;
	private $_FROM;
	private $_GREEZ;
	private $_URL;
	private $_FOOTER = "-- \nDu kannst auf dieses E-Mail nicht antworten. Wende dich bei Fragen oder Problemen an die auf der Website angegebene Adresse!\n";

	private $to_array;
	private $subject;
	private $message;
	private $message_html;
	private $type;
	private $sender;
	private $config;

	/**
	* @param $config config-Objekt des aufrufenden Moduls / einfach $this->config �bergeben
	* @param $to_array Array mit den user_ids an die gemailt werden soll
	* @param $subject Subject der Email
	* @param $message der Body der Email
	* @param $type 'reminder' (geht nur an die im $to_array, die den Reminder aktiviert haben)
	* 			   'info' (geht nur an die im $to_array, die Infomails aktiviert haben)
	* 			   'force' (geht an alle in $to_array - nur im Notfall verwenden!!)
	* @param $sender String der angibt wer gesendet hat - nur f�rs Log
	*
	* */
	public function __construct($config, $to_array, $subject, $message, $type, $sender='N/A', $message_html='') {
		$this->config = $config;
		$this->to_array = $to_array;
		$this->subject = $subject;
		$this->message = $message;
		$this->message_html = $message_html;
		$this->type = $type;
		$this->sender = $sender;

		$this->_RETURN = "-f".strval($config->mail_return_address);
		$this->_REPLY = strval($config->mail_reply_address);
		$this->_FROM = strval($config->mail_from_address);
		$this->_GREEZ = strval($config->mail_greez);
		$this->_URL = strval($config->mail_url);
	}

	private function getPermission($user_id) {
		$user_perm = WebUserPermissionsPeer::retrieveByPK($user_id);
		$server_name = $_SERVER["SERVER_NAME"];
		if($this->type == 'reminder') {
			$mailtype_name = 'Erinnerungsmails';
			$ms_code = $user_perm->getUserPermissionsFfbMailserviceReminder();
			$ms_link = 'http://'.$server_name.'/users/mailservice/cancel.html?t=r&id='.$ms_code.'-'.$user_id;
		} elseif($this->type == 'info') {
			$mailtype_name = 'Infomails';
			$ms_code = $user_perm->getUserPermissionsFfbMailserviceInfo();
			$ms_link = 'http://'.$server_name.'/users/mailservice/cancel.html?t=i&id='.$ms_code.'-'.$user_id;
		} else {
			return false;
		}

		if($ms_code) {
			$ms_text = '';
			$ms_text .= "Wenn du keine $mailtype_name mehr bekommen möchtest, kannst du sie in deinem Profil deaktivieren oder auf folgenden Link klicken um sie zu deaktivieren:\n";
			$ms_text .= $ms_link."\n";
			$ms_text .= "ACHTUNG: Wenn du diesen Link anklickst, wirst du bei SoccerSportsfan ausgeloggt und musst dich neu einloggen.";
			return $ms_text;
		} else {
			return false;
		}
	}

	private function getMailArray() {
		$mail_array = array();
		$i=0;
		foreach($this->to_array as $user_id) {
			$user = WebUserPeer::retrieveByPK($user_id);
			if($user) {
				$addr = $user->getUserEmail();
				$footer_link = '';
				if($this->type == 'force' || ($footer_link = $this->getPermission($user_id))) {
					$mail_array[$i]['mail_user_id'] = $user_id;
					$mail_array[$i]['mail_to'] = $addr;
					$mail_array[$i]['mail_headers'] = "From: ".$this->_FROM."\r\n"."Bcc: ".$addr.";\r\n"."X-Mailer: PHP/".phpversion()."\r\n"."Content-Type:text/plain;charset=utf-8";
					$mail_array[$i]['mail_subject'] = $this->config->mail_subject_prefix.$this->subject;
					$mail_array[$i]['mail_message'] = wordwrap($this->createPersonalMail($this->message, $user)."\n\n".$this->_GREEZ."\n".$this->_URL."\n\n".$this->_FOOTER.$footer_link."\n");
					if($this->message_html != '') {
						$mail_array[$i]['mail_message_html'] = $this->buildHtmlText(wordwrap($this->createPersonalMail($this->message_html, $user)."\n\n<br><br>".$this->_GREEZ."\n".$this->_URL."\n\n<br><br>".$this->_FOOTER.$footer_link."\n<br>"));
					} else {
						$mail_array[$i]['mail_message_html'] = $this->buildHtmlText($mail_array[$i]['mail_message']);
					}
					$i++;
				}
			}
		}
		return $mail_array;
	}

	private function createPersonalMail($message, $user) {
		$user_nickname = $user->getUserNickname();
		$message = str_replace('{*nickname*}', $user_nickname, $message);
		return $message;
	}

	public function send() {
		if(!is_array($this->to_array) || !$this->subject || !$this->message || !$this->type || !$this->sender) {
			return false;
		}

		$mail_array = $this->getMailArray();

		if(count($mail_array) <= 0) {
			return false;
		} else {
			$num_mails = count($mail_array);
		}

		foreach($mail_array as $email) {
			mail($this->_REPLY, $email['mail_subject'], $email['mail_message'], $email['mail_headers'], $this->_RETURN);
		}
		$this->logEmail($mail_array);

		return $num_mails;
	}

	public function sendHtml() {
		if(!is_array($this->to_array) || !$this->subject || !$this->message || !$this->type || !$this->sender) {
			return false;
		}

		include('Mail.php');
		include('Mail/mime.php');

		$mail_array = $this->getMailArray();

		if(count($mail_array) <= 0) {
			return false;
		} else {
			$num_mails = count($mail_array);
		}

		foreach($mail_array as $email) {
			$text = $email['mail_message'];
			$html = $email['mail_message_html'];
			$crlf = "\n";
			$hdrs = array(
			              'From'    => $this->_FROM,
			              'Subject' => $email['mail_subject'],
			              'Bcc' => $email['mail_to'],
			              'X-Mailer' => "PHP/".phpversion(),
						  "Content-Type:text/html;charset=utf-8",
			              );

			$mime = new Mail_mime($crlf);

			$mime->setTXTBody($text);
			$mime->setHTMLBody($html);

			//do not ever try to call these lines in reverse order
			$body = $mime->get();
			$hdrs = $mime->headers($hdrs);

			$mail =& Mail::factory('mail', array('Return-Path' => $this->_RETURN));
			$mail->send($this->_REPLY, $hdrs, $body);
		}
		$this->logEmail($mail_array);

		return $num_mails;
	}

	private function logEmail($mail_array) {
		$mail = new WebMail();
		$mail->setMailSender($this->sender);
		$mail->setMailDate(date('Y-m-d H:i:s', time()));
		$mail->setMailSubject($this->subject);
		$mail->setMailText($this->message);
		$mail->setMailNumReciepients(count($mail_array));
		$mail->setMailCriteria($this->type);
		$str = '';
		$i=0;
		foreach($mail_array as $email) {
			if($i==0) {
				$str .= $email['mail_user_id'];
			} else {
				$str .= ','.$email['mail_user_id'];
			}
			$i++;
		}
		$mail->setMailTo($str);

		$mail->save();
		return;
	}

	private function buildHtmlText($message_html) {
		//$message_html = nl2br($message_html);
		$html = '';
		$html .= '<html><head>';
		$html .= '<title>Html Mail</title>';
		$html .= '</head>';
		$html .= '<body style="background-color:#FF0000;">';
		$html .= nl2br($message_html);
		$html .= '</body>';
		$html .= '</html>';

		return $html;
	}
}

?>