-- ALTER TABLE `state` ADD `schoolUniqueCode` VARCHAR(100) NOT NULL AFTER `id`;
-- ALTER TABLE `state` CHANGE `status` `status` ENUM('1','2',"3","4") CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1';
-- UPDATE city SET schoolUniqueCode = '683611' WHERE schoolUniqueCode = '';
-- UPDATE class SET schoolUniqueCode = '683611' WHERE schoolUniqueCode = '';
-- UPDATE section SET schoolUniqueCode = '683611' WHERE schoolUniqueCode = '';
-- UPDATE state SET schoolUniqueCode = '683611' WHERE schoolUniqueCode IS NULL;
-- UPDATE subject SET schoolUniqueCode = '683611' WHERE schoolUniqueCode = '';
-- CREATE TABLE `gift` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `gift_name` VARCHAR(100) NOT NULL , `gift_image` VARCHAR(255) NOT NULL , `redeem_digiCoins` INT NOT NULL , `user_type` ENUM('1','2','3','4') NOT NULL , `status` ENUM('1','2','3','4') NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

-- INSERT INTO `admin_panel_menu` (`name`, `link`, `icon`, `parent_id`, `is_parent`, `is_child`) VALUES ('Gift Master', 'digicoin/giftMaster', NULL, '33', '0', '1');


-- CREATE TABLE `home_work` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `login_user_id` INT NOT NULL , `login_user_type` VARCHAR(100) NOT NULL , `class_id` INT NOT NULL , `section_id` INT NOT NULL , `subject_id` INT NOT NULL , `home_work_note` TEXT NOT NULL , `home_work_date` DATE NOT NULL , `home_work_finish_date` DATE NOT NULL , `status` ENUM("1","2","3","4") NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;


-- CREATE TABLE `redeem_gifts` ( `id` INT NOT NULL AUTO_INCREMENT , `schoolUniqueCode` VARCHAR(100) NOT NULL , `login_user_id` INT NOT NULL , `login_user_type` INT NOT NULL , `gift_id` INT NOT NULL , `digiCoin_used` INT NOT NULL , `status` ENUM('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1=>pending,2=>send,3=>intransit,4=>deliverd' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;