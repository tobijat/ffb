-- Drop Facebook-related columns removed from schema.xml
-- Date: 2026-09-04

ALTER TABLE `web_user`
  DROP COLUMN `user_facebook_id`;

ALTER TABLE `web_user_permissions`
  DROP COLUMN `user_permissions_ffb_facebook`,
  DROP COLUMN `user_permissions_pictory_facebook`,
  DROP COLUMN `user_permissions_facebook_connected`;

ALTER TABLE `ffb_user_award_defines`
  DROP COLUMN `user_award_defines_facebook_description`;

ALTER TABLE `ffb_user_award_finished`
  DROP COLUMN `user_award_finished_facebook_stream_id`;
