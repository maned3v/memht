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

class contactModel {
	function Main() {
		global $Db,$config_sys,$Router;

		//Load plugin language
		Language::LoadPluginFile(_PLUGIN_CONTROLLER);
		//Initialize and show site header
		Layout::Header();
		//Start buffering content
		Utils::StartBuffering();

		?>
		
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				var i = 1;
				$("#simpletext").click(function() {
					$('#dynbox').append("<div id='js_"+i+"'><fieldset class='sys_form_fieldset'><legend class='sys_form_legend'><?php echo _t("TEXT"); ?></legend><div style='width:50%;float:left;'><p style='margin:10px 0;'><span class='sys_form_label'><label><?php echo _t("LABEL"); ?></label></span><input type='text' name='label[]' id='label' value='Label' style='margin:2px 0; width:300px;' class='sys_form_text' /></p></div><div style='width:50%;float:left;'><p style='margin:10px 0;'><span class='sys_form_label'><label><?php echo _t("NAME"); ?></label></span><input type='text' name='name[]' id='name' value='name' style='margin:2px 0; width:300px;' class='sys_form_text' /></p></div><div style='width:50%;float:left;'><p style='margin:10px 0;'><span class='sys_form_label'><label><?php echo _t("REQUIRED"); ?></label></span><select name='required[]' id='required' style='margin:2px 0; width:200px;' class='sys_form_select'><option value='0'><?php echo _t("NO"); ?></option><option value='1'><?php echo _t("YES"); ?></option><option value='email'><?php echo _t("EMAIL"); ?></option><option value='url'><?php echo _t("URL"); ?></option></select></p></div><div style='width:50%;float:left;text-align:right;margin-top:27px;'><p style='display:inline;'><input type='button' name='delete' value='<?php echo _t("DELETE"); ?>' style='margin:2px 0;' class='sys_form_button' onclick=\"javascript:deleteElement('js_"+i+"');\" /></p></div><input name='type[]' id='type[]' type='hidden' value='text' /></fieldset></div>");
					i++;
				});
				$("#textarea").click(function() {
					$('#dynbox').append("<div id='js_"+i+"'><fieldset class='sys_form_fieldset'><legend class='sys_form_legend'><?php echo _t("TEXTAREA"); ?></legend><div style='width:50%;float:left;'><p style='margin:10px 0;'><span class='sys_form_label'><label><?php echo _t("LABEL"); ?></label></span><input type='text' name='label[]' id='label' value='Label' style='margin:2px 0; width:300px;' class='sys_form_text' /></p></div><div style='width:50%;float:left;'><p style='margin:10px 0;'><span class='sys_form_label'><label><?php echo _t("NAME"); ?></label></span><input type='text' name='name[]' id='name' value='name' style='margin:2px 0; width:300px;' class='sys_form_text' /></p></div><div style='width:50%;float:left;'><p style='margin:10px 0;'><span class='sys_form_label'><label><?php echo _t("REQUIRED"); ?></label></span><select name='required[]' id='required' style='margin:2px 0; width:200px;' class='sys_form_select'><option value='0'><?php echo _t("NO"); ?></option><option value='1'><?php echo _t("YES"); ?></option><option value='email'><?php echo _t("EMAIL"); ?></option><option value='url'><?php echo _t("URL"); ?></option></select></p></div><div style='width:50%;float:left;text-align:right;margin-top:27px;'><p style='display:inline;'><input type='button' name='delete' value='<?php echo _t("DELETE"); ?>' style='margin:2px 0;' class='sys_form_button' onclick=\"javascript:deleteElement('js_"+i+"');\" /></p></div><input name='type[]' id='type[]' type='hidden' value='textarea' /></fieldset></div>");
					i++;
				});
			});

			function deleteElement(id) {
				$("#"+id).remove();
			}
		</script>
		
		<div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
       	
       	<?php
       	$form = new Form();
       	$form->action = "admin.php?cont="._PLUGIN."&amp;op=save";
       	$form->Open();
       	?>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
        	<tr>
		       	<td style="vertical-align:top;">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _PLUGIN_TITLE; ?></div>
                        <div class="body">
                        	<?php
                        	$id = 1;
                        	$forms = Utils::Unserialize($Router->GetOption("forms"));
                        	if (!$forms) {
                        		$forms = Utils::Unserialize('a:1:{s:5:"forms";a:5:{i:0;a:6:{s:7:"element";s:4:"text";s:5:"label";s:4:"Name";s:4:"name";s:4:"name";s:5:"width";s:5:"250px";s:8:"required";i:1;s:4:"info";s:8:"Required";}i:1;a:6:{s:7:"element";s:4:"text";s:5:"label";s:5:"Email";s:4:"name";s:5:"email";s:5:"width";s:5:"250px";s:8:"required";s:5:"email";s:4:"info";s:8:"Required";}i:2;a:6:{s:7:"element";s:4:"text";s:5:"label";s:7:"Subject";s:4:"name";s:7:"subject";s:5:"width";s:5:"300px";s:8:"required";i:1;s:4:"info";s:8:"Required";}i:3;a:6:{s:7:"element";s:8:"textarea";s:5:"label";s:7:"Message";s:4:"name";s:7:"message";s:5:"class";s:6:"simple";s:8:"required";i:1;s:4:"info";s:8:"Required";}i:4;a:5:{s:7:"element";s:6:"submit";s:4:"name";s:6:"submit";s:2:"id";s:3:"id8";s:7:"captcha";b:1;s:5:"value";s:6:"Submit";}}}');
                        		$forms = $forms['forms'];
                        	}
                        	foreach ($forms as $element) {
                        		if ($element['element']=="submit") break;
                        		echo "<div id='db_$id'>\n";
	                        		switch ($element['element']) {
	                        			case "text":
	                        				$form->FieldsetStart(_t("SIMPLE_TEXT"));
											break;
	                        			case "textarea":
	                        				$form->FieldsetStart(_t("TEXTAREA"));
	                        				break;
	                        			
	                        		}
	                        		echo "<div style='width:50%;float:left;'>\n";
		                        		$form->AddElement(array("element"	=>"text",
		                        								"label"		=>_t("LABEL"),
		                        								"width"		=>"300px",
		                        								"name"		=>"label[]",
		                        								"value"		=>$element["label"],
		                        								"id"		=>"label"));
	                        		echo "</div>\n";
	                        		echo "<div style='width:50%;float:left;'>\n";
	                        			$form->AddElement(array("element"	=>"text",
		                        								"label"		=>_t("NAME"),
		                        								"width"		=>"300px",
		                        								"name"		=>"name[]",
		                        								"value"		=>$element["name"],
		                        								"id"		=>"name"));
	                        		echo "</div>\n";
	                        		
	                        		echo "<div style='width:50%;float:left;'>\n";
		                        		$form->AddElement(array("element"	=>"select",
		                        								"label"		=>_t("REQUIRED"),
		                        								"name"		=>"required[]",
		                        								"values"	=>array(_t("NO")	=>0,
		                        													_t("YES")	=>1,
		                        													_t("EMAIL")	=>"email",
		                        													_t("URL")	=>"url"),
		                        								"selected"	=>$element["required"],
		                        								"id"		=>"required"));
	                        		echo "</div>\n";
	                        		echo "<div style='width:50%;float:left;text-align:right;margin-top:27px;'>\n";
	                        			$form->AddElement(array("element"	=>"button",
	                        									"name"		=>"delete",
	                        									"inline"	=>true,
	                        									"extra"		=>"onclick=\"javascript:deleteElement('db_$id');\"",
	                        									"value"		=>_t("DELETE")));
	                        		echo "</div>\n";
	                        		
	                        		$form->Hidden("type[]",$element['element']);
	                        		
	                        		$form->FieldsetEnd();
	                        	echo "</div>\n";
	                        	$id++;
                        	}
                        	?>
                        	<div id="dynbox"></div>
                        </div>
                    </div>
                </td>
                <td class="sidebar">
                    <div class="widget ui-widget-content ui-corner-all">
                        <div class="ui-widget-header"><?php echo _t("ELEMENTS"); ?></div>
                        <div class="body">
							<input type='button' id='simpletext' value='<?php echo _t("SIMPLE_TEXT") ?>' style='margin:2px 0;width:100%;' class='sys_form_button' />
							<input type='button' id='textarea' value='<?php echo _t("TEXTAREA") ?>' style='margin:2px 0;width:100%;' class='sys_form_button' />
                        </div>
                    </div>
                    <div>
						<?php

						//Create
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
        
		//Assign captured content to the template engine and clean buffer
		Template::AssignVar("sys_main",array("title"=>_PLUGIN_TITLE,"url"=>"admin.php?cont="._PLUGIN,"content"=>Utils::GetBufferContent("clean")));
		//Draw site template
		Template::Draw();
		//Initialize and show site footer
		Layout::Footer();
	}

    function SaveForm() {
        global $Db,$config_sys,$Router;

		//Load plugin language
		Language::LoadPluginFile(_PLUGIN_CONTROLLER);
		//Initialize and show site header
		Layout::Header(array("editor"=>true));
		//Start buffering content
		Utils::StartBuffering();
		
		?>
		<div class="tpl_page_title"><a href="admin.php?cont=<?php echo _PLUGIN; ?>" title="<?php echo _PLUGIN_TITLE; ?>"><?php echo _PLUGIN_TITLE; ?></a></div>
		<?php

		//Check token
		if (Utils::CheckToken()) {
			if (isset($_POST['label']) && isset($_POST['name']) && isset($_POST['required']) && isset($_POST['type'])) {
				$forms = array();
				
				//Label
				$label = Io::GetVar('POST','label','nohtml');
				foreach ($label as $key => $value) {
					$forms[$key]['label'] = $value;
				}
				//Name
				$name = Io::GetVar('POST','name','nohtml');
				foreach ($name as $key => $value) {
					$forms[$key]['name'] = $value;
				}
				//Required
				$required = Io::GetVar('POST','required','nohtml');
				foreach ($required as $key => $value) {
					if ($value != "0") {
						$forms[$key]['required'] = $value;
						$forms[$key]['info'] = _t("REQUIRED");
					}
				}
				//Type
				$type = Io::GetVar('POST','type','nohtml');
				foreach ($type as $key => $value) {
					$forms[$key]['element'] = $value;
				}
				
				//Submit
				$forms[] = array('element'	=> 'submit',
            					 'name'		=> 'submit',
            					 'captcha'	=> 1,
								 'value'	=> _t("SEND"));
				
				$forms = Utils::Serialize($forms);
				$Router->SetOption("forms",$forms);
				
				?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" summary="">
						<tr>
							<td style="vertical-align:top;">
								<div class="widget ui-widget-content ui-corner-all">
									<div class="ui-widget-header"><?php echo _PLUGIN_TITLE; ?></div>
									<div class="body">
										<?php
										Error::Trigger("INFO",_t("SAVED"));
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
									<div class="ui-widget-header"><?php echo _PLUGIN_TITLE; ?></div>
									<div class="body">
										<?php
										Error::Trigger("USERERROR",_t("INTERROR","ACM_e001"));
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
							<div class="ui-widget-header"><?php echo _PLUGIN_TITLE; ?></div>
							<div class="body">
								<?php
								Error::Trigger("USERERROR",_t("INVALID_TOKEN"));
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
}

?>