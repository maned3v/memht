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

class blogModel {
	function Main() {
		global $Db,$Router,$config_sys;
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		?>
        
        <script type="text/javascript" charset="utf-8">
			function PostSwitch(id) {
				$.ajax({
					type: "POST",
					dataType: "xml",
					url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=switchpost",
					data: "id="+id,
					success: function(data){
						var text = data.result;
						$('.txt_'+id).html(text);
					}
				});
			}
			$(document).ready(function() {			
				//Send to trash
				$('input#trash').click(function() {
					var obj = $('.cb:checkbox:checked');
					if (obj.length>0) {
						var items = new Array();
						for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
						$.ajax({
							type: "POST",
							dataType: "xml",
							url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=trashcan&sub=trash",
							data: "items="+items,
							success: function(data){
								location = 'admin.php?cont=<?php echo _PLUGIN; ?>';
							}
						});
					} else {
						alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("POST"))); ?>');
					}
				});
				
				//Restore
				$('input#restore').click(function() {
					var obj = $('.cb:checkbox:checked');
					if (obj.length>0) {
						var items = new Array();
						for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
						$.ajax({
							type: "POST",
							dataType: "xml",
							url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=trashcan&sub=restore",
							data: "items="+items,
							success: function(data){
								location = 'admin.php?cont=<?php echo _PLUGIN; ?>&status=deleted';
							}
						});
					} else {
						alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("POST"))); ?>');
					}
				});
				
				//Delete permanently
				$('input#delete').click(function() {
					var obj = $('.cb:checkbox:checked');
					if (obj.length>0) {
						if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("POSTS"))); ?>')) {
							var items = new Array();
							for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
							$.ajax({
								type: "POST",
								dataType: "xml",
								url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=trashcan&sub=delete",
								data: "items="+items,
								success: function(data){
									location = 'admin.php?cont=<?php echo _PLUGIN; ?>&status=deleted';
								}
							});
						}
					} else {
						alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("POST"))); ?>');
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
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpost' title='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;status=deleted' title='"._t("TRASH_CAN")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."trash.png' alt='"._t("TRASH_CAN")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=categories' title='"._t("CATEGORIES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."category.png' alt='"._t("CATEGORIES")."' /></a>\n";
			echo "<a href='admin.php?cont=plugins&amp;op=options&amp;controller="._PLUGIN."' title='"._t("PLUGIN_OPTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."configuration.png' alt='"._t("PLUGIN_OPTIONS")."' /></a>\n";
		?>
        </div>
        
        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("MANAGE_BLOG_POSTS"); ?></div>
                        <div class="body">
                        	<?php
							//Options
							$plcom = $Router->GetOption("comments",1);
							$glcom = ($plcom==1 && $config_sys['comments']==1) ? true : false ;
							
							$sortby = $Db->_e(Io::GetVar("GET","sortby",false,true,"id"));
							$order = $Db->_e(Io::GetVar("GET","order",false,true,"DESC"));
							$limit = Io::GetVar("GET","limit","int",false,10);
							
							//Pagination
							$page = Io::GetVar("GET","page","int",false,1);
							if ($page<=0) $page = 1;
							$from = ($page * $limit) - $limit;
							
							$status = Io::GetVar("GET","status");
							$where = "WHERE ";
							$where .= ($status) ? "p.status='".$Db->_e($status)."'" : "p.status!='deleted'" ;
							$where .= " AND p.status!='revision'";
							
							//Category
							$category = Io::GetVar("GET","category","int");
							if (!empty($category)) $where .= " AND p.category='$category'";
							
							//Author
							$aid = Io::GetVar("GET","author","int");
							if (!empty($aid)) $where .= " AND p.author='$aid'";
							
							//Language
							$language = Io::GetVar("GET","language");
							if (!empty($language)) $where .= " AND p.language='".$Db->_e($language)."'";
							
							//Search
							$squery = Io::GetVar("POST","query");
							$swhere = Io::GetVar("POST","where");
							if (!empty($squery)) $where .= " AND ".$Db->_e($swhere)." LIKE '%".$Db->_e($squery)."%'" ;
							
							echo "<div style='float:left; margin:6px 0 2px 0;'>\n";
								$form = new Form();
								$form->action = "admin.php?cont="._PLUGIN;
								$form->token = false;
								$form->inline = true;
								
								$form->Open();
								
								//Where
								$form->AddElement(array("element"	=>"select",
														"name"		=>"where",
														"values"	=>array(_t("TITLE")		=> "p.title",
																			_t("CATEGORY")	=> "c.title",
																			_t("AUTHOR")	=> "u.name",
																			_t("LANGUAGE")	=> "l.title",
																			_t("STATUS")	=> "p.status"),
														"width"		=>"150px"));
								
								//Query
								$form->AddElement(array("element"	=>"text",
														"name"		=>"query",
														"width"		=>"150px"));
														
								//Submit
								$form->AddElement(array("element"	=>"submit",
														"name"		=>"submit",
														"value"		=>_t("SEARCH")));
								$form->Close();
							echo "</div>\n";
							
							echo "<form action='' method='post' id='validate'>\n";
							echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
								if ($status=="deleted") {
									//Delete permanently
									echo "<input type='button' name='delete' value='"._t("DELETE_PERMANENTLY")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
									//Restore
									echo "<input type='button' name='restore' value='"._t("RESTORE")."' style='margin:2px 0;' class='sys_form_button' id='restore' />\n";
								} else {
									//Send to trash
									echo "<input type='button' name='trash' value='"._t("SEND_TO_TRASH")."' style='margin:2px 0;' class='sys_form_button' id='trash' />\n";
								}
							echo "</div>\n";
							
							echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
								echo "<thead>\n";
									echo "<tr>\n";
										echo "<th width='1%' style='text-align:right;'><input type='checkbox' id='selectall' /></th>\n";
										echo "<th width='9%'>"._t("CREATED")."</th>\n";
										echo "<th width='32%'>"._t("TITLE")."</th>\n";
										echo "<th width='15%'>"._t("CATEGORY")."</th>\n";
										echo "<th width='14%'>"._t("AUTHOR")."</th>\n";
										echo "<th width='8%'>"._t("LANGUAGE")."</th>\n";
										echo "<th width='2%' style='text-align:center;'>&nbsp;</th>\n";
										echo "<th width='4%' style='text-align:center;'>&nbsp;</th>\n";
										echo "<th width='15%'>"._t("STATUS")."</th>\n";
									echo "</tr>\n";
								echo "</thead>\n";
								echo "<tbody>\n";
								
								if ($result = $Db->GetList("SELECT p.*,c.name AS cname, c.title AS ctitle, u.name AS author_name, l.title AS langtitle,
										(SELECT ROUND(SUM(rate)/COUNT(id)) AS rating FROM #__ratings WHERE controller='".$Db->_e(_PLUGIN)."' AND item=p.id) AS rating
										FROM #__blog_posts AS p FORCE INDEX(created) JOIN #__blog_categories AS c JOIN #__user AS u JOIN #__languages AS l
										ON p.category=c.id AND p.author=u.uid AND p.language=l.file
										{$where}
										ORDER BY p.{$sortby} $order
										LIMIT ".intval($from).",".intval($limit))) {
									
										$preroles = Ram::Get("roles");
										$preroles['ALL']['name'] = _t("EVERYONE");

										//Controller-name match
										$plugmatch = Ram::Get("plugmatch");
										$plugname = isset($plugmatch[_PLUGIN]) ? $plugmatch[_PLUGIN] : _PLUGIN ;
										
										foreach ($result as $row) {
											$id			= Io::Output($row['id'],"int");
											$category	= Io::Output($row['category'],"int");
											$cname		= Io::Output($row['cname']);
											$ctitle		= Io::Output($row['ctitle']);
											$title		= Io::Output($row['title']);
											$name		= Io::Output($row['name']);
											$aid		= Io::Output($row['author'],"int");
											$author		= Io::Output($row['author_name']);
											$text		= Io::Output($row['text']);
											$langstr	= Io::Output($row['language']);
											$language	= Io::Output($row['langtitle']);
											$created	= Time::Output(Io::Output($row['created']));
											$modified_o = Io::Output($row['modified']);
											$modified	= Time::Output($modified_o);
											$start		= Io::Output($row['start']);
											$end		= Io::Output($row['end']);
											$options	= Utils::Unserialize(Io::Output($row['options']));
											$roles		= Utils::Unserialize(Io::Output($row['roles']));
											$usecomments= ($glcom && Io::Output($row['usecomments'],"int")==1) ? true : false ;
											$comments	= Io::Output($row['comments'],"int");
											$hits		= Io::Output($row['hits'],"int");
											$revisions	= Io::Output($row['revisions'],"int");
											$rating		= Io::Output($row['rating'],"int");
											$status		= MB::ucfirst(Io::Output($row['status']));
											if (empty($rating)) $rating = 0;
											if (!sizeof($roles)|| empty($roles)) $roles = array('ALL');
																						
											//Tags
											$tags = array();
											$dbt = $Db->GetList("SELECT title FROM #__tags WHERE controller='".$Db->_e(_PLUGIN_CONTROLLER)."' AND item='".intval($id)."'");
											foreach ($dbt as $t) $tags[] = Io::Output($t['title']);
											if (!sizeof($tags)) $tags[] = _t("NO_TAGS");
											$tags = implode(", ",$tags);
												
											echo "<tr onmouseover='javascript:showmenu($id);' onmouseout='javascript:showmenu($id);'>\n";
												echo "<td><input type='checkbox' name='selected[]' value='$id' class='cb' /><br />&nbsp;</td>\n";
                                				echo "<td>$created</td>\n";
												echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=editpost&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("POST")))."'><strong>$title</strong></a>\n";
													echo "<div id='menu_$id' style='display:none; margin-top:2px;'>\n";
														if ($status=="Published") {
															echo "<a href='index.php?"._NODE."=$plugname&amp;cat=$cname&amp;title=$name' title='"._t("VIEW_THIS_X",MB::strtolower(_t("POST")))."' rel='external'>"._t("VIEW")."</a> - \n";
														}
														echo "<a href='admin.php?cont="._PLUGIN."&amp;op=editpost&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("POST")))."'>"._t("EDIT")."</a>\n";
														if ($revisions) echo " - <a href='admin.php?cont="._PLUGIN."&amp;op=revisions&amp;id=$id' title='"._t("PREVIOUS_VERSIONS")."'>"._t("PREVIOUS_VERSIONS")."</a>\n";
														$ronames = array();
														foreach ($roles as $role) if (isset($preroles[$role]['name'])) $ronames[] = $preroles[$role]['name'];
														$roles = _t("WHO_ACCESS_THE_X",MB::strtolower("POST")).": ".implode(", ",$ronames);
														echo " - <a title='$roles'>"._t("ROLES")."</a>\n";
													echo "</div>\n";
												echo "</td>\n";
												echo "<td><a href='admin.php?cont="._PLUGIN."&amp;category=$category' title='"._t("SHOW_X_IN_Y",MB::strtolower(_t("POSTS")),$ctitle)."'>$ctitle</a></td>\n";
												echo "<td><a href='admin.php?cont="._PLUGIN."&amp;author=$aid' title='"._t("SHOW_X_CREATED_BY_Y",MB::strtolower(_t("POSTS")),$author)."'>$author</a></td>\n";
												echo "<td><a href='admin.php?cont="._PLUGIN."&amp;language=".MB::strtolower($langstr)."' title='"._t("SHOW_X_IN_Y",MB::strtolower(_t("POSTS")),$language)."'>$language</a></td>\n";
												echo "<td style='text-align:center;' class='tags'><span title='".CleanTitleAtr($tags)."'>&nbsp;</span></td>\n";												
												echo "<td style='text-align:center;' class='comments'><span><a href='admin.php?cont="._PLUGIN."&amp;op=comments&amp;id=$id' title='"._t("X_COMMENTS",$comments)."'>$comments</a></span></td>\n";
												echo "<td class='switch'>$status\n";
													echo "<div id='status_$id' style='display:none; margin-top:2px;'>\n";
														if ($status=="Deleted") {
															echo "<a href='admin.php?cont="._PLUGIN."&amp;op=restore&amp;id=$id' title='"._t("RESTORE")."'>"._t("RESTORE")."</a>\n";
														} else {
															echo "<a href='admin.php?cont="._PLUGIN."&amp;op=sendtotrash&amp;id=$id' title='"._t("SEND_TO_TRASH")."'>"._t("SEND_TO_TRASH")."</a>\n";
														}
													echo "</div>\n";
												echo "</td>\n";
                                			echo "</tr>\n";
										}
									} else {
										echo "<tr>\n";
											echo "<td colspan='9' style='text-align:center;'>"._t("LIST_EMPTY")."</td>\n";
                                		echo "</tr>\n";
									}
								?>
                                </tbody>
                            </table>
                            </form>
                            <?php
								include_once(_PATH_ACP_LIBRARIES._DS."MemHT"._DS."content"._DS."pagination.class.php");
								$Pag = new Pagination();
								$Pag->page = $page;
								$Pag->limit = $limit;
								$Pag->query = "SELECT COUNT(p.id) AS tot
											   FROM #__blog_posts AS p JOIN #__blog_categories AS c JOIN #__user AS u JOIN #__languages AS l
											   ON p.category=c.id AND p.author=u.uid AND p.language=l.file
											   {$where}";
								if ($status=="Deleted") {
									$Pag->url = "admin.php?cont="._PLUGIN."&amp;status=deleted&amp;page={PAGE}";
								} else {
									$Pag->url = "admin.php?cont="._PLUGIN."&amp;page={PAGE}";
								}
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

	function CreateBlogPost() {
		global $Db,$User,$config_sys;

		//Initialize and show site header
		Layout::Header(array("editor"=>true));
		//Start buffering content
		Utils::StartBuffering();

		?>

        <script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>alphanumeric<?php echo _DS; ?>jquery.alphanumeric.js"></script>
		<script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>cloudytag<?php echo _DS; ?>jquery.cloudytag.js"></script>
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
				$('#tags').cloudyTag();
			});
        </script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpost' title='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;status=deleted' title='"._t("TRASH_CAN")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."trash.png' alt='"._t("TRASH_CAN")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=categories' title='"._t("CATEGORIES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."category.png' alt='"._t("CATEGORIES")."' /></a>\n";
			echo "<a href='admin.php?cont=plugins&amp;op=options&amp;controller="._PLUGIN."' title='"._t("PLUGIN_OPTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."configuration.png' alt='"._t("PLUGIN_OPTIONS")."' /></a>\n";
		?>
        </div>
        <?php
		echo "<div style='clear:both;'>\n";

		switch ((isset($_POST['create'])) ? "create" : ((isset($_POST['draft'])) ? "draft" : "")) {
			default:
				$form = new Form();
				$form->action = "admin.php?cont="._PLUGIN."&amp;op=createpost";
				$form->Open();

				?>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
				<tr>
					<td style="vertical-align:top;">
						<div class="widget ui-widget-content ui-corner-all">
							<div class="ui-widget-header"><?php echo _t("CREATE_NEW_X",MB::strtolower(_t("POST"))); ?></div>
							<div class="body">
								<?php

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

								//Category
								$result = $Db->GetList("SELECT id,title FROM #__blog_categories ORDER BY title");
								foreach ($result as $row) $select[Io::Output($row['title'])] = intval($row['id']);
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("CATEGORY"),
														"name"		=>"category",
														"values"	=>@$select));


								//Text
								$form->AddElement(array("element"	=>"textarea",
														"label"		=>_t("TEXT"),
														"name"		=>"text",
														"height"	=>"400px",
														"class"		=>"advanced"));

								?>
							</div>
						</div>
						<?php
						//Sticker
						$xmlobj = Utils::GetXmlFile("templates"._DS.$config_sys['default_template']._DS."info.xml");
						$sticker = array();
						if (isset($xmlobj->content->blog)) foreach ($xmlobj->content->blog->field as $attr) $sticker[(string)$attr->label] = (string)$attr->description;
						if (sizeof($sticker)) {
						?>
						<div class="widget ui-widget-content ui-corner-all">
							<div class="ui-widget-header"><?php echo _t("TEMPLATE_STICKERS"); ?></div>
							<div class="body">
								<?php

								foreach ($sticker as $key => $value) {
									$form->FieldsetStart("$".$key);
									if (!empty($value)) MemErr::Trigger("MINFO",$value);
										//Value
										$form->AddElement(array("element"	=>"textarea",
																"label"		=>_t("CONTENT"),
																"name"		=>"sticker_".$key,
																"width"		=>"600px",
																"height"	=>"100px",
																"class"		=>"sys_form_textarea"));
									$form->FieldsetEnd();
								}
								?>
							</div>
						</div>
						<?php
						}
						?>
					</td>
					<td class="sidebar">
						<div class="widget ui-widget-content ui-corner-all">
							<div class="ui-widget-header"><?php echo _t("OPTIONS"); ?></div>
							<div class="body">
								<?php

								//Language
								$result = $Db->GetList("SELECT title,file FROM #__languages ORDER BY title");
								$lang = array();
								foreach ($result as $row) $lang[Io::Output($row['title'])] = Io::Output($row['file']);
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("LANGUAGE"),
														"name"		=>"language",
														"values"	=>$lang));

								//Comments
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("COMMENTS"),
														"name"		=>"usecomments",
														"values"	=>array(_t("ENABLED") => 1,
																			_t("DISABLED") => 0)));

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

								//Tags
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("TAGS"),
														"id"		=>"tags",
														"width"		=>"110px",
														"class"		=>"sys_form_text",
														"suffix"	=>"<input type='button' id='addtag' value='"._t("ADD")."' class='sys_form_button' />"));

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
														"name"		=>"meta_desc",
														"width"		=>"90%",
														"height"	=>"50px",
														"class"		=>"sys_form_textarea"));

								//Keywords
								$form->AddElement(array("element"	=>"textarea",
														"label"		=>_t("KEYWORDS"),
														"name"		=>"meta_key",
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
														"label"		=>_t("WHO_ACCESS_THE_X",MB::strtolower("ARTICLE")),
														"name"		=>"roles[]",
														"multiple"	=>true,
														"values"	=>$rba,
														"selected"	=>"ALL",
														"info"		=>_t("MULTIPLE_CHOICES_ALLOWED")));

								?>
							</div>
						</div>
						<div>
							<?php

							//Create
							$form->AddElement(array("element"	=>"submit",
													"name"		=>"create",
													"inline"	=>true,
													"value"		=>_t("CREATE")));

							//Save as draft
							$form->AddElement(array("element"	=>"submit",
													"name"		=>"draft",
													"inline"	=>true,
													"value"		=>_t("SAVE_AS_DRAFT")));

							?>
						</div>
					</td>
				</tr>
				</table>
				<?php

				$form->Close();
				break;
			case "draft":
				$status = "draft";
			case "create":
				//Check token
				if (Utils::CheckToken()) {
					if (!isset($status)) $status = "published";

					//Get POST data
					$title = Io::GetVar('POST','title','fullhtml');
					$name = Io::GetVar('POST','name','[^a-zA-Z0-9\-]');
					$category = Io::GetVar('POST','category','int');
					$text = Io::GetVar('POST','text','fullhtml',false);
					$language = Io::GetVar('POST','language','nohtml');
					$start = Io::GetVar('POST','start',false,true,'2001-01-01 00:00:00');
					$end = Io::GetVar('POST','end',false,true,'2199-01-01 00:00:00');
					$usecomments = Io::GetVar('POST','usecomments','int');
					$meta_desc = Io::GetVar('POST','meta_desc','nohtml',true);
					$meta_key = Io::GetVar('POST','meta_key','nohtml',true);
					$roles = Io::GetVar('POST','roles','nohtml',true,array());
					$tags = Io::GetVar('POST','tags','nohtml',true,array());

					$errors = array();
					if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
					if (empty($name)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LINK_NAME"));
					if (empty($category)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("CATEGORY"));
					if (empty($text)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TEXT"));

					if (!sizeof($errors)) {
						$options = array();

						$xmlobj = Utils::GetXmlFile("templates"._DS.$config_sys['default_template']._DS."info.xml");
						$sticker = array();
						if (isset($xmlobj->content->blog)) foreach ($xmlobj->content->blog->field as $attr) $sticker[(string)$attr->label] = (string)$attr->description;
						foreach ($sticker as $key => $value) {
							$$key = Io::GetVar('POST','sticker_'.$key,'fullhtml',false);
							if (!empty($$key)) $options['stickers'][$key] = $$key;
						}
						if (!empty($meta_desc)) $options['meta']['desc'] = $meta_desc;
						if (!empty($meta_key)) $options['meta']['key'] = $meta_key;
						$options = Utils::Serialize($options);

						if (in_array("ALL",$roles)) $roles = array();
						$roles = Utils::Serialize($roles);

						$Db->Query("INSERT INTO #__blog_posts (category,title,name,author,text,language,created,modified,start,end,options,usecomments,status,roles)
									VALUES ('".intval($category)."','".$Db->_e($title)."','".$Db->_e($name)."','".intval($User->Uid())."','".$Db->_e($text)."','".$Db->_e($language)."',NOW(),NOW(),
											'".$Db->_e($start)."','".$Db->_e($end)."','".$Db->_e($options)."','".intval($usecomments)."','".$Db->_e($status)."','".$Db->_e($roles)."')");

						//Tags
						if (sizeof($tags)) {
							$id = intval($Db->InsertId());
							$query = array();
							foreach ($tags as $tag) $query[] = "('".$Db->_e(_PLUGIN)."','".$id."','".$Db->_e($tag)."','".$Db->_e(Utils::CleanString($tag))."')";
							if (sizeof($query)) $Db->Query("INSERT INTO #__tags (controller,item,title,name) VALUES ".implode(",",$query));
						}

						Utils::Redirect("admin.php?cont="._PLUGIN);
					} else {
						?>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
						<tr>
							<td style="vertical-align:top;">
								<div class="widget ui-widget-content ui-corner-all">
									<div class="ui-widget-header"><?php echo _t("CREATE_NEW_X",MB::strtolower(_t("POST"))); ?></div>
									<div class="body">
									<?php
									MemErr::Trigger("USERERROR",implode("<br />",$errors));
									?>
									</div>
								</div>
							</td>
						</tr>
						</table>
						<?php
					}
				} else {
					?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
					<tr>
						<td style="vertical-align:top;">
							<div class="widget ui-widget-content ui-corner-all">
								<div class="ui-widget-header"><?php echo _t("CREATE_NEW_X",MB::strtolower(_t("POST"))); ?></div>
								<div class="body">
								<?php
								MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
								?>
								</div>
							</div>
						</td>
					</tr>
					</table>
					<?php
				}
				break;
		}
		echo "</div>\n";

		//Assign captured content to the template engine and clean buffer
		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
		//Draw site template
		Template::Draw();
		//Initialize and show site footer
		Layout::Footer();
	}

	function EditBlogPost() {
		global $Db,$User,$config_sys;

		//Initialize and show site header
		Layout::Header(array("editor"=>true));
		//Start buffering content
		Utils::StartBuffering();

		?>

        <script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>alphanumeric<?php echo _DS; ?>jquery.alphanumeric.js"></script>
		<script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>cloudytag<?php echo _DS; ?>jquery.cloudytag.js"></script>
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
				$('#tags').cloudyTag();
			});
        </script>

        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpost' title='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;status=deleted' title='"._t("TRASH_CAN")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."trash.png' alt='"._t("TRASH_CAN")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=categories' title='"._t("CATEGORIES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."category.png' alt='"._t("CATEGORIES")."' /></a>\n";
			echo "<a href='admin.php?cont=plugins&amp;op=options&amp;controller="._PLUGIN."' title='"._t("PLUGIN_OPTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."configuration.png' alt='"._t("PLUGIN_OPTIONS")."' /></a>\n";
		?>
        </div>
        <?php
		echo "<div style='clear:both;'>\n";

		$id = Io::GetVar('GET','id','int');
		if ($row = $Db->GetRow("SELECT * FROM #__blog_posts WHERE id=".intval($id))) {
			if (!isset($_POST['save'])) {
				//Get values from db
				$category 	= Io::Output($row['category'],"int");
				$title 		= Io::Output($row['title']);
				$name 		= Io::Output($row['name']);
				$author 	= Io::Output($row['author'],"int");
				$text		= Io::Output($row['text']);
				$language 	= Io::Output($row['language']);
				$created	= Io::Output($row['created']);
				$start 		= Io::Output($row['start']);
				$end 		= Io::Output($row['end']);
				$options 	= Utils::Unserialize(Io::Output($row['options']));
				$usecomments= Io::Output($row['usecomments'],"int");
				$status 	= Io::Output($row['status']);
				$roles		= Utils::Unserialize(Io::Output($row['roles']));

				//Tags
				$dbtags = $Db->GetList("SELECT title FROM #__tags WHERE controller='".$Db->_e(_PLUGIN)."' AND item=".intval($id));
				$tags = array();
				foreach ($dbtags as $key => $value) $tags[] = $value['title'];
				$tags = implode(",",$tags);

				$form = new Form();
				$form->action = "admin.php?cont="._PLUGIN."&amp;op=editpost&amp;id=$id";
				$form->Open();

				?>

				<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
				<tr>
					<td style="vertical-align:top;">
						<div class="widget ui-widget-content ui-corner-all">
							<div class="ui-widget-header"><?php echo _t("EDIT"); ?></div>
							<div class="body">
								<?php

								//Title
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("TITLE"),
														"width"		=>"300px",
														"name"		=>"title",
														"id"		=>"title",
														"value"		=>$title));

								//Name
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("LINK_NAME"),
														"name"		=>"name",
														"value"		=>$name,
														"width"		=>"300px",
														"id"		=>"urlvalidname",
														"suffix"	=>"<input type='button' id='autoname' value='"._t("AUTO")."' class='sys_form_button' />",
														"info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY")));

								//Category
								$result = $Db->GetList("SELECT id,title FROM #__blog_categories ORDER BY title");
								foreach ($result as $row) $select[Io::Output($row['title'])] = intval($row['id']);
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("CATEGORY"),
														"name"		=>"category",
														"selected"	=>$category,
														"values"	=>$select));

								//Text
								$form->AddElement(array("element"	=>"textarea",
														"label"		=>_t("TEXT"),
														"name"		=>"text",
														"value"		=>$text,
														"height"	=>"400px",
														"class"		=>"advanced"));

								?>
							</div>
						</div>
						<?php
						//Sticker
						$xmlobj = Utils::GetXmlFile("templates"._DS.$config_sys['default_template']._DS."info.xml");
						$sticker = array();
						if (isset($xmlobj->content->blog)) foreach ($xmlobj->content->blog->field as $attr) $sticker[(string)$attr->label] = (string)$attr->description;
						if (sizeof($sticker)) {
						?>
                        <div class="widget ui-widget-content ui-corner-all">
						<div class="ui-widget-header"><?php echo _t("TEMPLATE_STICKERS"); ?></div>
							<div class="body">
								<?php

								foreach ($sticker as $key => $value) {
									$form->FieldsetStart("$".$key);
									if (!empty($value)) MemErr::Trigger("MINFO",$value);
										//Value
										$form->AddElement(array("element"	=>"textarea",
																"label"		=>_t("CONTENT"),
																"name"		=>"sticker_".$key,
																"value"		=>@$options['stickers'][$key],
																"width"		=>"600px",
																"height"	=>"100px",
																"class"		=>"sys_form_textarea"));
									$form->FieldsetEnd();
								}

								?>
							</div>
						</div>
                        <?php
						}
						?>
					</td>
					<td class="sidebar">
						<div class="widget ui-widget-content ui-corner-all">
						<div class="ui-widget-header"><?php echo _t("OPTIONS"); ?></div>
							<div class="body">
								<?php

								//Language
								$result = $Db->GetList("SELECT title,file FROM #__languages ORDER BY title");
								$lang = array();
								foreach ($result as $row) $lang[Io::Output($row['title'])] = Io::Output($row['file']);
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("LANGUAGE"),
														"name"		=>"language",
														"selected"	=>$language,
														"values"	=>$lang));

								//Comments
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("COMMENTS"),
														"name"		=>"usecomments",
														"selected"	=>$usecomments,
														"values"	=>array(_t("ENABLED") => 1,
																			_t("DISABLED") => 0)));

								//Start
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("START"),
														"name"		=>"start",
														"value"		=>$start,
														"class"		=>"sys_form_text datepicker",
														"width"		=>"150px",
														"suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."calendar.png' alt='Start' />"));

								//End
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("END"),
														"name"		=>"end",
														"value"		=>$end,
														"class"		=>"sys_form_text datepicker",
														"width"		=>"150px",
														"suffix"	=>"<img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."images"._DS."calendar.png' alt='End' />"));

								//Tags
								$form->AddElement(array("element"	=>"text",
														"label"		=>_t("TAGS"),
														"id"		=>"tags",
														"width"		=>"110px",
														"class"		=>"sys_form_text",
														"value"		=>$tags,
														"suffix"	=>"<input type='button' id='addtag' value='"._t("ADD")."' class='sys_form_button' />"));

								//Status
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("STATUS"),
														"name"		=>"status",
														"selected"	=>$status,
														"values"	=>array(_t("PUBLISHED") => "published",
																			_t("DRAFT") 	=> "draft",
																			_t("INACTIVE") 	=> "inactive",
																			_t("DELETED") 	=> "deleted")));

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
														"name"		=>"meta_desc",
														"value"		=>@$options['meta']['desc'],
														"width"		=>"90%",
														"height"	=>"50px",
														"class"		=>"sys_form_textarea"));

								//Keywords
								$form->AddElement(array("element"	=>"textarea",
														"label"		=>_t("KEYWORDS"),
														"name"		=>"meta_key",
														"value"		=>@$options['meta']['key'],
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
								if (!sizeof($roles)|| empty($roles)) $roles = array('ALL');
								foreach ($result as $row) $rba[Io::Output($row['title'])] = Io::Output($row['label']);
								$form->AddElement(array("element"	=>"select",
														"label"		=>_t("WHO_ACCESS_THE_X",MB::strtolower("ARTICLE")),
														"name"		=>"roles[]",
														"multiple"	=>true,
														"values"	=>$rba,
														"selected"	=>$roles,
														"info"		=>_t("MULTIPLE_CHOICES_ALLOWED")));

								?>
							</div>
						</div>
						<div>
							<?php

							//Save
							$form->AddElement(array("element"	=>"submit",
													"name"		=>"save",
													"inline"	=>true,
													"value"		=>_t("SAVE")));

							?>
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
					$title = Io::GetVar('POST','title','fullhtml');
					$name = Io::GetVar('POST','name','[^a-zA-Z0-9\-]');
					$category = Io::GetVar('POST','category','int');
					$text = Io::GetVar('POST','text','fullhtml',false);
					$language = Io::GetVar('POST','language','nohtml');
					$start = Io::GetVar('POST','start',false,true,'2001-01-01 00:00:00');
					$end = Io::GetVar('POST','end',false,true,'2199-01-01 00:00:00');
					$usecomments = Io::GetVar('POST','usecomments','int');
					$meta_desc = Io::GetVar('POST','meta_desc','nohtml',true);
					$meta_key = Io::GetVar('POST','meta_key','nohtml',true);
					$status = Io::GetVar('POST','status','nohtml');
					$roles = Io::GetVar('POST','roles','nohtml',true,array());
					$tags = Io::GetVar('POST','tags','nohtml',true,array());
					$inhome = Io::GetVar('POST','inhome','int');

					$errors = array();
					if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
					if (empty($name)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LINK_NAME"));
					if (empty($category)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("CATEGORY"));
					if (empty($text)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TEXT"));

					if (!sizeof($errors)) {
						$options = array();

						$xmlobj = Utils::GetXmlFile("templates"._DS.$config_sys['default_template']._DS."info.xml");
						$sticker = array();
						if (isset($xmlobj->content->blog)) foreach ($xmlobj->content->blog->field as $attr) $sticker[(string)$attr->label] = (string)$attr->description;
						foreach ($sticker as $key => $value) {
							$$key = Io::GetVar('POST','sticker_'.$key,'fullhtml',false);
							if (!empty($$key)) $options['stickers'][$key] = $$key;
						}
						if (!empty($meta_desc)) $options['meta']['desc'] = $meta_desc;
						if (!empty($meta_key)) $options['meta']['key'] = $meta_key;
						$options = Utils::Serialize($options);

						if (in_array("ALL",$roles)) $roles = array();
						$roles = Utils::Serialize($roles);

						//Create a new revision entry
						$Db->Query("INSERT INTO #__blog_posts_rev (category,title,name,text,language,prevmod,start,end,options,usecomments,postid,roles,prev)
									(SELECT category,title,name,text,language,modified,start,end,options,usecomments,id,roles,status FROM #__blog_posts WHERE id=".intval($id).")");

						//Update original post
						$Db->Query("UPDATE #__blog_posts SET category='".intval($category)."',title='".$Db->_e($title)."',name='".$Db->_e($name)."',text='".$Db->_e($text)."',
									language='".$Db->_e($language)."',modified=NOW(),start='".$Db->_e($start)."',end='".$Db->_e($end)."',options='".$Db->_e($options)."',
									usecomments='".intval($usecomments)."',revisions=revisions+1,status='".$Db->_e($status)."',roles='".$Db->_e($roles)."' WHERE id=".intval($id));

						//Tags
						if (sizeof($tags)) {
							$Db->Query("DELETE FROM #__tags WHERE controller='".$Db->_e(_PLUGIN)."' AND item='".intval($id)."'");
							$query = array();
							foreach ($tags as $tag) $query[] = "('".$Db->_e(_PLUGIN)."','".$id."','".$Db->_e($tag)."','".$Db->_e(Utils::CleanString($tag))."')";
							if (sizeof($query)) $Db->Query("INSERT INTO #__tags (controller,item,title,name) VALUES ".implode(",",$query));
						}

						Utils::Redirect("admin.php?cont="._PLUGIN);
					} else {
						?>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
						<tr>
							<td style="vertical-align:top;">
								<div class="widget ui-widget-content ui-corner-all">
									<div class="ui-widget-header"><?php echo _t("EDIT"); ?></div>
									<div class="body">
									<?php
									MemErr::Trigger("USERERROR",implode("<br />",$errors));
									?>
									</div>
								</div>
							</td>
							</tr>
						</table>
						<?php
					}
				} else {
					?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
					<tr>
						<td style="vertical-align:top;">
							<div class="widget ui-widget-content ui-corner-all">
								<div class="ui-widget-header"><?php echo _t("EDIT"); ?></div>
								<div class="body">
								<?php
								MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
								?>
								</div>
							</div>
						</td>
					</tr>
					</table>
					<?php
				}
			}
		} else {
			?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
			<tr>
				<td style="vertical-align:top;">
					<div class="widget ui-widget-content ui-corner-all">
						<div class="ui-widget-header"><?php echo _t("EDIT"); ?></div>
						<div class="body">
						<?php
						MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("POST")));
						?>
						</div>
					</div>
				</td>
			</tr>
			</table>
			<?php
		}

		echo "</div>\n";
		//Assign captured content to the template engine and clean buffer
		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
		//Draw site template
		Template::Draw();
		//Initialize and show site footer
		Layout::Footer();
	}
	
	function TrashBlogData() {
		global $Db;

		$items = Io::GetVar("POST","items",false,true);
		$sub = Io::GetVar("GET","sub");
		switch ($sub) {
			case "delete":
				$result = $Db->Query("DELETE FROM #__blog_posts WHERE id IN (".$Db->_e($items).")") ? 1 : 0 ;
				$Db->Query("DELETE FROM #__blog_posts_rev WHERE postid IN (".$Db->_e($items).")");
				$Db->Query("DELETE FROM #__comments WHERE controller='"._PLUGIN."' AND item IN (".$Db->_e($items).")");
				$Db->Query("DELETE FROM #__ratings WHERE controller='"._PLUGIN."' AND item IN (".$Db->_e($items).")");
				break;
			case "trash":
				$result = $Db->Query("UPDATE #__blog_posts SET prev=status, status='deleted' WHERE id IN (".$Db->_e($items).")") ? 1 : 0 ;
				break;
			case "restore":
				$result = $Db->Query("UPDATE #__blog_posts SET status=prev,prev='' WHERE id IN (".$Db->_e($items).")") ? 1 : 0 ;
				break;
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
	
	function SwitchBlogPost() {
		global $Db;

		$id = Io::GetVar("POST","id","int");
		$row = $Db->GetRow("SELECT status FROM #__blog_posts WHERE id=".intval($id)."");
		$status = (Io::Output($row['status'])=="published") ? "inactive" : "published" ;
		$result = $Db->Query("UPDATE #__blog_posts SET status='$status' WHERE id=".intval($id)) ? 1 : 0 ;
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
	
	function SendPostToTrash() {
		global $Db;

		$id = Io::GetVar("GET","id","int");
		$Db->Query("UPDATE #__blog_posts SET prev=status, status='deleted' WHERE id=".intval($id));
		
		Utils::Redirect("admin.php?cont="._PLUGIN);
	}
	
	function RestorePost() {
		global $Db;

		$id = Io::GetVar("GET","id","int");
		$Db->Query("UPDATE #__blog_posts SET status=prev,prev='' WHERE id=".intval($id));
		
		Utils::Redirect("admin.php?cont="._PLUGIN."&status=deleted");
	}
	
	function PostRevisions() {
		global $Db,$Router,$config_sys;
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		?>
        
        <script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				//Submit form
				$('form#validate').submit(function() {
					if ($('input.old:radio:checked').val()==$('input.new:radio:checked').val()) {
						alert('<?php echo _t("SELECT_2_DIST_REV_COMP"); ?>');
						return false;
					} else {
						return true;
					}
				});
			});
			
			function showmenu(id) {
				$("#menu_"+id).toggle();
			}
			//Delete all revisions of the post id
			function deleteRevisions(id) {
				if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("REVISIONS"))); ?>')) {
					$.ajax({
						type: "POST",
						dataType: "xml",
						url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=deleteallrev",
						data: "id="+id,
						success: function(data){
							location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=revisions&id='+id;
						}
					});
				}
			}
		</script>
        
        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpost' title='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;status=deleted' title='"._t("TRASH_CAN")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."trash.png' alt='"._t("TRASH_CAN")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=categories' title='"._t("CATEGORIES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."category.png' alt='"._t("CATEGORIES")."' /></a>\n";
			echo "<a href='admin.php?cont=plugins&amp;op=options&amp;controller="._PLUGIN."' title='"._t("PLUGIN_OPTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."configuration.png' alt='"._t("PLUGIN_OPTIONS")."' /></a>\n";
		?>
        </div>
        
        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("REVISION_MANAGEMENT"); ?></div>
                        <div class="body">
                        	<?php
							
							$id = Io::GetVar("GET","id","int");
							$rev = Io::GetVar("GET","rev","int");
							
							$old = Io::GetVar("POST","old","int");
							$new = Io::GetVar("POST","new","int");
							
							if ($row = $Db->GetRow("SELECT p.*,c.name AS cname, c.title AS ctitle, l.title AS langtitle
													FROM #__blog_posts AS p JOIN #__blog_categories AS c JOIN #__languages AS l
													ON p.category=c.id AND p.language=l.file
													WHERE p.id=".intval($id))) {	
								$title		= Io::Output($row['title']);
								$modified	= Io::Output($row['modified']);
								
								echo "<form action='admin.php?cont="._PLUGIN."&amp;op=revisions&amp;id=$id' method='post' id='validate'>\n";
								echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
									//Delete all revisions
									echo "<input type='button' name='restore' value='"._t("DELETE_ALL_REVISIONS")."' style='margin:2px 0;' class='sys_form_button' onclick='javascript:deleteRevisions($id);' />\n";
									//Compare
									echo "<input type='submit' name='restore' value='"._t("COMPARE")."' style='margin:2px 0;' class='sys_form_button' />\n";
								echo "</div>\n";
								
								echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
								echo "<thead>\n";
									echo "<tr>\n";
										echo "<th width='1%'></th>\n";
										echo "<th width='1%'></th>\n";
										echo "<th width='18%'>"._t("CURRENT_VERSION")."</th>\n";
										echo "<th width='60%'>"._t("TITLE")."</th>\n";
										echo "<th width='20%'></th>\n";
									echo "</tr>\n";
								echo "</thead>\n";
								echo "<tbody>\n";
								
									echo "<tr>\n";
										echo "<td><input type='radio' name='old' value='0' class='old' /></td>\n";
										echo "<td><input type='radio' name='new' value='0' class='new' checked='checked' /></td>\n";
                                		echo "<td>$modified</td>\n";
										echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=revisions&amp;id=$id&amp;rev=0'><strong>$title</strong></a></td>\n";
										echo "<td></td>\n";
                                	echo "</tr>\n";
								
								echo "<thead>\n";
									echo "<tr>\n";
										echo "<th></th>\n";
										echo "<th></th>\n";
										echo "<th>"._t("PREVIOUS_VERSIONS")."</th>\n";
										echo "<th>"._t("TITLE")."</th>\n";
										echo "<th></th>\n";
									echo "</tr>\n";
								echo "</thead>\n";
								echo "<tbody>\n";
								$n = 0;
								if ($rresult = $Db->GetList("SELECT p.*,c.name AS cname, c.title AS ctitle, l.title AS langtitle
														   FROM #__blog_posts_rev AS p JOIN #__blog_categories AS c JOIN #__languages AS l
														   ON p.category=c.id AND p.language=l.file
														   WHERE p.status='revision' AND p.postid=".intval($id)."
														   ORDER BY p.id DESC")) {
										foreach ($rresult as $rrow) {
											$rid			= Io::Output($rrow['id'],"int");
											$rtitle		= Io::Output($rrow['title']);
											$rprevmod	= Io::Output($rrow['prevmod']);
											$checked = ($n++==0) ? " checked='checked'" : "" ;
											echo "<tr onmouseover='javascript:showmenu($rid);' onmouseout='javascript:showmenu($rid);'>\n";
												echo "<td><input type='radio' name='old' value='$rid' class='old'{$checked} /></td>\n";
												echo "<td><input type='radio' name='new' value='$rid' class='new' /></td>\n";
                                				echo "<td>$rprevmod</td>\n";
												echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=revisions&amp;id=$id&amp;rev=$rid'><strong>$rtitle</strong></a></td>\n";
												echo "<td style='text-align:right;'>\n";
													echo "<div id='menu_$rid' style='display:none; margin-top:2px;'>\n";
														echo "<a href='admin.php?cont="._PLUGIN."&amp;op=deleterev&amp;id=$id&amp;rev=$rid'>"._t("DELETE")."</a>\n";
														echo " - <a href='admin.php?cont="._PLUGIN."&amp;op=restorerev&amp;id=$id&amp;rev=$rid'>"._t("RESTORE")."</a>\n";
													echo "</div>\n";
												echo "</td>\n";
                                			echo "</tr>\n";
										}
									} else {
										echo "<tr>\n";
											echo "<td colspan='5' style='text-align:center;'>"._t("LIST_EMPTY")."</td>\n";
                                		echo "</tr>\n";
									}
								?>
                                    </tbody>
                                </table>
                        		<?php
								
								if ($old!==false && $new!==false) {
									?>
                                        </div>
                                        <div class="ui-widget-header"><?php echo _t("COMPARE"); ?>: <?php echo (($old>0) ? $old : _t("CURRENT"))." > ".(($new>0) ? $new : _t("CURRENT")); ?></div>
                                        <div class="body">
                                    <?php
									if ($old!=$new) {
										if ($old>0) $row = $Db->GetRow("SELECT text FROM #__blog_posts_rev WHERE id=".intval($old)." AND postid=".intval($id));
										$otext = Io::Output($row['text']);
										if ($new>0) $row = $Db->GetRow("SELECT text FROM #__blog_posts_rev WHERE id=".intval($new)." AND postid=".intval($id));
										else $row = $Db->GetRow("SELECT text FROM #__blog_posts WHERE id=".intval($id));
										$ntext = Io::Output($row['text']);
										echo "<div class='sys_rev_box'>\n";
											echo Diff::Process($otext,$ntext);
										echo "</div>\n";
									} else {
										MemErr::Trigger("USERERROR",_t("SELECT_2_DIST_REV_COMP"));
									}
								} else if ($rev!==false) {
									?>
                                        </div>
                                        <div class="ui-widget-header"><?php echo _t("REVISION"); ?>: <?php echo ($rev>0) ? $rev : _t("CURRENT"); ?></div>
                                        <div class="body">
                                    <?php
									if ($rev>0) $row = $Db->GetRow("SELECT text FROM #__blog_posts_rev WHERE id=".intval($rev)." AND postid=".intval($id));
									else $row = $Db->GetRow("SELECT text FROM #__blog_posts WHERE id=".intval($id));
									$text = Io::Output($row['text']);
									echo "<div class='sys_rev_box'>\n";
										echo $text;
									echo "</div>\n";
								}
							} else {
								MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("POST")));
							}
							?>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        </form>
        <?php
			
		//Assign captured content to the template engine and clean buffer
		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
		//Draw site template
		Template::Draw();
		//Initialize and show site footer
		Layout::Footer();
	}
	
	function RestoreRevision() {
		global $Db;
		
		$id = Io::GetVar("GET","id","int");
		$rev = Io::GetVar("GET","rev","int");
		
		if ($Db->GetRow("SELECT id FROM #__blog_posts WHERE id=".intval($id)) && $Db->GetRow("SELECT id FROM #__blog_posts_rev WHERE id=".intval($rev)." AND postid=".intval($id))) {
			//Create a new revision entry
			$Db->Query("INSERT INTO #__blog_posts_rev (category,title,name,text,language,prevmod,start,end,options,usecomments,postid,roles,prev)
						(SELECT category,title,name,text,language,modified,start,end,options,usecomments,id,roles,status FROM #__blog_posts WHERE id=".intval($id).")");
			
			//Restore data in original post
			$Db->Query("UPDATE #__blog_posts AS p, #__blog_posts_rev AS r
					   SET p.category=r.category,p.title=r.title,p.name=r.name,p.text=r.text,p.language=r.language,p.modified=NOW(),p.start=r.start,
					   p.end=r.end,p.options=r.options,p.usecomments=r.usecomments,p.revisions=p.revisions+1,p.roles=r.roles,p.status=r.prev
					   WHERE p.id=".intval($id)." AND r.id=".intval($rev));
			
			Utils::Redirect("admin.php?cont="._PLUGIN."&op=revisions&id=$id&mes=restored");
		} else {
			//Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();
			
			MemErr::Trigger("USERERROR",_t("WRONG_DATA_X","APB_e001"));
			
			//Assign captured content to the template engine and clean buffer
			Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
			//Draw site template
			Template::Draw();
			//Initialize and show site footer
			Layout::Footer();
		}
	}
	
	function DeleteRevision() {
		global $Db;
		
		$id = Io::GetVar("GET","id","int");
		$rev = Io::GetVar("GET","rev","int");
		
		if ($Db->GetRow("SELECT id FROM #__blog_posts_rev WHERE id=".intval($rev)." AND postid=".intval($id))) {
			$Db->Query("DELETE FROM #__blog_posts_rev WHERE id=".intval($rev)." AND postid=".intval($id));
			$Db->Query("UPDATE #__blog_posts SET revisions=revisions-1 WHERE id=".intval($id));
			Utils::Redirect("admin.php?cont="._PLUGIN."&op=revisions&id=$id");
		} else {
			//Initialize and show site header
			Layout::Header();
			//Start buffering content
			Utils::StartBuffering();
			
			MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("REVISION")));
			
			//Assign captured content to the template engine and clean buffer
			Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
			//Draw site template
			Template::Draw();
			//Initialize and show site footer
			Layout::Footer();
		}
	}
	
	function DeleteAllRevisions() {
		global $Db;
		
		$id = Io::GetVar("POST","id","int");
		
		if ($Db->GetRow("SELECT id FROM #__blog_posts_rev WHERE postid=".intval($id))) {
			$result = $Db->Query("DELETE FROM #__blog_posts_rev WHERE postid=".intval($id));
			$Db->Query("UPDATE #__blog_posts SET revisions=0 WHERE id=".intval($id));
		
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
	
	function ShowPostComments() {
		global $Db,$config_sys,$User;
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		$id = Io::GetVar("GET","id","int");
		
		?>
        
        <script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				//Delete
				$('input#delete').click(function() {
					var obj = $('.cb:checkbox:checked');
					if (obj.length>0) {
						if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X",MB::strtolower(_t("COMMENT"))); ?>')) {
							var items = new Array();
							for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
							$.ajax({
								type: "POST",
								dataType: "html",
								url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=delcomments&id=<?php echo $id; ?>",
								data: "items="+items,
								success: function(data){
									location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=comments&id=<?php echo $id; ?>';
								}
							});
						}
					} else {
						alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("COMMENT"))); ?>');
					}
				});
			});
		</script>
		
		<div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpost' title='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;status=deleted' title='"._t("TRASH_CAN")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."trash.png' alt='"._t("TRASH_CAN")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=categories' title='"._t("CATEGORIES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."category.png' alt='"._t("CATEGORIES")."' /></a>\n";
			echo "<a href='admin.php?cont=plugins&amp;op=options&amp;controller="._PLUGIN."' title='"._t("PLUGIN_OPTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."configuration.png' alt='"._t("PLUGIN_OPTIONS")."' /></a>\n";
		?>
        </div>
        
        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("COMMENTS"); ?></div>
                        <div class="body">
							<?php
							
							echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
								//Delete
								echo "<input type='button' name='delete' value='"._t("DELETE_PERMANENTLY")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
							echo "</div>\n";
                            
							echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
							echo "<thead>\n";
								echo "<tr>\n";
									echo "<th width='1%'><input type='checkbox' id='selectall' /></th>\n";
									echo "<th width='20%'>"._t("AUTHOR")."</th>\n";
									echo "<th width='45%'>"._t("TEXT")."</th>\n";
									echo "<th width='14%'>"._t("STATUS")."</th>\n";
								echo "</tr>\n";
							echo "</thead>\n";
							echo "<tbody>\n";
							
                            if ($result = $Db->GetList("SELECT * FROM #__comments WHERE controller='"._PLUGIN."' AND item=".intval($id)." ORDER BY id DESC")) {
								foreach ($result as $row) {
									$cid	= Io::Output($row['id'],"int");
									$author	= Io::Output($row['author']);
									$text	= BBCode::ToHtml(Io::Output($row['text']));
									$status	= MB::ucfirst(Io::Output($row['status']));
									
									$author = ($author>0) ? $User->Name($author) : Io::Output($row['author_name']) ;
									
									echo "<tr>\n";
										echo "<td><input type='checkbox' name='selected[]' value='$cid' class='cb' /></td>\n";
										echo "<td>$author</td>\n";
										echo "<td>$text</td>\n";
										echo "<td>$status</td>\n";
                                	echo "</tr>\n";
								}
                            } else {
								echo "<tr>\n";
									echo "<td colspan='4' style='text-align:center;'>"._t("LIST_EMPTY")."</td>\n";
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
        </form>
        <?php
		
		//Assign captured content to the template engine and clean buffer
		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
		//Draw site template
		Template::Draw();
		//Initialize and show site footer
		Layout::Footer();
	}
	
	function DeleteComments() {
		global $Db;

		$id = Io::GetVar("GET","id",false,true);
		$items = Io::GetVar("POST","items",false,true);

		if ($id==0 || $items==0) return;
			
		$result = $Db->Query("DELETE FROM #__comments WHERE controller='".$Db->_e(_PLUGIN_CONTROLLER)."' AND id IN (".$Db->_e($items).")") ? 1 : 0 ;
		$total = $Db->AffectedRows();
		if ($total) $result = $Db->Query("UPDATE #__blog_posts SET comments=comments-".intval($total)." WHERE id=".intval($id)) ? 1 : 0 ;

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
	
	function BlogCategories() {
		global $Db,$config_sys;
		
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();
		
		?>
        
        <script type="text/javascript" charset="utf-8">
			function PostSwitch(id) {
				$.ajax({
					type: "POST",
					dataType: "xml",
					url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=switchpost",
					data: "id="+id,
					success: function(data){
						var text = data.result;
						$('.txt_'+id).html(text);
					}
				});
			}
			$(document).ready(function() {
				//Create
				$('input#create').click(function() {
                    window.location.href = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=createcat';
                });

				//Delete permanently
				$('input#delete').click(function() {
					var obj = $('.cb:checkbox:checked');
					if (obj.length>0) {
						if (confirm('<?php echo _t("SURE_PERMANENTLY_DELETE_THE_X_AND_CONTENT",MB::strtolower(_t("CATEGORIES"))); ?>')) {
							var items = new Array();
							for (var i=0;i<obj.length;i++) items[i] = obj[i].value;
							$.ajax({
								type: "POST",
								dataType: "xml",
								url: "admin.php?cont=<?php echo _PLUGIN; ?>&op=deletecat",
								data: "items="+items,
								success: function(data){
									location = 'admin.php?cont=<?php echo _PLUGIN; ?>&op=categories';
								}
							});
						}
					} else {
						alert('<?php echo _t("MUST_SELECT_AT_LEAST_ONE_X",MB::strtolower(_t("CATEGORY"))); ?>');
					}
				});
			});
		</script>
        
        <div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
        <div style="text-align:right;">
        <?php
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpost' title='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;status=deleted' title='"._t("TRASH_CAN")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."trash.png' alt='"._t("TRASH_CAN")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=categories' title='"._t("CATEGORIES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."category.png' alt='"._t("CATEGORIES")."' /></a>\n";
			echo "<a href='admin.php?cont=plugins&amp;op=options&amp;controller="._PLUGIN."' title='"._t("PLUGIN_OPTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."configuration.png' alt='"._t("PLUGIN_OPTIONS")."' /></a>\n";
		?>
        </div>
        
        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("MANAGE_CATEGORIES"); ?></div>
                        <div class="body">
        
        <?php
		echo "<div style='float:left; margin:6px 0 2px 0;'>\n";
			//Create category
			echo "<input type='button' name='create' value='"._t("CREATE_NEW_X",MB::strtolower(_t("CATEGORY")))."' style='margin:2px 0;' class='sys_form_button' id='create' />\n";
		echo "</div>\n";
		echo "<div style='text-align:right; padding:6px 0 2px 0; clear:right;'>\n";
			//Delete permanently
			echo "<input type='button' name='delete' value='"._t("DELETE_PERMANENTLY")."' style='margin:2px 0;' class='sys_form_button' id='delete' />\n";
		echo "</div>\n";
		
        echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' summary='0' class='tgrid'>\n";
			echo "<thead>\n";
				echo "<tr>\n";
					echo "<th width='1%' style='text-align:right;'><input type='checkbox' id='selectall' /></th>\n";
					echo "<th width='45%'>"._t("TITLE")."</th>\n";
					echo "<th width='45%'>"._t("NAME")."</th>\n";
					echo "<th width='9%' style='text-align:right;'>"._t("POSTS")."</th>\n";
				echo "</tr>\n";
			echo "</thead>\n";
			echo "<tbody>\n";
								
			if ($result = $Db->GetList("SELECT c.id,c.title,c.name,(SELECT COUNT(p.id) AS tot FROM #__blog_posts AS p WHERE p.category=c.id AND p.status='published') AS posts FROM #__blog_categories AS c ORDER BY c.title")) {
				foreach ($result as $row) {
					$id		= Io::Output($row['id'],"int");
					$title	= Io::Output($row['title']);
					$name	= Io::Output($row['name']);
					$posts	= Io::Output($row['posts'],"int");
												
					echo "<tr>\n";
						echo "<td><input type='checkbox' name='selected[]' value='$id' class='cb' /></td>\n";
						echo "<td><a href='admin.php?cont="._PLUGIN."&amp;op=editcat&amp;id=$id' title='"._t("EDIT_THIS_X",MB::strtolower(_t("CATEGORY")))."'>$title</a></td>\n";
						echo "<td>$name</td>\n";
						echo "<td style='text-align:right;'>$posts</td>\n";
                    echo "</tr>\n";
				}
			} else {
				echo "<tr>\n";
					echo "<td colspan='4' style='text-align:center;'>"._t("LIST_EMPTY")."</td>\n";
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
	
	function DeleteBlogCategory() {
		global $Db;

		$items = Io::GetVar("POST","items",false,true);

		$res = $Db->Query("DELETE FROM #__blog_categories WHERE id IN (".$Db->_e($items).")") ? 1 : 0 ;
		$total = $Db->AffectedRows();
		
		$ids = array();
		if ($result = $Db->GetList("SELECT id FROM #__blog_posts WHERE category IN (".$Db->_e($items).")")) {
			foreach ($result as $row) $ids[] = Io::Output($row['id']);
		}
		$items = implode(",",$ids);
		
		$Db->Query("DELETE FROM #__blog_posts WHERE id IN (".$Db->_e($items).")");
		$Db->Query("DELETE FROM #__blog_posts_rev WHERE postid IN (".$Db->_e($items).")");
		$Db->Query("DELETE FROM #__comments WHERE controller='"._PLUGIN."' AND item IN (".$Db->_e($items).")");
		$Db->Query("DELETE FROM #__ratings WHERE controller='"._PLUGIN."' AND item IN (".$Db->_e($items).")");

		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
		header("Cache-Control: no-cache, must-revalidate" );
		header("Pragma: no-cache" );
		header("Content-Type: text/xml");

		$xml = '<?xml version="1.0" encoding="utf-8"?>\n';
		$xml .= '<response>\n';
			$xml .= '<result>\n';
				$xml .= '<query>'.$res.'</query>\n';
				$xml .= '<rows>'.$total.'</rows>\n';
			$xml .= '</result>\n';
		$xml .= '</response>';
		return $xml;
	}
	
	function CreateBlogCategory() {
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
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpost' title='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;status=deleted' title='"._t("TRASH_CAN")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."trash.png' alt='"._t("TRASH_CAN")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=categories' title='"._t("CATEGORIES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."category.png' alt='"._t("CATEGORIES")."' /></a>\n";
			echo "<a href='admin.php?cont=plugins&amp;op=options&amp;controller="._PLUGIN."' title='"._t("PLUGIN_OPTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."configuration.png' alt='"._t("PLUGIN_OPTIONS")."' /></a>\n";
		?>
        </div>
        
        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("CREATE_NEW_X",MB::strtolower(_t("CATEGORY"))); ?></div>
                        <div class="body">
                        
						<?php
						
						if (!isset($_POST['create'])) {
								$form = new Form();
								$form->action = "admin.php?cont="._PLUGIN."&amp;op=createcat";
								
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
								

								?>						
								
                                <div style="padding:2px;"></div>
                                <?php
										
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
								$title = Io::GetVar('POST','title','fullhtml');
								$name = Io::GetVar('POST','name','[^a-zA-Z0-9\-]');
								
								$errors = array();
								if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
								if (empty($name)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LINK_NAME"));
								
								if (!sizeof($errors)) {
									$Db->Query("INSERT INTO #__blog_categories (title,name)
												VALUES ('".$Db->_e($title)."','".$Db->_e($name)."')");
												
									Utils::Redirect("admin.php?cont="._PLUGIN."&op=categories");
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
	
	function EditBlogCategory() {
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
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=createpost' title='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."create.png' alt='"._t("CREATE_NEW_X",MB::strtolower(_t("POST")))."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;status=deleted' title='"._t("TRASH_CAN")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."trash.png' alt='"._t("TRASH_CAN")."' /></a>\n";
			echo "<a href='admin.php?cont="._PLUGIN."&amp;op=categories' title='"._t("CATEGORIES")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."category.png' alt='"._t("CATEGORIES")."' /></a>\n";
			echo "<a href='admin.php?cont=plugins&amp;op=options&amp;controller="._PLUGIN."' title='"._t("PLUGIN_OPTIONS")."'><img src='admin"._DS."templates"._DS.$config_sys['admincp_template']._DS."buttons"._DS."configuration.png' alt='"._t("PLUGIN_OPTIONS")."' /></a>\n";
		?>
        </div>
        
        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("EDIT"); ?></div>
                        <div class="body">
                        
						<?php
						
						$id = Io::GetVar('GET','id','int');
						if ($row = $Db->GetRow("SELECT * FROM #__blog_categories WHERE id=".intval($id))) {
						
							if (!isset($_POST['save'])) {
									$form = new Form();
									$form->action = "admin.php?cont="._PLUGIN."&amp;op=editcat&amp;id=$id";
									
									$form->Open();
		
									//Title
									$form->AddElement(array("element"	=>"text",
															"label"		=>_t("TITLE"),
															"width"		=>"300px",
															"name"		=>"title",
															"id"		=>"title",
															"value"		=>Io::Output($row['title'])));
															
									//Name
									$form->AddElement(array("element"	=>"text",
															"label"		=>_t("LINK_NAME"),
															"name"		=>"name",
															"width"		=>"300px",
															"id"		=>"urlvalidname",
															"suffix"	=>"<input type='button' id='autoname' value='"._t("AUTO")."' class='sys_form_button' />",
															"info"		=>_t("NUM_LOWCASE_LATIN_CHARS_DASH_ONLY"),
															"value"		=>Io::Output($row['name'])));
	
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
										$title = Io::GetVar('POST','title','fullhtml');
										$name = Io::GetVar('POST','name','[^a-zA-Z0-9\-]');
										
										$errors = array();
										if (empty($title)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("TITLE"));
										if (empty($name)) $errors[] = _t("THE_FIELD_X_IS_REQUIRED",_t("LINK_NAME"));
										
										if (!sizeof($errors)) {
											$Db->Query("UPDATE #__blog_categories SET title='".$Db->_e($title)."',name='".$Db->_e($name)."' WHERE id=".intval($id));
											
											Utils::Redirect("admin.php?cont="._PLUGIN."&op=categories");
										} else {
											MemErr::Trigger("USERERROR",implode("<br />",$errors));
										}
									} else {
										MemErr::Trigger("USERERROR",_t("INVALID_TOKEN"));
									}
								}
						} else {
							MemErr::Trigger("USERERROR",_t("X_NOT_FOUND",_t("CATEGORY")));
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
}

?>