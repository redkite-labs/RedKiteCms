RENAME TABLE `al_page_attribute` TO `al_seo`;
UPDATE `al_block` SET `class_name` = "Text" WHERE `class_name` = "Media";