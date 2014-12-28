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

error_reporting(0);
ini_set('error_reporting',0);
ini_set('log_errors','1');
header('Content-type: text/html; charset=utf-8');

define("_DS","/");
//define("_PATH_ROOT",dirname(__FILE__));
define("_PATH_ROOT","../../");
define("_PATH_INCLUDES",_PATH_ROOT._DS."includes");
define("_LOAD",true);
define("_CRON",true);

require_once(_PATH_INCLUDES._DS."defines.inc.php");
include_once(_PATH_CONFIGFILE); //Database configuration

if (!defined("_INSTALLED") || filesize(_PATH_CONFIGFILE) < 100) {
	exit;
}

require_once(_PATH_LIBRARIES._DS."MemHT"._DS."io.class.php"); //Input and output management
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."mb.class.php"); //Multibyte support for UTF-8
MB::Initialize();
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."utils.class.php"); //Misc utilities
require_once(_PATH_INCLUDES._DS."scout.inc.php"); //Scout
require_once(_PATH_INCLUDES._DS."common.inc.php"); //Common
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."error.class.php"); //Error handler
require_once(_PATH_INCLUDES._DS."ram.inc.php"); //Memory
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."extensions.class.php"); //Load extensions
require_once(_PATH_INCLUDES._DS."db.inc.php"); //Create database connection [Ext,DbLayer]
Utils::LoadComOptions(); //Load options
require_once(_PATH_SYSCONFIGFILE); //Site configuration
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."preloader.class.php"); //Preloader [Ext,Preloader]
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."session.class.php"); //Sessions [Ext,SessionDb]
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."user.class.php"); //User login engine [Ext,User]
define("_ERRHDL",true);
$handler = set_error_handler(array('Error','MemErrorHandler'));
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."security.class.php"); //Security filter [Ext,Security]
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."i18n.class.php"); //Localization
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."modrewrite.class.php"); //Nice & SEO urls

if (!$User->IsAdmin()) die();

?>
<link rel="stylesheet" href="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>MemHT<?php echo _DS; ?>style<?php echo _DS; ?>common.css" type="text/css" />
<style type="text/css">
	body {
		font-family:Arial, Helvetica, sans-serif;
		font-size:12px;
		color:#000;
	}
	#insert {
		border:0; margin:0; padding:0;
		font-weight:bold;
		width:94px; height:26px;
		background:url(themes/advanced/skins/default/img/buttons.png) 0 -26px;
		cursor:pointer;
		padding-bottom:2px;
	}
	a,
	a:link,
	a:visited {
		text-decoration:none;
		color:#000;
	}
	a:hover {
		text-decoration:underline;
		color:#000;
	}
</style>
<?php

	$type = Io::GetVar("GET","type","[^a-z]");
	$op = Io::GetVar("POST","op","[^a-zA-Z0-9\-]");
	
	switch ($type) {
		default:
		case "image":
			$max_size = Utils::GetComOption("editor","image_size",3145728);
			$max_size_txt = $max_size / 1024;
			$max_w = Utils::GetComOption("editor","image_width",2000);
			$max_h = Utils::GetComOption("editor","image_height",1000);
			switch ($op) {
				default:
					echo "<form action='dialog_serv.php?type=image' method='post' enctype='multipart/form-data'>\n";
						echo "<div><input type='file' name='upfile' id='upfile' /><input type='submit' id='insert' name='submit' value='"._t("UPLOAD")."' /></div>\n";
						echo "<div style='margin-top:10px;'>"._t("IMAGE_TYPE_INFO_X_Y",$max_size_txt."Kb",$max_w."px x ".$max_h."px")."</div>\n";
						echo "<input type='hidden' name='op' value='upload' />\n";
					echo "</form>\n";
					break;
				case "upload":
					//Upload
					include_once(_PATH_LIBRARIES._DS."MemHT"._DS."upload.img.class.php");
					$Up = new UploadImg();
					$Up->path = "../../assets/images/";
					$Up->field = "upfile";
					$Up->max_size = $max_size;
					$Up->max_w = $max_w;
					$Up->max_h = $max_h;
					if (!$image = $Up->Upload()) $errors[] = implode(",",$Up->GetErrors());
					
					if (!sizeof($errors)) {
						Error::Trigger("INFO",_t("UPLOADED"),"assets/images/$image");
						echo "<input type='hidden' name='filename' id='filename' value='assets/images/$image' />\n";
					} else {
						Error::Trigger("USERERROR",implode("<br />",$errors));
						echo "<div style='margin-top:10px;'><a href='dialog_serv.php?type=image'>"._t("TRY_AGAIN")."</a></div>\n";
					}
					break;
			}
			break;
		case "media":
			echo "Feature not supported (yet)";
			break;
		case "file":
			if ($row = $Db->GetRow("SELECT * FROM #__content WHERE controller='filesmanager' AND type='PLUGIN' AND status='active'")) {
				if (is_writable("../../assets/files/".$config_sys['files_path'])) {
					$max_size = Utils::GetComOption("editor","file_size",3145728);
					$max_size_text = $max_size / 1024;
					switch ($op) {
						default:
							echo "<form action='dialog_serv.php?type=file' method='post' enctype='multipart/form-data'>\n";
							echo "<div>"._t("TITLE")."<br /><input type='text' name='title' id='title' /></div>\n";
								//Category
								$select = array();
								$result = $Db->GetList("SELECT id,title FROM #__filemgr_categories ORDER by title");
								foreach ($result as $row) {
									$id = Io::Output($row['id'],"int");
									$title = Io::Output($row['title']);
								
									foreach ($result as $row) {
										$select[Io::Output($row['title'])] = Io::Output($row['id'],"int");
									}
								}
								
								echo "<div style='margin:10px 0;'>"._t("CATEGORY")."<br /><select name='category'>\n";
									foreach ($select as $key => $value) {
										echo "<option value='$value'>$key</option>";	
									}
								echo "</select></div>\n";
								echo "<div><input type='file' name='upfile' id='upfile' /><input type='submit' id='insert' name='submit' value='"._t("UPLOAD")."' /></div>\n";
								echo "<div style='margin-top:10px;'>"._t("ACCEPTED_FILE_TYPES_X","Gif, Jpg, Png, Zip, Rar, Pdf")."<br />"._t("MAX_FILESIZE_X",$max_size."Kb")."</div>\n";
								echo "<input type='hidden' name='op' value='upload' />\n";
							echo "</form>\n";
							break;
						case "upload":
							//Get POST data
							$title = Io::GetVar('POST','title','fullhtml');
							$category = Io::GetVar('POST','category','int');
							
							if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
							if (empty($category)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("CATEGORY"));
							
							if (!sizeof($errors)) {
								//Upload
								include_once(_PATH_LIBRARIES._DS."MemHT"._DS."upload.filemgr.class.php");
								$Up = new UploadFile();
								$Up->path = "../../assets/files/";
								$Up->field = "upfile";
								$Up->max_size = $max_size;
								if (!$filename = $Up->Upload()) $errors[] = implode(",",$Up->GetErrors());
								$ext = $Up->ext;
								$name = $Up->name;
								$size = $Up->size;
							}
							
							if (!sizeof($errors)) {
								$roles = array();
								$roles = Utils::Serialize($roles);
								
								$Db->Query("INSERT INTO #__filemgr (category,title,file_name,file_ext,size,author,uploaded,ip,roles)
											VALUES ('".intval($category)."','".$Db->_e($title)."','".$Db->_e($name)."','".$Db->_e($ext)."','".$Db->_e($size)."','".intval($User->Uid())."',NOW(),'".$Db->_e(Utils::Ip2num($User->Ip()))."','".$Db->_e($roles)."')");
								
								$id = $Db->InsertId();
								
								//Controller-name match
								$plugmatch = Ram::Get("plugmatch");
								$plugname = isset($plugmatch['filesmanager']) ? $plugmatch['filesmanager'] : "filesmanager" ;
								
								$url = RewriteUrl("index.php?"._NODE."=$plugname&amp;op=get&amp;id=$id");
								
								Error::Trigger("INFO",_t("UPLOADED"),"$url");
								echo "<input type='hidden' name='filename' id='filename' value='$url' />\n";
							} else {
								Error::Trigger("USERERROR",implode("<br />",$errors));
								echo "<div style='margin-top:10px;'><a href='dialog_serv.php?type=file'>"._t("TRY_AGAIN")."</a></div>\n";
							}
							break;
					}
				} else {
					Error::Trigger("USERERROR",_t("CREATE_FOLDER"),"assets/files/<strong>".$config_sys['files_path']."</strong>");
				}
			} else {
				Error::Trigger("USERERROR",_t("X_NOT_FOUND_OR_INACTIVE",_t("PLUGIN")),"Files manager");
			}
			break;
	}

?>