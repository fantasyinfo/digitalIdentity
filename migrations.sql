-- ALTER TABLE `users` CHANGE `mobile` `mobile` VARCHAR(100) NULL DEFAULT NULL;


-- ALTER TABLE `push_notification` ADD `for_what` VARCHAR(100) NULL AFTER `device_type`;

-- ALTER TABLE push_notification CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;


-- CREATE TABLE `holiday_calendar` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `title` VARCHAR(255) NOT NULL , `event_date` DATE NOT NULL , `status` ENUM("1","2") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;