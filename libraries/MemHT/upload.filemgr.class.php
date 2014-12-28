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

class UploadFile {
	
	//Upload path
	var $path = "assets/files/";
	
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

	var $anytype = false;

	//Image type
	var $ftype = array("image/jpeg",
						"image/gif",
						"image/pjpeg",
						"image/png",
						"application/zip",
						"application/x-zip",
						"application/x-zip-compressed",
						"application/octet-stream",
						"application/x-compress",
						"application/x-compressed",
						"application/force-download",
						"multipart/x-zip",
						"application/x-rar-compressed",
						"application/pdf",
						"application/x-pdf",
						"application/acrobat",
						"applications/vnd.pdf",
						"text/pdf",
						"text/x-pdf");


	var $fmime = array('gif','jpg','png','zip','rar','pdf');
	var $fext = array('gif','jpeg','jpg','png','zip','rar','pdf');
	
	//===============================
	// Internal variables
	//===============================
	var $FILES = array();
	var $errors = array();
	var $filename;
	var $ext;
	var $name;
	var $size;
	
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
			$this->errors[] = _t("NO_FILE_SELECTED");
			return false;
		}
	}
	
	//File weight check
	private function FileSize() {
		$this->size = $this->FILES[$this->field]['size'];
		if ($this->size<=$this->max_size) {
			return true;
		} else {
			$this->errors[] = _t("FILE_TOO_LARGE_X_MAX",($this->max_size/1024));
			return false ;
		}
	}

	private function GetFileName() {
		$ext = pathinfo($this->FILES[$this->field]['name']);
		$this->ext = MB::strtolower($ext['extension']);
		$this->name = Utils::GenerateRandomString(10);

		$filename = $this->name.".zip";
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

	//Type check
	private function TypeCheck() {
		$type = $this->FILES[$this->field]['type'];
		if ($this->anytype) {
			return true;
		} else if (in_array($type,$this->ftype) && in_array($this->ext,$this->fext)) {
			return true;
		} else {
			$this->errors[] = _t("FILE_TYPE_NOT_ACCEPTED_X",implode(", ",$this->fmime))." - ".$type;
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
	
	public function GetErrors() {
		return $this->errors;
	}
	
	public function Upload() {
		global $_FILES,$config_sys;
		
		$this->FILES = $_FILES;
		$this->errors = array();
		$this->path .= $config_sys['files_path']._DS;
		$this->WritablePath();
		if (!sizeof($this->errors)) $this->Selected();
		if (!sizeof($this->errors)) $this->FileSize();
		if (!sizeof($this->errors)) $this->filename = $this->GetFileName();
		if (!sizeof($this->errors)) $this->TypeCheck();
		if (!sizeof($this->errors)) $this->FileUploaded();
		
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