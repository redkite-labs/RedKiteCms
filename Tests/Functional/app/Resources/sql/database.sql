DROP TABLE IF EXISTS `al_block`;
CREATE TABLE IF NOT EXISTS `al_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `slot_name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'Text',
  `content` text NOT NULL,
  `internal_javascript` text,
  `external_javascript` text,
  `internal_stylesheet` text,
  `external_stylesheet` text,
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
  `language_name` varchar(10) NOT NULL,
  `main_language` INT(11) NOT NULL DEFAULT '0',
  `to_delete` int(11) NOT NULL DEFAULT '0',
  `created_at` date NOT NULL default '1975-08-17 12:30:12',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_language` (`id`),
  KEY `id_language_2` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `al_page`;
CREATE TABLE IF NOT EXISTS `al_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(255) NOT NULL,
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
  `language_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `permalink` varchar(255) NOT NULL,
  `meta_title` varchar(255) DEFAULT '',
  `meta_description` text,
  `meta_keywords` text,
  `meta_title_frontend` text,
  `meta_description_frontend` text,
  `meta_keywords_frontend` text,
  `sitemap_changefreq` varchar(255) NOT NULL DEFAULT '',
  `sitemap_lastmod` varchar(255) NOT NULL DEFAULT '',
  `sitemap_priority` varchar(255) NOT NULL DEFAULT '',
  `to_delete` int(1) NOT NULL DEFAULT '0',
  `created_at` date NOT NULL default '1975-08-17 12:30:12',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `al_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `ip` varchar(15) NOT NULL DEFAULT '127.0.0.1',
  `created_at` datetime NOT NULL DEFAULT '1975-08-17 12:30:12',
  PRIMARY KEY (`id`),
  UNIQUE KEY `I_USERNAME` (`username`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `al_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `I_ROLENAME` (`role`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `al_locked_resource` (
  `resource_name` varchar(32) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '1975-08-17 12:30:12',
  `updated_at` datetime NOT NULL DEFAULT '1975-08-17 12:30:12',
  PRIMARY KEY (`resource_name`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;