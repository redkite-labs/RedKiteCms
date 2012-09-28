ALTER TABLE `al_block` CHANGE `html_content` `content` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `al_block` CHANGE `class_name` `type` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'Text';
ALTER TABLE `al_language` CHANGE `language` `language_name` VARCHAR( 5 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `al_language` CHANGE `main_language` `main_language` INT( 1 ) NOT NULL DEFAULT '0';