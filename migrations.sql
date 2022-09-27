-- ALTER TABLE `driver` CHANGE `user_id` `user_id` VARCHAR(100) NOT NULL;

-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Show Live Location', 'driver/showMap', NULL, '48', '0', '1', '1', '2022-08-29 09:25:52');


-- ALTER TABLE `users` ADD `fcm_token` TEXT NULL AFTER `auth_token`, ADD `mobile` INT(100) NULL AFTER `fcm_token`;


-- ALTER TABLE `driver` CHANGE `u_qr_id` `u_qr_id` VARCHAR(100) NULL DEFAULT NULL;
-- ALTER TABLE `driver` CHANGE `mobile` `mobile` VARCHAR(100) NOT NULL;


-- ALTER TABLE `students` ADD `driver_id` INT NULL AFTER `fcm_token`;
-- ALTER TABLE `teachers` ADD `driver_id` INT NULL AFTER `fcm_token`;
-- ALTER TABLE `students` ADD `vechicle_type` INT NULL AFTER `fcm_token`;
-- ALTER TABLE `teachers` ADD `vechicle_type` INT NULL AFTER `fcm_token`;


ALTER DATABASE digitalf_dvmProject CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;