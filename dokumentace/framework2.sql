SET CHARACTER SET utf8;

CREATE TABLE `core_categories` (
  `id_category` int(10) unsigned NOT NULL auto_increment,
  `parent` int(10) unsigned NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `url` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `core_categories` VALUES ('3', '0', 'dalsi', 'dalsi');
INSERT INTO `core_categories` VALUES ('4', '0', 'a jeste jedna', 'a-jeste-jedna');

CREATE TABLE `core_groups` (
  `id_group` int(10) unsigned NOT NULL default '0',
  `name` int(10) unsigned NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `posts_writing` enum('0','1') collate utf8_czech_ci default NULL,
  `posts_editing` enum('0','1') collate utf8_czech_ci default NULL,
  `posts_deleting` enum('0','1') collate utf8_czech_ci default NULL,
  `categories_managing` enum('0','1') collate utf8_czech_ci default NULL,
  `texts_managing` enum('0','1') collate utf8_czech_ci default NULL,
  `comments_managing` enum('0','1') collate utf8_czech_ci default NULL,
  `settings_managing` enum('0','1') collate utf8_czech_ci default NULL,
  `users_managing` enum('0','1') collate utf8_czech_ci default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `core_posts` (
  `id_post` int(10) unsigned NOT NULL auto_increment,
  `id_category` int(10) unsigned NOT NULL default '0',
  `id_user` int(10) unsigned NOT NULL default '0',
  `name` varchar(100) collate utf8_czech_ci NOT NULL default '',
  `url` varchar(100) collate utf8_czech_ci NOT NULL default '',
  `perex` text collate utf8_czech_ci,
  `text` text collate utf8_czech_ci,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `active` tinyint(4) default '0',
  `count` int(10) unsigned default '0',
  `karma` int(10) unsigned default '0',
  PRIMARY KEY  (`id_post`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `core_posts` VALUES ('7', '4', '0', 'dalsi', 'dalsi', 'pere', 'ex\r\n\r\n\r\nnejakej fakt dlouuuuuhatanskej text', '2007-08-09 20:36:00', '1', '0', '0');

CREATE TABLE `core_sites` (
  `id_site` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  `short_description` varchar(200) NOT NULL default '',
  `mod_rewrite` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_site`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `core_sites` VALUES ('1', 'Nors 4', 'Nors 4', 'klíè', 'desc', 'short desc', '1');

CREATE TABLE `core_users` (
  `id_user` int(11) unsigned NOT NULL auto_increment,
  `id_group` int(11) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  `fullname` varchar(100) NOT NULL default '',
  `phone` varchar(50) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `active` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `core_users` VALUES ('1', '1', 'dundee', '5280e84f8be43fcbc874a94aa5899d2a', 'Daniel Milde', '777609440', 'info@milde.cz', '2007-07-17 16:23:32', '1');



