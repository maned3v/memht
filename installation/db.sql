SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES 'utf8';
SET character_set_server = 'utf8';

CREATE TABLE IF NOT EXISTS `#__adv_banners` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL,
  `type` enum('imd','imi') NOT NULL,
  `catadv` enum('yes','no') NOT NULL DEFAULT 'no',
  `name` varchar(255) NOT NULL,
  `label` varchar(30) NOT NULL,
  `impressions` int(10) NOT NULL,
  `clicks` int(10) NOT NULL,
  `start` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `end` datetime NOT NULL DEFAULT '2199-01-01 00:00:00',
  `todo_imp` int(10) NOT NULL,
  `todo_clicks` int(10) NOT NULL,
  `unlimited` enum('yes','no') NOT NULL DEFAULT 'no',
  `img_path` varchar(255) NOT NULL,
  `img_url` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `options` longtext NOT NULL,
  `roles` text NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`),
  KEY `label` (`label`),
  KEY `status` (`status`),
  KEY `start` (`start`),
  KEY `end` (`end`),
  KEY `unlimited` (`unlimited`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__adv_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `label` varchar(30) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__adv_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__articles` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__articles` (`id`, `category`, `title`, `name`, `author`, `text`, `language`, `created`, `modified`, `start`, `end`, `options`, `usecomments`, `comments`, `hits`, `revisions`, `status`, `inhome`, `roles`, `prev`) VALUES
(1, 6, 'Lorem ipsum dolor sit amet', 'lorem-ipsum-dolor-sit-amet', 1, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer non  malesuada metus. In viverra, tellus ac facilisis vulputate, lectus dui  porttitor dolor, sed molestie lorem erat at urna. Sed ut massa sit amet  lorem scelerisque pharetra. Nulla condimentum diam sit amet risus  tincidunt sit amet gravida enim elementum. Aenean at fermentum enim.  Fusce vitae tortor id lectus consequat eleifend ut sed turpis. Duis  convallis, quam in viverra accumsan, massa dolor cursus quam, id  molestie risus enim rutrum neque. Cras erat neque, dictum eu egestas a,  laoreet sit amet dolor. Fusce rhoncus viverra dolor in sollicitudin.  Duis vitae pharetra orci. Pellentesque arcu tellus, commodo sit amet  luctus eget, pulvinar nec magna. Phasellus aliquam fringilla ligula at  vulputate. Vivamus hendrerit dui ac lectus blandit vel pretium urna  accumsan. Sed fermentum, est et dictum scelerisque, ligula risus  eleifend sem, at interdum quam nisl ac tellus. Nunc quis rutrum orci.  Sed faucibus, augue a vehicula fermentum, sem ipsum mattis augue, ut  malesuada eros dolor non velit. Proin diam metus, aliquam ac  sollicitudin non, pretium quis nisl.</p>\r\n<p>[[READMORE]]</p>\r\n<p>Sed a risus in tortor sollicitudin malesuada quis eget massa. Donec  vulputate leo nec leo blandit nec venenatis nisi consequat. Nullam  libero massa, porta at sodales eget, tincidunt vel neque. Nulla eu metus  eu nisl placerat suscipit ut in dolor. Mauris vestibulum iaculis  elementum. Pellentesque semper, metus in pharetra porttitor, massa urna  fringilla leo, ac ultricies metus libero vitae dui. Aenean at tellus  lectus. Nulla dictum odio dictum tellus placerat auctor. Nulla aliquet  elementum egestas. Nullam eget felis in arcu tristique fringilla. In  viverra neque non arcu tincidunt lacinia.</p>\r\nMauris bibendum semper sem, ac luctus nisi tempor in. Sed gravida,  sapien ac convallis vulputate, nisl mauris tempus neque, quis fermentum  massa dolor sed diam. Cras molestie elementum posuere. Morbi tincidunt  dui quis ipsum pellentesque consectetur. Aenean eu odio quis eros  condimentum aliquet. In aliquam mi diam, sit amet pulvinar ante. Aliquam  magna est, consectetur id porttitor gravida, consectetur non magna.  Pellentesque tempus est sit amet ante pretium consectetur. In lobortis  convallis odio at sodales. Maecenas facilisis lacus at purus pulvinar  tincidunt. Nulla est nunc, pulvinar et volutpat et, interdum at ante.  Aenean risus risus, venenatis ac pharetra a, mattis id ante.', 'en', '2010-06-14 12:36:38', '2010-06-15 11:24:55', '2001-01-01 00:00:00', '2199-01-01 00:00:00', 'a:2:{s:8:"stickers";a:2:{s:11:"thumb_index";s:21:"assets/images/bmw.jpg";s:10:"thumb_view";s:25:"assets/images/bmw_big.jpg";}s:4:"meta";a:2:{s:4:"desc";s:26:"Lorem ipsum dolor sit amet";s:3:"key";s:26:"Lorem,ipsum,dolor,sit,amet";}}', 1, 2, 25, 6, 'published', 1, 'a:0:{}', ''),
(2, 10, 'Quisque rhoncus scelerisque erat', 'quisque-rhoncus-scelerisque-erat', 1, '<p>Quisque rhoncus scelerisque erat, quis malesuada justo pharetra  lobortis. Nulla et urna quam, id elementum magna. Nunc lobortis urna id  nulla vestibulum consequat. Aenean accumsan, justo vitae aliquam  facilisis, purus dolor mattis velit, quis feugiat justo sem venenatis  est. Fusce dictum dolor pulvinar tellus molestie laoreet consectetur  felis bibendum. Nam rutrum mauris nec lorem rhoncus eu feugiat nunc  consequat. Phasellus egestas dictum blandit. Morbi at eros odio, a  venenatis turpis. Duis imperdiet elit id dolor accumsan laoreet. Cras  dolor tortor, sodales et rutrum eget, mollis sed est.</p>\r\n<p>[[READMORE]]</p>\r\n<p>Suspendisse sed eros nisl. Duis ultricies volutpat auctor. Suspendisse  vestibulum faucibus ligula, eu fermentum metus pellentesque ac. Nullam  quis est ipsum. Nulla sollicitudin mollis justo, in scelerisque eros  condimentum vitae. Vestibulum ante tellus, convallis sit amet semper ac,  feugiat ac orci. Aenean tempus placerat odio molestie vestibulum.  Quisque placerat ligula id turpis mattis vitae suscipit quam tincidunt.  Donec urna lectus, aliquam ut dapibus a, suscipit sed ante. Nam augue  orci, tincidunt quis faucibus sed, consectetur id mi. Pellentesque  habitant morbi tristique senectus et netus et malesuada fames ac turpis  egestas. Nulla velit mauris, condimentum quis vulputate eu, viverra at  massa. Duis egestas diam ac massa condimentum sagittis. Integer nisi  lacus, pretium in mollis eget, sodales sit amet lorem. Vivamus  dignissim, metus nec porttitor posuere, ligula odio lacinia sapien,  tincidunt sagittis dui diam vitae urna. Mauris pellentesque dui ut diam  dictum lacinia. Fusce sollicitudin, tellus faucibus pulvinar viverra,  leo purus elementum quam, nec interdum nulla metus ut leo.</p>', 'en', '2010-06-14 12:52:13', '2010-06-14 13:02:12', '2001-01-01 00:00:00', '2199-01-01 00:00:00', 'a:2:{s:8:"stickers";a:2:{s:11:"thumb_index";s:26:"assets/images/mercedes.jpg";s:10:"thumb_view";s:30:"assets/images/mercedes_big.jpg";}s:4:"meta";a:2:{s:4:"desc";s:32:"Quisque rhoncus scelerisque erat";s:3:"key";s:32:"Quisque,rhoncus,scelerisque,erat";}}', 1, 1, 10, 1, 'published', 1, 'a:0:{}', ''),
(3, 2, 'Aenean imperdiet sem sit amet mauris', 'aenean-imperdiet', 1, 'Aenean imperdiet sem sit amet mauris condimentum ornare. Sed viverra  pulvinar aliquet. Aenean fermentum libero quis turpis vulputate  pharetra. Nunc mollis dictum est, id consectetur odio imperdiet quis.  Nulla facilisi. Nullam sit amet ligula ac nunc eleifend interdum. Cras  porttitor neque quis mi molestie aliquam. Nulla facilisi. Nullam ornare  vulputate nulla non ultrices. Nam fringilla gravida pharetra. Fusce diam  augue, hendrerit eget semper a, elementum a leo. Nulla venenatis metus  vitae urna porta condimentum. Cras vitae sem dolor, a dignissim lacus.', 'en', '2010-06-14 13:03:52', '2010-06-14 13:03:52', '2001-01-01 00:00:00', '2199-01-01 00:00:00', 'a:2:{s:8:"stickers";a:2:{s:11:"thumb_index";s:20:"assets/images/am.jpg";s:10:"thumb_view";s:24:"assets/images/am_big.jpg";}s:4:"meta";a:2:{s:4:"desc";s:36:"Aenean imperdiet sem sit amet mauris";s:3:"key";s:36:"Aenean,imperdiet,sem,sit,amet,mauris";}}', 0, 0, 2, 0, 'published', 1, 'a:0:{}', ''),
(4, 1, 'Test article', 'test-article', 1, 'This article has been sent to the trash can', 'en', '2010-06-14 13:05:28', '2010-06-14 13:05:28', '2001-01-01 00:00:00', '2199-01-01 00:00:00', 'a:0:{}', 0, 0, 0, 0, 'deleted', 0, 'a:0:{}', 'draft');

CREATE TABLE IF NOT EXISTS `#__articles_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `section` int(10) NOT NULL,
  `parent` int(10) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `section` (`section`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__articles_categories` (`id`, `section`, `parent`, `title`, `name`) VALUES
(1, 2, 0, 'Mobiles', 'mobiles'),
(2, 2, 0, 'Notebooks', 'notebooks'),
(4, 1, 0, 'Technology', 'technology'),
(5, 1, 4, 'Electronics', 'electronics'),
(6, 1, 4, 'Computers', 'computers'),
(10, 2, 1, 'Windows Mobile', 'winmob'),
(11, 2, 1, 'Android', 'android');

CREATE TABLE IF NOT EXISTS `#__articles_rev` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__articles_sections` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__articles_sections` (`id`, `title`, `name`) VALUES
(1, 'News', 'news'),
(2, 'Reviews', 'reviews'),
(3, 'Tutorials', 'tutorials'),
(4, 'Various', 'various');

CREATE TABLE IF NOT EXISTS `#__banned` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `ip` varbinary(39) NOT NULL,
  `toip` varbinary(39) NOT NULL,
  `iprange` tinyint(1) NOT NULL,
  `expire` datetime NOT NULL,
  `reason` text NOT NULL,
  `author` int(10) NOT NULL,
  `bandate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `iit` (`iprange`,`ip`,`toip`),
  KEY `expire` (`expire`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__bbcode` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `bbcode` text NOT NULL,
  `html` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__bbcode` (`id`, `name`, `bbcode`, `html`) VALUES
(1, 'Bold', '[b]{TEXT}[/b]', '<b>{1}</b>'),
(2, 'Italic', '[i]{TEXT}[/i]', '<i>{1}</i>'),
(3, 'Underlined', '[u]{TEXT}[/u]', '<u>{1}</u>'),
(4, 'Strike text', '[s]{TEXT}[/s]', '<span style="text-decoration:line-through;">{1}</span>'),
(5, 'Text size', '[size={NUMBER}]{TEXT}[/size]', '<span style="font-size:{1}px;">{2}</span>'),
(6, 'Text color', '[color={COLOR}]{TEXT}[/color]', '<span style="color:{1};">{2}</span>'),
(7, 'Background color', '[background={COLOR}]{TEXT}[/background]', '<span style="background-color:{1};">{2}</span>'),
(8, 'Blinking text', '[blink]{TEXT}[/blink]', '<span style="text-decoration:blink;">{1}</span>'),
(9, 'Horizontal line', '[hr][/hr]', '<hr />'),
(10, 'Tabulation', '[tab={NUMBER}]{TEXT}[/tab]', '<div style="margin-left:{1}px;">{2}</div>'),
(11, 'Left alignment', '[left]{TEXT}[/left]', '<div style="text-align:left;">{1}</div>'),
(12, 'Center alignment', '[center]{TEXT}[/center]', '<div style="text-align:center;">{1}</div>'),
(13, 'Right alignment', '[right]{TEXT}[/right]', '<div style="text-align:right;">{1}</div>'),
(14, 'Justify text', '[justify]{TEXT}[/justify]', '<div style="text-align:justify;">{1}</div>'),
(15, 'Link', '[url]{TEXT}[/url]', '<a href="{1}">{1}</a>'),
(16, 'Link with text', '[url={TEXT}]{TEXT}[/url]', '<a href="{1}">{2}</a>'),
(17, 'Image', '[img]{TEXT}[/img]', '<img src="{1}" alt="" />'),
(18, 'Image with dim.', '[img={NUMBER}x{NUMBER}]{URL}[/img]', '<img width="{1}" height="{2}" src="{3}" alt="" />'),
(19, 'Spoiler', '[spoiler]{TEXT}[/spoiler]', '<span style="background-color:#000000">{1}</span>'),
(20, 'YouTube', '[youtube]{SIMPLETEXT}[/youtube]', '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/{1}"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/{1}" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>'),
(21, 'Google video', '[googlevid]{NUMBER}[/googlevid]', '<embed style="width:400px; height:326px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId={1}&hl=en" flashvars=""></embed>');

CREATE TABLE IF NOT EXISTS `#__bbcode_smiles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `code` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__bbcode_smiles` (`id`, `name`, `image`, `code`) VALUES
(1, 'Smile', 'smile_smile.gif', ':)'),
(2, 'BigGrin', 'smile_biggrin.gif', ':D'),
(3, 'Sad', 'smile_sad.gif', ':('),
(4, 'Wink', 'smile_wink.gif', ':wink:'),
(5, 'Razz', 'smile_razz.gif', ':P'),
(6, 'Evil', 'smile_evil.gif', ':evil:'),
(7, 'Surprised', 'smile_eek.gif', ':O'),
(8, 'Ops', 'smile_redface.gif', ':ops:'),
(9, 'Boss', 'smile_boss.gif', ':boss:'),
(10, ':|', 'smile_wtf.gif', ':|'),
(11, 'InLove', 'smile_inlove.gif', ':inlove:'),
(12, 'Kiss', 'smile_kiss.gif', ':kiss:'),
(13, 'Nerd', 'smile_nerd.gif', ':nerd:'),
(14, 'Angry', 'smile_angry.gif', ':angry:'),
(15, 'Bad', 'smile_bad.gif', ':bad:'),
(16, 'NoComment', 'smile_nocomment.gif', ':nc:'),
(17, 'Sick', 'smile_sick.gif', ':sick:'),
(18, 'Uhm', 'smile_uhm.gif', ':uhm:'),
(19, 'Cowboy', 'smile_cowboy.gif', ':cowboy:'),
(20, 'Crazy', 'smile_crazy.gif', ':crazy:'),
(21, 'Francais', 'smile_francais.gif', ':france:'),
(22, 'Ghgh', 'smile_ghgh.gif', ':gh:'),
(23, 'Gift', 'smile_gift.gif', ':gift:'),
(24, 'Horns', 'smile_horns.gif', ':horns:'),
(25, 'King', 'smile_king.gif', ':king:'),
(26, 'Money', 'smile_money.gif', ':money:'),
(27, 'OMG', 'smile_omg.gif', ':omg:'),
(28, 'Oriental', 'smile_orient.gif', ':orient:'),
(29, 'Santa', 'smile_santa.gif', ':santa:'),
(30, 'Smoke', 'smile_smoke.gif', ':smoke:'),
(31, 'Yuhu', 'smile_yuhu.gif', ':yuhu:');

CREATE TABLE IF NOT EXISTS `#__blocks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `showtitle` tinyint(1) NOT NULL DEFAULT '1',
  `type` enum('content','file') NOT NULL DEFAULT 'content',
  `zone` enum('nav','extra','sticker') NOT NULL DEFAULT 'nav',
  `position` tinyint(2) NOT NULL,
  `file` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `options` longtext NOT NULL,
  `roles` text NOT NULL,
  `start` datetime NOT NULL DEFAULT '2001-01-01 00:00:00',
  `end` datetime NOT NULL DEFAULT '2199-01-01 00:00:00',
  `status` enum('display','inactive') NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `zone` (`zone`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__blocks` (`id`, `title`, `label`, `showtitle`, `type`, `zone`, `position`, `file`, `content`, `options`, `roles`, `start`, `end`, `status`) VALUES
(2, 'Static block', '', 1, 'content', 'extra', 4, '', 'Nel mezzo del <strong>cammin</strong> di <em>nostra</em> vita <span style="text-decoration: line-through;">mi ritrovai </span>per <span style="text-decoration: underline;">una selva</span> oscura, 3 ch&eacute; la diritta via era smarrita. Ahi quanto a dir qual era &egrave; cosa dura esta selva selvaggia e aspra e forte 6 che nel pensier rinova la paura! Tant&rsquo;&egrave; amara che poco &egrave; pi&ugrave; morte; ma per trattar del ben ch&rsquo;i&rsquo; vi trovai, 9 dir&ograve; de l&rsquo;altre cose ch&rsquo;i&rsquo; v&rsquo;ho scorte.', 'a:0:{}', 'a:0:{}', '2001-01-01 00:00:00', '2199-01-01 00:00:00', 'display'),
(4, 'Blog posts', '', 1, 'file', 'extra', 2, 'blog_posts', '', '', '', '2001-01-01 00:00:00', '2199-01-01 00:00:00', 'display'),
(5, 'Blog categories', '', 1, 'file', 'extra', 0, 'blog_categories', '', '', '', '2001-01-01 00:00:00', '2199-01-01 00:00:00', 'display'),
(6, 'Blog archive', '', 1, 'file', 'extra', 1, 'blog_archive', '', '', '', '2001-01-01 00:00:00', '2199-01-01 00:00:00', 'display'),
(26, 'Sections', '', 1, 'file', 'nav', 0, 'articles_sections', '', '', 'a:0:{}', '2001-01-01 00:00:00', '2199-01-01 00:00:00', 'display'),
(27, 'Archive', '', 1, 'file', 'nav', 1, 'articles_archive', '', '', 'a:0:{}', '2001-01-01 00:00:00', '2199-01-01 00:00:00', 'display'),
(28, 'Recent articles', '', 1, 'file', 'nav', 2, 'articles', '', '', 'a:0:{}', '2001-01-01 00:00:00', '2199-01-01 00:00:00', 'display'),
(29, 'Lorem ipsum', '', 1, 'content', 'extra', 3, '', 'This block will be shown in Articles and Blog only<br />', 'a:1:{s:13:"showincontent";a:2:{i:0;s:8:"articles";i:1;s:4:"blog";}}', 'a:0:{}', '2001-01-01 00:00:00', '2199-01-01 00:00:00', 'display'),
(25, 'Online visitors', '', 1, 'file', 'nav', 3, 'online', '', '', 'a:0:{}', '2001-01-01 00:00:00', '2199-01-01 00:00:00', 'display');

CREATE TABLE IF NOT EXISTS `#__blog_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__blog_categories` (`id`, `title`, `name`) VALUES
(1, 'My two cents', 'my-two-cents'),
(2, 'Development', 'development'),
(3, 'MemHT Portal', 'memht-portal');

CREATE TABLE IF NOT EXISTS `#__blog_posts` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__blog_posts` (`id`, `category`, `title`, `name`, `author`, `text`, `language`, `created`, `modified`, `start`, `end`, `options`, `usecomments`, `comments`, `hits`, `revisions`, `status`, `roles`, `prev`) VALUES
(1, 1, 'Lorem ipsum', 'lorem-ipsum', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ultrices  iaculis purus at tempus. Proin congue diam at mi placerat non pulvinar  risus luctus. Cras venenatis porta elit quis pharetra. Aliquam lacus  enim, bibendum fringilla viverra ut, imperdiet ac tellus. Suspendisse  molestie, tellus ac posuere pharetra, massa tellus lobortis felis, id  ornare lorem eros sed tellus. Aenean varius magna ac massa laoreet quis  venenatis purus feugiat. Duis rhoncus orci id eros tincidunt non  vestibulum tellus vulputate. Sed feugiat est pretium nisl fermentum  elementum. Aenean magna purus, malesuada non lacinia a, pellentesque nec  ipsum. Vivamus non massa tortor, vitae sagittis nisi. Pellentesque ut  ipsum libero. Fusce faucibus condimentum libero ac posuere. Nunc sem  eros, lacinia in dapibus ac, interdum vitae nulla. Ut a est turpis.  Curabitur blandit nisi mattis odio varius posuere. Duis rhoncus lacus in  leo molestie nec semper elit sodales. Nam mollis interdum rutrum.  Vestibulum laoreet sollicitudin mauris, sit amet faucibus risus  ullamcorper eget. Vestibulum eget diam sapien.', 'en', '2010-06-14 14:49:07', '2010-06-14 14:49:07', '2001-01-01 00:00:00', '2199-01-01 00:00:00', 'a:2:{s:8:"stickers";a:2:{s:11:"thumb_index";s:21:"assets/images/bmw.jpg";s:10:"thumb_view";s:25:"assets/images/bmw_big.jpg";}s:4:"meta";a:2:{s:4:"desc";s:26:"Lorem ipsum dolor sit amet";s:3:"key";s:26:"Lorem,ipsum,dolor,sit,amet";}}', 1, 0, 3, 0, 'published', 'a:0:{}', '');

CREATE TABLE IF NOT EXISTS `#__blog_posts_rev` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `controller` varchar(255) NOT NULL,
  `item` int(10) NOT NULL,
  `author` int(10) NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `author_email` varchar(255) NOT NULL,
  `author_site` varchar(255) NOT NULL,
  `author_ip` varbinary(32) NOT NULL,
  `created` datetime NOT NULL,
  `text` text NOT NULL,
  `points` int(10) NOT NULL DEFAULT '0',
  `status` enum('published','approved','inactive') NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `controller` (`controller`),
  KEY `item` (`item`),
  KEY `created` (`created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__comments` (`id`, `controller`, `item`, `author`, `author_name`, `author_email`, `author_site`, `author_ip`, `created`, `text`, `points`, `status`) VALUES
(1, 'articles', 1, 1, '', '', '', '', '2010-06-14 12:40:35', 'Integer et eros [b]vulputate metus[/b] luctus convallis. :) Vivamus congue, odio sed ultricies aliquam, tortor [i]purus luctus[/i] ipsum, sed lobortis nibh risus ac urna. Suspendisse porttitor erat vitae purus placerat aliquam. [color=#F90]Sed bibendum sapien[/color] nec augue fringilla vitae laoreet diam consectetur.', 0, 'published'),
(2, 'articles', 2, 1, '', '', '', '', '2010-06-14 12:53:12', 'ras ornare [s]pulvinar enim[/s]. Nulla posuere suscipit ante at lobortis. Phasellus ipsum sapien, [url=http://www.memht.com]fermentum tempor ultricies[/url] in, semper in est. Suspendisse :( potenti. Sed imperdiet erat sit amet arcu condimentum sed volutpat justo aliquet.', 0, 'published'),
(3, 'articles', 1, 1, '', '', '', '', '2010-06-15 11:11:59', '[youtube]3YxaaGgTQYM[/youtube]', 0, 'published');

CREATE TABLE IF NOT EXISTS `#__configuration` (
  `label` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`label`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__configuration` (`label`, `value`) VALUES
('site_name', 'MemHT 5'),
('meta_description', 'MemHT Portal 5'),
('meta_keywords', 'MemHT, Portal'),
('nice_seo_urls', '0'),
('default_template', 'memht'),
('dbserver_timezone', '+1:00'),
('node', 'cont'),
('default_timestamp', '%H:%M'),
('captcha', '1'),
('default_home', 'articles'),
('default_datestamp', '%A %d %b %Y'),
('cronjobs', '0'),
('maintenance', '0'),
('maintenance_message', 'The site is under maintenance.'),
('texteditor', '1'),
('output_compression', '0'),
('nice_seo_urls_separator', '/'),
('comments', '1'),
('default_language', 'en'),
('lock_template', '1'),
('login_cookie_expire', '7'),
('copyright', 'Copyright © 2012 by Your Name'),
('breadcrumb_separator', ' / '),
('site_title_separator', '|'),
('site_title_order', 'ASC'),
('uniqueid', '5754163f11'),
('email_mailer', 'mail'),
('email_smtp_host', ''),
('email_smtp_user', ''),
('email_smtp_pass', ''),
('email_charset', 'utf-8'),
('email_type', 'text'),
('cnt_email_or_notify', 'notification'),
('engine_version', '5.0.1.0'),
('captcha_for_users', '1'),
('user_signup', '1'),
('user_signup_moderate', '0'),
('user_signup_invite', '0'),
('user_signup_confirm', '1'),
('maintenance_last', '2010-01-01 10:00:00'),
('maintenance_whiteip', ''),
('maintenance_pause', '10'),
('terms_of_use_dialog', '1'),
('terms_of_use_notice', 'By accessing or using this website or any applications or services made available by it, you agree to be bound by the terms of use (“Terms”) available on the following page. If you do not agree to these Terms, please do not use the site and exit now.'),
('terms_of_use_controller', 'terms-of-use'),
('statistics', '1'),
('statistics_full', '1'),
('nice_seo_urls_suffix', '');

CREATE TABLE IF NOT EXISTS `#__content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `type` enum('PLUGIN','STATIC','INTERNAL','REDIRECT') NOT NULL DEFAULT 'PLUGIN',
  `showtitle` tinyint(1) NOT NULL DEFAULT '1',
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `content` longtext NOT NULL,
  `cont_before` longtext NOT NULL,
  `cont_after` longtext NOT NULL,
  `options` longtext NOT NULL,
  `roles` text NOT NULL,
  `sitemap` tinyint(1) NOT NULL DEFAULT '1',
  `rss` tinyint(1) NOT NULL DEFAULT '0',
  `searchable` tinyint(1) NOT NULL DEFAULT '0',
  `acp` enum('yes','no') NOT NULL DEFAULT 'no',
  `status` enum('active','acp','inactive') NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__content` (`id`, `title`, `name`, `controller`, `type`, `showtitle`, `meta_keywords`, `meta_description`, `content`, `cont_before`, `cont_after`, `options`, `roles`, `sitemap`, `rss`, `searchable`, `acp`, `status`) VALUES
(1, 'Blog', 'blog', 'blog', 'PLUGIN', 1, 'blog,key', 'Blog desc', '', '', '', 'a:1:{s:6:"layout";a:2:{s:3:"nav";i:1;s:5:"extra";i:1;}}', 'a:0:{}', 1, 1, 1, 'yes', 'active'),
(3, 'My static page', 'my-static-page', '', 'STATIC', 1, 'my,keywords', 'My description', 'Nel mezzo del cammin di nostra vitami ritrovai pe<span style="text-decoration: underline;">r una selva oscura,ch&eacute; la dirit</span>ta via era smarrita. Ah qua<span style="#ff0000;">nto a dir qu</span>al era &egrave; cosa dura,esta <strong>selva selvaggia e aspra e </strong>forte,che nel<span style="background-color: #ffff00;"><span style="#ff0000;"> pensier rin</span></span>nova la paura! Tant&rsquo;&egrave; amara che poco &egrave; pi&ugrave; morte;ma per trattar del ben <span style="text-decoration: line-through;">ch&rsquo;i&rsquo; vi trovaidir&ograve; de l&rsquo;</span>altre cose ch&rsquo;i&rsquo; v&rsquo;ho scorte.<br />', 'Before', 'After', 'a:1:{s:6:"layout";a:2:{s:3:"nav";i:1;s:5:"extra";i:0;}}', 'a:4:{i:0;s:10:"REGISTERED";i:1;s:5:"ADMIN";i:2;s:6:"EDITOR";i:3;s:9:"MODERATOR";}', 1, 0, 1, 'no', 'active'),
(4, 'Captcha', 'captcha', 'captcha', 'INTERNAL', 1, '', '', '', '', '', '', '', 0, 0, 0, 'no', 'active'),
(7, 'User', 'user', 'user', 'PLUGIN', 1, '', '', '', '', '', 'a:1:{s:6:"layout";a:2:{s:3:"nav";i:1;s:5:"extra";i:1;}}', '', 0, 0, 0, 'yes', 'active'),
(10, 'Redirect', 'redirect', 'redirect', 'REDIRECT', 0, '', '', 'http://www.memht.com', '', '', '', '', 0, 0, 0, 'no', 'active'),
(11, 'Contact us', 'contact', 'contact', 'PLUGIN', 1, '', '', '', '', '', 'a:1:{s:6:"layout";a:2:{s:3:"nav";i:1;s:5:"extra";i:1;}}', 'a:0:{}', 0, 0, 0, 'yes', 'active'),
(12, 'Dashboard', 'dashboard', 'dashboard', 'PLUGIN', 1, '', '', '', '', '', '', '', 0, 0, 0, 'yes', 'acp'),
(16, 'Sitemap', 'sitemap', 'sitemap', 'PLUGIN', 1, '', '', '', '', '', '', '', 0, 0, 0, 'no', 'active'),
(18, 'Rss', 'rss', 'rss', 'PLUGIN', 1, '', '', '', '', '', 'a:1:{s:6:"layout";a:2:{s:3:"nav";i:1;s:5:"extra";i:1;}}', 'a:0:{}', 1, 0, 0, 'no', 'active'),
(19, 'Configuration', 'configuration', 'configuration', 'PLUGIN', 1, '', '', '', '', '', '', '', 0, 0, 0, 'yes', 'acp'),
(20, 'Articles', 'articles', 'articles', 'PLUGIN', 1, '', '', '', '', '', 'a:1:{s:6:"layout";a:2:{s:3:"nav";i:1;s:5:"extra";i:1;}}', 'a:0:{}', 1, 1, 1, 'yes', 'active'),
(21, 'Search', 'search', 'search', 'PLUGIN', 1, '', '', '', '', '', '', '', 0, 0, 0, 'no', 'active'),
(22, 'Blocks', 'blocks', 'blocks', 'PLUGIN', 1, '', '', '', '', '', '', '', 0, 0, 0, 'yes', 'acp'),
(23, 'Comments', 'comments', 'comments', 'PLUGIN', 1, '', '', '', '', '', '', '', 0, 0, 0, 'yes', 'acp'),
(26, 'Internal', 'internal', 'internal', 'INTERNAL', 1, '', '', '', '', '', '', '', 0, 0, 0, 'yes', 'acp'),
(29, 'Plugins', 'plugins', 'plugins', 'PLUGIN', 1, '', '', '', '', '', '', '', 0, 0, 0, 'yes', 'acp'),
(30, 'Security', 'security', 'security', 'PLUGIN', 1, '', '', '', '', '', '', '', 0, 0, 0, 'yes', 'acp'),
(31, 'Statistics', 'statistics', 'statistics', 'PLUGIN', 1, '', '', '', '', '', '', '', 0, 0, 0, 'yes', 'acp'),
(32, 'Terms of use', 'terms-of-use', '', 'STATIC', 1, '', '', 'This is a placeholder for the terms of use of your website. You can disable the terms of use dialog in the site configuration.', '', '', 'a:1:{s:6:"layout";a:2:{s:3:"nav";i:1;s:5:"extra";i:1;}}', 'a:0:{}', 1, 0, 0, 'no', 'active');

CREATE TABLE IF NOT EXISTS `#__conv_chars` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pattern` char(1) NOT NULL,
  `replace` char(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__conv_chars` (`id`, `pattern`, `replace`) VALUES
(1, 'А', 'A'),
(2, 'а', 'a'),
(3, 'Б', 'B'),
(4, 'б', 'b'),
(5, 'В', 'V'),
(6, 'в', 'v'),
(7, 'Г', 'G'),
(8, 'г', 'g'),
(9, 'Ґ', 'G'),
(10, 'ґ', 'g'),
(11, 'Ѓ', 'Gj'),
(12, 'ѓ', 'gj'),
(13, 'Д', 'D'),
(14, 'д', 'd'),
(15, 'Ђ', 'Dj'),
(16, 'ђ', 'dj'),
(17, 'Е', 'E'),
(18, 'е', 'e'),
(19, 'Ѐ', 'E'),
(20, 'ѐ', 'e'),
(21, 'Ё', 'Yo'),
(22, 'ё', 'yo'),
(23, 'Ж', 'Zh'),
(24, 'ж', 'zh'),
(25, 'Ӂ', 'Zh'),
(26, 'ӂ', 'zh'),
(27, 'З', 'Z'),
(28, 'з', 'z'),
(29, 'Ѕ', 'Z'),
(30, 'ѕ', 'z'),
(31, 'И', 'I'),
(32, 'и', 'i'),
(33, 'Ѝ', 'I'),
(34, 'ѝ', 'i'),
(35, 'І', 'I'),
(36, 'і', 'i'),
(37, 'Ї', 'Ji'),
(38, 'ї', 'ji'),
(39, 'Й', 'Y'),
(40, 'й', 'y'),
(41, 'Ј', 'J'),
(42, 'ј', 'j'),
(43, 'К', 'K'),
(44, 'к', 'k'),
(45, 'Л', 'L'),
(46, 'л', 'l'),
(47, 'Љ', 'Lj'),
(48, 'љ', 'lj'),
(49, 'М', 'M'),
(50, 'м', 'm'),
(51, 'Н', 'N'),
(52, 'н', 'n'),
(53, 'Њ', 'Nj'),
(54, 'њ', 'nj'),
(55, 'О', 'O'),
(56, 'о', 'o'),
(57, 'П', 'P'),
(58, 'п', 'p'),
(59, 'Р', 'R'),
(60, 'р', 'r'),
(61, 'С', 'S'),
(62, 'с', 's'),
(63, 'Т', 'T'),
(64, 'т', 't'),
(65, 'Ћ', 'T'),
(66, 'ћ', 't'),
(67, 'Ќ', 'Kj'),
(68, 'ќ', 'kj'),
(69, 'У', 'U'),
(70, 'у', 'u'),
(71, 'Ў', 'U'),
(72, 'ў', 'u'),
(73, 'Ф', 'F'),
(74, 'ф', 'f'),
(75, 'Х', 'H'),
(76, 'х', 'h'),
(77, 'Ц', 'Ts'),
(78, 'ц', 'ts'),
(79, 'Ч', 'Ch'),
(80, 'ч', 'ch'),
(81, 'Џ', 'Dz'),
(82, 'џ', 'dz'),
(83, 'Ш', 'Sh'),
(84, 'ш', 'sh'),
(85, 'Щ', 'Sht'),
(86, 'щ', 'sht'),
(87, 'Ъ', 'A'),
(88, 'ъ', 'a'),
(89, 'Ы', 'I'),
(90, 'ы', 'i'),
(91, 'Ь', 'J'),
(92, 'ь', 'j'),
(93, 'Э', 'E'),
(94, 'э', 'e'),
(95, 'Ю', 'Ju'),
(96, 'ю', 'ju'),
(97, 'Я', 'Ja'),
(98, 'я', 'ja'),
(99, 'Á', 'A'),
(100, 'á', 'a'),
(101, 'Â', 'A'),
(102, 'â', 'a'),
(103, 'Ã', 'A'),
(104, 'ã', 'a'),
(105, 'À', 'A'),
(106, 'à', 'a'),
(107, 'Ä', 'A'),
(108, 'ä', 'a'),
(109, 'Ą', 'A'),
(110, 'ą', 'a'),
(111, 'Å', 'A'),
(112, 'å', 'a'),
(113, 'Ă', 'A'),
(114, 'ă', 'a'),
(115, 'Æ', 'Ae'),
(116, 'æ', 'ae'),
(117, 'Ç', 'C'),
(118, 'ç', 'c'),
(119, 'Ć', 'C'),
(120, 'ć', 'c'),
(121, 'Č', 'C'),
(122, 'č', 'c'),
(123, 'Œ', 'Oe'),
(124, 'œ', 'oe'),
(125, 'Đ', 'Dj'),
(126, 'đ', 'dj'),
(127, 'É', 'E'),
(128, 'é', 'e'),
(129, 'È', 'E'),
(130, 'è', 'e'),
(131, 'Ê', 'E'),
(132, 'ê', 'e'),
(133, 'Ę', 'E'),
(134, 'ę', 'e'),
(135, 'Ë', 'E'),
(136, 'ë', 'e'),
(137, 'Ğ', 'G'),
(138, 'ğ', 'g'),
(139, 'Í', 'I'),
(140, 'í', 'i'),
(141, 'Ì', 'I'),
(142, 'ì', 'i'),
(143, 'Î', 'I'),
(144, 'î', 'i'),
(145, 'Ï', 'I'),
(146, 'ï', 'i'),
(147, 'Ł', 'L'),
(148, 'ł', 'l'),
(149, 'Ń', 'N'),
(150, 'ń', 'n'),
(151, 'Ó', 'O'),
(152, 'ó', 'o'),
(153, 'Ò', 'O'),
(154, 'ò', 'o'),
(155, 'Ô', 'O'),
(156, 'ô', 'o'),
(157, 'Õ', 'O'),
(158, 'õ', 'o'),
(159, 'Ö', 'O'),
(160, 'ö', 'o'),
(161, 'Ø', 'O'),
(162, 'ø', 'o'),
(163, 'Ś', 'S'),
(164, 'ś', 's'),
(165, 'Š', 'S'),
(166, 'š', 's'),
(167, 'Ş', 'Sh'),
(168, 'ş', 'sh'),
(169, 'Ș', 'Sh'),
(170, 'ș', 'sh'),
(171, 'Ţ', 'T'),
(172, 'ţ', 't'),
(173, 'Ú', 'U'),
(174, 'ú', 'u'),
(175, 'Ù', 'U'),
(176, 'ù', 'u'),
(177, 'Ü', 'U'),
(178, 'ü', 'u'),
(179, 'Û', 'U'),
(180, 'û', 'u'),
(181, 'ß', 'ss'),
(182, 'Ÿ', 'Y'),
(183, 'ÿ', 'y'),
(184, 'Ź', 'Z'),
(185, 'ź', 'z'),
(186, 'Ż', 'Z'),
(187, 'ż', 'z'),
(188, 'Ž', 'Z'),
(189, 'ž', 'z'),
(190, 'Қ', 'K'),
(191, 'қ', 'k'),
(192, 'Ñ', 'Nh'),
(193, 'ñ', 'nh');

CREATE TABLE IF NOT EXISTS `#__languages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `file` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `file` (`file`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__languages` (`id`, `title`, `file`) VALUES
(1, 'English', 'en');

CREATE TABLE IF NOT EXISTS `#__log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `label` varchar(20) NOT NULL,
  `message` longtext NOT NULL,
  `ip` varbinary(32) NOT NULL,
  `time` datetime NOT NULL,
  `uniqueid` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueid` (`uniqueid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__menu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `zone` enum('nav','head') NOT NULL DEFAULT 'nav',
  `position` tinyint(3) NOT NULL,
  `roles` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `zone` (`zone`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__menu` (`id`, `title`, `url`, `zone`, `position`, `roles`) VALUES
(10, 'Home', 'index.php', 'head', 0, ''),
(2, 'User', 'index.php?{NODE}=user', 'head', 3, ''),
(3, 'My static page', 'index.php?{NODE}=my-static-page', 'head', 2, ''),
(6, 'My static page', 'index.php?{NODE}=my-static-page', 'nav', 1, ''),
(9, 'Blog', 'index.php?{NODE}=blog', 'head', 1, ''),
(11, 'Home', 'index.php', 'nav', 0, ''),
(14, 'Contact', 'index.php?{NODE}=contact', 'nav', 2, ''),
(16, 'Articles', 'index.php?{NODE}=articles', 'head', 4, ''),
(17, 'Search', 'index.php?{NODE}=search', 'nav', 3, ''),
(18, 'Terms of use', 'index.php?{NODE}=terms-of-use', 'nav', 5, ''),
(30, 'Administration', 'admin.php', 'nav', 6, 'a:1:{i:0;s:5:"ADMIN";}');

CREATE TABLE IF NOT EXISTS `#__menu_acp` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `uniqueid` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'docs.png',
  `menu` enum('system','content','security') NOT NULL DEFAULT 'content',
  `submenu` tinyint(1) NOT NULL DEFAULT '1',
  `quickicons` int(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`),
  KEY `menu` (`menu`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__menu_acp` (`id`, `title`, `uniqueid`, `url`, `icon`, `menu`, `submenu`, `quickicons`, `status`) VALUES
(1, 'System', '', '', 'system.png', 'system', 0, 0, 'active'),
(3, 'Blocks', '', 'admin.php?cont=blocks', 'blocks.png', 'system', 1, 1, 'active'),
(4, 'Comments', '', 'admin.php?cont=comments', 'cloud.png', 'system', 1, 1, 'active'),
(5, 'Menu editor', '', 'admin.php?cont=plugins&amp;op=menu', 'content.png', 'system', 1, 0, 'active'),
(7, 'Plugins and pages', '', 'admin.php?cont=plugins', 'plugins.png', 'system', 1, 0, 'active'),
(8, 'Site configuration', '', 'admin.php?cont=configuration', 'settings.png', 'system', 1, 0, 'active'),
(9, 'Statistics', '', 'admin.php?cont=statistics', 'stats.png', 'system', 1, 0, 'active'),
(10, 'Users', '', 'admin.php?cont=user', 'user.png', 'system', 1, 0, 'active'),
(11, 'Content', '', '', 'content.png', 'content', 0, 0, 'active'),
(12, 'Articles', 'articles_main', 'admin.php?cont=articles', 'write.png', 'content', 1, 1, 'active'),
(13, 'Blog', 'blog_main', 'admin.php?cont=blog', 'write.png', 'content', 1, 1, 'active'),
(19, 'Security', '', '', 'security.png', 'security', 0, 0, 'active'),
(20, 'Find IP', '', 'admin.php?cont=security&amp;op=find', 'find.png', 'security', 1, 0, 'active'),
(21, 'Banned visitors', '', 'admin.php?cont=security&amp;op=banned', 'ban.png', 'security', 1, 0, 'active'),
(22, 'Contact', '', 'admin.php?cont=contact', 'mail.png', 'system', 1, 0, 'active'),
(23, 'Languages', '', 'admin.php?cont=configuration&amp;op=lang', 'settings.png', 'system', 1, 0, 'active'),
(24, 'Character conversion', '', 'admin.php?cont=configuration&amp;op=chars', 'settings.png', 'system', 1, 0, 'active'),
(25, 'Engine options', '', 'admin.php?cont=configuration&amp;op=options', 'settings.png', 'system', 1, 0, 'active'),
(26, 'Menu editor (AdminCP)', '', 'admin.php?cont=plugins&amp;op=menuacp', 'content.png', 'system', 1, 0, 'active');

CREATE TABLE IF NOT EXISTS `#__online` (
  `ip` varbinary(32) NOT NULL,
  `uid` int(10) NOT NULL,
  `guest` tinyint(1) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__options` (
  `label` varchar(255) NOT NULL,
  `data` longtext NOT NULL,
  UNIQUE KEY `label` (`label`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__options` (`label`, `data`) VALUES
('reCaptcha', 'a:2:{s:9:"publickey";s:40:"6Ld5OgQAAAAAABxzey8i5eHRyR3Ntq-JEZJbjtTB";s:10:"privatekey";s:40:"6Ld5OgQAAAAAAPbVI9O6nMiHfuqMafb9Ia3M1n03";}'),
('sys_menu', 'a:2:{s:11:"order_field";s:8:"position";s:9:"order_dir";s:3:"ASC";}'),
('comments', 'a:7:{s:11:"avatar_size";i:40;s:5:"order";s:4:"DESC";s:5:"limit";i:10;s:9:"guest_can";i:1;s:10:"spam_words";a:9:{i:0;s:4:"http";i:1;s:3:"ftp";i:2;s:3:"www";i:3;s:3:"://";i:4;s:3:"sex";i:5;s:4:"porn";i:6;s:6:"viagra";i:7;s:8:"pharmacy";i:8;s:4:"fuck";}s:15:"moderate_always";i:0;s:15:"moderate_onspam";i:1;}'),
('error_handler', 'a:2:{s:7:"log_sys";i:1;s:8:"log_user";i:0;} '),
('rating', 'a:2:{s:7:"enabled";i:1;s:6:"guests";i:0;}');

CREATE TABLE IF NOT EXISTS `#__ratings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `controller` varchar(255) NOT NULL,
  `item` int(10) NOT NULL,
  `rate` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `ip` varbinary(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rba_roles` (
  `rid` int(10) NOT NULL AUTO_INCREMENT,
  `label` varchar(30) NOT NULL,
  `title` varchar(255) NOT NULL,
  `options` text NOT NULL,
  `static` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rid`),
  KEY `label` (`label`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__rba_roles` (`rid`, `label`, `title`, `options`, `static`) VALUES
(1, 'GUEST', 'Guest', '', 1),
(2, 'REGISTERED', 'Registered', 'a:1:{s:5:"style";s:4:"bold";}', 1),
(3, 'ADMIN', 'Administrator', 'a:2:{s:5:"color";s:7:"#CC0000";s:5:"style";s:4:"bold";}', 1),
(4, 'EDITOR', 'Editor', 'a:2:{s:5:"color";s:7:"#0000CC";s:5:"style";s:4:"bold";}', 0),
(5, 'MODERATOR', 'Moderator', 'a:2:{s:5:"color";s:7:"#00CC00";s:5:"style";s:6:"italic";}', 0),
(6, 'VIP', 'Vip', '', 0);

CREATE TABLE IF NOT EXISTS `#__stats_hits` (
  `date` date NOT NULL,
  `hits` int(11) NOT NULL,
  `uniqvis` int(11) NOT NULL,
  PRIMARY KEY (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__stats_pages` (
  `date` date NOT NULL,
  `page` varchar(255) NOT NULL,
  `hits` int(11) NOT NULL,
  `uniqueid` varchar(20) NOT NULL,
  UNIQUE KEY `uniqueid` (`uniqueid`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__stickers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `roles` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `label` (`label`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__surveys_answers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `surveyid` int(10) NOT NULL,
  `answer` text NOT NULL,
  `votes` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `surveyid` (`surveyid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__surveys_log` (
  `surveyid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `ip` varbinary(32) NOT NULL,
  KEY `surveyid` (`surveyid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__surveys_questions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `date` datetime NOT NULL,
  `type` enum('content','plugin') NOT NULL,
  `label` varchar(30) NOT NULL,
  `usecomments` int(1) NOT NULL,
  `comments` int(10) NOT NULL,
  `roles` text NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `type` (`label`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__tags` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `controller` varchar(255) NOT NULL,
  `item` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `controller` (`controller`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__user` (
  `uid` int(10) NOT NULL AUTO_INCREMENT,
  `user` varchar(40) NOT NULL,
  `name` varchar(255) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `regdate` datetime NOT NULL,
  `options` longtext NOT NULL,
  `roles` text NOT NULL,
  `cookiesalt` int(10) NOT NULL,
  `lastseen` datetime NOT NULL,
  `lastip` varbinary(32) NOT NULL,
  `code` varchar(10) NOT NULL,
  `oauth_provider` enum('facebook','twitter','openid','google') NOT NULL,
  `oauth_uid` text NOT NULL,
  `status` enum('active','inactive','waiting','moderate') NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `user` (`user`),
  KEY `regdate` (`regdate`),
  KEY `status` (`status`),
  KEY `login` (`user`,`pass`,`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__user` (`uid`, `user`, `name`, `pass`, `email`, `regdate`, `options`, `roles`, `cookiesalt`, `lastseen`, `lastip`, `code`, `status`) VALUES
(0, '', 'Guest', '', '', '0000-00-00 00:00:00', '', '', 0, '0000-00-00 00:00:00', '', '', 'inactive');

CREATE TABLE IF NOT EXISTS `#__user_invites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(30) NOT NULL,
  `registrations` int(10) NOT NULL,
  `expiration` datetime NOT NULL DEFAULT '2199-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__user_profile` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('text','textarea') NOT NULL,
  `options` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `label` (`label`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__user_profile` (`id`, `label`, `name`, `type`, `options`) VALUES
(1, 'country', 'Country', 'text', ''),
(2, 'about', 'About me', 'textarea', '');