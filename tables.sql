SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
CREATE TABLE IF NOT EXISTS `devices` (
  `device_id` int(7) NOT NULL AUTO_INCREMENT,
  `address` varchar(18) NOT NULL,
  `device_description` varchar(100) NOT NULL,
  `allowed` tinyint(1) NOT NULL,
  `first_seen` datetime NOT NULL,
  `last_seen` datetime NOT NULL,
  `first_network` varchar(7) NOT NULL,
  `last_network` varchar(7) NOT NULL,
  PRIMARY KEY (`device_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1515 ;
