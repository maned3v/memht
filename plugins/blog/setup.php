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
		
		//#__blog_posts
		$Db->Query("CREATE TABLE IF NOT EXISTS `#__blog_posts` (
                      `id` int(10) NOT NULL AUTO_INCREMENT,
                      `category` int(10) NOT NULL,
                      `title` varchar(255) NOT NULL,
                      `name` varchar(255) NOT NULL,
                      `author` int(10) NOT NULL,
                      `text` longtext NOT NULL,
                      `language` varchar(30) NOT NULL DEFAULT 'en',
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
                      `roles` text NOT NULL,
                      `prev` enum('published','deleted','revision','inactive','draft') NOT NULL,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `name` (`name`),
                      KEY `status` (`status`),
                      KEY `category` (`category`),
                      KEY `created` (`created`),
                      KEY `sse` (`status`,`start`,`end`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

		//#__blog_categories
		$Db->Query("CREATE TABLE IF NOT EXISTS `#__blog_categories` (
                      `id` int(10) NOT NULL AUTO_INCREMENT,
                      `title` varchar(255) NOT NULL,
                      `name` varchar(255) NOT NULL,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `name` (`name`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
                    
		//#__blog_posts_rev
		$Db->Query("CREATE TABLE IF NOT EXISTS `#__blog_posts_rev` (
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
                      `postid` int(10) NOT NULL DEFAULT '0',
                      `status` enum('published','deleted','revision','inactive','draft') NOT NULL DEFAULT 'revision',
                      `roles` text NOT NULL,
                      `prev` enum('published','deleted','revision','inactive','draft') NOT NULL,
                      PRIMARY KEY (`id`),
                      KEY `postid` (`postid`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");                                         
		
		//ACP Menu link
		$Db->Query("INSERT INTO `#__menu_acp` (`title`, `uniqueid`, `url`, `icon`, `menu`, `submenu`, `quickicons`, `status`)
					VALUES ('Blog', 'blog_main', 'admin.php?cont=blog', 'write.png', 'content', 1, 1, 'active');");
		
		return _t("INSTALLED");
	}

	static function Uninstall() {
		global $Db,$User;
		
		if (!$User->IsAdmin()) die('Access denied!');        
        // blog posts
        $Db->Query("DROP TABLE #__blog_posts");
        // blog categories
        $Db->Query("DROP TABLE #__blog_categories");
        // blog posts rev
        $Db->Query("DROP TABLE #__blog_posts_rev");
        // blog comments
        $Db->Query("DELETE FROM `#__comments` WHERE `controller`='blog'");
        // blog rate
		$Db->Query("DELETE FROM `#__ratings` WHERE `controller`='blog'");
		//ACP Menu link
		$Db->Query("DELETE FROM `#__menu_acp` WHERE `uniqueid`='blog_main'");
                
		return _t("UNINSTALLED");
	}
}
	
?>