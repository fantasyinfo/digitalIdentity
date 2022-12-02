CREATE TABLE `srregisterhistory` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `student_id` INT NOT NULL , `srData` JSON NULL , `updatedDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `status` ENUM('1','2') NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

ALTER TABLE `srregisterhistory` ADD `currentClass` INT NULL AFTER `srData`;

ALTER TABLE `students` ADD `occupation` VARCHAR(100) NULL AFTER `admission_no`, ADD `last_schoool_name` VARCHAR(100) NULL AFTER `occupation`, ADD `aadhar_no` VARCHAR(100) NULL AFTER `last_schoool_name`, ADD `residence_in_india_since` VARCHAR(100) NULL AFTER `aadhar_no`;


CREATE TABLE `preloader` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `isRun` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT 'if 1 => not run or 2 => runed' , `status` ENUM('1','2') NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;