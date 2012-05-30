{*

tags.tpl template
Copyright (C) 2011 Fumaggo Internet Solutions. All rights reserved
Available variables:
$tag - name of the tag
$video_count - total number of videos
$video_list - array with information about the videos

*}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

	<div class="content-view-full">
		<div class="eztube tags">

		{if $video_list}
			<div class="attribute-header">
			    <h1>{'Videos tagged with "%1"'|i18n( 'fumaggo/design/eztube/tag',,array( $tag|wash ) )}</h1>
			</div>
			
			<div class="break"></div>

			<div class="attribute-long">
				{foreach $video_list as $video}
					{include uri="design:content/youtubevideo.tpl" video=$video}
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
			page_uri=concat( "eztube/tag/",$tag|wash )
			item_count=$video_count
			view_parameters=$view_parameters
			item_limit=25}

		</div>
	</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>
		
				



