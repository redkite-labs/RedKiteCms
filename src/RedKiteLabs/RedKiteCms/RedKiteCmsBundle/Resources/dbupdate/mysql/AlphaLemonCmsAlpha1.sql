RENAME TABLE `al_page_attribute` TO `al_seo`;
UPDATE `al_block` SET `class_name` = "Text" WHERE `class_name` = "Media";
UPDATE `al_block` SET `html_content` = REPLACE(html_content, '/bundles/alphalemoncms', '') WHERE `html_content` like '%/bundles/alphalemoncms/uploads/assets%';
UPDATE `al_block` SET `internal_javascript` = REPLACE(internal_javascript, '/bundles/alphalemoncms', '') WHERE `internal_javascript` like '%/bundles/alphalemoncms/uploads/assets%';
