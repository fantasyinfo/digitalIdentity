-- CREATE TABLE `token_filter` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `token` TEXT NOT NULL , `for_what` VARCHAR(100) NOT NULL , `status` ENUM("1","2") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

-- ALTER TABLE `token_filter` ADD `insertId` INT NULL AFTER `for_what`;
-- ALTER TABLE `check_salary_slip` ADD `generateDate` DATE NOT NULL AFTER `status`;


-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Experience Letter', 'master/getExperienceLetter', NULL, '69', '0', '1', '1', '2022-08-29 09:25:52');



-- CREATE TABLE `experience_letter` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `empId` INT NOT NULL , `issueDate` INT NOT NULL , `content` TEXT NOT NULL , `departmentId` INT NULL , `designationId` INT NULL , `session_table_id` INT NULL , `status` ENUM('1','2','3','4') NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

-- ALTER TABLE `experience_letter` ADD `employeeName` VARCHAR(100) NULL AFTER `empId`;

-- ALTER TABLE `salary` CHANGE `status` `status` ENUM('1','2',"3","4") CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1';

-- ALTER TABLE `experience_letter` CHANGE `issueDate` `issueDate` DATE NOT NULL;

-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Character Certificate', 'student/getCharacterCertificate', NULL, '4', '0', '1', '1', '2022-08-29 09:25:52');

-- CREATE TABLE `studentcharatercertificate` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `student_id` INT NOT NULL , `studentName` VARCHAR(100) NOT NULL , `class_id` INT NOT NULL , `section_id` INT NOT NULL , `content` TEXT NOT NULL , `issueDate` DATE NOT NULL , `status` ENUM('1','2','3','4') NOT NULL DEFAULT '1' , `session_table_id` INT NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE `studentcharatercertificate` ADD `tc_id` INT NOT NULL AFTER `issueDate`;