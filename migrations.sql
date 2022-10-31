-- CREATE TABLE `sem_exam_results` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `sem_id` INT NOT NULL , `sec_exam_id` INT NOT NULL , `class_id` INT NOT NULL , `section_id` INT NOT NULL , `subject_id` INT NOT NULL , `student_id` INT NOT NULL , `marks` FLOAT NOT NULL , `status` ENUM("1","2","3","4") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;


-- ALTER TABLE `schoolmaster` ADD `fee_invoice_start` VARCHAR(100) NULL AFTER `session_ended_to_year`;

-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Download Students QR Code', 'showDownloadQR', NULL, '4', '0', '1', '1', '2022-08-29 03:55:52');


-- ALTER TABLE `fees` ADD `tution_fees_amt` FLOAT NULL DEFAULT '0' AFTER `fees_amt`, ADD `reg_fees` FLOAT NULL DEFAULT '0' AFTER `tution_fees_amt`, ADD `adm_fees` FLOAT NULL DEFAULT '0' AFTER `reg_fees`, ADD `id_card_fees` FLOAT NULL DEFAULT '0' AFTER `adm_fees`, ADD `development_fees` FLOAT NULL DEFAULT '0' AFTER `id_card_fees`, ADD `annual_function_fees` FLOAT NULL DEFAULT '0' AFTER `development_fees`, ADD `book_and_stationary_fees` FLOAT NULL DEFAULT '0' AFTER `annual_function_fees`, ADD `uniform_fees` FLOAT NULL DEFAULT '0' AFTER `book_and_stationary_fees`, ADD `worksheet_examination_fees` FLOAT NULL DEFAULT '0' AFTER `uniform_fees`, ADD `extra_curricular_fees` FLOAT NULL DEFAULT '0' AFTER `worksheet_examination_fees`;

-- ALTER TABLE `fees` ADD `smart_class_fees` FLOAT NULL DEFAULT '0' AFTER `extra_curricular_fees`, ADD `transport_fees` FLOAT NULL DEFAULT '0' AFTER `smart_class_fees`;


-- ALTER TABLE `students` ADD `sr_number` VARCHAR(100) NULL AFTER `driver_id`;
-- ALTER TABLE `teachers` ADD `cbse_id` VARCHAR(100) NULL AFTER `experience`;


-- ALTER TABLE `schoolmaster` ADD `gifts_system` ENUM('1','2',"3","4") NULL DEFAULT '1' COMMENT '1 => enabled, 2 => disabled' AFTER `fee_invoice_start`;

-- ALTER TABLE `students` ADD `cast_category` VARCHAR(100) NULL AFTER `sr_number`, ADD `date_of_admission` DATE NULL AFTER `cast_category`;
-- ALTER TABLE `students` ADD `admission_no` VARCHAR(100) NULL AFTER `date_of_admission`;


-- CREATE TABLE `school_sessions` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `session_start_year` INT NOT NULL , `session_start_month` INT NOT NULL , `session_end_year` INT NOT NULL , `session_end_month` INT NOT NULL , `status` ENUM('1','2','3','4') NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;


-- CREATE TABLE `student_history` ( `id` INT NOT NULL , `schoolUniqueCode` VARCHAR(100) NOT NULL , `student_id` INT NOT NULL , `session_table_id` INT NOT NULL , `class_id` INT NOT NULL , `section_id` INT NOT NULL , `fees_due` FLOAT NULL , `status` ENUM("1","2","3","4") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;


-- ALTER TABLE `sem_exam_results` ADD `session_table_id` INT NULL AFTER `status`;

-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Session Master', 'master/sessionMaster', NULL, '40', '0', '1', '1', '2022-08-29 09:25:52');

-- ALTER TABLE `school_sessions` CHANGE `session_start_month` `session_start_month` VARCHAR(100) NOT NULL;
-- ALTER TABLE `school_sessions` CHANGE `session_end_month` `session_end_month` VARCHAR(100) NOT NULL;
-- ALTER TABLE `schoolmaster` ADD `current_session` INT NULL AFTER `gifts_system`;


-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Permote Students', 'student/permoteStudent', NULL, '4', '0', '1', '1', '2022-08-29 09:25:52');

-- ALTER TABLE `student_history` CHANGE `schoolUniqueCode` `schoolUniqueCode` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

-- ALTER TABLE `student_history` CHANGE `status` `status` ENUM('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1';


-- ALTER TABLE school_sessions CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- ALTER TABLE schoolmaster CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- ALTER TABLE `attendence` ADD `session_table_id` INT NULL AFTER `status`;
-- ALTER TABLE `attendenceteachers` ADD `session_table_id` INT NULL AFTER `status`;
-- ALTER TABLE `departure` ADD `session_table_id` INT NULL AFTER `status`;
-- ALTER TABLE `exam` ADD `session_table_id` INT NULL AFTER `status`;
-- ALTER TABLE `fees` ADD `session_table_id` INT NULL AFTER `status`;
-- ALTER TABLE `feesforstudent` ADD `session_table_id` INT NULL AFTER `status`;
-- ALTER TABLE `holiday_calendar` ADD `session_table_id` INT NULL AFTER `status`;
-- ALTER TABLE `result` ADD `session_table_id` INT NULL AFTER `status`;
-- ALTER TABLE `sec_exam_table` ADD `session_table_id` INT NULL AFTER `status`;
-- ALTER TABLE `sem_exam` ADD `session_table_id` INT NULL AFTER `status`;
-- ALTER TABLE `visitor_entry` ADD `session_table_id` INT NULL AFTER `status`;


-- CREATE TABLE `student_tc` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `book_register_no` VARCHAR(100) NOT NULL , `s_i_s_r_no` VARCHAR(100) NOT NULL , `admission_no` VARCHAR(100) NOT NULL , `student_id` INT NOT NULL , `student_name` VARCHAR(100) NOT NULL , `father_name` VARCHAR(100) NOT NULL , `mother_name` VARCHAR(100) NOT NULL , `gender` VARCHAR(100) NOT NULL , `date_of_birth` DATE NOT NULL , `category` VARCHAR(100) NOT NULL , `nationality` VARCHAR(100) NOT NULL , `date_of_admission` DATE NOT NULL , `last_class_studies` VARCHAR(100) NOT NULL , `board_exam_last_taken` VARCHAR(100) NOT NULL , `failed_in_class` VARCHAR(100) NOT NULL , `subjects_studies` JSON NOT NULL , `qualify_for_permotion` VARCHAR(100) NOT NULL , `fees_due` VARCHAR(100) NOT NULL , `total_working_days` INT NOT NULL , `total_present_days` INT NOT NULL , `ncc_cadet` VARCHAR(100) NOT NULL , `game_played` VARCHAR(100) NOT NULL , `general_conduct` VARCHAR(100) NOT NULL , `date_of_application` DATE NOT NULL , `date_of_issue` DATE NOT NULL , `reason_for_leaving` VARCHAR(100) NOT NULL , `remark` VARCHAR(100) NOT NULL , `other_details` VARCHAR(100) NOT NULL , `session_table_id` INT NOT NULL , `status` ENUM("1","2") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;


-- ALTER TABLE `student_tc` CHANGE `other_details` `shedule_tribe` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
-- ALTER TABLE `student_tc` CHANGE `subjects_studies` `subjects_studies` VARCHAR(100) NOT NULL;


-- ALTER TABLE student_tc CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE `student_tc` ADD `user_id` VARCHAR(100) NULL AFTER `student_id`;

-- ALTER TABLE `sem_exam_results` ADD `result_status` INT NOT NULL COMMENT '1 => Pass || 2 => Fail' AFTER `marks`;



-- ALTER TABLE sem_exam_results CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE sec_exam_table CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE students CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE sem_exam CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE class CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE section CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE subject CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- ALTER TABLE schoolmaster  CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


-- ALTER TABLE `push_notification` ADD `image` TEXT NULL AFTER `for_what`;


-- CREATE TABLE `department` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `departmentName` VARCHAR(100) NOT NULL , `status` ENUM('1','2','3','4') NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

-- ALTER TABLE department CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- CREATE TABLE `designation` (
--   `id` int(11) NOT NULL,
--   `schoolUniqueCode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
--   `departmentId` int(100) NOT NULL,
--   `designationName` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
--   `status` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
--   `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ALTER TABLE `designation`
--   ADD PRIMARY KEY (`id`);

-- ALTER TABLE `designation`
--   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
-- COMMIT;


-- CREATE TABLE `bankdetails` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `empId` INT NOT NULL , `bankName` VARCHAR(100) NOT NULL , `accNo` VARCHAR(100) NOT NULL , `ifscNo` VARCHAR(100) NOT NULL , `swiftCode` VARCHAR(100) NOT NULL , `branch` VARCHAR(100) NOT NULL , `status` ENUM('1','2','3','4') NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

-- CREATE TABLE `salary` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` INT NOT NULL , `empId` INT NOT NULL , `departmentId` INT NOT NULL , `designationId` INT NOT NULL , `bankId` INT NULL , `location` VARCHAR(100) NULL , `panNo` VARCHAR(20) NULL , `pfAccNo` VARCHAR(100) NULL , `doj` DATE NULL , `basicSalaryMonth` INT NOT NULL , `basicSalaryDay` INT NOT NULL , `dearnessAll` INT NULL , `hra` INT NULL , `conAll` INT NULL , `medicalAll` INT NULL , `specialAll` INT NULL , `leavesPerMonth` INT NULL , `lwp` INT NULL , `professionalTaxPerMonth` INT NULL , `pfPerMonth` INT NULL , `tdsPerMonth` INT NULL , `totalDed` INT NULL , `status` ENUM("1","2") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

-- ALTER TABLE `salary` CHANGE `empId` `empId` INT(11) NULL, CHANGE `departmentId` `departmentId` INT(11) NULL, CHANGE `designationId` `designationId` INT(11) NULL;
-- ALTER TABLE `salary` ADD `employeeName` VARCHAR(100) NOT NULL AFTER `empId`;

-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Payroll', '#', 'fa-solid fa-money-bills', '0', '1', '0', '1', '2022-09-10 10:40:10');
-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Department', 'master/department', NULL, '69', '0', '1', '1', '2022-08-29 09:25:52');
-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Designation', 'master/designation', NULL, '69', '0', '1', '1', '2022-08-29 09:25:52');

-- ALTER TABLE `salary` CHANGE `empId` `empId` VARCHAR(100) NULL DEFAULT NULL;
-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Salary Master', 'master/salaryMaster', NULL, '69', '0', '1', '1', '2022-08-29 09:25:52');

ALTER TABLE `salary` ADD `ded_half_day` INT NULL AFTER `lwp`;
ALTER TABLE `salary` ADD `session_table_id` INT NULL AFTER `totalDed`;
INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Check Salary', 'master/checkSalary', NULL, '69', '0', '1', '1', '2022-08-29 09:25:52');


CREATE TABLE `staffattendance` (
  `id` int(11) NOT NULL,
  `schoolUniqueCode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  `employeeName` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `departmentName` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `login_user_id` int(11) NOT NULL,
  `login_user_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `attendenceStatus` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1' COMMENT '1 => absent, 2=> present, 3 => halfday, 4 => leave',
  `dateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `att_date` date DEFAULT NULL,
  `status` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
  `session_table_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `staffattendance`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `staffattendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;