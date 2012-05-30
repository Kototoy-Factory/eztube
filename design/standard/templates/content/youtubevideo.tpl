{if is_set( $type )}
{def $user = $video.credit}
{else}
{def $user = $video.user_id}
{/if}
<div class="content-view-line">
	<div class="eztube float-break" style="margin-bottom:25px;">	

		{if $video.thumbnails.0}
			<div class="attribute-image" style="float:left; margin-right:25px;margin-top:0px;">
			    <a href={concat( "eztube/video/", $video.id|wash )|ezurl} title="{$video.title|wash}"><img src="{$video.thumbnails.0|wash}" border="0" alt="{$video.title|wash}" /></a>
			</div>
		{/if}
		
		<h3><a href={concat( "eztube/video/", $video.id|wash )|ezurl} title="{$video.title|wash}">{$video.title|wash}</a></h3>


		<div class="attribute-byline">
			
			{set-block variable=profile_link}
				<a href={concat( "eztube/profile/", $user|wash )|ezurl}>{$user|wash}</a>
			{/set-block}
			
			<div class="small">
			 	{'Uploaded by %1 on %2'|i18n( 'fumaggo/design/eztube/video',, array( $profile_link, $video.uploaded|wash|l10n( shortdate ) ) )} | {'%1 views'|i18n('fumaggo/design/eztube/video',, array( $video.view_count|wash ) )}<br/> 
			</div>
		</div>

		{if $video.description}
			<div class="attribute-short">
				<p>{$video.description|wash|shorten(250)|autolink}</p>
			</div>
		{/if}
	</div>
</div>
