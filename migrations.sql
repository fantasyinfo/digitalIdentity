-- CREATE TABLE `sem_exam_results` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `sem_id` INT NOT NULL , `sec_exam_id` INT NOT NULL , `class_id` INT NOT NULL , `section_id` INT NOT NULL , `subject_id` INT NOT NULL , `student_id` INT NOT NULL , `marks` FLOAT NOT NULL , `status` ENUM("1","2","3","4") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;


-- ALTER TABLE `schoolmaster` ADD `fee_invoice_start` VARCHAR(100) NULL AFTER `session_ended_to_year`;

INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `status`, `created_at`) VALUES (NULL, 'Download Students QR Code', 'showDownloadQR', NULL, '4', '0', '1', '1', '2022-08-29 03:55:52');


ALTER TABLE `fees` ADD `tution_fees_amt` FLOAT NULL DEFAULT '0' AFTER `fees_amt`, ADD `reg_fees` FLOAT NULL DEFAULT '0' AFTER `tution_fees_amt`, ADD `adm_fees` FLOAT NULL DEFAULT '0' AFTER `reg_fees`, ADD `id_card_fees` FLOAT NULL DEFAULT '0' AFTER `adm_fees`, ADD `development_fees` FLOAT NULL DEFAULT '0' AFTER `id_card_fees`, ADD `annual_function_fees` FLOAT NULL DEFAULT '0' AFTER `development_fees`, ADD `book_and_stationary_fees` FLOAT NULL DEFAULT '0' AFTER `annual_function_fees`, ADD `uniform_fees` FLOAT NULL DEFAULT '0' AFTER `book_and_stationary_fees`, ADD `worksheet_examination_fees` FLOAT NULL DEFAULT '0' AFTER `uniform_fees`, ADD `extra_curricular_fees` FLOAT NULL DEFAULT '0' AFTER `worksheet_examination_fees`;

ALTER TABLE `fees` ADD `smart_class_fees` FLOAT NULL DEFAULT '0' AFTER `extra_curricular_fees`, ADD `transport_fees` FLOAT NULL DEFAULT '0' AFTER `smart_class_fees`;


ALTER TABLE `students` ADD `sr_number` VARCHAR(100) NULL AFTER `driver_id`;
ALTER TABLE `teachers` ADD `cbse_id` VARCHAR(100) NULL AFTER `experience`;