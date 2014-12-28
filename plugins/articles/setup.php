<?php

//========================================================================
// MemHT Portal
// 
// Copyright (C) 2008-2012 by Miltenovikj Manojlo <dev@miltenovik.com>
// http://www.memht.com
// 
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your opinion) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License along
// with this program; if not, see <http://www.gnu.org/licenses/> (GPLv2)
// or write to the Free Software Foundation, Inc., 51 Franklin Street,
// Fifth Floor, Boston, MA02110-1301, USA.
//========================================================================

/**
 * @author      Miltenovikj Manojlo <dev@miltenovik.com>
 * @author		Paulo Ferreira <sisnox@gmail.com>
 * @copyright	Copyright (C) 2008-2012 Miltenovikj Manojlo. All rights reserved.
 * @license     GNU/GPLv2 http://www.gnu.org/licenses/
 */

//Deny direct access
defined("_LOAD") or die("Access denied");

class Setup {
	static function Install() {
		global $Db,$User;
		
		if (!$User->IsAdmin()) die('Access denied!');
		
		//#__articles
		$Db->Query("CREATE TABLE IF NOT EXISTS `#__articles` (
                      `id` int(10) NOT NULL AUTO_INCREMENT,
                      `category` int(10) NOT NULL,
                      `title` varchar(255) NOT NULL,
                      `name` varchar(255) NOT NULL,
                      `author` int(10) NOT NULL,
                      `text` longtext NOT NULL,
                      `language` varchar(30) NOT NULL,
                      `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                      `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                      `start` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
                      `end` datetime NOT NULL DEFAULT '2199-01-01 00:00:00',
                      `options` longtext NOT NULL,
                      `usecomments` tinyint(1) NOT NULL DEFAULT '0',
                      `comments` int(10) NOT NULL DEFAULT '0',
                      `hits` int(10) NOT NULL DEFAULT '0',
                      `revisions` int(10) NOT NULL,
                      `status` enum('published','deleted','revision','inactive','draft') NOT NULL DEFAULT 'inactive',
                      `inhome` tinyint(1) NOT NULL DEFAULT '0',
                      `roles` text NOT NULL,
                      `prev` enum('published','deleted','revision','inactive','draft') NOT NULL,
                      PRIMARY KEY (`id`),
                      KEY `category` (`category`),
                      KEY `inhome` (`inhome`),
                      KEY `status` (`status`),
                      KEY `created` (`created`),
                      KEY `sse` (`status`,`start`,`end`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

		//#__articles_categories
		$Db->Query("CREATE TABLE IF NOT EXISTS `#__articles_categories` (
                      `id` int(10) NOT NULL AUTO_INCREMENT,
                      `section` int(10) NOT NULL,
                      `parent` int(10) NOT NULL DEFAULT '0',
                      `title` varchar(255) NOT NULL,
                      `name` varchar(255) NOT NULL,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `name` (`name`),
                      KEY `section` (`section`),
                      KEY `parent` (`parent`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
                    
		//#__articles_rev
		$Db->Query("CREATE TABLE IF NOT EXISTS `#__articles_rev` (
                      `id` int(10) NOT NULL AUTO_INCREMENT,
                      `category` int(10) NOT NULL,
                      `title` varchar(255) NOT NULL,
                      `name` varchar(255) NOT NULL,
                      `text` longtext NOT NULL,
                      `language` varchar(30) NOT NULL DEFAULT 'en',
                      `prevmod` datetime NOT NULL,
                      `start` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
                      `end` datetime NOT NULL DEFAULT '2199-01-01 00:00:00',
                      `options` longtext NOT NULL,
                      `usecomments` tinyint(1) NOT NULL DEFAULT '0',
                      `artid` int(10) NOT NULL DEFAULT '0',
                      `status` enum('published','deleted','revision','inactive','draft') NOT NULL DEFAULT 'revision',
                      `inhome` tinyint(1) NOT NULL DEFAULT '0',
                      `roles` text NOT NULL,
                      `prev` enum('published','deleted','revision','inactive','draft') NOT NULL,
                      PRIMARY KEY (`id`),
                      KEY `artid` (`artid`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
                    
		//#__articles_sections
		$Db->Query("CREATE TABLE IF NOT EXISTS `#__articles_sections` (
                      `id` int(10) NOT NULL AUTO_INCREMENT,
                      `title` varchar(255) NOT NULL,
                      `name` varchar(255) NOT NULL,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `name` (`name`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");                                        
		
		//ACP Menu link
		$Db->Query("INSERT INTO `#__menu_acp` (`title`, `uniqueid`, `url`, `icon`, `menu`, `submenu`, `quickicons`, `status`)
					VALUES ('Articles', 'articles_main', 'admin.php?cont=articles', 'write.png', 'content', 1, 1, 'active');");
		
		return _t("INSTALLED");
	}

	static function Uninstall() {
		global $Db,$User;
		
		if (!$User->IsAdmin()) die('Access denied!');        
        // articles
        $Db->Query("DROP TABLE #__articles");
        // articles categories
        $Db->Query("DROP TABLE #__articles_categories");
        // articles rev
        $Db->Query("DROP TABLE #__articles_rev");
        // articles sections
        $Db->Query("DROP TABLE #__articles_sections");
        // articles comments
        $Db->Query("DELETE FROM `#__comments` WHERE `controller`='articles'");
        // articles rate
		$Db->Query("DELETE FROM `#__ratings` WHERE `controller`='articles'");
		//ACP Menu link
		$Db->Query("DELETE FROM `#__menu_acp` WHERE `uniqueid`='articles_main'");
                
		return _t("UNINSTALLED");
	}
}
	
?>