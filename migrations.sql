-- CREATE TABLE `srregisterhistory` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `student_id` INT NOT NULL , `srData` JSON NULL , `updatedDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `status` ENUM('1','2') NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

-- ALTER TABLE `srregisterhistory` ADD `currentClass` INT NULL AFTER `srData`;

-- ALTER TABLE `students` ADD `occupation` VARCHAR(100) NULL AFTER `admission_no`, ADD `last_schoool_name` VARCHAR(100) NULL AFTER `occupation`, ADD `aadhar_no` VARCHAR(100) NULL AFTER `last_schoool_name`, ADD `residence_in_india_since` VARCHAR(100) NULL AFTER `aadhar_no`;


-- CREATE TABLE `preloader` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `isRun` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT 'if 1 => not run or 2 => runed' , `status` ENUM('1','2') NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;


-- CREATE TABLE `books` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `book_name` VARCHAR(100) NOT NULL , `class_name` VARCHAR(100) NOT NULL , `board_name` VARCHAR(100) NOT NULL , `publication_name` VARCHAR(100) NULL , `writer_name` VARCHAR(100) NULL , `status` ENUM("1","2") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

-- ALTER TABLE `books` ADD `subject_name` VARCHAR(100) NOT NULL AFTER `class_name`;


-- CREATE TABLE `questions_types` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `questionNames` VARCHAR(255) NOT NULL , `status` ENUM('1','2') NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;


-- CREATE TABLE `chapters` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `book_id` INT NOT NULL , `chapter_name` VARCHAR(255) NOT NULL , `status` ENUM("1","2") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;


-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Question Bank', '#', 'fa-solid fa-database', '0', '1', '0', '1', '2022-09-10 16:10:10');
-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Books Master', 'questionBank/booksMaster', NULL, '91', '0', '1', '1', '2022-08-29 09:25:52');

-- CREATE TABLE `question_bank` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `book_id` INT NOT NULL , `chapter_id` INT NOT NULL , `question_type` INT NOT NULL , `question` TEXT NOT NULL , `image` JSON NULL , `option_1` VARCHAR(100) NULL , `option_2` VARCHAR(100) NULL , `option_3` VARCHAR(100) NULL , `option_4` VARCHAR(100) NULL , `status` ENUM("1","2") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Question Bank Master', 'questionBank/questionBankMaster', NULL, '91', '0', '1', '1', '2022-08-29 09:25:52');