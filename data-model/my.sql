CREATE DATABASE `stack_network_kinlane_blogapi` /*!40100 DEFAULT CHARACTER SET latin1 */;

DROP TABLE IF EXISTS `stack_network_kinlane_blogapi`.`blog`;
CREATE TABLE  `stack_network_kinlane_blogapi`.`blog` (
  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_date` datetime DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `body` text,
  `url` varchar(1500) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  FULLTEXT KEY `Body` (`Body`,`Title`)
) ENGINE=MyISAM AUTO_INCREMENT=12146 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `stack_network_kinlane_blogapi`.`blog_image`;
CREATE TABLE  `stack_network_kinlane_blogapi`.`blog_image` (
  `blog_image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(10) unsigned NOT NULL,
  `image_name` varchar(1000) DEFAULT NULL,
  `image_path` varchar(1500) DEFAULT NULL,
  PRIMARY KEY (`blog_image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `stack_network_kinlane_blogapi`.`blog_keyword_pivot`;
CREATE TABLE  `stack_network_kinlane_blogapi`.`blog_keyword_pivot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) DEFAULT NULL,
  `Keyword_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `stack_network_kinlane_blogapi`.`blog_tag_pivot`;
CREATE TABLE  `stack_network_kinlane_blogapi`.`blog_tag_pivot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=52173 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `stack_network_kinlane_blogapi`.`tags`;
CREATE TABLE  `stack_network_kinlane_blogapi`.`tags` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(250) NOT NULL,
  `type` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`tag_id`,`tag`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15223 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
