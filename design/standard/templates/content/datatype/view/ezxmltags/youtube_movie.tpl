{def $video = fetch( 'eztube', 'video', hash( 'video_id', $id, 'minimal', false ) ) }
<div class="eztube box">{$size}
	{set-block variable=dimensions}
		{switch match=$size}
		
		{case match="small"}
		width="320" height="240"
		{/case}
		
		{case match="large"}
		width="640" height="480"
		{/case}
		
		{case match="hd720"}
		width="960" height="720"
		{/case}
		
		{case}
		width="480" height="360"
		{/case}
		
		{/switch}
	{/set-block}
	<div class="attribute-short">
		<iframe title="{'YouTube video player'|i18n( 'fumaggo/design/eztube/video' )}" {$dimensions} src="http://www.youtube.com/embed/{$video.id|wash}" frameborder="0" allowfullscreen></iframe>	
	</div>

	<div class="break"></div>

	<div class="attribute-long">
		{set-block variable=profile_link}
			<a href={concat( "eztube/profile/", $video.user_name|wash )|ezurl} title="{$video.user_name|wash}">{$video.user_name|wash}</a>
		{/set-block}

		<br/>

		<div class="small">{'Uploaded by %1 on %2'|i18n('fumaggo/design/eztube/video',, array( $profile_link, $video.published|wash|l10n( shortdate ) ) )} | {'%1 views'|i18n('fumaggo/design/eztube/video',, array( $video.view_count|wash ) )} | {'%1 likes'|i18n('fumaggo/design/eztube/video',, array( $video.likes|wash ) )} | {'%1 dislikes'|i18n('fumaggo/design/eztube/video',, array( $video.dislikes|wash ) )}</div>

		<br/>

		<div class="description">{$video.description|wash|autolink|nl2br}</div>

		<br/>

		<div class="categories"><label>{'Categories'|i18n( 'fumaggo/design/eztube/video' )}</label>{foreach $video.categories as $category}<a href="{concat( "http://www.youtube.com/", $category|trim|downcase )}" target="_blank" title="{$category|wash}">{$category|wash|trim}</a>{delimiter}, {/delimiter}{/foreach}</div>

		<br/>
		{if fetch( 'user', 'has_access_to', hash( 'module',   'eztube', 'function', 'tag') )}
		<div class="tags"><label>{'Tags'|i18n( 'fumaggo/design/eztube/video' )}</label> {foreach $video.tags as $tag}<a href={concat( "eztube/tag/", $tag|trim|urlencode )|ezurl} title="{$tag|wash}">{$tag|wash|trim}</a>{delimiter}, {/delimiter}{/foreach}</div>

		<br/>
		{/if}
		
		{if eq( $comments, "comments" )}
			{if fetch( 'user', 'has_access_to', hash( 'module',   'eztube', 'function', 'comments') )}
				{if gt( $video.comments|count, 0 )}
				<hr/>
					<div class="attribute-comments">
						<h3>{'%1 Comments'|i18n('fumaggo/design/eztube/video',, array($video.comments.comments_count|wash ) )}</h3>
						<div class="break"></div>
						{foreach $video.comments.comments_list as $comment max 10}
							<div class="video-comment">
								<p>
									<a href={concat( "eztube/profile/", $comment.author_name|wash|downcase )|ezurl} title="{$comment.author_name|wash}">{$comment.author_name|wash}</a> {'on %1'|i18n( 'fumaggo/design/eztube/video',, array( $comment.published|wash|l10n( shortdate ) ) )}<br/>
									{$comment.description|wash()|autolink|nl2br()}
								</p>
							</div>
						{/foreach}
						&raquo;&nbsp;<a href={concat( "eztube/comments/", $video.id )|wash|ezurl}>{'View all comments'|i18n( 'fumaggo/design/eztube/video' )}</a>
					</div>
				{/if}
			{/if}
		{/if}
		
	</div>   
</div>