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

class commentsModel {
	function Main() {
		global $Db,$config_sys,$User;
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		$id = Io::GetVar("GET","id","int");
		
		?>
        
        <script type="text/javascript" charset="utf-8">
			function delcomment(controller,item,id) {
				$.ajax({
					type: "POST",
					dataType: "html",
					url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=delcomment",
					data: "controller="+controller+"&item="+item+"&id="+id,
					success: function(){
						$("#Comment"+id+">td").animate({ backgroundColor: "#FFDBDB" }, 500);
						$("#Comment"+id).slideUp();
					}
				});
			}
			function showmenu(id) {
				$("#ip_"+id).toggle();
				$("#command_"+id).toggle();
			}
		</script>
		
		<div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        
		<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("COMMENTS"); ?></div>
                        <div class="body">
							<?php
                            
							echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
							echo "<thead>\n";
								echo "<tr>\n";
									echo "<th width='1%'>&nbsp;</th>\n";
									echo "<th width='20%'>"._t("AUTHOR")."</th>\n";
									echo "<th width='50%'>"._t("TEXT")."</th>\n";
									echo "<th width='10%'>"._t("IP")."</th>\n";
									echo "<th width='19%'>"._t("DATE")."</th>\n";
								echo "</tr>\n";
							echo "</thead>\n";
							echo "<tbody>\n";
							
							//Options							
							$sortby = $Db->_e(Io::GetVar("GET","sortby",false,true,"id"));
							$order = $Db->_e(Io::GetVar("GET","order",false,true,"DESC"));
							$limit = Io::GetVar("GET","limit","int",false,10);
							
							//Pagination
							$page = Io::GetVar("GET","page","int",false,1);
							if ($page<=0) $page = 1;
							$from = ($page * $limit) - $limit;
							
							if ($result = $Db->GetList("SELECT * FROM #__comments ORDER BY {$sortby} $order LIMIT ".intval($from).",".intval($limit))) {
								foreach ($result as $row) {
									$id				= Io::Output($row['id'],"int");
									$text			= Io::Output($row['text']);
									$controller		= Io::Output($row['controller']);
									$item			= Io::Output($row['item'],"int");
									$ip				= Utils::Num2ip(Io::Output($row['author_ip']));
									$created		= Time::Output(Io::Output($row['created']));
									$author_name	= Io::Output($row['author_name']);
									$author_email	= Io::Output($row['author_email']);
									$author			= Io::Output($row['author'],"int");
										
									$author = ($author>0) ? $User->Name($author) : "<strong>$author_name</strong><br />$author_email" ;
										
									echo "<tr id='Comment{$id}' onmouseover='javascript:showmenu($id);' onmouseout='javascript:showmenu($id);'>\n";
										echo "<td><img src='admin/templates/memht/icons/cloud.png' alt='Comment{$id}' /><br />&nbsp;</td>\n";
										echo "<td>$author</td>\n";
										echo "<td>$text</td>\n";
										echo "<td>$ip\n";
											echo "<div id='ip_$id' style='display:none; margin-top:2px;'><a href='admin.php?cont=security&amp;op=find&amp;ip=$ip' title='"._t("FIND_X",_t("IP"))."'>"._t("FIND_X",_t("IP"))."</a></div>\n";
										echo "</td>\n";
										echo "<td>$created<br />\n";
											echo "<div id='command_$id' style='display:none; margin-top:2px;'><a href='javascript:void(0);' onclick=\"javascript:delcomment('$controller','$item','$id');\" title='"._t("DELETE")."' style='color:#F30;'>"._t("DELETE")."</a></div>\n";
										echo "</td>\n";
									echo "</tr>\n";
								}
                            } else {
								echo "<tr>\n";
									echo "<td colspan='5' style='text-align:center;'>"._t("LIST_EMPTY")."</td>\n";
                                echo "</tr>\n";
                            }
							
							echo "</tbody>\n";
							echo "</table>\n";
							
							include_once(_PATH_ACP_LIBRARIES._DS."MemHT"._DS."content"._DS."pagination.class.php");
							$Pag = new Pagination();
							$Pag->page = $page;
							$Pag->limit = $limit;
							$Pag->query = "SELECT COUNT(id) AS tot FROM #__comments";
							$Pag->url = "admin.php?cont="._PLUGIN."&amp;page={PAGE}";
							echo $Pag->Show();
							
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
	
	function DeleteComment() {
		global $Db;

		$id = Io::GetVar("POST","id",false,true);
		$item = Io::GetVar("POST","item",false,true);
		$controller = Io::GetVar("POST","controller",false,true);

		if ($id==0 || $item==0) return;
			
		$result = $Db->Query("DELETE FROM #__comments WHERE id=".intval($id)) ? 1 : 0 ;
		$total = $Db->AffectedRows();
		if ($result) $result = $Db->Query("UPDATE #__".$Db->_e($controller)." SET comments=comments-1 WHERE id=".intval($item)) ? 1 : 0 ;

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
}

?>