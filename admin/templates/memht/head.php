<link rel="stylesheet" type="text/css" href="<?php echo $config_sys['site_url']._DS; ?>admin<?php echo _DS; ?>templates<?php echo _DS.$config_sys['admincp_template']._DS; ?>jqueryslidemenu.css" />
<script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>admin<?php echo _DS; ?>templates<?php echo _DS.$config_sys['admincp_template']._DS; ?>jqueryslidemenu.js"></script>

<link type="text/css" href="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>ui<?php echo _DS; ?>css<?php echo _DS; ?>redmond<?php echo _DS; ?>jquery-ui.css" rel="Stylesheet" />
<script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>ui<?php echo _DS; ?>js<?php echo _DS; ?>jquery-ui.js"></script>
        
<link type="text/css" href="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>flexigrid<?php echo _DS; ?>css<?php echo _DS; ?>flexigrid<?php echo _DS; ?>flexigrid.css" rel="Stylesheet" />
<script type="text/javascript" src="<?php echo $config_sys['site_url']._DS; ?>libraries<?php echo _DS; ?>jQuery<?php echo _DS; ?>plugins<?php echo _DS; ?>flexigrid<?php echo _DS; ?>flexigrid.js"></script>
        		
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
		//Menu
		var arrowimages={
			down:['downarrowclass', '<?php echo $config_sys['site_url']._DS; ?>admin<?php echo _DS; ?>templates<?php echo _DS; ?>memht<?php echo _DS; ?>images<?php echo _DS; ?>down.gif', 23],
			right:['rightarrowclass', '<?php echo $config_sys['site_url']._DS; ?>admin<?php echo _DS; ?>templates<?php echo _DS; ?>memht<?php echo _DS; ?>images<?php echo _DS; ?>right.gif']
		}
		jqueryslidemenu.buildmenu("myslidemenu", arrowimages);

		//Round corners
		$('input,select,textarea').addClass('ui-corner-all');
	});
</script>