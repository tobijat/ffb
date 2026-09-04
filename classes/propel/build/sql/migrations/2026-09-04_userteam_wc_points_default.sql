-- Propel omits unmodified default columns on INSERT; live DB had no DEFAULT.
ALTER TABLE `ffb_userteam`
  MODIFY COLUMN `userteam_wc_points` INT(11) NOT NULL DEFAULT 0;
