{*

playlist.tpl template
Copyright (C) 2011 Fumaggo Internet Solutions. All rights reserved
Available variables:
$playlist_id - id of the playlist
$playlist_info - array with information about the playlist
$user_info - array with profile info about the user who created the playlist
$video_count - total number of videos in the playlist
$video_list - array with information about the videos in the playlist

*}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

	<div class="content-view-full">
		<div class="eztube playlist">

		{if gt( $video_count, 0 )}
			
			<div class="attribute-header">
			    <h1>{'Playlist "%1"'|i18n( 'fumaggo/design/eztube/playlist',,array( $playlist_info.title|wash ) )}</h1>
			</div>
			
			<div class="break"></div>
			
			{if not($view_parameters.offset)}
				<div class="attribute-short">
					<p>{$playlist_info.subtitle|wash}</p>
				</div>
			{/if}
			
			<div class="break"></div><br/>

			<div class="attribute-long">
				{foreach $video_list as $video_item}
					{include uri="design:content/youtubevideo.tpl" video=$video_item type=2}
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
			page_uri=concat( "eztube/playlist/", $playlist_id|wash )
			item_count=$video_count
			view_parameters=$view_parameters
			item_limit=25}

		</div>
	</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>
		
				



