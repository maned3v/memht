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
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."time.class.php"); //Date and Time
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."user.class.php"); //User login engine [Ext,User]
define("_ERRHDL",true);
$handler = set_error_handler(array('Error','MemErrorHandler'));
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."security.class.php"); //Security filter [Ext,Security]
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."i18n.class.php"); //Localization
require_once(_PATH_LIBRARIES._DS."MemHT"._DS."modrewrite.class.php"); //Nice & SEO urls

if (!$User->IsAdmin()) die();

$type = Io::GetVar("GET","type","[^a-z]");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $config_sys['default_language']; ?>" xml:lang="<?php echo $config_sys['default_language']; ?>" dir="<?php echo _t("LANGDIR") ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $config_sys['site_name']; ?></title>

		<meta name="generator" content="MemHT Portal - Free PHP CMS and Blog" />
		
		<base href="<?php echo $config_sys['site_url']; ?>/" />
		<script type="text/javascript" src="libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>jquery.js"></script>
        <script type="text/javascript" src="libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>ui<?php echo _DS; ?>js<?php echo _DS; ?>jquery-ui.js"></script>
		<link rel="stylesheet" href="libraries<?php echo _DS; ?>MemHT<?php echo _DS; ?>style<?php echo _DS; ?>common.css" type="text/css" />
        <link rel="stylesheet" href="libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>ui<?php echo _DS; ?>css<?php echo _DS; ?>redmond<?php echo _DS; ?>jquery-ui.css" type="text/css" />
		<script type="text/javascript">
            $(document).ready(function() {
                console.log('am ready');
                $("#tabs").tabs();
            });
			function setInVal(val) {
                var args = top.tinymce.activeEditor.windowManager.getParams();
                //console.log(args);
                args['window'].document.getElementById(args['input']).value = val;
                args['window'].document.getElementById(args['input']).value = val;
                top.tinymce.activeEditor.windowManager.close();
			}
			function selectFile() {
				var file = window.frames['uploadframe'].document.getElementById('filename').value;
				if (file != "undefined" && file != "") {
					setInVal(window.frames['uploadframe'].document.getElementById('filename').value);
				} else {
					alert('<?php echo _t("NO_X_SELECTED",strtolower(_t("FILE"))); ?>');
				}
			}
		</script>
		<style type="text/css">
			/*.panel_wrapper div.current {
				height:550px;
				overflow:auto;
			}*/
		</style>
	</head>
	<body>

        <div id="tabs">
            <ul>
                <li><a href="<?php echo "libraries/TinyMCE/dialog.php?type=".$type; ?>#tabs-1"><?php echo _t("SELECT"); ?></a></li>
                <li><a href="<?php echo "libraries/TinyMCE/dialog.php?type=".$type; ?>#tabs-2"><?php echo _t("UPLOAD"); ?></a></li>
            </ul>
            <div id="tabs-1">
                <fieldset>
                    <legend><?php echo _t("CLICK_TO_SELECT"); ?></legend>

                    <input name="src" id="src" type="hidden" value="" />

                    <?php
                    $type = Io::GetVar("GET","type","[^a-z]");

                    switch ($type) {
                        default:
                        case "image":
                            ?>
                            <table class="properties">
                                <tr>
                                    <td>
                                        <?php
                                        $list = Utils::GetDirContent("../../assets/images");
                                        foreach ($list as $image) {
                                            echo "<div style='border-bottom:1px dotted #DDD;padding:5px;'><img src='".$config_sys['site_url']."/assets/images/$image' style='cursor:pointer;max-width:600px;' title='$image' onclick=\"javascript:setInVal('assets/images/$image');\" /></div>\n";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            <?php
                            break;
                        case "media":
                            //Feature not supported (yet)
                            ?>
                            <table class="properties">
                                <tr>
                                    <td>
                                        Feature not supported (yet)
                                    </td>
                                </tr>
                            </table>
                            <?php
                            break;
                        case "file":
                            //Files manager
                            ?>
                            <table class="properties">
                                <tr>
                                    <td>
                                        <?php
                                        if ($row = $Db->GetRow("SELECT * FROM #__content WHERE controller='filesmanager' AND type='PLUGIN' AND status='active'")) {
                                            if (is_writable("../../assets/files/".$config_sys['files_path'])) {
                                                $order = Utils::GetComOption("editor","file_order","f.title ASC");
                                                if ($result = $Db->GetList("SELECT f.*,c.title AS ctitle FROM #__filemgr AS f JOIN #__filemgr_categories AS c ON f.Category=c.id ORDER BY ".$Db->_e($order))) {
                                                    //Controller-name match
                                                    $plugmatch = Ram::Get("plugmatch");
                                                    $plugname = isset($plugmatch['filesmanager']) ? $plugmatch['filesmanager'] : "filesmanager" ;

                                                    $preroles = Ram::Get("roles");
                                                    $preroles['ALL']['name'] = _t("EVERYONE");

                                                    ?><table class="properties"><?php

                                                    foreach ($result as $row) {
                                                        $id			= Io::Output($row['id'],"int");
                                                        $name		= Io::Output($row['title']);
                                                        $ctitle		= Io::Output($row['ctitle']);
                                                        $file_name	= Io::Output($row['file_name']);
                                                        $file_ext	= Io::Output($row['file_ext']);
                                                        $size		= Io::Output($row['size'],"int");
                                                        $author		= Io::Output($row['autname']);
                                                        $uploaded	= Time::Output(Io::Output($row['uploaded']));
                                                        $roles		= Utils::Unserialize(Io::Output($row['roles']));
                                                        if (!sizeof($roles)|| empty($roles)) $roles = array('ALL');

                                                        $ronames = array();
                                                        foreach ($roles as $role) if (isset($preroles[$role]['name'])) $ronames[] = $preroles[$role]['name'];
                                                        $roles = _t("WHO_ACCESS_THE_X",strtolower("FILE")).": ".implode(", ",$ronames);

                                                        echo "<tr>\n";
                                                        echo "<td style='width:35%;border-bottom:1px dotted #DDD;padding:2px 5px;font-weight:bold;'><a href='javascript:void();' onclick=\"javascript:setInVal('".RewriteUrl("index.php?"._NODE."=$plugname&amp;op=get&amp;id=$id")."');FileBrowserDialogue.SubmitForm();\" />$name</a></td>\n";
                                                        echo "<td style='width:14%;border-bottom:1px dotted #DDD;padding:2px 5px;'>$ctitle</td>\n";
                                                        echo "<td style='width:1%;text-align:center;border-bottom:1px dotted #DDD;padding:2px 5px;'><img src='plugins"._DS."filesmanager"._DS."icons"._DS."$file_ext.png' alt='$id' title='$file_ext' /></td>\n";
                                                        echo "<td style='width:10%;border-bottom:1px dotted #DDD;padding:2px 5px;'>".Utils::Bytes2str($size)."</td>\n";
                                                        echo "<td style='width:25%;border-bottom:1px dotted #DDD;padding:2px 5px;'>$uploaded</td>\n";
                                                        echo "<td style='width:5%;border-bottom:1px dotted #DDD;padding:2px 5px;'><a href='javascript:void();' title='$roles'>"._t("ROLES")."</a></td>\n";
                                                        echo "</tr>\n";
                                                    }

                                                    ?></table><?php
                                                } else {
                                                    MemErr::Trigger("INFO",_t("LIST_EMPTY"));
                                                }
                                            } else {
                                                MemErr::Trigger("USERERROR",_t("CREATE_FOLDER"),"assets/files/<strong>".$config_sys['files_path']."</strong>");
                                            }
                                        } else {
                                            MemErr::Trigger("USERERROR",_t("X_NOT_FOUND_OR_INACTIVE",_t("PLUGIN")),"Files manager");
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            <?php
                            break;
                    }
                    ?>
                </fieldset>
            </div>
            <div id="tabs-2">
                <fieldset>
                    <legend><?php echo _t("UPLOAD"); ?></legend>

                    <?php
                    $type = Io::GetVar("GET","type","[^a-z]");

                    switch ($type) {
                    default:
                    case "image":
                    ?>
                    <table class="properties">
                        <tr>
                            <td class="column1"><iframe name="uploadframe" src="libraries/TinyMCE/dialog_serv.php?type=image" style="width:745px;height:490px;" frameborder="0"></iframe></td>
                        </tr>
                    </table>
                </fieldset>

                <div class="mceActionPanel">
                    <div style="float: left">
                        <input type="button" id="insert" name="insert" value="<?php echo _t("SELECT"); ?>" onclick="javascript:selectFile();" />
                    </div>

                    <div style="float: right">
                        <input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
                    </div>
                </div>
                <?php
                break;
                case "media":
                    ?>
                    <table class="properties">
                        <tr>
                            <td class="column1"><iframe name="uploadframe" src="libraries/TinyMCE/dialog_serv.php?type=media" style="width:745px;height:490px;" frameborder="0"></iframe></td>
                        </tr>
                    </table>
                    </fieldset>
                    <?php
                    break;
                case "file":
                    ?>
                    <table class="properties">
                        <tr>
                            <td class="column1"><iframe name="uploadframe" src="libraries/TinyMCE/dialog_serv.php?type=file" style="width:745px;height:490px;" frameborder="0"></iframe></td>
                        </tr>
                    </table>
                    </fieldset>

                    <div class="mceActionPanel">
                        <div style="float: left">
                            <input type="button" id="insert" name="insert" value="<?php echo _t("SELECT"); ?>" onclick="javascript:selectFile();" />
                        </div>

                        <div style="float: right">
                            <input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
                        </div>
                    </div>
                    <?php
                    break;
                }
                ?>
            </div>
        </div>

	</body>
</html>