{*

comments.tpl template
Copyright (C) 2011 Fumaggo Internet Solutions. All rights reserved
Available variables:

$video - array with information about the video being commented on
$comments_count - total number of comments on the video
$comments_list - array with comments on the video

*}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

	<div class="content-view-full">
		<div class="eztube comments">

		{if $comments_list}
			<div class="attribute-header">
			    <h1>{'Comments for "%1"'|i18n( 'fumaggo/design/eztube/comments',, array( $video.title|wash ) )}</h1>
			</div>
			
			<div class="break"></div>

			<div class="attribute-long">
				
				{foreach $comments_list as $comment}
					<div class="content-view-line">
						<div class="class-article float-break">	
							
							<div class="attribute-short">
								<p>{$comment.description|wash|autolink|nl2br}</p>
							</div>

							
							{set-block variable=profile_link}
								<a href={concat( "eztube/profile/", $comment.author_name|wash )|ezurl}>{$comment.author_name|wash}</a>
							{/set-block}						
							
							<div class="attribute-byline small">
								<p>
									{'By %1 on %2'|i18n( 'fumaggo/design/eztube/comments',, array( $profile_link,$comment.published|wash|l10n( shortdate ) ) )}<br/>	
								</p>
							</div>
							
							<div class="break"></div>
							
						</div>
    					</div>
				{/foreach}
				
			</div>
		{else}
			 <div class="attribute-header">
				<h1>{'No results.'|i18n( 'fumaggo/design/eztube' )}</h1>
			 </div>
			 
			 <div class="break"></div>
		{/if}

		{include name=navigator
				uri='design:navigator/google.tpl'
				page_uri=concat( "eztube/comments/", $video.id|wash )
				item_count=$comments_count
				view_parameters=$view_parameters
				item_limit=25}

		</div>
	</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>
