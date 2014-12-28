{if $comments.info.status=='active'}
	{if $comments.info.comments>0}
        <div class="tpl_comments_title"><a name="comments">{t 1=X_COMMENTS 2=$comments.info.comments}</a></div>
        {if !empty($comments.info.retinfo)}
        <div class="tpl_comments_item tpl_rounded" style="background-color:#CCE8A8; text-align:center;">{$comments.info.retinfo}</div>
        {/if}
        {foreach item=value from=$comments.data name=com}
            {if $value.isadmin==1}
            <div id="comment{$value.id}" class="tpl_comments_item_h tpl_rounded" style="background-color: {cycle values='#CCE8A8,#DDF0C6'};">
            {else}
            <div id="comment{$value.id}" class="tpl_comments_item tpl_rounded" style="background-color: {cycle values='#CCE8A8,#DDF0C6'};">
            {/if}
                <div style="float:left; margin-right:8px;" class="tpl_comments_avatar">{$value.avatar}</div>
                <div class="tpl_comments_counter"><a name="comment{$value.id}" href="{$comments.info.url}#comment{$value.id}" title="{t 1=COMMENT} #{$value.id}" rel="tooltip">{$value.id}</a>
					{if $sys_user.isadmin>0}
					<a href="javascript:void(0);" onclick="javascript:delcomment('{$comments.info.item}','{$value.id}');" title="{t 1=DELETE}">{t 1=DELETE}</a>
					{/if}
				</div>
                <div><strong>
					{if $value.isguest==0}
						<a href="index.php?{$smarty.const._NODE}=user&amp;op=info&amp;id={$value.author}" title="{$value.author_name|CleanTitleAtr}">{$value.author_name}</a>
					{else}
						{if !empty($value.author_site)}
							<a href="{$value.author_site}" rel="external nofollow" title="{$value.author_name|CleanTitleAtr}">{$value.author_name}</a>
						{else}
							{$value.author_name}
						{/if}
					{/if}
				</strong></div>
                <div style="font-size:11px; margin-top:3px;">{$value.created}</div>
                <div class="tpl_comments_text tpl_rounded">{$value.text}</div>
            </div>
        {/foreach}
        {if file_exists("`$smarty.const._PATH_TEMPLATES``$smarty.const._DS`$sys_template`$smarty.const._DS`pagination.tpl")}
        	{include file="`$smarty.const._PATH_TEMPLATES``$smarty.const._DS`$sys_template`$smarty.const._DS`pagination.tpl"}
        {/if}
    <div class="tpl_comments_formtitle">{t 1=LEAVE_A_COMMENT}</div>
    {else}
    <div class="tpl_comments_formtitle"><a name="comments">{t 1=LEAVE_A_COMMENT}</a></div>
    {/if}
    <div class="tpl_comments_formbody tpl_rounded">
    {if $comments.info.canwrite>0}
      	<form method="post" action="{$comments.info.action}">
      	{if $sys_user.isuser==0}
     	<div><label><span class="title">{t 1=NAME}</span> <span class="info">{t 1=REQUIRED}</span></label><br /><input type="text" name="name" style="width:200px;" /></div>
        <div><label><span class="title">{t 1=EMAIL}</span> <span class="info">{t 1=REQUIRED}</span></label><br /><input type="text" name="email" style="width:200px;" /></div>
        <div><label><span class="title">{t 1=URL}</span></label><br /><input type="text" name="url" style="width:200px;" /></div>
        {/if}
        <div>
			<label><span class="title">{t 1=MESSAGE}</span> <span class="info">{t 1=REQUIRED}</span></label>
			<br /><textarea name="message" rows="5" cols="40" style="width:99%; height:150px;"></textarea></div>
        <div>
        {if $comments.info.moderated>0}<div style="font-size:10px;">{t 1=COMMENTS_MODERATED_BEFORE_PUBLISHED}</div>{/if}
        <div style="font-size:10px;">{t 1=BBCODE_FORMAT_MESSAGES}</div>
            <input type="submit" name="comment" value="{t 1=POST_COMMENT}" />
            <input type="hidden" name="item" value="{$comments.info.item}" />
            <input type="hidden" name="ctok" value="{$comments.info.ctok}" />
            <input type="hidden" name="ftok" value="{$comments.info.ftok}" />            
        </div>
        </form>
    {else}
	    <div style="text-align:center">{t 1=LOGIN_TO_WRITE_COMMENT}</div>
    {/if}
    </div>
{/if}