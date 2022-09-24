-- ALTER TABLE `driver` CHANGE `user_id` `user_id` VARCHAR(100) NOT NULL;

-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Show Live Location', 'driver/showMap', NULL, '48', '0', '1', '1', '2022-08-29 09:25:52');


-- ALTER TABLE `users` ADD `fcm_token` TEXT NULL AFTER `auth_token`, ADD `mobile` INT(100) NULL AFTER `fcm_token`;