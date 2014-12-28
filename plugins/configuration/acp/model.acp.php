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
defined("_ADMINCP") or die("Access denied");

class configurationModel {
	function Main() {
		global $Db,$config_sys,$config_sys_fields,$User;
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		?>
        
        <script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$("#tabs").tabs();
			});
		</script>
        
        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;"></div>
        
        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header">&nbsp;</div>
                        <div class="body">
                            
                            <?php
							$form = new Form();
							$form->action = "admin.php?cont="._PLUGIN."&amp;op=save";
							
							$form->Open();
							?>
                            
                            <div id="tabs">
                                <ul>
                                    <li><a href="<?php echo $config_sys['site_url']._DS.'admin.php?cont='._PLUGIN; ?>#tabs-1"><?php echo _t("GENERAL"); ?></a></li>
                                    <li><a href="<?php echo $config_sys['site_url']._DS.'admin.php?cont='._PLUGIN; ?>#tabs-2"><?php echo _t("META"); ?></a></li>
                                    <li><a href="<?php echo $config_sys['site_url']._DS.'admin.php?cont='._PLUGIN; ?>#tabs-3"><?php echo _t("CONTENT"); ?></a></li>
                                    <li><a href="<?php echo $config_sys['site_url']._DS.'admin.php?cont='._PLUGIN; ?>#tabs-4"><?php echo _t("USERS"); ?></a></li>
                                    <li><a href="<?php echo $config_sys['site_url']._DS.'admin.php?cont='._PLUGIN; ?>#tabs-5"><?php echo _t("EMAIL"); ?></a></li>
                                    <li><a href="<?php echo $config_sys['site_url']._DS.'admin.php?cont='._PLUGIN; ?>#tabs-6"><?php echo _t("MAINTENANCE"); ?></a></li>
                                    <li><a href="<?php echo $config_sys['site_url']._DS.'admin.php?cont='._PLUGIN; ?>#tabs-7"><?php echo _t("TERMS_OF_USE"); ?></a></li>
                                </ul>
                                <!-- General -->
                                <div id="tabs-1">
                                    <?php
										
										//Get configuration data
										$config_orig = array();
										$result = $Db->GetList("SELECT label,value FROM #__configuration ORDER BY label");
										foreach ($result as $row) $config_orig[Io::Output($row['label'])] = Io::Output($row['value']);
										$config_orig = array_merge($config_sys_fields,$config_orig);
										
										//Site name (site_name)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("SITE_NAME"),
																"name"		=>"site_name",
																"width"		=>"300px",
																"value"		=>$config_orig['site_name']));
										//Site address (site_url)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("SITE_ADDRESS"),
																"name"		=>"site_url",
																"width"		=>"300px",
																"value"		=>$config_orig['site_url']));
										//Service email (site_email)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("SERVICE_EMAIL"),
																"name"		=>"site_email",
																"width"		=>"300px",
																"value"		=>$config_orig['site_email']));
										//SEO urls (nice_seo_urls) -select[enabled,disabled]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("SEO_URLS"),
																"name"		=>"nice_seo_urls",
																"selected"	=>$config_orig['nice_seo_urls'],
																"values"	=>array(_t("ENABLED")=>1,_t("DISABLED")=>0)));
										//Site template (default_template) -select
										echo "<div>";
											echo "<div style='float:left;'>";
												$tdir = Utils::GetDirContent("templates"._DS,array("compiled"));
												$files = array();
												foreach ($tdir as $file) $files[MB::ucfirst($file)] = $file;
												$form->AddElement(array("element"	=>"select",
																		"label"		=>_t("TEMPLATE"),
																		"name"		=>"default_template",
																		"selected"	=>$config_orig['default_template'],
																		"info"		=>_t("DESKTOP"),
																		"values"	=>$files));
											echo "</div>";
											echo "<div style='float:left;margin-left:10px;'>";
												$form->AddElement(array("element"	=>"select",
																		"label"		=>"&nbsp;",
																		"name"		=>"default_mobiletemplate",
																		"selected"	=>$config_orig['default_mobiletemplate'],
																		"info"		=>_t("MOBILE"),
																		"values"	=>$files));
											echo "</div>";
											echo "<div style='float:left;margin-left:10px;'>";
												$form->AddElement(array("element"	=>"select",
																		"label"		=>_t("LOCKED"),
																		"name"		=>"lock_template",
																		"width"		=>"100px",
																		"info"		=>_t("LOCK_TPL_INFO"),
																		"selected"	=>$config_orig['lock_template'],
																		"values"	=>array(_t("YES")=>1,_t("NO")=>0)));
											echo "</div>";
											echo "<div style='clear:both;'></div>";
										echo "</div>\n";
										//Server timezone (dbserver_timezone) -select
										$timezone = array();
										foreach ($config_sys['timezone_list'] as $key => $value) $timezone[$value] = $key;
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("SERVER_TIMEZONE"),
																"name"		=>"dbserver_timezone",
																"selected"	=>$config_orig['dbserver_timezone'],
																"info"		=>"GMT "._GMT_DATETIME,
																"values"	=>$timezone));
										//Url node (node)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("URL_NODE"),
																"name"		=>"node",
																"width"		=>"300px",
																"info"		=>_t("URL_NODE_INFO"),
																"value"		=>$config_orig['node']));
										//Default timestamp (default_timestamp)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("TIMESTAMP"),
																"name"		=>"default_timestamp",
																"width"		=>"300px",
																"value"		=>$config_orig['default_timestamp']));
										//Captcha (captcha) -select[enabled,disabled]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("CAPTCHA"),
																"name"		=>"captcha",
																"selected"	=>$config_orig['captcha'],
																"values"	=>array(_t("ENABLED")=>1,_t("DISABLED")=>0)));
										//Default homepage (default_home) -select
										$result = $Db->GetList("SELECT title,name FROM #__content WHERE (type='PLUGIN' OR type='STATIC') AND status='active' ORDER BY title");
										$plugins = array();
										foreach ($result as $row) $plugins[Io::Output($row['title'])] = Io::Output($row['name']);
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("HOMEPAGE"),
																"name"		=>"default_home",
																"selected"	=>$config_orig['default_home'],
																"values"	=>$plugins));
										//Default datestamp (default_datestamp)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("DATESTAMP"),
																"name"		=>"default_datestamp",
																"width"		=>"300px",
																"value"		=>$config_orig['default_datestamp']));
										//Output compression (output_compression) -select[enabled,disabled]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("OUTPUT_COMPRESSION"),
																"name"		=>"output_compression",
																"selected"	=>$config_orig['output_compression'],
																"values"	=>array(_t("ENABLED")=>1,_t("DISABLED")=>0)));
										//SEO urls separator (nice_seo_urls_separator)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("SEO_URLS_SEPARATOR"),
																"name"		=>"nice_seo_urls_separator",
																"width"		=>"300px",
																"info"		=>_t("SEO_URLS_INFO"),
																"value"		=>$config_orig['nice_seo_urls_separator']));
										//Default language (default_language) -select
										$result = $Db->GetList("SELECT title,file FROM #__languages ORDER BY title");
										$lang = array();
										foreach ($result as $row) $lang[Io::Output($row['title'])] = Io::Output($row['file']);
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("LANGUAGE"),
																"name"		=>"default_language",
																"selected"	=>$config_orig['default_language'],
																"values"	=>$lang));
										//Copyright text (copyright)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("COPYRIGHT"),
																"name"		=>"copyright",
																"width"		=>"300px",
																"value"		=>$config_orig['copyright']));
										//Breadbrumb's separator (breadcrumb_separator)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("BREADCRUMBS_SEPARATOR"),
																"name"		=>"breadcrumb_separator",
																"width"		=>"300px",
																"value"		=>$config_orig['breadcrumb_separator']));
										//Site title separator (site_title_separator)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("SITE_TITLE_SEPARATOR"),
																"name"		=>"site_title_separator",
																"width"		=>"300px",
																"value"		=>$config_orig['site_title_separator']));
										//Site title order (site_title_order) -select[asc,desc]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("SITE_TITLE_ORDER"),
																"name"		=>"site_title_order",
																"selected"	=>$config_orig['site_title_order'],
																"values"	=>array(_t("ASCENDANT")=>"ASC",_t("DESCENDANT")=>"DESC")));
										//Contact type (cnt_email_or_notify) -select[email,notification]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("CONTACT_TYPE"),
																"name"		=>"cnt_email_or_notify",
																"selected"	=>$config_orig['cnt_email_or_notify'],
																"values"	=>array(_t("EMAIL")=>"email",_t("NOTIFICATION")=>"notification")));
									?>
                                </div>
                                <!-- Meta -->
                                <div id="tabs-2">
                                    <?php
										//Meta: Description (meta_description)
										$form->AddElement(array("element"	=>"textarea",
																"label"		=>_t("DESCRIPTION"),
																"name"		=>"meta_description",
																"width"		=>"90%",
																"height"	=>"50px",
																"value"		=>$config_orig['meta_description'],
																"class"		=>"sys_form_textarea"));
										//Meta: Keywords (meta_keywords)
										$form->AddElement(array("element"	=>"textarea",
																"label"		=>_t("KEYWORDS"),
																"name"		=>"meta_keywords",
																"width"		=>"90%",
																"height"	=>"50px",
																"value"		=>$config_orig['meta_keywords'],
																"class"		=>"sys_form_textarea"));
										//Site custom header
										$form->AddElement(array("element"	=>"textarea",
																"label"		=>_t("SITE_CUSTOM_HEADER"),
																"name"		=>"custom_head",
																"width"		=>"90%",
																"height"	=>"100px",
																"value"		=>$config_orig['custom_head'],
																"class"		=>"sys_form_textarea",
																"info"		=>_t("SITE_CUSTOM_HEADER_INFO")));
										//Site custom footer
										$form->AddElement(array("element"	=>"textarea",
																"label"		=>_t("SITE_CUSTOM_FOOTER"),
																"name"		=>"custom_foot",
																"width"		=>"90%",
																"height"	=>"100px",
																"value"		=>$config_orig['custom_foot'],
																"class"		=>"sys_form_textarea",
																"info"		=>_t("SITE_CUSTOM_FOOTER_INFO")));
									?>
                                </div>
                                <!-- Content -->
                                <div id="tabs-3">
                                    <?php
										//WYSIWYG text editor (texteditor) -select[enabled,disabled]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("WYSIWYG_EDITOR"),
																"name"		=>"texteditor",
																"selected"	=>$config_orig['texteditor'],
																"values"	=>array(_t("ENABLED")=>1,_t("DISABLED")=>0)));
										//Comments (comments) -select[enabled,disabled]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("COMMENTS"),
																"name"		=>"comments",
																"selected"	=>$config_orig['comments'],
																"values"	=>array(_t("ENABLED")=>1,_t("DISABLED")=>0)));
									?>
                                </div>
                                <!-- Users -->
                                <div id="tabs-4">
                                    <?php
										//Cookie's expiration (login_cookie_expire) -select
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("COOKIE_EXPIRATION"),
																"name"		=>"login_cookie_expire",
																"width"		=>"300px",
																"value"		=>$config_orig['login_cookie_expire'],
																"info"		=>_t("VALUE_EXPRESSED_IN_X",MB::strtolower(_t("DAYS")))));
										//Show captcha to registered users (captcha_for_users) -select[yes,no]
										/*$form->AddElement(array("element"	=>"select",
																"label"		=>_t("CAPTCHA_FOR_USERS")." (Coming soon...)", //TODO
																"name"		=>"captcha_for_users",
																"selected"	=>$config_orig['captcha_for_users'],
																"values"	=>array(_t("YES")=>1,_t("NO")=>0)));*/
										//Users registration (user_signup) -select[enabled,disabled]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("USER_SIGNUP"),
																"name"		=>"user_signup",
																"selected"	=>$config_orig['user_signup'],
																"values"	=>array(_t("ENABLED")=>1,_t("DISABLED")=>0)));
										//Moderate users registrations (user_signup_moderate) -select[yes,no]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("USER_SIGNUP_MOD"),
																"name"		=>"user_signup_moderate",
																"selected"	=>$config_orig['user_signup_moderate'],
																"values"	=>array(_t("YES")=>1,_t("NO")=>0)));
										//Users registration by invitation only (user_signup_invite) -select[enabled,disabled]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("USER_SIGNUP_INVITE"),
																"name"		=>"user_signup_invite",
																"selected"	=>$config_orig['user_signup_invite'],
																"values"	=>array(_t("ENABLED")=>1,_t("DISABLED")=>0)));
										//Confirm registration email (user_signup_confirm) -select[yes,no]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("USER_SIGNUP_CONFIRM"),
																"name"		=>"user_signup_confirm",
																"selected"	=>$config_orig['user_signup_confirm'],
																"values"	=>array(_t("YES")=>1,_t("NO")=>0)));
										//Allow login using oauth (social_login) -select[enabled,disabled]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("USER_OAUTH_LOGIN"),
																"name"		=>"social_login",
																"selected"	=>$config_orig['social_login'],
																"info"		=>_t("SOCIAL_LOGIN_INFO"),
																"values"	=>array(_t("ENABLED")=>1,_t("DISABLED")=>0)));
									?>
                                </div>
                                <!-- Email -->
                                <div id="tabs-5">
                                    <?php
										//Email: Mailer (email_mailer) -select[mail,smtp]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("EMAIL_MAILER"),
																"name"		=>"email_mailer",
																"selected"	=>$config_orig['email_mailer'],
																"values"	=>array("Mail"=>"mail","Smtp"=>"smtp")));
										//Email: SMTP Host (email_smtp_host)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("SMTP_HOST"),
																"name"		=>"email_smtp_host",
																"width"		=>"300px",
																"value"		=>$config_orig['email_smtp_host']));
										//Email: SMTP Username (email_smtp_user)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("SMTP_USER"),
																"name"		=>"email_smtp_user",
																"width"		=>"300px",
																"value"		=>$config_orig['email_smtp_user']));
										//Email: SMTP Password (email_smtp_pass) //password
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("SMTP_PASS"),
																"name"		=>"email_smtp_pass",
																"width"		=>"300px",
																"password"	=>true,
																"value"		=>$config_orig['email_smtp_pass']));
										//Email: SMTP Use SSL (email_smtp_ssl)
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("SMTP_USESSL"),
																"name"		=>"email_smtp_ssl",
																"selected"	=>$config_orig['email_smtp_ssl'],
																"values"	=>array(_t("YES")=>1,_t("NO")=>0)));
										//Email: SMTP Port (email_smtp_port)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("SMTP_PORT"),
																"name"		=>"email_smtp_port",
																"value"		=>$config_orig['email_smtp_port']));
										//Email: Content charset (email_charset)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("CHARSET"),
																"name"		=>"email_charset",
																"width"		=>"300px",
																"value"		=>$config_orig['email_charset']));
										//Email: Content type (email_type) -select[text,html]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("TYPE"),
																"name"		=>"email_type",
																"selected"	=>$config_orig['email_type'],
																"values"	=>array("Text"=>"text","Html"=>"html")));
									?>
                                </div>
                                <!-- Maintenance -->
                                <div id="tabs-6">
                                    <?php
										//Maintenance mode (maintenance) -select[enabled,disabled]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("MAINTENANCE_MODE"),
																"name"		=>"maintenance",
																"selected"	=>$config_orig['maintenance'],
																"values"	=>array(_t("ENABLED")=>1,_t("DISABLED")=>0)));
										//Whitelisted IP (maintenance_whiteip)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("WHITELIST_IP"),
																"name"		=>"maintenance_whiteip",
																"width"		=>"300px",
																"value"		=>Utils::Num2ip($config_orig['maintenance_whiteip']),
																"info"		=>_t("YOUR_IP").": ".$User->Ip()));
										//Maintenance mode message (maintenance_message)
										$form->AddElement(array("element"	=>"textarea",
																"label"		=>_t("MAINTENANCE_MSG"),
																"name"		=>"maintenance_message",
																"width"		=>"90%",
																"height"	=>"50px",
																"value"		=>$config_orig['maintenance_message'],
																"class"		=>"sys_form_textarea"));
										//Internal maintenance pause (maintenance_pause)
										$form->AddElement(array("element"	=>"text",
																"label"		=>_t("INT_MAINTENANCE_PAUSE"),
																"name"		=>"maintenance_pause",
																"width"		=>"300px",
																"value"		=>$config_orig['maintenance_pause'],
																"info"		=>_t("VALUE_EXPRESSED_IN_X",MB::strtolower(_t("MINUTES")))));
										//Cronjobs (cronjobs) -select[enabled,disabled]
										$form->AddElement(array("element"	=>"select",
																"label"		=>_t("CRONJOBS"),
																"name"		=>"cronjobs",
																"selected"	=>$config_orig['cronjobs'],
																"values"	=>array(_t("ENABLED")=>1,_t("DISABLED")=>0)));
									?>
                                </div>
                                <!-- Terms of use -->
                                <div id="tabs-7">
                                    <?php
                                    //Terms of use dialog (terms_of_use_dialog) -select[enabled,disabled]
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("DIALOG"),
                                                            "name"		=>"terms_of_use_dialog",
                                                            "selected"	=>$config_orig['terms_of_use_dialog'],
                                                            "values"	=>array(_t("ENABLED")=>1,_t("DISABLED")=>0),
                                                            "info"		=>_t("TERMS_OF_USE_DIALOG_INFO")));

                                    //Terms of use notice ($terms_of_use_notice)
                                    $form->AddElement(array("element"	=>"textarea",
                                                            "label"		=>_t("NOTICE"),
                                                            "name"		=>"terms_of_use_notice",
                                                            "width"		=>"90%",
                                                            "height"	=>"50px",
                                                            "value"		=>$config_orig['terms_of_use_notice'],
                                                            "class"		=>"sys_form_textarea",
                                                            "info"		=>_t("TERMS_OF_USE_NOTICE_INFO")));

                                    //Terms of use controller (terms_of_use_controller) -select
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("PAGE"),
                                                            "name"		=>"terms_of_use_controller",
                                                            "selected"	=>$config_orig['terms_of_use_controller'],
                                                            "values"	=>$plugins,
                                                            "info"		=>_t("TERMS_OF_USE_CONTROLLER_INFO")));

                                    ?>
                                </div>
                                
                            </div>
                            
                            <?php
							
							//Save
							$form->AddElement(array("element"	=>"submit",
													"name"		=>"save",
													"value"		=>_t("SAVE_CONFIGURATION")));
							
							$form->Close();
							?>
                            
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        
        <?php
			
		//Assign captured content to the template engine and clean buffer
		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
		//Draw site template
		Template::Draw();
		//Initialize and show site footer
		Layout::Footer();
	}
	
	function SaveConfig() {
		global $Db,$config_sys,$config_sys_fields;
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		//Check token
		if (Utils::CheckToken()) {
			$config_orig = array();
			$result = $Db->GetList("SELECT label,value FROM #__configuration ORDER BY label");
			foreach ($result as $row) $config_orig[Io::Output($row['label'])] = Io::Output($row['value']);
			$config_orig = array_merge($config_sys_fields,$config_orig);
			
			$site_name 				= Io::GetVar('POST','site_name',false,false,$config_orig['site_name']);
			$site_url 				= Io::GetVar('POST','site_url',false,false,$config_orig['site_url']);
			$site_email 			= Io::GetVar('POST','site_email',false,false,$config_orig['site_email']);
			$nice_seo_urls 			= Io::GetVar('POST','nice_seo_urls','int',false,$config_orig['nice_seo_urls']);
			$default_template 		= Io::GetVar('POST','default_template',false,false,$config_orig['default_template']);
			$default_mobiletemplate	= Io::GetVar('POST','default_mobiletemplate',false,false,$config_orig['default_mobiletemplate']);
			$lock_template 			= Io::GetVar('POST','lock_template','int',false,$config_orig['lock_template']);
			$dbserver_timezone		= Io::GetVar('POST','dbserver_timezone',false,false,$config_orig['dbserver_timezone']);
			$node					= Io::GetVar('POST','node',false,false,$config_orig['node']);
			$default_timestamp 		= Io::GetVar('POST','default_timestamp',false,false,$config_orig['default_timestamp']);
			$captcha 				= Io::GetVar('POST','captcha','int',false,$config_orig['captcha']);
			$default_home 			= Io::GetVar('POST','default_home',false,false,$config_orig['default_home']);
			$default_datestamp 		= Io::GetVar('POST','default_datestamp',false,false,$config_orig['default_datestamp']);
			$output_compression 	= Io::GetVar('POST','output_compression','int',false,$config_orig['output_compression']);
			$nice_seo_urls_separator= Io::GetVar('POST','nice_seo_urls_separator',false,false,$config_orig['nice_seo_urls_separator']);
			$default_language		= Io::GetVar('POST','default_language',false,false,$config_orig['default_language']);
			$copyright 				= Io::GetVar('POST','copyright',false,false,$config_orig['copyright']);
			$breadcrumb_separator 	= Io::GetVar('POST','breadcrumb_separator',false,false,$config_orig['breadcrumb_separator']);
			$site_title_separator 	= Io::GetVar('POST','site_title_separator',false,false,$config_orig['site_title_separator']);
			$site_title_order 		= Io::GetVar('POST','site_title_order',false,false,$config_orig['site_title_order']);
			$cnt_email_or_notify 	= Io::GetVar('POST','cnt_email_or_notify',false,false,$config_orig['cnt_email_or_notify']);
			$meta_description 		= Io::GetVar('POST','meta_description','nohtml',true,$config_orig['meta_description']);
			$meta_keywords 			= Io::GetVar('POST','meta_keywords','nohtml',true,$config_orig['meta_keywords']);
			$custom_head 			= Io::GetVar('POST','custom_head','fullhtml',false,"");
			$custom_foot 			= Io::GetVar('POST','custom_foot','fullhtml',false,"");
			$texteditor 			= Io::GetVar('POST','texteditor','int',false,$config_orig['texteditor']);
			$comments 				= Io::GetVar('POST','comments','int',false,$config_orig['comments']);
			$login_cookie_expire 	= Io::GetVar('POST','login_cookie_expire','int',false,$config_orig['login_cookie_expire']);
			$captcha_for_users 		= Io::GetVar('POST','captcha_for_users','int',false,$config_orig['captcha_for_users']);
			$user_signup 			= Io::GetVar('POST','user_signup','int',false,$config_orig['user_signup']);
			$user_signup_moderate 	= Io::GetVar('POST','user_signup_moderate','int',false,$config_orig['user_signup_moderate']);
			$user_signup_invite 	= Io::GetVar('POST','user_signup_invite','int',false,$config_orig['user_signup_invite']);
			$user_signup_confirm 	= Io::GetVar('POST','user_signup_confirm','int',false,$config_orig['user_signup_confirm']);
			$social_login			= Io::GetVar('POST','social_login','int',false,$config_orig['social_login']);
			$email_mailer 			= Io::GetVar('POST','email_mailer',false,false,$config_orig['email_mailer']);
			$email_smtp_host 		= Io::GetVar('POST','email_smtp_host',false,false);
			$email_smtp_user 		= Io::GetVar('POST','email_smtp_user',false,false);
			$email_smtp_pass 		= Io::GetVar('POST','email_smtp_pass',false,false);
			$email_smtp_ssl 		= Io::GetVar('POST','email_smtp_ssl',false,false,1);
			$email_smtp_port 		= Io::GetVar('POST','email_smtp_port',false,false,25);
			$email_charset 			= Io::GetVar('POST','email_charset',false,false,$config_orig['email_charset']);
			$email_type 			= Io::GetVar('POST','email_type',false,false,$config_orig['email_type']);
			$maintenance 			= Io::GetVar('POST','maintenance','int',false,$config_orig['maintenance']);
			$maintenance_message 	= Io::GetVar('POST','maintenance_message',false,false,$config_orig['maintenance_message']);
			$maintenance_whiteip	= Io::GetVar('POST','maintenance_whiteip',false,false,$config_orig['maintenance_whiteip']);
			$maintenance_pause 		= Io::GetVar('POST','maintenance_pause','int',false,$config_orig['maintenance_pause']);
			$cronjobs 				= Io::GetVar('POST','cronjobs','int',false,$config_orig['cronjobs']);
            $terms_of_use_dialog    = Io::GetVar('POST','terms_of_use_dialog','int',false,$config_orig['terms_of_use_dialog']);
            $terms_of_use_notice    = Io::GetVar('POST','terms_of_use_notice',false,false,$config_orig['terms_of_use_notice']);
			$terms_of_use_controller= Io::GetVar('POST','terms_of_use_controller',false,false,$config_orig['terms_of_use_controller']);

			//Save
			if ($Db->Query("REPLACE INTO #__configuration (label,value) VALUES
						('site_name','".$Db->_e($site_name)."'),
						('site_url','".$Db->_e($site_url)."'),
						('site_email','".$Db->_e($site_email)."'),
						('nice_seo_urls','".$Db->_e($nice_seo_urls)."'),
						('default_template','".$Db->_e($default_template)."'),
						('default_mobiletemplate','".$Db->_e($default_mobiletemplate)."'),
						('dbserver_timezone','".$Db->_e($dbserver_timezone)."'),
						('node','".$Db->_e($node)."'),
						('default_timestamp','".$Db->_e($default_timestamp)."'),
						('captcha','".$Db->_e($captcha)."'),
						('default_home','".$Db->_e($default_home)."'),
						('default_datestamp','".$Db->_e($default_datestamp)."'),
						('output_compression','".$Db->_e($output_compression)."'),
						('nice_seo_urls_separator','".$Db->_e($nice_seo_urls_separator)."'),
						('default_language','".$Db->_e($default_language)."'),
						('copyright','".$Db->_e($copyright)."'),
						('breadcrumb_separator','".$Db->_e($breadcrumb_separator)."'),
						('site_title_separator','".$Db->_e($site_title_separator)."'),
						('site_title_order','".$Db->_e($site_title_order)."'),
						('cnt_email_or_notify','".$Db->_e($cnt_email_or_notify)."'),
						('meta_description','".$Db->_e($meta_description)."'),
						('meta_keywords','".$Db->_e($meta_keywords)."'),
						('custom_head','".$Db->_e($custom_head)."'),
						('custom_foot','".$Db->_e($custom_foot)."'),
						('texteditor','".$Db->_e($texteditor)."'),
						('comments','".$Db->_e($comments)."'),
						('lock_template','".intval($lock_template)."'),
						('login_cookie_expire','".$Db->_e($login_cookie_expire)."'),
						('captcha_for_users','".$Db->_e($captcha_for_users)."'),
						('user_signup','".$Db->_e($user_signup)."'),
						('user_signup_moderate','".$Db->_e($user_signup_moderate)."'),
						('user_signup_invite','".$Db->_e($user_signup_invite)."'),
						('user_signup_confirm','".$Db->_e($user_signup_confirm)."'),
						('social_login','".$Db->_e($social_login)."'),
						('email_mailer','".$Db->_e($email_mailer)."'),
						('email_smtp_host','".$Db->_e($email_smtp_host)."'),
						('email_smtp_user','".$Db->_e($email_smtp_user)."'),
						('email_smtp_pass','".$Db->_e($email_smtp_pass)."'),
						('email_smtp_ssl','".$Db->_e($email_smtp_ssl)."'),
						('email_smtp_port','".$Db->_e($email_smtp_port)."'),
						('email_charset','".$Db->_e($email_charset)."'),
						('email_type','".$Db->_e($email_type)."'),
						('maintenance','".$Db->_e($maintenance)."'),
						('maintenance_message','".$Db->_e($maintenance_message)."'),
						('maintenance_whiteip','".Utils::Ip2num($Db->_e($maintenance_whiteip))."'),
						('maintenance_pause','".$Db->_e($maintenance_pause)."'),
						('terms_of_use_dialog','".$Db->_e($terms_of_use_dialog)."'),
						('terms_of_use_notice','".$Db->_e($terms_of_use_notice)."'),
						('terms_of_use_controller','".$Db->_e($terms_of_use_controller)."'),
						('cronjobs','".$Db->_e($cronjobs)."')")) { //+Additional
				Error::Trigger("INFO",_t("X_SAVED",_t("CONFIGURATION")));
				Utils::Redirect("admin.php?cont="._PLUGIN);
			} else {
				Error::Trigger("USERERROR",_t("X_NOT_SAVED",_t("CONFIGURATION")));
			}
		} else {
			Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
		}
		
		//Assign captured content to the template engine and clean buffer
		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
		//Draw site template
		Template::Draw();
		//Initialize and show site footer
		Layout::Footer();
	}
	//Languages
	function Languages() {
		global $Db,$config_sys;
	
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
	
		?>
	
	        <script type="text/javascript" charset="utf-8">
				$(document).ready(function() {
					//Create
					$('input#create').click(function() {
	                    window.location.href = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=addlang';
	                });
	
					//Delete permanently
					$('input#delete').click(function() {
						var obj = $('.cb:checkbox:checked');
						if (obj.length>0) {
							if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("LANGUAGE"))); ?>')) {
								var items = new Array();
								for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
								$.ajax({
									type: "POST",
									dataType: "xml",
									url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=dellang",
									data: "items="+items,
									success: function(data){
										location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=lang';
									}
								});
							}
						} else {
							alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("LANGUAGE"))); ?>');
						}
					});
				});
			</script>
	
	        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>&amp;op=lang" title="<?php echo _t("LANGUAGES"); ?>"><?php echo _t("LANGUAGES"); ?></a></div>
	
	        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
	        	<tr>
			       	<td style="vertical-align:top;">
	                    <div class="widget ui-widget-content ui-corner-all">
	                        <div class="ui-widget-header"><?php echo _t("MANAGE_LANGUAGES"); ?></div>
	                        <div class="body">
	
	        <?php
			echo "<div style='float:left; margin:6px 0 2px 0;'>\n";
	            //INstall
				echo "<input type='button' name='create' value='"._t("INSTALL_NEW_X",MB::strtolower(_t("LANGUAGE")))."' style='margin:2px 0;' class='sys_form_button' id='create' />\n";
			echo "</div>\n";
			echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
				//Delete permanently
				echo "<input type='button' name='delete' value='"._t("DELETE_PERMANENTLY")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
			echo "</div>\n";
	
	        echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
	            echo "<thead>\n";
				echo "<tr>\n";
					echo "<th width='1%' style='text-align:right;'></th>\n";
					echo "<th width='50%'>"._t("TITLE")."</th>\n";
					echo "<th width='49%'>"._t("FILE")."</th>\n";
				echo "</tr>\n";
				echo "</thead>\n";
				echo "<tbody>\n";
	
	            if ($result = $Db->GetList("SELECT id,title,file FROM #__languages ORDER BY title")) {
					foreach ($result as $row) {
						$lid	= Io::Output($row['id'],"int");
						$ltitle	= Io::Output($row['title']);
						$lfile	= Io::Output($row['file']);
	
	                    echo "<tr>\n";
							echo "<td><input type='checkbox' name='selected[]' value='$lid' class='cb' /></td>\n";
							echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=editlang&amp;id=$lid' title='"._t("EDIT_THIS_X",MB::strtolower(_t("LANGUAGE")))."'>$ltitle</a></td>\n";
							echo "<td>$lfile</td>\n";
						echo "</tr>\n";
					}
				} else {
					echo "<tbody>\n";
					echo "<tr>\n";
						echo "<td style='text-align:center;' colspan='3'>"._t("LIST_EMPTY")."</td>\n";
					echo "</tr>\n";
				}
			?>
	                                </tbody>
	                            </table>
	                        </div>
	                    </div>
	                </td>
	            </tr>
	        </table>
	        <?php
	
			//Assign captured content to the template engine and clean buffer
			Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
			//Draw site template
			Template::Draw();
			//Initialize and show site footer
			Layout::Footer();
		}
		
		function DeleteLanguage() {
			global $Db;
		
			$items = Io::GetVar("POST","items",false,true);
		
			$result = $Db->Query("DELETE FROM #__languages WHERE id IN (".$Db->_e($items).")") ? 1 : 0 ;
			$total = $Db->AffectedRows();
		
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
			header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
			header("Cache-Control: no-cache, must-revalidate" );
			header("Pragma: no-cache" );
			header("Content-Type: text/xml");
		
			$xml = '<?xml version="1.0" encoding="utf-8"?>\n';
			$xml .= '<response>\n';
			$xml .= '<result>\n';
			$xml .= '<query>'.$result.'</query>\n';
			$xml .= '<rows>'.$total.'</rows>\n';
			$xml .= '</result>\n';
			$xml .= '</response>';
			return $xml;
		}
		
		function AddLanguage() {
			global $Db,$User,$config_sys;
		
			//Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();
		
			?>
		
	        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>&amp;op=lang" title="<?php echo _t("LANGUAGES"); ?>"><?php echo _t("LANGUAGES"); ?></a></div>
	        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
	        	<tr>
			       	<td style="vertical-align:top;">
	                    <div class="widget ui-widget-content ui-corner-all">
	                        <div class="ui-widget-header"><?php echo _t("INSTALL_NEW_X",MB::strtolower(_t("LANGUAGE"))); ?></div>
	                        <div class="body">
	
							<?php
	
							if (!isset($_POST['create'])) {
									$form = new Form();
									$form->action = "admin.php?cont="._PLUGIN."&amp;op=addlang";
	
									$form->Open();
	
									//Title
									$form->AddElement(array("element"	=>"text",
															"label"		=>_t("TITLE"),
															"width"		=>"300px",
															"name"		=>"title",
															"id"		=>"title"));
	
									//File
									$langs = array();
									$result = $Db->GetList("SELECT file FROM #__languages");
									foreach ($result as $row) $langs[] = Io::Output($row['file']);
									$tdir = Utils::GetDirContent("languages");
									$files = array();
									foreach ($tdir as $file) {
										if (!in_array(str_replace(".php","",$file),$langs)) {
											$files[MB::ucfirst(str_replace(".php","",$file))] = str_replace(".php","",$file);
										}
									}
									$form->AddElement(array("element"	=>"select",
															"label"		=>_t("FILE"),
															"name"		=>"file",
															"values"	=>$files));
	
									?>
	
	                                <div style="padding:2px;"></div>
	                                <?php
	
									//Create
									$form->AddElement(array("element"	=>"submit",
															"name"		=>"create",
															"inline"	=>true,
															"value"		=>_t("CREATE")));
	
									?>
												</div>
											</div>
										</td>
									</tr>
								</table>
								<?php
	
								$form->Close();
	
							} else {
								//Check token
								if (Utils::CheckToken()) {
									//Get POST data
									$title = Io::GetVar('POST','title','fullhtml');
									$file = Io::GetVar('POST','file','[^a-zA-Z0-9\-]');
	
									$errors = array();
									if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
									if (empty($file)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("FILE"));
	
									if (!sizeof($errors)) {
										$Db->Query("INSERT INTO #__languages (title,file)
													VALUES ('".$Db->_e($title)."','".$Db->_e($file)."')");
	
										Utils::Redirect("admin.php?cont="._PLUGIN."&op=languages");
									} else {
										Error::Trigger("USERERROR",implode("<br />",$errors));
									}
								} else {
									Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
								}
	
								?>
												</div>
											</div>
										</td>
									</tr>
								</table>
	
								<?php
							}
	
	
			//Assign captured content to the template engine and clean buffer
			Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
			//Draw site template
			Template::Draw();
			//Initialize and show site footer
			Layout::Footer();
		}
	
		function EditLanguage() {
			global $Db,$User,$config_sys;
	
			//Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();
	
			?>
	
	        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>&amp;op=lang" title="<?php echo _t("LANGUAGES"); ?>"><?php echo _t("LANGUAGES"); ?></a></div>
	        
	        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
	        	<tr>
			       	<td style="vertical-align:top;">
	                    <div class="widget ui-widget-content ui-corner-all">
	                        <div class="ui-widget-header"><?php echo _t("EDIT"); ?></div>
	                        <div class="body">
	
							<?php
	
							$id = Io::GetVar('GET','id','int');
							if ($valrow = $Db->GetRow("SELECT * FROM #__languages WHERE id=".intval($id))) {
	
								if (!isset($_POST['save'])) {
									$form = new Form();
									$form->action = "admin.php?cont="._PLUGIN."&amp;op=editlang&amp;id=$id";
	
									$form->Open();
	
									//Title
									$form->AddElement(array("element"	=>"text",
															"label"		=>_t("TITLE"),
															"width"		=>"300px",
															"value"		=>Io::Output($valrow['title']),
															"name"		=>"title",
															"id"		=>"title"));
	
									//File
									$langs = array();
									$result = $Db->GetList("SELECT file FROM #__languages WHERE id!=".intval($id));
									foreach ($result as $row) $langs[] = Io::Output($row['file']);
									$tdir = Utils::GetDirContent("languages");
									$files = array();
									foreach ($tdir as $file) {
										if (!in_array(str_replace(".php","",$file),$langs)) {
											$files[MB::ucfirst(str_replace(".php","",$file))] = str_replace(".php","",$file);
										}
									}
									$form->AddElement(array("element"	=>"select",
															"label"		=>_t("FILE"),
															"name"		=>"file",
															"selected"	=>Io::Output($valrow['title']),
															"values"	=>$files));
	
									?>
	
	                                <div style="padding:2px;"></div>
	                                <?php
	
									//Save
									$form->AddElement(array("element"	=>"submit",
															"name"		=>"save",
															"inline"	=>true,
															"value"		=>_t("SAVE")));
	
									$form->Close();
								} else {
									//Check token
									if (Utils::CheckToken()) {
										//Get POST data
										$title = Io::GetVar('POST','title','fullhtml');
										$file = Io::GetVar('POST','file','[^a-zA-Z0-9\-]');
	
										$errors = array();
										if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
										if (empty($file)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("FILE"));
	
										if (!sizeof($errors)) {
											$Db->Query("UPDATE #__languages
	                                                    SET title='".$Db->_e($title)."',file='".$Db->_e($file)."'
	                                                    WHERE id=".intval($id));
	
											Utils::Redirect("admin.php?cont="._PLUGIN."&op=languages");
										} else {
											Error::Trigger("USERERROR",implode("<br />",$errors));
										}
									} else {
										Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
									}
								}
							} else {
								Error::Trigger("USERERROR",_t("X_NOT_FOUND",_t("SECTION")));
							}
							?>
	
											</div>
										</div>
									</td>
								</tr>
							</table>
							<?php
	
	
			//Assign captured content to the template engine and clean buffer
			Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
			//Draw site template
			Template::Draw();
			//Initialize and show site footer
			Layout::Footer();
		}
		
		function Characters() {
			global $Db;
			//Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();
			 
			?>
		    <script type="text/javascript" charset="utf-8">
			    $(document).ready(function() {
				    //Create
					$('input#create').click(function() {
	                    window.location.href = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=addchars';
		            });
		
		            //Delete permanently
					$('input#delete').click(function() {
					    var obj = $('.cb:checkbox:checked');
	                    if (obj.length>0) {
					        if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("CHARACTER"))); ?>')) {
							    var items = new Array();
					            for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
								$.ajax({
									type: "POST",
									dataType: "xml",
									url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=delchars",
									data: "items="+items,
									success: function(data){
									    location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=chars';
									}
								});
							}
						} else {
							alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("CHARACTER"))); ?>');
						}
					});
			    });
		    </script>
	        
	        <?php
	        
	        echo "<div class='tpl_page_title'><a href='admin.php?cont="._PLUGIN."&amp;op=chars' title='"._t("CHARACTERS")."'>"._t("CHARACTERS")."</a></div>";
	        	
			echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' summary=''>";
			echo "<tr>";
			    echo "<td style='vertical-align:top;'>";
	                echo "<div class='widget ui-widget-content ui-corner-all'>";
	                    echo "<div class='ui-widget-header'>"._t("MANAGE_CHARACTERS")."</div>";
	                	echo "<div class='body'>";
	                    
				            echo "<div style='float:left; margin:6px 0 2px 0;'>\n";
					            echo "<input type='button' name='create' value='"._t("ADD_NEW_X",MB::strtolower(_t("CHARACTER")))."' style='margin:2px 0;' class='sys_form_button' id='create' />\n";
				            echo "</div>\n";
				            echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
					            echo "<input type='button' name='delete' value='"._t("DELETE_PERMANENTLY")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
				            echo "</div>\n";
	                                    
	            	        echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
	            	            echo "<thead>\n";
	            				echo "<tr>\n";
	            					echo "<th width='1%' style='text-align:right;'><input type='checkbox' id='selectall' /></th>\n";
	            					echo "<th width='50%'>"._t("PATTERN")."</th>\n";
	            					echo "<th width='49%'>"._t("REPLACE")."</th>\n";
	            				echo "</tr>\n";
	            				echo "</thead>\n";
	            				echo "<tbody>\n";
	
		                            if ($result = $Db->GetList("SELECT * FROM #__conv_chars ORDER BY id")) {
						                foreach ($result as $row) {
							                $id	        = Io::Output($row['id'],"int");
						                    $pattern	= Io::Output($row['pattern']);
							                $replace	= Io::Output($row['replace']);
		
		                                    echo "<tr>\n";
								                echo "<td><input type='checkbox' name='selected[]' value='$id' class='cb' /></td>\n";
								                echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=editchars&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("CHARACTER")))."'>$pattern</a></td>\n";
								                echo "<td>$replace</td>\n";
							                echo "</tr>\n";
						                }
		                            } else {
						                echo "<tr>\n";
							                echo "<td style='text-align:center;' colspan='3'>"._t("LIST_EMPTY")."</td>\n";
						                echo "</tr>\n";
					                }
	                                           
	                            echo "</tbody>\n";
	                        echo "</table>\n";
	                                
	                    echo "</div>";
	                echo "</div>";
	            echo "</td>";
	        echo "</tr>";
	        echo "</table>";
	    		
	    	//Assign captured content to the template engine and clean buffer
	    	Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
	    	//Draw site template
	    	Template::Draw();
	    	//Initialize and show site footer
	    	Layout::Footer();
		}
		
	    function AddCharacters() {
		    global $Db;
	        //Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();
			
			echo "<div class='tpl_page_title'><a href='admin.php?cont="._PLUGIN."&amp;op=chars' title='"._t("CHARACTERS")."'>"._t("CHARACTERS")."</a></div>";
	        
	        echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' summary=''>";
			echo "<tr>";
			    echo "<td style='vertical-align:top;'>";
	                echo "<div class='widget ui-widget-content ui-corner-all'>";
	                    echo "<div class='ui-widget-header'>"._t("ADD_NEW_X",MB::strtolower(_t("CHARACTER")))."</div>";
	                	echo "<div class='body'>";
	
						    if (!isset($_POST['add'])) {
							    $form = new Form();
								$form->action = "admin.php?cont="._PLUGIN."&amp;op=addchars";
			
								$form->Open();
			
								//Pattern
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("PATTERN"),
														"width"		=>"300px",
														"name"		=>"pattern",
														"id"		=>"pattern"));
			
								//Replace
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("REPLACE"),
														"width"		=>"300px",
														"name"		=>"replace",
														"id"		=>"replace"));
			
								//Add
								$form->AddElement(array("element"	=>"submit",
														"name"		=>"add",
														"inline"	=>true,
														"value"		=>_t("ADD")));
			
								$form->Close();
			
						    } else {
								//Check token
								if (Utils::CheckToken()) {
								    //Get POST data
									$pattern = Io::GetVar('POST','pattern','nohtml');
	                                $replace = Io::GetVar('POST','replace','nohtml');
	
			                        $errors = array();
									if (empty($pattern)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("PATTERN"));
									if (empty($replace)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("REPLACE"));
			
									if (!sizeof($errors)) {
									    $Db->Query("INSERT INTO #__conv_chars (`pattern`,`replace`)
													VALUES ('".$Db->_e($pattern)."','".$Db->_e($replace)."')");
	                                             	
										Utils::Redirect("admin.php?cont="._PLUGIN."&op=chars");
									} else {
										Error::Trigger("USERERROR",implode("<br />",$errors));
									}
								} else {
								    Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
								}
						    }
	
	                    echo "</div>";
	                echo "</div>";
	            echo "</td>";
	        echo "</tr>";
	        echo "</table>";
			
			//Assign captured content to the template engine and clean buffer
			Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
			//Draw site template
			Template::Draw();
			//Initialize and show site footer
			Layout::Footer();
	    }
	    
		function EditCharacters() {
		    global $Db;
	        //Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();
			
			echo "<div class='tpl_page_title'><a href='admin.php?cont="._PLUGIN."&amp;op=chars' title='"._t("CHARACTERS")."'>"._t("CHARACTERS")."</a></div>";
	        
	        echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' summary=''>";
			echo "<tr>";
			    echo "<td style='vertical-align:top;'>";
	                echo "<div class='widget ui-widget-content ui-corner-all'>";
	                    echo "<div class='ui-widget-header'>"._t("EDIT_X",MB::strtolower(_t("CHARACTER")))."</div>";
	                	echo "<div class='body'>";
	                        $id = Io::GetVar('GET','id','int');
						    if ($row = $Db->GetRow("SELECT * FROM #__conv_chars WHERE id=".intval($id))) {
	                            if (!isset($_POST['save'])) {
	    						    $form = new Form();
	    							$form->action = "admin.php?cont="._PLUGIN."&amp;op=editchars&amp;id=$id";
	    		
	    							$form->Open();
	    		
	    							//Pattern
	    							$form->AddElement(array("element"	=>"text",
	    													"label"		=>_t("PATTERN"),
	    													"width"		=>"300px",
	    													"name"		=>"pattern",
	    													"value"		=>Io::Output($row['pattern']),
	                                                        "id"		=>"pattern"));
	    		
	    							//Replace
	    							$form->AddElement(array("element"	=>"text",
	    													"label"		=>_t("REPLACE"),
	    													"width"		=>"300px",
	    													"name"		=>"replace",
	    													"value"		=>Io::Output($row['replace']),
	                                                        "id"		=>"replace"));
	    		
	    							//Save
	    							$form->AddElement(array("element"	=>"submit",
	    													"name"		=>"save",
	    													"inline"	=>true,
	    													"value"		=>_t("SAVE")));
	    		
	    							$form->Close();
	    		
	    					    } else {
	    							//Check token
	    							if (Utils::CheckToken()) {
	    							    //Get POST data
	    								$pattern = Io::GetVar('POST','pattern','nohtml');
	                                    $replace = Io::GetVar('POST','replace','nohtml');
	    
	    		                        $errors = array();
	    								if (empty($pattern)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("PATTERN"));
	    								if (empty($replace)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("REPLACE"));
	    		
	    								if (!sizeof($errors)) {
	                                        $Db->Query("UPDATE #__conv_chars SET `pattern`='".$Db->_e($pattern)."',`replace`='".$Db->_e($replace)."' WHERE id=".intval($id));
	    									Utils::Redirect("admin.php?cont="._PLUGIN."&op=chars");
	    								} else {
	    									Error::Trigger("USERERROR",implode("<br />",$errors));
	    								}
	    							} else {
	    							    Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
	    							}
	    					    }
							} else {
								Error::Trigger("USERERROR",_t("X_NOT_FOUND",_t("CHARACTER")));
							}
	                    echo "</div>";
	                echo "</div>";
	            echo "</td>";
	        echo "</tr>";
	        echo "</table>";
			
			//Assign captured content to the template engine and clean buffer
			Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
			//Draw site template
			Template::Draw();
			//Initialize and show site footer
			Layout::Footer();
		}
	    
		function DeleteCharacters() {
				global $Db;
			
				$items = Io::GetVar("POST","items",false,true);
			
				$result = $Db->Query("DELETE FROM #__conv_chars WHERE id IN (".$Db->_e($items).")") ? 1 : 0 ;
				$total = $Db->AffectedRows();
			
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
				header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
				header("Cache-Control: no-cache, must-revalidate" );
				header("Pragma: no-cache" );
				header("Content-Type: text/xml");
			
				$xml = '<?xml version="1.0" encoding="utf-8"?>\n';
				$xml .= '<response>\n';
				$xml .= '<result>\n';
				$xml .= '<query>'.$result.'</query>\n';
				$xml .= '<rows>'.$total.'</rows>\n';
				$xml .= '</result>\n';
				$xml .= '</response>';
				return $xml;
		}
		
		//Options
		function EngineOptions() {
			global $Db,$config_sys;
		
			//Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();
			
			?>
				
				<script type="text/javascript" charset="utf-8">
					$(document).ready(function() {
						<?php
						if (!Io::GetCookie("waropt","int",false,0)) {
						?>
						$("#dialog-message").dialog({
							resizable: false,
							modal: true,
							buttons: {
								"<?php echo _t("CONTINUE"); ?>": function() {
									$(this).dialog("close");
									var date = new Date();
							        date.setTime(date.getTime()+(60*60*1000));
									document.cookie = "waropt=1; expires="+date.toGMTString()+"; path=<?php echo _COOKIEPATH; ?>";
								},
								"<?php echo _t("LEAVEPAGE"); ?>": function() {
									$(this).dialog("close");
									location = 'admin.php';
								}
							}
						});
						<?php } ?>
						
		                //Add
						$('input#create').click(function() {
		                    window.location.href = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=setoption';
		                });
					});
		
					function deleteoption(label,key) {
						if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("OPTION"))); ?>')) {
							$.ajax({
								type: "POST",
								dataType: "xml",
								url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=deleteoption",
								data: "label="+label+"&key="+key,
								success: function(data){
									location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=options';
								}
							});
						}
					}
				</script>
				
		        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>&amp;op=options" title="<?php echo _t("OPTIONS"); ?>"><?php echo _t("OPTIONS"); ?></a></div>
		
		        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
		        	<tr>
				       	<td style="vertical-align:top;">
		                    <div class="widget ui-widget-content ui-corner-all">
		                        <?php

		                        ?>
                        		<div class="ui-widget-header"><?php echo _t("OPTIONS"); ?></div>
                        		<div class="body">
                        		<?php
                        								
                        		echo "<div style='float:left; margin:6px 0 2px 0;'>\n";
                        			//Add
                        			echo "<input type='button' name='create' value='"._t("ADD_NEW_X",MB::strtolower(_t("OPTION")))."' style='margin:2px 0;' class='sys_form_button' id='create' />\n";
                        		echo "</div>\n";
                        									
                        		echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
                        		
								if ($result = $Db->GetList("SELECT label,data FROM #__options ORDER BY label")) {
									foreach ($result as $row) {
										$label	= Io::Output($row['label']);
										$data	= Utils::Unserialize(Io::Output($row['data']));
									
										echo "<thead>\n";
											echo "<tr>\n";
											echo "<th width='45%'>$label</th>\n";
											echo "<th width='45%'>&nbsp;</th>\n";
											echo "<th width='10%' style='text-align:right;'>\n";
												echo "<input type='button' value='"._t("DELETE")."' class='sys_form_button' onclick=\"javascript:deleteoption('$label','');\" />\n";
											echo "</th>\n";
											echo "</tr>\n";
										echo "</thead>\n";
										
										foreach ($data as $key => $value) {
											$tip = (is_array($value)) ? implode(", ",$value) : $value ;
											echo "<tbody>\n";
												echo "<tr>\n";
													echo "<td style='vertical-align:middle;'>\n";
														if (is_array($value)) {
															echo $key;
														} else {
															echo "<a href='admin.php?cont="._PLUGIN."&amp;op=setoption&amp;label=$label&amp;key=$key' title='"._t("EDIT_THIS_X",MB::strtolower(_t("OPTION")))."'>$key</a>";
														}
													echo "</td>\n";
													echo "<td style='vertical-align:middle;'><a title='".CleanTitleAtr($tip)."'>$tip</a></td>\n";
													echo "<td style='text-align:right;'>\n";
														echo "<input type='button' value='"._t("DELETE")."' class='sys_form_button' onclick=\"javascript:deleteoption('$label','$key');\" />\n";
													echo "</td>\n";
												echo "</tr>\n";
											echo "</tbody>\n";
										}
									}
								} else {
									echo "<tbody>\n";
									echo "<tr>\n";
										echo "<td style='text-align:center;' colspan='4'>"._t("LIST_EMPTY")."</td>\n";
									echo "</tr>\n";
									echo "</tbody>\n";
								}
								?>
		        				</table>
		                        
		                        </div>
		                    </div>
		                </td>
		                <?php
		                if (isset($title)) {
		                ?>
		                <td class="sidebar">
		                    <div class="widget ui-widget-content ui-corner-all">
		                        <div class="ui-widget-header"><?php echo _t("DOCUMENTS"); ?></div>
		                        <div class="body">
									<?php echo _t("READ_THIS_X_FOR_MORE_INFORMATION","http://www.memht.com/docs/plugins/options"); ?>
		                        </div>
		                    </div>
		                </td>
		                <?php
		                }
		                ?>
		            </tr>
		        </table>
		        
		        <?php
				if (!Io::GetCookie("waropt","int",false,0)) {
				?>
		        <div id="dialog-message" title="<?php echo _t("WARNING"); ?>">
		        <p>
		        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
		        <?php echo _t("WARNINGPOTPROB"); ?>
		        </p>
		        </div>
		        <?php
				}
		
				//Assign captured content to the template engine and clean buffer
				Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
				//Draw site template
				Template::Draw();
				//Initialize and show site footer
				Layout::Footer();
		    }
		    
		    function SetEngineOptions() {
		    	global $Db,$User,$config_sys;
		    
		    	//Initialize and show site header
		    	Layout::Header();
		    	//Start buffering content
		    	Utils::StartBuffering();
		    
		    	$label = Io::GetVar("GET","label",false,true,false);
		    	$key = Io::GetVar("GET","key",false,true,false);
		    	$value = ($label != false && $key != false) ? Utils::GetComOption($label,$key) : false ;
		    		
		    	
		    	?>
		                
                <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>&amp;op=options" title="<?php echo _t("OPTIONS"); ?>"><?php echo _t("OPTIONS"); ?></a></div>
                
                <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
                	<tr>
        		       	<td style="vertical-align:top;">
                            <div class="widget ui-widget-content ui-corner-all">
                                <div class="ui-widget-header"><?php echo _t("EDIT_X",MB::strtolower(_t("OPTION"))); ?></div>
                                <div class="body">
                                
        						<?php
        						
        						if (!isset($_POST['set'])) {
        								$form = new Form();
        								$form->action = "admin.php?cont="._PLUGIN."&amp;op=setoption";
        								
        								$form->Open();
    
        								//Label
        								$form->AddElement(array("element"	=>"text",
        														"label"		=>_t("LABEL"),
        														"name"		=>"label",
        														"width"		=>"300px",
        														"value"		=>$label,
        														"info"		=>_t("REQUIRED")));
        								
        								//Key
        								$form->AddElement(array("element"	=>"text",
        								        				"label"		=>_t("KEY"),
        								        				"name"		=>"key",
        								        				"width"		=>"300px",
        								        				"value"		=>$key,
        								        				"info"		=>_t("REQUIRED")));
        								
        								//Value
	        							$form->AddElement(array("element"	=>"text",
	        													"label"		=>_t("VALUE"),
	        													"name"		=>"value",
	        													"width"		=>"600px",
	        													"value"		=>$value,
	        													"info"		=>_t("REQUIRED")));
        								
        								?>
        								
                                        <div style="padding:2px;"></div>
                                        <?php
        								
        								//Save
        								$form->AddElement(array("element"	=>"submit",
        														"name"		=>"set",
        														"inline"	=>true,
        														"value"		=>_t("SAVE")));
        								
        								?>
        											</div>
        										</div>
        									</td>
        								</tr>
        							</table>
        							<?php
        							
        							$form->Close();
        							
        						} else {
        							//Check token
        							if (Utils::CheckToken()) {
        								//Get POST data
        								$label = Io::GetVar('POST','label');
        								$key = Io::GetVar('POST','key');
        								$value = Io::GetVar('POST','value');
        								
        								$errors = array();
        								if (empty($label)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LABEL"));
        								if ($key=="") $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("KEY"));
        								if ($value=="") $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("VALUE"));
        								
        								if (!sizeof($errors)) {
        									Utils::SetComOption($label,$key,$value);
        												
        									Utils::Redirect("admin.php?cont="._PLUGIN."&op=options");
        								} else {
        									Error::Trigger("USERERROR",implode("<br />",$errors));
        								}
        							} else {
        								Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
        							}
        							
        							?>
        											</div>
        										</div>
        									</td>
        								</tr>
        							</table>
        							
        							<?php
        						}
        						
        			
        		//Assign captured content to the template engine and clean buffer
        		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
        		//Draw site template
        		Template::Draw();
        		//Initialize and show site footer
        		Layout::Footer();
        	}
        	
        	function DeleteEngineOptions() {
        		global $Db;
        	
        		$label = Io::GetVar("POST","label");
        		$key = Io::GetVar("POST","key");
        		
        		Utils::DeleteComOption($label,$key);
        	
        		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
        		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
        		header("Cache-Control: no-cache, must-revalidate" );
        		header("Pragma: no-cache" );
        		header("Content-Type: text/xml");
        		 
        		$xml = '<?xml version="1.0" encoding="utf-8"?>\n';
        		$xml .= '<response>\n';
        		$xml .= '<result>\n';
        		$xml .= '<query>1</query>\n';
        		$xml .= '<rows>1</rows>\n';
        		$xml .= '</result>\n';
        		$xml .= '</response>';
        		return $xml;
        	}
}

?>