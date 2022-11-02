CREATE TABLE `token_filter` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `token` TEXT NOT NULL , `for_what` VARCHAR(100) NOT NULL , `status` ENUM("1","2") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

ALTER TABLE `token_filter` ADD `insertId` INT NULL AFTER `for_what`;
ALTER TABLE `check_salary_slip` ADD `generateDate` DATE NOT NULL AFTER `status`;