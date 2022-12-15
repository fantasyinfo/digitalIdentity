ALTER TABLE `admin_panel_menu` ADD `img` VARCHAR(255) NULL AFTER `is_child`;
ALTER TABLE `admin_panel_menu` ADD `position` INT NULL AFTER `img`
INSERT INTO `admin_panel_menu` (`id`, `name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`, `img`, `position`, `status`, `created_at`) VALUES (NULL, 'Create Question Paper', 'questionBank/createQuestionPaper', NULL, '91', '0', '1', NULL, NULL, '1', '2022-08-29 09:25:52');


CREATE TABLE `schoolmodules` ( `id` INT NOT NULL , `schoolUniqueCode` VARCHAR(100) NOT NULL , `modules` JSON NOT NULL , `status` ENUM('1','2') NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;