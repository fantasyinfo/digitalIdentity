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

-- CREATE TABLE `gatepass` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `student_id` INT NOT NULL , `class_id` INT NOT NULL , `section_id` INT NOT NULL , `guardian_name` VARCHAR(100) NOT NULL , `mobile` VARCHAR(100) NOT NULL , `address` VARCHAR(100) NOT NULL , `image` TEXT NOT NULL , `time` TIME NOT NULL , `date` DATE NOT NULL , `status` ENUM("1","2") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;


-- CREATE TABLE `qrscanhistory` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `qrcode` VARCHAR(100) NOT NULL , `student_id` INT NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

-- ALTER TABLE `qrscanhistory` ADD `user_type_id` INT NOT NULL AFTER `student_id`;
-- ALTER TABLE `qrscanhistory` CHANGE `student_id` `user_id` INT(11) NOT NULL;


-- CREATE TABLE `advancefeessystem` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `class_id` INT NOT NULL , `feesData` JSON NOT NULL , `status` ENUM("1","2") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

-- ALTER TABLE `advancefeessystem` ADD `section_id` INT NOT NULL AFTER `class_id`;
-- ALTER TABLE `advancefeessystem` ADD `session_table_id` INT NOT NULL AFTER `feesData`;

-- ALTER TABLE `newfeestypes` ADD `durationType` ENUM('1','2',"3","4") NULL COMMENT '1= monthly, 2= one time, 3 = yearly, ' AFTER `description`;

-- ALTER TABLE `newfeestypes` CHANGE `durationType` `durationType` ENUM('1','2','3','4') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '1= monthly, 2 = yearly, ';

-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Bonafide Certificate', 'student/getBonafideCertificate', NULL, '4', '0', '1', '1', '2022-08-29 09:25:52');

-- CREATE TABLE `studentbonafidecertificate` (
--   `id` int(11) NOT NULL,
--   `schoolUniqueCode` varchar(100) NOT NULL,
--   `student_id` int(11) NOT NULL,
--   `studentName` varchar(100) NOT NULL,
--   `class_id` int(11) NOT NULL,
--   `section_id` int(11) NOT NULL,
--   `content` text NOT NULL,
--   `issueDate` date NOT NULL,
--   `status` enum('1','2','3','4') NOT NULL DEFAULT '1',
--   `session_table_id` int(11) NOT NULL,
--   `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ALTER TABLE `studentbonafidecertificate`
--   ADD PRIMARY KEY (`id`);

-- ALTER TABLE `studentbonafidecertificate`
--   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
-- COMMIT;


CREATE TABLE `srregister` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `book_no` VARCHAR(100) NULL , `sr_no` VARCHAR(100) NOT NULL , `name` VARCHAR(100) NOT NULL , `nationality` VARCHAR(100) NOT NULL , `caste` VARCHAR(100) NOT NULL , `father_name` VARCHAR(100) NOT NULL , `occupation` VARCHAR(100) NOT NULL , `mother_name` VARCHAR(100) NOT NULL , `address` VARCHAR(255) NOT NULL , `last_school_name` VARCHAR(100) NULL , `residence_of_india_since` VARCHAR(100) NOT NULL , `dob` VARCHAR(100) NOT NULL , `date_of_birth_words` VARCHAR(255) NOT NULL , `className` VARCHAR(100) NOT NULL , `date_of_admission` VARCHAR(100) NOT NULL , `date_of_permotion` VARCHAR(100) NULL , `date_of_removal` VARCHAR(100) NULL , `cause_of_removal` VARCHAR(100) NULL , `session_year` VARCHAR(100) NOT NULL , `conduct` VARCHAR(100) NULL , `work` VARCHAR(100) NULL , `signature` VARCHAR(100) NULL , `session_table_id` INT NOT NULL , `status` ENUM("1","2") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

ALTER TABLE `srregister` ADD `student_id` INT NOT NULL AFTER `sr_no`, ADD `unique_id` VARCHAR(100) NULL AFTER `student_id`;