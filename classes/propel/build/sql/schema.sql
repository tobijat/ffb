
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- web_user_details
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `web_user_details`;


CREATE TABLE `web_user_details`
(
	`user_id` INTEGER  NOT NULL,
	`user_details_avatar` VARCHAR(255),
	`user_details_photo` VARCHAR(255),
	`user_details_website` VARCHAR(255),
	`user_details_zip` VARCHAR(255),
	`user_details_street` VARCHAR(255),
	`user_details_city` VARCHAR(255),
	`user_details_phone` VARCHAR(255),
	`user_details_ffb_favourite_team` INTEGER,
	`user_details_ffb_own_team` INTEGER,
	`user_details_ffb_own_player` INTEGER,
	`user_details_ffb_selected_game` INTEGER  NOT NULL,
	`user_details_last_update` DATETIME  NOT NULL,
	PRIMARY KEY (`user_id`),
	CONSTRAINT `web_user_details_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `web_user` (`user_id`)
		ON DELETE CASCADE,
	INDEX `web_user_details_FI_2` (`user_details_ffb_favourite_team`),
	CONSTRAINT `web_user_details_FK_2`
		FOREIGN KEY (`user_details_ffb_favourite_team`)
		REFERENCES `ffb_team` (`team_id`)
		ON DELETE CASCADE,
	INDEX `web_user_details_FI_3` (`user_details_ffb_own_team`),
	CONSTRAINT `web_user_details_FK_3`
		FOREIGN KEY (`user_details_ffb_own_team`)
		REFERENCES `ffb_team` (`team_id`)
		ON DELETE CASCADE,
	INDEX `web_user_details_FI_4` (`user_details_ffb_own_player`),
	CONSTRAINT `web_user_details_FK_4`
		FOREIGN KEY (`user_details_ffb_own_player`)
		REFERENCES `ffb_player` (`player_id`)
		ON DELETE CASCADE,
	INDEX `web_user_details_FI_5` (`user_details_ffb_selected_game`),
	CONSTRAINT `web_user_details_FK_5`
		FOREIGN KEY (`user_details_ffb_selected_game`)
		REFERENCES `ffb_game` (`game_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- web_user_permissions
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `web_user_permissions`;


CREATE TABLE `web_user_permissions`
(
	`user_id` INTEGER  NOT NULL,
	`user_permissions_ffb_mailservice_reminder` VARCHAR(255)  NOT NULL,
	`user_permissions_ffb_mailservice_info` VARCHAR(255)  NOT NULL,
	`user_permissions_ffb_visible_profile` TINYINT default 0 NOT NULL,
	`user_permissions_pictory_visible_profile` TINYINT default 0 NOT NULL,
	PRIMARY KEY (`user_id`),
	CONSTRAINT `web_user_permissions_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `web_user` (`user_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_cronjob
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_cronjob`;


CREATE TABLE `ffb_cronjob`
(
	`cronjob_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`cronjob_description` VARCHAR(255)  NOT NULL,
	`cronjob_function` VARCHAR(255)  NOT NULL,
	`cronjob_time_start` DATETIME  NOT NULL,
	`cronjob_time_end` DATETIME  NOT NULL,
	`cronjob_time_lastrun` DATETIME  NOT NULL,
	`cronjob_status` TINYINT default 1 NOT NULL,
	`cronjob_interval_hours` INTEGER default 24 NOT NULL,
	`cronjob_runonce` TINYINT default 0 NOT NULL,
	`cronjob_runhour` INTEGER default 5 NOT NULL,
	PRIMARY KEY (`cronjob_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_comments
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_comments`;


CREATE TABLE `ffb_comments`
(
	`comments_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`comments_user_id` INTEGER  NOT NULL,
	`comments_game_id` INTEGER  NOT NULL,
	`comments_matchround_id` INTEGER  NOT NULL,
	`comments_location` VARCHAR(255)  NOT NULL,
	`comments_text` TEXT  NOT NULL,
	`comments_date` DATETIME  NOT NULL,
	PRIMARY KEY (`comments_id`),
	INDEX `ffb_comments_FI_1` (`comments_user_id`),
	CONSTRAINT `ffb_comments_FK_1`
		FOREIGN KEY (`comments_user_id`)
		REFERENCES `web_user` (`user_id`)
		ON DELETE CASCADE,
	INDEX `ffb_comments_FI_2` (`comments_game_id`),
	CONSTRAINT `ffb_comments_FK_2`
		FOREIGN KEY (`comments_game_id`)
		REFERENCES `ffb_game` (`game_id`)
		ON DELETE CASCADE,
	INDEX `ffb_comments_FI_3` (`comments_matchround_id`),
	CONSTRAINT `ffb_comments_FK_3`
		FOREIGN KEY (`comments_matchround_id`)
		REFERENCES `ffb_matchround` (`matchround_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_apikey
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_apikey`;


CREATE TABLE `ffb_apikey`
(
	`apikey_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`apikey_key` VARCHAR(255)  NOT NULL,
	`apikey_ip` VARCHAR(255)  NOT NULL,
	`apikey_description` VARCHAR(255)  NOT NULL,
	`apikey_lastcall` DATETIME  NOT NULL,
	`apikey_status` TINYINT default 1 NOT NULL,
	PRIMARY KEY (`apikey_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_poll
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_poll`;


CREATE TABLE `ffb_poll`
(
	`poll_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`poll_title` VARCHAR(255)  NOT NULL,
	`poll_start` DATETIME  NOT NULL,
	`poll_end` DATETIME  NOT NULL,
	`poll_game_id` INTEGER  NOT NULL,
	`poll_location` VARCHAR(255)  NOT NULL,
	`poll_type` VARCHAR(255)  NOT NULL,
	`poll_visible` TINYINT default 1 NOT NULL,
	PRIMARY KEY (`poll_id`),
	INDEX `ffb_poll_FI_1` (`poll_game_id`),
	CONSTRAINT `ffb_poll_FK_1`
		FOREIGN KEY (`poll_game_id`)
		REFERENCES `ffb_game` (`game_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_poll_result
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_poll_result`;


CREATE TABLE `ffb_poll_result`
(
	`poll_result_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`poll_result_poll_id` INTEGER  NOT NULL,
	`poll_result_user_id` INTEGER  NOT NULL,
	`poll_result_poll_answer_id` INTEGER  NOT NULL,
	`poll_result_text` TEXT  NOT NULL,
	PRIMARY KEY (`poll_result_id`),
	INDEX `ffb_poll_result_FI_1` (`poll_result_poll_id`),
	CONSTRAINT `ffb_poll_result_FK_1`
		FOREIGN KEY (`poll_result_poll_id`)
		REFERENCES `ffb_poll` (`poll_id`),
	INDEX `ffb_poll_result_FI_2` (`poll_result_poll_answer_id`),
	CONSTRAINT `ffb_poll_result_FK_2`
		FOREIGN KEY (`poll_result_poll_answer_id`)
		REFERENCES `ffb_poll_answer` (`poll_answer_id`),
	INDEX `ffb_poll_result_FI_3` (`poll_result_user_id`),
	CONSTRAINT `ffb_poll_result_FK_3`
		FOREIGN KEY (`poll_result_user_id`)
		REFERENCES `web_user` (`user_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_poll_answer
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_poll_answer`;


CREATE TABLE `ffb_poll_answer`
(
	`poll_answer_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`poll_answer_poll_id` INTEGER  NOT NULL,
	`poll_answer_title` VARCHAR(255)  NOT NULL,
	`poll_answer_count` INTEGER  NOT NULL,
	PRIMARY KEY (`poll_answer_id`),
	INDEX `ffb_poll_answer_FI_1` (`poll_answer_poll_id`),
	CONSTRAINT `ffb_poll_answer_FK_1`
		FOREIGN KEY (`poll_answer_poll_id`)
		REFERENCES `ffb_poll` (`poll_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_team
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_team`;


CREATE TABLE `ffb_team`
(
	`team_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`team_foreign_id` VARCHAR(255)  NOT NULL,
	`team_name` VARCHAR(255)  NOT NULL,
	`team_nationality` VARCHAR(255)  NOT NULL,
	`team_avg_price` DOUBLE  NOT NULL,
	`team_num_players` INTEGER  NOT NULL,
	`team_status` TINYINT default 1 NOT NULL,
	PRIMARY KEY (`team_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_player
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_player`;


CREATE TABLE `ffb_player`
(
	`player_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`player_foreign_id` VARCHAR(255)  NOT NULL,
	`player_fname` VARCHAR(255)  NOT NULL,
	`player_lname` VARCHAR(255)  NOT NULL,
	`player_nationality` VARCHAR(255)  NOT NULL,
	`player_status` INTEGER default 0 NOT NULL,
	`player_status_description` VARCHAR(255) default '',
	PRIMARY KEY (`player_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_playerteam
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_playerteam`;


CREATE TABLE `ffb_playerteam`
(
	`playerteam_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`playerteam_player_id` INTEGER  NOT NULL,
	`playerteam_team_id` INTEGER  NOT NULL,
	`playerteam_player_picture` VARCHAR(255) default '',
	`playerteam_status` TINYINT default 1 NOT NULL,
	`playerteam_player_price` DOUBLE default 0 NOT NULL,
	`playerteam_player_position` VARCHAR(255) default 'd' NOT NULL,
	`playerteam_date_transfer` DATETIME  NOT NULL,
	PRIMARY KEY (`playerteam_id`),
	INDEX `ffb_playerteam_FI_1` (`playerteam_player_id`),
	CONSTRAINT `ffb_playerteam_FK_1`
		FOREIGN KEY (`playerteam_player_id`)
		REFERENCES `ffb_player` (`player_id`)
		ON DELETE CASCADE,
	INDEX `ffb_playerteam_FI_2` (`playerteam_team_id`),
	CONSTRAINT `ffb_playerteam_FK_2`
		FOREIGN KEY (`playerteam_team_id`)
		REFERENCES `ffb_team` (`team_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_playerprice
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_playerprice`;


CREATE TABLE `ffb_playerprice`
(
	`playerprice_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`playerprice_playerteam_id` INTEGER  NOT NULL,
	`playerprice_matchround_id` INTEGER  NOT NULL,
	`playerprice_price` DOUBLE default 0 NOT NULL,
	`playerprice_player_power` DOUBLE default 0 NOT NULL,
	`playerprice_av_power` DOUBLE default 0 NOT NULL,
	PRIMARY KEY (`playerprice_id`),
	INDEX `ffb_playerprice_FI_1` (`playerprice_playerteam_id`),
	CONSTRAINT `ffb_playerprice_FK_1`
		FOREIGN KEY (`playerprice_playerteam_id`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE CASCADE,
	INDEX `ffb_playerprice_FI_2` (`playerprice_matchround_id`),
	CONSTRAINT `ffb_playerprice_FK_2`
		FOREIGN KEY (`playerprice_matchround_id`)
		REFERENCES `ffb_matchround` (`matchround_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_invitation
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_invitation`;


CREATE TABLE `ffb_invitation`
(
	`invitation_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`invitation_sender_id` INTEGER  NOT NULL,
	`invitation_email` VARCHAR(255)  NOT NULL,
	`invitation_date` DATETIME  NOT NULL,
	PRIMARY KEY (`invitation_id`),
	INDEX `ffb_invitation_FI_1` (`invitation_sender_id`),
	CONSTRAINT `ffb_invitation_FK_1`
		FOREIGN KEY (`invitation_sender_id`)
		REFERENCES `web_user` (`user_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_match
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_match`;


CREATE TABLE `ffb_match`
(
	`match_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`match_round` INTEGER  NOT NULL,
	`match_hometeam_id` INTEGER  NOT NULL,
	`match_guestteam_id` INTEGER  NOT NULL,
	`match_homescore` VARCHAR(255) default '',
	`match_guestscore` VARCHAR(255) default '',
	`match_homescore_penalty` VARCHAR(255) default '',
	`match_guestscore_penalty` VARCHAR(255) default '',
	`match_date` DATETIME  NOT NULL,
	`match_minutes` INTEGER default 0 NOT NULL,
	`match_status` VARCHAR(255) default '',
	`match_url` VARCHAR(255) default '',
	PRIMARY KEY (`match_id`),
	INDEX `ffb_match_FI_1` (`match_round`),
	CONSTRAINT `ffb_match_FK_1`
		FOREIGN KEY (`match_round`)
		REFERENCES `ffb_matchround` (`matchround_id`)
		ON DELETE CASCADE,
	INDEX `ffb_match_FI_2` (`match_hometeam_id`),
	CONSTRAINT `ffb_match_FK_2`
		FOREIGN KEY (`match_hometeam_id`)
		REFERENCES `ffb_team` (`team_id`)
		ON DELETE CASCADE,
	INDEX `ffb_match_FI_3` (`match_guestteam_id`),
	CONSTRAINT `ffb_match_FK_3`
		FOREIGN KEY (`match_guestteam_id`)
		REFERENCES `ffb_team` (`team_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_goal
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_goal`;


CREATE TABLE `ffb_goal`
(
	`goal_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`goal_match_id` INTEGER  NOT NULL,
	`goal_playerteam_id` INTEGER  NOT NULL,
	`goal_minute` INTEGER  NOT NULL,
	`goal_owngoal` TINYINT default 0 NOT NULL,
	`goal_penalty` TINYINT default 0 NOT NULL,
	`goal_penaltyshootout` TINYINT default 0 NOT NULL,
	PRIMARY KEY (`goal_id`),
	INDEX `ffb_goal_FI_1` (`goal_match_id`),
	CONSTRAINT `ffb_goal_FK_1`
		FOREIGN KEY (`goal_match_id`)
		REFERENCES `ffb_match` (`match_id`)
		ON DELETE CASCADE,
	INDEX `ffb_goal_FI_2` (`goal_playerteam_id`),
	CONSTRAINT `ffb_goal_FK_2`
		FOREIGN KEY (`goal_playerteam_id`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_psgoal
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_psgoal`;


CREATE TABLE `ffb_psgoal`
(
	`psgoal_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`psgoal_match_id` INTEGER  NOT NULL,
	`psgoal_playerteam_id` INTEGER  NOT NULL,
	`psgoal_minute` INTEGER  NOT NULL,
	`psgoal_hit` TINYINT default 0 NOT NULL,
	`psgoal_fail` TINYINT default 0 NOT NULL,
	PRIMARY KEY (`psgoal_id`),
	INDEX `ffb_psgoal_FI_1` (`psgoal_match_id`),
	CONSTRAINT `ffb_psgoal_FK_1`
		FOREIGN KEY (`psgoal_match_id`)
		REFERENCES `ffb_match` (`match_id`)
		ON DELETE CASCADE,
	INDEX `ffb_psgoal_FI_2` (`psgoal_playerteam_id`),
	CONSTRAINT `ffb_psgoal_FK_2`
		FOREIGN KEY (`psgoal_playerteam_id`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_matchround
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_matchround`;


CREATE TABLE `ffb_matchround`
(
	`matchround_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`matchround_game_id` INTEGER  NOT NULL,
	`matchround_title` VARCHAR(255) default 'Round' NOT NULL,
	`matchround_startdate` DATETIME  NOT NULL,
	`matchround_enddate` DATETIME  NOT NULL,
	`matchround_status` INTEGER default 1 NOT NULL,
	`matchround_credits` DOUBLE default 0 NOT NULL,
	`matchround_max_players_from_team` INTEGER default 0 NOT NULL,
	PRIMARY KEY (`matchround_id`),
	INDEX `ffb_matchround_FI_1` (`matchround_game_id`),
	CONSTRAINT `ffb_matchround_FK_1`
		FOREIGN KEY (`matchround_game_id`)
		REFERENCES `ffb_game` (`game_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_news
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_news`;


CREATE TABLE `ffb_news`
(
	`news_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`news_title` VARCHAR(255)  NOT NULL,
	`news_text` TEXT  NOT NULL,
	`news_date` DATETIME  NOT NULL,
	`news_priority` INTEGER default 0 NOT NULL,
	`news_game_id` INTEGER default 0 NOT NULL,
	`news_symbol` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`news_id`),
	INDEX `ffb_news_FI_1` (`news_game_id`),
	CONSTRAINT `ffb_news_FK_1`
		FOREIGN KEY (`news_game_id`)
		REFERENCES `ffb_game` (`game_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_game
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_game`;


CREATE TABLE `ffb_game`
(
	`game_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`game_title` VARCHAR(255) default 'Round' NOT NULL,
	`game_visible` TINYINT default 0 NOT NULL,
	`game_archive` TINYINT default 0 NOT NULL,
	`game_countdown` TINYINT default 0 NOT NULL,
	`game_status` TINYINT default 0 NOT NULL,
	`game_description` TEXT,
	`game_symbol` VARCHAR(255) default 'game_symbol_na.png' NOT NULL,
	PRIMARY KEY (`game_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_playerstats
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_playerstats`;


CREATE TABLE `ffb_playerstats`
(
	`playerstats_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`playerstats_playerteam_id` INTEGER  NOT NULL,
	`playerstats_match_id` INTEGER  NOT NULL,
	`playerstats_matchround_id` INTEGER  NOT NULL,
	`playerstats_goals` INTEGER default 0 NOT NULL,
	`playerstats_assists` INTEGER default 0 NOT NULL,
	`playerstats_minutes` INTEGER default 0 NOT NULL,
	`playerstats_minute_in` INTEGER default 0 NOT NULL,
	`playerstats_minute_out` INTEGER default 0 NOT NULL,
	`playerstats_cards` VARCHAR(255) default '' NOT NULL,
	`playerstats_owngoals` INTEGER default 0 NOT NULL,
	`playerstats_penaltieslost` INTEGER default 0 NOT NULL,
	`playerstats_penaltiessaved` INTEGER default 0 NOT NULL,
	`playerstats_penaltyshootout_save` INTEGER default 0 NOT NULL,
	`playerstats_penaltyshootout_lost` INTEGER default 0 NOT NULL,
	`playerstats_penaltyshootout_hit` INTEGER default 0 NOT NULL,
	`playerstats_score_goals` INTEGER default 0 NOT NULL,
	`playerstats_score_assists` INTEGER default 0 NOT NULL,
	`playerstats_score_minutes` INTEGER default 0 NOT NULL,
	`playerstats_score_cards` INTEGER default 0 NOT NULL,
	`playerstats_score_owngoals` INTEGER default 0 NOT NULL,
	`playerstats_score_penaltieslost` INTEGER default 0 NOT NULL,
	`playerstats_score_penaltiessaved` INTEGER default 0 NOT NULL,
	`playerstats_score_oppgoals` INTEGER default 0 NOT NULL,
	`playerstats_score_nooppgoals` INTEGER default 0 NOT NULL,
	`playerstats_score_high_loss` INTEGER default 0 NOT NULL,
	`playerstats_score_high_win` INTEGER default 0 NOT NULL,
	`playerstats_score_penaltyshootout_save` INTEGER default 0 NOT NULL,
	`playerstats_score_penaltyshootout_lost` INTEGER default 0 NOT NULL,
	`playerstats_score_penaltyshootout_hit` INTEGER default 0 NOT NULL,
	`playerstats_score` INTEGER default 0 NOT NULL,
	PRIMARY KEY (`playerstats_id`),
	INDEX `ffb_playerstats_FI_1` (`playerstats_playerteam_id`),
	CONSTRAINT `ffb_playerstats_FK_1`
		FOREIGN KEY (`playerstats_playerteam_id`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE CASCADE,
	INDEX `ffb_playerstats_FI_2` (`playerstats_match_id`),
	CONSTRAINT `ffb_playerstats_FK_2`
		FOREIGN KEY (`playerstats_match_id`)
		REFERENCES `ffb_match` (`match_id`)
		ON DELETE CASCADE,
	INDEX `ffb_playerstats_FI_3` (`playerstats_matchround_id`),
	CONSTRAINT `ffb_playerstats_FK_3`
		FOREIGN KEY (`playerstats_matchround_id`)
		REFERENCES `ffb_matchround` (`matchround_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- web_mail
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `web_mail`;


CREATE TABLE `web_mail`
(
	`mail_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`mail_date` DATETIME  NOT NULL,
	`mail_sender` VARCHAR(255)  NOT NULL,
	`mail_to` TEXT  NOT NULL,
	`mail_cc` TEXT  NOT NULL,
	`mail_bc` TEXT  NOT NULL,
	`mail_subject` VARCHAR(255)  NOT NULL,
	`mail_text` TEXT  NOT NULL,
	`mail_num_reciepients` INTEGER  NOT NULL,
	`mail_criteria` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`mail_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_playerfid
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_playerfid`;


CREATE TABLE `ffb_playerfid`
(
	`playerfid_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`playerfid_playerteam_id` INTEGER  NOT NULL,
	`playerfid_team_id` INTEGER  NOT NULL,
	`playerfid_fid_foe` VARCHAR(255)  NOT NULL,
	`playerfid_fid_fifa` VARCHAR(255)  NOT NULL,
	`playerfid_fid_tm` VARCHAR(255)  NOT NULL,
	`playerfid_fid_uefa` VARCHAR(255)  NOT NULL,
	`playerfid_fid_wf` VARCHAR(255)  NOT NULL,
	`playerfid_name_foe` VARCHAR(255)  NOT NULL,
	`playerfid_name_fifa` VARCHAR(255)  NOT NULL,
	`playerfid_name_tm` VARCHAR(255)  NOT NULL,
	`playerfid_name_uefa` VARCHAR(255)  NOT NULL,
	`playerfid_name_wf` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`playerfid_id`),
	INDEX `ffb_playerfid_FI_1` (`playerfid_playerteam_id`),
	CONSTRAINT `ffb_playerfid_FK_1`
		FOREIGN KEY (`playerfid_playerteam_id`)
		REFERENCES `ffb_playerteam` (`playerteam_id`),
	INDEX `ffb_playerfid_FI_2` (`playerfid_team_id`),
	CONSTRAINT `ffb_playerfid_FK_2`
		FOREIGN KEY (`playerfid_team_id`)
		REFERENCES `ffb_team` (`team_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_teamfid
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_teamfid`;


CREATE TABLE `ffb_teamfid`
(
	`teamfid_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`teamfid_team_id` INTEGER  NOT NULL,
	`teamfid_fid_foe` VARCHAR(255)  NOT NULL,
	`teamfid_fid_tm` VARCHAR(255)  NOT NULL,
	`teamfid_fid_wf` VARCHAR(255)  NOT NULL,
	`teamfid_name_foe` VARCHAR(255)  NOT NULL,
	`teamfid_name_tm` VARCHAR(255)  NOT NULL,
	`teamfid_name_wf` VARCHAR(255)  NOT NULL,
	`teamfid_url_foe` VARCHAR(255)  NOT NULL,
	`teamfid_url_tm` VARCHAR(255)  NOT NULL,
	`teamfid_url_wf` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`teamfid_id`),
	INDEX `ffb_teamfid_FI_1` (`teamfid_team_id`),
	CONSTRAINT `ffb_teamfid_FK_1`
		FOREIGN KEY (`teamfid_team_id`)
		REFERENCES `ffb_team` (`team_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_userteam
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_userteam`;


CREATE TABLE `ffb_userteam`
(
	`userteam_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`userteam_user_id` INTEGER  NOT NULL,
	`userteam_date` DATETIME  NOT NULL,
	`userteam_player_id1` INTEGER  NOT NULL,
	`userteam_player_id2` INTEGER  NOT NULL,
	`userteam_player_id3` INTEGER  NOT NULL,
	`userteam_player_id4` INTEGER  NOT NULL,
	`userteam_player_id5` INTEGER  NOT NULL,
	`userteam_player_id6` INTEGER  NOT NULL,
	`userteam_player_id7` INTEGER  NOT NULL,
	`userteam_player_id8` INTEGER  NOT NULL,
	`userteam_player_id9` INTEGER  NOT NULL,
	`userteam_player_id10` INTEGER  NOT NULL,
	`userteam_player_id11` INTEGER  NOT NULL,
	`userteam_price` DECIMAL default 0 NOT NULL,
	`userteam_matchround_id` INTEGER  NOT NULL,
	`userteam_score` INTEGER default -1 NOT NULL,
	`userteam_wc_points` INTEGER default 0 NOT NULL,
	PRIMARY KEY (`userteam_id`),
	INDEX `ffb_userteam_FI_1` (`userteam_user_id`),
	CONSTRAINT `ffb_userteam_FK_1`
		FOREIGN KEY (`userteam_user_id`)
		REFERENCES `web_user` (`user_id`),
	INDEX `ffb_userteam_FI_2` (`userteam_player_id1`),
	CONSTRAINT `ffb_userteam_FK_2`
		FOREIGN KEY (`userteam_player_id1`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE SET NULL,
	INDEX `ffb_userteam_FI_3` (`userteam_player_id2`),
	CONSTRAINT `ffb_userteam_FK_3`
		FOREIGN KEY (`userteam_player_id2`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE SET NULL,
	INDEX `ffb_userteam_FI_4` (`userteam_player_id3`),
	CONSTRAINT `ffb_userteam_FK_4`
		FOREIGN KEY (`userteam_player_id3`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE SET NULL,
	INDEX `ffb_userteam_FI_5` (`userteam_player_id4`),
	CONSTRAINT `ffb_userteam_FK_5`
		FOREIGN KEY (`userteam_player_id4`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE SET NULL,
	INDEX `ffb_userteam_FI_6` (`userteam_player_id5`),
	CONSTRAINT `ffb_userteam_FK_6`
		FOREIGN KEY (`userteam_player_id5`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE SET NULL,
	INDEX `ffb_userteam_FI_7` (`userteam_player_id6`),
	CONSTRAINT `ffb_userteam_FK_7`
		FOREIGN KEY (`userteam_player_id6`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE SET NULL,
	INDEX `ffb_userteam_FI_8` (`userteam_player_id7`),
	CONSTRAINT `ffb_userteam_FK_8`
		FOREIGN KEY (`userteam_player_id7`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE SET NULL,
	INDEX `ffb_userteam_FI_9` (`userteam_player_id8`),
	CONSTRAINT `ffb_userteam_FK_9`
		FOREIGN KEY (`userteam_player_id8`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE SET NULL,
	INDEX `ffb_userteam_FI_10` (`userteam_player_id9`),
	CONSTRAINT `ffb_userteam_FK_10`
		FOREIGN KEY (`userteam_player_id9`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE SET NULL,
	INDEX `ffb_userteam_FI_11` (`userteam_player_id10`),
	CONSTRAINT `ffb_userteam_FK_11`
		FOREIGN KEY (`userteam_player_id10`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE SET NULL,
	INDEX `ffb_userteam_FI_12` (`userteam_player_id11`),
	CONSTRAINT `ffb_userteam_FK_12`
		FOREIGN KEY (`userteam_player_id11`)
		REFERENCES `ffb_playerteam` (`playerteam_id`)
		ON DELETE SET NULL,
	INDEX `ffb_userteam_FI_13` (`userteam_matchround_id`),
	CONSTRAINT `ffb_userteam_FK_13`
		FOREIGN KEY (`userteam_matchround_id`)
		REFERENCES `ffb_matchround` (`matchround_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_userscore
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_userscore`;


CREATE TABLE `ffb_userscore`
(
	`userscore_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`userscore_user_id` INTEGER  NOT NULL,
	`userscore_game_id` INTEGER  NOT NULL,
	`userscore_total` INTEGER default 0 NOT NULL,
	`userscore_wc_points` INTEGER default 0 NOT NULL,
	PRIMARY KEY (`userscore_id`),
	INDEX `ffb_userscore_FI_1` (`userscore_user_id`),
	CONSTRAINT `ffb_userscore_FK_1`
		FOREIGN KEY (`userscore_user_id`)
		REFERENCES `web_user` (`user_id`)
		ON DELETE CASCADE,
	INDEX `ffb_userscore_FI_2` (`userscore_game_id`),
	CONSTRAINT `ffb_userscore_FK_2`
		FOREIGN KEY (`userscore_game_id`)
		REFERENCES `ffb_game` (`game_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_admin
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_admin`;


CREATE TABLE `ffb_admin`
(
	`admin_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`admin_user_id` INTEGER  NOT NULL,
	`admin_game_id` INTEGER  NOT NULL,
	PRIMARY KEY (`admin_id`),
	INDEX `ffb_admin_FI_1` (`admin_user_id`),
	CONSTRAINT `ffb_admin_FK_1`
		FOREIGN KEY (`admin_user_id`)
		REFERENCES `web_user` (`user_id`)
		ON DELETE CASCADE,
	INDEX `ffb_admin_FI_2` (`admin_game_id`),
	CONSTRAINT `ffb_admin_FK_2`
		FOREIGN KEY (`admin_game_id`)
		REFERENCES `ffb_game` (`game_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_rss
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_rss`;


CREATE TABLE `ffb_rss`
(
	`ffb_rss_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`ffb_rss_title` TEXT  NOT NULL,
	`ffb_rss_description` TEXT  NOT NULL,
	`ffb_rss_category` TEXT  NOT NULL,
	`ffb_rss_guid` VARCHAR(255)  NOT NULL,
	`ffb_rss_author` VARCHAR(255)  NOT NULL,
	`ffb_rss_origin_id` INTEGER  NOT NULL,
	`ffb_rss_pubdate` DATETIME  NOT NULL,
	PRIMARY KEY (`ffb_rss_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_rss_category
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_rss_category`;


CREATE TABLE `ffb_rss_category`
(
	`ffb_rss_category_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`ffb_rss_category_name` TEXT  NOT NULL,
	PRIMARY KEY (`ffb_rss_category_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- web_log
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `web_log`;


CREATE TABLE `web_log`
(
	`log_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`log_user_id` INTEGER  NOT NULL,
	`log_user_nickname` VARCHAR(255)  NOT NULL,
	`log_user_ip` VARCHAR(255)  NOT NULL,
	`log_module` VARCHAR(255)  NOT NULL,
	`log_class` VARCHAR(255)  NOT NULL,
	`log_event` VARCHAR(255)  NOT NULL,
	`log_presenter` VARCHAR(255)  NOT NULL,
	`log_subdomain` VARCHAR(255)  NOT NULL,
	`log_date` DATETIME  NOT NULL,
	PRIMARY KEY (`log_id`),
	INDEX `web_log_FI_1` (`log_user_id`),
	CONSTRAINT `web_log_FK_1`
		FOREIGN KEY (`log_user_id`)
		REFERENCES `web_user` (`user_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_options
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_options`;


CREATE TABLE `ffb_options`
(
	`options_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`options_game_id` INTEGER  NOT NULL,
	`options_score_minutes` INTEGER  NOT NULL,
	`options_score_minutes_treshold` INTEGER  NOT NULL,
	`options_score_minutes_gt` INTEGER  NOT NULL,
	`options_score_minutes_lt` INTEGER  NOT NULL,
	`options_score_minutes_lt30` INTEGER  NOT NULL,
	`options_score_goals_g` INTEGER  NOT NULL,
	`options_score_goals_d` INTEGER  NOT NULL,
	`options_score_goals_m` INTEGER  NOT NULL,
	`options_score_goals_s` INTEGER  NOT NULL,
	`options_score_assists` INTEGER  NOT NULL,
	`options_score_no_oppgoals_g` INTEGER  NOT NULL,
	`options_score_no_oppgoals_d` INTEGER  NOT NULL,
	`options_score_no_oppgoals_m` INTEGER  NOT NULL,
	`options_score_oppgoals_g` INTEGER  NOT NULL,
	`options_score_oppgoals_d` INTEGER  NOT NULL,
	`options_score_owngoals` INTEGER  NOT NULL,
	`options_score_card_y` INTEGER  NOT NULL,
	`options_score_card_r` INTEGER  NOT NULL,
	`options_score_card_yr` INTEGER  NOT NULL,
	`options_score_penalty_saved` INTEGER  NOT NULL,
	`options_score_penalty_lost` INTEGER  NOT NULL,
	`options_score_penaltyshootout_save` INTEGER  NOT NULL,
	`options_score_penaltyshootout_lost` INTEGER  NOT NULL,
	`options_score_penaltyshootout_hit` INTEGER  NOT NULL,
	`options_score_high_loss` INTEGER  NOT NULL,
	`options_score_high_win` INTEGER  NOT NULL,
	`options_score_high_win_loss_treshold` INTEGER  NOT NULL,
	`options_status_error` INTEGER  NOT NULL,
	`options_status_error_validation` INTEGER  NOT NULL,
	`options_status_success` INTEGER  NOT NULL,
	`options_status_success_insert` INTEGER  NOT NULL,
	`options_status_success_update` INTEGER  NOT NULL,
	`options_status_success_delete` INTEGER  NOT NULL,
	`options_lineup_max_players` INTEGER  NOT NULL,
	`options_lineup_max_credits` INTEGER  NOT NULL,
	`options_lineup_max_players_team` INTEGER  NOT NULL,
	`options_lineup_min_g` INTEGER  NOT NULL,
	`options_lineup_min_d` INTEGER  NOT NULL,
	`options_lineup_min_m` INTEGER  NOT NULL,
	`options_lineup_min_s` INTEGER  NOT NULL,
	`options_lineup_max_g` INTEGER  NOT NULL,
	`options_lineup_max_d` INTEGER  NOT NULL,
	`options_lineup_max_m` INTEGER  NOT NULL,
	`options_lineup_max_s` INTEGER  NOT NULL,
	`options_game_rankmode` VARCHAR(255) default 'wc' NOT NULL,
	`options_game_pricemode` VARCHAR(255) default 'dynamic' NOT NULL,
	`options_game_pointsmode` VARCHAR(255) default 'new' NOT NULL,
	`options_game_wcpoints` VARCHAR(255) default 'new' NOT NULL,
	`options_game_remind_hours_before` INTEGER default 0 NOT NULL,
	PRIMARY KEY (`options_id`),
	INDEX `ffb_options_FI_1` (`options_game_id`),
	CONSTRAINT `ffb_options_FK_1`
		FOREIGN KEY (`options_game_id`)
		REFERENCES `ffb_game` (`game_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- web_user
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `web_user`;


CREATE TABLE `web_user`
(
	`user_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`user_nickname` VARCHAR(255)  NOT NULL,
	`user_password` VARCHAR(255)  NOT NULL,
	`user_email` VARCHAR(255)  NOT NULL,
	`user_fname` VARCHAR(255)  NOT NULL,
	`user_lname` VARCHAR(255)  NOT NULL,
	`user_gender` VARCHAR(255) default '',
	`user_status` VARCHAR(255) default 'active' NOT NULL,
	`user_admin` TINYINT default 0 NOT NULL,
	`user_nationality` VARCHAR(255),
	`user_date_birth` DATETIME,
	`user_ip` VARCHAR(255)  NOT NULL,
	`user_lip` VARCHAR(255)  NOT NULL,
	`user_date_register` DATETIME,
	`user_date_llogin` DATETIME,
	`user_date_laction` DATETIME,
	`user_activation_code` VARCHAR(255)  NOT NULL,
	`user_mailservice` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`user_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_user_award
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_user_award`;


CREATE TABLE `ffb_user_award`
(
	`user_award_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`user_award_name` VARCHAR(255)  NOT NULL,
	`user_award_image` VARCHAR(255)  NOT NULL,
	`user_award_description` TEXT  NOT NULL,
	`user_award_sortflag` INTEGER  NOT NULL,
	PRIMARY KEY (`user_award_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_user_award_defines
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_user_award_defines`;


CREATE TABLE `ffb_user_award_defines`
(
	`user_award_defines_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`user_award_defines_award_id` INTEGER  NOT NULL,
	`user_award_defines_rank` INTEGER  NOT NULL,
	`user_award_defines_rank_name` VARCHAR(255)  NOT NULL,
	`user_award_defines_aim` VARCHAR(255)  NOT NULL,
	`user_award_defines_aim_dbtable` VARCHAR(255)  NOT NULL,
	`user_award_defines_aim_operator` VARCHAR(255)  NOT NULL,
	`user_award_defines_aim_count` INTEGER  NOT NULL,
	`user_award_defines_aim_automatic` TINYINT default 1 NOT NULL,
	`user_award_defines_aim_function_name` VARCHAR(255)  NOT NULL,
	`user_award_defines_image` VARCHAR(255)  NOT NULL,
	`user_award_defines_description` TEXT  NOT NULL,
	PRIMARY KEY (`user_award_defines_id`),
	INDEX `ffb_user_award_defines_FI_1` (`user_award_defines_award_id`),
	CONSTRAINT `ffb_user_award_defines_FK_1`
		FOREIGN KEY (`user_award_defines_award_id`)
		REFERENCES `ffb_user_award` (`user_award_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_user_award_finished
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_user_award_finished`;


CREATE TABLE `ffb_user_award_finished`
(
	`user_award_finished_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`user_award_finished_user_id` INTEGER  NOT NULL,
	`user_award_finished_award_defines_id` INTEGER  NOT NULL,
	`user_award_finished_date` DATETIME,
	PRIMARY KEY (`user_award_finished_id`),
	INDEX `ffb_user_award_finished_FI_1` (`user_award_finished_user_id`),
	CONSTRAINT `ffb_user_award_finished_FK_1`
		FOREIGN KEY (`user_award_finished_user_id`)
		REFERENCES `web_user` (`user_id`)
		ON DELETE CASCADE,
	INDEX `ffb_user_award_finished_FI_2` (`user_award_finished_award_defines_id`),
	CONSTRAINT `ffb_user_award_finished_FK_2`
		FOREIGN KEY (`user_award_finished_award_defines_id`)
		REFERENCES `ffb_user_award_defines` (`user_award_defines_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- web_admin
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `web_admin`;


CREATE TABLE `web_admin`
(
	`admin_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`admin_user_id` INTEGER  NOT NULL,
	`admin_section` VARCHAR(255)  NOT NULL,
	`admin_level` INTEGER  NOT NULL,
	`admin_status` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`admin_id`),
	INDEX `web_admin_FI_1` (`admin_user_id`),
	CONSTRAINT `web_admin_FK_1`
		FOREIGN KEY (`admin_user_id`)
		REFERENCES `web_user` (`user_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_ads
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_ads`;


CREATE TABLE `ffb_ads`
(
	`ads_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`ads_name` VARCHAR(255)  NOT NULL,
	`ads_code` TEXT  NOT NULL,
	PRIMARY KEY (`ads_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_ads_slot
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_ads_slot`;


CREATE TABLE `ffb_ads_slot`
(
	`ads_slot_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`ads_slot_name` VARCHAR(255)  NOT NULL,
	`ads_slot_css_class` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`ads_slot_id`)
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_ads_allocation
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_ads_allocation`;


CREATE TABLE `ffb_ads_allocation`
(
	`ads_allocation_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`ads_allocation_ads_id` INTEGER  NOT NULL,
	`ads_allocation_slot_id` INTEGER  NOT NULL,
	`ads_allocation_game_id` INTEGER  NOT NULL,
	`ads_allocation_ad_count` INTEGER  NOT NULL,
	`ads_allocation_ad_max` INTEGER  NOT NULL,
	`ads_allocation_ad_priority` INTEGER  NOT NULL,
	`ads_allocation_start` DATETIME,
	`ads_allocation_end` DATETIME,
	PRIMARY KEY (`ads_allocation_id`),
	INDEX `ffb_ads_allocation_FI_1` (`ads_allocation_ads_id`),
	CONSTRAINT `ffb_ads_allocation_FK_1`
		FOREIGN KEY (`ads_allocation_ads_id`)
		REFERENCES `ffb_ads` (`ads_id`)
		ON DELETE CASCADE,
	INDEX `ffb_ads_allocation_FI_2` (`ads_allocation_slot_id`),
	CONSTRAINT `ffb_ads_allocation_FK_2`
		FOREIGN KEY (`ads_allocation_slot_id`)
		REFERENCES `ffb_ads_slot` (`ads_slot_id`)
		ON DELETE CASCADE,
	INDEX `ffb_ads_allocation_FI_3` (`ads_allocation_game_id`),
	CONSTRAINT `ffb_ads_allocation_FK_3`
		FOREIGN KEY (`ads_allocation_game_id`)
		REFERENCES `ffb_game` (`game_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

#-----------------------------------------------------------------------------
#-- ffb_no_ads
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ffb_no_ads`;


CREATE TABLE `ffb_no_ads`
(
	`no_ads_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`no_ads_user_id_ip` VARCHAR(255)  NOT NULL,
	`no_ads_slot_id` INTEGER  NOT NULL,
	PRIMARY KEY (`no_ads_id`),
	INDEX `ffb_no_ads_FI_1` (`no_ads_slot_id`),
	CONSTRAINT `ffb_no_ads_FK_1`
		FOREIGN KEY (`no_ads_slot_id`)
		REFERENCES `ffb_ads_slot` (`ads_slot_id`)
		ON DELETE CASCADE
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
