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

class userModel extends Views {
	public function Main() {
		global $Db,$User,$config_sys,$Ext;
		
		//Load plugin language
		Language::LoadPluginFile(_PLUGIN_CONTROLLER);
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		if ($User->IsUser()===true) {
			//Logged in
			echo $User->DisplayAvatar()."<br />";
			echo _t("HI_X",$User->Name())." (".$User->GetRoleName().")";
			echo "<br /><br /><a href='index.php?"._NODE."="._PLUGIN."&amp;op=profile' title='"._t("CHANGE_X",MB::strtolower(_t("PROFILE")))."'>"._t("CHANGE_X",MB::strtolower(_t("PROFILE")))."</a>";
			echo "<br /><br /><a href='index.php?"._NODE."="._PLUGIN."&amp;op=logout' title='"._t("LOGOUT")."'>"._t("LOGOUT")."</a>";
			
			$Ext->RunMext("UserProfile");
		} else {
			//Guest
			$form = new Form();
			$form->action = "index.php?"._NODE."="._PLUGIN."&amp;op=login";
			
			$form->Open();
			
			//Text
			$form->AddElement(array("element"	=>"text",
									"label"		=>_t("USERNAME"),
									"name"		=>"username"));
			
			//Password
			$form->AddElement(array("element"	=>"text",
									"label"		=>_t("PASSWORD"),
									"name"		=>"password",
									"password"	=>true));
			//Remember
			$form->AddElement(array("element"	=>"checkbox",
									"label"		=>_t("REMEMBERME"),
									"name"		=>"remember",
									"value"		=>"1",
									"checked"	=>true));
			//Submit
			$form->AddElement(array("element"	=>"submit",
									"name"		=>"submit",
									"captcha"	=>true,
									"value"		=>_t("LOGIN")));
			
			$form->Close();
			
			echo "<div><a href='index.php?"._NODE."="._PLUGIN."&amp;op=lostpass' title='"._t("LOSTPASS")."'>"._t("LOSTPASS")."</a> | <a href='index.php?"._NODE."="._PLUGIN."&amp;op=register' title='"._t("REGISTER")."'>"._t("REGISTER")."</a></div>\n";
			
			if ($config_sys['social_login']) {
				echo "<div style='padding-top:20px;'></div>";
				
				$google = Utils::GetComOption("social_signin","google_api_client_id",false);
				$facebook = Utils::GetComOption("social_signin","fb_app_id",false);
				
				if ($facebook) {
					echo "<a href='index.php?"._NODE."="._PLUGIN."&op=social&engine=facebook' title='"._t("SOCIAL_LOGIN_WITH_FACEBOOK")."'><img src='images"._DS."social"._DS."facebook_32.png' /></a>";
				}
				echo "<br />";
				if ($google) {
					echo "<a href='index.php?"._NODE."="._PLUGIN."&op=social&engine=google' title='"._t("SOCIAL_LOGIN_WITH_GOOGLE")."'><img src='images"._DS."social"._DS."google_32.png' /></a>";
				}
			}
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
	
	public function SocialLogin() {
		global $Db,$User,$config_sys,$Ext,$Visitor;
	
		if ($User->IsUser()===true || !$config_sys['social_login']) {
			Utils::Redirect("index.php?"._NODE."="._PLUGIN);
			exit;
		}
		
		switch (Io::GetVar("GET","engine")) {
			default:
			case "google":
				require_once(_PATH_LIBRARIES._DS."MemHT"._DS."social"._DS."google"._DS."Google_Client.php");
				require_once(_PATH_LIBRARIES._DS."MemHT"._DS."social"._DS."google"._DS."contrib"._DS."Google_Oauth2Service.php");
				
				Session::Start();
				
				$client = new Google_Client();
				$client->setApplicationName(Utils::GetComOption("social_signin","google_api_app_name","Google Connect"));
				$client->setClientId(Utils::GetComOption("social_signin","google_api_client_id",""));
				$client->SetClientSecret(Utils::GetComOption("social_signin","google_api_client_secret",""));
				$client->SetRedirectUri(RewriteUrl("index.php?"._NODE."="._PLUGIN."&op=social&engine=google"));
				$client->SetDeveloperKey(Utils::GetComOption("social_signin","google_api_ky",""));
				$client->setApprovalPrompt(false);
								
				$google = new Google_Oauth2Service($client);
				
				//Code is the temporary code returned by Google
				if (isset($_GET['code'])) {
					try {
						//Get access token from Google
						$client->authenticate();
						Io::SetSession('token', $client->getAccessToken());
					} catch (Exception $e) {
					}
				}
				
				if (isset($_SESSION['token'])) {
					try {
						$client->setAccessToken(Io::GetSession('token'));
					} catch (Exception $e) {
					}
				}
				
				if ($client->getAccessToken()) {
					try {
						$profile = $google->userinfo->get();
					} catch (Exception $e) {
					}
					
					Io::SetSession('token', $client->getAccessToken());
					
					if (isset($profile['id'])) {
						if ($row = $Db->GetRow("SELECT * FROM #__user WHERE oauth_provider='google' AND oauth_uid='".$Db->_e($profile['id'])."' LIMIT 1")) {
							//Linked user already exists, log in
						
							//Get user data from db
							$userdata = array();
							$userdata['uid'] = Io::Output($row['uid']);
							$userdata['user'] = Io::Output($row['user']);
							$userdata['pass'] = Io::Output($row['pass']);
							$userdata['name'] = strip_tags(Io::Output($row['name']));
							$userdata['email'] = Io::Output($row['email']);
							$userdata['regdate'] = Io::Output($row['regdate']);
							$userdata['options'] = Io::Output($row['options']);
							$userdata['lastseen'] = Io::Output($row['lastseen']);
							$userdata['lastip'] = Utils::Num2Ip(Io::Output($row['lastip']));
								
							//Get roles
							$preroles = Ram::Get("roles");
							$userdata['roles'] = array("REGISTERED"=>$preroles['REGISTERED']);
							$roles = Utils::Unserialize(Io::Output($row['roles']));
							if (isset($roles[0])) foreach ($roles as $role) $userdata['roles'][$role] = $preroles[$role];
							//Store user roles in ram
							Ram::Set("useroles_".$userdata['uid'],$userdata['roles']);
								
							//Preferred role
							$options = Utils::Unserialize(Io::Output($row['options']));
							$prefrole = ((isset($options['prefrole']))) ? $options['prefrole'] : "REGISTERED" ;
							//Store preferred role in ram
							Ram::Set("userprefrole_".$userdata['uid'],$prefrole);
								
							$salt = intval(mt_rand(1000000,9999999));
							$Db->Query("UPDATE #__user SET lastseen=NOW(),lastip='".$Db->_e(Utils::Ip2num($User->Ip()))."',cookiesalt='".intval($salt)."' WHERE uid=".intval($userdata['uid']));
						
							//Create cookie
							$cookie_expire = time()+(86400*$config_sys['login_cookie_expire']) ;
							$cookie_value = $userdata['uid']."_".md5($userdata['user'].$userdata['pass'].$salt);
							setcookie("user",$cookie_value,$cookie_expire,_COOKIEPATH);
							Utils::Redirect(RewriteUrl("index.php?"._NODE."="._PLUGIN));
						
						} else if (isset($profile['email']) && $row = $Db->GetRow("SELECT * FROM #__user WHERE email='".$Db->_e($profile['email'])."' LIMIT 1")) {
							//There are no linked users, but someone used the same email, link data or create a new user?
						
							//Load plugin language
							Language::LoadPluginFile(_PLUGIN_CONTROLLER);
							//Initialize and show site header
							Layout::Header();
							//Start buffering content
							Utils::StartBuffering();
						
							Error::Trigger("INFO",_t("ACCOUNT_SOCIAL_EMAIL_EXISTS_X",$profile['name']),_t("ACCOUNT_SOCIAL_EMAIL_DETAILS_X_Y",Io::Output($row['name']),Io::Output($row['email'])));
						
						} else if (isset($profile['email'])) {
							//Create the new user
						
							//Check if the user already exists
							$username = (isset($profile['given_name'])) ? $profile['given_name'] : $profile['id'] ;
							$password = Utils::GenerateRandomString(10);
						
							if (strlen($username) < 3 || $Db->GetRow("SELECT uid FROM #__user WHERE user='".$Db->_e($username)."' LIMIT 1")) {
								//User already taken
								$username = $profile['id'];
							}
						
							//Get avatar
							$options = array();
							try {
								$data =  @file_get_contents($profile['picture']);
								if ($data) {
									//The image has been downloaded...
									$img = @imagecreatefromstring($data);
									if ($img) {
										//...and it is valid
										$new_w = Utils::GetComOption("social_signin","avatar_width",90);
										$new_h = Utils::GetComOption("social_signin","avatar_height",90);
											
										$filename = Utils::GenerateRandomString(10);
											
										$new_img = @imagecreatetruecolor($new_w,$new_h);
										@imagecopyresampled($new_img,$img,0,0,0,0,$new_w,$new_h,100,100);
										@imagejpeg($new_img,"assets/avatars/ggl".$filename.".jpg",100);
										@imagedestroy($new_img);
										$options["avatar"] = array("selector"=>"MemHT","value"=>"ggl".$filename.".jpg");
									}
								}
							} catch (Exception $e) {
								$options = array();
							}
							$options = Utils::Serialize($options);
						
							$Db->Query("INSERT INTO #__user (user,name,pass,email,regdate,lastip,options,oauth_provider,oauth_uid,status)
									VALUES ('".$Db->_e($username)."','".$Db->_e($profile['name'])."','".md5($password)."','".$Db->_e($profile['email'])."',NOW(),
									'".$Db->_e(Utils::Ip2num($Visitor['ip']))."','".$Db->_e($options)."','google','".$Db->_e($profile['id'])."','active')");
						
							$uid = $Db->InsertId();
							$Ext->RunMext("UserRegFormSaveData_Social",array($uid,$profile['name'],$username,$profile['email']));
						
							$loginlink = RewriteUrl($config_sys['site_url']._DS."index.php?"._NODE."="._PLUGIN);
							$message = _t("EMAIL_ACCT_SOCIAL_ACTIVATED_TEXT",$profile['name'],$config_sys['site_name'],$username,$password,$loginlink,$config_sys['site_name']);
								
							$Email = new Email();
							$Email->AddEmail($profile['email'],$profile['name']);
							$Email->SetFrom($config_sys['site_email'],$config_sys['site_name']);
							$Email->SetSubject(_t("ACCOUNT_ACTIVATED_AT_X",$config_sys['site_name']));
							$Email->SetContent($message);
							$result = $Email->Send();
						
							//Load plugin language
							Language::LoadPluginFile(_PLUGIN_CONTROLLER);
							//Initialize and show site header
							Layout::Header();
							//Start buffering content
							Utils::StartBuffering();
								
							Error::Trigger("INFO",_t('ACCOUNT_ACTIVATED'),_t("ACCOUNT_LOGIN_DETAILS_X_Y_Z",$username,$password,RewriteUrl("index.php?"._NODE."="._PLUGIN."&op=social&engine=google")));
						
							if (!$result) {
								Error::StoreLog("error_sys","Message: Social account activation email not sent<br />User: [$uid] $username (".$profile['email'].")<br />File: ".__FILE__."<br />Line: ".__LINE__."<br />Details: ".implode(",",$Email->GetErrors()));
							}
						} else {
							//Can't create the new user without an email
							Utils::Redirect(RewriteUrl("index.php?"._NODE."="._PLUGIN));
						}
					} else {
						//Couldn't sign in
						Utils::Redirect(RewriteUrl("index.php?"._NODE."="._PLUGIN));
					}
				} else {
					Utils::Redirect($client->createAuthUrl());
				}
				break;
			case "facebook":
				require_once(_PATH_LIBRARIES._DS."MemHT"._DS."social"._DS."facebook"._DS."facebook.php");
				
				$facebook = new Facebook(array(
					'appId'			=> Utils::GetComOption("social_signin","fb_app_id",""),
					'secret'		=> Utils::GetComOption("social_signin","fb_app_secret",""),
					'fileUpload'	=> false
				));
				
				// Get User ID
				$fbuser = $facebook->getUser();
				
				if ($fbuser) {
					try {
						// Proceed knowing you have a logged in user who's authenticated.
						$user_profile = $facebook->api('/me');
					} catch (FacebookApiException $e) {
						error_log($e);
						$fbuser = null;
						$user_profile = array();
					}
				}
				
				if ($fbuser) {
					//$logoutUrl = $facebook->getLogoutUrl(array('next'=>RewriteUrl("index.php?"._NODE."="._PLUGIN)));
					//Utils::Debug($user_profile);
					
					if ($row = $Db->GetRow("SELECT * FROM #__user WHERE oauth_provider='facebook' AND oauth_uid='".$Db->_e($fbuser)."' LIMIT 1")) {
						//Linked user already exists, log in
						
						//Get user data from db
						$userdata = array();
						$userdata['uid'] = Io::Output($row['uid']);
						$userdata['user'] = Io::Output($row['user']);
						$userdata['pass'] = Io::Output($row['pass']);
						$userdata['name'] = strip_tags(Io::Output($row['name']));
						$userdata['email'] = Io::Output($row['email']);
						$userdata['regdate'] = Io::Output($row['regdate']);
						$userdata['options'] = Io::Output($row['options']);
						$userdata['lastseen'] = Io::Output($row['lastseen']);
						$userdata['lastip'] = Utils::Num2Ip(Io::Output($row['lastip']));
							
						//Get roles
						$preroles = Ram::Get("roles");
						$userdata['roles'] = array("REGISTERED"=>$preroles['REGISTERED']);
						$roles = Utils::Unserialize(Io::Output($row['roles']));
						if (isset($roles[0])) foreach ($roles as $role) $userdata['roles'][$role] = $preroles[$role];
						//Store user roles in ram
						Ram::Set("useroles_".$userdata['uid'],$userdata['roles']);
							
						//Preferred role
						$options = Utils::Unserialize(Io::Output($row['options']));
						$prefrole = ((isset($options['prefrole']))) ? $options['prefrole'] : "REGISTERED" ;
						//Store preferred role in ram
						Ram::Set("userprefrole_".$userdata['uid'],$prefrole);
							
						$salt = intval(mt_rand(1000000,9999999));
						$Db->Query("UPDATE #__user SET lastseen=NOW(),lastip='".$Db->_e(Utils::Ip2num($User->Ip()))."',cookiesalt='".intval($salt)."' WHERE uid=".intval($userdata['uid']));
						
						//Create cookie
						$cookie_expire = time()+(86400*$config_sys['login_cookie_expire']) ;
						$cookie_value = $userdata['uid']."_".md5($userdata['user'].$userdata['pass'].$salt);
						setcookie("user",$cookie_value,$cookie_expire,_COOKIEPATH);
						Utils::Redirect(RewriteUrl("index.php?"._NODE."="._PLUGIN));
						
					} else if (isset($user_profile['email']) && $row = $Db->GetRow("SELECT * FROM #__user WHERE email='".$Db->_e($user_profile['email'])."' LIMIT 1")) {
						//There are no linked users, but someone used the same email, link data or create a new user?
						
						//Load plugin language
						Language::LoadPluginFile(_PLUGIN_CONTROLLER);
						//Initialize and show site header
						Layout::Header();
						//Start buffering content
						Utils::StartBuffering();
						
						Error::Trigger("INFO",_t("ACCOUNT_SOCIAL_EMAIL_EXISTS_X",$user_profile['name']),_t("ACCOUNT_SOCIAL_EMAIL_DETAILS_X_Y",Io::Output($row['name']),Io::Output($row['email'])));
						
					} else if (isset($user_profile['email'])) {
						//Create the new user
						
						//Check if the user already exists
						$username = (isset($user_profile['username'])) ? $user_profile['username'] : (($user_profile['first_name']) ? $user_profile['first_name'] : $user_profile['id'] ) ;
						$password = Utils::GenerateRandomString(10);
						
						if (strlen($username) < 3 || $Db->GetRow("SELECT uid FROM #__user WHERE user='".$Db->_e($username)."' LIMIT 1")) {
							//User already taken
							$username = $user_profile['id'];
						}
						
						//Get avatar
						//http://graph.facebook.com/USERID/picture?type=normal
						$options = array();
						try {
							$data =  @file_get_contents("http://graph.facebook.com/".$fbuser."/picture?type=normal");
							if ($data) {
								//The image has been downloaded...
								$img = @imagecreatefromstring($data);
								if ($img) {
									//...and it is valid
									$new_w = Utils::GetComOption("social_signin","avatar_width",90);
									$new_h = Utils::GetComOption("social_signin","avatar_height",90);
									
									$filename = Utils::GenerateRandomString(10);
							
									$new_img = @imagecreatetruecolor($new_w,$new_h);
									@imagecopyresampled($new_img,$img,0,0,0,0,$new_w,$new_h,100,100);
									@imagejpeg($new_img,"assets/avatars/fb".$filename.".jpg",100);
									@imagedestroy($new_img);
									$options["avatar"] = array("selector"=>"MemHT","value"=>"fb".$filename.".jpg");
								}
							}
						} catch (Exception $e) {
							$options = array();
						}
						$options = Utils::Serialize($options);
						
						$Db->Query("INSERT INTO #__user (user,name,pass,email,regdate,lastip,options,oauth_provider,oauth_uid,status)
									VALUES ('".$Db->_e($username)."','".$Db->_e($user_profile['name'])."','".md5($password)."','".$Db->_e($user_profile['email'])."',NOW(),
											'".$Db->_e(Utils::Ip2num($Visitor['ip']))."','".$Db->_e($options)."','facebook','".$Db->_e($fbuser)."','active')");
						
						$uid = $Db->InsertId();
						$Ext->RunMext("UserRegFormSaveData_Social",array($uid,$user_profile['name'],$username,$user_profile['email']));
						
						$loginlink = RewriteUrl($config_sys['site_url']._DS."index.php?"._NODE."="._PLUGIN);
						$message = _t("EMAIL_ACCT_SOCIAL_ACTIVATED_TEXT",$user_profile['name'],$config_sys['site_name'],$username,$password,$loginlink,$config_sys['site_name']);
					
						$Email = new Email();
						$Email->AddEmail($user_profile['email'],$user_profile['name']);
						$Email->SetFrom($config_sys['site_email'],$config_sys['site_name']);
						$Email->SetSubject(_t("ACCOUNT_ACTIVATED_AT_X",$config_sys['site_name']));
						$Email->SetContent($message);
						$result = $Email->Send();
						
						//Load plugin language
						Language::LoadPluginFile(_PLUGIN_CONTROLLER);
						//Initialize and show site header
						Layout::Header();
						//Start buffering content
						Utils::StartBuffering();
					
						Error::Trigger("INFO",_t('ACCOUNT_ACTIVATED'),_t("ACCOUNT_LOGIN_DETAILS_X_Y_Z",$username,$password,RewriteUrl("index.php?"._NODE."="._PLUGIN."&op=social&engine=facebook")));
						
						if (!$result) {
							Error::StoreLog("error_sys","Message: Social account activation email not sent<br />User: [$uid] $username (".$user_profile['email'].")<br />File: ".__FILE__."<br />Line: ".__LINE__."<br />Details: ".implode(",",$Email->GetErrors()));
						}
					} else {
						//Can't create the new user without an email
						Utils::Redirect(RewriteUrl("index.php?"._NODE."="._PLUGIN));
					}
				} else {
					//Login
					$loginUrl = $facebook->getLoginUrl(array('scope'		=> 'email',
															 'redirect_uri'	=> RewriteUrl("index.php?"._NODE."="._PLUGIN."&op=social&engine=facebook")));
					
					$error = Io::GetVar('GET','error',false,true,false);
					if ($error===false) {
						Utils::Redirect($loginUrl);
					} else {
						//The user logged in but refused asked permissions, leaving login loop
						Utils::Redirect(RewriteUrl("index.php?"._NODE."="._PLUGIN));
					}
				}
				
				break;
			case "twitter":
				break;
			case "openid":
				break;
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
	
	public function LoginCheck() {
		global $Db,$User;
		
		$errors = array();
		if (Utils::CheckToken()===true) {
			$captchares = Captcha::Check(true);
			if ($captchares===true) {
				if ($User->Authenticate()) {
					//TODO: Redirect on the requested page? Buffer?
					Utils::Redirect("index.php?"._NODE."="._PLUGIN);
				} else $errors[] = $User->GetError();
			} else $errors[] = $captchares;
		} else $errors[] = _t("INVALID_TOKEN");
		
		if (sizeof($errors)) {
			//Load plugin language
			Language::LoadPluginFile(_PLUGIN_CONTROLLER);
			//Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();
			
			Error::Trigger("USERERROR",implode("<br />",$errors));
			
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
	
	public function LoginDestroy() {
		global $Db,$User,$config_sys;
		
		$User->Logout();
		Utils::Redirect($config_sys['site_url']);
	}
	
	public function RegisterForm() {
		global $Db,$config_sys,$Visitor,$Ext;
		
		//Load plugin language
		Language::LoadPluginFile(_PLUGIN_CONTROLLER);
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		if ($config_sys['user_signup']) {
			switch (Io::GetVar("GET","subop")) {
				default:
					$form = new Form();
					$form->action = "index.php?"._NODE."="._PLUGIN."&amp;op=register&amp;subop=proceed";
					
					$form->Open();
					
					//Display name
					$form->AddElement(array("element"	=>"text",
											"label"		=>_t("DISPLAY_NAME"),
											"name"		=>"displayname"));
					
					//Username
					$form->AddElement(array("element"	=>"text",
											"label"		=>_t("USERNAME"),
											"name"		=>"username",
											"info"		=>_t("4CHARSMIN_LETTERS_NUM_ONLY")));
					
					//Password
					$form->AddElement(array("element"	=>"text",
											"label"		=>_t("PASSWORD"),
											"name"		=>"password",
											"password"	=>true,
											"info"		=>_t("LETTERS_NUM_SPECIAL_ACCEPT")));
					
					//Password confirm
					$form->AddElement(array("element"	=>"text",
											"label"		=>_t("CONFIRM_X",MB::strtolower(_t("PASSWORD"))),
											"name"		=>"cpassword",
											"password"	=>true));
					
					//Email
					$form->AddElement(array("element"	=>"text",
											"label"		=>_t("EMAIL"),
											"name"		=>"email"));
					
					if ($config_sys['user_signup_invite']) {
						//Invitation code
						$form->AddElement(array("element"	=>"text",
												"label"		=>_t("INVITATION_CODE"),
												"name"		=>"invitecode",
												"info"		=>_t("REQUIRED")));
					}
					
					$Ext->RunMext("UserRegForm");
					
					//Submit
					$form->AddElement(array("element"	=>"submit",
											"name"		=>"submit",
											"captcha"	=>true,
											"value"		=>_t("REGISTER")));
					
					$form->Close();
					break;
					
				case "proceed":
					if (Utils::CheckToken()===true) {
						if (Captcha::Check()===true) {
							$displayname = Io::GetVar('POST','displayname');
							$username = Io::GetVar('POST','username');
							$password = Io::GetVar('POST','password');
							$cpassword = Io::GetVar('POST','cpassword');
							$email = Io::GetVar('POST','email');
							if ($config_sys['user_signup_invite']) {
								$invitecode = Io::GetVar('POST','invitecode');
							}
							
							$Ext->RunMext("UserRegFormGetData");

							$errors = array();
							if (empty($username))	$errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("USERNAME"));
							if (empty($password))	$errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("PASSWORD"));
							if (empty($cpassword))	$errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("CONFIRM_X",MB::strtolower(_t("PASSWORD"))));
							if (empty($email))		$errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("EMAIL"));
							if ($config_sys['user_signup_invite'] && !sizeof($errors)) {
								if (empty($invitecode))	$errors[] = _t("REG_NEWACCOUNTS_INVITATIONONLY");
								if (!sizeof($errors)) {
									if (!$Db->GetNum("SELECT id FROM #__user_invites WHERE code='".$Db->_e($invitecode)."' AND registrations>0 AND expiration > NOW()")) $errors[] = _t("REG_NEWACCOUNTS_INVITECODERR");
								}
							}

							if (!sizeof($errors)) {
								if (!preg_match("#^[a-zA-Z0-9]{4,}$#is",$username)) $errors[] = _t("THE_FIELD_X_IS_NOT_INVALID",MB::strtolower(_t("USERNAME")));
								if (!Utils::ValidEmail($email)) $errors[] = _t("THE_FIELD_X_IS_NOT_INVALID",MB::strtolower(_t("EMAIL")));
								if ($password!=$cpassword) $errors[] = _t("PASS_DONT_MATCH");
								if ($row = $Db->GetRow("SELECT uid FROM #__user WHERE user='".$Db->_e($username)."' OR email='".$Db->_e($email)."'")) {
									$errors[] = _t("USER_OR_EMAIL_ALREADY_EXIST");
								}
							}

							if (!sizeof($errors)) {
								$status = ($config_sys['user_signup_moderate']) ? "moderate" : "active" ;
								$status = ($config_sys['user_signup_confirm']) ? "waiting" : $status ;
								$code = ($config_sys['user_signup_confirm']) ? Utils::GenerateRandomString(10) : "" ;
								$Db->Query("INSERT INTO #__user (uid,user,name,pass,email,regdate,lastip,code,status)
											VALUES (null,'".$Db->_e($username)."','".$Db->_e($displayname)."','".md5($password)."','".$Db->_e($email)."',NOW(),'".$Db->_e(Utils::Ip2num($Visitor['ip']))."','".$Db->_e($code)."','$status')");

								$uid = $Db->InsertId();
								$Ext->RunMext("UserRegFormSaveData",array($uid,$displayname,$username,$email));
								
								if ($config_sys['user_signup_confirm']) {
									$actlink = RewriteUrl($config_sys['site_url']._DS."index.php?"._NODE."="._PLUGIN."&op=activate&uid=$uid&code=$code");
									$message = _t("EMAIL_ACTIVATION_TEXT",$displayname,$config_sys['site_name'],$actlink,$config_sys['site_name'],$config_sys['site_name']);

									$Email = new Email();
									$Email->AddEmail($email,$displayname);
									$Email->SetFrom($config_sys['site_email'],$config_sys['site_name']);
									$Email->SetSubject(_t("ACTIVATE_ACCOUNT_AT_X",$config_sys['site_name']));
									$Email->SetContent($message);
									$result = $Email->Send();

									if ($result) {
										Error::Trigger("INFO",_t('YOU_RECEIVE_ACT_LINK_ACCOUNT_EXPIRE_IN_X',48));
									} else {
										Error::StoreLog("error_sys","Message: Activation email not sent<br />User: [$uid] $username ($email)<br />File: ".__FILE__."<br />Line: ".__LINE__."<br />Details: ".implode(",",$Email->GetErrors()));
										Error::Trigger("INFO",_t("REG_SUC_TECH_PROB_CONTACT_ADMIN_ACC_ACTIVATED"),implode("<br />",$Email->GetErrors()));
									}
								} else if ($config_sys['user_signup_moderate']) {
									Error::Trigger("INFO",_t("ACCOUNT_ACTIVED_SOON_BYADMIN"));
								} else {
									Error::Trigger("INFO",_t("ACCOUNT_ACTIVED_NOW_UCAN_LOGIN"));
								}
								
								if ($config_sys['user_signup_invite']) {
									$Db->Query("UPDATE #__user_invites SET registrations=registrations-1 WHERE code='".$Db->_e($invitecode)."'");
								}
							} else {
								Error::Trigger("USERERROR",implode("<br />",$errors));
							}
						}
					} else {
						Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
					}

					break;
			}
		} else {
			Error::Trigger("INFO",_t("REG_NEWACCOUNTS_CLOSED"));
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
	
	public function AccountActivation() {
		global $Db,$config_sys;
		
		//Load plugin language
		Language::LoadPluginFile(_PLUGIN_CONTROLLER);
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		if ($config_sys['user_signup']) {
			$uid = Io::GetVar('GET','uid','int');
			$code = Io::GetVar('GET','code','[^a-zA-Z0-9]');
			
			if ($row = $Db->GetRow("SELECT code FROM #__user WHERE uid='".intval($uid)."' AND code='".$Db->_e($code)."'")) {
				if ($config_sys['user_signup_moderate']) {
					$Db->Query("UPDATE #__user SET status='moderate',code='' WHERE uid=".intval($uid));
					Error::Trigger("INFO",_t("X_CONFIRMED",_t("EMAIL")).". "._t("ACCOUNT_ACTIVED_SOON_BYADMIN"));
				} else {
					$Db->Query("UPDATE #__user SET status='active',code='' WHERE uid=".intval($uid));
					Error::Trigger("INFO",_t("ACCOUNT_ACTIVED_NOW_UCAN_LOGIN"));
				}
			} else {
				Error::Trigger("USERERROR",_t("CODE_WRONG_OR_ACCOUNT_EXPIRED"));
			}
		} else {
			Error::Trigger("INFO",_t("REG_NEWACCOUNTS_CLOSED"));
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
	
	public function ForgotPassword() {
		global $Db,$config_sys,$Visitor;
		
		//Load plugin language
		Language::LoadPluginFile(_PLUGIN_CONTROLLER);
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		switch (Io::GetVar("GET","subop")) {
			default:
				$form = new Form();
				$form->action = "index.php?"._NODE."="._PLUGIN."&amp;op=lostpass&amp;subop=send";

				$form->Open();

				//Email
				$form->AddElement(array("element"	=>"text",
										"label"		=>_t("EMAIL"),
										"name"		=>"email",
										"info"		=>_t("REQUIRED")));
				//Submit
				$form->AddElement(array("element"	=>"submit",
										"name"		=>"submit",
										"captcha"	=>true,
										"value"		=>_t("NEXT")));

				$form->Close();
				break;
			case "send":
				if (Utils::CheckToken()===true) {
					if (Captcha::Check()===true) {
						$errors = array();
						$email = Io::GetVar('POST','email');
						
						if (!Utils::ValidEmail($email)) $errors[] = _t("THE_FIELD_X_IS_NOT_INVALID",MB::strtolower(_t("EMAIL")));
						if (!sizeof($errors)) {
							if ($row = $Db->GetRow("SELECT uid,user,name FROM #__user WHERE email='".$Db->_e($email)."'")) {
								$uid	= Io::Output($row['uid'],"int");
								$user	= Io::Output($row['user']);
								$name	= Io::Output($row['name']);
								$code	= Utils::GenerateRandomString(10);
								
								$resetlink = RewriteUrl($config_sys['site_url']._DS."index.php?"._NODE."="._PLUGIN."&op=repass&uid=$uid&code=$code");
								$message = _t("EMAIL_RESET_TEXT",$name,$config_sys['site_name'],$resetlink,$config_sys['site_name']);

								$Email = new Email();
								$Email->AddEmail($email,$name);
								$Email->SetFrom($config_sys['site_email'],$config_sys['site_name']);
								$Email->SetSubject(_t("RESET_PASSWORD_AT_X",$config_sys['site_name']));
								$Email->SetContent($message);
								$result = $Email->Send();

								if ($result) {
									$Db->Query("UPDATE #__user SET code='".$Db->_e($code)."',lastip='".$Db->_e(Utils::Ip2num($Visitor['ip']))."' WHERE uid='".intval($uid)."'");
									Error::Trigger("INFO",_t('YOU_RECEIVE_RESET_LINK'));
								} else {
									Error::StoreLog("error_sys","Message: Reset email not sent<br />User: [$uid] $user ($email)<br />File: ".__FILE__."<br />Line: ".__LINE__."<br />Details: ".implode(",",$Email->GetErrors()));
									Error::Trigger("USERERROR",_t("ERROR_SENDING_MAIL_CONTACT_ADMIN"),implode("<br />",$Email->GetErrors()));
								}
							} else {
								Error::Trigger("USERERROR",_t("X_NOT_FOUND",_t("EMAIL")));
							}
						} else {
							Error::Trigger("USERERROR",implode("<br />",$errors));
						}
					}
				} else {
					Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
				}
				
				break;
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
	
	public function ResetPassword() {
		global $Db,$config_sys,$Visitor;
		
		//Load plugin language
		Language::LoadPluginFile(_PLUGIN_CONTROLLER);
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		switch (Io::GetVar("GET","subop")) {
			default:
				$errors = array();
				$uid = Io::GetVar('GET','uid','int');
				$code = Io::GetVar('GET','code');
				
				if (empty($uid) || empty($code))	$errors[] = _t("VERIF_DATA_MISSING");
				
				if (!sizeof($errors)) {
					if ($Db->GetRow("SELECT user,name FROM #__user WHERE uid='".intval($uid)."' AND code='".$Db->_e($code)."'")) {
						$form = new Form();
						$form->action = "index.php?"._NODE."="._PLUGIN."&amp;op=repass&amp;uid=$uid&amp;code=$code&amp;subop=reset";
						
						$form->Open();
						
						//Password
						$form->AddElement(array("element"	=>"text",
												"label"		=>_t("PASSWORD"),
												"name"		=>"password",
												"password"	=>true,
												"info"		=>_t("LETTERS_NUM_SPECIAL_ACCEPT")));
						
						//Password confirm
						$form->AddElement(array("element"	=>"text",
												"label"		=>_t("CONFIRM_X",MB::strtolower(_t("PASSWORD"))),
												"name"		=>"cpassword",
												"password"	=>true));
						
						//Submit
						$form->AddElement(array("element"	=>"submit",
												"name"		=>"submit",
												"captcha"	=>true,
												"value"		=>_t("SAVE")));
						
						$form->Close();
					} else {
						Error::Trigger("USERERROR",_t("CODE_WRONG"));
					}
				} else {
					Error::Trigger("USERERROR",implode("<br />",$errors));
				}
				break;
			case "reset":
				if (Utils::CheckToken()===true) {
					if (Captcha::Check()===true) {
						$errors = array();
						$uid = Io::GetVar('GET','uid','int');
						$code = Io::GetVar('GET','code');
						$password = Io::GetVar('POST','password');
						$cpassword = Io::GetVar('POST','cpassword');
						
						if (empty($password))	$errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("PASSWORD"));
						if (empty($cpassword))	$errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("CONFIRM_X",MB::strtolower(_t("PASSWORD"))));
						if ($password!=$cpassword) $errors[] = _t("PASS_DONT_MATCH");
						
						if (!sizeof($errors)) {
							if ($row = $Db->GetRow("SELECT user,name FROM #__user WHERE uid='".intval($uid)."' AND code='".$Db->_e($code)."'")) {
								$user = Io::Output($row['user']);
								$name = Io::Output($row['name']);
								$Db->Query("UPDATE #__user SET pass='".md5($password)."',code='' WHERE uid='".intval($uid)."'");
								
								Error::Trigger("INFO",_t("PASSWORD_CHANGED_USER_IS_X",$user));
							} else {
								Error::Trigger("USERERROR",_t("VERIF_DATA_MISSING"));
							}
						} else {
							Error::Trigger("USERERROR",implode("<br />",$errors));
						}
					}
				} else {
					Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
				}
				break;
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
	
	public function UserInfo() {
		global $Db,$Router,$User,$Ext;
		
		//Load plugin language
		Language::LoadPluginFile(_PLUGIN_CONTROLLER);
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		$uid = Io::GetVar("GET","uid","[^0-9]");
		if ($row = $Db->GetRow("SELECT uid,name,options FROM #__user WHERE uid='".intval($uid)."' AND status='active'")) {
			
			//Site title step
			Utils::AddTitleStep($row['name']);
			
			echo "<div style='float:left; width:35%;'>";
				echo $User->Name($row['uid']);
				echo "<br />";
				echo $User->GetRoleName($row['uid']);
				echo "<br /><br />";
				echo $User->DisplayAvatar($row['uid']);
			echo "</div>";
			
			echo "<div style='float:left; width:65%;'>";
				$options = Utils::Unserialize(Io::Output($row['options']));
				
				//Additional
				$result = $Db->GetList("SELECT label,name,type FROM #__user_profile");
				foreach ($result as $row) {
					$label = MB::strtolower(Io::Output($row['label']));
					$name = Io::Output($row['name']);
					$type = MB::strtolower(Io::Output($row['type']));
				
					if ($type!="text" && $type!="textarea") $type = "text";
				
					$value = (isset($options[$label])) ? $options[$label] : "";
				
					if (!empty($value)) {
						switch ($type) {
							case "text":
							case "textarea":
								echo "<div><strong>$name</strong>:</div><div style='margin-bottom:10px;'>$value</div>";
								break;
						}
					}
				}
			echo "</div>";
			
			$Ext->RunMext("UserInfo",array($uid,$row));
		} else {
			Error::Trigger("INFO",_t("X_NOT_FOUND_OR_INACTIVE",_t("USER")));
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
	
	public function ChangeProfile() {
		global $Db,$User,$config_sys,$Ext,$Router;
		
		//Load plugin language
		Language::LoadPluginFile(_PLUGIN_CONTROLLER);
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();

		?>
        <script type="text/javascript">
        	$(document).ready(function() {
                $('#avatarsel').change(function() {
                    switch($('#avatarsel').val()) {
                        case "MemHT":
                            $('#memht').show();
                            $('#third').hide();
                            break;
						case "Gravatar":
							$('#memht').hide();
                            $('#third').show();
							break;
                        default:
                            $('#memht').hide();
                            $('#third').hide();
                            break;
                    }
                });
			});
        </script>
		<?php
		
		$data = $User->Data();
		
		switch (Io::GetVar("GET","subop")) {
			default:
				$form = new Form();
				$form->action = "index.php?"._NODE."="._PLUGIN."&amp;op=profile&amp;subop=proceed";
				$form->enctype = "multipart/form-data";
				
				$form->Open();
				
				//Display name
				$form->AddElement(array("element"	=>"text",
										"label"		=>_t("DISPLAY_NAME"),
										"name"		=>"name",
										"value"		=>$data['name']));
				
				//Preferred role
				$roles = array();
				foreach ($data['roles'] as $key => $value) $roles[$value['name']] = $key;
				$form->AddElement(array("element"	=>"select",
										"label"		=>_t("PREFERRED_ROLE"),
										"name"		=>"op_prefrole",
										"values"	=>$roles,
										"selected"	=>(isset($data['options']['prefrole']) ? $data['options']['prefrole'] : "")));
				
				//Email
				$form->AddElement(array("element"	=>"text",
										"label"		=>_t("EMAIL"),
										"name"		=>"email",
										"value"		=>$data['email']));

				//Timezone
				$array = array();
				foreach ($config_sys['timezone_list'] as $val => $zone) $array[$zone] = $val;
				$form->AddElement(array("element"	=>"select",
										"label"		=>_t("TIMEZONE"),
										"name"		=>"op_timezone",
										"values"	=>$array,
										"info"		=>"GMT "._GMT_DATETIME,
										"selected"	=>(isset($data['options']['timezone']) ? $data['options']['timezone'] : "+0:00")));
				
				//Language
				$result = $Db->GetList("SELECT title,file FROM #__languages ORDER BY title");
				$array = array();
				foreach ($result as $row) $array[Io::Output($row['title'])] = Io::Output($row['file']);
				$form->AddElement(array("element"	=>"select",
										"label"		=>_t("LANGUAGE"),
										"name"		=>"op_language",
										"values"	=>$array,
										"selected"	=>(isset($data['options']['language']) ? $data['options']['language'] : "")));
				
				if (!$config_sys['lock_template']) {
					//Template
					$dir = Utils::GetDirContent("templates"._DS,array("compiled"));
					$files = array();
					foreach ($dir as $file) $files[MB::ucfirst($file)] = $file;
					$form->AddElement(array("element"	=>"select",
											"label"		=>_t("TEMPLATE"),
											"name"		=>"op_template",
											"values"	=>$files,
											"selected"	=>(isset($data['options']['template']) ? $data['options']['template'] : "")));
				}
				
				//Datestamp
				$form->AddElement(array("element"	=>"text",
										"label"		=>_t("DATESTAMP"),
										"name"		=>"op_datestamp",
										"value"		=>(isset($data['options']['datestamp']) ? $data['options']['datestamp'] : ""),
										"info"		=>_t("READ_THIS_X_FOR_MORE_INFORMATION","http://php.net/manual/en/function.strftime.php")));
														//TODO: Create a wiki page and link there
				//Timestamp
				$form->AddElement(array("element"	=>"text",
										"label"		=>_t("TIMESTAMP"),
										"name"		=>"op_timestamp",
										"value"		=>(isset($data['options']['timestamp']) ? $data['options']['timestamp'] : ""),
										"info"		=>_t("READ_THIS_X_FOR_MORE_INFORMATION","http://php.net/manual/en/function.strftime.php")));
														//TODO: Create a wiki page and link there

				//Avatar engine
				$form->AddElement(array("element"	=>"select",
										"label"		=>_t("AVATAR_ENGINE"),
										"name"		=>"op_avatar_engine",
										"id"		=>"avatarsel",
										"values"	=>array("MemHT"=>"MemHT","Gravatar"=>"Gravatar",_t("NO_AVATAR")=>"No"),
										"selected"	=>(isset($data['options']['avatar']['selector']) ? $data['options']['avatar']['selector'] : "No")));

				//Avatar: MemHT, Upload file
				$max_size = $Router->GetOption("avatar_size",20480);
				$max_size /= 1024;
				$max_w = $Router->GetOption("avatar_width",90);
				$max_h = $Router->GetOption("avatar_height",90);
				echo "<div id='memht'".((isset($data['options']['avatar']['selector']) && $data['options']['avatar']['selector']=="MemHT") ? "" : " style='display:none'").">";
				$form->AddElement(array("element"	=>"file",
										"label"		=>_t("AVATAR"),
										"name"		=>"op_avatar",
										"size"		=>30,
										"value"		=>(isset($data['options']['avatar']['selector']) &&
													$data['options']['avatar']['selector']=="MemHT" ? @$data['options']['avatar']['value'] : ""),
										"info"		=>_t("AVATAR_TYPE_INFO_X_Y",$max_size."Kb",$max_w."px x ".$max_h."px")));
				echo "</div>";

				//Avatar: Gravatar
				echo "<div id='third'".((isset($data['options']['avatar']['selector']) && $data['options']['avatar']['selector']=="Gravatar") ? "" : " style='display:none'").">";
				$form->AddElement(array("element"	=>"text",
										"label"		=>"Gravatar",
										"value"		=>(isset($data['options']['avatar']['selector']) &&
													$data['options']['avatar']['selector']=="Gravatar" ? @$data['options']['avatar']['value'] : ""),
										"name"		=>"op_gravatar",
										"info"		=>_t("READ_THIS_X_FOR_MORE_INFORMATION","http://www.gravatar.com")));
														//TODO: Create a wiki page and link there
				echo "</div>";
						
				//Additional
				$result = $Db->GetList("SELECT label,name,type FROM #__user_profile");
				foreach ($result as $row) {
					$label = MB::strtolower(Io::Output($row['label']));
					$name = Io::Output($row['name']);
					$type = MB::strtolower(Io::Output($row['type']));
					
					if ($type!="text" && $type!="textarea") $type = "text";
					
					$value = (isset($data['options'][$label])) ? $data['options'][$label] : "";
					$form->AddElement(array("element"	=>$type,
											"label"		=>$name,
											"name"		=>"ad_".$label,
											"value"		=>$value));
				}
				
				$Ext->RunMext("UserChangeProfileForm",array($data));
				
				//Submit
				$form->AddElement(array("element"	=>"submit",
										"name"		=>"submit",
										"inline"	=>true,
										"value"		=>_t("CHANGE_X",MB::strtolower(_t("PROFILE")))));
				
				$form->AddElement(array("element"	=>"button",
										"name"		=>"pass",
										"extra"		=>"onclick='javascript:location=\"".RewriteUrl("index.php?"._NODE."="._PLUGIN."&amp;op=password")."\";'",
										"inline"	=>true,
										"value"		=>_t("CHANGE_X",MB::strtolower(_t("PASSWORD")))));
				
				$form->Close();
				break;
				
			case "proceed":
				//Check token
				if (Utils::CheckToken()) {
					$name = Io::GetVar('POST','name',false,true,$data['name']);
					$email = Io::GetVar('POST','email',false,true,$data['email']);
					$timezone = Io::GetVar('POST','op_timezone',false,"int");
					$language = Io::GetVar('POST','op_language');
					$template = Io::GetVar('POST','op_template');
					$timestamp = Io::GetVar('POST','op_timestamp');
					$datestamp = Io::GetVar('POST','op_datestamp');
					$prefrole = Io::GetVar('POST','op_prefrole');
					$av_engine = Io::GetVar('POST','op_avatar_engine');
					$av_gravatar = Io::GetVar('POST','op_gravatar');

					$Ext->RunMext("UserChangeProfileFormGetData");

					$errors = array();
					if (empty($name)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("DISPLAY_NAME"));
					if (empty($email)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("EMAIL"));
					if (!Utils::ValidEmail($email)) $errors[] = _t("THE_FIELD_X_IS_NOT_INVALID",MB::strtolower(_t("EMAIL")));

					$options = array();
					if (!empty($timezone)) $options["timezone"] = $timezone;
					if (!empty($language)) $options["language"] = $language;
					if (!empty($template)) $options["template"] = $template;
					if (!empty($timestamp)) $options["timestamp"] = $timestamp;
					if (!empty($datestamp)) $options["datestamp"] = $datestamp;
					if (!empty($prefrole)) $options["prefrole"] = $prefrole;
					
					switch ($av_engine) {
						case "MemHT":
							//Upload
							include_once(_PATH_LIBRARIES._DS."MemHT"._DS."upload.img.class.php");
							$Up = new UploadImg();
							$Up->path = "assets/avatars/";
							$Up->field = "op_avatar";
							
							//Avatar: MemHT, Upload file
							$Up->max_size = $Router->GetOption("avatar_size",20480);
							$Up->max_w = $Router->GetOption("avatar_width",90);
							$Up->max_h = $Router->GetOption("avatar_height",90);
							if ($avatar = $Up->Upload()) {
								//Delete previous data/files if necessary
								if (isset($data['options']['avatar']['selector']) && $data['options']['avatar']['selector']=="MemHT") {
									@unlink("assets/avatars/".$data['options']['avatar']['value']);
								}
								$options["avatar"] = array("selector"=>"MemHT","value"=>$avatar);
							} else if (!$Up->Selected()) {
								if (isset($data['options']['avatar'])) $options["avatar"] = $data['options']['avatar'];
							} else {
								$errors[] = implode(",",$Up->GetErrors());
							}
							break;
						case "Gravatar":
							$options["avatar"] = array("selector"=>"Gravatar","value"=>$av_gravatar);
							//Delete previous data/files if necessary
							$data = $User->Data();
							if (isset($data['options']['avatar']['selector']) && $data['options']['avatar']['selector']=="MemHT") {
								@unlink("assets/avatars/".$data['options']['avatar']['value']);
							}
							break;
						default:
							//Delete previous data/files if necessary
							$data = $User->Data();
							if (isset($data['options']['avatar']['selector']) && $data['options']['avatar']['selector']=="MemHT") {
								@unlink("assets/avatars/".$data['options']['avatar']['value']);
							}
							break;
					}
					
					if (!sizeof($errors)) {
						//Additional
						$result = $Db->GetList("SELECT label FROM #__user_profile");
						foreach ($result as $row) {
							$label = Io::Output($row['label']);
							
							${$label} = Io::GetVar('POST','ad_'.$label);
							if (!empty(${$label})) $options[$label] = ${$label};
						}
						
						$options = Utils::Serialize($options);
						$Db->Query("UPDATE #__user SET name='".$Db->_e($name)."',email='".$Db->_e($email)."',options='".$Db->_e($options)."'
									WHERE uid=".intval($User->Uid()));
						
						$Ext->RunMext("UserChangeProfileFormSaveData",array($User->Uid()));
						
						Utils::Redirect("index.php?"._NODE."="._PLUGIN);
					} else {
						Error::Trigger("USERERROR",implode("<br />",$errors));
					}
				} else {
					Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
				}
				break;
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
	
	public function ChangePassword() {
		global $Db,$User;
		
		//Load plugin language
		Language::LoadPluginFile(_PLUGIN_CONTROLLER);
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		switch (Io::GetVar("GET","subop")) {
			default:
				$form = new Form();
				$form->action = "index.php?"._NODE."="._PLUGIN."&amp;op=password&amp;subop=proceed";
				
				$form->Open();
				
				//Old password
				$form->AddElement(array("element"	=>"text",
										"label"		=>_t("OLD_X",MB::strtolower(_t("PASSWORD"))),
										"name"		=>"oldpassword",
										"password"	=>true));
				//New password
				$form->AddElement(array("element"	=>"text",
										"label"		=>_t("NEW_X",MB::strtolower(_t("PASSWORD"))),
										"name"		=>"newpassword",
										"password"	=>true));
				//Confirm new password
				$form->AddElement(array("element"	=>"text",
										"label"		=>_t("CONFIRM_X",MB::strtolower(_t("NEW_X",_t("PASSWORD")))),
										"name"		=>"repassword",
										"password"	=>true));
				
				//Submit
				$form->AddElement(array("element"	=>"submit",
										"name"		=>"submit",
										"value"		=>_t("CHANGE_X",MB::strtolower(_t("PASSWORD")))));
				
				$form->Close();
				break;
				
			case "proceed":
				//Check token
				if (Utils::CheckToken()) {
					$old = Io::GetVar('POST','oldpassword');
					$new = Io::GetVar('POST','newpassword');
					$re = Io::GetVar('POST','repassword');
					
					$errors = array();
					if (!$row = $Db->GetRow("SELECT uid FROM #__user WHERE uid='".intval($User->Uid())."' AND pass='".$Db->_e(md5($old))."'")) $errors[] = _t("THE_FIELD_X_IS_NOT_INVALID",MB::strtolower(_t("PASSWORD")));
					if (empty($new)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("NEW_X",MB::strtolower(_t("PASSWORD"))));
					if ($new!=$re) $errors[] = _t("PASS_DONT_MATCH");
					if (!sizeof($errors)) {
						$Db->Query("UPDATE #__user SET pass='".$Db->_e(md5($new))."' WHERE uid=".intval($User->Uid()));
						
						$User->Logout();
						Utils::Redirect("index.php?"._NODE."="._PLUGIN);
					} else {
						Error::Trigger("USERERROR",implode("<br />",$errors));
					}
				} else {
					Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
				}
				break;
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