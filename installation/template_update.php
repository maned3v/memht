<?php

function template_header($step) {
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" dir="ltr">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>MemHT Installer</title>
	
	<link rel="stylesheet" href="../libraries/MemHT/style/common.css" type="text/css" />
	<link rel="stylesheet" href="../admin/templates/memht/style.css" type="text/css" />
	<script src="../libraries/jQuery/jquery.js" type="text/javascript" charset="utf-8"></script>
	<script src="../libraries/MemHT/common.js" type="text/javascript" charset="utf-8"></script>
	<link type="text/css" href="../libraries/jQuery/plugins/ui/css/redmond/jquery-ui.css" rel="Stylesheet" />
    <!--<script type="text/javascript" src="../libraries/jQuery/plugins/ui/js/jquery-ui.js"></script>-->
    
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
    
    <style type="text/css">
    	.steps {
    		margin:0;
    		padding:0;
    		list-style:none;
    		border-left: 1px solid #74A4CB;
    	}
    	.steps li {
    		float:left;
    		background:url(../admin/templates/memht/images/tpl_bar_h.png) repeat-x #FFF;
    		padding:10px 15px 9px 15px;
    		border-right: 1px solid #74A4CB;
    		color:#456882;
    	}
    	.steps li.sel {
    		color:#FFF;
    		text-shadow:1px 1px 1px #74A4CB;
    	}
    	.info {
    		margin:10px 0;
    		padding:10px;
    		border:1px solid #FFCC72;
    		background-color:#FFF6E5;
    		color:#A06600;
    		clear:both;
    	}
    	.lilstep {
    		margin:10px 0;
    		padding:10px;
    		border:1px solid #7FAD7B;
    		background-color:#C8EDC5;
    		color:#3D8037;
    		clear:both;
    	}
    	.error {
    		margin:10px 0;
    		padding:10px;
    		border:1px solid #D11717;
    		background-color:#FFDBDB;
    		color:#D11717;
    		clear:both;
    	}
    	.ok {
    		color:#579629;
    	}
    	.bad {
    		color:#C31B1B;
    	}
    </style>
	
	</head>
	<body>
	<div class="tpl_head">
		<div class="tpl_inner">
	    	<div style="float:left;">MemHT Updater</div>
	        <div style="text-align:right;">5.0.1.0</div>
	    </div>
	</div>
	<div class="tpl_head_logo">
		<div class="tpl_inner"><img src="../admin/templates/memht/images/tpl_head_logo.png" alt="MemHT Installer" title="MemHT Installer" width="299" height="122" /></div>
	</div>
	
	<div class="tpl_bar">
		<div>
			<ul class="steps">
				<li<?php if ($step==0) { echo " class='sel'"; } ?>>License</li>
				<li<?php if ($step==1) { echo " class='sel'"; } ?>>Installed version</li>
				<li<?php if ($step==2) { echo " class='sel'"; } ?>>Tables</li>
				<li<?php if ($step==3) { echo " class='sel'"; } ?>>Finish</li>
			</ul>
        </div>
    </div>
	</div>
	
	<?php
	switch ($step) {
		default:
		case 0: $message = "MemHT Portal is a free software released under the GNU/GPLv2 License by Miltenovikj Manojlo";
			break;
		case 1: $message = "Trying to detect the installed version";
			break;
		case 2: $message = "Database structure and data update";
			break;
		case 3: $message = "Update finished";
			break;
	}
	?>
	
	<div class="tpl_bar_opt"><div class="tpl_inner"><?php echo $message; ?></div></div>
	
	<div class="tpl_main">
		<div class="tpl_inner">
		<?php
}

function template_footer() {
		?>
		</div>
	</div>
	<div class="tpl_foot">
		<div class="tpl_inner">
	        <div style="text-align:center;"><img src="../admin/templates/memht/images/tpl_foot_line.png" alt="-" width="725" height="26" /><div style="font-size:10px;">Powered by <a href='http://www.memht.com' title='MemHT' rel='external'>MemHT</a></div></div>
	    </div>
	</div>
	</body>
	</html>
	<?php
}

?>