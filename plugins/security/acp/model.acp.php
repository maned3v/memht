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

class securityModel {
	function Main() {
		global $Db,$config_sys,$User;
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		?>

		<div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <?php
        $this->Menu();

       	?>
       	
       	<script type="text/javascript" charset="utf-8">
            //Unban
			function unban(id) {
				if (confirm('<?php echo _t("SURE_UNBAN_IP"); ?>')) {
					$.ajax({
						type: "POST",
						dataType: "xml",
						url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=unban",
						data: "id="+id,
						success: function(data){
							location = 'admin.php?cont=<?php echo _PLUGIN; ?>';
						}
					});
				}
			}
		</script>
       	
       	<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("BANNED_VISITORS"); ?></div>
                        <div class="body">
                       		<?php
        					echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
	        					echo "<thead>\n";
		        					echo "<tr>\n";
			        					echo "<th width='30%'>"._t("IP")."</th>\n";
			        					echo "<th width='25%'>"._t("USER")."</th>\n";
			        					echo "<th width='15%' style='text-align:center;'>"._t("AUTHOR")."</th>\n";
			        					echo "<th width='20%'>"._t("EXPIRATION_DATE")."</th>\n";
			        					echo "<th width='9%'>"._t("REASON")."</th>\n";
			        					echo "<th width='1%'>&nbsp;</th>\n";
		        					echo "</tr>\n";
	        					echo "</thead>\n";
		        				echo "<tbody>\n";
		        				
		        				//Pagination
		        				$limit = Io::GetVar("GET","limit","int",false,20);
		        				$page = Io::GetVar("GET","page","int",false,1);
		        				if ($page<=0) $page = 1;
		        				$from = ($page * $limit) - $limit;
		        				
		        				if ($result = $Db->GetList("SELECT b.*,u.name AS user FROM #__banned AS b LEFT JOIN #__user AS u ON b.uid=u.uid ORDER BY b.expire ASC, b.id DESC LIMIT ".intval($from).",".intval($limit))) {
		        					foreach ($result as $row) {
		        						$id		= Io::Output($row['id'],"int");
			        					$uid	= Io::Output($row['uid'],"int");
			        					$user	= Io::Output($row['user']);
			        					$ip		= Utils::Num2ip(Io::Output($row['ip']));
			        					$toip	= Utils::Num2ip(Io::Output($row['toip']));
			        					$expire	= Io::Output($row['expire']);
			        					$reason	= Io::Output($row['reason']);
			        					$author	= Io::Output($row['author'],"int");
			        					
			        					if ($expire == "2099-12-31 00:00:00") {
			        						$expire = _t("PERMANENT");
			        					} else {
			        						$expire = Time::Output($expire,"d");
			        					}
			        					
			        					if ($uid>0) {
			        						$uid = "<a href='admin.php?cont=user&op=info&uid=$uid' title='"._t("MORE_INFO_ABOUT_THIS_X",MB::strtolower(_t("USER")))."'>$user ($uid)</a>";
			        					} else {
			        						$uid = _t("GUEST");
			        					}
			        					
			        					if (!empty($toip)) {
			        						$ip .= " - $toip";
			        					}
			        					
			        					echo "<tr>\n";
			        						echo "<td style='vertical-align:middle;'><a href='admin.php?cont="._PLUGIN."&amp;op=edit&amp;id=$id' title='"._t("EDIT_THIS_X",_t("IP"))."' rel='tooltip'>$ip</a></td>\n";
			        						echo "<td style='vertical-align:middle;'>$uid</td>\n";
			        						echo "<td style='vertical-align:middle; text-align:center;'><a href='admin.php?cont=user&op=info&uid=$author' title='"._t("MORE_INFO_ABOUT_THIS_X",MB::strtolower(_t("USER")))."'>$author</a></td>\n";
			        						echo "<td style='vertical-align:middle;'>$expire</td>\n";
			        						echo "<td style='vertical-align:middle;'><a title='$reason' rel='tooltip'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."cloud.png' alt='"._t("REASON")."' /></a></td>\n";
			        						echo "<td style='text-align:right;'><input type='button' value='"._t("UNBAN")."' class='sys_form_button' onclick=\"javascript:unban('$id');\" /></td>\n";
			        					echo "</tr>\n";
		        					}
		        				} else {
		        					echo "<tr>\n";
		        						echo "<td colspan='6' style='text-align:center;'>"._t("LIST_EMPTY")."</td>\n";
		        					echo "</tr>\n";
		        				}
		        				echo "</tbody>\n";
	        				echo "</table>\n";
	        				
	        				include_once(_PATH_ACP_LIBRARIES._DS."MemHT"._DS."content"._DS."pagination.class.php");
	        				$Pag = new Pagination();
	        				$Pag->page = $page;
	        				$Pag->limit = $limit;
	        				$Pag->query = "SELECT COUNT(*) AS tot FROM #__banned";
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
	
	function FindIp() {
		global $Db,$config_sys,$User;
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		$ip = Io::GetVar("GET","ip",false,true,Io::GetVar("POST","ip",false,true));
		
		?>
		
		<script type="text/javascript" charset="utf-8">
			function ban(uid,ip) {
				if (uid) {
					location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=ban&uid='+uid+'&ip='+ip;					
				} else {
					location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=ban&ip='+ip;					
				}
			}

            //Unban
			function unban(id) {
				if (confirm('<?php echo _t("SURE_UNBAN_IP"); ?>')) {
					$.ajax({
						type: "POST",
						dataType: "xml",
						url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=unban",
						data: "id="+id,
						success: function(data){
							location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=find&ip=<?php echo $ip; ?>';
						}
					});
				}
			}
		</script>

		<div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <?php
        $this->Menu();
        
		if (Utils::ValidIp($ip)) {
	       	?>
	       	<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
	        	<tr>
			       	<td style="vertical-align:top;">
	                    <div class="widget ui-widget-content ui-corner-all">
	                        <div class="ui-widget-header"><?php echo _t("FIND_X",_t("IP")); ?></div>
	                        <div class="body">
	                       		<?php
	        					echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
	        					echo "<thead>\n";
		        					echo "<tr>\n";
			        					echo "<th width='24%'>"._t("IP")."</th>\n";
			        					echo "<th width='74%'>"._t("HOST")."</th>\n";
			        					echo "<th width='2%'>&nbsp;</th>\n";
		        					echo "</tr>\n";
	        					echo "</thead>\n";
		        					echo "<tbody>\n";
		        						echo "<td>".$ip."</td>\n";
		        						echo "<td>".gethostbyaddr($ip)."</td>\n";
		        						echo "<td style='text-align:right;'><input type='button' value='"._t("BAN_X",_t("IP"))."' class='sys_form_button' onclick=\"javascript:ban(false,'$ip');\" /></td>\n";
		        					echo "</tbody>\n";
		        				echo "</table>\n";
	                        	?>
	                        </div>
	                    </div>
	                </td>
	            </tr>
	        </table>
	                        
	        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
	        	<tr>
			       	<td style="vertical-align:top;">
	                    <div class="widget ui-widget-content ui-corner-all">
	                        <div class="ui-widget-header"><?php echo _t("USERS"); ?></div>
	                        <div class="body">
	        				<?php
	        					echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
		        					echo "<thead>\n";
			        					echo "<tr>\n";
			        						echo "<th width='22%'>"._t("NAME")."</th>\n";
	                                        echo "<th width='12%'>"._t("USER")."</th>\n";
	                                        echo "<th width='23%'>"._t("EMAIL")."</th>\n";
											echo "<th width='18%'>"._t("LAST_LOGIN")."</th>\n";
											echo "<th width='1%'>&nbsp;</th>\n";
			        					echo "</tr>\n";
		        					echo "</thead>\n";
		        					echo "<tbody>\n";
		        					//Users
		        					if ($result = $Db->GetList("SELECT * FROM #__user WHERE lastip='".$Db->_e(Utils::Ip2Num($ip))."' ORDER BY lastseen DESC")) {
		        						foreach ($result as $row) {
			        						$uid    = Io::Output($row['uid'],'int');
			        						$name   = Io::Output($row['name']);
			        						$user   = Io::Output($row['user']);
			        						$email  = Io::Output($row['email']);
			        						$regdate= Time::Output(Io::Output($row['regdate']));
			        						$lastseen_o = Io::Output($row['lastseen']);
			        						$lastseen= Time::Output(Io::Output($row['lastseen']));
			        						$status = Io::Output($row['status']);
			        						if (Time::DateEmpty($lastseen_o)) $lastseen = _t("NEVER");
			        						
			        						echo "<tr>\n";
				        						echo "<td><a href='admin.php?cont=user&amp;op=info&amp;uid=$uid' title='"._t("MORE_INFO_ABOUT_THIS_X",MB::strtolower(_t("USER")))."'><strong>$name</strong></a></td>\n";
				        						echo "<td>$user</td>\n";
				        						echo "<td>$email</td>\n";
			        						    echo "<td>$lastseen</td>\n";
			        						    echo "<td style='text-align:right;'><input type='button' value='"._t("BAN_X",_t("USER"))."' class='sys_form_button' onclick=\"javascript:ban('$uid','$ip');\" /></td>\n";
			        						echo "</tr>\n";
		        						}
		        					} else {
		        						echo "<td colspan='5' style='text-align:center;'>"._t("X_NOT_FOUND",_t("IP"))."</td>\n";
		        					}
		        					echo "</tbody>\n";
		        				echo "</table>\n";
	        				?>
	                        </div>
	                    </div>
	                </td>
	            </tr>
	        </table>
	        
	        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
	        	<tr>
	        		<td style="vertical-align:top;">
	        			<div class="widget ui-widget-content ui-corner-all">
	        			<div class="ui-widget-header"><?php echo _t("BANNED_VISITORS"); ?></div>
        	            	<div class="body">
        	        			<?php
        	        				echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
        		        				echo "<thead>\n";
        			        				echo "<tr>\n";
					        					echo "<th width='30%'>"._t("IP")."</th>\n";
					        					echo "<th width='25%'>"._t("USER")."</th>\n";
					        					echo "<th width='15%' style='text-align:center;'>"._t("AUTHOR")."</th>\n";
					        					echo "<th width='20%'>"._t("EXPIRATION_DATE")."</th>\n";
					        					echo "<th width='9%'>"._t("REASON")."</th>\n";
					        					echo "<th width='1%'>&nbsp;</th>\n";
				        					echo "</tr>\n";
        		        				echo "</thead>\n";
        		        				echo "<tbody>\n";
       										if ($result = $Db->GetList("SELECT b.*,u.name AS user FROM #__banned AS b LEFT JOIN #__user AS u ON b.uid=u.uid WHERE ((b.iprange=0 AND b.ip='".$Db->_e(Utils::Ip2Num($ip))."') OR (b.iprange=1 AND '".$Db->_e(Utils::Ip2Num($ip))."' BETWEEN b.ip and b.toip)) ORDER BY b.expire ASC, b.id DESC")) {
					        					foreach ($result as $row) {
					        						$id		= Io::Output($row['id'],"int");
						        					$uid	= Io::Output($row['uid'],"int");
						        					$user	= Io::Output($row['user']);
						        					$ip		= Utils::Num2ip(Io::Output($row['ip']));
						        					$toip	= Utils::Num2ip(Io::Output($row['toip']));
						        					$expire	= Io::Output($row['expire']);
						        					$reason	= Io::Output($row['reason']);
						        					$author	= Io::Output($row['author'],"int");
						        					
						        					if ($expire == "2099-12-31 00:00:00") {
						        						$expire = _t("PERMANENT");
						        					} else {
						        						$expire = Time::Output($expire,"d");
						        					}
						        					
						        					if ($uid>0) {
						        						$uid = "<a href='admin.php?cont=user&op=info&uid=$uid' title='"._t("MORE_INFO_ABOUT_THIS_X",MB::strtolower(_t("USER")))."'>$user ($uid)</a>";
						        					} else {
						        						$uid = _t("GUEST");
						        					}
						        					
						        					if (!empty($toip)) {
						        						$ip .= " - $toip";
						        					}
						        					
						        					echo "<tr>\n";
						        						echo "<td style='vertical-align:middle;'><a href='admin.php?cont="._PLUGIN."&amp;op=edit&amp;id=$id' title='"._t("EDIT_THIS_X",_t("IP"))."' rel='tooltip'>$ip</a></td>\n";
						        						echo "<td style='vertical-align:middle;'>$uid</td>\n";
						        						echo "<td style='vertical-align:middle; text-align:center;'><a href='admin.php?cont=user&op=info&uid=$author' title='"._t("MORE_INFO_ABOUT_THIS_X",MB::strtolower(_t("USER")))."'>$author</a></td>\n";
						        						echo "<td style='vertical-align:middle;'>$expire</td>\n";
						        						echo "<td style='vertical-align:middle;'><a title='$reason' rel='tooltip'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."cloud.png' alt='"._t("REASON")."' /></a></td>\n";
						        						echo "<td style='text-align:right;'><input type='button' value='"._t("UNBAN")."' class='sys_form_button' onclick=\"javascript:unban('$id');\" /></td>\n";
						        					echo "</tr>\n";
					        					}
					        				} else {
					        					echo "<tr>\n";
					        						echo "<td colspan='6' style='text-align:center;'>"._t("LIST_EMPTY")."</td>\n";
					        					echo "</tr>\n";
					        				}
        		        				echo "</tbody>\n";
        		        			echo "</table>\n";
        	        			?>
        	                	</div>
        	            	</div>
        	        	</td>
        	    	</tr>
        		</table>
	        <?php
       	} else if (empty($ip)) {
       		?>
       		<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
       			<tr>
       				<td style="vertical-align:top;">
       					<div class="widget ui-widget-content ui-corner-all">
       						<div class="ui-widget-header"><?php echo _t("FIND_X",_t("IP")); ?></div>
       						<div class="body">
       					    	<?php
       					       					
       								$form = new Form();
       								$form->action = "admin.php?cont="._PLUGIN."&amp;op=find";
       				       										
       								$form->Open();
       				       			
       								//IP
       								$form->AddElement(array("element"	=>"text",
       														"width"		=>"150px",
       														"inline"	=>true,
       														"name"		=>"ip",
       														"id"		=>"ip"));
       				       												
       								//Find
       								$form->AddElement(array("element"	=>"submit",
       														"name"		=>"submit",
       														"inline"	=>true,
       														"value"		=>_t("FIND")));
       		
       								$form->Close();       									
       		       							
       		       				?>
       		       			</div>
       		       		</div>
       				</td>
       			</tr>
       		</table>
       		<?php
        } else {
        	?>
        	<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
	       		<tr>
		       		<td style="vertical-align:top;">
			       		<div class="widget ui-widget-content ui-corner-all">
			       			<div class="ui-widget-header"><?php echo _t("FIND_X",_t("IP")); ?></div>
			       			<div class="body">
       							<?php
	       							Error::Trigger("USERERROR",_t("X_NOT_VALID",_t("IP")));
	       						?>
	       						</div>
       					</div>
       				</td>
       			</tr>
       		</table>
       		<?php
        }
        ?>
        
		<?php
		//Assign captured content to the template engine and clean buffer
		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
		//Draw site template
		Template::Draw();
		//Initialize and show site footer
		Layout::Footer();
	}
	
	function UnbanIp() {
		global $Db;
	
		$id = Io::GetVar("POST","id","int");
	
		$result = $Db->Query("DELETE FROM #__banned WHERE id='".intval($id)."'") ? 1 : 0 ;
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
	
	function BanIp() {
		global $Db,$User,$config_sys;
		
		//Initialize and show site header
		Layout::Header(array("editor"=>true));
		//Start buffering content
		Utils::StartBuffering();
	
		$ip = Io::GetVar("GET","ip",false,true);
       	if (Utils::ValidIp($ip)) {
       		$uid = Io::GetVar("GET","uid","int",true);
       		
       		
       		?>
       		
       		<script type="text/javascript">
	        	$(document).ready(function() {
	        		$(".datepicker").datepicker({
						dateFormat: 'yy-mm-dd',
						minDate: 0
					});
					$('#find').click(function(){
						$.ajax({
							type: "POST",
							dataType: "xml",
							url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=finduser",
							data: "name="+$('#uid').val(),
							success: function(xml){
								$('#uid').val($(xml).find('uid').text());
								$('#user').html($(xml).find('name').text()+" ("+$(xml).find('user').text()+")");
							}
						});
					});
				});
	        </script>
       		
       		<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
	       		<tr>
		       		<td style="vertical-align:top;">
			       		<div class="widget ui-widget-content ui-corner-all">
			       			<div class="ui-widget-header"><?php echo _t("BAN_X",_t("IP")); ?></div>
			       			<div class="body">
			       		    	<?php
			       					if (!isset($_POST['ban'])) {
			       						$form = new Form();
			       						$form->action = "admin.php?cont="._PLUGIN."&amp;op=ban&amp;ip=$ip";
			       										
			       						$form->Open();
			       			
			       						//IP
			       						$form->AddElement(array("element"	=>"text",
			       												"label"		=>_t("IP"),
			       												"width"		=>"150px",
			       												"inline"	=>true,
			       												"name"		=>"ip",
			       												"value"		=>$ip,
			       												"id"		=>"ip"));
			       						echo "-";
			       			
			       						$form->AddElement(array("element"	=>"text",
       									       					"width"		=>"150px",
       									       					"inline"	=>true,
       									       					"name"		=>"toip",
       									       					"id"		=>"toip",
			       												"info"		=>_t("BANIP_RANGE_INFO")));
			       			
			       						//User
			       						$form->AddElement(array("element"	=>"text",
			       												"label"		=>_t("USER_ID"),
			       												"name"		=>"uid",
			       												"width"		=>"300px",
			       												"id"		=>"uid",
			       												"value"		=>$uid,
			       												"suffix"	=>"<input type='button' id='find' value='"._t("FIND")."' class='sys_form_button' /><span id='user' style='margin-left:5px;'></span>",
			       												"info"		=>_t("WRITE_USER_NAME_TO_FIND")));
			       						
			       						//Ban expires
			       						$form->AddElement(array("element"	=>"text",
			       												"label"		=>_t("BAN_EXPIRES"),
			       												"name"		=>"expire",
			       												"value"		=>"2099-12-31",
			       												"class"		=>"sys_form_text datepicker",
			       												"width"		=>"150px",
			       												"suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."calendar.png' alt='End' />"));
			       						
			       						//Reason
			       						$form->AddElement(array("element"	=>"textarea",
       															"label"		=>_t("REASON"),
       															"name"		=>"reason",
       															"height"	=>"200px",
       															"class"		=>"simple"));
			       										
			       						?>						
			       										
			       		                <div style="padding:2px;"></div>
			       		                <?php
			       												
			       						//Ban
			       						$form->AddElement(array("element"	=>"submit",
			       												"name"		=>"ban",
			       												"inline"	=>true,
			       												"value"		=>_t("SAVE")));

			       						$form->Close();       									
       								} else {
       									//Check token
       									if (Utils::CheckToken()) {							
       										//Get POST data
       										$ip			= Io::GetVar("POST","ip");
       										$toip		= Io::GetVar("POST","toip");
       										$uid		= Io::GetVar("POST","uid","int");
       										$expire		= Io::GetVar("POST","expire");
       										$reason		= Io::GetVar("POST","reason","fullhtml");
       										
       										$errors = array();
       										if (!Utils::ValidIp($ip)) $errors[] = _t("X_NOT_VALID",_t("IP"));
       										
       										if (!sizeof($errors)) {
       											if (Utils::ValidIp($toip)) {
       												$iprange = 1;
       												$toip = Utils::Ip2num($toip);
       											} else {
       												$iprange = 0;
       												$toip = "";
       											}
       											
       											$ip = Utils::Ip2num($ip);
       											
       											$Db->Query("INSERT INTO #__banned (uid,ip,toip,iprange,expire,reason,author,bandate)
       														VALUES ('".intval($uid)."','".$Db->_e($ip)."','".$Db->_e($toip)."','".intval($iprange)."','".$Db->_e($expire)."','".$Db->_e($reason)."','".intval($User->Uid())."',NOW())");
       											
       											Utils::Redirect("admin.php?cont="._PLUGIN);
       										} else {
       											Error::Trigger("USERERROR",implode("<br />",$errors));
       										}
       									} else {
       										Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
       									}
       								}
       								?>
       												</div>
       											</div>
       										</td>
       									</tr>
       								</table>
       								<?php
       		
       	} else {
       		?>
       		<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
       			<tr>
       				<td style="vertical-align:top;">
       					<div class="widget ui-widget-content ui-corner-all">
       						<div class="ui-widget-header"><?php echo _t("BAN_X",_t("IP")); ?></div>
       				    	    <div class="body">
       							<?php
	       							Error::Trigger("USERERROR",_t("X_NOT_VALID",_t("IP")));
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
	
	function EditIp() {
		global $Db,$User,$config_sys;
	
		//Initialize and show site header
		Layout::Header(array("editor"=>true));
		//Start buffering content
		Utils::StartBuffering();
	
		$id = Io::GetVar("GET","id","int");
		if ($row = $Db->GetRow("SELECT * FROM #__banned WHERE id='".intval($id)."'")) {
			$ip			= Utils::Num2ip(Io::Output($row['ip']));
			$toip		= Utils::Num2ip(Io::Output($row['toip']));
			$uid		= Io::Output($row['uid'],"int");
			$iprange	= Io::Output($row['iprange'],"int");
			$expire		= Io::Output($row['expire']);
			$reason		= Io::Output($row['reason']);
			?>
	       		
	       		<script type="text/javascript">
		        	$(document).ready(function() {
		        		$(".datepicker").datepicker({
							dateFormat: 'yy-mm-dd',
							minDate: 0
						});
						$('#find').click(function(){
							$.ajax({
								type: "POST",
								dataType: "xml",
								url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=finduser",
								data: "name="+$('#uid').val(),
								success: function(xml){
									$('#uid').val($(xml).find('uid').text());
									$('#user').html($(xml).find('name').text()+" ("+$(xml).find('user').text()+")");
								}
							});
						});
					});
		        </script>
	       		
	       		<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
		       		<tr>
			       		<td style="vertical-align:top;">
				       		<div class="widget ui-widget-content ui-corner-all">
				       			<div class="ui-widget-header"><?php echo _t("BAN_X",_t("IP")); ?></div>
				       			<div class="body">
				       		    	<?php
				       					if (!isset($_POST['ban'])) {
				       						$form = new Form();
				       						$form->action = "admin.php?cont="._PLUGIN."&amp;op=edit&amp;id=$id";
				       										
				       						$form->Open();
				       			
				       						//IP
				       						$form->AddElement(array("element"	=>"text",
				       												"label"		=>_t("IP"),
				       												"width"		=>"150px",
				       												"inline"	=>true,
				       												"name"		=>"ip",
				       												"value"		=>$ip,
				       												"id"		=>"ip"));
				       						echo "-";
				       			
				       						$form->AddElement(array("element"	=>"text",
	       									       					"width"		=>"150px",
	       									       					"inline"	=>true,
	       									       					"name"		=>"toip",
	       									       					"value"		=>$toip,
	       									       					"id"		=>"toip",
				       												"info"		=>_t("BANIP_RANGE_INFO")));
				       			
				       						//User
				       						$form->AddElement(array("element"	=>"text",
				       												"label"		=>_t("USER_ID"),
				       												"name"		=>"uid",
				       												"width"		=>"300px",
				       												"id"		=>"uid",
				       												"value"		=>$uid,
				       												"suffix"	=>"<input type='button' id='find' value='"._t("FIND")."' class='sys_form_button' /><span id='user' style='margin-left:5px;'></span>",
				       												"info"		=>_t("WRITE_USER_NAME_TO_FIND")));
				       						
				       						//Ban expires
				       						$form->AddElement(array("element"	=>"text",
				       												"label"		=>_t("BAN_EXPIRES"),
				       												"name"		=>"expire",
				       												"value"		=>$expire,
				       												"class"		=>"sys_form_text datepicker",
				       												"width"		=>"150px",
				       												"suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."calendar.png' alt='End' />"));
				       						
				       						//Reason
				       						$form->AddElement(array("element"	=>"textarea",
	       															"label"		=>_t("REASON"),
	       															"name"		=>"reason",
	       															"value"		=>$reason,
	       															"height"	=>"200px",
	       															"class"		=>"simple"));
				       										
				       						?>						
				       										
				       		                <div style="padding:2px;"></div>
				       		                <?php
				       												
				       						//Ban
				       						$form->AddElement(array("element"	=>"submit",
				       												"name"		=>"ban",
				       												"inline"	=>true,
				       												"value"		=>_t("SAVE")));
	
				       						$form->Close();       									
	       								} else {
	       									//Check token
	       									if (Utils::CheckToken()) {							
	       										//Get POST data
	       										$ip			= Io::GetVar("POST","ip");
	       										$toip		= Io::GetVar("POST","toip");
	       										$uid		= Io::GetVar("POST","uid","int");
	       										$expire		= Io::GetVar("POST","expire");
	       										$reason		= Io::GetVar("POST","reason","fullhtml");
	       										
	       										$errors = array();
	       										if (!Utils::ValidIp($ip)) $errors[] = _t("X_NOT_VALID",_t("IP"));
	       										
	       										if (!sizeof($errors)) {
	       											if (Utils::ValidIp($toip)) {
	       												$iprange = 1;
	       												$toip = Utils::Ip2num($toip);
	       											} else {
	       												$iprange = 0;
	       												$toip = "";
	       											}
	       											
	       											$ip = Utils::Ip2num($ip);
	       											
	       											$Db->Query("UPDATE #__banned SET uid='".intval($uid)."',ip='".$Db->_e($ip)."',toip='".$Db->_e($toip)."',
	       														iprange='".intval($iprange)."',expire='".$Db->_e($expire)."',reason='".$Db->_e($reason)."' WHERE id='".intval($id)."'");
	       											
	       											Utils::Redirect("admin.php?cont="._PLUGIN);
	       										} else {
	       											Error::Trigger("USERERROR",implode("<br />",$errors));
	       										}
	       									} else {
	       										Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
	       									}
	       								}
	       								?>
	       												</div>
	       											</div>
	       										</td>
	       									</tr>
	       								</table>
	       								<?php
	       		
	       	} else {
	       		?>
	       		<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
	       			<tr>
	       				<td style="vertical-align:top;">
	       					<div class="widget ui-widget-content ui-corner-all">
	       						<div class="ui-widget-header"><?php echo _t("EDIT_X",_t("IP")); ?></div>
	       				    	    <div class="body">
	       							<?php
		       							Error::Trigger("USERERROR",_t("X_NOT_FOUND",_t("IP")));
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
	
	public function FindUserName() {
		global $Db;
	
		$name = Io::GetVar('POST','name','nohtml');
	
		$row = $Db->GetRow("SELECT uid,name,user FROM #__user WHERE (uid='".intval($name)."' AND uid>0) OR user='".$Db->_e($name)."'");
		if ($row) {
			$uid	= Io::Output($row['uid'],"int");
			$name	= Io::Output($row['name']);
			$user	= Io::Output($row['user']);
		} else {
			$uid	= 0;
			$name	= _t("X_NOT_FOUND",_t("USER"));
			$user	= $name;
		}
		
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
		header("Cache-Control: no-cache, must-revalidate" );
		header("Pragma: no-cache" );
		header("Content-Type: text/xml");
		
		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= '<response>';
		$xml .= '<uid>'.$uid.'</uid>';
		$xml .= '<name>'.$name.'</name>';
		$xml .= '<user>'.$user.'</user>';
		$xml .= '</response>';
		return $xml;
	}
	
	function Menu() {
		global $config_sys;
	
		echo "<div style='text-align:right;'>";
			echo "<a href='admin.php?cont="._PLUGIN."' title='"._t("BANNED_VISITORS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("BANNED_VISITORS")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",_t("IP"))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("IP")))."' /></a>\n";
		echo "</div>";
	}
}

?>