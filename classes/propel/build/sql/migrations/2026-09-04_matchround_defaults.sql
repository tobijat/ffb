-- Ensure matchround insert works under MySQL strict mode when Propel omits default columns.
ALTER TABLE `ffb_matchround`
  MODIFY COLUMN `matchround_credits` DECIMAL(9,2) NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `matchround_max_players_from_team` INT(11) NOT NULL DEFAULT 0;
