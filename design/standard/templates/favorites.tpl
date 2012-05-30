{*

favorites.tpl template
Copyright (C) 2011 Fumaggo Internet Solutions. All rights reserved
Available variables:
$user_info - array with profile info about the user who created the playlist
$video_count - total number of videos in the playlist
$video_list - array with information about the videos in the playlist

*}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

	<div class="content-view-full">
		<div class="eztube favorites">

		{if $video_list}
			<div class="attribute-header">
			    <h1>{'%1\'s Favorites (%2)'|i18n( 'fumaggo/design/eztube/favorites',, array( $user_info.id|wash, $video_count) )}</h1>
			</div>
			
			<div class="break"></div>
			
			<div class="attribute-long">
				{foreach $video_list as $video}
					{include uri="design:content/youtubevideo.tpl" video=$video type=2}
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
			page_uri=concat( "eztube/favorites/", $user_info.id|wash )
			item_count=$video_count
			view_parameters=$view_parameters
			item_limit=25}

		</div>
	</div>
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>
		
				



