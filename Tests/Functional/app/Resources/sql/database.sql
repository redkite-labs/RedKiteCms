DROP TABLE IF EXISTS `al_block`;
CREATE TABLE IF NOT EXISTS `al_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL  DEFAULT '0',
  `language_id` int(11) NOT NULL  DEFAULT '0',
  `slot_name` varchar(255) NOT NULL  DEFAULT '',
  `class_name` varchar(255) NOT NULL DEFAULT 'Text',
  `html_content` text NOT NULL DEFAULT '',
  `internal_javascript` text NOT NULL DEFAULT '',
  `external_javascript` text NOT NULL DEFAULT '',
  `internal_stylesheet` text NOT NULL DEFAULT '',
  `external_stylesheet` text NOT NULL DEFAULT '',
  `to_delete` int(11) NOT NULL DEFAULT '0',
  `content_position` int(11) NOT NULL DEFAULT '1',
  `created_at` date NOT NULL default '1975-08-17 12:30:12',
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`,`language_id`),
  KEY `slot_name` (`slot_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `al_language`;
CREATE TABLE IF NOT EXISTS `al_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(10) NOT NULL DEFAULT '',
  `main_language` char(1) NOT NULL DEFAULT '0',
  `to_delete` int(11) NOT NULL DEFAULT '0',
  `created_at` date NOT NULL default '1975-08-17 12:30:12',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_language` (`id`),
  KEY `id_language_2` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `al_page`;
CREATE TABLE IF NOT EXISTS `al_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(255) NOT NULL DEFAULT '',
  `template_name` varchar(255) NOT NULL DEFAULT '',
  `is_home` int(1) NOT NULL DEFAULT '0',
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `to_delete` int(11) NOT NULL DEFAULT '0',
  `created_at` date NOT NULL default '1975-08-17 12:30:12',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `al_seo`;
CREATE TABLE IF NOT EXISTS `al_seo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `permalink` varchar(255) NOT NULL DEFAULT '',
  `meta_title` text NOT NULL DEFAULT '',
  `meta_description` text NOT NULL DEFAULT '',
  `meta_keywords` text NOT NULL DEFAULT '',
  `meta_title_frontend` text DEFAULT '',
  `meta_description_frontend` text DEFAULT '',
  `meta_keywords_frontend` text DEFAULT '',
  `sitemap_changefreq` text NOT NULL DEFAULT '',
  `sitemap_lastmod` text NOT NULL DEFAULT '',
  `sitemap_priority` text NOT NULL DEFAULT '',
  `to_delete` int(1) NOT NULL DEFAULT '0',
  `created_at` date NOT NULL default '1975-08-17 12:30:12',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
