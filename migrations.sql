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


CREATE TABLE `feetype` ( `id` INT NOT NULL , `schoolUniqueCode` VARCHAR(100) NOT NULL , `feeTypeName` VARCHAR(255) NOT NULL , `durationType` INT NOT NULL , `amount` FLOAT NOT NULL , `class_id` INT NOT NULL , `session_table_id` INT NOT NULL , `status` ENUM("1","2","3","4") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;





-- CREATE TABLE `newfeestypes` ( `id` INT NOT NULL , `schoolUniqueCode` VARCHAR(100) NOT NULL , `feeTypeName` VARCHAR(200) NOT NULL , `shortCode` VARCHAR(100) NULL , `description` TEXT NULL , `session_table_id` INT NOT NULL , `status` ENUM("1","2","3","4") NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

-- ALTER TABLE `newfeestypes` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;

-- CREATE TABLE `newfeesgroups` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `feeGroupName` VARCHAR(200) NOT NULL , `shortCode` VARCHAR(100) NULL , `description` TEXT NULL , `session_table_id` INT NOT NULL , `status` ENUM("1","2","3","4") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

-- CREATE TABLE `newfeesdiscounts` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `feeDiscountName` VARCHAR(200) NOT NULL , `shortCode` VARCHAR(100) NULL , `description` TEXT NULL , `amount` FLOAT NOT NULL , `session_table_id` INT NOT NULL , `status` ENUM("1","2","3","4") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

-- CREATE TABLE `newfeemaster` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `newFeeGroupId` INT NOT NULL , `newFeeType` JSON NOT NULL , `dueDate` DATE NULL , `amount` FLOAT NOT NULL , `fineType` ENUM("1","2","3","4") NOT NULL DEFAULT '1' COMMENT '1 - None, 2 - Percentage, 3- Fixed Amount' , `finePercentage` FLOAT NULL , `fineFixAmount` FLOAT NULL , `session_table_id` INT NOT NULL , `status` ENUM("1","2","3","4") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;


-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'New Fees Management', '#', 'fa-solid fa-money-bill-wave', '0', '1', '0', '1', '2022-09-10 10:40:10');
-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Fees Type Master', 'feesManagement/feeTypeMaster', NULL, '78', '0', '1', '1', '2022-08-29 09:25:52');
-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Fees Group Master', 'feesManagement/feeGroupMaster', NULL, '78', '0', '1', '1', '2022-08-29 09:25:52');
-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Fees Discount Master', 'feesManagement/feeDisctountMaster', NULL, '78', '0', '1', '1', '2022-08-29 09:25:52');
-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Main Fees Master', 'feesManagement/feeHeadMaster', NULL, '78', '0', '1', '1', '2022-08-29 09:25:52');
-- ALTER TABLE `newfeemaster` CHANGE `newFeeType` `newFeeType` INT NOT NULL;

-- CREATE TABLE `newfeeclasswise` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `class_id` INT NOT NULL , `section_id` INT NOT NULL , `fee_group_id` INT NOT NULL , `fee_type_id` INT NOT NULL , `session_table_id` INT NULL , `status` ENUM("1","2","3","4") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;
-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Collect Fees', 'feesManagement/collectFee', NULL, '78', '0', '1', '1', '2022-08-29 09:25:52');

-- CREATE TABLE `newfeessubmitmaster` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `stuId` INT NOT NULL , `fmtId` INT NOT NULL , `nftId` INT NOT NULL , `nfgId` INT NOT NULL , `depositAmount` FLOAT NOT NULL , `invoiceId` VARCHAR(100) NOT NULL , `depositDate` DATE NOT NULL , `paymentMode` ENUM("1","2") NOT NULL , `discount` FLOAT NOT NULL , `fine` FLOAT NOT NULL , `paid` FLOAT NOT NULL , `depositerName` VARCHAR(100) NOT NULL , `depositerAddress` VARCHAR(100) NOT NULL , `depositerMobileNo` VARCHAR(100) NULL , `session_table_id` INT NOT NULL , `status` ENUM("1","2","3","4") NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `note` TEXT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;
-- ALTER TABLE `newfeessubmitmaster` ADD `classId` INT NOT NULL AFTER `stuId`, ADD `sectionId` INT NOT NULL AFTER `classId`;


-- ALTER TABLE `newfeessubmitmaster` ADD `randomToken` TEXT NULL AFTER `note`;
-- ALTER TABLE `newfeeclasswise` ADD `student_id` INT NULL AFTER `schoolUniqueCode`;
-- ALTER TABLE `newfeeclasswise` CHANGE `fee_type_id` `fee_type_id` INT(11) NULL;


-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Fees Carry Forward', 'feesManagement/carryForward', NULL, '78', '0', '1', '1', '2022-08-29 14:55:52');

-- ALTER TABLE `student_history` ADD `currentClassId` INT NULL AFTER `session_table_id`, ADD `currentSessionId` INT NULL AFTER `currentClassId`;
-- ALTER TABLE `student_history` ADD `old_session_id` INT NULL AFTER `student_id`;

-- ALTER TABLE `students` CHANGE `date_of_admission` `date_of_admission` VARCHAR NULL DEFAULT NULL;

CREATE TABLE `gatepass` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `student_id` INT NOT NULL , `class_id` INT NOT NULL , `section_id` INT NOT NULL , `guardian_name` VARCHAR(100) NOT NULL , `mobile` VARCHAR(100) NOT NULL , `address` VARCHAR(100) NOT NULL , `image` TEXT NOT NULL , `time` TIME NOT NULL , `date` DATE NOT NULL , `status` ENUM("1","2") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;