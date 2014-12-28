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

class blocksModel {
	function Main() {
        global $Db,$config_sys;
        
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		?>

        <script type="text/javascript" charset="utf-8">
            $(document).ready(function() {
                //Uninstall
    			$('input#uninstall').click(function() {
    				var obj = $('.cb:checkbox:checked');
    				if (obj.length>0) {
    					if (confirm('<?php echo _t("SURE_UNINSTALL_THE_X",MB::strtolower(_t("BLOCK"))); ?>')) {
    						var items = new Array();
    						for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
    						$.ajax({
    							type: "POST",
    							dataType: "xml",
    							url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=uninstall",
    							data: "items="+items,
    							success: function(data){
    								location = 'admin.php?cont=<?php echo _PLUGIN; ?>';
    							}
    						});
    					}
    				} else {
    					alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("BLOCK"))); ?>');
    				}
    			});
                //Reset positions
				$('input#reset_nav').click(function() {
                    window.location.href = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=resetpos&zone=nav';
                });
                $('input#reset_extra').click(function() {
                    window.location.href = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=resetpos&zone=extra';
                });
            });
			function showmenu(id) {
				$("#menu_"+id).toggle();
				$("#content_"+id).toggle();
			}
		</script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("BLOCK")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("BLOCK")))."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_BLOCK")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_BLOCK")))."' /></a>\n";
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("BLOCKS"); ?></div>
                        <div class="body">
                        <?php
							//Options
							$sortby = $Db->_e(Io::GetVar("GET","sortby",false,true,"title"));
							$order = $Db->_e(Io::GetVar("GET","order",false,true,"ASC"));

                            echo "<form action='' method='post' id='validate'>\n";
							echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
								//Uninstall
								echo "<input type='button' name='trash' value='"._t("UNINSTALL")."' style='margin:2px 0;' class='sys_form_button' id='uninstall' />\n";
							echo "</div>\n";

							echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
								echo "<thead>\n";
									echo "<tr>\n";
										echo "<th width='1%' style='text-align:right;'><input type='checkbox' id='selectall' /></th>\n";
										echo "<th width='25%'>"._t("TITLE")."</th>\n";
										echo "<th width='20%'>"._t("LABEL")."</th>\n";
                                        echo "<th width='25%'>"._t("FILE")."</th>\n";
										echo "<th width='17%'>"._t("TYPE")."</th>\n";
										echo "<th width='12%'>"._t("STATUS")."</th>\n";
									echo "</tr>\n";
								echo "</thead>\n";
								echo "<tbody>\n";

								if ($result = $Db->GetList("SELECT * FROM #__blocks ORDER BY $sortby $order")) {
                                    $preroles = Ram::Get("roles");
									$preroles['ALL']['name'] = _t("EVERYONE");
									foreach ($result as $row) {
										$id			= Io::Output($row['id'],"int");
										$title		= Io::Output($row['title']);
										$label		= Io::Output($row['label']);
                                        $showtitle  = Io::Output($row['showtitle'],"int");
                                        $type		= MB::ucfirst(MB::strtolower(Io::Output($row['type'])));
                                        $zone       = Io::Output($row['zone']);
                                        $position   = Io::Output($row['position'],"int");
                                        $file       = Io::Output($row['file']);
                                        $content    = Io::Output($row['content']);
                                        $options	= Utils::Unserialize(Io::Output($row['options']));
										$roles		= Utils::Unserialize(Io::Output($row['roles']));
                                        $start      = Time::Output(Io::Output($row['start']));
                                        $end        = Time::Output(Io::Output($row['end']));
										$status		= MB::ucfirst(Io::Output($row['status']));
										if (!sizeof($roles)|| empty($roles)) $roles = array('ALL');

										echo "<tr onmouseover='javascript:showmenu($id);' onmouseout='javascript:showmenu($id);'>\n";
											echo "<td><input type='checkbox' name='selected[]' value='$id' class='cb' /><br />&nbsp;</td>\n";
                                            if ($type=="Content") {
                                                echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=editstatic&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("BLOCK")))."'><strong>$title</strong></a>\n";
                                            } else {
                                                echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=edit&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("BLOCK")))."'><strong>$title</strong></a>\n";
                                            }
												echo "<div id='menu_$id' style='display:none; margin-top:2px;'>\n";
                                                    if ($type=="Content") {
                                                        echo "<a href='admin.php?cont="._PLUGIN."&amp;op=editstatic&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("BLOCK")))."'>"._t("EDIT")."</a>\n";
                                                    } else {
                                                        echo "<a href='admin.php?cont="._PLUGIN."&amp;op=edit&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("BLOCK")))."'>"._t("EDIT")."</a>\n";
                                                    }
													$ronames = array();
													foreach ($roles as $role) if (isset($preroles[$role]['name'])) $ronames[] = $preroles[$role]['name'];
													$roles = _t("WHO_ACCESS_THE_X",MB::strtolower("BLOCK")).": ".implode(", ",$ronames);
													echo " - <a title='$roles'>"._t("ROLES")."</a>\n";
												echo "</div>\n";
											echo "</td>\n";
											echo "<td>\n";
                                                echo (!empty($label)) ? $label : "-" ;
                                            echo "</td>\n";
                                            echo "<td>\n";
                                                echo (!empty($file)) ? $file : "-" ;
                                            echo "</td>\n";
                                            echo "<td>$type";
                                            if (!empty($content)) {
                                                echo "<div id='content_$id' style='display:none; margin-top:2px;'>\n";
                                                    echo "<a href='javascript:void(0);' onclick=\"javascript:openPopup('admin.php?cont="._PLUGIN."&amp;op=preview&amp;id=$id','300','500')\" title='"._t("PREVIEW_THIS_X",MB::strtolower(_t("BLOCK")))."'>"._t("PREVIEW")."</a>\n";
                                                echo "</div>\n";
                                            }
                                            echo "</td>\n";
											echo "<td>$status</td>\n";
                               			echo "</tr>\n";
									}
								} else {
									echo "<tr>\n";
										echo "<td colspan='6' style='text-align:center;'>"._t("LIST_EMPTY")."</td>\n";
                               		echo "</tr>\n";
								}
								?>
                                </tbody>
                            </table>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top; width: 50%; padding-right:5px;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("NAVIGATOR"); ?></div>
                        <div class="body">
                            <?php
                                if ($result = $Db->GetList("SELECT * FROM #__blocks WHERE zone='nav' ORDER BY position ASC")) {
                                    echo "<div style='text-align:right; padding:0 0 2px 0; clear:right;'>\n";
                                        //Reset positions
                                        echo "<input type='button' name='reset_nav' value='"._t("RESET_POSITIONS")."' style='margin:2px 0;' class='sys_form_button' id='reset_nav' />\n";
                                    echo "</div>\n";
                                    echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
                                    echo "<thead>\n";
                                        echo "<tr>\n";
                                        	echo "<th width='65%'>"._t("TITLE")."</th>\n";
        									echo "<th width='15%'>&nbsp;</th>\n";
        									echo "<th width='20%' style='text-align:center;'>"._t("POSITION")."</th>\n";
    									echo "</tr>\n";
    								echo "</thead>\n";
    								echo "<tbody>\n";
                                    foreach ($result as $row) {
                                        $id			= Io::Output($row['id'],"int");
										$title		= Io::Output($row['title']);
										$position   = Io::Output($row['position'],"int");

                                        echo "<tr>\n";
                                            echo "<td>$title</td>\n";
                                            echo "<td style='text-align:right;'>\n";
                                                echo "<a href='admin.php?cont="._PLUGIN."&amp;op=switchpos&amp;id=$id&amp;dir=up'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."icons"._DS."up.png' alt='Up' /></a> ";
                                                echo "<a href='admin.php?cont="._PLUGIN."&amp;op=switchpos&amp;id=$id&amp;dir=down'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."icons"._DS."down.png' alt='Down' /></a>";
                                            echo "</td>\n";
                                            echo "<td style='text-align:center;'>$position</td>\n";
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
                <td style="vertical-align:top; width: 50%; padding-left:5px;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("EXTRA"); ?></div>
                        <div class="body">
                            <?php
                                if ($result = $Db->GetList("SELECT * FROM #__blocks WHERE zone='extra' ORDER BY position ASC")) {
                                    echo "<div style='text-align:right; padding:0 0 2px 0; clear:right;'>\n";
                                        //Reset positions
                                        echo "<input type='button' name='reset_extra' value='"._t("RESET_POSITIONS")."' style='margin:2px 0;' class='sys_form_button' id='reset_extra' />\n";
                                    echo "</div>\n";
                                    echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
                                    echo "<thead>\n";
                                        echo "<tr>\n";
                                        	echo "<th width='65%'>"._t("TITLE")."</th>\n";
        									echo "<th width='15%'>&nbsp;</th>\n";
        									echo "<th width='20%' style='text-align:center;'>"._t("POSITION")."</th>\n";
    									echo "</tr>\n";
    								echo "</thead>\n";
    								echo "<tbody>\n";
                                    foreach ($result as $row) {
                                        $id			= Io::Output($row['id'],"int");
										$title		= Io::Output($row['title']);
										$position   = Io::Output($row['position'],"int");

                                        echo "<tr>\n";
                                            echo "<td>$title</td>\n";
                                            echo "<td style='text-align:right;'>\n";
                                                echo "<a href='admin.php?cont="._PLUGIN."&amp;op=switchpos&amp;id=$id&amp;dir=up'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."icons"._DS."up.png' alt='Up' /></a> ";
                                                echo "<a href='admin.php?cont="._PLUGIN."&amp;op=switchpos&amp;id=$id&amp;dir=down'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."icons"._DS."down.png' alt='Down' /></a>";
                                            echo "</td>\n";
                                            echo "<td style='text-align:center;'>$position</td>\n";
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

    function InstallBlock() {
        global $Db,$config_sys;

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
                $(".datepicker").datepicker({
					dateFormat: 'yy-mm-dd',
					minDate: 0
				});
                $('#zonesel').change(function() {
                    switch($('#zonesel').val()) {
                        case "sticker":
                            $('#labelsel').show();
                            $('#positsel').hide();
                            break;
                        default:
                            $('#labelsel').hide();
                            $('#positsel').show();
                            break;
                    }
                });
			});
        </script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("BLOCK")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("BLOCK")))."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_BLOCK")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_BLOCK")))."' /></a>\n";
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("INSTALL_NEW_X",MB::strtolower(_t("BLOCK"))); ?></div>
                        <div class="body">

                        <?php

                        if (!isset($_POST['install'])) {
                                $form = new Form();
								$form->action = "admin.php?cont="._PLUGIN."&amp;op=install";
								$form->Open();

                                //Title
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("TITLE"),
														"width"		=>"300px",
														"name"		=>"title"));

                                //Zone
                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("ZONE"),
														"name"		=>"zone",
                                                        "id"        =>"zonesel",
														"values"	=>array(_t("NAVIGATOR") => "nav",
																			_t("EXTRA") => "extra",
                                                                            _t("STICKER") => "sticker")));

                                //Label
                                echo "<div id='labelsel' style='display:none;'>";
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("STICKER_LABEL"),
														"name"		=>"label",
														"width"		=>"300px",
														"id"		=>"urlvalidname",
														"info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));
                                echo "</div>";

                                //Position
                                echo "<div id='positsel'>";
                                $form->AddElement(array("element"	=>"text",
														"label"		=>_t("POSITION"),
														"width"		=>"100px",
														"name"		=>"position",
                                                        "info"      =>_t("LEAVE_BLANK_DEFAULT_VAL")));
                                echo "</div>";
                                
                                //Type = file

                                //File
                                $tdir = Utils::GetDirContent("blocks"._DS);
                                $blocks = array();
								foreach ($tdir as $file) {
                                    $file = preg_replace("#block_|\.php#is","",$file);
                                    $blocks[MB::ucfirst($file)] = $file;
                                }

                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("FILE"),
														"name"		=>"file",
														"values"	=>$blocks));

                                //Content
                                
                                ?>
										</div>
									</div>
                                </td>
								<td class="sidebar">
									<div class="widget ui-widget-content ui-corner-all">
										<div class="ui-widget-header"><?php echo _t("OPTIONS"); ?></div>
										<div class="body">
                                <?php

                                //Show title
                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("SHOW_TITLE"),
														"name"		=>"showtitle",
														"values"	=>array(_t("YES") => 1,
																			_t("NO") => 0),
														"selected"	=>1));

                                //Start
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("START"),
														"name"		=>"start",
														"class"		=>"sys_form_text datepicker",
														"width"		=>"150px",
														"suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."calendar.png' alt='Start' />"));

								//End
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("END"),
														"name"		=>"end",
														"class"		=>"sys_form_text datepicker",
														"width"		=>"150px",
														"suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."calendar.png' alt='End' />"));

                                //Status
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("STATUS"),
														"name"		=>"status",
														"values"	=>array(_t("DISPLAY")   => "display",
																			_t("INACTIVE") 	=> "inactive")));

                                ?>
										</div>
									</div>
                                    <div class="widget ui-widget-content ui-corner-all">
										<div class="ui-widget-header"><?php echo _t("LAYOUT"); ?></div>
                                        <div class="body">
                                <?php

                                //Showincontent
                                $result = $Db->GetList("SELECT title,name FROM #__content WHERE type IN ('PLUGIN','STATIC') AND status='active' ORDER BY title");
								$plugins = array();
                                $plugins[_t("EVERYWHERE")] = "ALL";
								foreach ($result as $row) $plugins[Io::Output($row['title'])] = Io::Output($row['name']);

                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("SHOW_IN_CONTENT"),
														"name"		=>"showincontent[]",
                                                        "multiple"	=>true,
                                                        "size"      =>10,
														"values"	=>$plugins,
														"selected"	=>"ALL",
														"info"		=>_t("MULTIPLE_CHOICES_ALLOWED")));

                                ?>
										</div>
									</div>
                                    <div class="widget ui-widget-content ui-corner-all">
                                      	<div class="ui-widget-header"><?php echo _t("AUTHORIZATION_MANAGER"); ?></div>
										<div class="body">
                                <?php

                                //Required roles
								$result = $Db->GetList("SELECT title,label FROM #__rba_roles ORDER BY rid");
								$rba = array();
								$rba[_t("EVERYONE")] = "ALL";
								foreach ($result as $row) $rba[Io::Output($row['title'])] = Io::Output($row['label']);
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("WHO_ACCESS_THE_X",MB::strtolower("PLUGIN")),
														"name"		=>"roles[]",
														"multiple"	=>true,
														"values"	=>$rba,
														"selected"	=>"ALL",
														"info"		=>_t("MULTIPLE_CHOICES_ALLOWED")));
                                ?>
										</div>
									</div>
                                <?php
                                //Install
								$form->AddElement(array("element"	=>"submit",
														"name"		=>"install",
														"inline"	=>true,
														"value"		=>_t("INSTALL")));

                                $form->Close();
                        } else {
                            //Check token
							if (Utils::CheckToken()) {
								//Get POST data
								$title = Io::GetVar('POST','title','fullhtml');
								$label = Io::GetVar('POST','label','[^a-zA-Z0-9\-]');
                                $showtitle = Io::GetVar('POST','showtitle','int');
                                $zone = Io::GetVar('POST','zone','nohtml');
                                $position = Io::GetVar('POST','position','int');
                                $file = Io::GetVar('POST','file','nohtml');
                                $roles = Io::GetVar('POST','roles','nohtml',true,array());
                                $start = Io::GetVar('POST','start',false,true,'2001-01-01 00:00:00');
								$end = Io::GetVar('POST','end',false,true,'2199-01-01 00:00:00');
                                $status = Io::GetVar('POST','status','nohtml');
								
								$errors = array();
								if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
                                if ($zone=="sticker") {
                                    if (empty($label)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("STICKER_LABEL"));
                                    $position = 0;
                                } else {
                                    $label = "";
                                    if ($position==0 || $row = $Db->GetNum("SELECT position FROM #__blocks WHERE zone='".$Db->_e($zone)."' AND position=".intval($position))) {
                                        $row = $Db->GetRow("SELECT position FROM #__blocks WHERE zone='".$Db->_e($zone)."' ORDER BY position DESC LIMIT 1");
                                        $position = Io::Output($row['position'],"int");
                                        $position++;
                                    }
                                }

								if (!sizeof($errors)) {
									$options = array();
                                    $showincontent = Io::GetVar('POST','showincontent','nohtml',true,array());
                                    if (!in_array("ALL",$showincontent)) $options['showincontent'] = $showincontent;
									$options = Utils::Serialize($options);
									
									if (in_array("ALL",$roles)) $roles = array();
									$roles = Utils::Serialize($roles);
									
									$Db->Query("INSERT INTO #__blocks (title,label,showtitle,type,zone,position,file,options,roles,start,end,status)
                                                VALUES ('".$Db->_e($title)."','".$Db->_e($label)."','".intval($showtitle)."','file','".$Db->_e($zone)."',
                                                        '".intval($position)."','".$Db->_e($file)."','".$Db->_e($options)."','".$Db->_e($roles)."',
                                                        '".$Db->_e($start)."','".$Db->_e($end)."','".$Db->_e($status)."')");

									Utils::Redirect("admin.php?cont="._PLUGIN);
								} else {
									Error::Trigger("USERERROR",implode("<br />",$errors));
								}
							} else {
								Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
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

    function EditBlock() {
        global $Db,$config_sys;

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
                $(".datepicker").datepicker({
					dateFormat: 'yy-mm-dd',
					minDate: 0
				});
                $('#zonesel').change(function() {
                    switch($('#zonesel').val()) {
                        case "sticker":
                            $('#labelsel').show();
                            $('#positsel').hide();
                            break;
                        default:
                            $('#labelsel').hide();
                            $('#positsel').show();
                            break;
                    }
                });
			});
        </script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("BLOCK")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("BLOCK")))."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_BLOCK")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_BLOCK")))."' /></a>\n";
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("EDIT_X",MB::strtolower(_t("BLOCK"))); ?></div>
                        <div class="body">

                        <?php

                        $id = Io::GetVar('GET','id','int');
                        if ($row = $Db->GetRow("SELECT * FROM #__blocks WHERE id=".intval($id))) {
                            if (!isset($_POST['save'])) {
                                //Get values from db
                                $title      = Io::Output($row['title']);
                                $label      = Io::Output($row['label']);
                                $showtitle  = Io::Output($row['showtitle'],'int');
                                $type       = Io::Output($row['type']);
                                $zone       = Io::Output($row['zone']);
                                $position   = Io::Output($row['position'],'int');
                                $file       = Io::Output($row['file']);
                                $content    = Io::Output($row['content']);
                                $options    = Utils::Unserialize(Io::Output($row['options']));
                                $roles      = Utils::Unserialize(Io::Output($row['roles']));
                                $start      = Io::Output($row['start']);
                                $end        = Io::Output($row['end']);
                                $status     = Io::Output($row['status']);

                                    $form = new Form();
                                    $form->action = "admin.php?cont="._PLUGIN."&amp;op=edit&amp;id=$id";
                                    $form->Open();

                                    //Title
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("TITLE"),
                                                            "width"		=>"300px",
                                                            "name"		=>"title",
                                                            "value"     =>$title));

                                    //Zone
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("ZONE"),
                                                            "name"		=>"zone",
                                                            "id"        =>"zonesel",
                                                            "values"	=>array(_t("NAVIGATOR") => "nav",
                                                                                _t("EXTRA") => "extra",
                                                                                _t("STICKER") => "sticker"),
                                                            "selected"  =>$zone));

                                    //Label
                                    echo "<div id='labelsel' style='display:none;'>";
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("STICKER_LABEL"),
                                                            "name"		=>"label",
                                                            "value"     =>$label,
                                                            "width"		=>"300px",
                                                            "id"		=>"urlvalidname",
                                                            "info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));
                                    echo "</div>";

                                    //Position
                                    echo "<div id='positsel'>";
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("POSITION"),
                                                            "width"		=>"100px",
                                                            "name"		=>"position",
                                                            "value"     =>$position,
                                                            "info"      =>_t("LEAVE_BLANK_DEFAULT_VAL")));
                                    echo "</div>";

                                    //Type = file

                                    //File
                                    $tdir = Utils::GetDirContent("blocks"._DS);
                                    $blocks = array();
                                    foreach ($tdir as $tfile) {
                                        $tfile = preg_replace("#block_|\.php#is","",$tfile);
                                        $blocks[MB::ucfirst($tfile)] = $tfile;
                                    }

                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("FILE"),
                                                            "name"		=>"file",
                                                            "selected"  =>$file,
                                                            "values"	=>$blocks));

                                    //Content

                                    ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="sidebar">
                                        <div class="widget ui-widget-content ui-corner-all">
                                            <div class="ui-widget-header"><?php echo _t("OPTIONS"); ?></div>
                                            <div class="body">
                                    <?php

                                    //Show title
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("SHOW_TITLE"),
                                                            "name"		=>"showtitle",
                                                            "values"	=>array(_t("YES") => 1,
                                                                                _t("NO") => 0),
                                                            "selected"	=>$showtitle));

                                    //Start
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("START"),
                                                            "name"		=>"start",
                                                            "value"     =>$start,
                                                            "class"		=>"sys_form_text datepicker",
                                                            "width"		=>"150px",
                                                            "suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."calendar.png' alt='Start' />"));

                                    //End
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("END"),
                                                            "name"		=>"end",
                                                            "value"     =>$end,
                                                            "class"		=>"sys_form_text datepicker",
                                                            "width"		=>"150px",
                                                            "suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."calendar.png' alt='End' />"));

                                    //Status
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("STATUS"),
                                                            "name"		=>"status",
                                                            "selected"  =>$status,
                                                            "values"	=>array(_t("DISPLAY")   => "display",
                                                                                _t("INACTIVE") 	=> "inactive")));

                                    ?>
                                            </div>
                                        </div>
                                        <div class="widget ui-widget-content ui-corner-all">
                                            <div class="ui-widget-header"><?php echo _t("LAYOUT"); ?></div>
                                            <div class="body">
                                    <?php

                                    //Showincontent
                                    $result = $Db->GetList("SELECT title,name FROM #__content WHERE type IN ('PLUGIN','STATIC') AND status='active' ORDER BY title");
                                    $plugins = array();
                                    $plugins[_t("EVERYWHERE")] = "ALL";
                                    if (!isset($options['showincontent']) || empty($options['showincontent'])) $options['showincontent'] = array('ALL');
                                    foreach ($result as $row) $plugins[Io::Output($row['title'])] = Io::Output($row['name']);

                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("SHOW_IN_CONTENT"),
                                                            "name"		=>"showincontent[]",
                                                            "multiple"	=>true,
                                                            "size"      =>10,
                                                            "values"	=>$plugins,
                                                            "selected"	=>$options['showincontent'],
                                                            "info"		=>_t("MULTIPLE_CHOICES_ALLOWED")));

                                    ?>
                                            </div>
                                        </div>
                                        <div class="widget ui-widget-content ui-corner-all">
                                            <div class="ui-widget-header"><?php echo _t("AUTHORIZATION_MANAGER"); ?></div>
                                            <div class="body">
                                    <?php

                                    //Required roles
                                    $result = $Db->GetList("SELECT title,label FROM #__rba_roles ORDER BY rid");
                                    $rba = array();
                                    $rba[_t("EVERYONE")] = "ALL";
                                    if (!sizeof($roles) || empty($roles)) $roles = array('ALL');
                                    foreach ($result as $row) $rba[Io::Output($row['title'])] = Io::Output($row['label']);
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("WHO_ACCESS_THE_X",MB::strtolower("PLUGIN")),
                                                            "name"		=>"roles[]",
                                                            "multiple"	=>true,
                                                            "values"	=>$rba,
                                                            "selected"	=>$roles,
                                                            "info"		=>_t("MULTIPLE_CHOICES_ALLOWED")));
                                    ?>
                                            </div>
                                        </div>
                                    <?php
                                    //Install
                                    $form->AddElement(array("element"	=>"submit",
                                                            "name"		=>"save",
                                                            "inline"	=>true,
                                                            "value"		=>_t("SAVE")));

                                    $form->Close();
                            } else {
                                //Check token
                                if (Utils::CheckToken()) {
                                    //Get POST data
                                    $title = Io::GetVar('POST','title','fullhtml');
                                    $label = Io::GetVar('POST','label','[^a-zA-Z0-9\-]');
                                    $showtitle = Io::GetVar('POST','showtitle','int');
                                    $zone = Io::GetVar('POST','zone','nohtml');
                                    $position = Io::GetVar('POST','position','int');
                                    $file = Io::GetVar('POST','file','nohtml');
                                    $roles = Io::GetVar('POST','roles','nohtml',true,array());
                                    $start = Io::GetVar('POST','start',false,true,'2001-01-01 00:00:00');
                                    $end = Io::GetVar('POST','end',false,true,'2199-01-01 00:00:00');
                                    $status = Io::GetVar('POST','status','nohtml');

                                    $errors = array();
                                    if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
                                    if ($zone=="sticker") {
                                        if (empty($label)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("STICKER_LABEL"));
                                        $position = 0;
                                    } else {
                                        $label = "";
                                        if ($position==0 || $row = $Db->GetNum("SELECT position FROM #__blocks WHERE zone='".$Db->_e($zone)."' AND position=".intval($position)." AND id!=".intval($id))) {
                                            $row = $Db->GetRow("SELECT position FROM #__blocks WHERE zone='".$Db->_e($zone)."' AND id!=".intval($id)." ORDER BY position DESC LIMIT 1");
                                            $position = Io::Output($row['position'],"int");
                                            $position++;
                                        }
                                    }

                                    if (!sizeof($errors)) {
                                        $options = array();
                                        $showincontent = Io::GetVar('POST','showincontent','nohtml',true,array());
                                        if (!in_array("ALL",$showincontent)) $options['showincontent'] = $showincontent;
                                        $options = Utils::Serialize($options);

                                        if (in_array("ALL",$roles)) $roles = array();
                                        $roles = Utils::Serialize($roles);

                                        $Db->Query("UPDATE #__blocks SET title='".$Db->_e($title)."',label='".$Db->_e($label)."',showtitle='".intval($showtitle)."',
                                                                         zone='".$Db->_e($zone)."',position='".intval($position)."',file='".$Db->_e($file)."',options='".$Db->_e($options)."',
                                                                         roles='".$Db->_e($roles)."',start='".$Db->_e($start)."',end='".$Db->_e($end)."',status='".$Db->_e($status)."'
                                                                         WHERE id=".intval($id));

                                        Utils::Redirect("admin.php?cont="._PLUGIN);
                                    } else {
                                        Error::Trigger("USERERROR",implode("<br />",$errors));
                                    }
                                } else {
                                    Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
                                }
                            ?>
                                </div>
                            </div>
                            <?php
                            }
                        } else {
                            Error::Trigger("USERERROR",_t("X_NOT_FOUND",_t("BLOCK")));
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

    function UninstallBlock() {
        global $Db;

		$items = Io::GetVar("POST","items");

		$result = $Db->Query("DELETE FROM #__blocks WHERE id IN (".$Db->_e($items).")") ? 1 : 0 ;
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

    function ResetBlockPositions() {
        global $Db;

        $zone = Io::GetVar("GET","zone");
        if ($result = $Db->GetList("SELECT id FROM #__blocks WHERE zone='".$Db->_e($zone)."' ORDER BY position ASC")) {
            $pos = 0;
            foreach ($result as $row) {
                $id = Io::Output($row['id'],"int");
                $Db->Query("UPDATE #__blocks SET position='".intval($pos)."' WHERE zone='".$Db->_e($zone)."' AND id=".$id);
                $pos++;
            }
        }
        
        Utils::Redirect("admin.php?cont="._PLUGIN);
    }

    function SwitchBlockPosition() {
		global $Db;

		$id = Io::GetVar("GET","id","int");
        $dir = Io::GetVar("GET","dir");

        if ($row = $Db->GetRow("SELECT zone,position FROM #__blocks WHERE id=".intval($id))) {
            $zone       = Io::Output($row['zone']);
            $position   = Io::Output($row['position'],"int");

            switch ($dir) {
                case "up":
                    if ($Db->GetNum("SELECT id FROM #__blocks WHERE zone='".$Db->_e($zone)."' AND position<".intval($position))) {
                        $Db->Query("UPDATE #__blocks SET position=position+1 WHERE zone='".$Db->_e($zone)."' AND position=".($position-1));
                        $Db->Query("UPDATE #__blocks SET position=position-1 WHERE id=".intval($id));
                    }
                    break;
                case "down":
                    if ($Db->GetNum("SELECT id FROM #__blocks WHERE zone='".$Db->_e($zone)."' AND position>".intval($position))) {
                        $Db->Query("UPDATE #__blocks SET position=position-1 WHERE zone='".$Db->_e($zone)."' AND position=".($position+1));
                        $Db->Query("UPDATE #__blocks SET position=position+1 WHERE id=".intval($id));
                    }
                    break;
            }
        }
        Utils::Redirect("admin.php?cont="._PLUGIN);
	}

    function CreateStaticBlock() {
        global $Db,$config_sys;

		//Initialize and show site header
		Layout::Header(array("editor"=>true));
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
                $(".datepicker").datepicker({
					dateFormat: 'yy-mm-dd',
					minDate: 0
				});
                $('#zonesel').change(function() {
                    switch($('#zonesel').val()) {
                        case "sticker":
                            $('#labelsel').show();
                            $('#positsel').hide();
                            break;
                        default:
                            $('#labelsel').hide();
                            $('#positsel').show();
                            break;
                    }
                });
			});
        </script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("BLOCK")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("BLOCK")))."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_BLOCK")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_BLOCK")))."' /></a>\n";
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("INSTALL_NEW_X",MB::strtolower(_t("BLOCK"))); ?></div>
                        <div class="body">

                        <?php

                        if (!isset($_POST['install'])) {
                                $form = new Form();
								$form->action = "admin.php?cont="._PLUGIN."&amp;op=create";
								$form->Open();

                                //Title
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("TITLE"),
														"width"		=>"300px",
														"name"		=>"title"));

                                //Zone
                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("ZONE"),
														"name"		=>"zone",
                                                        "id"        =>"zonesel",
														"values"	=>array(_t("NAVIGATOR") => "nav",
																			_t("EXTRA") => "extra",
                                                                            _t("STICKER") => "sticker")));

                                //Label
                                echo "<div id='labelsel' style='display:none;'>";
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("STICKER_LABEL"),
														"name"		=>"label",
														"width"		=>"300px",
														"id"		=>"urlvalidname",
														"info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));
                                echo "</div>";

                                //Position
                                echo "<div id='positsel'>";
                                $form->AddElement(array("element"	=>"text",
														"label"		=>_t("POSITION"),
														"width"		=>"100px",
														"name"		=>"position",
                                                        "info"      =>_t("LEAVE_BLANK_DEFAULT_VAL")));
                                echo "</div>";

                                //Type = content

                                //File

                                //Content
                                $form->AddElement(array("element"	=>"textarea",
														"label"		=>_t("CONTENT"),
														"name"		=>"content",
														"height"	=>"500px",
														"class"		=>"advanced"));

                                ?>
										</div>
									</div>
                                </td>
								<td class="sidebar">
									<div class="widget ui-widget-content ui-corner-all">
										<div class="ui-widget-header"><?php echo _t("OPTIONS"); ?></div>
										<div class="body">
                                <?php

                                //Show title
                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("SHOW_TITLE"),
														"name"		=>"showtitle",
														"values"	=>array(_t("YES") => 1,
																			_t("NO") => 0),
														"selected"	=>1));

                                //Start
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("START"),
														"name"		=>"start",
														"class"		=>"sys_form_text datepicker",
														"width"		=>"150px",
														"suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."calendar.png' alt='Start' />"));

								//End
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("END"),
														"name"		=>"end",
														"class"		=>"sys_form_text datepicker",
														"width"		=>"150px",
														"suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."calendar.png' alt='End' />"));

                                //Status
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("STATUS"),
														"name"		=>"status",
														"values"	=>array(_t("DISPLAY")   => "display",
																			_t("INACTIVE") 	=> "inactive")));

                                ?>
										</div>
									</div>
                                    <div class="widget ui-widget-content ui-corner-all">
										<div class="ui-widget-header"><?php echo _t("LAYOUT"); ?></div>
                                        <div class="body">
                                <?php

                                //Showincontent
                                $result = $Db->GetList("SELECT title,name FROM #__content WHERE type IN ('PLUGIN','STATIC') AND status='active' ORDER BY title");
								$plugins = array();
                                $plugins[_t("EVERYWHERE")] = "ALL";
								foreach ($result as $row) $plugins[Io::Output($row['title'])] = Io::Output($row['name']);

                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("SHOW_IN_CONTENT"),
														"name"		=>"showincontent[]",
                                                        "multiple"	=>true,
                                                        "size"      =>10,
														"values"	=>$plugins,
														"selected"	=>"ALL",
														"info"		=>_t("MULTIPLE_CHOICES_ALLOWED")));

                                ?>
										</div>
									</div>
                                    <div class="widget ui-widget-content ui-corner-all">
                                      	<div class="ui-widget-header"><?php echo _t("AUTHORIZATION_MANAGER"); ?></div>
										<div class="body">
                                <?php

                                //Required roles
								$result = $Db->GetList("SELECT title,label FROM #__rba_roles ORDER BY rid");
								$rba = array();
								$rba[_t("EVERYONE")] = "ALL";
								foreach ($result as $row) $rba[Io::Output($row['title'])] = Io::Output($row['label']);
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("WHO_ACCESS_THE_X",MB::strtolower("PLUGIN")),
														"name"		=>"roles[]",
														"multiple"	=>true,
														"values"	=>$rba,
														"selected"	=>"ALL",
														"info"		=>_t("MULTIPLE_CHOICES_ALLOWED")));
                                ?>
										</div>
									</div>
                                <?php
                                //Install
								$form->AddElement(array("element"	=>"submit",
														"name"		=>"install",
														"inline"	=>true,
														"value"		=>_t("INSTALL")));

                                $form->Close();
                        } else {
                            //Check token
							if (Utils::CheckToken()) {
								//Get POST data
								$title = Io::GetVar('POST','title','fullhtml');
								$label = Io::GetVar('POST','label','[^a-zA-Z0-9\-]');
                                $showtitle = Io::GetVar('POST','showtitle','int');
                                $zone = Io::GetVar('POST','zone','nohtml');
                                $position = Io::GetVar('POST','position','int');
                                $content = Io::GetVar('POST','content','fullhtml',false);
                                $roles = Io::GetVar('POST','roles','nohtml',true,array());
                                $start = Io::GetVar('POST','start',false,true,'2001-01-01 00:00:00');
								$end = Io::GetVar('POST','end',false,true,'2199-01-01 00:00:00');
                                $status = Io::GetVar('POST','status','nohtml');

								$errors = array();
								if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
                                if ($zone=="sticker") {
                                    if (empty($label)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("STICKER_LABEL"));
                                    $position = 0;
                                } else {
                                    $label = "";
                                    if ($position==0 || $row = $Db->GetNum("SELECT position FROM #__blocks WHERE zone='".$Db->_e($zone)."' AND position=".intval($position))) {
                                        $row = $Db->GetRow("SELECT position FROM #__blocks WHERE zone='".$Db->_e($zone)."' ORDER BY position DESC LIMIT 1");
                                        $position = Io::Output($row['position'],"int");
                                        $position++;
                                    }
                                }

								if (!sizeof($errors)) {
									$options = array();
                                    $showincontent = Io::GetVar('POST','showincontent','nohtml',true,array());
                                    if (!in_array("ALL",$showincontent)) $options['showincontent'] = $showincontent;
									$options = Utils::Serialize($options);

									if (in_array("ALL",$roles)) $roles = array();
									$roles = Utils::Serialize($roles);

									$Db->Query("INSERT INTO #__blocks (title,label,showtitle,type,zone,position,content,options,roles,start,end,status)
                                                VALUES ('".$Db->_e($title)."','".$Db->_e($label)."','".intval($showtitle)."','content','".$Db->_e($zone)."',
                                                        '".intval($position)."','".$Db->_e($content)."','".$Db->_e($options)."','".$Db->_e($roles)."',
                                                        '".$Db->_e($start)."','".$Db->_e($end)."','".$Db->_e($status)."')");

									Utils::Redirect("admin.php?cont="._PLUGIN);
								} else {
									Error::Trigger("USERERROR",implode("<br />",$errors));
								}
							} else {
								Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
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
    
    function EditStaticBlock() {
        global $Db,$config_sys;

		//Initialize and show site header
		Layout::Header(array("editor"=>true));
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
                $(".datepicker").datepicker({
					dateFormat: 'yy-mm-dd',
					minDate: 0
				});
                $('#zonesel').change(function() {
                    switch($('#zonesel').val()) {
                        case "sticker":
                            $('#labelsel').show();
                            $('#positsel').hide();
                            break;
                        default:
                            $('#labelsel').hide();
                            $('#positsel').show();
                            break;
                    }
                });
			});
        </script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("BLOCK")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("BLOCK")))."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=create' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_BLOCK")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_BLOCK")))."' /></a>\n";
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("EDIT_X",MB::strtolower(_t("BLOCK"))); ?></div>
                        <div class="body">

                        <?php

                        $id = Io::GetVar('GET','id','int');
                        if ($row = $Db->GetRow("SELECT * FROM #__blocks WHERE id=".intval($id))) {
                            if (!isset($_POST['save'])) {
                                //Get values from db
                                $title      = Io::Output($row['title']);
                                $label      = Io::Output($row['label']);
                                $showtitle  = Io::Output($row['showtitle'],'int');
                                $type       = Io::Output($row['type']);
                                $zone       = Io::Output($row['zone']);
                                $position   = Io::Output($row['position'],'int');
                                $file       = Io::Output($row['file']);
                                $content    = Io::Output($row['content']);
                                $options    = Utils::Unserialize(Io::Output($row['options']));
                                $roles      = Utils::Unserialize(Io::Output($row['roles']));
                                $start      = Io::Output($row['start']);
                                $end        = Io::Output($row['end']);
                                $status     = Io::Output($row['status']);

                                    $form = new Form();
                                    $form->action = "admin.php?cont="._PLUGIN."&amp;op=editstatic&amp;id=$id";
                                    $form->Open();

                                    //Title
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("TITLE"),
                                                            "width"		=>"300px",
                                                            "name"		=>"title",
                                                            "value"     =>$title));

                                    //Zone
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("ZONE"),
                                                            "name"		=>"zone",
                                                            "id"        =>"zonesel",
                                                            "values"	=>array(_t("NAVIGATOR") => "nav",
                                                                                _t("EXTRA") => "extra",
                                                                                _t("STICKER") => "sticker"),
                                                            "selected"  =>$zone));

                                    //Label
                                    echo "<div id='labelsel' style='display:none;'>";
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("STICKER_LABEL"),
                                                            "name"		=>"label",
                                                            "value"     =>$label,
                                                            "width"		=>"300px",
                                                            "id"		=>"urlvalidname",
                                                            "info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));
                                    echo "</div>";

                                    //Position
                                    echo "<div id='positsel'>";
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("POSITION"),
                                                            "width"		=>"100px",
                                                            "name"		=>"position",
                                                            "value"     =>$position,
                                                            "info"      =>_t("LEAVE_BLANK_DEFAULT_VAL")));
                                    echo "</div>";

                                    //Type = content

                                    //File

                                    //Content
                                    $form->AddElement(array("element"	=>"textarea",
    														"label"		=>_t("CONTENT"),
    														"name"		=>"content",
                                                            "value"     =>$content,
    														"height"	=>"500px",
    														"class"		=>"advanced"));

                                    ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="sidebar">
                                        <div class="widget ui-widget-content ui-corner-all">
                                            <div class="ui-widget-header"><?php echo _t("OPTIONS"); ?></div>
                                            <div class="body">
                                    <?php

                                    //Show title
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("SHOW_TITLE"),
                                                            "name"		=>"showtitle",
                                                            "values"	=>array(_t("YES") => 1,
                                                                                _t("NO") => 0),
                                                            "selected"	=>$showtitle));

                                    //Start
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("START"),
                                                            "name"		=>"start",
                                                            "value"     =>$start,
                                                            "class"		=>"sys_form_text datepicker",
                                                            "width"		=>"150px",
                                                            "suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."calendar.png' alt='Start' />"));

                                    //End
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("END"),
                                                            "name"		=>"end",
                                                            "value"     =>$end,
                                                            "class"		=>"sys_form_text datepicker",
                                                            "width"		=>"150px",
                                                            "suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."calendar.png' alt='End' />"));

                                    //Status
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("STATUS"),
                                                            "name"		=>"status",
                                                            "selected"  =>$status,
                                                            "values"	=>array(_t("DISPLAY")   => "display",
                                                                                _t("INACTIVE") 	=> "inactive")));

                                    ?>
                                            </div>
                                        </div>
                                        <div class="widget ui-widget-content ui-corner-all">
                                            <div class="ui-widget-header"><?php echo _t("LAYOUT"); ?></div>
                                            <div class="body">
                                    <?php

                                    //Showincontent
                                    $result = $Db->GetList("SELECT title,name FROM #__content WHERE type IN ('PLUGIN','STATIC') AND status='active' ORDER BY title");
                                    $plugins = array();
                                    $plugins[_t("EVERYWHERE")] = "ALL";
                                    if (!isset($options['showincontent']) || empty($options['showincontent'])) $options['showincontent'] = array('ALL');
                                    foreach ($result as $row) $plugins[Io::Output($row['title'])] = Io::Output($row['name']);

                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("SHOW_IN_CONTENT"),
                                                            "name"		=>"showincontent[]",
                                                            "multiple"	=>true,
                                                            "size"      =>10,
                                                            "values"	=>$plugins,
                                                            "selected"	=>$options['showincontent'],
                                                            "info"		=>_t("MULTIPLE_CHOICES_ALLOWED")));

                                    ?>
                                            </div>
                                        </div>
                                        <div class="widget ui-widget-content ui-corner-all">
                                            <div class="ui-widget-header"><?php echo _t("AUTHORIZATION_MANAGER"); ?></div>
                                            <div class="body">
                                    <?php

                                    //Required roles
                                    $result = $Db->GetList("SELECT title,label FROM #__rba_roles ORDER BY rid");
                                    $rba = array();
                                    $rba[_t("EVERYONE")] = "ALL";
                                    if (!sizeof($roles) || empty($roles)) $roles = array('ALL');
                                    foreach ($result as $row) $rba[Io::Output($row['title'])] = Io::Output($row['label']);
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("WHO_ACCESS_THE_X",MB::strtolower("PLUGIN")),
                                                            "name"		=>"roles[]",
                                                            "multiple"	=>true,
                                                            "values"	=>$rba,
                                                            "selected"	=>$roles,
                                                            "info"		=>_t("MULTIPLE_CHOICES_ALLOWED")));
                                    ?>
                                            </div>
                                        </div>
                                    <?php
                                    //Install
                                    $form->AddElement(array("element"	=>"submit",
                                                            "name"		=>"save",
                                                            "inline"	=>true,
                                                            "value"		=>_t("SAVE")));

                                    $form->Close();
                            } else {
                                //Check token
                                if (Utils::CheckToken()) {
                                    //Get POST data
                                    $title = Io::GetVar('POST','title','fullhtml');
                                    $label = Io::GetVar('POST','label','[^a-zA-Z0-9\-]');
                                    $showtitle = Io::GetVar('POST','showtitle','int');
                                    $zone = Io::GetVar('POST','zone','nohtml');
                                    $position = Io::GetVar('POST','position','int');
                                    $content = Io::GetVar('POST','content','fullhtml',false);
                                    $roles = Io::GetVar('POST','roles','nohtml',true,array());
                                    $start = Io::GetVar('POST','start',false,true,'2001-01-01 00:00:00');
                                    $end = Io::GetVar('POST','end',false,true,'2199-01-01 00:00:00');
                                    $status = Io::GetVar('POST','status','nohtml');

                                    $errors = array();
                                    if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
                                    if ($zone=="sticker") {
                                        if (empty($label)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("STICKER_LABEL"));
                                        $position = 0;
                                    } else {
                                        $label = "";
                                        if ($position==0 || $row = $Db->GetNum("SELECT position FROM #__blocks WHERE zone='".$Db->_e($zone)."' AND position=".intval($position)." AND id!=".intval($id))) {
                                            $row = $Db->GetRow("SELECT position FROM #__blocks WHERE zone='".$Db->_e($zone)."' AND id!=".intval($id)." ORDER BY position DESC LIMIT 1");
                                            $position = Io::Output($row['position'],"int");
                                            $position++;
                                        }
                                    }

                                    if (!sizeof($errors)) {
                                        $options = array();
                                        $showincontent = Io::GetVar('POST','showincontent','nohtml',true,array());
                                        if (!in_array("ALL",$showincontent)) $options['showincontent'] = $showincontent;
                                        $options = Utils::Serialize($options);

                                        if (in_array("ALL",$roles)) $roles = array();
                                        $roles = Utils::Serialize($roles);

                                        $Db->Query("UPDATE #__blocks SET title='".$Db->_e($title)."',label='".$Db->_e($label)."',showtitle='".intval($showtitle)."',
                                                                         zone='".$Db->_e($zone)."',position='".intval($position)."',content='".$Db->_e($content)."',options='".$Db->_e($options)."',
                                                                         roles='".$Db->_e($roles)."',start='".$Db->_e($start)."',end='".$Db->_e($end)."',status='".$Db->_e($status)."'
                                                                         WHERE id=".intval($id));

                                        Utils::Redirect("admin.php?cont="._PLUGIN);
                                    } else {
                                        Error::Trigger("USERERROR",implode("<br />",$errors));
                                    }
                                } else {
                                    Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
                                }
                            ?>
                                </div>
                            </div>
                            <?php
                            }
                        } else {
                            Error::Trigger("USERERROR",_t("X_NOT_FOUND",_t("BLOCK")));
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

    function StaticBlockPreview() {
        global $Db;

        $id = Io::GetVar('GET','id','int');
        if ($row = $Db->GetRow("SELECT content FROM #__blocks WHERE id=".intval($id))) {
           echo Io::Output($row['content']);
        } else {
            echo _t("X_NOT_FOUND",_t("BLOCK"));
        }
    }
}

?>