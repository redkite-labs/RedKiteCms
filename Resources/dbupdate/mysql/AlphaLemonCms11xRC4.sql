CREATE TABLE IF NOT EXISTS `al_configuration` (
  `parameter` varchar(128) NOT NULL,
  `value` varchar(128) NOT NULL,
  PRIMARY KEY (`parameter`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `al_configuration` (`parameter`, `value`) VALUES ('language', 'en');