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
 * @copyright	Copyright (C) 2008-2014 Miltenovikj Manojlo. All rights reserved.
 * @license     GNU/GPLv2 http://www.gnu.org/licenses/
 */

//Deny direct access
defined("_LOAD") or die("Access denied");

//DO NOT EDIT!!!
$config_sys_fields = array(
	"admincp_template"			=> "memht",
	"breadcrumb_separator"		=> " / ",
	"captcha"					=> 1,
	"captcha_for_users"			=> 1,
	"cnt_email_or_notify"		=> "notification",
	"comments"					=> 1,
	"copyright"					=> "Copyright ®",
	"cronjobs"					=> 0,
	"custom_foot"				=> "",
	"custom_head"				=> "",
	"dbserver_timezone"			=> 0,
	"default_datestamp"			=> "D, j M Y",
	"default_home"				=> "news",
	"default_language"			=> "en",
	"default_template"			=> "memht",
	"default_mobiletemplate"	=> "memht",
	"default_timestamp"			=> "H:i",
	"email_mailer"				=> "mail",
	"email_smtp_host"			=> "",
	"email_smtp_user"			=> "",
	"email_smtp_pass"			=> "",
	"email_smtp_ssl"			=> 1,
	"email_smtp_port"			=> 25,
	"email_charset"				=> "utf-8",
	"email_type"				=> "html",
	"engine_version"			=> "5.0.0.9",
	"files_path"				=> md5(mt_rand(1,9999)),
	"lock_template"				=> 1,
	"login_cookie_expire"		=> 7,
	"maintenance"				=> 0,
	"maintenance_last"			=> "2008-01-01 12:00:00",
	"maintenance_message"		=> "The site is under maintenance.",
	"maintenance_pause"			=> "10",
	"maintenance_whiteip"		=> "127.0.0.1",
	"meta_description"			=> "Site powered by MemHT Portal",
	"meta_keywords"				=> "Powered,MemHT,Portal",
	"nice_seo_urls"				=> 0,
	"nice_seo_urls_separator"	=> "/",
	"node"						=> "cont",
	"output_compression"		=> 0,
	"site_email"				=> "",
	"site_installed"			=> "2008-01-01 12:00:00",
	"site_name"					=> "Site powered by MemHT Portal",
	"site_title_order"			=> "ASC",
	"site_title_separator"		=> "|",
	"site_url"					=> "",
	"social_login"				=> 0,
	"statistics"				=> 1,
	"statistics_full"			=> 0,
	"texteditor"				=> 1,
	"uniqueid"					=> md5(mt_rand(1,9999)),
	"user_signup"				=> 1,
	"user_signup_confirm"		=> 1,
	"user_signup_invite"		=> 0,
	"user_signup_moderate"		=> 0,
    "terms_of_use_dialog"       => 1,
    "terms_of_use_notice"       => "By accessing or using this website or any applications or services made available by it, you agree to be bound by the terms of use (“Terms”) available on the following page. If you do not agree to these Terms, please do not use the site and exit now.",
    "terms_of_use_controller"   => "terms-of-use"
);

?>