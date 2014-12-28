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

class UploadImg {
	//Upload path
	var $path = "assets/";
	
	//File max weight
	var $max_size = 512000; //bytes
	
	//Show errors
	var $show_errors = true;
	
	//Overwrite files with the same name
	var $overwrite = true;
	
	//File field
	var $field = "file";
	
	//CHMOD
	var $chmod_fix = true;
	var $chmod_val = "0775";
	
	//===============================
	
	//Image type
	var $imgtype = array(IMAGETYPE_GIF,
						 IMAGETYPE_JPEG,
						 IMAGETYPE_PNG);
	var $imgmime = array('gif','jpeg','png');
	var $imgext = array('gif','jpeg','jpg','png');

	//Images max size
	var $max_w = 800;
	var $max_h = 600;
	
	//Resize images if too large
	var $resize = true;
	var $resize_w = 800;
	var $resize_h = 600;
	
	//Thumbnail
	var $create_thumb = false;
	var $thumb_path = "assets/";
	var $thumb_suffix = "_thumb";
	var $thumb_w = 100;
	var $thumb_h = 100;
	
	//===============================
	// Internal variables
	//===============================
	var $FILES = array();
	var $errors = array();
	var $filename;
	var $thumbname;
	var $gis = array();
	var $ext;
	
	//Check if the selected path is writable
	private function WritablePath() {
		if (!is_writable($this->path)) {
			if ($this->chmod_fix) {
				if (@chmod($this->path,$this->chmod_val)) {
					return true;
				} else {
					$this->errors[] = _t("FOLDER_NOT_WRITABLE");
					return false;
				}
			} else {
				$this->errors[] = _t("FOLDER_NOT_WRITABLE");
				return false;
			}
		}
	}

	public function Selected() {
		if (isset($this->FILES[$this->field]) && $this->FILES[$this->field]['size']>0) {
			return true;
		} else {
			$this->errors[] = _t("NO_IMAGE_SELECTED");
			return false;
		}
	}
	
	//File weight check
	private function FileSize() {
		if ($this->FILES[$this->field]['size']<=$this->max_size) {
			return true;
		} else {
			$this->errors[] = _t("FILE_TOO_LARGE_X_MAX",($this->max_size/1024));
			return false ;
		}
	}

	private function GetFileName() {
		$ext = pathinfo($this->FILES[$this->field]['name']);
		$this->ext = MB::strtolower($ext['extension']);

		$filename = Utils::GenerateRandomString(10).".".$this->ext;
		if (!empty($filename)) {
			if (file_exists($this->path.$filename)) {
				if ($this->overwrite) {
					@unlink($this->path.$filename);
					return $filename;
				} else {
					$this->errors[] = _t("FILE_ALREADY_EXISTS");
					return false;
				}
			} else {
				return $filename;
			}
		} else {
			$this->errors[] = _t("FILENAME_NOT_ACCEPTED");
			return false;
		}
	}

	//Image type check
	private function ImageTypeCheck() {
		$this->gis = @getimagesize($this->FILES[$this->field]["tmp_name"]);
		if (isset($this->gis[2]) && in_array($this->gis[2],$this->imgtype) && in_array($this->ext,$this->imgext)) {
			return true;
		} else {
			$this->errors[] = _t("FILE_TYPE_NOT_ACCEPTED_X",implode(", ",$this->imgmime));
			return false;
		}
	}

	//Image size check
	private function ImageSizeCheck() {
		if (file_exists($this->FILES[$this->field]["tmp_name"])) {
			if (!isset($this->gis[0]) || !isset($this->gis[1]) || !isset($this->gis['bits'])) {
				$this->errors[] = _t("FILE_TYPE_NOT_ACCEPTED_X",implode(", ",$this->imgmime));
				return false;
			}
			if (($this->gis[0]<=$this->max_w && $this->gis[1]<=$this->max_h) || $this->resize) {
				return true;
			} else {
				$this->errors[] = _t("IMAGE_TOO_LARGE_WxH_MAX",$this->max_w."x".$this->max_h);
				return false;
			}
		} else {
			$this->errors[] = _t("INTERROR","LMU_isc001");
			return false;
		}
	}
	
	private function FileUploaded() {
		if(is_uploaded_file($this->FILES[$this->field]["tmp_name"])) {
			if (@move_uploaded_file($this->FILES[$this->field]["tmp_name"],$this->path.$this->filename)) {
				return true;
			} else {
				$this->errors[] = _t("FILE_NOT_UPLOADED_CHECK_PRIVS");
				return false;
			}
		} else {
			$this->errors[] = _t("FILE_NOT_UPLOADED_CHECK_PRIVS");
			return false;
		}
	}
	
	private function ResampleImage() {
		$imagesize = getimagesize($this->path.$this->filename);
		if ($imagesize[0]>$this->max_w || $imagesize[1]>$this->max_h) {
			if ($this->resize) {
				$target_w = $this->resize_w;
				$target_h = $this->resize_h;
			} else {
				$this->errors[] = _t("IMAGE_TOO_LARGE_WxH_MAX",$this->max_w."x".$this->max_h);
				return false;
			}
		} else {
			$target_w = $imagesize[0];
			$target_h = $imagesize[1];
		}

		switch ($this->FILES[$this->field]['type']) {
			case "image/gif":
				$img = @imagecreatefromgif($this->path.$this->filename);
				if (!isset($imagesize[2]) || $imagesize[2]!=1) $img = false;
				break;
			case "image/jpeg":
				$img = @imagecreatefromjpeg($this->path.$this->filename);
				if (!isset($imagesize[2]) || $imagesize[2]!=2) $img = false;
				break;
			case "image/png":
				$img = @imagecreatefrompng($this->path.$this->filename);
				if (!isset($imagesize[2]) || $imagesize[2]!=3) $img = false;
				break;
			default:
				$img = false;
				break;
		}

		if ($img) {
			if ($imagesize[0] > $imagesize[1]) {
				$ratio = ($target_w/$imagesize[0]);
				$new_w = round($imagesize[0]*$ratio);
				$new_h = round($imagesize[1]*$ratio);
			} else {
				$ratio = ($target_h/$imagesize[1]);
				$new_w = round($imagesize[0]*$ratio);
				$new_h = round($imagesize[1]*$ratio);
			}

			$new_img = imagecreatetruecolor($new_w,$new_h);
			imagecopyresampled($new_img,$img,0,0,0,0,$new_w,$new_h,$imagesize[0],$imagesize[1]);
			imagejpeg($new_img,$this->path.$this->filename,100);
			imagedestroy($new_img);
		} else {
			$this->errors[] = _t("FILE_TYPE_NOT_ACCEPTED_X",implode(", ",$this->imgmime));
			return false;
		}
	}
	
	private function CreateThumbnail() {
		if ($this->create_thumb) {
			$imagesize = getimagesize($this->path.$this->filename);			

			switch ($imagesize['mime']) {
				case "image/gif":
					$img = @imagecreatefromgif($this->path.$this->filename);
					if (!isset($imagesize[2]) || $imagesize[2]!=1) $img = false;
					break;
				case "image/jpeg":
					$img = @imagecreatefromjpeg($this->path.$this->filename);
					if (!isset($imagesize[2]) || $imagesize[2]!=2) $img = false;
					break;
				case "image/png":
					$img = @imagecreatefrompng($this->path.$this->filename);
					if (!isset($imagesize[2]) || $imagesize[2]!=3) $img = false;
					break;
				default:
					$img = false;
					break;
			}

			if ($img) {
				if ($imagesize[0] > $imagesize[1]) {
					$ratio = ($this->thumb_w/$imagesize[0]);
					$new_w = round($imagesize[0]*$ratio);
					$new_h = round($imagesize[1]*$ratio);
				} else {
					$ratio = ($this->thumb_h/$imagesize[1]);
					$new_w = round($imagesize[0]*$ratio);
					$new_h = round($imagesize[1]*$ratio);
				}

				$ext = pathinfo($this->path.$this->filename);
				$name = $ext['filename'].$this->thumb_suffix;
				$ext = MB::strtolower($ext['extension']);
				$this->thumbname = "$name.$ext";

				$new_img = imagecreatetruecolor($new_w,$new_h);
				imagecopyresampled($new_img,$img,0,0,0,0,$new_w,$new_h,$imagesize[0],$imagesize[1]);
				imagejpeg($new_img,$this->thumb_path.$this->thumbname,100);
				imagedestroy($new_img);
			} else {
				$this->errors[] = _t("FILE_TYPE_NOT_ACCEPTED_X",implode(", ",$this->imgmime));
				return false;
			}
			
		}
	}
	
	public function GetErrors() {
		return $this->errors;
	}
	
	public function Upload() {
		global $_FILES;
		
		$this->FILES = $_FILES;		
		$this->errors = array();
		$this->WritablePath();
		if (!sizeof($this->errors)) $this->Selected();
		if (!sizeof($this->errors)) $this->FileSize();
		if (!sizeof($this->errors)) $this->filename = $this->GetFileName();
		if (!sizeof($this->errors)) $this->ImageTypeCheck();
		if (!sizeof($this->errors)) $this->ImageSizeCheck();
		if (!sizeof($this->errors)) $this->FileUploaded();
		if (!sizeof($this->errors)) $this->ResampleImage();
		if (!sizeof($this->errors)) $this->CreateThumbnail();
		
		if (!sizeof($this->errors)) {
			return $this->filename;
		} else {
			@unlink($this->path.$this->filename);
			@unlink($this->FILES[$this->field]["tmp_name"]);
			return false;
		}
	}
}

?>