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
defined("_LOAD") or die("Access denied");

class BaseForm {
	//Form options
	var $id = '';
	var $action = false;
	var $method = 'post';
	var $enctype = '';
	var $texteditor = true;
	var $inline = false;
	var $token = true;
	
	var $infieldset = false;
	var $VSpacer = true;
	
	//Form elements
	var $element = array();
	var $hidden = array();
	
	function Open() {
		if ($this->action) {
			echo "<form";
				if (!empty($this->id)) echo " id='".$this->id."'";
				echo " action='".$this->action."'";
				echo " method='".$this->method."'";
				if (!empty($this->enctype)) echo " enctype='".$this->enctype."'";
				if (!empty($this->accept)) echo " accept='".$this->accept."'";
				if (!empty($this->acceptcharset)) echo " accept-charset='".$this->acceptcharset."'";
				if (!empty($this->class)) echo " class='".$this->class."'";
				if (!empty($this->extra)) echo " ".$this->extra;
			echo ">\n";
		}
	}
	
	function FieldsetStart($legend=false) {
		if ($this->infieldset==true)
		$this->AddElement(array('element'	=>'fieldset_end'));
		
		$this->AddElement(array('element'	=>'fieldset_start',
								'legend'	=>$legend));
		$this->infieldset = true;
	}
	
	function FieldsetEnd() {
		if ($this->infieldset==true) {
			$this->AddElement(array('element'	=>'fieldset_end'));
			$this->infieldset = false;
		}
	}
	
	function Hidden($label,$value) {
		echo "<input name='$label' id='$label' type='hidden' value='".str_replace("'","&#039;",$value)."' />\n";
	}
	
	function AddElement() {
		global $config_sys;
		
		$args = func_get_args();
		$args = $args[0];
		
		switch ($args['element']) {
			case "fieldset_start":
				echo "<fieldset class='sys_form_fieldset'>\n";
				if (!empty($args['legend'])) {
					echo "<legend class='sys_form_legend'>".$args['legend']."</legend>\n";
				}
				break;
			case "fieldset_end":
				echo "</fieldset>\n";
				break;
			case "text":
				echo "<p";
					if ($this->inline || isset($args['inline'])) {
						echo " style='display:inline;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if ($this->VSpacer) {
						echo " style='margin:10px 0;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if (isset($args['align'])) {
						echo " style='text-align:".$args['align'].";";
					}
					echo "'>\n";
					if (isset($args['label'])) echo "<span class='sys_form_label'><label>".$args['label']."</label></span>\n";
					if (isset($args['prefix'])) echo $args['prefix'];
					echo "<input type=";
							echo (isset($args['password'])) ? "'password'" : "'text'" ;
						if (isset($args['name'])) echo " name='".$args['name']."'";
						if (isset($args['id'])) echo " id='".$args['id']."'";
						if (isset($args['value'])) echo " value='".str_replace("'","&#039;",$args['value'])."'";
						if (isset($args['disabled']) && $args['disabled']) echo " disabled='disabled'";
						echo " style='margin:2px 0; width:";
							echo (isset($args['width'])) ? $args['width'].";" : "200px;" ;
							if (isset($args['style'])) echo " ".$args['style'];
							echo "'";
						$required = isset($args['required']) ? " sys_form_required" : "" ;
						if (isset($args['class'])) { echo " class='".$args['class']."{$required}'"; } else { echo " class='sys_form_text{$required}'"; }
						if (isset($args['extra'])) echo " ".$args['extra'];
						if (isset($args['readonly'])) echo " readonly='readonly'";
					echo " />\n";
					if (isset($args['suffix'])) echo $args['suffix'];
					if (isset($args['info'])) echo "<span class='sys_form_info'>".$args['info']."</span>\n";
				echo "</p>\n";
				break;
			case "textarea":
				echo "<p";
					if ($this->inline || isset($args['inline'])) {
						echo " style='display:inline;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if ($this->VSpacer) {
						echo " style='margin:10px 0;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if (isset($args['align'])) {
						echo " style='text-align:".$args['align'].";";
					}
				echo "'>\n";
					if (isset($args['label'])) echo "<span class='sys_form_label'><label>".$args['label']."</label></span>\n";
					echo "<textarea";
						if (isset($args['name'])) echo " name='".$args['name']."'";
						if (isset($args['id'])) echo " id='".$args['id']."'";
						$required = isset($args['required']) ? " sys_form_required" : "" ;
						echo " class='";
							echo (isset($args['class'])) ? $args['class'] : "advanced" ;
							echo "{$required}'";
						if (isset($args['extra'])) echo " ".$args['extra'];
						if (isset($args['readonly'])) echo " readonly='readonly'";
						if (isset($args['disabled']) && $args['disabled']) echo " disabled='disabled'";
				echo " cols='60' rows='10'";
					echo " style='margin:2px 0; width:";
						echo (isset($args['width'])) ? $args['width'] : "100%" ;
						echo "; height:";
						echo (isset($args['height'])) ? $args['height'].";" : "200px;" ;
						if (isset($args['style'])) echo " ".$args['style'];
						echo "'";
					echo ">";
						if (isset($args['value'])) echo str_replace("'","&#039;",$args['value']);
					echo "</textarea>\n";
					if (isset($args['info'])) echo "<span class='sys_form_info'>".$args['info']."</span>\n";
				echo "</p>\n";
				break;
			
			case "select":
				echo "<p";
                    if ($this->inline || isset($args['inline'])) {
						echo " style='display:inline;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if ($this->VSpacer) {
						echo " style='margin:10px 0;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if (isset($args['align'])) {
						echo " style='text-align:".$args['align'].";";
					}
				echo "'>\n";
					if (isset($args['label'])) echo "<span class='sys_form_label'><label>".$args['label']."</label></span>\n";
					if (isset($args['prefix'])) echo $args['prefix'];
					echo "<select" ;
						if (isset($args['name'])) echo " name='".$args['name']."'";
						if (isset($args['multiple'])) echo " multiple='multiple'";
                        if (isset($args['size'])) echo " size='".$args['size']."'";
						if (isset($args['id'])) echo " id='".$args['id']."'";
						if (isset($args['disabled']) && $args['disabled']) echo " disabled='disabled'";
						echo " style='margin:2px 0; width:";
							echo (isset($args['width'])) ? $args['width'].";" : "200px;" ;
							if (isset($args['style'])) echo " ".$args['style'];
							echo "'";
						$required = isset($args['required']) ? " sys_form_required" : "" ;
						if (isset($args['class'])) { echo " class='".$args['class']."{$required}'"; } else { echo " class='sys_form_select{$required}'"; }
						if (isset($args['extra'])) echo " ".$args['extra'];
					echo ">\n";
                        //Options
						if (isset($args['values'])) {
							foreach ($args['values'] as $key => $value)	{
								//Selected
                                if (isset($args['selected'])) {
									if (is_array($args['selected'])) {
										$selected = (in_array($value,$args['selected'])) ? " selected='selected'" : "" ;
									} else {
										$selected = ($args['selected']==$value) ? " selected='selected'" : "" ;
									}
								} else {
									$selected = "";
								}
                                //Disabled
								if (isset($args['optdisabled'])) {
									if (is_array($args['optdisabled'])) {
										$disabled = (in_array($value,$args['optdisabled'],true)) ? " disabled='disabled'" : "" ;
									} else {
										$disabled = ($args['optdisabled']==$value) ? " disabled='disabled'" : "" ;
									}
								} else {
									$disabled = "";
								}
								echo "<option value='".str_replace("'","&#039;",$value)."'{$selected}{$disabled}>".str_replace("'","&#039;",$key)."</option>\n";
							}
						}
					echo "</select>\n";
					if (isset($args['suffix'])) echo $args['suffix'];
					if (isset($args['info'])) echo "<span class='sys_form_info'>".$args['info']."</span>\n";
				echo "</p>\n";
				break;
			
			case "checkbox":
				echo "<p";
					if ($this->inline || isset($args['inline'])) {
						echo " style='display:inline;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if ($this->VSpacer) {
						echo " style='margin:10px 0;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if (isset($args['align'])) {
						echo " style='text-align:".$args['align'].";";
					}
				echo "'>\n";
					if (isset($args['prefix'])) echo $args['prefix'];
					echo "<input type='checkbox'";
						if (isset($args['name'])) echo " name='".$args['name']."'";
						if (isset($args['id'])) echo " id='".$args['id']."'";
						if (isset($args['value'])) echo " value='".str_replace("'","&#039;",$args['value'])."'";
						if (isset($args['checked'])) echo " checked='checked'";
						echo " style='margin:2px 0;";
						if (isset($args['style'])) echo " ".$args['style'];
						echo "'";
						$required = isset($args['required']) ? " sys_form_required" : "" ;
						if (isset($args['class'])) { 
							echo " class='".$args['class']."{$required}'";
						} else if ($required) {
							echo " class='{$required}'";
						}
						if (isset($args['extra'])) echo " ".$args['extra'];
					echo " />\n";
					if (isset($args['label'])) echo "<span class='sys_form_label_inline'><label>".$args['label']."</label></span>\n";
					if (isset($args['suffix'])) echo $args['suffix'];
					if (isset($args['info'])) echo "<span class='sys_form_info'>".$args['info']."</span>\n";
				echo "</p>\n";
				break;
			
			case "radio":
				echo "<p";
					if ($this->inline || isset($args['inline'])) {
						echo " style='display:inline;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if ($this->VSpacer) {
						echo " style='margin:10px 0;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if (isset($args['align'])) {
						echo " style='text-align:".$args['align'].";";
					}
				echo "'>\n";
					if (isset($args['prefix'])) echo $args['prefix'];
					echo "<input type='radio'";
						if (isset($args['name'])) echo " name='".$args['name']."'";
						if (isset($args['id'])) echo " id='".$args['id']."'";
						if (isset($args['value'])) echo " value='".str_replace("'","&#039;",$args['value'])."'";
						if (isset($args['checked'])) echo " checked='checked'";
						echo " style='margin:2px 0;";
						if (isset($args['style'])) echo " ".$args['style'];
						echo "'";
						$required = isset($args['required']) ? " sys_form_required" : "" ;
						if (isset($args['class'])) { 
							echo " class='".$args['class']."{$required}'";
						} else if ($required) {
							echo " class='{$required}'";
						}
						if (isset($args['extra'])) echo " ".$args['extra'];
					echo " />\n";
					if (isset($args['label'])) echo "<span class='sys_form_label_inline'><label>".$args['label']."</label></span>\n";
					if (isset($args['suffix'])) echo $args['suffix'];
					if (isset($args['info'])) echo "<span class='sys_form_info'>".$args['info']."</span>\n";
				echo "</p>\n";
				break;
			
			case "file":
				echo "<p";
					if ($this->inline || isset($args['inline'])) {
						echo " style='display:inline;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if ($this->VSpacer) {
						echo " style='margin:10px 0;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if (isset($args['align'])) {
						echo " style='text-align:".$args['align'].";";
					}
				echo "'>\n";
					if (isset($args['label'])) echo "<span class='sys_form_label'><label>".$args['label']."</label></span>\n";
					if (isset($args['prefix'])) echo $args['prefix'];
					echo "<input type='file'";
						if (isset($args['name'])) echo " name='".$args['name']."'";
						if (isset($args['id'])) echo " id='".$args['id']."'";
						echo " size='";
							echo (isset($args['size'])) ? $args['size'] : "40" ;
							echo "'";
						if (isset($args['disabled']) && $args['disabled']) echo " disabled='disabled'";
						echo " style='margin:2px 0;";
						if (isset($args['style'])) echo " ".$args['style'];
						echo "'";
						$required = isset($args['required']) ? " sys_form_required" : "" ;
						if (isset($args['class'])) { 
							echo " class='".$args['class']."{$required}'";
						} else if ($required) {
							echo " class='{$required}'";
						}
						if (isset($args['extra'])) echo " ".$args['extra'];
					echo " />\n";
					if (isset($args['suffix'])) echo $args['suffix'];
					if (isset($args['info'])) echo "<span class='sys_form_info'>".$args['info']."</span>\n";
				echo "</p>\n";
				break;
				
			case "image":
				echo "<p";
					if ($this->inline || isset($args['inline'])) {
						echo " style='display:inline;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if ($this->VSpacer) {
						echo " style='margin:10px 0;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if (isset($args['align'])) {
						echo " style='text-align:".$args['align'].";";
					}
				echo "'>\n";
					if (isset($args['label'])) echo "<span class='sys_form_label'><label>".$args['label']."</label></span>\n";
					if (isset($args['prefix'])) echo $args['prefix'];
					echo "<input type='image'" ;
						if (isset($args['name'])) echo " name='".$args['name']."'";
						if (isset($args['id'])) echo " id='".$args['id']."'";
						if (isset($args['alt'])) echo " alt='".$args['alt']."'";
						echo " src='".$args['src']."'";
						echo " style='margin:2px 0;";
						if (isset($args['style'])) echo " ".$args['style'];
						echo "'";
						$required = isset($args['required']) ? " sys_form_required" : "" ;
						if (isset($args['class'])) { 
							echo " class='".$args['class']."{$required}'";
						} else if ($required) {
							echo " class='{$required}'";
						}
						if (isset($args['extra'])) echo " ".$args['extra'];
					echo " />\n";
					if (isset($args['suffix'])) echo $args['suffix'];
					if (isset($args['info'])) echo "<span class='sys_form_info'>".$args['info']."</span>\n";
				echo "</p>\n";
				break;
				
			case "submit":
				if (isset($args['captcha']) && $config_sys['captcha']==1) {
					echo "<p";
						if ($this->inline || isset($args['inline'])) {
							echo "display:inline;";
						} else if ($this->VSpacer) {
							echo " style='margin:10px 0;";
						}
				echo "'>\n";
					Captcha::Display();
					echo "</p>\n";
				}
				echo "<p";
					if ($this->inline || isset($args['inline'])) {
						echo " style='display:inline;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if ($this->VSpacer) {
						echo " style='margin:10px 0;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if (isset($args['align'])) {
						echo " style='text-align:".$args['align'].";";
					}
				echo "'>\n";
					if (isset($args['label'])) echo "<span class='sys_form_label'><label>".$args['label']."</label></span>\n";
					if (isset($args['prefix'])) echo $args['prefix'];
					echo "<input type='submit'" ;
						if (isset($args['name'])) echo " name='".$args['name']."'";
						if (isset($args['id'])) echo " id='".$args['id']."'";
						if (isset($args['value'])) echo " value='".str_replace("'","&#039;",$args['value'])."'";
						echo " style='margin:2px 0;";
						if (isset($args['style'])) echo " ".$args['style'];
						echo "' class='";
							echo (isset($args['class'])) ? $args['class'] : "sys_form_button" ;
							echo "'";
						if (isset($args['extra'])) echo " ".$args['extra'];
					echo " />\n";
					if (isset($args['suffix'])) echo $args['suffix'];
					if (isset($args['info'])) echo "<span class='sys_form_info'>".$args['info']."</span>\n";
				echo "</p>\n";
				break;
				
			case "reset":
				echo "<p";
					if ($this->inline || isset($args['inline'])) {
						echo " style='display:inline;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if ($this->VSpacer) {
						echo " style='margin:10px 0;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if (isset($args['align'])) {
						echo " style='text-align:".$args['align'].";";
					}
				echo "'>\n";
					if (isset($args['label'])) echo "<span class='sys_form_label'><label>".$args['label']."</label></span>\n";
					if (isset($args['prefix'])) echo $args['prefix'];
					echo "<input type='reset'" ;
						if (isset($args['name'])) echo " name='".$args['name']."'";
						if (isset($args['id'])) echo " id='".$args['id']."'";
						if (isset($args['value'])) echo " value='".str_replace("'","&#039;",$args['value'])."'";
						echo " style='margin:2px 0;";
						if (isset($args['style'])) echo " ".$args['style'];
						echo "' class='";
							echo (isset($args['class'])) ? $args['class'] : "sys_form_button" ;
							echo "'";
						if (isset($args['extra'])) echo " ".$args['extra'];
					echo " />\n";
					if (isset($args['suffix'])) echo $args['suffix'];
					if (isset($args['info'])) echo "<span class='sys_form_info'>".$args['info']."</span>\n";
				echo "</p>\n";
				break;
			
			case "submit_and_reset":
				if (isset($args['captcha']) && $config_sys['captcha']==1) {
					echo "<p";
						if ($this->VSpacer) echo " style='margin:10px 0;";
						if ($this->inline) echo "display:inline;";
					echo "'>\n";
					Captcha::Display();
					echo "</p>\n";
				}
				echo "<p";
					if ($this->inline || isset($args['inline'])) {
						echo " style='display:inline;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if ($this->VSpacer) {
						echo " style='margin:10px 0;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if (isset($args['align'])) {
						echo " style='text-align:".$args['align'].";";
					}
				echo "'>\n";
					if (isset($args['label'])) echo "<span class='sys_form_label'><label>".$args['label']."</label></span>\n";
					//Submit
					if (isset($args['prefix'])) echo $args['prefix'];
					echo "<input type='submit'" ;
						if (isset($args['s_name'])) echo " name='".$args['s_name']."'";
						if (isset($args['s_id'])) echo " id='".$args['s_id']."'";
						if (isset($args['s_value'])) echo " value='".str_replace("'","&#039;",$args['s_value'])."'";
						echo " style='margin:2px 0;";
						if (isset($args['s_style'])) echo " ".$args['s_style'];
						echo "' class='";
							echo (isset($args['s_class'])) ? $args['s_class'] : "sys_form_button" ;
							echo "'";
						if (isset($args['s_extra'])) echo " ".$args['s_extra'];
					echo " /> \n";
					//Reset
					echo "<input type='reset'" ;
						if (isset($args['r_name'])) echo " name='".$args['r_name']."'";
						if (isset($args['r_id'])) echo " id='".$args['r_id']."'";
						if (isset($args['r_value'])) echo " value='".str_replace("'","&#039;",$args['r_value'])."'";
						echo " style='margin:2px 0;";
						if (isset($args['r_style'])) echo " ".$args['r_style'];
						echo "' class='";
							echo (isset($args['r_class'])) ? $args['r_class'] : "sys_form_button" ;
							echo "'";
						if (isset($args['r_extra'])) echo " ".$args['r_extra'];
					echo " />\n";
					if (isset($args['suffix'])) echo $args['suffix'];
					if (isset($args['info'])) echo "<span class='sys_form_info'>".$args['info']."</span>\n";
				echo "</p>\n";
				break;
			
			case "button":
				if (isset($args['captcha']) && $config_sys['captcha']==1) {
					echo "<p";
						if ($this->VSpacer) echo " style='margin:10px 0;";
						if ($this->inline) echo "display:inline;";
					echo "'>\n";
					Captcha::Display();
					echo "</p>\n";
				}
				echo "<p";
					if ($this->inline || isset($args['inline'])) {
						echo " style='display:inline;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if ($this->VSpacer) {
						echo " style='margin:10px 0;";
						if (isset($args['align'])) echo " text-align:".$args['align'].";";
					} else if (isset($args['align'])) {
						echo " style='text-align:".$args['align'].";";
					}
				echo "'>\n";
					if (isset($args['label'])) echo "<span class='sys_form_label'><label>".$args['label']."</label></span>\n";
					if (isset($args['prefix'])) echo $args['prefix'];
					echo "<input type='button'" ;
						if (isset($args['name'])) echo " name='".$args['name']."'";
						if (isset($args['id'])) echo " id='".$args['id']."'";
						if (isset($args['value'])) echo " value='".str_replace("'","&#039;",$args['value'])."'";
						echo " style='margin:2px 0;";
						if (isset($args['style'])) echo " ".$args['style'];
						echo "' class='";
							echo (isset($args['class'])) ? $args['class'] : "sys_form_button" ;
							echo "'";
						if (isset($args['extra'])) echo " ".$args['extra'];
					echo " />\n";
					if (isset($args['suffix'])) echo $args['suffix'];
					if (isset($args['info'])) echo "<span class='sys_form_info'>".$args['info']."</span>\n";
				echo "</p>\n";
				break;
		}
	}
	
	function Close() {
		if ($this->action) {
			//Token
			if ($this->token) {
				$tok = Utils::GenerateToken();
				$tok = explode(":",$tok);
				
				echo "<input name='ctok' type='hidden' value='".$tok[0]."' />\n";
				echo "<input name='ftok' type='hidden' value='".$tok[1]."' />\n";
			}
			echo "</form>\n";
		}
	}
}

//Initialize extension
global $Ext;
if (!$Ext->InitExt("Form")){class Form extends BaseForm{}}

?>