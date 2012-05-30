{*

playlists.tpl template
Copyright (C) 2011 Fumaggo Internet Solutions. All rights reserved
Available variables:
$playlist_id - id of the playlist
$playlist_info - array with information about the playlist
$user_info - array with profile info about the user who created the playlist
*}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

	<div class="content-view-full">
		<div class="eztube playlists">
			{if gt($playlists_count,0)}

				<div class="attribute-header">
					<h1>{'%1\'s Playlists (%2)'|i18n( 'fumaggo/design/eztube/playlists',,array( $user_info.id|wash(),$playlists_count))}</h1>
				</div>
				
				<div class="break"></div>
				
				<div class="attribute-long">
					
					{foreach $playlists_list as $playlist_item}
						{include uri="design:content/youtubeplaylist.tpl" playlist=$playlist_item.result}
					{/foreach}
				</div>
			{else}
				<div class="attribute-header">
					<h1>{'No results.'|i18n( 'fumaggo/design/eztube' )}</h1>
				</div>

				<div class="break"></div>
			{/if}
		</div>
	</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>                                                                