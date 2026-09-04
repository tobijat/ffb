-- Align live DB defaults with Propel schema (required + defaultValue).
-- Without these, Propel omits unmodified defaults on INSERT and MySQL rejects the row.

-- ffb_playerstats score / penalty fields
ALTER TABLE `ffb_playerstats`
  MODIFY COLUMN `playerstats_penaltiessaved` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_penaltyshootout_save` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_penaltyshootout_lost` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_penaltyshootout_hit` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_score_goals` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_score_assists` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_score_minutes` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_score_cards` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_score_owngoals` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_score_penaltieslost` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_score_penaltiessaved` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_score_oppgoals` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_score_nooppgoals` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_score_high_loss` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_score_high_win` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_score_penaltyshootout_save` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_score_penaltyshootout_lost` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerstats_score_penaltyshootout_hit` INT(11) NOT NULL DEFAULT 0;

-- ffb_playerprice
ALTER TABLE `ffb_playerprice`
  MODIFY COLUMN `playerprice_price` DECIMAL(9,2) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerprice_player_power` DECIMAL(9,2) NOT NULL DEFAULT 0,
  MODIFY COLUMN `playerprice_av_power` DECIMAL(9,2) NOT NULL DEFAULT 0;

-- ffb_userscore / ffb_userteam
ALTER TABLE `ffb_userscore`
  MODIFY COLUMN `userscore_wc_points` INT(11) NOT NULL DEFAULT 0;

ALTER TABLE `ffb_userteam`
  MODIFY COLUMN `userteam_price` DECIMAL(9,2) NOT NULL DEFAULT 0;

-- ffb_news / ffb_game / ffb_poll / ffb_options
ALTER TABLE `ffb_news`
  MODIFY COLUMN `news_priority` INT(11) NOT NULL DEFAULT 0,
  MODIFY COLUMN `news_game_id` INT(11) NOT NULL DEFAULT 0;

ALTER TABLE `ffb_game`
  MODIFY COLUMN `game_countdown` TINYINT(4) NOT NULL DEFAULT 0,
  MODIFY COLUMN `game_status` TINYINT(4) NOT NULL DEFAULT 0,
  MODIFY COLUMN `game_symbol` VARCHAR(255) NOT NULL DEFAULT 'game_symbol_na.png';

ALTER TABLE `ffb_poll`
  MODIFY COLUMN `poll_visible` TINYINT(1) NOT NULL DEFAULT 1;

ALTER TABLE `ffb_options`
  MODIFY COLUMN `options_game_pointsmode` VARCHAR(255) NOT NULL DEFAULT 'new',
  MODIFY COLUMN `options_game_wcpoints` VARCHAR(255) NOT NULL DEFAULT 'new';

-- Optional-but-NOT-NULL string FKs / FID fields left blank on partial inserts
ALTER TABLE `ffb_player`
  MODIFY COLUMN `player_foreign_id` VARCHAR(255) NOT NULL DEFAULT '';

ALTER TABLE `ffb_team`
  MODIFY COLUMN `team_foreign_id` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `team_num_players` INT(11) UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE `ffb_playerfid`
  MODIFY COLUMN `playerfid_fid_foe` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `playerfid_fid_fifa` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `playerfid_fid_tm` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `playerfid_fid_uefa` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `playerfid_fid_wf` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `playerfid_name_foe` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `playerfid_name_fifa` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `playerfid_name_tm` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `playerfid_name_uefa` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `playerfid_name_wf` VARCHAR(255) NOT NULL DEFAULT '';

ALTER TABLE `ffb_teamfid`
  MODIFY COLUMN `teamfid_fid_foe` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `teamfid_fid_tm` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `teamfid_fid_wf` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `teamfid_name_foe` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `teamfid_name_tm` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `teamfid_name_wf` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `teamfid_url_foe` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `teamfid_url_tm` VARCHAR(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `teamfid_url_wf` VARCHAR(255) NOT NULL DEFAULT '';

ALTER TABLE `ffb_poll_answer`
  MODIFY COLUMN `poll_answer_count` INT(11) NOT NULL DEFAULT 0;

ALTER TABLE `ffb_poll_result`
  MODIFY COLUMN `poll_result_text` MEDIUMTEXT NOT NULL DEFAULT '';
