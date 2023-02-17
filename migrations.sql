-- ALTER TABLE `admin_panel_menu` ADD `img` VARCHAR(255) NULL AFTER `is_child`;
-- ALTER TABLE `admin_panel_menu` ADD `position` INT NULL AFTER `img`
-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `img`, `position`, `status`, `created_at`) VALUES (NULL, 'Create Question Paper', 'questionBank/createQuestionPaper', NULL, '91', '0', '1', NULL, NULL, '1', '2022-08-29 09:25:52');


-- CREATE TABLE `schoolmodules` ( `id` INT NOT NULL , `schoolUniqueCode` VARCHAR(100) NOT NULL , `modules` JSON NOT NULL , `status` ENUM('1','2') NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

-- CREATE TABLE `adm_registration` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `regNo` VARCHAR(100) NOT NULL , `regDate` DATE NOT NULL , `stuName` VARCHAR(100) NOT NULL , `gender` INT NOT NULL , `class` INT NOT NULL , `category` VARCHAR(100) NOT NULL , `father_name` VARCHAR(100) NULL , `mother_name` VARCHAR(100) NULL , `email` VARCHAR(100) NULL , `mobile` VARCHAR(100) NOT NULL , `address` VARCHAR(255) NOT NULL , `state` INT NOT NULL , `city` INT NOT NULL , `pincode` INT(10) NOT NULL , `dob` DATE NOT NULL , `father_occupation` VARCHAR(100) NULL , `last_school_name` VARCHAR(255) NULL , `last_class` VARCHAR(100) NULL , `reg_fee` VARCHAR(100) NULL , `session_table_id` INT NOT NULL , `status` ENUM("1","2") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

-- ALTER TABLE `adm_registration` ADD `image` VARCHAR(255) NULL AFTER `reg_fee`;

-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `img`, `position`, `status`, `created_at`) VALUES (NULL, 'Registration', '#', 'fa-regular fa-notebook', '0', '1', '0', 'essay.png', '15', '1', '2022-09-10 16:10:10');
-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `img`, `position`, `status`, `created_at`) VALUES (NULL, 'Add New Registration', 'registration/newRegistration', NULL, '97', '0', '1', NULL, NULL, '1', '2022-08-29 09:25:52');
-- INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `img`, `position`, `status`, `created_at`) VALUES (NULL, 'Registration Lists', 'registration/registrationLists', NULL, '97', '0', '1', NULL, NULL, '1', '2022-08-29 09:25:52');


-- ALTER TABLE `adm_registration` CHANGE `status` `status` ENUM('1','2','3','4') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '1 => Registration, 2 => Admission, 3 => Doubt';

-- ALTER TABLE `adm_registration` CHANGE `status` `status` ENUM('1','2',"3","4") CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1';


INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `img`, `position`, `status`, `created_at`) VALUES (NULL, 'Download Id Card', 'showDownloadIDCardTeacher', NULL, '12', '0', '1', NULL, NULL, '1', '2022-08-29 09:25:52')