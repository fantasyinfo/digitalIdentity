CREATE TABLE `sem_exam_results` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `sem_id` INT NOT NULL , `sec_exam_id` INT NOT NULL , `class_id` INT NOT NULL , `section_id` INT NOT NULL , `subject_id` INT NOT NULL , `student_id` INT NOT NULL , `marks` FLOAT NOT NULL , `status` ENUM("1","2","3","4") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;


ALTER TABLE `schoolmaster` ADD `fee_invoice_start` VARCHAR(100) NULL AFTER `session_ended_to_year`;