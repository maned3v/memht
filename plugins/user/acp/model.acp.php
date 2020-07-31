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

class userModel {
	function Main() {
        global $Db,$config_sys;
        
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		?>

        <script type="text/javascript" charset="utf-8">
            $(document).ready(function() {
                //Activate
                $('input#activate').click(function() {
					var obj = $('.cb:checkbox:checked');
					if (obj.length>0) {
						if (confirm('<?php echo _t("SURE_ACTIVATE_THE_X",MB::strtolower(_t("USERS"))); ?>')) {
							var items = new Array();
							for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
							$.ajax({
								type: "POST",
								dataType: "xml",
								url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=activate",
								data: "items="+items,
								success: function(data){
									location = 'admin.php?cont=<?php echo _PLUGIN; ?>';
								}
							});
						}
					} else {
						alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("USER"))); ?>');
					}
				});
				//Delete
				$('input#delete').click(function() {
					var obj = $('.cb:checkbox:checked');
					if (obj.length>0) {
						if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("USERS"))); ?>')) {
							var items = new Array();
							for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
							$.ajax({
								type: "POST",
								dataType: "xml",
								url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=delete",
								data: "items="+items,
								success: function(data){
									location = 'admin.php?cont=<?php echo _PLUGIN; ?>';
								}
							});
						}
					} else {
						alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("USER"))); ?>');
					}
				});
			});
			function showmenu(id) {
				$("#menu_"+id).toggle();
				$("#ip_"+id).toggle();
			}
		</script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
			/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("USERS_WAIT_ACT"); ?></div>
                        <div class="body">
                            <?php
                            if ($result = $Db->GetList("SELECT * FROM #__user WHERE status='waiting' OR status='moderate' ORDER BY uid ASC")) {
                                echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
									//Activate
									echo "<input type='button' name='activate' value='"._t("ACTIVATE")."' style='margin:2px 0;' class='sys_form_button' id='activate' />\n";
									//Delete
									echo "<input type='button' name='delete' value='"._t("DELETE")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
                                echo "</div>\n";
                                
                                echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
								echo "<thead>\n";
									echo "<tr>\n";
										echo "<th width='1%' style='text-align:right;'><input type='checkbox' id='selectall' /></th>\n";
										echo "<th width='25%'>"._t("NAME")."</th>\n";
                                        echo "<th width='15%'>"._t("USER")."</th>\n";
                                        echo "<th width='25%'>"._t("EMAIL")."</th>\n";
										echo "<th width='14%'>"._t("IP")."</th>\n";
										echo "<th width='10%'>"._t("STATUS")."</th>\n";
									echo "</tr>\n";
								echo "</thead>\n";
								echo "<tbody>\n";
                                foreach ($result as $row) {
                                    $uid    = Io::Output($row['uid'],'int');
                                    $name   = Io::Output($row['name']);
                                    $user   = Io::Output($row['user']);
                                    $email  = Io::Output($row['email']);
                                    $ip     = Utils::Num2ip(Io::Output($row['lastip']));
                                    $regdate= Time::Output(Io::Output($row['regdate']));
                                    $status = Io::Output($row['status']);

                                    echo "<tr onmouseover='javascript:showmenu($uid);' onmouseout='javascript:showmenu($uid);'>\n";
                                        echo "<td><input type='checkbox' name='selected[]' value='$uid' class='cb' /><br />&nbsp;</td>\n";
                                        echo "<td><strong>$name</strong>\n";
                                            echo "<div id='menu_$uid' style='display:none; margin-top:2px;'>$regdate</div>\n";
                                        echo "</td>\n";
                                        echo "<td>$user</td>\n";
                                        echo "<td>$email</td>\n";
                                        echo "<td>$ip\n";
											echo "<div id='ip_$uid' style='display:none; margin-top:2px;'><a href='admin.php?cont=security&amp;op=find&amp;ip=$ip' title='"._t("FIND_X",_t("IP"))."'>"._t("FIND_X",_t("IP"))."</a></div>\n";
										echo "</td>\n";
                                        echo "<td>".MB::ucfirst($status)."</td>\n";
                                    echo "</tr>\n";
                                }
                                echo "</tbody>\n";
                                echo "</table>\n";
                            } else {
                                echo "<div style='text-align:center;'>"._t("LIST_EMPTY")."</div>";
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
    
    function ListOfUsers($query="") {
		global $Db,$config_sys;

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
						if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("USERS"))); ?>')) {
							var items = new Array();
							for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
							$.ajax({
								type: "POST",
								dataType: "xml",
								url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=delete",
								data: "items="+items,
								success: function(data){
									location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=userlist';
								}
							});
						}
					} else {
						alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("USER"))); ?>');
					}
				});
			});
			function showmenu(id) {
				$("#menu_"+id).toggle();
				$("#ip_"+id).toggle();
			}
		</script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
			/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("USERS_LIST"); ?></div>
                        <div class="body">
							<?php

							//Pagination
							$limit = Io::GetVar("GET","limit","int",false,10);
							$page = Io::GetVar("GET","page","int",false,1);
							if ($page<=0) $page = 1;
							$from = ($page * $limit) - $limit;
							
                            if ($result = $Db->GetList("SELECT * FROM #__user WHERE (status='active' OR status='inactive') AND uid>0 {$query} ORDER BY uid DESC LIMIT ".intval($from).",".intval($limit))) {
                                echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
									//Delete
									echo "<input type='button' name='delete' value='"._t("DELETE")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
                                echo "</div>\n";

                                echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
								echo "<thead>\n";
									echo "<tr>\n";
										echo "<th width='1%' style='text-align:right;'><input type='checkbox' id='selectall' /></th>\n";
										echo "<th width='22%'>"._t("NAME")."</th>\n";
                                        echo "<th width='12%'>"._t("USER")."</th>\n";
                                        echo "<th width='23%'>"._t("EMAIL")."</th>\n";
										echo "<th width='14%'>"._t("IP")."</th>\n";
										echo "<th width='18%'>"._t("LAST_LOGIN")."</th>\n";
									echo "</tr>\n";
								echo "</thead>\n";
								echo "<tbody>\n";
                                foreach ($result as $row) {
                                    $uid    = Io::Output($row['uid'],'int');
                                    $name   = Io::Output($row['name']);
                                    $user   = Io::Output($row['user']);
                                    $email  = Io::Output($row['email']);
                                    $ip     = Utils::Num2ip(Io::Output($row['lastip']));
                                    $regdate= Time::Output(Io::Output($row['regdate']));
									$lastseen_o = Io::Output($row['lastseen']);
									$lastseen= Time::Output(Io::Output($row['lastseen']));
                                    $status = Io::Output($row['status']);
									if (Time::DateEmpty($lastseen_o)) $lastseen = _t("NEVER");

                                    echo "<tr onmouseover='javascript:showmenu($uid);' onmouseout='javascript:showmenu($uid);'>\n";
                                        echo "<td><input type='checkbox' name='selected[]' value='$uid' class='cb' /><br />&nbsp;</td>\n";
                                        echo "<td><strong>$name</strong>\n";
                                            echo "<div id='menu_$uid' style='display:none; margin-top:2px;'>\n";
												echo "<a href='admin.php?cont="._PLUGIN."&amp;op=info&amp;uid=$uid' title='"._t("MORE_INFO_ABOUT_THIS_X",MB::strtolower(_t("USER")))."'>"._t("INFO")."</a>\n";
												echo " - <a href='admin.php?cont="._PLUGIN."&amp;op=edit&amp;uid=$uid' title='"._t("EDIT_THIS_X",MB::strtolower(_t("USER")))."'>"._t("EDIT")."</a>\n";
											echo "</div>\n";
                                        echo "</td>\n";
                                        echo "<td>$user</td>\n";
                                        echo "<td>$email</td>\n";
                                        echo "<td>$ip\n";
											echo "<div id='ip_$uid' style='display:none; margin-top:2px;'><a href='admin.php?cont=security&amp;op=find&amp;ip=$ip' title='"._t("FIND_X",_t("IP"))."'>"._t("FIND_X",_t("IP"))."</a></div>\n";
										echo "</td>\n";
                                        echo "<td>$lastseen</td>\n";
                                    echo "</tr>\n";
                                }
                                echo "</tbody>\n";
                                echo "</table>\n";
                            } else {
                                echo "<div style='text-align:center;'>"._t("LIST_EMPTY")."</div>";
                            }

							if (empty($query)) {
								include_once(_PATH_ACP_LIBRARIES._DS."MemHT"._DS."content"._DS."pagination.class.php");
								$Pag = new Pagination();
								$Pag->page = $page;
								$Pag->limit = $limit;
								$Pag->query = "SELECT COUNT(uid) AS tot
											   FROM #__user WHERE (status='active' OR status='inactive') AND uid>0 ORDER BY uid";
								$Pag->url = "admin.php?cont="._PLUGIN."&amp;op=userlist&amp;page={PAGE}";
								echo $Pag->Show();
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

	function InfoUser() {
        global $Db,$User,$config_sys;

		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();

		$uid = Io::GetVar('GET','uid','int');

		?>

		<script type="text/javascript" charset="utf-8">
            $(document).ready(function() {
				//Edit
				$('input#edit').click(function() {
                    window.location.href = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=edit&uid=<?php echo $uid; ?>';
                });
				//Delete
				$('input#delete').click(function() {
					if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("USERS"))); ?>')) {
						var items = new Array();
						items[0] = <?php echo $uid; ?>;
						$.ajax({
							type: "POST",
							dataType: "xml",
							url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=delete>",
							data: "items="+items,
							success: function(data){
								location = 'admin.php?cont=<?php echo _PLUGIN; ?>';
							},
							error: function(XMLHttpRequest, textStatus, errorThrown) {
								alert(textStatus+' - '+errorThrown);
							}//TODO: Why throws a "parsererror - undefined" error?
						});
					}
				});
			});
		</script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
			/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("USER"); ?></div>
                        <div class="body">
                            <?php
							if ($row = $Db->GetRow("SELECT * FROM #__user WHERE uid=".intval($uid))) {
								$name		= Io::Output($row['name']);
								$user		= Io::Output($row['user']);
								$email		= Io::Output($row['email']);
								$regdate	= Time::Output(Io::Output($row['regdate']));
								$options	= Utils::Unserialize(Io::Output($row['options']));
								$roles		= Utils::Unserialize(Io::Output($row['roles']));
								$lastseen	= Time::Output(Io::Output($row['lastseen']));
								$ip			= Utils::Num2ip(Io::Output($row['lastip']));
								$status		= Io::Output($row['status']);

								//Roles
								$preroles = Ram::Get("roles");
								$uroles = array("REGISTERED"=>$preroles['REGISTERED']);
								if (isset($roles[0])) foreach ($roles as $role) $uroles[$role] = $preroles[$role];

								//Preferred role
								$prefrole = ((isset($options['prefrole']))) ? $options['prefrole'] : "REGISTERED" ;

                                echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
									//Edit
									echo "<input type='button' name='edit' value='"._t("EDIT")."' style='margin:2px 0;' class='sys_form_button' id='edit' />\n";
									//Delete
									echo "<input type='button' name='delete' value='"._t("DELETE")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
                                echo "</div>\n";

                                echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
								echo "<tbody>\n";
									echo "<tr><td width='50%'><strong>"._t("DISPLAY_NAME")."</strong></td><td width='50%'>$name</td></tr>\n";
									echo "<tr><td><strong>"._t("USERNAME")."</strong></td><td>$user</td></tr>\n";
									echo "<tr><td><strong>"._t("EMAIL")."</strong></td><td>$email</td></tr>\n";
									echo "<tr><td><strong>"._t("REG_DATE")."</strong></td><td>$regdate</td></tr>\n";
									echo "<tr><td><strong>"._t("OPTIONS")."</strong></td><td><pre>\n";
										print_r($options);
									echo "</pre></td></tr>\n";
									echo "<tr><td><strong>"._t("ROLES")."</strong></td><td><pre>\n";
										print_r($uroles);
									echo "</pre></td></tr>\n";
									echo "<tr><td><strong>"._t("LAST_LOGIN")."</strong></td><td>$lastseen</td></tr>\n";
									echo "<tr><td><strong>"._t("IP")."</strong></td><td>$ip - <a href='admin.php?cont=security&amp;op=find&amp;ip=$ip' title='"._t("FIND_X",_t("IP"))."'>"._t("FIND_X",_t("IP"))."</a></td></tr>\n";
									echo "<tr><td><strong>"._t("STATUS")."</strong></td><td>".MB::ucfirst($status)."</td></tr>\n";
									//TODO: Custom profile fields
                                echo "</tbody>\n";
                                echo "</table>\n";
                            } else {
								MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("USER")));
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

    function FindUser() {
		global $Db,$User,$config_sys;

		if (isset($_POST['find']) || isset($_POST['match'])) {
			if (Utils::CheckToken()===true) {
				$uid	= Io::GetVar('POST','uid','int');
				$name	= Io::GetVar('POST','name');
				$user	= Io::GetVar('POST','user');
				$email	= Io::GetVar('POST','email');

				$query = array();
				if (isset($_POST['find'])) {
					if (!empty($uid)) $query[] = "uid LIKE '%".intval($uid)."%'";
					if (!empty($name)) $query[] = "name LIKE '%".$Db->_e($name)."%'";
					if (!empty($user)) $query[] = "user LIKE '%".$Db->_e($user)."%'";
					if (!empty($email)) $query[] = "email LIKE '%".$Db->_e($email)."%'";
				} else if (isset($_POST['match'])) {
					if (!empty($uid)) $query[] = "uid='".intval($uid)."'";
					if (!empty($name)) $query[] = "name='".$Db->_e($name)."'";
					if (!empty($user)) $query[] = "user='".$Db->_e($user)."'";
					if (!empty($email)) $query[] = "email='".$Db->_e($email)."'";
				}
				if (sizeof($query)) $query = "AND (".implode(" OR ",$query).")";
				if (is_array($query)) $query = "";
				$this->ListOfUsers($query);
			} else {
				//Initialize and show site header
				Layout::Header();
				//Start buffering content
				Utils::StartBuffering();

				MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));

				//Assign captured content to the template engine and clean buffer
				Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
				//Draw site template
				Template::Draw();
				//Initialize and show site footer
				Layout::Footer();
			}
		} else {
			//Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();

			?>

			<div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
			<div style="text-align:right;">
			<?php
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
				/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
			?>
			</div>

			<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
				<tr>
					<td style="vertical-align:top;">
						<div class="widget ui-widget-content ui-corner-all">
							<div class="ui-widget-header"><?php echo _t("FIND_X",MB::strtolower(_t("USER"))); ?></div>
							<div class="body">
							<?php
						
							$form = new Form();
							$form->action = "admin.php?cont="._PLUGIN."&amp;op=find";
							$form->Open();

							//Uid
							$form->AddElement(array("element"	=>"text",
													"label"		=>_t("USER_ID"),
													"width"		=>"100px",
													"name"		=>"uid"));

							//Display name
							$form->AddElement(array("element"	=>"text",
													"label"		=>_t("DISPLAY_NAME"),
													"width"		=>"300px",
													"name"		=>"name"));

							//Username
							$form->AddElement(array("element"	=>"text",
													"label"		=>_t("USERNAME"),
													"width"		=>"300px",
													"name"		=>"user"));

							//Email
							$form->AddElement(array("element"	=>"text",
													"label"		=>_t("EMAIL"),
													"width"		=>"300px",
													"name"		=>"email"));

							//Find
							$form->AddElement(array("element"	=>"submit",
													"name"		=>"find",
													"inline"	=>true,
													"value"		=>_t("FIND")));
							//Exact match
							$form->AddElement(array("element"	=>"submit",
													"name"		=>"match",
													"inline"	=>true,
													"value"		=>_t("EXACT_MATCH")));

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
	}

	function ActivateUser() {
		global $Db,$config_sys;

		$items = Io::GetVar("POST","items",false,true);
		
		$result = $Db->Query("UPDATE #__user SET code='',status='active' WHERE uid>0 AND uid IN (".$Db->_e($items).")");
		$total = $Db->AffectedRows();
		
		$res = $Db->GetList("SELECT uid,user,name,email FROM #__user WHERE uid>0 AND uid IN (".$Db->_e($items).")");
		foreach ($res as $row) {
			$uid	= Io::Output($row['uid'],"int");
			$user	= Io::Output($row['user']);
			$name	= Io::Output($row['name']);
			$email	= Io::Output($row['email']);
			
			$loginlink = RewriteUrl($config_sys['site_url']._DS."index.php?"._NODE."="._PLUGIN);
			$message = _t("EMAIL_ACCT_ACTIVATED_TEXT",$name,$user,$config_sys['site_name'],$loginlink,$config_sys['site_name']);
			
			$Email = new Email();
			$Email->AddEmail($email,$name);
			$Email->SetFrom($config_sys['site_email'],$config_sys['site_name']);
			$Email->SetSubject(_t("ACCOUNT_ACTIVATED_AT_X",$config_sys['site_name']));
			$Email->SetContent($message);
			$result = $Email->Send();
			
			if (!$result) {
				MemErr::StoreLog("error_sys","Message: Activation email not sent<br />User: [$uid] $user ($email)<br />File: ".__FILE__."<br />Line: ".__LINE__."<br />Details: ".implode(",",$Email->GetErrors()));
			}			
		}

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

    function DeleteUser() {
		global $Db;

		//TODO: Protect ADMINs

		$items = Io::GetVar("POST","items",false,true);
		$result = $Db->Query("DELETE FROM #__user WHERE uid>0 AND uid IN (".$Db->_e($items).")");
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

	function CreateUser() {
		global $Db,$User,$config_sys;

		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();

		?>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
			/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("CREATE_NEW_X",MB::strtolower(_t("USER"))); ?></div>
                        <div class="body">
                            <?php
							if (!isset($_POST['create'])) {
								$form = new Form();
								$form->action = "admin.php?cont="._PLUGIN."&amp;op=create";
					
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

								?>
										</div>
									</div>
                                </td>
								<td class="sidebar">
                                    <div class="widget ui-widget-content ui-corner-all">
                                      	<div class="ui-widget-header"><?php echo _t("OPTIONS"); ?></div>
										<div class="body">
                                <?php

                                //Required roles
								$result = $Db->GetList("SELECT title,label FROM #__rba_roles ORDER BY rid");
								$rba = array();
								foreach ($result as $row) if (Io::Output($row['label'])!="GUEST") $rba[Io::Output($row['title'])] = Io::Output($row['label']);
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("ROLES"),
														"name"		=>"roles[]",
														"multiple"	=>true,
														"values"	=>$rba,
														"selected"	=>"REGISTERED",
														"info"		=>_t("MULTIPLE_CHOICES_ALLOWED")));

								//Status
                                $form->AddElement(array("element"	=>"select",
                                                        "label"		=>_t("STATUS"),
                                                        "name"		=>"status",
                                                        "values"	=>array(_t("ACTIVE")    => "active",
                                                                            _t("INACTIVE") 	=> "inactive",
                                                                            _t("MODERATE")  => "moderate"),
                                                        "selected"  =>"active"));

                                ?>
										</div>
									</div>
                                <?php

								//Submit
								$form->AddElement(array("element"	=>"submit",
														"name"		=>"create",
														"value"		=>_t("CREATE")));
								$form->Close();
							} else {
								if (Utils::CheckToken()===true) {
									$displayname = Io::GetVar('POST','displayname');
									$username = Io::GetVar('POST','username');
									$password = Io::GetVar('POST','password');
									$cpassword = Io::GetVar('POST','cpassword');
									$email = Io::GetVar('POST','email');
									$roles = Io::GetVar('POST','roles','nohtml',true,array());
									$status = Io::GetVar('POST','status','nohtml');

									$errors = array();
									if (empty($username))	$errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("USERNAME"));
									if (empty($password))	$errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("PASSWORD"));
									if (empty($cpassword))	$errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("CONFIRM_X",MB::strtolower(_t("PASSWORD"))));
									if (empty($email))		$errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("EMAIL"));

									if (!preg_match("#^[a-zA-Z0-9]{4,}$#is",$username)) $errors[] = _t("THE_FIELD_X_IS_NOT_INVALID",MB::strtolower(_t("USERNAME")));
									if (!Utils::ValidEmail($email)) $errors[] = _t("THE_FIELD_X_IS_NOT_INVALID",MB::strtolower(_t("EMAIL")));
									if ($password!=$cpassword) $errors[] = _t("PASS_DONT_MATCH");
									if ($row = $Db->GetRow("SELECT uid FROM #__user WHERE (user='".$Db->_e($username)."' OR email='".$Db->_e($email)."')")) {
										$errors[] = _t("USER_OR_EMAIL_ALREADY_EXIST");
									}

									if (!sizeof($errors)) {
										$roles = Utils::Serialize($roles);

										$Db->Query("INSERT INTO #__user (uid,user,name,pass,email,regdate,roles,status)
													VALUES (null,'".$Db->_e($username)."','".$Db->_e($displayname)."','".md5($password)."','".$Db->_e($email)."',NOW(),'".$Db->_e($roles)."','".$Db->_e($status)."')");

										MemErr::Trigger("INFO",_t("ACCOUNT_CREATED"));
									} else {
										MemErr::Trigger("USERERROR",implode("<br />",$errors));
									}
								} else {
									MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
								}
							?>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
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

	function EditUser() {
		global $Db,$User,$config_sys;

		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		?>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
			/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
		?>
        </div>

        <?php
		$uid = Io::GetVar('GET','uid','int');
		?>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("EDIT"); ?></div>
                        <div class="body">
                            <?php
							if ($row = $Db->GetRow("SELECT * FROM #__user WHERE uid>0 AND uid=".intval($uid))) {
								if (!isset($_POST['save'])) {
									$name		= Io::Output($row['name']);
									$user		= Io::Output($row['user']);
									$email		= Io::Output($row['email']);
									$roles		= Utils::Unserialize(Io::Output($row['roles']));
									$status		= Io::Output($row['status']);

									//Roles
									$preroles = Ram::Get("roles");
									$uroles = array("REGISTERED"=>$preroles['REGISTERED']);
									if (isset($roles[0])) foreach ($roles as $role) $uroles[$role] = $preroles[$role];
									
									$form = new Form();
									$form->action = "admin.php?cont="._PLUGIN."&amp;op=edit&amp;uid=$uid";
						
									$form->Open();
									
									//Display name
									$form->AddElement(array("element"	=>"text",
															"label"		=>_t("DISPLAY_NAME"),
															"name"		=>"displayname",
															"value"		=>$name));

									//Username
									$form->AddElement(array("element"	=>"text",
															"label"		=>_t("USERNAME"),
															"name"		=>"username",
															"value"		=>$user,
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
															"name"		=>"email",
															"value"		=>$email));

									?>
											</div>
										</div>
									</td>
									<td class="sidebar">
										<div class="widget ui-widget-content ui-corner-all">
											<div class="ui-widget-header"><?php echo _t("OPTIONS"); ?></div>
											<div class="body">
									<?php

									//Required roles
									$result = $Db->GetList("SELECT title,label FROM #__rba_roles ORDER BY rid");
									$rba = array();
									if (!sizeof($roles) || empty($roles)) $roles = array('REGISTERED');
									foreach ($result as $row) if (Io::Output($row['label'])!="GUEST") $rba[Io::Output($row['title'])] = Io::Output($row['label']);
									$form->AddElement(array("element"	=>"select",
															"label"		=>_t("ROLES"),
															"name"		=>"roles[]",
															"multiple"	=>true,
															"values"	=>$rba,
															"selected"	=>$roles,
															"info"		=>_t("MULTIPLE_CHOICES_ALLOWED")));

									//Status
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("STATUS"),
                                                            "name"		=>"status",
                                                            "values"	=>array(_t("ACTIVE")    => "active",
                                                                                _t("INACTIVE") 	=> "inactive",
                                                                                _t("MODERATE")  => "moderate"),
                                                            "selected"  =>$status));

									?>
											</div>
										</div>
									<?php

									//Submit
									$form->AddElement(array("element"	=>"submit",
															"name"		=>"save",
															"value"		=>_t("SAVE")));
									
									$form->Close();
								} else {
									if (Utils::CheckToken()===true) {
										$displayname = Io::GetVar('POST','displayname');
										$username = Io::GetVar('POST','username');
										$password = Io::GetVar('POST','password');
										$cpassword = Io::GetVar('POST','cpassword');
										$email = Io::GetVar('POST','email');
										$roles = Io::GetVar('POST','roles','nohtml',true,array());
										$status = Io::GetVar('POST','status','nohtml');

										$errors = array();
										if (empty($username))	$errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("USERNAME"));
										if (empty($email))		$errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("EMAIL"));

										if (!preg_match("#^[a-zA-Z0-9]{4,}$#is",$username)) {
											$errors[] = _t("THE_FIELD_X_IS_NOT_INVALID",MB::strtolower(_t("USERNAME")));
										}
										if (!Utils::ValidEmail($email)) {
											$errors[] = _t("THE_FIELD_X_IS_NOT_INVALID",MB::strtolower(_t("EMAIL")));
										}
										$pass = "";
										if (!empty($password) || !empty($cpassword)) {
											if ($password==$cpassword) {
												$pass = ",pass='".md5($password)."'";
											} else {
												$errors[] = _t("PASS_DONT_MATCH");
											}
										}
										if ($row = $Db->GetRow("SELECT uid FROM #__user WHERE (user='".$Db->_e($username)."' OR email='".$Db->_e($email)."') AND uid!=".intval($uid))) {
											$errors[] = _t("USER_OR_EMAIL_ALREADY_EXIST");
										}

										if (!sizeof($errors)) {
											$roles = Utils::Serialize($roles);

											$Db->Query("UPDATE #__user SET user='".$Db->_e($username)."',name='".$Db->_e($displayname)."'{$pass},email='".$Db->_e($email)."',roles='".$Db->_e($roles)."',status='".$Db->_e($status)."' WHERE uid=".intval($uid));

											MemErr::Trigger("INFO",_t("ACCOUNT_MODIFIED"));
										} else {
											MemErr::Trigger("USERERROR",implode("<br />",$errors));
										}
									} else {
										MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
									}
								?>
								</div>
							</div>
							<?php
							}
						} else {
							MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("USER")));
						}
                        ?>
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

	function UsersProfile() {
		global $Db,$User,$config_sys;

		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();

		?>

		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				//Create
				$('input#create').click(function() {
                    window.location.href = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=createfield';
                });

				//Delete permanently
				$('input#delete').click(function() {
					var obj = $('.cb:checkbox:checked');
					if (obj.length>0) {
						if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("FIELDS"))); ?>')) {
							var items = new Array();
							for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
							$.ajax({
								type: "POST",
								dataType: "xml",
								url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=deletefield",
								data: "items="+items,
								success: function(data){
									location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=profile';
								}
							});
						}
					} else {
						alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("FIELD"))); ?>');
					}
				});
			});
		</script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
			/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("CUSTOM_PROFILE_FIELDS"); ?></div>
                        <div class="body">
                            <?php
		echo "<div style='float:left; margin:6px 0 2px 0;'>\n";
            //Create section
			echo "<input type='button' name='create' value='"._t("CREATE_NEW_X",MB::strtolower(_t("FIELD")))."' style='margin:2px 0;' class='sys_form_button' id='create' />\n";
		echo "</div>\n";
		echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
			//Delete permanently
			echo "<input type='button' name='delete' value='"._t("DELETE_PERMANENTLY")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
		echo "</div>\n";

        echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
            echo "<thead>\n";
			echo "<tr>\n";
				echo "<th width='1%' style='text-align:right;'></th>\n";
				echo "<th width='35%'>"._t("NAME")."</th>\n";
				echo "<th width='35%'>"._t("LABEL")."</th>\n";
				echo "<th width='29%'>"._t("TYPE")."</th>\n";
			echo "</tr>\n";
			echo "</thead>\n";
			echo "<tbody>\n";

            if ($result = $Db->GetList("SELECT * FROM #__user_profile ORDER BY name")) {
				foreach ($result as $row) {
					$id		= Io::Output($row['id']);
					$label	= Io::Output($row['label']);
					$name	= Io::Output($row['name']);
					$type	= Io::Output($row['type']);

                    echo "<tr>\n";
						echo "<td><input type='checkbox' name='selected[]' value='$id' class='cb' /></td>\n";
						echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=editfield&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("FIELD")))."'>$name</a></td>\n";
						echo "<td>$label</td>\n";
						echo "<td>$type</td>\n";
					echo "</tr>\n";
				}
			} else {
				echo "<tbody>\n";
				echo "<tr>\n";
					echo "<td style='text-align:center;' colspan='4'>"._t("LIST_EMPTY")."</td>\n";
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

    function DeleteProfileField() {
		global $Db;

		//TODO: Protect ADMINs

		$items = Io::GetVar("POST","items",false,true);
		$result = $Db->Query("DELETE FROM #__user_profile WHERE id IN (".$Db->_e($items).")");
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

	function CreateProfileField() {
		global $Db,$User,$config_sys;

		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();

		?>

        <script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>alphanumeric<?php echo _DS; ?>jquery.alphanumeric.js"></script>
        <script type="text/javascript">
        	$(document).ready(function() {
				$('#urlvalidname').alphanumeric({
					allow:"-",
					nocaps:true
				});
			});
        </script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
			/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("CUSTOM_PROFILE_FIELDS"); ?></div>
                        <div class="body">

						<?php

						if (!isset($_POST['create'])) {
							$form = new Form();
							$form->action = "admin.php?cont="._PLUGIN."&amp;op=createfield";

							$form->Open();

							//Name
							$form->AddElement(array("element"	=>"text",
													"label"		=>_t("NAME"),
													"width"		=>"300px",
													"name"		=>"name"));

							//Label
							$form->AddElement(array("element"	=>"text",
													"label"		=>_t("LABEL"),
													"name"		=>"label",
													"width"		=>"300px",
													"id"		=>"urlvalidname",
													"info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));

							//Type
							$form->AddElement(array("element"	=>"select",
													"label"		=>_t("TYPE"),
													"name"		=>"type",
													"values"	=>array(_t("TEXT") => "text",
																		_t("TEXTAREA") => "textarea")));

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
								$name = Io::GetVar('POST','name','fullhtml');
								$label = Io::GetVar('POST','label','[^a-zA-Z0-9\-]');
								$type = Io::GetVar('POST','type','nohtml');

								$errors = array();
								if (empty($name)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("NAME"));
								if (empty($label)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LABEL"));

								if (!sizeof($errors)) {
									$Db->Query("INSERT INTO #__user_profile (label,name,type)
												VALUES ('".$Db->_e($label)."','".$Db->_e($name)."','".$Db->_e($type)."')");

									Utils::Redirect("admin.php?cont="._PLUGIN."&op=profile");
								} else {
									MemErr::Trigger("USERERROR",implode("<br />",$errors));
								}
							} else {
								MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
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

	function EditProfileField() {
		global $Db,$User,$config_sys;

		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();

		?>

        <script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>alphanumeric<?php echo _DS; ?>jquery.alphanumeric.js"></script>
        <script type="text/javascript">
        	$(document).ready(function() {
				$('#urlvalidname').alphanumeric({
					allow:"-",
					nocaps:true
				});
			});
        </script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
			/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("CUSTOM_PROFILE_FIELDS"); ?></div>
                        <div class="body">

						<?php

						$id = Io::GetVar('GET','id','int');
						if ($row = $Db->GetRow("SELECT * FROM #__user_profile WHERE id=".intval($id))) {
							if (!isset($_POST['save'])) {
								$label	= Io::Output($row['label']);
								$name	= Io::Output($row['name']);
								$type	= Io::Output($row['type']);

								$form = new Form();
								$form->action = "admin.php?cont="._PLUGIN."&amp;op=editfield&amp;id=$id";

								$form->Open();

								//Name
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("NAME"),
														"width"		=>"300px",
														"name"		=>"name",
														"value"		=>$name));

								//Label
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("LABEL"),
														"name"		=>"label",
														"value"		=>$label,
														"width"		=>"300px",
														"id"		=>"urlvalidname",
														"info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));

								//Type
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("TYPE"),
														"name"		=>"type",
														"values"	=>array(_t("TEXT") => "text",
																			_t("TEXTAREA") => "textarea"),
														"selected"	=>$type));

								//Save
								$form->AddElement(array("element"	=>"submit",
														"name"		=>"save",
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
									$name = Io::GetVar('POST','name','fullhtml');
									$label = Io::GetVar('POST','label','[^a-zA-Z0-9\-]');
									$type = Io::GetVar('POST','type','nohtml');

									$errors = array();
									if (empty($name)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("NAME"));
									if (empty($label)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LABEL"));

									if (!sizeof($errors)) {
										$Db->Query("UPDATE #__user_profile SET label='".$Db->_e($label)."',name='".$Db->_e($name)."',type='".$Db->_e($type)."' WHERE id=".intval($id));

										Utils::Redirect("admin.php?cont="._PLUGIN."&op=profile");
									} else {
										MemErr::Trigger("USERERROR",implode("<br />",$errors));
									}
								} else {
									MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
								}
							}
						} else {
							MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("FIELD")));
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

	function ProhibitedUsers() {
		global $Db,$User,$config_sys;

		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();

		$subop = Io::GetVar('GET','subop');

		?>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
			/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header">
						<?php
							switch ($subop) {
								default:
								case "users":
									echo _t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")));
									break;
								case "domains":
									echo _t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")));
									break;
							}
						?>
						</div>
                        <div class="body">
                            Coming soon...
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
	
	function ListInvitations() {
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
	                    window.location.href = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=addinvites';
		            });
		
		            //Delete permanently
					$('input#delete').click(function() {
					    var obj = $('.cb:checkbox:checked');
	                    if (obj.length>0) {
					        if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("INVITATIONS"))); ?>')) {
							    var items = new Array();
					            for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
								$.ajax({
									type: "POST",
									dataType: "xml",
									url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=delinvites",
									data: "items="+items,
									success: function(data){
									    location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=invitations';
									}
								});
							}
						} else {
							alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("INVITATION"))); ?>');
						}
					});
			    });
		    </script>        
	        
		    <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
	        <div style="text-align:right;">
	        <?php
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
	            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
				/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
			?>
	        </div>
	        <?php
	        	
			echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' summary=''>";
			echo "<tr>";
			    echo "<td style='vertical-align:top;'>";            
	                echo "<div class='widget ui-widget-content ui-corner-all'>";
	                    echo "<div class='ui-widget-header'>"._t("MANAGE_INVITATIONS")."</div>";
	                	echo "<div class='body'>";
	                    
				            echo "<div style='float:left; margin:6px 0 2px 0;'>\n";
					            echo "<input type='button' name='create' value='"._t("ADD_NEW_X",MB::strtolower(_t("INVITATION_CODE")))."' style='margin:2px 0;' class='sys_form_button' id='create' />\n";
				            echo "</div>\n";
				            echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
					            echo "<input type='button' name='delete' value='"._t("DELETE_PERMANENTLY")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
				            echo "</div>\n";
	                                    
	            	        echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
	            	            echo "<thead>\n";
	            				echo "<tr>\n";
	            					echo "<th width='1%' style='text-align:right;'><input type='checkbox' id='selectall' /></th>\n";
	            					echo "<th width='54%'>"._t("INVITATION_CODE")."</th>\n";
	            					echo "<th width='30%'>"._t("NUM_OF_INVITES")."</th>\n";
	                                echo "<th width='15%'>"._t("EXPIRATION_DATE")."</th>\n";
	            				echo "</tr>\n";
	            				echo "</thead>\n";
	            				echo "<tbody>\n";
	
		                            if ($result = $Db->GetList("SELECT * FROM #__user_invites ORDER BY id")) {
						                foreach ($result as $row) {
							                $id	            = Io::Output($row['id'],"int");
						                    $code	        = Io::Output($row['code']);
							                $registrations	= Io::Output($row['registrations']);
		                                    $expiration 	= Io::Output($row['expiration']);
		                                    
	                                        echo "<tr>\n";
								                echo "<td><input type='checkbox' name='selected[]' value='$id' class='cb' /></td>\n";
								                echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=editinvites&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("INVITATION")))."'>$code</a></td>\n";
								                echo "<td>$registrations</td>\n";
	                                            echo "<td>$expiration</td>\n";
							                echo "</tr>\n";
						                }
		                            } else {
						                echo "<tr>\n";
							                echo "<td style='text-align:center;' colspan='4'>"._t("LIST_EMPTY")."</td>\n";
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
	    
	    function AddInvitations() { 
		    global $Db,$config_sys;
	        //Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();
	        ?>
	        <script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>alphanumeric<?php echo _DS; ?>jquery.alphanumeric.js"></script>
	        <script type="text/javascript">
	        	$(document).ready(function() {
	        		$('#numberonly').numeric();
					$(".datepicker").datepicker({
						dateFormat: 'yy-mm-dd',
						minDate: 0
					});
				});
	        </script>
	        
	        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
            <div style="text-align:right;">
            <?php
        		echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
	            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
				/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
        		echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
        	?>
            </div>
	        
	        <?php		
			
	        echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' summary=''>";
			echo "<tr>";
			    echo "<td style='vertical-align:top;'>";            
	                echo "<div class='widget ui-widget-content ui-corner-all'>";
	                    echo "<div class='ui-widget-header'>"._t("ADD_NEW_X",MB::strtolower(_t("INVITATION")))."</div>";
	                	echo "<div class='body'>";
	
						    if (!isset($_POST['add'])) {
							    $form = new Form();
								$form->action = "admin.php?cont="._PLUGIN."&amp;op=addinvites";
			
								$form->Open();
			
								//Code
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("INVITATION_CODE"),
														"width"		=>"300px",
														"name"		=>"code",
														"id"		=>"code"));
			
								//Registrations
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("NUM_OF_INVITES"),
														"width"		=>"150px",
														"name"		=>"registrations",
														"id"		=>"numberonly"));
								//Expiration date
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("EXPIRATION_DATE"),
														"name"		=>"expiration",
														"class"		=>"sys_form_text datepicker",
														"width"		=>"150px",
														"suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."calendar.png' alt='Expiration' />"));
			
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
									$code = Io::GetVar('POST','code','nohtml');
	                                $registrations = Io::GetVar('POST','registrations','int');
	                                $expiration = Io::GetVar('POST','expiration',false,true,'2099-12-31 00:00:00');
			                        
	                                $errors = array();
									if (empty($code)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("INVITATION_CODE"));
									if (empty($registrations)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("NUM_OF_INVITES"));
	                                
									if (!sizeof($errors)) {
									    $Db->Query("INSERT INTO #__user_invites (code,registrations,expiration)
													VALUES ('".$Db->_e($code)."','".$Db->_e($registrations)."','".$Db->_e($expiration)."')");
	                                             	
										Utils::Redirect("admin.php?cont="._PLUGIN."&op=invitations");
									} else {
										MemErr::Trigger("USERERROR",implode("<br />",$errors));
									}
								} else {
								    MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
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
	    
	    function EditInvitations() { 
		    global $Db,$config_sys;
	        //Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();
	        ?>
	        <script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>alphanumeric<?php echo _DS; ?>jquery.alphanumeric.js"></script>
	        <script type="text/javascript">
	        	$(document).ready(function() {
	        		$('#numberonly').numeric();
					$(".datepicker").datepicker({
						dateFormat: 'yy-mm-dd',
						minDate: 0
					});
				});
	        </script>
	        
	        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
            <div style="text-align:right;">
            <?php
        		echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
	            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
				/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
        		echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
        	?>
            </div>
	        
	        <?php		
	        
	        echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' summary=''>";
			echo "<tr>";
			    echo "<td style='vertical-align:top;'>";            
	                echo "<div class='widget ui-widget-content ui-corner-all'>";
	                    echo "<div class='ui-widget-header'>"._t("EDIT_X",MB::strtolower(_t("INVITATIONS")))."</div>";
	                	echo "<div class='body'>";
	                        $id = Io::GetVar('GET','id','int');
						    if ($row = $Db->GetRow("SELECT * FROM #__user_invites WHERE id=".intval($id))) {
	                            if (!isset($_POST['save'])) {
	    						    $form = new Form();
	    							$form->action = "admin.php?cont="._PLUGIN."&amp;op=editinvites&amp;id=$id";
	    		
	    							$form->Open();
	    		
	    							//Code
	    							$form->AddElement(array("element"	=>"text",
	    													"label"		=>_t("INVITATION_CODE"),
	    													"width"		=>"300px",
	    													"name"		=>"code",
	    													"value"		=>Io::Output($row['code']),
	                                                        "id"		=>"code"));
	    		
	    							//Registrations
	    							$form->AddElement(array("element"	=>"text",
	    													"label"		=>_t("NUM_OF_INVITES"),
	    													"width"		=>"300px",
	    													"name"		=>"registrations",
	    													"value"		=>Io::Output($row['registrations']),
	                                                        "id"		=>"numberonly"));
								    //Expiration date
								    $form->AddElement(array("element"	=>"text",
														    "label"		=>_t("EXPIRATION_DATE"),
														    "name"		=>"expiration",
														    "class"		=>"sys_form_text datepicker",
														    "width"		=>"150px",
														    "value"		=>Io::Output($row['expiration']),
	                                                        "suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."calendar.png' alt='Expiration' />"));                                                        
	    		
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
	    								$code = Io::GetVar('POST','code','nohtml');
	                                    $registrations = Io::GetVar('POST','registrations','int');
	                                    $expiration = Io::GetVar('POST','expiration',false,true,'2001-01-01 00:00:00');
	    		                        
	                                    $errors = array();
	    								if (empty($code)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("INVITATION_CODE"));
	    								if (empty($registrations)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("NUM_OF_INVITES"));
	                                    
	    								if (!sizeof($errors)) {            
	                                        $Db->Query("UPDATE #__user_invites SET code='".$Db->_e($code)."',registrations='".$Db->_e($registrations)."',expiration='".$Db->_e($expiration)."' WHERE id=".intval($id));          	
	    									Utils::Redirect("admin.php?cont="._PLUGIN."&op=invitations");
	    								} else {
	    									MemErr::Trigger("USERERROR",implode("<br />",$errors));
	    								}
	    							} else {
	    							    MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
	    							}                               
	    					    }
							} else {
								MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("CHARACTER")));
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
	    
	    function DeleteInvitations() { 
		    global $Db;
			
			$items = Io::GetVar("POST","items",false,true);
			
			$result = $Db->Query("DELETE FROM #__user_invites WHERE id IN (".$Db->_e($items).")") ? 1 : 0 ;
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
	    
	    //Roles
	    function ListRoles() {
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
                        window.location.href = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=addroles';
    	            });
    	
    	            //Delete permanently
    				$('input#delete').click(function() {
    				    var obj = $('.cb:checkbox:checked');
                        if (obj.length>0) {
    				        if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("ROLES"))); ?>')) {
    						    var items = new Array();
    				            for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
    							$.ajax({
    								type: "POST",
    								dataType: "xml",
    								url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=delroles",
    								data: "items="+items,
    								success: function(data){
    								    location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=roles';
    								}
    							});
    						}
    					} else {
    						alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("ROLE"))); ?>');
    					}
    				});
    		    });
    	    </script>

    	    <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
    	    <div style="text-align:right;">
    	    <?php
    	    	echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
	            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
				/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
    	    	echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
    	    ?>
    	    </div>
            <?php
            
    		echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' summary=''>";
    		echo "<tr>";
    		    echo "<td style='vertical-align:top;'>";
                
                    echo "<div class='widget ui-widget-content ui-corner-all'>";
                        echo "<div class='ui-widget-header'>"._t("MANAGE_ROLES")."</div>";
                    	echo "<div class='body'>";
                        
    			            echo "<div style='float:left; margin:6px 0 2px 0;'>\n";
    				            echo "<input type='button' name='create' value='"._t("ADD_NEW_X",MB::strtolower(_t("ROLE")))."' style='margin:2px 0;' class='sys_form_button' id='create' />\n";
    			            echo "</div>\n";
    			            echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
    				            echo "<input type='button' name='delete' value='"._t("DELETE_PERMANENTLY")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
    			            echo "</div>\n";                    
                            
                	        echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
                	            echo "<thead>\n";
                				echo "<tr>\n";
                					echo "<th width='1%' style='text-align:right;'><input type='checkbox' id='selectall' /></th>\n";
                					echo "<th width='50%'>"._t("LABEL")."</th>\n";
                					echo "<th width='49%'>"._t("TITLE")."</th>\n";
                				echo "</tr>\n";
                				echo "</thead>\n";
                				echo "<tbody>\n";                        
    
                                    if($list = $Db->GetList("SELECT * FROM #__rba_roles ORDER BY rid")) {
                                        foreach($list AS $row) {
                    					    $rid	 = Io::Output($row['rid'],"int");
                                            $label   = Io::Output($row['label']);
                                            $title   = Io::Output($row['title']);
                    						$options = Utils::Unserialize(Io::Output($row['options']));                                                            
                                            $static = Io::Output($row['static']);
                                            $disable = ($static) ? "disabled" : "class='cb'";
                                            $style = isset($options['style']) ? $style = $options['style'] : "";
                                            $color = isset($options['color']) ? $color = $options['color'] : "";
                                         
                                            if($style) {
                                                switch ($style){ 
                                                	case 'bold':
                                                	    $title = sprintf("<strong>%s</strong>",$title);
                                                    break;                                           
                                                	case 'italic':
                                                	    $title = sprintf("<em>%s</em>",$title);
                                                    break;                                                                                          
                                                }                                             
                                            }
                                            
                                            if($color) { $title = sprintf("<font color=".$color.">%s</font>", $title); }
    
                                            echo "<tr onmouseover='javascript:showmenu($rid);' onmouseout='javascript:showmenu($rid);'>\n";
    							                echo "<td><input type='checkbox' $disable name='selected[]' value='$rid'/></td>\n";
    							                echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=editroles&amp;id=$rid' title='"._t("EDIT_THIS_X",MB::strtolower(_t("ROLE")))."'>$label</a></td>\n";
                                                echo "<td>$title</td>\n";
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
        
        function AddRole() { 
    		global $Db,$config_sys;
            //Initialize and show site header
    		Layout::Header();
    		//Start buffering content
    		Utils::StartBuffering();
    		?>
    		<script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>alphanumeric<?php echo _DS; ?>jquery.alphanumeric.js"></script>
            <script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>colorpicker<?php echo _DS; ?>colorpicker.js"></script>
            <link rel="stylesheet" href="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>colorpicker<?php echo _DS; ?>css<?php echo _DS; ?>colorpicker.css" type="text/css" />
            <script type="text/javascript">
            	$(document).ready(function() {
            		$('#label').alphanumeric({
    					allow:"-",
    					nocaps:true
    				});
                    $('#colorpicker').ColorPicker({
    	                onSubmit: function(hsb, hex, rgb, el) {
    		                $(el).val(hex);
    		                $(el).ColorPickerHide();
    	                },
    	                onBeforeShow: function () {
    		                $(this).ColorPickerSetColor(this.value);
    	                },
    	                onChange: function (hsb, hex, rgb, el) {	
                            $('#colorpicker').val(hex);
    	                }    
                    })
    			});
            </script>        
            <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
    	    <div style="text-align:right;">
    	    <?php
    	    	echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
	            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
				/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
    	    	echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
    	    ?>
    	    </div>
            <?php
            
    		echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' summary=''>";
    		echo "<tr>";
    		    echo "<td style='vertical-align:top;'>";
                
                    echo "<div class='widget ui-widget-content ui-corner-all'>";
                        echo "<div class='ui-widget-header'>"._t("ADD_NEW_X",MB::strtolower(_t("ROLE")))."</div>";
                    	echo "<div class='body'>";
                        
    					    if (!isset($_POST['add'])) {
    						    $form = new Form();
    							$form->action = "admin.php?cont="._PLUGIN."&amp;op=addroles";
    		
    							$form->Open();
    		
    							//Label
    							$form->AddElement(array("element"	=>"text",
    													"label"		=>_t("LABEL"),
    													"width"		=>"300px",
    													"name"		=>"label",
    													"id"		=>"label",
    													"info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));
    		
    							//Title
    							$form->AddElement(array("element"	=>"text",
    													"label"		=>_t("TITLE"),
    													"width"		=>"300px",
    													"name"		=>"title",
    													"id"		=>"title"));
    							//Color
    							$form->AddElement(array("element"	=>"text",
    													"label"		=>_t("COLOR"),
    													"width"		=>"100px",
    													"name"		=>"color",
        												"id"		=>"colorpicker",
                                                        "suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."colorpicker.png' alt='Colors' />"));                                                  
    							//Style
    							$sel = sprintf("--%s--",_t("STYLE"));
                                $form->AddElement(array("element"	=>"select",
    													"label"		=>_t("STYLE"),
    													"name"		=>"style",
    													"width"		=>"110px",
                                                        "values"	=>array($sel       => '',
                                                                           _t("BOLD") => 'bold',
    									                                   _t("ITALIC") => 'italic')));                                                    					    
    
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
    								$label = MB::strtoupper(Io::GetVar('POST','label','nohtml'));
                                    $title = Io::GetVar('POST','title','nohtml');
                                    $color = Io::GetVar('POST','color','nohtml');
                                    $style = Io::GetVar('POST','style','nohtml');
                                    		                        
                                    $errors = array();
    								if (empty($label)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LABEL"));
    								if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
                                    if (!empty($color) && strlen($color)!=6) $errors[] = _t("THE_FIELD_X_IS_NOT_INVALID",_t("COLOR"));                                
                                    
    								if (!sizeof($errors)) {
    								    $options = array();
                                        if (!empty($color)) { $options['color'] = "#".$color; }
    						            if (!empty($style)) $options['style'] = $style;
    						            $options = Utils::Serialize($options);
                                        $Db->Query("INSERT INTO #__rba_roles (label,title,options)
    												VALUES ('".$Db->_e($label)."','".$Db->_e($title)."','".$Db->_e($options)."')");
                                                 	
    									Utils::Redirect("admin.php?cont="._PLUGIN."&op=roles");
    								} else {
    									MemErr::Trigger("USERERROR",implode("<br />",$errors));
    								}
    							} else {
    							    MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
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
                     
        function EditRole() { 
    		global $Db,$config_sys;
            //Initialize and show site header
    		Layout::Header();
    		//Start buffering content
    		Utils::StartBuffering();
    		?>
            <script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>alphanumeric<?php echo _DS; ?>jquery.alphanumeric.js"></script>
            <script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>colorpicker<?php echo _DS; ?>colorpicker.js"></script>
            <link rel="stylesheet" href="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>colorpicker<?php echo _DS; ?>css<?php echo _DS; ?>colorpicker.css" type="text/css" />
            <script type="text/javascript">
            	$(document).ready(function() {
            		$('#label').alphanumeric({
    					allow:"-",
    					nocaps:true
    				});
                    $('#colorpicker').ColorPicker({
    	                onSubmit: function(hsb, hex, rgb, el) {
    		                $(el).val(hex);
    		                $(el).ColorPickerHide();
    	                },
    	                onBeforeShow: function () {
    		                $(this).ColorPickerSetColor(this.value);
    	                },
    	                onChange: function (hsb, hex, rgb, el) {	
                            $('#colorpicker').val(hex);
    	                }    
                    })
    			});
            </script>        
            <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
    	    <div style="text-align:right;">
    	    <?php
    	    	echo "<a href='admin.php?cont="._PLUGIN."&amp;op=userlist' title='"._t("USERS_LIST")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."users.png' alt='"._t("USERS_LIST")."' /></a>\n";
	            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=find' title='"._t("FIND_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."finduser.png' alt='"._t("FIND_X",MB::strtolower(_t("USER")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."createuser.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("USER")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=profile' title='"._t("CUSTOM_PROFILE_FIELDS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userfields.png' alt='"._t("CUSTOM_PROFILE_FIELDS")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=roles' title='"._t("ROLES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userlock.png' alt='"._t("ROLES")."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=invitations' title='"._t("MANAGE_INVITATIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."userinvites.png' alt='"._t("MANAGE_INVITATIONS")."' /></a>\n";
				/*echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=users' title='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nouser.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("USERNAMES")))."' /></a>\n";
    	    	echo "<a href='admin.php?cont="._PLUGIN."&amp;op=prohibited&amp;subop=domains' title='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."nomail.png' alt='"._t("PROHIBITED_X",MB::strtolower(_t("EMAIL_DOMAINS")))."' /></a>\n";*/
    	    ?>
    	    </div>
            <?php
            
    		echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' summary=''>";
    		echo "<tr>";
    		    echo "<td style='vertical-align:top;'>";
                
                    echo "<div class='widget ui-widget-content ui-corner-all'>";
                        echo "<div class='ui-widget-header'>"._t("EDIT_X",MB::strtolower(_t("ROLE")))."</div>";
                    	echo "<div class='body'>";
                            $id = Io::GetVar('GET','id','int');
                            if ($row = $Db->GetRow("SELECT * FROM #__rba_roles WHERE rid=".intval($id))) {
                                if (!isset($_POST['save'])) {
        						    $form = new Form();
        		                    $form->action = "admin.php?cont="._PLUGIN."&amp;op=editroles&amp;id=$id";
        							$form->Open();    		                      
        							
                                    $disabled = (Io::Output($row['static'])) ? true : false ;                                
                                    
                                    //Label
        							$form->AddElement(array("element"	=>"text",
        													"label"		=>_t("LABEL"),
        													"width"		=>"300px",
        													"disabled"  =>$disabled,                                                      
                                                            "name"		=>"label",
        													"value"		=>Io::Output($row['label']),
                                                            "id"		=>"label",
        													"info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));
        		
        							//Title
        							$form->AddElement(array("element"	=>"text",
        													"label"		=>_t("TITLE"),
        													"width"		=>"300px",
        													"name"		=>"title",
                                                            "value"		=>Io::Output($row['title']),
        													"id"		=>"title"));
        							
                                    $options = Utils::Unserialize(Io::Output($row['options']));                                
                                    $color = isset($options['color']) ? MB::substr($options['color'],1) : "";
                                    //Color
        							$form->AddElement(array("element"	=>"text",
        													"label"		=>_t("COLOR"),
        													"width"		=>"100px",
        													"name"		=>"color",
                                                            "value"		=>$color,
        													"id"		=>"colorpicker",
                                                            "suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."colorpicker.png' alt='Colors' />"));                                                    
        							//Style
        							$sel = sprintf("--%s--",_t("STYLE"));
                                    $style = isset($options['style']) ? $options['style'] : "";
                                    $form->AddElement(array("element"	=>"select",
        													"label"		=>_t("STYLE"),
        													"name"		=>"style",
        													"width"		=>"110px",
                                                            "selected"	=>$style,
                                                            "values"	=>array($sel         => '',
                                                                                _t("BOLD")   => 'bold',
    									                                        _t("ITALIC") => 'italic')));                                                     					    
                                    
        							//Add
        							$form->AddElement(array("element"	=>"submit",
        													"name"		=>"save",
        													"inline"	=>true,
        													"value"		=>_t("SAVE")));
        		
        							$form->Close();
        		
        					    } else {
        							//Check token
        							if (Utils::CheckToken()) {
        							    //Get POST data                                    
                                        $label = (Io::Output($row['static'])) ? Io::Output($row['label']) : MB::strtoupper(Io::GetVar('POST','label','nohtml'));
                                        $title = Io::GetVar('POST','title','nohtml');
                                        $color = Io::GetVar('POST','color','nohtml');
                                        $style = Io::GetVar('POST','style','nohtml');
                                        		                        
                                        $errors = array();
        								if (empty($label)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LABEL"));
        								if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
                                        if (!empty($color) && strlen($color)!=6) $errors[] = _t("THE_FIELD_X_IS_NOT_INVALID",_t("COLOR"));                                
                                        
        								if (!sizeof($errors)) {
        								    $options = array();
                                            if (!empty($color)) { $options['color'] = "#".$color; }
        						            if (!empty($style)) $options['style'] = $style;
        						            $options = Utils::Serialize($options);
                                            
                                            $Db->Query("UPDATE #__rba_roles SET label='".$Db->_e($label)."',title='".$Db->_e($title)."',options='".$Db->_e($options)."' WHERE rid=".intval($id));         	
        									Utils::Redirect("admin.php?cont="._PLUGIN."&op=roles");
        								} else {
        									MemErr::Trigger("USERERROR",implode("<br />",$errors));
        								}
        							} else {
        							    MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
        							}
        					    }
                            } else {
    							MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("ROLE")));
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
        
        function DeleteRoles() { 
    	    global $Db;
    		
    		$items = Io::GetVar("POST","items",false,true);
    		
    		$result = $Db->Query("DELETE FROM #__rba_roles WHERE rid IN (".$Db->_e($items).")") ? 1 : 0 ;
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
}

?>