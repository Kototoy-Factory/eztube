{*

video.tpl template
Copyright (C) 2011 Fumaggo Internet Solutions. All rights reserved
Available variables:
$video - array with information about the video

*}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

	<div class="content-view-full">
        	<div class="eztube video">
        	
        	<div class="object-right">
			<form method="get" action={"/eztube/search/"|ezurl} style="display: inline;">
				<input type="text" id="eztubesearch" width="70"  name="q" value="" /> <input class="button" type="submit" name="search" value="{'Search'|i18n( 'fumaggo/design/eztube/home' )}" />
			</form>
		</div>
        
		<div class="attribute-header">
			    <h1>{$video.title|wash}</h1>
		</div>
		
		<div class="break"></div>
		
		<hr/>

		<table width="100%" border="0">
			<tr>
				<td width="52%" valign="top">
						
					<div class="attribute-short">
						<iframe title="{'YouTube video player'|i18n( 'fumaggo/design/eztube/video' )}" width="480" height="360" src="http://www.youtube.com/embed/{$video.id|wash}?{$player_params}" frameborder="0" allowfullscreen></iframe>	
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
					</div>
					
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
					
					{if fetch( 'user', 'has_access_to', hash( 'module',   'eztube', 'function', 'responses') )}										
						{if gt( $video.video_responses|count,0 )}
						<hr/>
							<div class="attribute-videoresponses">
								<h3>{'Video responses'|i18n( 'fumaggo/design/eztube/video' )}</h3>
								<table width="100%"><tr>
									{foreach $video.video_responses.video_list as $video_response max 3}
										<td valign="top" width="33%">

											<a href={concat( "eztube/video/", $video_response.id )|ezurl}><img src="{$video_response.thumbnails.0|wash()}" alt="{$video_response.title|wash}" /></a>

											<div class="title small">
												<a href={concat( "eztube/video/", $video_response.id|wash )|ezurl}>{$video_response.title|wash}</a><br/>
											</div>

											{set-block variable=profile_link}
												<a href={concat( "eztube/profile/", $video_response.user_name|wash )|ezurl}>{$video_response.user_name|wash}</a>
											{/set-block}

											<div class="info small">
												{'By %1'|i18n( 'fumaggo/design/eztube/video',, array( $profile_link ) )}<br/>
												{'%1 views'|i18n('fumaggo/design/eztube/video',, array( $video_response.view_count|wash ) )}
											</div>
										</td>
									{/foreach}
								</tr></table>
								{if gt( $video.video_responses.video_count, 3 )}
									&raquo;&nbsp;<a href={concat( "eztube/responses/",$video.id|wash )|ezurl}>{'View all video responses'|i18n( 'fumaggo/design/eztube/video' )}</a>
								{/if}

							</div>
						{/if}
					{/if}
					
        			</td>
        			<td width="3%">&nbsp;</td>
        			<td width="45%" valign="top">
        				{if fetch( 'user', 'has_access_to', hash( 'module',   'eztube', 'function', 'related') )}
						{if and( is_set( $video.related_videos.video_count), gt( $video.related_videos.video_count, 0 ) )}
							<div class="attribute-relatedvideos">
								<table width="100%" cellpadding="0" cellspacing="0">

									{foreach $video.related_videos.video_list as $related_video max 5}
										<tr>
											<td valign="top"><a href={concat( "eztube/video/", $related_video.id|wash )|ezurl}><img src="{$related_video.thumbnails.0|wash}" width="120" align="left" alt="{$related_video.title|wash}" /></a></td>
											<td>&nbsp;</td>
											<td valign="top" width="70%">
												<div class="title small"><a href={concat( "eztube/video/", $related_video.id|wash )|ezurl}>{$related_video.title|wash}</a></div>

												{set-block variable=profile_link}
													<a href={concat( "eztube/profile/", $related_video.user_name|wash )|ezurl}>{$related_video.user_name|wash}</a>
												{/set-block}

												<div class="info small">
													{'By %1'|i18n( 'fumaggo/design/eztube/video',, array( $profile_link ) )}<br/>
													{'%1 views'|i18n('fumaggo/design/eztube/video',, array( $related_video.view_count|wash ) )}
												</div>
												<br clear="all"/>
											</td>
										</tr>
										<tr><td colspan="3">&nbsp;</td></tr>
									{/foreach}

									{if gt( $video.related_videos.video_count, 5 )}
										<tr>
											<td colspan="3">
												&raquo;&nbsp;<a href={concat( "eztube/related/", $video.id|wash )|ezurl}>{'View more suggestions'|i18n( 'fumaggo/design/eztube/video' )}</a>
											</td>
										</tr>
									{/if}
								</table>
							</div>
						{/if}
					{/if}
					
        			</td>
        		</tr>
        	</table>        
	</div>
    </div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>
		
