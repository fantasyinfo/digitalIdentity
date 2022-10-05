-- ALTER TABLE `users` CHANGE `mobile` `mobile` VARCHAR(100) NULL DEFAULT NULL;


-- ALTER TABLE `push_notification` ADD `for_what` VARCHAR(100) NULL AFTER `device_type`;

-- ALTER TABLE push_notification CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;


-- CREATE TABLE `holiday_calendar` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `title` VARCHAR(255) NOT NULL , `event_date` DATE NOT NULL , `status` ENUM("1","2") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;


-- CREATE TABLE `sem_exam` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `sem_exam_name` VARCHAR(100) NOT NULL , `start_date` DATE NOT NULL , `end_date` DATE NOT NULL , `status` ENUM('1','2','3','4') NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

-- ALTER TABLE `sem_exam` ADD `exam_year` DATE NULL AFTER `sem_exam_name`;


-- CREATE TABLE `sec_exam_table` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `sem_exam_id` INT NOT NULL , `class_id` INT NOT NULL , `section_id` INT NOT NULL , `subject_id` INT NOT NULL , `exam_date` DATE NOT NULL , `exam_day` INT NOT NULL , `exam_start_time` TIME NOT NULL , `exam_end_time` TIME NOT NULL , `min_marks` FLOAT NOT NULL , `max_marks` FLOAT NOT NULL , `status` ENUM("1","2","3","4") NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;


-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Semester', '#', 'fa-duotone fa-clipboard', '0', '1', '0', '1', '2022-09-10 10:40:10');


-- ALTER TABLE `sem_exam` CHANGE `exam_year` `exam_year` VARCHAR(100) NULL DEFAULT NULL;


-- ALTER TABLE `sec_exam_table` CHANGE `exam_day` `exam_day` VARCHAR(100) NOT NULL;


-- ALTER TABLE `sec_exam_table` CHANGE `exam_start_time` `exam_start_time` TIME NULL;
-- ALTER TABLE `sec_exam_table` CHANGE `exam_end_time` `exam_end_time` TIME NULL;



CREATE TABLE `notificationmaster` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `for_what` INT NOT NULL , `title` VARCHAR(255) NOT NULL , `body` TEXT NOT NULL , `image` VARCHAR(255) NULL , `sound` VARCHAR(255) NULL , `status` ENUM("1","2","3","4") NOT NULL , `updated_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;


ALTER TABLE notificationmaster CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;