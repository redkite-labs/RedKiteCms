DROP TABLE IF EXISTS `al_role`;
CREATE TABLE IF NOT EXISTS `al_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `I_ROLE` (`id`),
  UNIQUE KEY `I_ROLENAME` (`role`)
) ENGINE=innoDb  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

INSERT INTO `al_role` (`id`, `role`) VALUES
(1, 'ROLE_USER'),
(2, 'ROLE_ADMIN'),
(3, 'ROLE_SUPER_ADMIN');

DROP TABLE IF EXISTS `al_user`;
CREATE TABLE IF NOT EXISTS `al_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `I_USER` (`id`),
  UNIQUE KEY `I_USERNAME` (`username`),
  KEY `I_ROLE` (`role_id`)
) ENGINE=innoDb  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

INSERT INTO `al_user` (`id`, `role_id`, `username`, `password`, `salt`, `email`, `ip`, `created_at`) VALUES
(1, 2, 'admin', 'e+bya4WFVA1Wh+KK98MxsltYfdoFar8Br2L+TZkHjCbJyxSw3+FmjEAbtOFg+kjw+1fqCV3rz4T3+xz9IRmnyQ==', '5k89t44zr5wkccccws44k0w8cwg0s8o', '', NULL, '0000-00-00');     