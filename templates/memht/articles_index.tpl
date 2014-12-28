{foreach item=value from=$plugin_index name=cnt}    
	<div class="tpl_blog_title">
    	{if $value.usecomments==1}
    	<div class="tpl_blog_com"><a href="index.php?{$smarty.const._NODE}={$smarty.const._PLUGIN}&amp;sec={$value.sname}&amp;cat={$value.cname}&amp;year={$value.year}&amp;month={$value.month}&amp;title={$value.name}#comments" title="{t 1=X_COMMENTS 2=$value.comments}" rel="tooltip">{$value.comments}</a></div>
        {/if}
        <div class="title">
            <a href="index.php?{$smarty.const._NODE}={$smarty.const._PLUGIN}&amp;sec={$value.sname}&amp;cat={$value.cname}&amp;year={$value.year}&amp;month={$value.month}&amp;title={$value.name}" title="{$value.title|CleanTitleAtr}"><strong>{$value.title}</strong></a>
        </div>
        <div class="tpl_blog_info">{t 1=WRITTEN_BY_X_ON_Y 2=$value._author 3=$value.created}</div>
    </div>
    
    {if isset($value.options.stickers.thumb_index) && !empty($value.options.stickers.thumb_index)}<img src="{$value.options.stickers.thumb_index}" alt="{$value.title|CleanTitleAtr}" title="{$value.title|CleanTitleAtr}" class="tpl_thumb_index" />{/if}

    {*    
    <div style="font-size:80%">{$value.info}</div>
    {if !empty($value.modified)} <div style="font-size:80%">{$value.modified.info}</div> {/if}
    <div style="font-size:80%">{t 1=RATING}: {$value.rating}{if $value.usecomments}, {t 1=X_COMMENTS 2=$value.comments}{/if}</div>
    *}
    
    <div class="tpl_blog_body">
        {$value.text}
        {if $value.more}
        	<div class="tpl_blog_more"><a href="index.php?{$smarty.const._NODE}={$smarty.const._PLUGIN}&amp;sec={$value.sname}&amp;cat={$value.cname}&amp;year={$value.year}&amp;month={$value.month}&amp;title={$value.name}" title="{$value.title|CleanTitleAtr}" rel="tooltip">{t 1=READ_MORE} &raquo;</a></div>
        {/if}
        
        {*
        <!-- ARTICLES: TAGS begin -->
        {if sizeof($value.tags)>0}
        	<div class="tpl_tags_box"><strong>{t 1=TAGS}:</strong> 
            {foreach item=tag from=$value.tags}
                <span class="tpl_tags"><a href="index.php?{$smarty.const._NODE}={$smarty.const._PLUGIN}&amp;op=related&amp;tag={$tag.name}" title="{$tag.title|CleanTitleAtr}">{$tag.title}</a></span>
            {/foreach}
            </div>
        {/if}
        <!-- ARTICLES: TAGS end -->
        *}
        
    </div>
    <div style="clear:both;"></div>
    <div class="tpl_blog_spacer"></div>
{/foreach}