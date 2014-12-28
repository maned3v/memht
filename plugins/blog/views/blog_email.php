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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="<?php echo $site_url; ?>/libraries/MemHT/style/common.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $site_url; ?>/libraries/MemHT/style/email.css" type="text/css" />
        <base href="<?php echo $site_url; ?>/" />
        <title><?php echo $title; ?></title>
    </head>
    <body>
<?php

if ($status=="form") {
	//Use this form to email the post to a friend
	
	global $form,$User;
	
	$form = new Form();
	$form->action = "index.php?"._NODE."="._PLUGIN."&amp;op=email";
	
	$form->Open();
	
	//ToName
	$form->AddElement(array("element"	=>"text",
							"label"		=>_t("TONAME"),
							"name"		=>"toname",
							"info"		=>_t("REQUIRED")));
	//ToEmail
	$form->AddElement(array("element"	=>"text",
							"label"		=>_t("TOEMAIL"),
							"name"		=>"toemail",
							"info"		=>_t("REQUIRED")));		
	//FromName
	$form->AddElement(array("element"	=>"text",
							"label"		=>_t("FROMNAME"),
							"name"		=>"fromname",
							"value"		=>($User->IsUser() ? $User->Name(false,true) : ""),
							"info"		=>_t("REQUIRED")));
	///FromEmail			
	$form->AddElement(array("element"	=>"text",
							"label"		=>_t("FROMEMAIL"),
							"name"		=>"fromemail",
							"value"		=>($User->IsUser() ? $User->GetInfo('email') : ""),
							"info"		=>_t("REQUIRED")));
	//Message
	$form->AddElement(array("element"	=>"textarea",
							"label"		=>_t("MESSAGE"),
							"name"		=>"message",
							"height"	=>"100px",
							"info"		=>_t("REQUIRED")));		
	//Submit & Reset
	$form->AddElement(array("element"	=>"submit_and_reset",
							//Submit
							"s_name"	=>"submit",
							"s_id"		=>"id10",
							"s_value"	=>"Submit",
							"captcha"	=>true,
							//Reset
							"r_name"	=>"reset",
							"r_id"		=>"id11",
							"r_value"	=>"Reset"));
			
	$form->Hidden('status','send');
	$form->Hidden('post',$post);
	
	$form->Close();
}

?>
    </body>
</html>