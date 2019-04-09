<?php
$sql = rex_sql::factory();
// Install database
$sql->setQuery("CREATE TABLE IF NOT EXISTS `". rex::getTablePrefix() ."d2u_staff` (
    `staff_id` int(10) unsigned NOT NULL auto_increment,
    `name` varchar(255) collate utf8mb4_unicode_ci default NULL,
    `picture` varchar(255) collate utf8mb4_unicode_ci default NULL,
    `online_status` varchar(10) collate utf8mb4_unicode_ci default NULL,
    `address_id` int(10) NULL default NULL,
    `article_id` int(10) NULL default NULL,
    `priority` int(11) NULL default NULL,
    PRIMARY KEY (`staff_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;");
$sql->setQuery("CREATE TABLE IF NOT EXISTS `". rex::getTablePrefix() ."d2u_staff_lang` (
    `staff_id` int(10) NOT NULL,
    `clang_id` int(10) NOT NULL,
    `area_of_responsibility` varchar(255) collate utf8mb4_unicode_ci default NULL,
    `position` varchar(255) collate utf8mb4_unicode_ci default NULL,
    `citation` text collate utf8mb4_unicode_ci default NULL,
	`translation_needs_update` varchar(6) collate utf8mb4_unicode_ci default NULL,
    PRIMARY KEY (`staff_id`, `clang_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;");