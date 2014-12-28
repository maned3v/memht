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

class internalModel {
	function Main() {
		header('Location: admin.php');
	}

	function SiteEvents() {
		global $Db,$config_sys,$User,$Ext;
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		?>

		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				//Delete
				$('input#delete').click(function() {
					var obj = $('.cb:checkbox:checked');
					if (obj.length>0) {
						if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("EVENT"))); ?>')) {
							var items = new Array();
							for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
							$.ajax({
								type: "POST",
								dataType: "xml",
								url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=deletevent",
								data: "items="+items,
								success: function(data){
									location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=events';
								}
							});
						}
					} else {
						alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("EVENT"))); ?>');
					}
				});
			});
			function modcomment(controller,item,id,decision) {
				$.ajax({
					type: "POST",
					dataType: "xml",
					url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=modcomment",
					data: "controller="+controller+"&item="+item+"&id="+id+"&dec="+decision,
					success: function(data){
						if (decision>0) {
							$("#Comment"+id+">td").animate({ backgroundColor: "#CAEDB6" }, 500);
							$("#approve_"+id).html("<span style='color:#0A5;'><?php echo _t("APPROVED"); ?></span>");
						} else {
							$("#Comment"+id+">td").animate({ backgroundColor: "#FFDBDB" }, 500);
							$("#approve_"+id).html("<span style='color:#F30;'><?php echo _t("DELETED"); ?></span>");
						}
						$("#approve_"+id).attr("id","#approved_off_"+id);
						$("#ip_"+id).attr("id","#ip_off_"+id);
					}
				});
			}
			function showmenu(id) {
				$("#ip_"+id).toggle();
				$("#approve_"+id).toggle();
			}
		</script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>&amp;op=events" title="<?php echo _t("EVENTS"); ?>"><?php echo _t("EVENTS"); ?></a></div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("EVENTS"); ?></div>
                        <div class="body">
							<?php

							echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
									//Delete
									echo "<input type='button' name='delete' value='"._t("DELETE")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
                            echo "</div>\n";

                            //Contact
							echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
								echo "<thead>\n";
									echo "<tr>\n";
										echo "<th colspan='5'>"._t("CONTACT_MESSAGES")."</th>\n";
									echo "</tr>\n";
									echo "<tr>\n";
										echo "<th width='1%'>&nbsp;</th>\n";
										echo "<th width='70%'>"._t("MESSAGE")."</th>\n";
										echo "<th width='10%'>"._t("IP")."</th>\n";
										echo "<th width='18%'>"._t("DATE")."</th>\n";
										echo "<th width='1%'>&nbsp;</th>\n";
									echo "</tr>\n";
								echo "</thead>\n";
								echo "<tbody>\n";
								if ($result = $Db->GetList("SELECT * FROM #__log WHERE label='contact_message' ORDER BY time DESC")) {
									foreach ($result as $row) {
										$id		= Io::Output($row['id'],"int");
										$message= str_replace("&lt;br /&gt;","<br />",Io::Output($row['message']));
										$ip		= Utils::Num2ip(Io::Output($row['ip']));
										$time	= Time::Output(Io::Output($row['time']));

										echo "<tr onmouseover='javascript:showmenu($id);' onmouseout='javascript:showmenu($id);'>\n";
											echo "<td><input type='checkbox' name='selected[]' value='$id' class='cb' /><br />&nbsp;</td>\n";
											echo "<td>$message</td>\n";
											echo "<td>$ip\n";
												echo "<div id='ip_$id' style='display:none; margin-top:2px;'><a href='admin.php?cont=security&amp;op=find&amp;ip=$ip' title='"._t("FIND_X",_t("IP"))."'>"._t("FIND_X",_t("IP"))."</a></div>\n";
											echo "</td>\n";
											echo "<td>$time</td>\n";
										echo "</tr>\n";
									}
								} else {
									echo "<tr>\n";
										echo "<td colspan='5' style='text-align:center;'>"._t("LIST_EMPTY")."</td>\n";
                               		echo "</tr>\n";
								}
								echo "</tbody>\n";
							echo "</table>\n";
							
							echo "<br />\n";
							//Comments
							echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
								echo "<thead>\n";
									echo "<tr>\n";
										echo "<th colspan='5'>"._t("COMMENTS")."</th>\n";
									echo "</tr>\n";
									echo "<tr>\n";
										echo "<th width='1%'>&nbsp;</th>\n";
										echo "<th width='20%'>"._t("AUTHOR")."</th>\n";
										echo "<th width='50%'>"._t("TEXT")."</th>\n";
										echo "<th width='10%'>"._t("IP")."</th>\n";
										echo "<th width='19%'>"._t("DATE")."</th>\n";
									echo "</tr>\n";
								echo "</thead>\n";
								echo "<tbody>\n";
								if ($result = $Db->GetList("SELECT * FROM #__comments WHERE status='waiting' ORDER BY created DESC")) {
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
												echo "<div id='approve_$id' style='display:none; margin-top:2px;'><a href='javascript:void(0);' onclick=\"javascript:modcomment('$controller','$item','$id','1');\" title='"._t("APPROVE")."' style='color:#0A5;'>"._t("APPROVE")."</a> - <a href='javascript:void(0);' onclick=\"javascript:modcomment('$controller','$item','$id','0');\" title='"._t("DELETE")."' style='color:#F30;'>"._t("DELETE")."</a></div>\n";
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
							
							echo "<br />\n";
							//Errors
							echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
								echo "<thead>\n";
									echo "<tr>\n";
										echo "<th colspan='5'>"._t("ERRORS")."</th>\n";
									echo "</tr>\n";
									echo "<tr>\n";
										echo "<th width='1%'><input type='checkbox' id='selectall' /></th>\n";
										echo "<th width='70%'>"._t("MESSAGE")."</th>\n";
										echo "<th width='10%'>"._t("IP")."</th>\n";
										echo "<th width='19%' colspan='2'>"._t("DATE")."</th>\n";
									echo "</tr>\n";
								echo "</thead>\n";
								echo "<tbody>\n";
								if ($result = $Db->GetList("SELECT * FROM #__log WHERE label='error_sys' OR label='error_mysql' OR label='error_user' ORDER BY time DESC")) {
									foreach ($result as $row) {
										$id		= Io::Output($row['id'],"int");
										$message= str_replace("&lt;br /&gt;","<br />",Io::Output($row['message']));
										$ip		= Utils::Num2ip(Io::Output($row['ip']));
										$time	= Time::Output(Io::Output($row['time']));

										echo "<tr onmouseover='javascript:showmenu($id);' onmouseout='javascript:showmenu($id);'>\n";
											echo "<td><input type='checkbox' name='selected[]' value='$id' class='cb' /><br />&nbsp;</td>\n";
											echo "<td>$message</td>\n";
											echo "<td>$ip\n";
												echo "<div id='ip_$id' style='display:none; margin-top:2px;'><a href='admin.php?cont=security&amp;op=find&amp;ip=$ip' title='"._t("FIND_X",_t("IP"))."'>"._t("FIND_X",_t("IP"))."</a></div>\n";
											echo "</td>\n";
											echo "<td>$time</td>\n";
										echo "</tr>\n";
									}
								} else {
									echo "<tr>\n";
										echo "<td colspan='5' style='text-align:center;'>"._t("LIST_EMPTY")."</td>\n";
                               		echo "</tr>\n";
								}
								echo "</tbody>\n";
							echo "</table>\n";
							
							$Ext->RunMext("AdminCP_Notifications_Display");
							
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

	function DeleteEvents() {
        global $Db;

		$items = Io::GetVar("POST","items",false,true);

		$result = $Db->Query("DELETE FROM #__log WHERE id IN (".$Db->_e($items).")") ? 1 : 0 ;
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
	
	function ModerateComments() {
        global $Db;

		$id = Io::GetVar("POST","id",false,true);
		$dec = Io::GetVar("POST","dec",false,true);
		$item = Io::GetVar("POST","item",false,true);
		$controller = Io::GetVar("POST","controller",false,true);

		if ($dec>0) {
			$result = $Db->Query("UPDATE #__comments SET status='published' WHERE status='waiting' AND id=".intval($id)) ? 1 : 0 ;
			if ($result) $result = $Db->Query("UPDATE #__".$Db->_e($controller)." SET comments=comments+1 WHERE id=".intval($item));
		} else {
			$result = $Db->Query("DELETE FROM #__comments WHERE status='waiting' AND id=".intval($id)) ? 1 : 0 ;
		}
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

    function Pinfo() {
		phpinfo();
    }

	public function CleanGivenChar() {
		global $Db;

		$string = Io::GetVar('POST','string','fullhtml');
		$lowercase = Io::GetVar('GET','lowercase','int');

		$result = $Db->GetList("SELECT * FROM #__conv_chars ORDER BY pattern ASC");
		$patterns = array();
		$replaces = array();
		foreach ($result as $row) {
			$patterns[] = $row['pattern'];
			$replaces[] = $row['replace'];
		}
		
		$string = str_replace($patterns,$replaces,$string);
		$string = preg_replace("#[ ]+#is","-",$string);
		$string = preg_replace("#[^a-zA-Z0-9\-]#is","",$string);
		if ($lowercase) $string = MB::strtolower($string);
		echo $string;

		unset($result);
		unset($patterns);
		unset($replaces);
	}
	
	public function SaveQuickNoteContent() {
		global $Db,$User;
	
		$text = Io::GetVar('POST','text',false,true);
		
		Utils::SetComOption("quicknote",$User->Uid(),$text);
	
		unset($text);
	}
}

?>