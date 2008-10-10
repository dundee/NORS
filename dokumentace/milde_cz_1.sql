-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Počítač: hz-w4
-- Vygenerováno: Úterý 04. září 2007, 17:41
-- Verze MySQL: 4.0.27
-- Verze PHP: 5.2.4_pre200708051230-pl2-gentoo

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Databáze: `milde_cz_1`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabulky `nors4_categories`
-- 

CREATE TABLE `nors4_categories` (
  `id_category` int(10) unsigned NOT NULL auto_increment,
  `parent` int(10) unsigned NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `name_en` varchar(100) NOT NULL default '',
  `name_pl` varchar(100) NOT NULL default '',
  `url` varchar(100) NOT NULL default '',
  `location` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id_category`)
) TYPE=InnoDB AUTO_INCREMENT=17 ;

-- 
-- Vypisuji data pro tabulku `nors4_categories`
-- 

INSERT INTO `nors4_categories` (`id_category`, `parent`, `name`, `name_en`, `name_pl`, `url`, `location`) VALUES 
(6, 3, 'ObecnÃ© informace', '', '', 'Obecne-informace', '100'),
(7, 3, 'DisciplÃ­ny', '', '', 'Discipliny', '200'),
(8, 3, 'Jak zaÄÃ­t?', '', '', 'Jak-zacit-', '300'),
(9, 3, 'Kurzy freedivingu', '', '', 'Kurzy-freedivingu', '400'),
(10, 3, 'Co po kurzu?', '', '', 'Co-po-kurzu-', '500'),
(11, 3, 'Kam jezdÃ­me', '', '', 'Kam-jezdime', '600'),
(12, 3, 'Filmy, videa, fotogalerie', '', '', 'Filmy-videa-fotogalerie', '700'),
(13, 3, 'Forum', '', '', 'Forum', '800'),
(14, 3, 'PoslednÃ­ komentÃ¡Å™e', '', '', 'Posledni-komentare', '900'),
(15, 3, 'Shop', '', '', 'Shop', '1000'),
(16, 0, 'obecné', '', '', 'obecne', '100');

-- --------------------------------------------------------

-- 
-- Struktura tabulky `nors4_files`
-- 

CREATE TABLE `nors4_files` (
  `id_file` int(11) NOT NULL auto_increment,
  `id_post` int(10) unsigned NOT NULL default '0',
  `id_product` int(10) unsigned NOT NULL default '0',
  `id_text` int(10) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `label` varchar(100) NOT NULL default '',
  `type` enum('image','document','executable','unknown') NOT NULL default 'unknown',
  PRIMARY KEY  (`id_file`)
) TYPE=InnoDB AUTO_INCREMENT=2 ;

-- 
-- Vypisuji data pro tabulku `nors4_files`
-- 

INSERT INTO `nors4_files` (`id_file`, `id_post`, `id_product`, `id_text`, `name`, `label`, `type`) VALUES 
(1, 5, 0, 0, '2007-09-02_20-03-46.jpg', '', 'image');

-- --------------------------------------------------------

-- 
-- Struktura tabulky `nors4_groups`
-- 

CREATE TABLE `nors4_groups` (
  `id_group` int(10) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `post_list` enum('0','1') NOT NULL default '0',
  `post_edit` enum('0','1') NOT NULL default '0',
  `post_del` enum('0','1') NOT NULL default '0',
  `text_list` enum('0','1') NOT NULL default '0',
  `text_edit` enum('0','1') NOT NULL default '0',
  `text_del` enum('0','1') NOT NULL default '0',
  `category_list` enum('0','1') NOT NULL default '0',
  `category_edit` enum('0','1') NOT NULL default '0',
  `category_del` enum('0','1') NOT NULL default '0',
  `user_list` enum('0','1') NOT NULL default '0',
  `user_edit` enum('0','1') NOT NULL default '0',
  `user_del` enum('0','1') NOT NULL default '0',
  `group_list` enum('0','1') NOT NULL default '0',
  `group_edit` enum('0','1') NOT NULL default '0',
  `group_del` enum('0','1') NOT NULL default '0',
  `settings_list` enum('0','1') NOT NULL default '0',
  `settings_edit` enum('0','1') NOT NULL default '0',
  `settings_del` enum('0','1') NOT NULL default '0'
) TYPE=InnoDB;

-- 
-- Vypisuji data pro tabulku `nors4_groups`
-- 

INSERT INTO `nors4_groups` (`id_group`, `name`, `created`, `post_list`, `post_edit`, `post_del`, `text_list`, `text_edit`, `text_del`, `category_list`, `category_edit`, `category_del`, `user_list`, `user_edit`, `user_del`, `group_list`, `group_edit`, `group_del`, `settings_list`, `settings_edit`, `settings_del`) VALUES 
(1, 'Admin', '0000-00-00 00:00:00', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1'),
(2, 'Návštěvník', '0000-00-00 00:00:00', '1', '1', '0', '1', '1', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');

-- --------------------------------------------------------

-- 
-- Struktura tabulky `nors4_posts`
-- 

CREATE TABLE `nors4_posts` (
  `id_post` int(10) unsigned NOT NULL auto_increment,
  `id_category` int(10) unsigned NOT NULL default '0',
  `id_user` int(10) unsigned NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `name_en` varchar(100) NOT NULL default '',
  `name_pl` varchar(100) NOT NULL default '',
  `url` varchar(100) NOT NULL default '',
  `perex` text,
  `perex_en` text NOT NULL,
  `perex_pl` text NOT NULL,
  `text` text,
  `text_en` text NOT NULL,
  `text_pl` text NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `active` tinyint(4) default NULL,
  `count` int(10) unsigned default NULL,
  `karma` int(10) unsigned default NULL,
  PRIMARY KEY  (`id_post`),
  UNIQUE KEY `url` (`url`)
) TYPE=InnoDB AUTO_INCREMENT=7 ;

-- 
-- Vypisuji data pro tabulku `nors4_posts`
-- 

INSERT INTO `nors4_posts` (`id_post`, `id_category`, `id_user`, `name`, `name_en`, `name_pl`, `url`, `perex`, `perex_en`, `perex_pl`, `text`, `text_en`, `text_pl`, `date`, `active`, `count`, `karma`) VALUES 
(6, 0, 1, 'test', '', '', 'test', 'test', '', '', '<code>\r\nsdfsdfsdf\r\n</code>\r\n\r\n<br />\r\n\r\nsddfg\r\n', '', '', '2007-09-04 14:45:00', 1, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Struktura tabulky `nors4_sites`
-- 

CREATE TABLE `nors4_sites` (
  `id_site` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  `short_description` varchar(200) NOT NULL default '',
  `mod_rewrite` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_site`)
) TYPE=InnoDB AUTO_INCREMENT=2 ;

-- 
-- Vypisuji data pro tabulku `nors4_sites`
-- 

INSERT INTO `nors4_sites` (`id_site`, `name`, `title`, `keywords`, `description`, `short_description`, `mod_rewrite`) VALUES 
(1, 'Nors 4', 'Nors 4', 'nors', 'nors', '', 0);

-- --------------------------------------------------------

-- 
-- Struktura tabulky `nors4_texts`
-- 

CREATE TABLE `nors4_texts` (
  `id_text` int(10) unsigned NOT NULL auto_increment,
  `id_category` int(10) unsigned NOT NULL default '0',
  `id_user` int(10) unsigned NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `name_en` varchar(100) NOT NULL default '',
  `name_pl` varchar(100) NOT NULL default '',
  `url` varchar(100) NOT NULL default '',
  `text` text,
  `text_en` text NOT NULL,
  `text_pl` text NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `active` tinyint(4) default '0',
  `count` int(10) unsigned default '0',
  `karma` int(10) unsigned default '0',
  PRIMARY KEY  (`id_text`),
  UNIQUE KEY `url` (`url`)
) TYPE=InnoDB AUTO_INCREMENT=2 ;

-- 
-- Vypisuji data pro tabulku `nors4_texts`
-- 

INSERT INTO `nors4_texts` (`id_text`, `id_category`, `id_user`, `name`, `name_en`, `name_pl`, `url`, `text`, `text_en`, `text_pl`, `date`, `active`, `count`, `karma`) VALUES 
(1, 0, 1, 'Test web spuštěn', '', '', 'Test-web-spusten', 'Test web spuštěn', '', '', '2007-08-27 16:38:00', 1, 0, 0);

-- --------------------------------------------------------

-- 
-- Struktura tabulky `nors4_users`
-- 

CREATE TABLE `nors4_users` (
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
) TYPE=InnoDB AUTO_INCREMENT=3 ;

-- 
-- Vypisuji data pro tabulku `nors4_users`
-- 

INSERT INTO `nors4_users` (`id_user`, `id_group`, `name`, `password`, `fullname`, `phone`, `email`, `created`, `active`) VALUES 
(1, 1, 'dundee', '5280e84f8be43fcbc874a94aa5899d2a', '', '', '', '0000-00-00 00:00:00', 1),
(2, 2, 'test', '098f6bcd4621d373cade4e832627b4f6', 'test', '', '', '2007-09-04 14:57:00', 1);
