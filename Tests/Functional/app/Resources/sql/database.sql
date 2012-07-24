DROP TABLE IF EXISTS `al_block`;
CREATE TABLE IF NOT EXISTS `al_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `slot_name` varchar(255) NOT NULL,
  `class_name` varchar(255) NOT NULL DEFAULT 'Text',
  `html_content` text NOT NULL,
  `internal_javascript` text NOT NULL,
  `external_javascript` text NOT NULL,
  `internal_stylesheet` text NOT NULL,
  `external_stylesheet` text NOT NULL,
  `to_delete` int(11) NOT NULL DEFAULT '0',
  `content_position` int(11) NOT NULL DEFAULT '1',
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`,`language_id`),
  KEY `slot_name` (`slot_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `al_language`;
CREATE TABLE IF NOT EXISTS `al_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(10) NOT NULL,
  `main_language` char(1) NOT NULL DEFAULT '0',
  `to_delete` int(11) NOT NULL DEFAULT '0',
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_language` (`id`),
  KEY `id_language_2` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `al_page`;
CREATE TABLE IF NOT EXISTS `al_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(255) NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `is_home` int(1) NOT NULL,
  `is_published` tinyint(1) NOT NULL,
  `to_delete` int(11) NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `al_seo`;
CREATE TABLE IF NOT EXISTS `al_page_attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `permalink` varchar(255) NOT NULL,
  `meta_title` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_title_frontend` text,
  `meta_description_frontend` text,
  `meta_keywords_frontend` text,
  `sitemap_changefreq` text NOT NULL,
  `sitemap_lastmod` text NOT NULL,
  `sitemap_priority` text NOT NULL,
  `to_delete` int(1) NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `al_theme`;
CREATE TABLE IF NOT EXISTS `al_theme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_name` varchar(255) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_progetto` (`id`),
  KEY `id_progetto_2` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
