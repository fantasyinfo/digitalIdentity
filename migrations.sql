-- ALTER TABLE `users` CHANGE `mobile` `mobile` VARCHAR(100) NULL DEFAULT NULL;


ALTER TABLE `push_notification` ADD `for_what` VARCHAR(100) NULL AFTER `device_type`;

ALTER TABLE push_notification CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;