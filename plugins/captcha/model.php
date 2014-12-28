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

class captchamodel extends Views {
	public function GenerateImage() {
		global $config_sys;

		header("content-type: image/jpeg");
		header("cache-control: no-cache,no-store"); 
		
		$width = 150;
		$height = 40;
		
		//create the image
		$im = imagecreatetruecolor($width,$height);
		
		//create colors
		$white = imagecolorallocate($im,255,255,255);
		$grey = imagecolorallocate($im,50,50,50);
		$black = imagecolorallocate($im,0,0,0);
		$red = imagecolorallocate($im,255,0,0);
		$green = imagecolorallocate($im,0,255,0);
		$blue = imagecolorallocate($im,0,0,255);
		
		//Background color
		ImageFill($im,0,0,imagecolorallocate($im,mt_rand(50,250),mt_rand(50,250),mt_rand(50,250)));
		
		//Background lines
		imagesetthickness($im,$height/5);
		for($i=0; $i<$width*$height/200; $i++) {
			$color = imagecolorallocate($im,mt_rand(50,250),mt_rand(50,250),mt_rand(50,250));
			imageline($im,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width),mt_rand(0,$height),$color);
		}
		
		$distorsion_hor = 1;
		$distorsion_ver = 1;
		$charactercolormode = 0; //0 = black,1 = random
		$charactersizemode = 1;
		$characterrotationmode = 0;
		$codelength = 7;		
		$securitycode = Utils::GenerateRandomString(7,"23456789acdefikLnprsvxyz");
		$font = "plugins"._DS."captcha"._DS."sans.ttf";
		$fontsize = $height * 0.78;

		//Chars
		$x = $width/30;
		for ($i = 0; $i < $codelength; $i++) {
			$color = $black;
			if ($charactercolormode>0) $color = imagecolorallocate($im,mt_rand(0,250),mt_rand(0,250),mt_rand(0,250));
			$textbox = imagettfbbox($fontsize,0,$font,$securitycode[$i]);
			$y = ($height - $textbox[5])/2;
			$w = abs($textbox[4] - $textbox[0]);
			$size = mt_rand($fontsize*$charactersizemode,$fontsize);
			$angle = mt_rand(-$characterrotationmode,$characterrotationmode);
			$y = $height - $height/4;
			$x = $x + $w*1.05;
			ImageTtfText($im,$size,$angle,$x-$w,$y,$color,$font,$securitycode[$i]);
		}
		
		//Squares
		imagesetthickness($im,1);
		for($i = 0; $i <= $width; $i += $height/5) @ImageLine($im,$i,0,$i,$height,$black);
		for($i = 0; $i <= $height; $i += $height/5) @ImageLine($im,0,$i,$width,$i,$black);
		
		//Image distortion
		$ampl_y = 5;
		$freq_y = 10;
		
		$ampl_x = 5;
		$freq_x = 10;
		
		$xx = 0;
		$yy = 0;
		if ($distorsion_ver>0) for ($i=0;$i<$width;$i+=2) imagecopy($im,$im,$xx+$i-2,$yy+sin($i/$freq_y)*$ampl_y,$xx+$i,$yy,2,$height);
		if ($distorsion_hor>0) for ($i=0;$i<$height;$i+=1) imagecopy($im,$im,$xx+sin($i/$freq_x)*$ampl_x,$yy+$i-1,$xx,$yy+$i,$width,1);
		
		imagepng($im);
		imagedestroy($im);

		Io::SetSession("fcap",md5(MB::strtolower($securitycode).$config_sys['uniqueid']));
	}
}

?>