<div class="tpl_head_menu">
	<div class="tpl_inner">
        <!-- MENU: HEAD begin -->
        <ul>
            {foreach item=value from=$sys_menu.head}
                <li><a href="{$value.url}" title="{$value.title}"><span>{$value.title}</span></a></li>
                <li class="spacer">&nbsp;</li>
            {/foreach}
        </ul>
        <!-- MENU: HEAD end -->
    </div>
</div>
<div class="tpl_head_logo">
	<div class="tpl_inner">
    	<img src="{$sys_site_url}/templates/{$sys_template}/images/tpl_head_logo.png" alt="{$sys_site_name}" title="{$sys_site_name}" width="299" height="122" style="float:left;" />
        <div style="text-align:right; padding-top:30px;">{if isset($sys_adv_head)}{$sys_adv_head}{/if}</div>
    </div>
</div>
<div class="tpl_head_bar">
	<div class="tpl_inner">
    <div style="float:left;">
        <!-- HEAD: BREADCRUMBS begin -->
        <div class="tpl_breadcrumbs"><strong>{t 1=YOURE_IN}:</strong> <img src="{$sys_site_url}/templates/{$sys_template}/images/home.gif" alt="{t 1=HOME}" title="{t 1=HOME}" width="9" height="9" /> {$sys_breadcrumbs}</div>
        <!-- HEAD: BREADCRUMBS end -->
        <!-- MAIN: TITLE begin -->
        {if $sys_main.showtitle==1 && isset($smarty.get.$sys_node)}
            <div class="tpl_pagetitle">
            {if !empty($sys_main.url)}
                <a href='{$sys_main.url}' title='{$sys_main.title}'>{$sys_main.title}</a>
            {else}
                {$sys_main.title}
            {/if}
            </div>
        {/if}
        <!-- MAIN: TITLE end -->
    </div>
    <div class="headone">{if isset($sys_sticker_one)}{$sys_sticker_one.content}{/if}</div>
    </div>
</div>
<div class="tpl_main">
	<div class="tpl_inner">
	<!-- MAIN: CONTENT begin -->
    	{if $sys_layout.nav==1 && $sys_layout.extra==1}
        	<div class="tpl_content" style="width:510px;">
        {elseif ($sys_layout.nav==1 && $sys_layout.extra==0) || ($sys_layout.nav==0 && $sys_layout.extra==1)}
        	<div class="tpl_content" style="width:710px;">
        {else}
        	<div class="tpl_content" style="width:910px;">
        {/if}
            <!-- CONTENT begin -->
                {if !empty($sys_main.before)} <div class="tpl_content_before tpl_rounded">{$sys_main.before}</div> {/if}
                <div>
                {$sys_main.content}
                {if isset($sys_main_additional)}
                    {foreach item=value from=$sys_main_additional}
                        {if file_exists("`$smarty.const._PATH_TEMPLATES``$smarty.const._DS`$sys_template`$smarty.const._DS`$value.tpl")}
                            {include file="`$smarty.const._PATH_TEMPLATES``$smarty.const._DS`$sys_template`$smarty.const._DS`$value.tpl"}
                        {/if}
                    {/foreach}
                {/if}
                </div>
                {if !empty($sys_main.after)} <div class="tpl_content_after tpl_rounded">{$sys_main.after}</div> {/if}
            <!-- CONTENT end -->
        </div>
        {if $sys_layout.extra==1}
			<div class="tpl_extra">
            	<!-- BLOCKS: EXTRA begin -->
                {foreach item=value from=$sys_blocks.extra}
                	{if isset($value.options.highlight) && $value.options.highlight>0}
                	<div class="tpl_blocks_1">
                    {else}
                    <div class="tpl_blocks">
                    {/if}
                    	<div class="tpl_blocks_inner">
                        	{if !empty($value.title)}
                        	<div class="tpl_blocks_title"><div>{$value.title}</div></div>
                            {/if}
                            <div class="tpl_blocks_body">{$value.content}</div>
                        </div>
                    </div>
                {/foreach}
                <!-- BLOCKS: EXTRA end -->
            </div>
        {/if}
        {if $sys_layout.nav==1}
			<div class="tpl_nav">
            	<!-- MENU: NAV begin -->
                <div class="tpl_blocks">
                  	<div class="tpl_blocks_inner">
                    	<div class="tpl_blocks_title"><div>{t 1=NAVIGATOR}</div></div>
                        <div class="tpl_blocks_body">
                        {foreach item=value from=$sys_menu.nav}
                            <div class="tpl_nav_item"><a href="{$value.url}" title="{$value.title}">{$value.title}</a></div>
                        {/foreach}
                        </div>
                    </div>
                </div>
                <!-- MENU: NAV end -->
                
                {if isset($sys_adv_nav)}<div style="margin-bottom:8px;">{$sys_adv_nav}</div>{/if}
                
            	<!-- BLOCKS: NAV begin -->
                {foreach item=value from=$sys_blocks.nav}
                    {if isset($value.options.highlight) && $value.options.highlight>0}
                	<div class="tpl_blocks_1">
                    {else}
                    <div class="tpl_blocks">
                    {/if}
                    	<div class="tpl_blocks_inner">
                        	{if !empty($value.title)}
                        	<div class="tpl_blocks_title"><div>{$value.title}</div></div>
                            {/if}
                            <div class="tpl_blocks_body">{$value.content}</div>
                        </div>
                    </div>
                {/foreach}
                <!-- BLOCKS: NAV end -->
            </div>
        {/if}
    	<div style="clear:both;"></div>
    <!-- MAIN: CONTENT end -->
	</div>
</div>
<div class="tpl_foot">
	<div class="tpl_inner">
        <!-- FOOTER begin -->
            {if isset($sys_sticker_footone)}
            	<div>
            	<strong>{$sys_sticker_footone.title}</strong><br />
            	{$sys_sticker_footone.content}
                </div>
            {/if}
        <!-- FOOTER end -->
        <div style="text-align:center;"><img src="{$sys_site_url}/templates/{$sys_template}/images/tpl_foot_line.png" alt="-" width="725" height="26" />{if !empty($sys_copyright)}<div style="font-size:10px;">{$sys_copyright}</div>{/if}<div style="font-size:10px;">{$sys_memht}</div></div>
    </div>
</div>
{*
    <!-- MENU: NAV begin -->
    {foreach item=value from=$sys_menu_nav}
		<div style="margin:6px 0;"><a href="{$value.url}" title="{$value.title}">{$value.title}</a></div>
	{/foreach}
    <!-- MENU: NAV end -->
    
    
    
    <div style="float:left; background-color:#66FF66; width:80%;">
        <div style="float:left; background-color:#66FF66;">
        <!-- CONTENT begin -->
            {if !empty($sys_main.before)} <div>{$sys_main.before}</div> {/if}
            <div>
            {$sys_main.content}
            {if isset($sys_main_additional)}
                {foreach item=value from=$sys_main_additional}
                    {if file_exists("`$smarty.const._PATH_TEMPLATES``$smarty.const._DS`$sys_template`$smarty.const._DS`$value.tpl")}
                        {include file="`$smarty.const._PATH_TEMPLATES``$smarty.const._DS`$sys_template`$smarty.const._DS`$value.tpl"}
                    {/if}
                {/foreach}
            {/if}
            </div>
            {if !empty($sys_main.after)} <div>{$sys_main.after}</div> {/if}
        <!-- CONTENT end -->
        </div>
        <div style="float:right; width:25%; background-color:#00CCFF;">extra</div>
    </div>
    <div style="width:20%; margin-left:80%; background-color:#FFFFCC;">nav</div>
    <div style="clear:both;"></div>
*}