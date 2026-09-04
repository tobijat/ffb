-- Propel omits unmodified boolean defaults on INSERT.
ALTER TABLE `ffb_goal`
  MODIFY COLUMN `goal_owngoal` TINYINT(4) NOT NULL DEFAULT 0,
  MODIFY COLUMN `goal_penalty` TINYINT(4) NOT NULL DEFAULT 0,
  MODIFY COLUMN `goal_penaltyshootout` TINYINT(4) NOT NULL DEFAULT 0;

ALTER TABLE `ffb_psgoal`
  MODIFY COLUMN `psgoal_hit` TINYINT(1) NOT NULL DEFAULT 0,
  MODIFY COLUMN `psgoal_fail` TINYINT(1) NOT NULL DEFAULT 0;
