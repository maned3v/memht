<?php

echo "<div>Updating table `".$config_db['prefix']."_adv_banner`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_adv_banners` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_adv_banners` CHANGE  `type`  `type` ENUM(  'imd',  'imi' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_adv_banners` CHANGE  `catadv`  `catadv` ENUM(  'yes',  'no' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_adv_banners` CHANGE  `unlimited`  `unlimited` ENUM(  'yes',  'no' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'no';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_adv_banners` CHANGE  `status`  `status` ENUM(  'active',  'inactive' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'inactive';";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_adv_categories`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_adv_categories` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_adv_categories` CHANGE  `status`  `status` ENUM(  'active',  'inactive' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'inactive';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_articles_categories` DROP INDEX `name`;";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_articles`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_articles` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "UPDATE  `".$config_db['prefix']."_articles` SET  `prev` =  'inactive' WHERE  `prev` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_articles` CHANGE  `status`  `status` ENUM(  'published',  'deleted',  'revision',  'inactive',  'draft' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'inactive';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_articles` CHANGE  `prev`  `prev` ENUM(  'published',  'deleted',  'revision',  'inactive',  'draft' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_articles` DROP INDEX `name`;";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_articles` ADD INDEX (  `created` );";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_articles` ADD INDEX  `sse` (  `status` ,  `start` ,  `end` );";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_articles_rev`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_articles_rev` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "UPDATE  `".$config_db['prefix']."_articles_rev` SET  `prev` =  'inactive' WHERE  `prev` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_articles_rev` CHANGE  `status`  `status` ENUM(  'published',  'deleted',  'revision',  'inactive',  'draft' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'revision';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_articles_rev` CHANGE  `prev`  `prev` ENUM(  'published',  'deleted',  'revision',  'inactive',  'draft' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_banned`</div>\n";
$query = array();
$query[] = "ALTER TABLE  `".$config_db['prefix']."_banned` DROP INDEX `ip`;";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_banned` DROP INDEX `author`;";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_banned` ADD INDEX (  `iprange` );";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_banned` ADD INDEX (  `expire` );";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_banned` ADD INDEX  `iit` (  `iprange` ,  `ip` ,  `toip` );";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_bbcode_smiles`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_bbcode_smiles` SET  `code` = ':wink:' WHERE `name` = 'Wink';";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_blocks`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_blocks` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_blocks` CHANGE  `type`  `type` ENUM(  'content',  'file' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'content';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_blocks` CHANGE  `zone`  `zone` ENUM(  'nav',  'extra',  'sticker' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'nav';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_blocks` CHANGE  `status`  `status` ENUM(  'display',  'inactive' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'inactive';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_blocks` ADD INDEX (  `type` );";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_blog_posts`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_blog_posts` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "UPDATE  `".$config_db['prefix']."_blog_posts` SET  `prev` =  'inactive' WHERE  `prev` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_blog_posts` CHANGE  `status`  `status` ENUM(  'published',  'deleted',  'revision',  'inactive',  'draft' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'inactive';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_blog_posts` CHANGE  `prev`  `prev` ENUM(  'published',  'deleted',  'revision',  'inactive',  'draft' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_blog_posts` ADD INDEX (  `created` );";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_blog_posts` ADD INDEX  `sse` (  `status` ,  `start` ,  `end` );";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_blog_posts_rev`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_blog_posts_rev` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "UPDATE  `".$config_db['prefix']."_blog_posts_rev` SET  `prev` =  'inactive' WHERE  `prev` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_blog_posts_rev` CHANGE  `status`  `status` ENUM(  'published',  'deleted',  'revision',  'inactive',  'draft' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'revision';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_blog_posts_rev` CHANGE  `prev`  `prev` ENUM(  'published',  'deleted',  'revision',  'inactive',  'draft' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_comments`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_comments` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_comments` CHANGE  `status`  `status` ENUM(  'published',  'approved',  'inactive' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'inactive';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_comments` ADD INDEX (  `created` );";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_comments` ADD INDEX  `cis` (  `created` ,  `item` ,  `status` );";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_configuration`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_configuration` SET  `value` =  '%H:%M' WHERE  `label` =  'default_timestamp';";
$query[] = "UPDATE  `".$config_db['prefix']."_configuration` SET  `value` =  '%A %d %b %Y' WHERE  `label` =  'default_datestamp';";
$query[] = "UPDATE  `".$config_db['prefix']."_configuration` SET  `value` =  '5.0.0.9' WHERE  `label` =  'engine_version';";
$query[] = "INSERT INTO  `".$config_db['prefix']."_configuration` (`label` ,`value`) VALUES ('maintenance_whiteip',  '');";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_content`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_content` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_content` CHANGE  `type`  `type` ENUM(  'PLUGIN',  'STATIC',  'INTERNAL',  'REDIRECT' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'PLUGIN';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_content` CHANGE  `status`  `status` ENUM(  'active',  'acp',  'inactive' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'inactive';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_content` CHANGE  `acp`  `acp` ENUM(  'yes',  'no' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'no';";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_downloads`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_downloads` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_downloads` CHANGE  `status`  `status` ENUM(  'active',  'inactive' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'inactive';";
$query[] = "ALTER TABLE `".$config_db['prefix']."_downloads_categories` DROP INDEX `name`;";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_gallery`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_gallery` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_gallery` CHANGE  `status`  `status` ENUM(  'active',  'inactive' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'inactive';";
$query[] = "ALTER TABLE `".$config_db['prefix']."_gallery` DROP INDEX `name`;";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_links`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_links` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_links` CHANGE  `status`  `status` ENUM(  'active',  'inactive' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'inactive';";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_menu`</div>\n";
$query = array();
$query[] = "ALTER TABLE  `".$config_db['prefix']."_menu` CHANGE  `zone`  `zone` ENUM(  'nav',  'head' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'nav';";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_menu_acp`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_menu_acp` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_menu_acp` CHANGE  `menu`  `menu` ENUM(  'system',  'content',  'security' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'content';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_menu_acp` CHANGE  `status`  `status` ENUM(  'active',  'inactive' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'inactive';";
$query[] = "UPDATE  `".$config_db['prefix']."_menu_acp` SET  `uniqueid` =  'articles_main' WHERE  `id` = 12;";
$query[] = "UPDATE  `".$config_db['prefix']."_menu_acp` SET  `uniqueid` =  'blog_main' WHERE  `id` = 13;";
$query[] = "UPDATE  `".$config_db['prefix']."_menu_acp` SET  `uniqueid` =  'downloads_main' WHERE  `id` = 14;";
$query[] = "UPDATE  `".$config_db['prefix']."_menu_acp` SET  `uniqueid` =  'filesmgr_main' WHERE  `id` = 15;";
$query[] = "UPDATE  `".$config_db['prefix']."_menu_acp` SET  `uniqueid` =  'gallery_main' WHERE  `id` = 16;";
$query[] = "UPDATE  `".$config_db['prefix']."_menu_acp` SET  `uniqueid` =  'links_main' WHERE  `id` = 29;";
$query[] = "UPDATE  `".$config_db['prefix']."_menu_acp` SET  `uniqueid` =  'surveys_main' WHERE  `id` = 19;";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_surveys_questions`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_surveys_questions` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_surveys_questions` CHANGE  `type`  `type` ENUM(  'content',  'plugin' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_surveys_questions` CHANGE  `status`  `status` ENUM(  'active',  'inactive' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'inactive';";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_user`</div>\n";
$query = array();
$query[] = "UPDATE  `".$config_db['prefix']."_user` SET  `status` =  'inactive' WHERE  `status` =  'off';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_user` CHANGE  `status`  `status` ENUM(  'active',  'inactive',  'waiting',  'moderate' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'inactive';";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_user` ADD  `oauth_provider` ENUM(  'facebook',  'twitter',  'openid',  'google' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `code`;";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_user` ADD  `oauth_uid` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `oauth_provider`;";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_user` DROP INDEX `name`;";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_user` DROP INDEX `active`;";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_user` DROP INDEX `email`;";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_user` ADD INDEX (  `regdate` );";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_user` ADD INDEX (  `status` );";
$query[] = "ALTER TABLE  `".$config_db['prefix']."_user` ADD INDEX  `login` (  `user` ,  `pass` ,  `status` );";
foreach ($query as $q) mysqli_query($conn,$q);

echo "<div>Updating table `".$config_db['prefix']."_user_profile`</div>\n";
$query = array();
$query[] = "ALTER TABLE  `".$config_db['prefix']."_user_profile` CHANGE  `type`  `type` ENUM(  'text',  'textarea' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
foreach ($query as $q) mysqli_query($conn,$q);

?>