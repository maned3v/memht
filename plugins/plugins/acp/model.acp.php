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

class pluginsModel {
	function Main() {
        global $Db,$config_sys;
        
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		$type = Io::GetVar("GET","type",false,true,"PLUGIN");
		
		?>

        <script type="text/javascript" charset="utf-8">
    		<?php
    			if ($type != "static") {
    		?>
    			//Uninstall
    			function uninstallplugin(id) {
    				if (confirm('<?php echo _t("SURE_UNINSTALL_THE_X",MB::strtolower(_t("PLUGIN"))); ?>')) {
    					location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=uninstall&id='+id;
    				}
    			}
    		<?php		
    			} else {
    		?>
    		$(document).ready(function() {
    			//Delete permanently
    			$('input#delete').click(function() {
                    var obj = $('.cb:checkbox:checked');
    				if (obj.length>0) {
    					if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("PAGES"))); ?>')) {
    						var items = new Array();
    						for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
    						$.ajax({
    							type: "POST",
    							dataType: "xml",
    							url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=delete",
    							data: "items="+items,
    							success: function(data){
    								location = 'admin.php?cont=<?php echo _PLUGIN; ?>&type=static';
    							}
    						});
    					}
    				} else {
    					alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("PAGE"))); ?>');
    				}
    			});
    		});	    			
    		<?php
    			}
    		?>
			function showmenu(id) {
				$("#menu_"+id).toggle();
				$("#status_"+id).toggle();
			}
		</script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpage' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=addredirect' title='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."addredirect.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=static' title='"._t("STATIC_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."page.png' alt='"._t("STATIC_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=redirects' title='"._t("REDIRECTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."redirect.png' alt='"._t("REDIRECTIONS")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=internal' title='"._t("INTERNAL_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."internal.png' alt='"._t("INTERNAL_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menu' title='"._t("MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menu.png' alt='"._t("MENU_EDITOR")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menuacp' title='"._t("ADMIN_MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menuacp.png' alt='"._t("ADMIN_MENU_EDITOR")."' /></a>\n";
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <?php
                            //Where
                            $where = "WHERE ";
							$where .= "type='".$Db->_e(MB::strtoupper($type))."'";
                            $where .= " AND status!='ACP'";
                        ?>
                        <div class="ui-widget-header">
                        <?php
                            switch ($type) {
                                case "static":
                                    echo _t("STATIC_PAGES");
                                    break;
                                case "internal":
                                    echo _t("INTERNAL_PAGES");
                                    break;
                                default:
                                    echo _t("INSTALLED_X",MB::strtolower(_t("PLUGINS")));
                                    break;
                            }
                        ?>
                        </div>
                        <div class="body">
                        <?php
							//Options
							$sortby = $Db->_e(Io::GetVar("GET","sortby",false,true,"title"));
							$order = $Db->_e(Io::GetVar("GET","order",false,true,"ASC"));

                            echo "<form action='' method='post' id='validate'>\n";
							echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
								if ($type=="static") {
									//Delete
									echo "<input type='button' name='delete' value='"._t("DELETE_PERMANENTLY")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
								}
							echo "</div>\n";

							echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
								echo "<thead>\n";
									echo "<tr>\n";
										echo "<th width='1%' style='text-align:right;'><input type='checkbox' id='selectall' /></th>\n";
										echo "<th width='30%'>"._t("TITLE")."</th>\n";
										echo "<th width='30%'>"._t("NAME")."</th>\n";
										echo "<th width='19%'>"._t("TYPE")."</th>\n";
										echo "<th width='10%'>"._t("STATUS")."</th>\n";
										echo "<th width='10%'>&nbsp;</th>\n";
									echo "</tr>\n";
								echo "</thead>\n";
								echo "<tbody>\n";

								if ($result = $Db->GetList("SELECT * FROM #__content {$where} ORDER BY $sortby $order")) {
                                    $preroles = Ram::Get("roles");
									$preroles['ALL']['name'] = _t("EVERYONE");
									foreach ($result as $row) {
										$id			= Io::Output($row['id'],"int");
										$title		= Io::Output($row['title']);
										$name		= Io::Output($row['name']);
                                        $type		= MB::ucfirst(MB::strtolower(Io::Output($row['type'])));
										$options	= Utils::Unserialize(Io::Output($row['options']));
										$roles		= Utils::Unserialize(Io::Output($row['roles']));
										$status		= MB::ucfirst(Io::Output($row['status']));
										
										if (!sizeof($roles)|| empty($roles)) $roles = array('ALL');
										echo "<tr onmouseover='javascript:showmenu($id);' onmouseout='javascript:showmenu($id);'>\n";
											echo "<td><input type='checkbox' name='selected[]' value='$id' class='cb' /><br />&nbsp;</td>\n";
                                            if ($type=="Static") {
                                                echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=editpage&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("PLUGIN")))."'><strong>$title</strong></a>\n";
                                            } else {
                                                echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=edit&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("PLUGIN")))."'><strong>$title</strong></a>\n";
                                            }
												echo "<div id='menu_$id' style='display:none; margin-top:2px;'>\n";
                                                    if ($status=="Active") {
														echo "<a href='index.php?"._NODE."=$name' title='"._t("VIEW_THIS_X",MB::strtolower(_t("PLUGIN")))."' rel='external'>"._t("VIEW")."</a> - \n";
													}
                                                    if ($type=="Static") {
                                                        echo "<a href='admin.php?cont="._PLUGIN."&amp;op=editpage&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("PLUGIN")))."'>"._t("EDIT")."</a>\n";
                                                    } else {
                                                        echo "<a href='admin.php?cont="._PLUGIN."&amp;op=edit&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("PLUGIN")))."'>"._t("EDIT")."</a>\n";
                                                    }
													$ronames = array();
													foreach ($roles as $role) if (isset($preroles[$role]['name'])) $ronames[] = $preroles[$role]['name'];
                                                    if ($type=="Static") {
                                                        $roles = _t("WHO_ACCESS_THE_X",MB::strtolower("PAGE")).": ".implode(", ",$ronames);
                                                    } else {
                                                        $roles = _t("WHO_ACCESS_THE_X",MB::strtolower("PLUGIN")).": ".implode(", ",$ronames);
                                                    }
													echo " - <a title='$roles'>"._t("ROLES")."</a>\n";
												echo "</div>\n";
											echo "</td>\n";
											echo "<td>$name</td>\n";
                                            echo "<td>$type</td>\n";
											echo "<td>$status\n";
												echo "<div id='status_$id' style='display:none; margin-top:2px;'>\n";
                                                echo "</div>\n";
											echo "</td>\n";
											echo "<td style='text-align:right;vertical-align:middle;'>\n";
												if ($type != "Static") {
													echo "<input type='button' name='uninstall' value='"._t("UNINSTALL")."' style='margin:2px 0;' class='sys_form_button' id='uninstall' onclick=\"javascript:uninstallplugin('$id');\" />\n";
												} else {
													echo "&nbsp;";
												}
											echo "</td>\n";											
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

        <?php
		
		//Assign captured content to the template engine and clean buffer
		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
		//Draw site template
		Template::Draw();
		//Initialize and show site footer
		Layout::Footer();
	}

    function InstallPlugin() {
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
				$('#autoname').click(function(){
					$.ajax({
						type: "POST",
						dataType: "html",
						url: "admin.php?cont=internal&op=cleanchar&lowercase=1",
						data: "string="+$('#title').val(),
						success: function(data,textStatus,XMLHttpRequest){
							$('#urlvalidname').val(data);
						},
						error: function(XMLHttpRequest,textStatus,errorThrown) {
							$('#urlvalidname').val('Error');
						}
					});
				});
			});
        </script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpage' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=addredirect' title='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."addredirect.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=static' title='"._t("STATIC_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."page.png' alt='"._t("STATIC_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=redirects' title='"._t("REDIRECTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."redirect.png' alt='"._t("REDIRECTIONS")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=internal' title='"._t("INTERNAL_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."internal.png' alt='"._t("INTERNAL_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menu' title='"._t("MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menu.png' alt='"._t("MENU_EDITOR")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menuacp' title='"._t("ADMIN_MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menuacp.png' alt='"._t("ADMIN_MENU_EDITOR")."' /></a>\n";
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN"))); ?></div>
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
														"name"		=>"title",
														"id"		=>"title"));

                                //Name
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("LINK_NAME"),
														"name"		=>"name",
														"width"		=>"300px",
														"id"		=>"urlvalidname",
														"suffix"	=>"<input type='button' id='autoname' value='"._t("AUTO")."' class='sys_form_button' />",
														"info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));
                                
                                //TODO: Generate link with havascript

                                //Controller
                                $tdir = Utils::GetDirContent("plugins"._DS);
                                $controllers = array();
								foreach ($tdir as $dir) {
                                    $controllers[MB::ucfirst($dir)] = $dir;
                                }

                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("PLUGIN"),
														"name"		=>"controller",
														"values"	=>$controllers));

                                //type
                                $form->AddElement(array("element"	=>"select",
				                                		"label"		=>_t("TYPE"),
				                                		"name"		=>"type",
				                                		"values"	=>array(_t("PLUGIN") => 'PLUGIN',
				                                							_t("INTERNAL") => 'INTERNAL')));

                                //content

                                //Content before
                                $form->AddElement(array("element"	=>"textarea",
														"label"		=>_t("CONT_BEFORE"),
														"name"		=>"cont_before",
														"height"	=>"300px",
														"class"		=>"simple"));

                                //Content after
                                $form->AddElement(array("element"	=>"textarea",
														"label"		=>_t("CONT_AFTER"),
														"name"		=>"cont_after",
														"height"	=>"300px",
														"class"		=>"simple"));

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

                                //Sitemap
                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("SHOW_IN_SITEMAP"),
														"name"		=>"sitemap",
														"values"	=>array(_t("YES") => 1,
																			_t("NO") => 0),
														"selected"	=>1));

								//Rss feeds
                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("RSS_FEEDS"),
														"name"		=>"rss",
														"values"	=>array(_t("YES") => 1,
																			_t("NO") => 0),
														"selected"	=>0));

								//Search
                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("SEARCH"),
														"name"		=>"searchable",
														"values"	=>array(_t("ENABLED") => 1,
																			_t("DISABLED") => 0),
														"selected"	=>0));

                                //Administration
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("ADMINCP"),
														"name"		=>"acp",
														"values"	=>array(_t("YES") => "yes",
																			_t("NO") => "no")));

                                //Status
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("STATUS"),
														"name"		=>"status",
														"values"	=>array(_t("ACTIVE")    => "active",
																			_t("INACTIVE") 	=> "off",
                                                                            _t("INTERNAL")  => "internal")));

                                //Add in site menu
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("ADD_IN_SITE_MENU"),
														"name"		=>"addinmenu",
														"values"	=>array(_t("NO")        => "no",
																			_t("NAVIGATOR")	=> "nav",
																			_t("HEADER")	=> "head")));

                                ?>
										</div>
									</div>
                                    <div class="widget ui-widget-content ui-corner-all">
										<div class="ui-widget-header"><?php echo _t("LAYOUT"); ?></div>
                                        <div class="body">
                                <?php

                                //Nav
                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("NAVIGATOR")." ("._t("LEFT_COLUMN").")",
														"name"		=>"nav",
														"values"	=>array(_t("SHOW") => 1,
                                                                            _t("HIDE") => 0)));

                                //Extra
                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("EXTRA")." ("._t("RIGHT_COLUMN").")",
														"name"		=>"extra",
														"values"	=>array(_t("SHOW") => 1,
                                                                            _t("HIDE") => 0)));

                                ?>
										</div>
									</div>
                                    <div class="widget ui-widget-content ui-corner-all">
										<div class="ui-widget-header"><?php echo _t("META"); ?></div>
                                        <div class="body">
                                <?php

                                //Description
								$form->AddElement(array("element"	=>"textarea",
														"label"		=>_t("DESCRIPTION"),
														"name"		=>"meta_description",
														"width"		=>"90%",
														"height"	=>"50px",
														"class"		=>"sys_form_textarea"));

								//Keywords
								$form->AddElement(array("element"	=>"textarea",
														"label"		=>_t("KEYWORDS"),
														"name"		=>"meta_keywords",
														"width"		=>"90%",
														"height"	=>"50px",
														"class"		=>"sys_form_textarea",
														"info"		=>_t("VALUES_SEPARATED_BY_COMMAS")));

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
								$name = Io::GetVar('POST','name','[^a-zA-Z0-9\-]');
                                $controller = Io::GetVar('POST','controller','nohtml');
                                $type = Io::GetVar('POST','type','nohtml','PLUGIN');
                                $showtitle = Io::GetVar('POST','showtitle','int');
                                $meta_description = Io::GetVar('POST','meta_description','nohtml');
								$meta_keywords = Io::GetVar('POST','meta_keywords','nohtml');
                                $cont_before = Io::GetVar('POST','cont_before','fullhtml',false);
                                $cont_after = Io::GetVar('POST','cont_after','fullhtml',false);
								$roles = Io::GetVar('POST','roles','nohtml',true,array());
                                $sitemap = Io::GetVar('POST','sitemap','int');
								$rss = Io::GetVar('POST','rss','int');
								$searchable = Io::GetVar('POST','searchable','int');
                                $acp = Io::GetVar('POST','acp','nohtml');
                                $status = Io::GetVar('POST','status','nohtml');
								
								$errors = array();
								if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
								if (empty($name)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LINK_NAME"));
								
								$setupresult = false;
								if (!sizeof($errors)) {
									if (file_exists("plugins"._DS.$controller._DS."setup.php")) {
										include_once("plugins"._DS.$controller._DS."setup.php");
										
										if (method_exists("Setup","Install")) {
											$setupresult = Setup::Install();
										}
									}
								}
								
								if (!sizeof($errors)) {
									$options = array();
                                    $nav = Io::GetVar('POST','nav','int');
                                    $extra = Io::GetVar('POST','extra','int');
                                    $options['layout']['nav'] = (!empty($nav)) ? 1 : 0 ;
                                    $options['layout']['extra'] = (!empty($extra)) ? 1 : 0 ;
									$options = Utils::Serialize($options);
									
									if (in_array("ALL",$roles)) $roles = array();
									$roles = Utils::Serialize($roles);
									
									$Db->Query("INSERT INTO #__content (title,name,controller,type,showtitle,meta_keywords,meta_description,cont_before,cont_after,options,roles,sitemap,rss,searchable,acp,status)
                                                VALUES ('".$Db->_e($title)."','".$Db->_e($name)."','".$Db->_e($controller)."','".$Db->_e($type)."','".intval($showtitle)."','".$Db->_e($meta_keywords)."',
                                                        '".$Db->_e($meta_description)."','".$Db->_e($cont_before)."','".$Db->_e($cont_after)."','".$Db->_e($options)."','".$Db->_e($roles)."',
                                                        '".intval($sitemap)."','".intval($rss)."','".intval($searchable)."','".$Db->_e($acp)."','".$Db->_e($status)."')");

                                    $addinmenu = Io::GetVar('POST','addinmenu','nohtml');
                                    if ($addinmenu!="no") {
                                        $position = 0;
                                        if ($row = $Db->GetRow("SELECT position FROM #__menu WHERE zone='".$Db->_e($addinmenu)."' ORDER BY position DESC LIMIT 1")) {
                                            $position = Io::Output($row['position'],"int");
                                            $position++;
                                        }
                                        $Db->Query("INSERT INTO #__menu (title,url,zone,position,roles)
                                                    VALUES ('".$Db->_e($title)."','index.php?{NODE}=".$Db->_e($name)."',
                                                            '".$Db->_e($addinmenu)."','".intval($position)."','".$Db->_e($roles)."')");
                                    }

                                    if (!empty($setupresult)) {
                                    	MemErr::Trigger("INFO",$setupresult,"<a href='admin.php?cont="._PLUGIN."' title='"._t("CONTINUE")."'>"._t("CONTINUE")."</a>");
                                    } else {
                                    	Utils::Redirect("admin.php?cont="._PLUGIN);
                                    }
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

    function EditPlugin() {
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
				$('#autoname').click(function(){
					$.ajax({
						type: "POST",
						dataType: "html",
						url: "admin.php?cont=internal&op=cleanchar&lowercase=1",
						data: "string="+$('#title').val(),
						success: function(data,textStatus,XMLHttpRequest){
							$('#urlvalidname').val(data);
						},
						error: function(XMLHttpRequest,textStatus,errorThrown) {
							$('#urlvalidname').val('Error');
						}
					});
				});
			});
        </script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpage' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=addredirect' title='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."addredirect.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=static' title='"._t("STATIC_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."page.png' alt='"._t("STATIC_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=redirects' title='"._t("REDIRECTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."redirect.png' alt='"._t("REDIRECTIONS")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=internal' title='"._t("INTERNAL_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."internal.png' alt='"._t("INTERNAL_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menu' title='"._t("MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menu.png' alt='"._t("MENU_EDITOR")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menuacp' title='"._t("ADMIN_MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menuacp.png' alt='"._t("ADMIN_MENU_EDITOR")."' /></a>\n";
        ?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("EDIT_X",MB::strtolower(_t("PLUGIN"))); ?></div>
                        <div class="body">

                        <?php

                        $id = Io::GetVar('GET','id','int');
                        if ($row = $Db->GetRow("SELECT * FROM #__content WHERE id=".intval($id))) {
                            if (!isset($_POST['save'])) {
                                //Get values from db
                                $title              = Io::Output($row['title']);
                                $name               = Io::Output($row['name']);
                                $controller         = Io::Output($row['controller']);
                                $type               = Io::Output($row['type']);
                                $showtitle          = Io::Output($row['showtitle'],'int');
                                $meta_keywords      = Io::Output($row['meta_keywords']);
                                $meta_description   = Io::Output($row['meta_description']);
                                $content            = Io::Output($row['content']);
                                $cont_before        = Io::Output($row['cont_before']);
                                $cont_after         = Io::Output($row['cont_after']);
                                $options            = Utils::Unserialize(Io::Output($row['options']));
                                $roles              = Utils::Unserialize(Io::Output($row['roles']));
                                $sitemap            = Io::Output($row['sitemap'],'int');
								$rss				= Io::Output($row['rss'],'int');
								$searchable			= Io::Output($row['searchable'],'int');
                                $acp                = Io::Output($row['acp']);
                                $status             = Io::Output($row['status']);

                                    $form = new Form();
                                    $form->action = "admin.php?cont="._PLUGIN."&amp;op=edit&amp;id=$id";
                                    $form->Open();

                                    //Title
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("TITLE"),
                                                            "width"		=>"300px",
                                                            "name"		=>"title",
															"id"		=>"title",
                                                            "value"     =>$title));

                                    //Name
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("LINK_NAME"),
                                                            "name"		=>"name",
                                                            "value"     =>$name,
                                                            "width"		=>"300px",
                                                            "id"		=>"urlvalidname",
															"suffix"	=>"<input type='button' id='autoname' value='"._t("AUTO")."' class='sys_form_button' />",
                                                            "info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));

                                    //TODO: Generate link with havascript

                                    //Controller
                                    $tdir = Utils::GetDirContent("plugins"._DS);
                                    $controllers = array();
                                    foreach ($tdir as $dir) {
                                        $controllers[MB::ucfirst($dir)] = $dir;
                                    }

                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("PLUGIN"),
                                                            "name"		=>"controller",
                                                            "values"	=>$controllers,
                                                            "selected"  =>$controller));

                                    //type
                                    $form->AddElement(array("element"	=>"select",
				                                    		"label"		=>_t("TYPE"),
				                                    		"name"		=>"type",
				                                    		"values"	=>array(_t("PLUGIN") => 'PLUGIN',
				                                    							_t("INTERNAL") => 'INTERNAL'),
                                    						"selected"	=>$type));

                                    //content

                                    //Content before
                                    $form->AddElement(array("element"	=>"textarea",
                                                            "label"		=>_t("CONT_BEFORE"),
                                                            "name"		=>"cont_before",
                                                            "height"	=>"300px",
                                                            "class"		=>"simple",
                                                            "value"     =>$cont_before));

                                    //Content after
                                    $form->AddElement(array("element"	=>"textarea",
                                                            "label"		=>_t("CONT_AFTER"),
                                                            "name"		=>"cont_after",
                                                            "height"	=>"300px",
                                                            "class"		=>"simple",
                                                            "value"     =>$cont_after));

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

                                    //Sitemap
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("SHOW_IN_SITEMAP"),
                                                            "name"		=>"sitemap",
                                                            "values"	=>array(_t("YES") => 1,
                                                                                _t("NO") => 0),
                                                            "selected"	=>$sitemap));

									//RSS Feeds
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("RSS_FEEDS"),
                                                            "name"		=>"rss",
                                                            "values"	=>array(_t("YES") => 1,
                                                                                _t("NO") => 0),
                                                            "selected"	=>$rss));

									//Search
									$form->AddElement(array("element"	=>"select",
															"label"		=>_t("SEARCH"),
															"name"		=>"searchable",
															"values"	=>array(_t("ENABLED") => 1,
																				_t("DISABLED") => 0),
															"selected"	=>$searchable));

                                    //Administration
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("ADMINCP"),
                                                            "name"		=>"acp",
                                                            "values"	=>array(_t("YES") => "yes",
                                                                                _t("NO") => "no"),
                                                            "selected"  =>$acp));

                                    //Status
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("STATUS"),
                                                            "name"		=>"status",
                                                            "values"	=>array(_t("ACTIVE")    => "active",
                                                                                _t("INACTIVE") 	=> "off",
                                                                                _t("INTERNAL")  => "internal"),
                                                            "selected"  =>$status));

                                    ?>
                                            </div>
                                        </div>
                                        <div class="widget ui-widget-content ui-corner-all">
                                            <div class="ui-widget-header"><?php echo _t("LAYOUT"); ?></div>
                                            <div class="body">
                                    <?php

                                    //Nav
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("NAVIGATOR")." ("._t("LEFT_COLUMN").")",
                                                            "name"		=>"nav",
                                                            "values"	=>array(_t("SHOW") => 1,
                                                                                _t("HIDE") => 0),
                                                            "selected"  =>@$options['layout']['nav']));

                                    //Extra
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("EXTRA")." ("._t("RIGHT_COLUMN").")",
                                                            "name"		=>"extra",
                                                            "values"	=>array(_t("SHOW") => 1,
                                                                                _t("HIDE") => 0),
                                                            "selected"  =>@$options['layout']['extra']));

                                    ?>
                                            </div>
                                        </div>
                                        <div class="widget ui-widget-content ui-corner-all">
                                            <div class="ui-widget-header"><?php echo _t("META"); ?></div>
                                            <div class="body">
                                    <?php

                                    //Description
                                    $form->AddElement(array("element"	=>"textarea",
                                                            "label"		=>_t("DESCRIPTION"),
                                                            "name"		=>"meta_description",
                                                            "width"		=>"90%",
                                                            "height"	=>"50px",
                                                            "class"		=>"sys_form_textarea",
                                                            "value"     =>$meta_description));

                                    //Keywords
                                    $form->AddElement(array("element"	=>"textarea",
                                                            "label"		=>_t("KEYWORDS"),
                                                            "name"		=>"meta_keywords",
                                                            "width"		=>"90%",
                                                            "height"	=>"50px",
                                                            "class"		=>"sys_form_textarea",
                                                            "info"		=>_t("VALUES_SEPARATED_BY_COMMAS"),
                                                            "value"     =>$meta_keywords));

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
                                    $name = Io::GetVar('POST','name','[^a-zA-Z0-9\-]');
                                    $controller = Io::GetVar('POST','controller','nohtml');
                                    $type = Io::GetVar('POST','type','nohtml','PLUGIN');
                                    $showtitle = Io::GetVar('POST','showtitle','int');
                                    $meta_description = Io::GetVar('POST','meta_description','nohtml');
                                    $meta_keywords = Io::GetVar('POST','meta_keywords','nohtml');
                                    $cont_before = Io::GetVar('POST','cont_before','fullhtml',false);
                                    $cont_after = Io::GetVar('POST','cont_after','fullhtml',false);
                                    $roles = Io::GetVar('POST','roles','nohtml',true,array());
                                    $sitemap = Io::GetVar('POST','sitemap','int');
									$rss = Io::GetVar('POST','rss','int');
									$searchable = Io::GetVar('POST','searchable','int');
                                    $acp = Io::GetVar('POST','acp','nohtml');
                                    $status = Io::GetVar('POST','status','nohtml');

                                    $errors = array();
                                    if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
                                    if (empty($name)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LINK_NAME"));

                                    if (!sizeof($errors)) {
                                        $options = array();
                                        $nav = Io::GetVar('POST','nav','int');
                                        $extra = Io::GetVar('POST','extra','int');
                                        $options['layout']['nav'] = (!empty($nav)) ? 1 : 0 ;
                                        $options['layout']['extra'] = (!empty($extra)) ? 1 : 0 ;
                                        $options = Utils::Serialize($options);

                                        if (in_array("ALL",$roles)) $roles = array();
                                        $roles = Utils::Serialize($roles);

                                        $Db->Query("UPDATE #__content SET title='".$Db->_e($title)."',name='".$Db->_e($name)."',controller='".$Db->_e($controller)."',type='".$Db->_e($type)."',
                                                    showtitle='".intval($showtitle)."',meta_keywords='".$Db->_e($meta_keywords)."',meta_description='".$Db->_e($meta_description)."',
                                                    cont_before='".$Db->_e($cont_before)."',cont_after='".$Db->_e($cont_after)."',options='".$Db->_e($options)."',
                                                    roles='".$Db->_e($roles)."',sitemap='".intval($sitemap)."',rss='".intval($rss)."',searchable='".intval($searchable)."',acp='".$Db->_e($acp)."',status='".$Db->_e($status)."' WHERE id=".intval($id));

                                        Utils::Redirect("admin.php?cont="._PLUGIN);
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
                            MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("PLUGIN")));
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

    function DeletePage() {
    	global $Db;
    
    	$items = Io::GetVar("POST","items",false,true);
    
    	$result = $Db->Query("DELETE FROM #__content WHERE id IN (".$Db->_e($items).")") ? 1 : 0 ;
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
    
    function UninstallPlugin() {
        global $Db,$config_sys;

		//Initialize and show site header
		Layout::Header(array("editor"=>true));
		//Start buffering content
		Utils::StartBuffering();
		
		?>
		
        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpage' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=addredirect' title='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."addredirect.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=static' title='"._t("STATIC_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."page.png' alt='"._t("STATIC_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=redirects' title='"._t("REDIRECTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."redirect.png' alt='"._t("REDIRECTIONS")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=internal' title='"._t("INTERNAL_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."internal.png' alt='"._t("INTERNAL_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menu' title='"._t("MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menu.png' alt='"._t("MENU_EDITOR")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menuacp' title='"._t("ADMIN_MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menuacp.png' alt='"._t("ADMIN_MENU_EDITOR")."' /></a>\n";
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("UNINSTALL"); ?></div>
                        <div class="body">
                        	<?php

                        	$id = Io::GetVar('GET','id','int');
                        	if ($row = $Db->GetRow("SELECT * FROM #__content WHERE id=".intval($id))) {
                        		$controller	= Io::Output($row['controller']);
                        		
	                        	$setupresult = false;
                        		if (file_exists("plugins"._DS.$controller._DS."setup.php")) {
                        			include_once("plugins"._DS.$controller._DS."setup.php");
	                        	
	                        		if (method_exists("Setup","Uninstall")) {
	                        			$setupresult = Setup::Uninstall();
	                        		}
	                        	}
	                        	
	                        	$Db->Query("DELETE FROM #__content WHERE id='".intval($id)."'");
	                        	
	                        	if (!empty($setupresult)) {
	                        		MemErr::Trigger("INFO",$setupresult,"<a href='admin.php?cont="._PLUGIN."' title='"._t("CONTINUE")."'>"._t("CONTINUE")."</a>");
	                        	} else {
	                        		Utils::Redirect("admin.php?cont="._PLUGIN);
	                        	}
                        	} else {
                        		MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("PLUGIN")));
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

    function CreateStaticPage() {
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
				$('#autoname').click(function(){
					$.ajax({
						type: "POST",
						dataType: "html",
						url: "admin.php?cont=internal&op=cleanchar&lowercase=1",
						data: "string="+$('#title').val(),
						success: function(data,textStatus,XMLHttpRequest){
							$('#urlvalidname').val(data);
						},
						error: function(XMLHttpRequest,textStatus,errorThrown) {
							$('#urlvalidname').val('Error');
						}
					});
				});
			});
        </script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpage' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=addredirect' title='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."addredirect.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=static' title='"._t("STATIC_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."page.png' alt='"._t("STATIC_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=redirects' title='"._t("REDIRECTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."redirect.png' alt='"._t("REDIRECTIONS")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=internal' title='"._t("INTERNAL_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."internal.png' alt='"._t("INTERNAL_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menu' title='"._t("MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menu.png' alt='"._t("MENU_EDITOR")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menuacp' title='"._t("ADMIN_MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menuacp.png' alt='"._t("ADMIN_MENU_EDITOR")."' /></a>\n";
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE"))); ?></div>
                        <div class="body">

                        <?php

                       if (!isset($_POST['install'])) {
                                $form = new Form();
								$form->action = "admin.php?cont="._PLUGIN."&amp;op=createpage";
								$form->Open();

                                //Title
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("TITLE"),
														"width"		=>"300px",
														"name"		=>"title",
														"id"		=>"title"));

                                //Name
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("LINK_NAME"),
														"name"		=>"name",
														"width"		=>"300px",
														"id"		=>"urlvalidname",
														"suffix"	=>"<input type='button' id='autoname' value='"._t("AUTO")."' class='sys_form_button' />",
														"info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));

                                //TODO: Generate link with havascript

                                //Controller

                                //type = STATIC

                                //Content before
                                $form->AddElement(array("element"	=>"textarea",
														"label"		=>_t("CONT_BEFORE"),
														"name"		=>"cont_before",
														"height"	=>"200px",
														"class"		=>"simple"));

                                //Content
                                $form->AddElement(array("element"	=>"textarea",
														"label"		=>_t("CONTENT"),
														"name"		=>"content",
														"height"	=>"500px",
														"class"		=>"advanced"));

                                //Content after
                                $form->AddElement(array("element"	=>"textarea",
														"label"		=>_t("CONT_AFTER"),
														"name"		=>"cont_after",
														"height"	=>"200px",
														"class"		=>"simple"));

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

                                //Sitemap
                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("SHOW_IN_SITEMAP"),
														"name"		=>"sitemap",
														"values"	=>array(_t("YES") => 1,
																			_t("NO") => 0),
														"selected"	=>1));

								//Search
                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("SEARCH"),
														"name"		=>"searchable",
														"values"	=>array(_t("ENABLED") => 1,
																			_t("DISABLED") => 0),
														"selected"	=>0));

                                //Status
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("STATUS"),
														"name"		=>"status",
														"values"	=>array(_t("ACTIVE")    => "active",
																			_t("INACTIVE") 	=> "off")));

                                //Add in site menu
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("ADD_IN_SITE_MENU"),
														"name"		=>"addinmenu",
														"values"	=>array(_t("NO")        => "no",
																			_t("NAVIGATOR")	=> "nav",
																			_t("HEADER")	=> "head")));

                                ?>
										</div>
									</div>
                                    <div class="widget ui-widget-content ui-corner-all">
										<div class="ui-widget-header"><?php echo _t("LAYOUT"); ?></div>
                                        <div class="body">
                                <?php

                                //Nav
                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("NAVIGATOR")." ("._t("LEFT_COLUMN").")",
														"name"		=>"nav",
														"values"	=>array(_t("SHOW") => 1,
                                                                            _t("HIDE") => 0)));

                                //Extra
                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("EXTRA")." ("._t("RIGHT_COLUMN").")",
														"name"		=>"extra",
														"values"	=>array(_t("SHOW") => 1,
                                                                            _t("HIDE") => 0)));

                                ?>
										</div>
									</div>
                                    <div class="widget ui-widget-content ui-corner-all">
										<div class="ui-widget-header"><?php echo _t("META"); ?></div>
                                        <div class="body">
                                <?php

                                //Description
								$form->AddElement(array("element"	=>"textarea",
														"label"		=>_t("DESCRIPTION"),
														"name"		=>"meta_description",
														"width"		=>"90%",
														"height"	=>"50px",
														"class"		=>"sys_form_textarea"));

								//Keywords
								$form->AddElement(array("element"	=>"textarea",
														"label"		=>_t("KEYWORDS"),
														"name"		=>"meta_keywords",
														"width"		=>"90%",
														"height"	=>"50px",
														"class"		=>"sys_form_textarea",
														"info"		=>_t("VALUES_SEPARATED_BY_COMMAS")));

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
														"label"		=>_t("WHO_ACCESS_THE_X",MB::strtolower("PAGE")),
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
														"value"		=>_t("CREATE")));

                                $form->Close();
                        } else {
                            //Check token
							if (Utils::CheckToken()) {
								//Get POST data
								$title = Io::GetVar('POST','title','fullhtml');
								$name = Io::GetVar('POST','name','[^a-zA-Z0-9\-]');
                                $type = "PLUGIN";
                                $showtitle = Io::GetVar('POST','showtitle','int');
                                $meta_description = Io::GetVar('POST','meta_description','nohtml');
								$meta_keywords = Io::GetVar('POST','meta_keywords','nohtml');
                                $content = Io::GetVar('POST','content','fullhtml',false);
                                $cont_before = Io::GetVar('POST','cont_before','fullhtml',false);
                                $cont_after = Io::GetVar('POST','cont_after','fullhtml',false);
								$roles = Io::GetVar('POST','roles','nohtml',true,array());
                                $sitemap = Io::GetVar('POST','sitemap','int');
								$searchable = Io::GetVar('POST','searchable','int');
                                $status = Io::GetVar('POST','status','nohtml');

								$errors = array();
								if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
								if (empty($name)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LINK_NAME"));

								if (!sizeof($errors)) {
									$options = array();
                                    $nav = Io::GetVar('POST','nav','int');
                                    $extra = Io::GetVar('POST','extra','int');
                                    $options['layout']['nav'] = (!empty($nav)) ? 1 : 0 ;
                                    $options['layout']['extra'] = (!empty($extra)) ? 1 : 0 ;
									$options = Utils::Serialize($options);

									if (in_array("ALL",$roles)) $roles = array();
									$roles = Utils::Serialize($roles);

									$Db->Query("INSERT INTO #__content (title,name,controller,type,showtitle,meta_keywords,meta_description,content,cont_before,cont_after,options,roles,sitemap,searchable,status)
                                                VALUES ('".$Db->_e($title)."','".$Db->_e($name)."','','STATIC','".intval($showtitle)."','".$Db->_e($meta_keywords)."',
                                                        '".$Db->_e($meta_description)."','".$Db->_e($content)."','".$Db->_e($cont_before)."','".$Db->_e($cont_after)."','".$Db->_e($options)."','".$Db->_e($roles)."',
                                                        '".intval($sitemap)."','".intval($searchable)."','".$Db->_e($status)."')");

                                    $addinmenu = Io::GetVar('POST','addinmenu','nohtml');
                                    if ($addinmenu!="no") {
                                        $position = 0;
                                        if ($row = $Db->GetRow("SELECT position FROM #__menu WHERE zone='".$Db->_e($addinmenu)."' ORDER BY position DESC LIMIT 1")) {
                                            $position = Io::Output($row['position'],"int");
                                            $position++;
                                        }
                                        $Db->Query("INSERT INTO #__menu (title,url,zone,position,roles)
                                                    VALUES ('".$Db->_e($title)."','index.php?{NODE}=".$Db->_e($name)."',
                                                            '".$Db->_e($addinmenu)."','".intval($position)."','".$Db->_e($roles)."')");
                                    }

									Utils::Redirect("admin.php?cont="._PLUGIN."&type=static");
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

    function EditStaticPage() {
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
				$('#autoname').click(function(){
					$.ajax({
						type: "POST",
						dataType: "html",
						url: "admin.php?cont=internal&op=cleanchar&lowercase=1",
						data: "string="+$('#title').val(),
						success: function(data,textStatus,XMLHttpRequest){
							$('#urlvalidname').val(data);
						},
						error: function(XMLHttpRequest,textStatus,errorThrown) {
							$('#urlvalidname').val('Error');
						}
					});
				});
			});
        </script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpage' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=addredirect' title='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."addredirect.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=static' title='"._t("STATIC_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."page.png' alt='"._t("STATIC_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=redirects' title='"._t("REDIRECTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."redirect.png' alt='"._t("REDIRECTIONS")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=internal' title='"._t("INTERNAL_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."internal.png' alt='"._t("INTERNAL_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menu' title='"._t("MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menu.png' alt='"._t("MENU_EDITOR")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menuacp' title='"._t("ADMIN_MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menuacp.png' alt='"._t("ADMIN_MENU_EDITOR")."' /></a>\n";
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("EDIT_X",MB::strtolower(_t("PLUGIN"))); ?></div>
                        <div class="body">

                        <?php

                        $id = Io::GetVar('GET','id','int');
                        if ($row = $Db->GetRow("SELECT * FROM #__content WHERE id=".intval($id))) {
                            if (!isset($_POST['save'])) {
                                //Get values from db
                                $title              = Io::Output($row['title']);
                                $name               = Io::Output($row['name']);
                                $controller         = Io::Output($row['controller']);
                                $type               = Io::Output($row['type']);
                                $showtitle          = Io::Output($row['showtitle']);
                                $meta_keywords      = Io::Output($row['meta_keywords']);
                                $meta_description   = Io::Output($row['meta_description']);
                                $content            = Io::Output($row['content']);
                                $cont_before        = Io::Output($row['cont_before']);
                                $cont_after         = Io::Output($row['cont_after']);
                                $options            = Utils::Unserialize(Io::Output($row['options']));
                                $roles              = Utils::Unserialize(Io::Output($row['roles']));
                                $sitemap            = Io::Output($row['sitemap']);
								$searchable			= Io::Output($row['searchable']);
                                $acp                = Io::Output($row['acp']);
                                $status             = Io::Output($row['status']);

                                    $form = new Form();
                                    $form->action = "admin.php?cont="._PLUGIN."&amp;op=editpage&amp;id=$id";
                                    $form->Open();

                                    //Title
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("TITLE"),
                                                            "width"		=>"300px",
                                                            "name"		=>"title",
															"id"		=>"title",
                                                            "value"     =>$title));

                                    //Name
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("LINK_NAME"),
                                                            "name"		=>"name",
                                                            "value"     =>$name,
                                                            "width"		=>"300px",
                                                            "id"		=>"urlvalidname",
															"suffix"	=>"<input type='button' id='autoname' value='"._t("AUTO")."' class='sys_form_button' />",
                                                            "info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));

                                    //TODO: Generate link with havascript

                                    //Controller
                                    
                                    //type = STATIC

                                    //Content before
                                    $form->AddElement(array("element"	=>"textarea",
                                                            "label"		=>_t("CONT_BEFORE"),
                                                            "name"		=>"cont_before",
                                                            "height"	=>"300px",
                                                            "class"		=>"simple",
                                                            "value"     =>$cont_before));

                                    //Content
                                    $form->AddElement(array("element"	=>"textarea",
                                        					"label"		=>_t("CONTENT"),
                                            				"name"		=>"content",
                                                			"height"	=>"500px",
                                                    		"class"		=>"advanced",
                                                            "value"     =>$content));

                                    //Content after
                                    $form->AddElement(array("element"	=>"textarea",
                                                            "label"		=>_t("CONT_AFTER"),
                                                            "name"		=>"cont_after",
                                                            "height"	=>"300px",
                                                            "class"		=>"simple",
                                                            "value"     =>$cont_after));

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

                                    //Sitemap
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("SHOW_IN_SITEMAP"),
                                                            "name"		=>"sitemap",
                                                            "values"	=>array(_t("YES") => 1,
                                                                                _t("NO") => 0),
                                                            "selected"	=>$sitemap));

									//Search
									$form->AddElement(array("element"	=>"select",
															"label"		=>_t("SEARCH"),
															"name"		=>"searchable",
															"values"	=>array(_t("ENABLED") => 1,
																				_t("DISABLED") => 0),
															"selected"	=>$searchable));

                                    //Status
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("STATUS"),
                                                            "name"		=>"status",
                                                            "values"	=>array(_t("ACTIVE")    => "active",
                                                                                _t("INACTIVE") 	=> "off"),
                                                            "selected"  =>$status));

                                    ?>
                                            </div>
                                        </div>
                                        <div class="widget ui-widget-content ui-corner-all">
                                            <div class="ui-widget-header"><?php echo _t("LAYOUT"); ?></div>
                                            <div class="body">
                                    <?php

                                    //Nav
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("NAVIGATOR")." ("._t("LEFT_COLUMN").")",
                                                            "name"		=>"nav",
                                                            "values"	=>array(_t("SHOW") => 1,
                                                                                _t("HIDE") => 0),
                                                            "selected"  =>@$options['layout']['nav']));

                                    //Extra
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("EXTRA")." ("._t("RIGHT_COLUMN").")",
                                                            "name"		=>"extra",
                                                            "values"	=>array(_t("SHOW") => 1,
                                                                                _t("HIDE") => 0),
                                                            "selected"  =>@$options['layout']['extra']));

                                    ?>
                                            </div>
                                        </div>
                                        <div class="widget ui-widget-content ui-corner-all">
                                            <div class="ui-widget-header"><?php echo _t("META"); ?></div>
                                            <div class="body">
                                    <?php

                                    //Description
                                    $form->AddElement(array("element"	=>"textarea",
                                                            "label"		=>_t("DESCRIPTION"),
                                                            "name"		=>"meta_description",
                                                            "width"		=>"90%",
                                                            "height"	=>"50px",
                                                            "class"		=>"sys_form_textarea",
                                                            "value"     =>$meta_description));

                                    //Keywords
                                    $form->AddElement(array("element"	=>"textarea",
                                                            "label"		=>_t("KEYWORDS"),
                                                            "name"		=>"meta_keywords",
                                                            "width"		=>"90%",
                                                            "height"	=>"50px",
                                                            "class"		=>"sys_form_textarea",
                                                            "info"		=>_t("VALUES_SEPARATED_BY_COMMAS"),
                                                            "value"     =>$meta_keywords));

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
                                    if (!sizeof($roles)|| empty($roles)) $roles = array('ALL');
                                    foreach ($result as $row) $rba[Io::Output($row['title'])] = Io::Output($row['label']);
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("WHO_ACCESS_THE_X",MB::strtolower("PAGE")),
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
                                    $name = Io::GetVar('POST','name','[^a-zA-Z0-9\-]');
                                    $controller = Io::GetVar('POST','controller','nohtml');
                                    $type = "PLUGIN";
                                    $showtitle = Io::GetVar('POST','showtitle','int');
                                    $meta_description = Io::GetVar('POST','meta_description','nohtml');
                                    $meta_keywords = Io::GetVar('POST','meta_keywords','nohtml');
                                    $content = Io::GetVar('POST','content','fullhtml',false);
                                    $cont_before = Io::GetVar('POST','cont_before','fullhtml',false);
                                    $cont_after = Io::GetVar('POST','cont_after','fullhtml',false);
                                    $roles = Io::GetVar('POST','roles','nohtml',true,array());
                                    $sitemap = Io::GetVar('POST','sitemap','int');
									$searchable = Io::GetVar('POST','searchable','int');
                                    $status = Io::GetVar('POST','status','nohtml');

                                    $errors = array();
                                    if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
                                    if (empty($name)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LINK_NAME"));

                                    if (!sizeof($errors)) {
                                        $options = array();
                                        $nav = Io::GetVar('POST','nav','int');
                                        $extra = Io::GetVar('POST','extra','int');
                                        $options['layout']['nav'] = (!empty($nav)) ? 1 : 0 ;
                                        $options['layout']['extra'] = (!empty($extra)) ? 1 : 0 ;
                                        $options = Utils::Serialize($options);

                                        if (in_array("ALL",$roles)) $roles = array();
                                        $roles = Utils::Serialize($roles);

                                        $Db->Query("UPDATE #__content SET title='".$Db->_e($title)."',name='".$Db->_e($name)."',showtitle='".intval($showtitle)."',
                                                    meta_keywords='".$Db->_e($meta_keywords)."',meta_description='".$Db->_e($meta_description)."',
                                                    content='".$Db->_e($content)."',cont_before='".$Db->_e($cont_before)."',cont_after='".$Db->_e($cont_after)."',
                                                    options='".$Db->_e($options)."',roles='".$Db->_e($roles)."',sitemap='".intval($sitemap)."',searchable='".intval($searchable)."',
                                                    status='".$Db->_e($status)."' WHERE id=".intval($id));

                                        Utils::Redirect("admin.php?cont="._PLUGIN."&type=static");
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
                            MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("PLUGIN")));
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

    function MenuEditor() {
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
                    window.location.href = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=addmenu';
                });

                //Reset positions
				$('input#reset').click(function() {
                    window.location.href = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=resetmenu';
                });

				//Delete permanently
				$('input#delete').click(function() {
					var obj = $('.cb:checkbox:checked');
					if (obj.length>0) {
						if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("LINKS"))); ?>')) {
							var items = new Array();
							for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
							$.ajax({
								type: "POST",
								dataType: "xml",
								url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=deletemenu",
								data: "items="+items,
								success: function(data){
									location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=menu';
								}
							});
						}
					} else {
						alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("LINK"))); ?>');
					}
				});
			});
            function showmenu(id) {
				$("#menu_"+id).toggle();
				$("#status_"+id).toggle();
			}
		</script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpage' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=addredirect' title='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."addredirect.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=static' title='"._t("STATIC_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."page.png' alt='"._t("STATIC_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=redirects' title='"._t("REDIRECTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."redirect.png' alt='"._t("REDIRECTIONS")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=internal' title='"._t("INTERNAL_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."internal.png' alt='"._t("INTERNAL_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menu' title='"._t("MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menu.png' alt='"._t("MENU_EDITOR")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menuacp' title='"._t("ADMIN_MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menuacp.png' alt='"._t("ADMIN_MENU_EDITOR")."' /></a>\n";
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("MENU_EDITOR"); ?></div>
                        <div class="body">

        <?php
		echo "<div style='float:left; margin:6px 0 2px 0;'>\n";
            //Create
            echo "<input type='button' name='create' value='"._t("CREATE_NEW_X",MB::strtolower(_t("LINK")))."' style='margin:2px 0;' class='sys_form_button' id='create' />\n";
		echo "</div>\n";
		echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
            //Reset positions
			echo "<input type='button' name='reset' value='"._t("RESET_POSITIONS")."' style='margin:2px 0;' class='sys_form_button' id='reset' />\n";
			//Delete permanently
			echo "<input type='button' name='delete' value='"._t("DELETE_PERMANENTLY")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
		echo "</div>\n";

        echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
            $preroles = Ram::Get("roles");
			$preroles['ALL']['name'] = _t("EVERYONE");

            //Head
            echo "<thead>\n";
				echo "<tr>\n";
					echo "<th colspan='5'>"._t("HEAD")."</th>\n";
				echo "</tr>\n";
			echo "</thead>\n";
			if ($result = $Db->GetList("SELECT * FROM #__menu WHERE zone='head' ORDER BY position")) {
				echo "<thead>\n";
				echo "<tr>\n";
					echo "<th width='1%' style='text-align:right;'></th>\n";
					echo "<th width='25%'>"._t("TITLE")."</th>\n";
					echo "<th width='65%'>"._t("LINK")."</th>\n";
                    echo "<th width='5%'>&nbsp;</th>\n";
					echo "<th width='4%' style='text-align:center;'>"._t("POSITION")."</th>\n";
				echo "</tr>\n";
				echo "</thead>\n";
				echo "<tbody>\n";

				foreach ($result as $row) {
					$id         = Io::Output($row['id'],"int");
					$title      = Io::Output($row['title']);
					$url        = Io::Output($row['url']);
                    $position   = Io::Output($row['position'],"int");
                    $roles      = Utils::Unserialize(Io::Output($row['roles']));
                    if (!sizeof($roles)|| empty($roles)) $roles = array('ALL');

					echo "<tr onmouseover='javascript:showmenu($id);' onmouseout='javascript:showmenu($id);'>\n";
						echo "<td><input type='checkbox' name='selected[]' value='$id' class='cb' /><br />&nbsp;</td>\n";
						echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=editmenu&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("LINK")))."'><strong>$title</strong></a>\n";
                            echo "<div id='menu_$id' style='display:none; margin-top:2px;'>\n";
								echo "<a href='".str_replace("{NODE}",_NODE,$url)."' title='"._t("OPEN_THIS_X",MB::strtolower(_t("LINK")))."' rel='external'>"._t("OPEN")."</a> - \n";
                                echo "<a href='admin.php?cont="._PLUGIN."&amp;op=editmenu&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("LINK")))."'>"._t("EDIT")."</a>\n";
								$ronames = array();
								foreach ($roles as $role) if (isset($preroles[$role]['name'])) $ronames[] = $preroles[$role]['name'];
								$roles = _t("WHO_ACCESS_THE_X",MB::strtolower("LINK")).": ".implode(", ",$ronames);
								echo " - <a title='$roles'>"._t("ROLES")."</a>\n";
							echo "</div>\n";
                        echo "</td>\n";
						echo "<td>$url</td>\n";
                        echo "<td>\n";
                            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=switchpos&amp;id=$id&amp;dir=up'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."icons"._DS."up.png' alt='Up' /></a> ";
                            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=switchpos&amp;id=$id&amp;dir=down'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."icons"._DS."down.png' alt='Down' /></a>";
                        echo "</td>\n";
						echo "<td style='text-align:center;'>$position</td>\n";
					echo "</tr>\n";
				}
			} else {
				echo "<tbody>\n";
				echo "<tr>\n";
					echo "<td style='text-align:center;'>"._t("LIST_EMPTY")."</td>\n";
				echo "</tr>\n";
			}

            //Nav
            echo "<thead>\n";
				echo "<tr>\n";
					echo "<th colspan='5'>"._t("NAV")."</th>\n";
				echo "</tr>\n";
			echo "</thead>\n";
			if ($result = $Db->GetList("SELECT * FROM #__menu WHERE zone='nav' ORDER BY position")) {
				echo "<thead>\n";
				echo "<tr>\n";
					echo "<th width='1%' style='text-align:right;'></th>\n";
					echo "<th width='25%'>"._t("TITLE")."</th>\n";
					echo "<th width='65%'>"._t("LINK")."</th>\n";
                    echo "<th width='5%'>&nbsp;</th>\n";
					echo "<th width='4%' style='text-align:center;'>"._t("POSITION")."</th>\n";
				echo "</tr>\n";
				echo "</thead>\n";
				echo "<tbody>\n";

				foreach ($result as $row) {
					$id         = Io::Output($row['id'],"int");
					$title      = Io::Output($row['title']);
					$url        = Io::Output($row['url']);
                    $position   = Io::Output($row['position'],"int");
                    $roles      = Utils::Unserialize(Io::Output($row['roles']));
                    if (!sizeof($roles)|| empty($roles)) $roles = array('ALL');

					echo "<tr onmouseover='javascript:showmenu($id);' onmouseout='javascript:showmenu($id);'>\n";
						echo "<td><input type='checkbox' name='selected[]' value='$id' class='cb' /><br />&nbsp;</td>\n";
						echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=editmenu&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("LINK")))."'><strong>$title</strong></a>\n";
                            echo "<div id='menu_$id' style='display:none; margin-top:2px;'>\n";
								echo "<a href='".str_replace("{NODE}",_NODE,$url)."' title='"._t("OPEN_THIS_X",MB::strtolower(_t("LINK")))."' rel='external'>"._t("OPEN")."</a> - \n";
                                echo "<a href='admin.php?cont="._PLUGIN."&amp;op=editmenu&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("LINK")))."'>"._t("EDIT")."</a>\n";
								$ronames = array();
								foreach ($roles as $role) if (isset($preroles[$role]['name'])) $ronames[] = $preroles[$role]['name'];
								$roles = _t("WHO_ACCESS_THE_X",MB::strtolower("LINK")).": ".implode(", ",$ronames);
								echo " - <a title='$roles'>"._t("ROLES")."</a>\n";
							echo "</div>\n";
                        echo "</td>\n";
						echo "<td>$url</td>\n";
                        echo "<td>\n";
                            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=switchpos&amp;id=$id&amp;dir=up'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."icons"._DS."up.png' alt='Up' /></a> ";
                            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=switchpos&amp;id=$id&amp;dir=down'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."icons"._DS."down.png' alt='Down' /></a>";
                        echo "</td>\n";
						echo "<td style='text-align:center;'>$position</td>\n";
					echo "</tr>\n";
				}
			} else {
				echo "<tbody>\n";
				echo "<tr>\n";
					echo "<td style='text-align:center;'>"._t("LIST_EMPTY")."</td>\n";
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

    function SwitchLinkPosition() {
		global $Db;

		$id = Io::GetVar("GET","id","int");
        $dir = Io::GetVar("GET","dir");

        if ($row = $Db->GetRow("SELECT zone,position FROM #__menu WHERE id=".intval($id))) {
            $zone       = Io::Output($row['zone']);
            $position   = Io::Output($row['position'],"int");

            switch ($dir) {
                case "up":
                    if ($Db->GetNum("SELECT id FROM #__menu WHERE zone='".$Db->_e($zone)."' AND position<".intval($position))) {
                        $Db->Query("UPDATE #__menu SET position=position+1 WHERE zone='".$Db->_e($zone)."' AND position=".($position-1));
                        $Db->Query("UPDATE #__menu SET position=position-1 WHERE id=".intval($id));
                    }
                    break;
                case "down":
                    if ($Db->GetNum("SELECT id FROM #__menu WHERE zone='".$Db->_e($zone)."' AND position>".intval($position))) {
                        $Db->Query("UPDATE #__menu SET position=position-1 WHERE zone='".$Db->_e($zone)."' AND position=".($position+1));
                        $Db->Query("UPDATE #__menu SET position=position+1 WHERE id=".intval($id));
                    }
                    break;
            }
        }
        Utils::Redirect("admin.php?cont="._PLUGIN."&op=menu");
	}

    function ResetMenuPositions() {
        global $Db;
        
        //Head
        if ($result = $Db->GetList("SELECT id FROM #__menu WHERE zone='head' ORDER BY position ASC")) {
            $pos = 0;
            foreach ($result as $row) {
                $id = Io::Output($row['id'],"int");
                $Db->Query("UPDATE #__menu SET position='".intval($pos)."' WHERE id=".$id);
                $pos++;
            }
        }
        //Nav
        if ($result = $Db->GetList("SELECT id FROM #__menu WHERE zone='nav' ORDER BY position ASC")) {
            $pos = 0;
            foreach ($result as $row) {
                $id = Io::Output($row['id'],"int");
                $Db->Query("UPDATE #__menu SET position='".intval($pos)."' WHERE id=".$id);
                $pos++;
            }
        }
        Utils::Redirect("admin.php?cont="._PLUGIN."&op=menu");
    }

    function AddToMenu() {
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
			});
        </script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpage' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=addredirect' title='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."addredirect.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=static' title='"._t("STATIC_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."page.png' alt='"._t("STATIC_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=redirects' title='"._t("REDIRECTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."redirect.png' alt='"._t("REDIRECTIONS")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=internal' title='"._t("INTERNAL_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."internal.png' alt='"._t("INTERNAL_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menu' title='"._t("MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menu.png' alt='"._t("MENU_EDITOR")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menuacp' title='"._t("ADMIN_MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menuacp.png' alt='"._t("ADMIN_MENU_EDITOR")."' /></a>\n";
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("CREATE_NEW_X",MB::strtolower(_t("LINK"))); ?></div>
                        <div class="body">

                        <?php

                        if (!isset($_POST['create'])) {
                                $form = new Form();
								$form->action = "admin.php?cont="._PLUGIN."&amp;op=addmenu";
								$form->Open();

                                //Title
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("TITLE"),
														"width"		=>"300px",
														"name"		=>"title"));

                                //Address
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("URL"),
														"name"		=>"url",
														"width"		=>"400px",
														"info"		=>_t("USE_NODE_PLACEHOLDER")));

                                //Zone
                                $form->AddElement(array("element"	=>"select",
														"label"		=>_t("ZONE"),
														"name"		=>"zone",
														"values"	=>array(_t("HEAD") => "head",
																			_t("NAV") => "nav")));
                                
                                //Position
                                $form->AddElement(array("element"	=>"text",
														"label"		=>_t("POSITION"),
														"width"		=>"100px",
														"name"		=>"position",
                                                        "info"      =>_t("LEAVE_BLANK_DEFAULT_VAL")));

                                ?>
										</div>
									</div>
                                </td>
								<td class="sidebar">
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
														"label"		=>_t("WHO_ACCESS_THE_X",MB::strtolower("LINK")),
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
														"name"		=>"create",
														"inline"	=>true,
														"value"		=>_t("CREATE")));

                                $form->Close();
                        } else {
                            //Check token
							if (Utils::CheckToken()) {
								//Get POST data
								$title = Io::GetVar('POST','title','fullhtml');
								$url = Io::GetVar('POST','url','nohtml');
                                $zone = Io::GetVar('POST','zone','nohtml');
                                $position = Io::GetVar('POST','position','int');
								$roles = Io::GetVar('POST','roles','nohtml',true,array());

								$errors = array();
								if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
								if (empty($url)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("URL"));

								if (!sizeof($errors)) {
									if (in_array("ALL",$roles)) $roles = array();
									$roles = Utils::Serialize($roles);

                                    if ($position==0) {
                                        $row = $Db->GetRow("SELECT position FROM #__menu WHERE zone='".$Db->_e($zone)."' ORDER BY position DESC LIMIT 1");
                                        $position = Io::Output($row['position']);
                                        $position++;
                                    }

									$Db->Query("INSERT INTO #__menu (title,url,zone,position,roles)
                                                VALUES ('".$Db->_e($title)."','".$Db->_e($url)."','".$Db->_e($zone)."','".intval($position)."','".$Db->_e($roles)."')");
                                    
									Utils::Redirect("admin.php?cont="._PLUGIN."&op=menu");
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

    function EditMenuLink() {
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
			});
        </script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpage' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=addredirect' title='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."addredirect.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=static' title='"._t("STATIC_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."page.png' alt='"._t("STATIC_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=redirects' title='"._t("REDIRECTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."redirect.png' alt='"._t("REDIRECTIONS")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=internal' title='"._t("INTERNAL_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."internal.png' alt='"._t("INTERNAL_PAGES")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menu' title='"._t("MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menu.png' alt='"._t("MENU_EDITOR")."' /></a>\n";
            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menuacp' title='"._t("ADMIN_MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menuacp.png' alt='"._t("ADMIN_MENU_EDITOR")."' /></a>\n";
		?>
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("CREATE_NEW_X",MB::strtolower(_t("LINK"))); ?></div>
                        <div class="body">

                        <?php

                        $id = Io::GetVar('GET','id','int');
                        if ($row = $Db->GetRow("SELECT * FROM #__menu WHERE id=".intval($id))) {
                            if (!isset($_POST['save'])) {
                                    $title      = Io::Output($row['title']);
                                    $url        = Io::Output($row['url']);
                                    $zone        = Io::Output($row['zone']);
                                    $position   = Io::Output($row['position'],"int");
                                    $roles      = Utils::Unserialize(Io::Output($row['roles']));
                                    if (!sizeof($roles)|| empty($roles)) $roles = array('ALL');

                                    $form = new Form();
                                    $form->action = "admin.php?cont="._PLUGIN."&amp;op=editmenu&amp;id=$id";
                                    $form->Open();

                                    //Title
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("TITLE"),
                                                            "width"		=>"300px",
                                                            "name"		=>"title",
                                                            "value"		=>$title));

                                    //Address
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("URL"),
                                                            "name"		=>"url",
                                                            "value"     =>$url,
                                                            "width"		=>"400px",
                                                            "info"		=>_t("USE_NODE_PLACEHOLDER")));

                                    //Zone
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("ZONE"),
                                                            "name"		=>"zone",
                                                            "values"	=>array(_t("HEAD") => "head",
                                                                                _t("NAV") => "nav"),
                                                            "selected"  =>$zone));

                                    //Position
                                    $form->AddElement(array("element"	=>"text",
                                                            "label"		=>_t("POSITION"),
                                                            "width"		=>"100px",
                                                            "name"		=>"position",
                                                            "value"     =>$position,
                                                            "info"      =>_t("LEAVE_BLANK_DEFAULT_VAL")));

                                    ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="sidebar">
                                        <div class="widget ui-widget-content ui-corner-all">
                                            <div class="ui-widget-header"><?php echo _t("AUTHORIZATION_MANAGER"); ?></div>
                                            <div class="body">
                                    <?php

                                    //Required roles
                                    $result = $Db->GetList("SELECT title,label FROM #__rba_roles ORDER BY rid");
                                    $rba = array();
                                    $rba[_t("EVERYONE")] = "ALL";
                                    if (!sizeof($roles)|| empty($roles)) $roles = array('ALL');
                                    foreach ($result as $row) $rba[Io::Output($row['title'])] = Io::Output($row['label']);
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("WHO_ACCESS_THE_X",MB::strtolower("LINK")),
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
                                    $url = Io::GetVar('POST','url','nohtml');
                                    $zone = Io::GetVar('POST','zone','nohtml');
                                    $position = Io::GetVar('POST','position','int');
                                    $roles = Io::GetVar('POST','roles','nohtml',true,array());

                                    $errors = array();
                                    if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
                                    if (empty($url)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("URL"));

                                    if (!sizeof($errors)) {
                                        if (in_array("ALL",$roles)) $roles = array();
                                        $roles = Utils::Serialize($roles);

                                        if ($position==0) {
                                            $row = $Db->GetRow("SELECT position FROM #__menu WHERE zone='".$Db->_e($zone)."' ORDER BY position DESC LIMIT 1");
                                            $position = Io::Output($row['position']);
                                            $position++;
                                        }

                                        $Db->Query("UPDATE #__menu SET title='".$Db->_e($title)."',url='".$Db->_e($url)."',zone='".$Db->_e($zone)."',
                                                                       position='".intval($position)."',roles='".$Db->_e($roles)."' WHERE id=".intval($id));

                                        Utils::Redirect("admin.php?cont="._PLUGIN."&op=menu");
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
                            MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("LINK")));
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

    function DeleteFromMenu() {
        global $Db;

		$items = Io::GetVar("POST","items",false,true);

		$result = $Db->Query("DELETE FROM #__menu WHERE id IN (".$Db->_e($items).")") ? 1 : 0 ;
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

    //MenuAcp
    function AcpMenuEditor() {
    	global $Db,$config_sys;
    
    	//Initialize and show site header
    	Layout::Header();
    	//Start buffering content
    	Utils::StartBuffering();
    
    	?>
    
            <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
            <div style="text-align:right;">
            <?php
    			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."' /></a>\n";
    			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpage' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."' /></a>\n";
    			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=addredirect' title='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."addredirect.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."' /></a>\n";
                echo "<a href='admin.php?cont="._PLUGIN."&amp;type=static' title='"._t("STATIC_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."page.png' alt='"._t("STATIC_PAGES")."' /></a>\n";
                echo "<a href='admin.php?cont="._PLUGIN."&amp;op=redirects' title='"._t("REDIRECTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."redirect.png' alt='"._t("REDIRECTIONS")."' /></a>\n";
                echo "<a href='admin.php?cont="._PLUGIN."&amp;type=internal' title='"._t("INTERNAL_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."internal.png' alt='"._t("INTERNAL_PAGES")."' /></a>\n";
                echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menu' title='"._t("MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menu.png' alt='"._t("MENU_EDITOR")."' /></a>\n";
                echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menuacp' title='"._t("ADMIN_MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menuacp.png' alt='"._t("ADMIN_MENU_EDITOR")."' /></a>\n";
    		?>
            </div>
    
            <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
            	<tr>
    		       	<td style="vertical-align:top;">
                        <div class="widget ui-widget-content ui-corner-all">
                            <div class="ui-widget-header"><?php echo _t("ADMIN_MENU_EDITOR"); ?></div>
                            <div class="body">
    
            <?php
    		
            echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
            if ($result = $Db->GetList("SELECT * FROM #__menu_acp WHERE submenu=0 AND status='active' ORDER BY title")) {
    			foreach ($result as $row) {
    				$menu		= Io::Output($row['menu']);
    				$title		= Io::Output($row['title']);
    				$url		= Io::Output($row['url']);
    				
	                //Head
	                echo "<thead>\n";
	    				echo "<tr>\n";
	    					echo "<th colspan='3'>$title</th>\n";
	    				echo "</tr>\n";
	    			echo "</thead>\n";
	    			if ($result = $Db->GetList("SELECT * FROM #__menu_acp WHERE submenu=1 AND menu='".$Db->_e($menu)."' AND status='active' ORDER BY title")) {
	    				echo "<thead>\n";
	    				echo "<tr>\n";
	    					echo "<th width='25%'>"._t("TITLE")."</th>\n";
	    					echo "<th width='60%'>"._t("LINK")."</th>\n";
	    					echo "<th width='15%' style='text-align:center;'>"._t("SHOW_IN_QUICK_LINKS")."</th>\n";
	    				echo "</tr>\n";
	    				echo "</thead>\n";
	    				echo "<tbody>\n";
	    
	    				foreach ($result as $row) {
	    					$id         = Io::Output($row['id'],"int");
	    					$title      = Io::Output($row['title']);
	    					$url        = Io::Output($row['url']);
	    					$quickicons	= Io::Output($row['quickicons'],"int");
	    
	    					echo "<tr>\n";
	    						echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=editmenuacp&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("LINK")))."'><strong>$title</strong></a>\n";
	                            echo "<td>$url</td>\n";
	                            echo "<td style='text-align:center;'>".(($quickicons) ? _t("YES") : _t("NO"))."</td>\n";
	    					echo "</tr>\n";
	    				}
	    			} else {
	    				echo "<tbody>\n";
	    				echo "<tr>\n";
	    					echo "<td style='text-align:center;' colspan='3'>"._t("LIST_EMPTY")."</td>\n";
	    				echo "</tr>\n";
	    			}
    			}
    
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
    
    function EditAcpMenuLink() {
    	global $Db,$config_sys;
    
    	//Initialize and show site header
    	Layout::Header();
    	//Start buffering content
    	Utils::StartBuffering();
    
    	?>
    
            <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
            <div style="text-align:right;">
            <?php
    			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."' /></a>\n";
    			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpage' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."' /></a>\n";
    			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=addredirect' title='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."addredirect.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."' /></a>\n";
                echo "<a href='admin.php?cont="._PLUGIN."&amp;type=static' title='"._t("STATIC_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."page.png' alt='"._t("STATIC_PAGES")."' /></a>\n";
                echo "<a href='admin.php?cont="._PLUGIN."&amp;op=redirects' title='"._t("REDIRECTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."redirect.png' alt='"._t("REDIRECTIONS")."' /></a>\n";
                echo "<a href='admin.php?cont="._PLUGIN."&amp;type=internal' title='"._t("INTERNAL_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."internal.png' alt='"._t("INTERNAL_PAGES")."' /></a>\n";
                echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menu' title='"._t("MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menu.png' alt='"._t("MENU_EDITOR")."' /></a>\n";
                echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menuacp' title='"._t("ADMIN_MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menuacp.png' alt='"._t("ADMIN_MENU_EDITOR")."' /></a>\n";
    		?>
            </div>
    
            <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
            	<tr>
    		       	<td style="vertical-align:top;">
                        <div class="widget ui-widget-content ui-corner-all">
                            <div class="ui-widget-header"><?php echo _t("EDIT_X",MB::strtolower(_t("LINK"))); ?></div>
                            <div class="body">
    
                            <?php
    
                            $id = Io::GetVar('GET','id','int');
                            if ($row = $Db->GetRow("SELECT * FROM #__menu_acp WHERE id=".intval($id))) {
                                if (!isset($_POST['save'])) {
                                        $title      = Io::Output($row['title']);
                                        $url        = Io::Output($row['url']);
                                        $quickicons = Io::Output($row['quickicons'],"int");
                                        
                                        $form = new Form();
                                        $form->action = "admin.php?cont="._PLUGIN."&amp;op=editmenuacp&amp;id=$id";
                                        $form->Open();
    
                                        //Title
                                        $form->AddElement(array("element"	=>"text",
                                                                "label"		=>_t("TITLE"),
                                                                "width"		=>"300px",
                                                                "name"		=>"title",
                                                                "value"		=>$title));
                                        
                                        //Address
                                        $form->AddElement(array("element"	=>"text",
                                                                "label"		=>_t("URL"),
                                                                "name"		=>"url",
                                                                "value"     =>$url,
                                                                "width"		=>"400px",
                                        						"readonly"	=>true));
                                        
                                        //Quick link
                                        $form->AddElement(array("element"	=>"select",
                                            					"label"		=>_t("SHOW_IN_QUICK_LINKS"),
                                            					"name"		=>"quickicons",
                                            					"values"	=>array(_t("NO") => "0",
                                        											_t("YES") => "1"),
                                            					"selected"  =>$quickicons));
    
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
                                        $quickicons = Io::GetVar('POST','quickicons','int');
                                        
                                        $errors = array();
                                        if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
                                        
                                        if (!sizeof($errors)) {
                                            
                                            $Db->Query("UPDATE #__menu_acp SET title='".$Db->_e($title)."',quickicons='".intval($quickicons)."' WHERE id=".intval($id));
    
                                            Utils::Redirect("admin.php?cont="._PLUGIN."&op=menuacp");
                                        } else {
                                            MemErr::Trigger("USERERROR",implode("<br />",$errors));
                                        }
                                    } else {
                                        MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
                                    }
                                }
                            } else {
                                MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("LINK")));
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
    
    //Options
    function PluginOptions() {
        global $Db,$config_sys;

		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();

		$controller = Io::GetVar("GET","controller","#[^a-zA-Z0-9\-]#i");
		?>
		
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				<?php
				if (!Io::GetCookie("waropt","int",false,0)) {
				?>
				$("#dialog-message").dialog({
					resizable: false,
					modal: true,
					buttons: {
						"<?php echo _t("CONTINUE"); ?>": function() {
							$(this).dialog("close");
							var date = new Date();
					        date.setTime(date.getTime()+(60*60*1000));
							document.cookie = "waropt=1; expires="+date.toGMTString()+"; path=<?php echo _COOKIEPATH; ?>";
						},
						"<?php echo _t("LEAVEPAGE"); ?>": function() {
							$(this).dialog("close");
							location = 'admin.php';
						}
					}
				});						
				<?php } ?>
                //Add
				$('input#create').click(function() {
                    window.location.href = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=addoption&controller=<?php echo $controller; ?>';
                });
			});

			function deleteoption(label) {
				if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("OPTION"))); ?>')) {
					$.ajax({
						type: "POST",
						dataType: "xml",
						url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=deleteoption&controller=<?php echo $controller; ?>",
						data: "label="+label,
						success: function(data){
							location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=options&controller=<?php echo $controller; ?>';
						}
					});
				}
			}
		</script>
		
        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>&amp;op=options&amp;controller=<?php echo $controller; ?>" title="<?php echo _t("PLUGIN_OPTIONS"); ?>"><?php echo _t("PLUGIN_OPTIONS"); ?></a></div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <?php
						
						if ($row = $Db->GetRow("SELECT title,name,options FROM #__content WHERE controller='".$Db->_e($controller)."'")) {
							$title		= Io::Output($row['title']);
							$name		= Io::Output($row['name']);
							$options	= Utils::Unserialize(Io::Output($row['options']));
							
							?>
							<div class="ui-widget-header"><?php echo $title; ?></div>
							<div class="body">
							<?php
						
							echo "<div style='float:left; margin:6px 0 2px 0;'>\n";
								//Add
								echo "<input type='button' name='create' value='"._t("ADD_NEW_X",MB::strtolower(_t("OPTION")))."' style='margin:2px 0;' class='sys_form_button' id='create' />\n";
							echo "</div>\n";
							
							echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
							echo "<thead>\n";
								echo "<tr>\n";
									echo "<th width='45%'>"._t("LABEL")."</th>\n";
									echo "<th width='45%'>"._t("VALUE")."</th>\n";
									echo "<th width='10%'>&nbsp;</th>\n";
								echo "</tr>\n";
							echo "</thead>\n";
							echo "<tbody>\n";
							
							if (sizeof($options)) {
								unset($options['layout']);
								foreach ($options as $key => $value) {
									echo "<tr>\n";
										echo "<td style='vertical-align:middle;'><a href='admin.php?cont="._PLUGIN."&amp;op=editoption&amp;controller=$controller&amp;label=$key' title='"._t("EDIT_THIS_X",MB::strtolower(_t("OPTION")))."'>$key</a></td>\n";
										echo "<td style='vertical-align:middle;'>$value</td>\n";
										echo "<td style='text-align:right;'>\n";
											echo "<input type='button' value='"._t("DELETE")."' class='sys_form_button' onclick=\"javascript:deleteoption('$key');\" />\n";
										echo "</td>\n";
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
                        
                        <?php 
                        } else {
							?>
							<div class="ui-widget-header"><?php echo $controller; ?></div>
							<div class="body">
							<?php
                        	MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("PLUGIN")));
                        }
                        ?>
                        
                        </div>
                    </div>
                </td>
                <?php 
                if (isset($title)) {
                ?>
                <td class="sidebar">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("DOCUMENTS"); ?></div>
                        <div class="body">
							<?php echo _t("READ_THIS_X_FOR_MORE_INFORMATION","http://www.memht.com/docs/plugins/options"); ?>
                        </div>
                    </div>
                </td>
                <?php 
                }
                ?>
            </tr>
        </table>
        
        <?php
		if (!Io::GetCookie("waropt","int",false,0)) {
		?>
        <div id="dialog-message" title="<?php echo _t("WARNING"); ?>">
        <p>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
        <?php echo _t("WARNINGPOTPROB"); ?>
        </p>
        </div>
        <?php
		}

		//Assign captured content to the template engine and clean buffer
		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
		//Draw site template
		Template::Draw();
		//Initialize and show site footer
		Layout::Footer();
    }
    
    function AddPluginOptions() {
    	global $Db,$User,$config_sys,$Router;
    
    	//Initialize and show site header
    	Layout::Header();
    	//Start buffering content
    	Utils::StartBuffering();
    
    	$controller = Io::GetVar("GET","controller","#[^a-zA-Z0-9\-]#i");
    	?>
            
            <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>&amp;op=options&amp;controller=<?php echo $controller; ?>" title="<?php echo _t("PLUGIN_OPTIONS"); ?>"><?php echo _t("PLUGIN_OPTIONS"); ?></a></div>
            
            <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
            	<tr>
    		       	<td style="vertical-align:top;">
                        <div class="widget ui-widget-content ui-corner-all">
                            <div class="ui-widget-header"><?php echo _t("ADD_NEW_X",MB::strtolower(_t("OPTION"))); ?></div>
                            <div class="body">
                            
    						<?php
    						
    						if (!isset($_POST['create'])) {
    								$form = new Form();
    								$form->action = "admin.php?cont="._PLUGIN."&amp;op=addoption&amp;controller=$controller";
    								
    								$form->Open();

    								//Label
    								$form->AddElement(array("element"	=>"text",
    														"label"		=>_t("LABEL"),
    														"name"		=>"label",
    														"width"		=>"300px",
    														"info"		=>_t("REQUIRED")));
    								
    								//Value
    								$form->AddElement(array("element"	=>"text",
    														"label"		=>_t("VALUE"),
    														"name"		=>"value",
    														"width"		=>"300px",
    														"info"		=>_t("REQUIRED")));
    
    								?>						
    								
                                    <div style="padding:2px;"></div>
                                    <?php
    										
    								//Create
    								$form->AddElement(array("element"	=>"submit",
    														"name"		=>"create",
    														"inline"	=>true,
    														"value"		=>_t("ADD")));
    								
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
    								$label = Io::GetVar('POST','label');
    								$value = Io::GetVar('POST','value');
    								
    								$errors = array();
    								if (empty($label)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LABEL"));
    								if ($value=="") $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("VALUE"));
    								
    								if (!sizeof($errors)) {
    									$Router->SetOption($label,$value,$controller);
    												
    									Utils::Redirect("admin.php?cont="._PLUGIN."&op=options&controller=$controller");
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
    	
    	function EditPluginOptions() {
    		global $Db,$User,$config_sys,$Router;
    		
    		//Initialize and show site header
    		Layout::Header();
    		//Start buffering content
    		Utils::StartBuffering();
    		
    		$controller = Io::GetVar("GET","controller","#[^a-zA-Z0-9\-]#i");
    		$label = Io::GetVar("GET","label");
    		?>
            
            <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>&amp;op=options&amp;controller=<?php echo $controller; ?>" title="<?php echo _t("PLUGIN_OPTIONS"); ?>"><?php echo _t("PLUGIN_OPTIONS"); ?></a></div>
            
            <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
            	<tr>
    		       	<td style="vertical-align:top;">
                        <div class="widget ui-widget-content ui-corner-all">
                            <div class="ui-widget-header"><?php echo _t("EDIT"); ?></div>
                            <div class="body">
                            
    						<?php
    						
    						$id = Io::GetVar('GET','id','int');
    						if ($row = $Db->GetRow("SELECT options FROM #__content WHERE controller='".$Db->_e($controller)."'")) {
    							if (!isset($_POST['save'])) {
    								$form = new Form();
    								$form->action = "admin.php?cont="._PLUGIN."&amp;op=editoption&amp;controller=$controller";
    								
    								$form->Open();
    								
    								$opt = Utils::Unserialize(Io::Output($row['options']));
    								$value = (isset($opt[$label])) ? $opt[$label] : "" ;
    	
    								//Title
    								$form->AddElement(array("element"	=>"text",
    														"label"		=>_t("LABEL"),
    														"width"		=>"300px",
    														"value"		=>$label,
    														"name"		=>"label",
    														"readonly"	=>true,
    														"info"		=>_t("REQUIRED")));
    														
    								//Name
    								$form->AddElement(array("element"	=>"text",
    														"label"		=>_t("LINK_NAME"),
    														"name"		=>"value",
    														"value"		=>$value,
    														"width"		=>"300px",
    														"info"		=>_t("REQUIRED")));
    								
    								?>						
    								
                                    <div style="padding:2px;"></div>
                                    <?php
    										
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
	    								$label = Io::GetVar('POST','label');
	    								$value = Io::GetVar('POST','value');
	    								
	    								$errors = array();
	    								if (empty($label)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LABEL"));
	    								if ($value=="") $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("VALUE"));
	    								
	    								if (!sizeof($errors)) {
	    									$Router->SetOption($label,$value,$controller);
	    												
	    									Utils::Redirect("admin.php?cont="._PLUGIN."&op=options&controller=$controller");
	    								} else {
	    									MemErr::Trigger("USERERROR",implode("<br />",$errors));
	    								}
	    							} else {
	    								MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
	    							}
    							}
    						} else {
    							MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("PLUGIN")));
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
    
    function DeletePluginOptions() {
    	global $Db,$Router;
    
    	$controller = Io::GetVar("GET","controller","#[^a-zA-Z0-9\-]#i");
    	$label = Io::GetVar("POST","label");
    	
    	$result = $Router->DeleteOption($label,$controller);
    
    	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
    	header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
    	header("Cache-Control: no-cache, must-revalidate" );
    	header("Pragma: no-cache" );
    	header("Content-Type: text/xml");
    	
    	$xml = '<?xml version="1.0" encoding="utf-8"?>\n';
    	$xml .= '<response>\n';
    	$xml .= '<result>\n';
    	$xml .= '<query>'.$result.'</query>\n';
    	$xml .= '<rows>1</rows>\n';
    	$xml .= '</result>\n';
    	$xml .= '</response>';
    	return $xml;
    }
    
    //Redirect
    function ListRedirects() {
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
        	            window.location.href = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=addredirect';
        		    });
        		
        		    //Delete permanently
        			$('input#delete').click(function() {
        				var obj = $('.cb:checkbox:checked');
        	            if (obj.length>0) {
        				    if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("REDIRECTION"))); ?>')) {
        					    var items = new Array();
        					    for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
        						$.ajax({
        							type: "POST",
        							dataType: "xml",
        							url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=delredirects",
        							data: "items="+items,
        							success: function(data){
        			                    location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=redirects';
        							}
        						});
        				    }
        				} else {
        					alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("REDIRECTION"))); ?>');
        				}
        			});
    		    });
    		</script>        

    		<div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
    		<div style="text-align:right;">
    		<?php
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpage' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."' /></a>\n";
				echo "<a href='admin.php?cont="._PLUGIN."&amp;op=addredirect' title='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."addredirect.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."' /></a>\n";
	            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=static' title='"._t("STATIC_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."page.png' alt='"._t("STATIC_PAGES")."' /></a>\n";
	            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=redirects' title='"._t("REDIRECTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."redirect.png' alt='"._t("REDIRECTIONS")."' /></a>\n";
	            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=internal' title='"._t("INTERNAL_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."internal.png' alt='"._t("INTERNAL_PAGES")."' /></a>\n";
	            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menu' title='"._t("MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menu.png' alt='"._t("MENU_EDITOR")."' /></a>\n";
	            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menuacp' title='"._t("ADMIN_MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menuacp.png' alt='"._t("ADMIN_MENU_EDITOR")."' /></a>\n";
			?>
    		</div>
    	    <?php
            		
    		echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' summary=''>";
    		echo "<tr>";
    		    echo "<td style='vertical-align:top;'>";            
                    echo "<div class='widget ui-widget-content ui-corner-all'>";
                        echo "<div class='ui-widget-header'>"._t("MANAGE_REDIRECTS")."</div>";
                    	echo "<div class='body'>";
     
    				        echo "<div style='float:left; margin:6px 0 2px 0;'>\n";
    					        echo "<input type='button' name='create' value='"._t("ADD_NEW_X",MB::strtolower(_t("REDIRECTION")))."' style='margin:2px 0;' class='sys_form_button' id='create' />\n";
    				        echo "</div>\n";
    				        echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
    					        echo "<input type='button' name='delete' value='"._t("DELETE_PERMANENTLY")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
    				        echo "</div>\n";
                            
    	            	        echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
    	            	            echo "<thead>\n";
    	            				echo "<tr>\n";
    	            					echo "<th width='1%' style='text-align:right;'><input type='checkbox' id='selectall' /></th>\n";
    	            					echo "<th width='35%'>"._t("TITLE")."</th>\n";
    	            					echo "<th width='59%'>"._t("URL")."</th>\n";
                                        echo "<th width='5%'>"._t("STATUS")."</th>\n";
    	            				echo "</tr>\n";
    	            				echo "</thead>\n";
    	            				echo "<tbody>\n";
    	
    		                            if ($result = $Db->GetList("SELECT * FROM #__content WHERE type='REDIRECT' ORDER BY title")) {
    						                foreach ($result as $row) {
    							                $id	      = Io::Output($row['id'],"int");
    						                    $title	  = Io::Output($row['title']);
    							                $content  = Io::Output($row['content']);
                                                $status =  (Io::Output($row['status'])!="off") ? _t("ACTIVE") : _t("INACTIVE");
    		
    		                                    echo "<tr>\n";
    								                echo "<td><input type='checkbox' name='selected[]' value='$id' class='cb' /></td>\n";
    								                echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=editredirection&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("REDIRECTION")))."'>$title</a></td>\n";
    								                echo "<td>$content</td>\n";
                                                    echo "<td>$status</td>\n";
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
        
        function AddRedirection() {
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
    				$('#autoname').click(function(){
    					$.ajax({
    						type: "POST",
    						dataType: "html",
    						url: "admin.php?cont=internal&op=cleanchar&lowercase=1",
    						data: "string="+$('#title').val(),
    						success: function(data,textStatus,XMLHttpRequest){
    							$('#urlvalidname').val(data);
    						},
    						error: function(XMLHttpRequest,textStatus,errorThrown) {
    							$('#urlvalidname').val('Error');
    						}
    					});
    				});
    			});
            </script>

            <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
            <div style="text-align:right;">
                <?php
					echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."' /></a>\n";
					echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpage' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."' /></a>\n";
					echo "<a href='admin.php?cont="._PLUGIN."&amp;op=addredirect' title='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."addredirect.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."' /></a>\n";
		            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=static' title='"._t("STATIC_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."page.png' alt='"._t("STATIC_PAGES")."' /></a>\n";
		            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=redirects' title='"._t("REDIRECTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."redirect.png' alt='"._t("REDIRECTIONS")."' /></a>\n";
		            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=internal' title='"._t("INTERNAL_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."internal.png' alt='"._t("INTERNAL_PAGES")."' /></a>\n";
		            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menu' title='"._t("MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menu.png' alt='"._t("MENU_EDITOR")."' /></a>\n";
		            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menuacp' title='"._t("ADMIN_MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menuacp.png' alt='"._t("ADMIN_MENU_EDITOR")."' /></a>\n";
				?>
            	</div>
            <?php
    
    		echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' summary=''>";
    		echo "<tr>";
    		    echo "<td style='vertical-align:top;'>";           
                    echo "<div class='widget ui-widget-content ui-corner-all'>";
                        echo "<div class='ui-widget-header'>"._t("ADD_NEW_X",MB::strtolower(_t("REDIRECTION")))."</div>";
                    	echo "<div class='body'>";
    					    
                            if (!isset($_POST['add'])) {
    					        
                                $form = new Form();
    					        $form->action = "admin.php?cont="._PLUGIN."&amp;op=addredirect";
    			
    						    $form->Open();
    			
    						    //Title
    						    $form->AddElement(array("element"	=>"text",
    													"label"		=>_t("TITLE"),
    													"width"		=>"300px",
    													"name"		=>"title",
    													"id"		=>"title"));
                                //Name
    							$form->AddElement(array("element"	=>"text",
    													"label"		=>_t("LINK_NAME"),
    													"name"		=>"name",
    													"width"		=>"300px",
    													"id"		=>"urlvalidname",
    													"suffix"	=>"<input type='button' id='autoname' value='"._t("AUTO")."' class='sys_form_button' />",
    													"info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));                                                      
    			
    							//URL
    							$form->AddElement(array("element"	=>"text",
    													"label"		=>_t("URL"),
    													"width"		=>"300px",
    													"name"		=>"url",
                                                        "info"		=>"http://...",
    													"id"		=>"url"));
    			
                    	echo "</div>";
                    echo "</div>";          
    		    echo "</td>";
        		echo "<td class='sidebar'>";
	        		echo "<div class='widget ui-widget-content ui-corner-all'>";
		        		echo "<div class='ui-widget-header'>"._t("OPTIONS")."</div>";
		        		echo "<div class='body'>";
		        		
		        		//Status
		        		$form->AddElement(array("element"	=>"select",
        		                                "label"		=>_t("STATUS"),
        		                                "name"		=>"status",
        		                                "values"	=>array(_t("ACTIVE")    => "active",
        															_t("INACTIVE") 	=> "off")));
		        		
		        		//Add in site menu
						$form->AddElement(array("element"	=>"select",
												"label"		=>_t("ADD_IN_SITE_MENU"),
												"name"		=>"addinmenu",
												"values"	=>array(_t("NO")        => "no",
																	_t("NAVIGATOR")	=> "nav",
																	_t("HEADER")	=> "head")));
		        		
		        		echo "</div>";
	        		echo "</div>";
        		
                    echo "<div class='widget ui-widget-content ui-corner-all'>";
                        echo "<div class='ui-widget-header'>"._t("AUTHORIZATION_MANAGER")."</div>";
                        echo "<div class='body'>";
                                
                            //Required roles
        					$result = $Db->GetList("SELECT title,label FROM #__rba_roles ORDER BY rid");
        					$rba = array();
        					$rba[_t("EVERYONE")] = "ALL";
        					foreach ($result as $row) $rba[Io::Output($row['title'])] = Io::Output($row['label']);
        					$form->AddElement(array("element"	=>"select",
        											"label"		=>_t("WHO_ACCESS_THE_X",MB::strtolower("PAGE")),
        											"name"		=>"roles[]",
        											"multiple"	=>true,
        											"values"	=>$rba,
        											"selected"	=>"ALL",
        											"info"		=>_t("MULTIPLE_CHOICES_ALLOWED")));  
                             //Install
        					$form->AddElement(array("element"	=>"submit",
        											"name"		=>"add",
        											"inline"	=>true,
        											"value"		=>_t("ADD")));
        
                            $form->Close();                                                        
                                                                                  
                        echo "</div>";
                    echo "</div>";
                            } else {
                    			//Check token
                    			if (Utils::CheckToken()) {
                    			    //Get POST data
                    				$title = Io::GetVar('POST','title','nohtml');
                                    $name = Io::GetVar('POST','name','[^a-zA-Z0-9\-]');
                                    $url = Io::GetVar('POST','url');
                    	            $status = Io::GetVar('POST','status','nohtml');
                                    $roles = Io::GetVar('POST','roles','nohtml',true,array());
                                    
                    			    $errors = array();
                    				if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
                    				if (empty($url)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("URL"));
                    			    if (!Utils::ValidUrl($url)) $errors[] = _t("THE_FIELD_X_IS_NOT_INVALID",_t("URL"));
                                    if (empty($name)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LINK_NAME"));
                                    if (in_array("ALL",$roles)) $roles = array();
    								$roles = Utils::Serialize($roles);
                                
                                
                    				if (!sizeof($errors)) {
    									$Db->Query("INSERT INTO #__content (title,name,controller,type,showtitle,meta_keywords,meta_description,content,cont_before,cont_after,options,roles,sitemap,searchable,status)
                                                    VALUES ('".$Db->_e($title)."','".$Db->_e($name)."','','REDIRECT','0','','','".$Db->_e($url)."','','','','".$Db->_e($roles)."','0','0','".$Db->_e($status)."')");

    									$addinmenu = Io::GetVar('POST','addinmenu','nohtml');
    									if ($addinmenu!="no") {
    										$position = 0;
    										if ($row = $Db->GetRow("SELECT position FROM #__menu WHERE zone='".$Db->_e($addinmenu)."' ORDER BY position DESC LIMIT 1")) {
    											$position = Io::Output($row['position'],"int");
    											$position++;
    										}
    										$Db->Query("INSERT INTO #__menu (title,url,zone,position,roles)
    									                VALUES ('".$Db->_e($title)."','index.php?{NODE}=".$Db->_e($name)."',
    									                		'".$Db->_e($addinmenu)."','".intval($position)."','".$Db->_e($roles)."')");
    									}
    									
                    					Utils::Redirect("admin.php?cont="._PLUGIN."&op=redirects");
                    				} else {
                    					MemErr::Trigger("USERERROR",implode("<br />",$errors));
                    				}
                    			} else {
                    				MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
                    			}
                            }                            
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
                    
        function EditRedirection() {
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
    				$('#autoname').click(function(){
    					$.ajax({
    						type: "POST",
    						dataType: "html",
    						url: "admin.php?cont=internal&op=cleanchar&lowercase=1",
    						data: "string="+$('#title').val(),
    						success: function(data,textStatus,XMLHttpRequest){
    							$('#urlvalidname').val(data);
    						},
    						error: function(XMLHttpRequest,textStatus,errorThrown) {
    							$('#urlvalidname').val('Error');
    						}
    					});
    				});
    			});
            </script>        
            <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
            <div style="text-align:right;">
                <?php
					echo "<a href='admin.php?cont="._PLUGIN."&amp;op=install' title='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."install.png' alt='"._t("INSTALL_NEW_X",MB::strtolower(_t("PLUGIN")))."' /></a>\n";
					echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpage' title='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("STATIC_PAGE")))."' /></a>\n";
					echo "<a href='admin.php?cont="._PLUGIN."&amp;op=addredirect' title='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."addredirect.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("REDIRECTION")))."' /></a>\n";
		            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=static' title='"._t("STATIC_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."page.png' alt='"._t("STATIC_PAGES")."' /></a>\n";
		            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=redirects' title='"._t("REDIRECTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."redirect.png' alt='"._t("REDIRECTIONS")."' /></a>\n";
		            echo "<a href='admin.php?cont="._PLUGIN."&amp;type=internal' title='"._t("INTERNAL_PAGES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."internal.png' alt='"._t("INTERNAL_PAGES")."' /></a>\n";
		            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menu' title='"._t("MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menu.png' alt='"._t("MENU_EDITOR")."' /></a>\n";
		            echo "<a href='admin.php?cont="._PLUGIN."&amp;op=menuacp' title='"._t("ADMIN_MENU_EDITOR")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."menuacp.png' alt='"._t("ADMIN_MENU_EDITOR")."' /></a>\n";
				?>
            	</div>
            <?php
    
    		echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' summary=''>";
    		echo "<tr>";
    		    echo "<td style='vertical-align:top;'>";           
                    echo "<div class='widget ui-widget-content ui-corner-all'>";
                        echo "<div class='ui-widget-header'>"._t("EDIT_X",MB::strtolower(_t("REDIRECTION")))."</div>";
                    	echo "<div class='body'>";
    					    $id = Io::GetVar('GET','id','int');
    						if ($row = $Db->GetRow("SELECT * FROM #__content WHERE type='REDIRECT' AND id=".intval($id))) {
                                if (!isset($_POST['edit'])) {
        					        $form = new Form();
        					        $form->action = "admin.php?cont="._PLUGIN."&amp;op=editredirect&amp;id=$id";
        			
        						    $form->Open();
        			
        						    //Title
        						    $form->AddElement(array("element"	=>"text",
        													"label"		=>_t("TITLE"),
        													"width"		=>"300px",
        													"name"		=>"title",
                                                            "value"		=>Io::Output($row['title']),
        													"id"		=>"title"));
                                    //Name
    								$form->AddElement(array("element"	=>"text",
    														"label"		=>_t("LINK_NAME"),
    														"name"		=>"name",
    														"width"		=>"300px",
                                                            "value"     =>Io::Output($row['name']),
    														"id"		=>"urlvalidname",
    														"suffix"	=>"<input type='button' id='autoname' value='"._t("AUTO")."' class='sys_form_button' />",
    														"info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));    			
        							
                                    //URL
        							$form->AddElement(array("element"	=>"text",
        													"label"		=>_t("URL"),
        													"width"		=>"300px",
        													"name"		=>"url",
                                                            "value"		=>Io::Output($row['content']),
                                                            "info"		=>"http://...",
        													"id"		=>"url"));
        			
                        	echo "</div>";
                        echo "</div>";          
        		    echo "</td>";
            		echo "<td class='sidebar'>";
            		echo "<div class='widget ui-widget-content ui-corner-all'>";
	            		echo "<div class='ui-widget-header'>"._t("OPTIONS")."</div>";
		            		echo "<div class='body'>";
		            		
		            		//Status
                                    $form->AddElement(array("element"	=>"select",
                                                            "label"		=>_t("STATUS"),
                                                            "name"		=>"status",
                                                            "values"	=>array(_t("ACTIVE")    => "active",
                                                                                _t("INACTIVE") 	=> "off"),
                                                            "selected"  =>Io::Output($row['status'])));
		            		
		            		echo "</div>";
	            		echo "</div>";
                        echo "<div class='widget ui-widget-content ui-corner-all'>";
                            echo "<div class='ui-widget-header'>"._t("AUTHORIZATION_MANAGER")."</div>";
                            echo "<div class='body'>";
                                    
								$roles = Utils::Unserialize(Io::Output($row['roles']));                            
                            
                                //Required roles
            					$result = $Db->GetList("SELECT title,label FROM #__rba_roles ORDER BY rid");
            					$rba = array();
            					$rba[_t("EVERYONE")] = "ALL";
            					if (!sizeof($roles) || empty($roles)) $roles = array('ALL');
                                foreach ($result as $roww) $rba[Io::Output($roww['title'])] = Io::Output($roww['label']);
            					$form->AddElement(array("element"	=>"select",
            											"label"		=>_t("WHO_ACCESS_THE_X",MB::strtolower("PAGE")),
            											"name"		=>"roles[]",
            											"multiple"	=>true,
            											"values"	=>$rba,
            											"selected"	=>$roles,
            											"info"		=>_t("MULTIPLE_CHOICES_ALLOWED")));  
                                 //Install
            					$form->AddElement(array("element"	=>"submit",
            											"name"		=>"edit",
            											"inline"	=>true,
            											"value"		=>_t("EDIT")));
            
                                $form->Close();                                                        
                                                                                      
                            echo "</div>";
                        echo "</div>";
                                } else {
                        			//Check token
                        			if (Utils::CheckToken()) {
                        			    //Get POST data
                        				$title = Io::GetVar('POST','title','nohtml');
                                        $url = Io::GetVar('POST','url');
                        	            $status = Io::GetVar('POST','status','nohtml');
                                        $roles = Io::GetVar('POST','roles','nohtml',true,array());
                                        $name = Io::GetVar('POST','name','[^a-zA-Z0-9\-]'); 							
    								                                  
                        			    $errors = array();
                                        
                        				if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
                        				if (empty($url)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("URL"));
                        			    if (!Utils::ValidUrl($url)) $errors[] = _t("THE_FIELD_X_IS_NOT_INVALID",_t("URL"));
                                        if (empty($name)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LINK_NAME"));
                                        if (in_array("ALL",$roles)) $roles = array();
        								$roles = Utils::Serialize($roles);
                                                                    
                        				if (!sizeof($errors)) {                    	                
                                            $Db->Query("UPDATE #__content SET title='".$Db->_e($title)."',name='".$Db->_e($name)."',content='".$Db->_e($url)."',roles='".$Db->_e($roles)."',status='".$Db->_e($status)."' WHERE id=".intval($id));         	                             	
                        					Utils::Redirect("admin.php?cont="._PLUGIN."&op=redirects");
                        				} else {
                        					MemErr::Trigger("USERERROR",implode("<br />",$errors));
                        				}
                        			} else {
                        				MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
                        			}
                                }
    					} else {
    						MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("REDIRECTION")));
    					}                                                        
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
        
        function DeleteRedirects() {
    	    global $Db;
    			
    		$items = Io::GetVar("POST","items",false,true);
    			
    		$result = $Db->Query("DELETE FROM #__content WHERE type='REDIRECT' AND id IN (".$Db->_e($items).")") ? 1 : 0 ;
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