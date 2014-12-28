{if isset($plugin_pagination) && sizeof($plugin_pagination)}
	<div class="tpl_pagination" style="margin-top:10px;">
    	{* LABEL *}
        {if isset($plugin_pagination.label)}<span class="tpl_rounded">{$plugin_pagination.label}</span>{/if}
    	
    	{* FIRST *}
        {if isset($plugin_pagination.first)}<span class="tpl_rounded">{$plugin_pagination.first}</span>{/if}
        
        {* -1 *}
        {if isset($plugin_pagination.minus_one)}<span class="tpl_rounded">{$plugin_pagination.minus_one}</span>{/if}
        
        {* PREVIOUS *}
        {if isset($plugin_pagination.prev)}
            {foreach item=value from=$plugin_pagination.prev name=pgn}
                <span class="tpl_rounded">{$value}</span>
            {/foreach}
        {/if}
    	
        {* PAGE *}
        {if isset($plugin_pagination.page)}<span class="tpl_rounded hl">{$plugin_pagination.page}</span>{/if}
        
        {* NEXT *}
        {if isset($plugin_pagination.next)}
            {foreach item=value from=$plugin_pagination.next name=pgn}
                <span class="tpl_rounded">{$value}</span>
            {/foreach}
        {/if}
        
        {* +1 *}
        {if isset($plugin_pagination.plus_one)}<span class="tpl_rounded">{$plugin_pagination.plus_one}</span>{/if}
        
        {* LAST *}
        {if isset($plugin_pagination.last)}<span class="tpl_rounded">{$plugin_pagination.last}</span>{/if}
    </div>
    <div style="clear:both;"></div>
{/if}

{if isset($plugin_pagination_label)}
	<div class="tpl_pagination" style="margin-top:10px;">
		{$plugin_pagination_label}
	</div>
    <div style="clear:both;"></div>
{/if}