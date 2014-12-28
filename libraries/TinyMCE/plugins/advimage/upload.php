<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){

	$('.alerts').delay(2000).fadeOut('slow');

	$('#upload_form').submit(function(){
		
		$('.alert_info').css({display:'block'});
	});
});
</script>
<link href="css/advimage.css" rel="stylesheet" type="text/css" />
<?php
equire('config.php');

function get_folder_list(){
	$foldersDir = dir(IMG_PATH);
	$ignore = array( '.', '..' ); 
	while(false !== ($entry = $foldersDir->read())){
		if(!in_array($entry, $ignore)){
			$length = 18;
			$dirName = (strlen($entry) > $length) ? substr($entry, 0, $length).'..': $entry;
			if(is_dir(IMG_PATH.$entry)){
				$list .= '<option value=\''.$dirName.'\'>'.$dirName.'</option>';
			}
		}
	}
	$foldersDir->close();
	return $list;
}

if(isset($_POST['submit'])){
	if($_POST['dirOptions'] == null){
		echo '<div class=\'alerts alert_error\'>Du må velge en mappe!</div>';
	}else{
		$dir = $_POST['dirOptions'].'/';
		include ('class.upload.php');
		
		// params : thumb path, resized path, original path(deletes after session), image quality(0-100)
		$imgUpload = new imgUpload(
			IMG_PATH.$dir.substr(THUMB_DIR,1), 
			IMG_PATH.$dir, 
			IMG_PATH_TEMP, 
			IMAGE_QUALITY
		);
		// params : thumb width, resized width
		$imgUpload->imgSize(
			DEFAULT_THUMB_WIDTH, 
			$_POST['img_width']
		);
		
		$image = $imgUpload->re;
		@unlink(IMG_PATH_TEMP.$image); 
		echo '<div class=\'alerts alert_ok\'>Opplastningen er fullført!</div>';
	}
	echo $upl;
}else{
	echo $upl;
}

?>
<div class="alerts alert_info" style="display:none">Laster opp.. vennligst vent!</div>

<form action="upload.php" id="upload_form" method="post" name="dirSelector" enctype="multipart/form-data">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr> 
			<td class="column1"><label id="ch_folder" for="align">Velg mappe</label></td> 
			<td>
				<select id="dirOptions" name="dirOptions" class="selector">
					<option value="">..</option>
					<?php echo get_folder_list(); ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Velg bilde</td>
			<td><input type="file" name="vImage" id="vImage" /></td>
		</tr>
		<tr>
			<td>Bildebredde (px)</td>
			<td><input type="text" name="img_width" style="width:50px" id="img_width" value="<?php echo DEFAULT_IMG_WIDTH; ?>" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" id="submit" name="submit" value="Upload" /></td>
		</tr>
	</table>
</form>

<div class="info">
	<?php echo UPLOAD_INFO; ?>
</div>