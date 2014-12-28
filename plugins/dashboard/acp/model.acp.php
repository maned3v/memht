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
defined("_ADMINCP") or die("Access denied");

class dashboardModel {
	function DashboardPage() {
		global $Db,$User,$config_sys;
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		$order = array(0=>1,1=>2,2=>3,3=>4,4=>5);
		$boxes = array(1=>"quickicons",2=>"comments",3=>"memhtnews",4=>"quicknote",5=>"memhtbox");
		
		?>
        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;" id="infobox"></div>
        
        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;" class="dash_container">
                    <?php
					foreach ($order as $value) include(_PATH_PLUGINS._DS._PLUGIN._DS."acp"._DS."boxes"._DS.$boxes[$value].".php");						
					?>
                </td>
                <td class="sidebar" style="padding-left:0;">
                	<div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header">MemHT Portal</div>
                        <div class="body">
                        	<table width="100%" cellpadding="0" cellspacing="2" border="0" summary="">
                            <?php
							echo "<tr><td width='55%'>"._t("VERSION").":</td><td width='45%'>".$config_sys['engine_version']."</td></tr>\n";
							echo "<tr><td>"._t("SEO_URLS").":</td><td>".(($config_sys['nice_seo_urls']) ? _t("ENABLED") : _t("DISABLED"))."</td></tr>\n";
							echo "<tr><td>Captcha:</td><td>".(($config_sys['captcha']) ? _t("ENABLED") : _t("DISABLED"))."</td></tr>\n";
							echo "<tr><td>"._t("MAINTENANCE_MODE").":</td><td>".(($config_sys['maintenance']) ? _t("ENABLED") : _t("DISABLED"))."</td></tr>\n";
							echo "<tr><td>"._t("WHITELIST_IP").":</td><td>".Utils::Num2ip($config_sys['maintenance_whiteip'])."</td></tr>\n";
							echo "<tr><td>"._t("LAST_MAINTENANCE").":</td><td>".$config_sys['maintenance_last']."</td></tr>\n";
							?>
                            </table>
                        </div>
                    </div>
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("SYSTEM"); ?></div>
                        <div class="body">
                            <table width="100%" cellpadding="0" cellspacing="2" border="0" summary="">
                            <?php
							echo "<tr><td width='55%'>"._t("OS").":</td><td width='45%'>".@php_uname('s')."</td></tr>\n";
							echo "<tr><td>"._t("SERVER_NAME").":</td><td><div style='width:105px;overflow:auto;'>".@php_uname('n')."</div></td></tr>\n";
							echo "<tr><td>"._t("DISK_FREE_SPACE").":</td><td>".Utils::Bytes2str(@disk_free_space("/"))."</td></tr>\n";
							?>
                            </table>
                        </div>
                    </div>
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header">PHP &amp; MySQL</div>
                        <div class="body">
                            <table width="100%" cellpadding="0" cellspacing="2" border="0" summary="">
                            <?php
							echo "<tr><td width='55%'>PHP "._t("VERSION").":</td><td width='45%'>".phpversion()."</td></tr>\n";
							$row = $Db->GetRow("SELECT VERSION() AS version");
							echo "<tr><td>MySQL "._t("VERSION").":</td><td>".Io::Output($row['version'])."</td></tr>\n";
							echo "<tr><td>"._t("REGISTER_GLOBALS").":</td><td>".((get_cfg_var('register_globals')) ? _t("ENABLED") : _t("DISABLED"))."</td></tr>\n";
							echo "<tr><td>"._t("MEMORY_LIMIT").":</td><td>".get_cfg_var('memory_limit')."</td></tr>\n";
							echo "<tr><td>"._t("UPLOAD_MAX_FILESIZE").":</td><td>".get_cfg_var('upload_max_filesize')."</td></tr>\n";
							?>
                            </table>
                        </div>
                    </div>
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("GD_GRAPH_LIB"); ?></div>
                        <div class="body">
                            <table width="100%" cellpadding="0" cellspacing="2" border="0" summary="">
                            <?php
							echo "<tr><td width='55%'>"._t("STATUS").":</td><td width='45%'>".((extension_loaded('gd')) ? _t("AVAILABLE") : _t("UNAVAILABLE"))."</td></tr>\n";
							$gdinfo = @gd_info();
							echo "<tr><td>"._t("VERSION").":</td><td>".@$gdinfo['GD Version']."</td></tr>\n";
							
							$gdtypes = array();
							if (isset($gdinfo['FreeType Support'])) $gdtypes[] = "FreeType";
							if (isset($gdinfo['T1Lib Support'])) $gdtypes[] = "T1Lib";
							if (isset($gdinfo['GIF Read Support'])) $gdtypes[] = "GIF Read";
							if (isset($gdinfo['GIF Create Support'])) $gdtypes[] = "GIF Create";
							if (isset($gdinfo['JPEG Support'])) $gdtypes[] = "JPEG";
							if (isset($gdinfo['JPG Support'])) $gdtypes[] = "JPG";
							if (isset($gdinfo['PNG Support'])) $gdtypes[] = "PNG";
							if (isset($gdinfo['WBMP Support'])) $gdtypes[] = "WBMP";
							if (isset($gdinfo['XBM Support'])) $gdtypes[] = "XBM";
							$gdtypes = implode(", ",$gdtypes);
							echo "<tr><td>"._t("SUPPORTED_TYPES").":</td><td>".$gdtypes."</td></tr>\n";
							?>
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
	
	function SaveDashboardOrder() {
		global $Db,$User;
		
		$order = Io::GetVar("GET","widget",false,false);
		$values = array();
		foreach ($order as $key => $value) $values[] = $value;
		echo "<span style='color:\n";
		echo (Utils::SetComOption("dashboard",$User->Uid(),$values)) ? "#009900';>"._t("SAVED") : "#990000';>"._t("NOT_SAVED") ;
		echo "</span>";
	}
}

?>