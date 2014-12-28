<?php

class imgUpload{
	/**
	* Image upload and resize class
	*
	* Author		: Kim Sandvold
	* Created		: 04.04.2009
	* Updated		: 25.06.2009
	* Requirements	: GD library, php 5 >
	*
	* The imgUpload class handles up to three images to upload.
	* Supported file types are jpg, png and gif. Also support for 
	* png's alpha channel.
	*/

	public $re;	
	public $defaultTh = 100;
	public $defaultRe = 500;
	public $defaultQuality = 75;

	public function __construct($thumb, $resized, $orig, $scale){
	
		$this->pathThumb = $thumb;
		$this->pathResized = $resized;
		$this->pathOrig = $orig;
		$this->imgQuality = ($scale != null) ? $scale: $this->defaultQuality; 
	}

	public function imgSize($thumbSize, $reSized){
	
		$this->sizeTh = ($thumbSize == null) ? $this->defaultTh : $thumbSize;
		$this->sizeRe = ($reSized == null) ? $this->defaultRe : $reSized;
		$this->engine();
	}

	private function filename(){
	
		$filenameOrig = explode('.',$_FILES['vImage']['name']);
		$suffix = $filenameOrig[1];
		$prefix = date('dmy_His');
		$this->imgName = $prefix.'.'.$suffix;
	}

	private function engine(){
	
		$this->filename();
		
        $source = $_FILES['vImage']['tmp_name'];
		$image_size = $_FILES['vImage']['size'];
		$image_type = $_FILES['vImage']['type'];
		
        $target = $this->pathOrig.$this->imgName;
        move_uploaded_file($source, $target);
              
        $imagepath = $this->imgName;
        $save = $this->pathResized . $imagepath; 
        $file = $this->pathOrig . $imagepath;

        list($width, $height) = getimagesize($file) ; 

        $modwidth = $this->sizeRe; 

        $diff = $width / $modwidth;
   
        $modheight = $height / $diff; 
        $tn = imagecreatetruecolor($modwidth, $modheight) ; 
		
		// png's alpha
		imagealphablending($tn, true);
		imagesavealpha($tn, true);
		$transparent = imagecolorallocatealpha($tn, 255, 255, 255, 0);
		imagefilledrectangle($tn, 0, 0, $modwidth, $modheight, $transparent);

		if($image_type == 'image/pjpeg' || $image_type == 'image/jpeg' || $image_type == 'image/jpeg' || $image_type == 'image/JPG' || $image_type == 'image/jpg'){
			$image = imagecreatefromjpeg($file); // for jpeg
		}else if($image_type == 'image/X-PNG' || $image_type == 'image/PNG' || $image_type == 'image/png' || $image_type == 'image/x-png'){
			$image = imagecreatefrompng($file); // for png
		}else if($image_type == 'image/gif' || $image_type == 'image/GIF'){
			$image = imagecreatefromgif($file); // for gif
		}else{
			echo 'Not supported imagetype!';
		}
		
        imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ; 
        imagejpeg($tn, $save, $this->imgQuality) ; 

        $save = $this->pathThumb. $imagepath;
        $file = $this->pathOrig . $imagepath;

		list($width, $height) = getimagesize($file) ; 

        $modwidth = $this->sizeTh; 

        $diff = $width / $modwidth;

        $modheight = $height / $diff; 
        $tn = imagecreatetruecolor($modwidth, $modheight) ; 

		// png's alpha
		imagealphablending($tn, true);
		imagesavealpha($tn, true);
		$transparent = imagecolorallocatealpha($tn, 255, 255, 255, 0);
		imagefilledrectangle($tn, 0, 0, $modwidth, $modheight, $transparent);

        imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ; 
		
        imagejpeg($tn, $save, $this->imgQuality) ; 
		
		$this->resize($imagepath);
	}

	private function resize($imagepath){
	
		return $this->re = $imagepath;
	}
}
?>