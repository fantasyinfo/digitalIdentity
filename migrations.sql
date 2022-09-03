ALTER TABLE `state` ADD `schoolUniqueCode` VARCHAR(100) NOT NULL AFTER `id`;
ALTER TABLE `state` CHANGE `status` `status` ENUM('1','2',"3","4") CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1';
UPDATE city SET schoolUniqueCode = '683611' WHERE schoolUniqueCode = '';
UPDATE class SET schoolUniqueCode = '683611' WHERE schoolUniqueCode = '';
UPDATE section SET schoolUniqueCode = '683611' WHERE schoolUniqueCode = '';
UPDATE state SET schoolUniqueCode = '683611' WHERE schoolUniqueCode IS NULL;
UPDATE subject SET schoolUniqueCode = '683611' WHERE schoolUniqueCode = '';