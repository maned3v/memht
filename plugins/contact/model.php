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
 * @copyright	Copyright (C) 2008-2012 Miltenovikj Manojlo. All rights reserved.
 * @license     GNU/GPLv2 http://www.gnu.org/licenses/
 */

//Deny direct access
defined("_LOAD") or die("Access denied");

class contactModel extends Views {
	public function _index() {
		global $Router;

		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		$form = new Form();
		$form->action = "index.php?"._NODE."="._PLUGIN."&amp;op=send";
		$form->Open();
		
  		$forms = Utils::Unserialize($Router->GetOption("forms"));
  		if (!$forms) {
			$forms = Utils::Unserialize('a:1:{s:5:"forms";a:5:{i:0;a:6:{s:7:"element";s:4:"text";s:5:"label";s:4:"Name";s:4:"name";s:4:"name";s:5:"width";s:5:"250px";s:8:"required";i:1;s:4:"info";s:8:"Required";}i:1;a:6:{s:7:"element";s:4:"text";s:5:"label";s:5:"Email";s:4:"name";s:5:"email";s:5:"width";s:5:"250px";s:8:"required";s:5:"email";s:4:"info";s:8:"Required";}i:2;a:6:{s:7:"element";s:4:"text";s:5:"label";s:7:"Subject";s:4:"name";s:7:"subject";s:5:"width";s:5:"300px";s:8:"required";i:1;s:4:"info";s:8:"Required";}i:3;a:6:{s:7:"element";s:8:"textarea";s:5:"label";s:7:"Message";s:4:"name";s:7:"message";s:5:"class";s:6:"simple";s:8:"required";i:1;s:4:"info";s:8:"Required";}i:4;a:5:{s:7:"element";s:6:"submit";s:4:"name";s:6:"submit";s:2:"id";s:3:"id8";s:7:"captcha";b:1;s:5:"value";s:6:"Submit";}}}');
			$forms = $forms['forms'];
		}
		foreach ($forms as $element) $form->AddElement($element);

		$form->Close();

		//Assign captured content to the template engine and clean buffer
		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,
											 "showtitle"=>_PLUGIN_SHOWTITLE,
											 "url"=>"index.php?"._NODE."="._PLUGIN,
											 "content"=>Utils::GetBufferContent("clean"),
											 "before"=>_PLUGIN_BEFORE,
											 "after"=>_PLUGIN_AFTER));
		//Draw site template
		Template::Draw();
		//Initialize and show site footer
		Layout::Footer();
	}

	public function _send() {
		global $config_sys,$Db,$User,$Router;

		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		if (Utils::CheckToken()===true) {
			if (Captcha::Check()===true) {
				$sep = ($config_sys['cnt_email_or_notify']=="email") ? "\n" : "<br />" ;
				$forms = Utils::Unserialize($Router->GetOption("forms"));
				if (!$forms) {
					$forms = Utils::Unserialize('a:1:{s:5:"forms";a:5:{i:0;a:6:{s:7:"element";s:4:"text";s:5:"label";s:4:"Name";s:4:"name";s:4:"name";s:5:"width";s:5:"250px";s:8:"required";i:1;s:4:"info";s:8:"Required";}i:1;a:6:{s:7:"element";s:4:"text";s:5:"label";s:5:"Email";s:4:"name";s:5:"email";s:5:"width";s:5:"250px";s:8:"required";s:5:"email";s:4:"info";s:8:"Required";}i:2;a:6:{s:7:"element";s:4:"text";s:5:"label";s:7:"Subject";s:4:"name";s:7:"subject";s:5:"width";s:5:"300px";s:8:"required";i:1;s:4:"info";s:8:"Required";}i:3;a:6:{s:7:"element";s:8:"textarea";s:5:"label";s:7:"Message";s:4:"name";s:7:"message";s:5:"class";s:6:"simple";s:8:"required";i:1;s:4:"info";s:8:"Required";}i:4;a:5:{s:7:"element";s:6:"submit";s:4:"name";s:6:"submit";s:2:"id";s:3:"id8";s:7:"captcha";b:1;s:5:"value";s:6:"Submit";}}}');
					$forms = $forms['forms'];
				}
				$proceed = true;
				$messagearr = array();
				foreach ($forms as $element) {
					${$element['name']} = Io::GetVar('POST',$element['name'],'fullhtml');
					if (MB::strtolower($element['name'])=="submit") break;
										
					if (isset($element['required'])) {
						switch(MB::strtolower($element['required'])) {
							case "email":
								if (!Utils::ValidEmail(${$element['name']})) {
									$proceed = false;
									$msg = _t("THE_FIELD_X_CONTAINS_INVALID_Y",$element['label'],MB::strtolower(_t('EMAIL')));
								}
								break;
							case "url":
								if (!Utils::ValidUrl(${$element['name']})) {
									$proceed = false;
									$msg = _t("THE_FIELD_X_CONTAINS_INVALID_Y",$element['label'],MB::strtolower(_t('URL')));
								}
								break;
							default:
								if (empty(${$element['name']})) {
									$proceed = false;
									$msg = _t("THE_FIELD_X_IS_REQUIRED",$element['label']);
								}
								break;
						}
					}
					$messagearr[] = Io::Filter($element['label']).":$sep".Io::Filter(${$element['name']});
				}
	
				if (sizeof($messagearr) && $proceed) {
	
					$messagearr = implode($sep.$sep,$messagearr);
					$messagearr .= $sep.$sep."Ip: ".$User->Ip();
					$messagearr .= $sep."Date: "._GMT_DATETIME;
					
					if ($config_sys['cnt_email_or_notify']=="email") {
						$Email = new Email();
						$Email->AddEmail($config_sys['site_email'],$config_sys['site_name']);
						$Email->SetFrom($email,$name);
						$Email->SetSubject($subject);
						$Email->SetContent($messagearr);
						$result = $Email->Send();
					} else {
						$result = $Db->Query("INSERT INTO #__log (label,message,ip,time,uniqueid)
											  VALUES ('contact_message','".$Db->_e(Io::Filter($messagearr))."','".$Db->_e(Utils::Ip2Num($User->Ip()))."',NOW(),'".$Db->_e(Utils::GenerateRandomString())."')");
					}
	
					if ($result) {
						Error::Trigger("INFO",_t('MESSAGE_SENT'));
					} else {
						$details = ($config_sys['cnt_email_or_notify']=="email") ? "<br />Details: ".implode(",",$Email->GetErrors()) : "" ;
						Error::StoreLog("error_sys","Message: Message not sent<br />File: ".__FILE__."<br />Line: ".__LINE__.$details);
						Error::Trigger("USERERROR",_t('MESSAGE_NOT_SENT'),($config_sys['cnt_email_or_notify']=="email") ? implode(",",$Email->GetErrors()) : false);
					}
				} else {
					Error::Trigger("USERERROR",$msg);
				}
			}
		} else {
			Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
		}

		//Assign captured content to the template engine and clean buffer
		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,
											 "showtitle"=>_PLUGIN_SHOWTITLE,
											 "url"=>"index.php?"._NODE."="._PLUGIN,
											 "content"=>Utils::GetBufferContent("clean"),
											 "before"=>_PLUGIN_BEFORE,
											 "after"=>_PLUGIN_AFTER));
		//Draw site template
		Template::Draw();
		//Initialize and show site footer
		Layout::Footer();
	}
}

?>