<div class="tpl_head">
	<div class="tpl_inner">
    	<div style="float:left;">
	    	{if $sys_events>0}
    	        <img src="admin/templates/memht/icons/star.png" alt="New events" style="vertical-align:text-bottom;" /> <a href="admin.php?cont=internal&amp;op=events" style="margin-right:15px;">{t 1=NEW_EVENTS}</a>
            {/if}
            
            {if $sys_waitusers>0}
				<img src="admin/templates/memht/icons/user.png" alt="Users waiting for activation" style="vertical-align:text-bottom;" /> <a href="admin.php?cont=user">{t 1=USERS_WAIT_ACT}</a>            
            {/if}
        </div>
        <div style="text-align:right;"><img src="admin/templates/memht/icons/user.png" alt="Welcome" style="vertical-align:text-bottom;" /> <strong>{$sys_user.name}</strong> - <a href="admin.php?logout=true">{t 1=LOGOUT}</a></div>
    </div>
</div>
<div class="tpl_head_logo">
	<div class="tpl_inner"><img src="admin/templates/memht/images/tpl_head_logo.png" alt="{$sys_site_name}" title="{$sys_site_name}" width="299" height="122" /></div>
</div>

<div class="tpl_bar">
    <!-- i -->
    <div id="myslidemenu" class="jqueryslidemenu">
        <ul>
            <li class="top"><a href="javascript:void(0);"><img src="admin/templates/memht/icons/folder.png" alt="General" style="vertical-align:text-bottom;" /> {t 1=GENERAL}</a>
                <ul>
                    <li class="sub"><a href="{$sys_site_url}"><img src="admin/templates/memht/icons/home.png" alt="Site home" style="vertical-align:text-bottom;" /> {t 1=HOME}</a></li>
                    <li class="sub"><a href="admin.php"><img src="admin/templates/memht/icons/dashboard.png" alt="Admin dashboard" style="vertical-align:text-bottom;" /> {t 1=ADMINISTRATION}</a></li>
                    <li class="sub"><a href="admin.php?logout=true"><img src="admin/templates/memht/icons/logout.png" alt="Logout" style="vertical-align:text-bottom;" /> {t 1=LOGOUT}</a></li>
                </ul>
            </li>
       
            {if isset($sys_acpmenu)}
            	{*Thanks to Paulo Ferreira for the idea and the original code*}
				{foreach item=value from=$sys_acpmenu}
					<li class="top"><a href="{$value.main.url}"><img src="admin/templates/memht/icons/{$value.main.icon}" alt="{$value.main.title}" style="vertical-align:text-bottom;" /> {$value.main.title}</a>
                		<ul>
							{foreach item=subvalue from=$value.sub}
								<li class="sub"><a href="{$subvalue.url}"><img src="admin/templates/memht/icons/{$subvalue.icon}" alt="{$subvalue.title}" style="vertical-align:text-bottom;" /> {$subvalue.title}</a></li>
							{/foreach}
                		</ul>
                	</li>
				{/foreach}            
            {/if}
       
            <li class="top"><a href="javascript:void(0);"><img src="admin/templates/memht/icons/help.png" alt="Help" style="vertical-align:text-bottom;" /> Help</a>
                <ul>
                    <li class="sub"><a href="http://www.memht.com" rel="external"><img src="admin/templates/memht/icons/home.png" alt="MemHT Portal" style="vertical-align:text-bottom;" /> MemHT Portal</a></li>
                    <li class="sub"><a href="http://docs.memht.com" rel="external"><img src="admin/templates/memht/icons/docs.png" alt="Documents" style="vertical-align:text-bottom;" /> Documents</a></li>
                    <li class="sub"><a href="http://forums.memht.com" rel="external"><img src="admin/templates/memht/icons/forum.png" alt="Forums" style="vertical-align:text-bottom;" /> Forums</a></li>
                    <li class="sub"><a href="admin.php?cont=internal&amp;op=updates"><img src="admin/templates/memht/icons/update.png" alt="Check for updates" style="vertical-align:text-bottom;" /> Check for updates</a></li>
<li class="sub"><a href="admin.php?cont=internal&amp;op=phpinfo"><img src="admin/templates/memht/icons/settings.png" alt="PHP Info" style="vertical-align:text-bottom;" /> PHP Info</a></li>
                </ul>
            </li>
        </ul>
        <br style="clear: left" />
    </div>
    <!-- e -->
</div>
<div class="tpl_bar_opt"><div class="tpl_inner"></div></div>

<div class="tpl_main">
	<div class="tpl_inner">
    	{$sys_main.content}
	</div>
</div>

<div class="tpl_foot">
	<div class="tpl_inner">
        <div style="text-align:center;"><img src="admin/templates/memht/images/tpl_foot_line.png" alt="-" width="725" height="26" /><div style="font-size:10px;">{$sys_memht}</div></div>
    </div>
</div>