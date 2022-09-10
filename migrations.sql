-- ALTER TABLE `students` ADD `password` VARCHAR(100) NULL AFTER `image`;


-- CREATE TABLE `visitor_entry` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `visit_date` DATE NOT NULL , `visit_time` TIME NOT NULL , `visitor_name` VARCHAR(100) NOT NULL , `person_to_meet` VARCHAR(100) NOT NULL , `purpose_to_meet` TEXT NOT NULL , `visitor_mobile_no` INT NOT NULL , `visitor_image` VARCHAR(255) NOT NULL , `status` ENUM("1","2","3","4") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Visitor Master', 'master/visitorMaster', NULL, '27', '0', '1', '1', CURRENT_TIMESTAMP);

-- ALTER TABLE `visitor_entry` CHANGE `visitor_mobile_no` `visitor_mobile_no` VARCHAR(100) NOT NULL;
-- ALTER TABLE `students` ADD `auth_token` VARCHAR(255) NULL AFTER `status`;


-- UPDATE `admin_panel_menu` SET `link` = 'school/schoolProfile' WHERE `admin_panel_menu`.`id` = 3;
-- ALTER TABLE `schoolmaster` ADD `pincode` INT(6) NULL AFTER `classes_up_to`;
-- ALTER TABLE `schoolmaster` ADD `doa` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `pincode`;
-- ALTER TABLE `schoolmaster` CHANGE `logo` `image` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;


ALTER TABLE `fees` ADD `schoolUniqueCode` VARCHAR(100) NOT NULL AFTER `id`;
ALTER TABLE `feesforstudent` ADD `schoolUniqueCode` VARCHAR(100) NOT NULL AFTER `id`;
ALTER TABLE `schoolmaster` ADD `session_started_from` INT NULL AFTER `doa`, ADD `session_ended_to` INT NULL AFTER `session_started_from`;
ALTER TABLE `feesforstudent` ADD `fee_deposit_date` DATE NULL AFTER `deposit_amt`;
ALTER TABLE `schoolmaster` ADD `session_started_from_year` INT NULL AFTER `session_ended_to`, ADD `session_ended_to_year` INT NULL AFTER `session_started_from_year`;