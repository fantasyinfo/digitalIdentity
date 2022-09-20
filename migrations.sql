-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Fees Invoice', 'master/feesInvoice', NULL, '40', '0', '1', '1', CURRENT_TIMESTAMP);


-- ALTER TABLE `city` ADD `stateId` INT NULL AFTER `cityName`;


-- CREATE TABLE `rating_and_reviews` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `login_user_id` INT NOT NULL , `login_user_type` INT NOT NULL , `user_id` INT NOT NULL , `user_type` INT NOT NULL , `stars` ENUM('1','2','3','4','5') NOT NULL , `review` TEXT NOT NULL , `for_what` VARCHAR(100) NOT NULL , `status` ENUM("1","2","3","4") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;


-- ALTER TABLE `rating_and_reviews` CHANGE `for_what` `for_what` INT NOT NULL;
-- ALTER TABLE `rating_and_reviews` ADD `review_title` VARCHAR(100) NOT NULL AFTER `review`;
-- ALTER TABLE `rating_and_reviews` ADD `reason_id` INT NOT NULL AFTER `for_what`;


-- CREATE TABLE `banner_for_app` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `image` VARCHAR(255) NOT NULL , `status` ENUM('1','2','3','4') NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;



-- CREATE TABLE `complaint` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `login_user_id` INT NOT NULL , `login_user_type` INT NOT NULL , `guilty_person_name` VARCHAR(100) NOT NULL , `guilty_person_position` VARCHAR(100) NOT NULL , `subject` VARCHAR(255) NOT NULL , `issue` TEXT NOT NULL , `action` TEXT NULL , `complaint_id` VARCHAR(100) NOT NULL , `action_taken_id` INT NULL , `action_taken_user_type` INT NULL , `action_taken_date` DATE NULL , `status` ENUM("1","2","3","4") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;


-- ALTER TABLE `exam` CHANGE `status` `status` ENUM('1','2',"3","4") CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1' COMMENT '1 => active 2 => deactive, 3 => resultPublished';