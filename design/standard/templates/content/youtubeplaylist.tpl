<div class="content-view-line">
	<div class="eztube float-break" style="margin-bottom:25px;">	

		
		<div class="attribute-image" style="float:left; margin-right:25px;margin-top:0px;">
		    <a href={concat( "eztube/playlist/", $playlist.info.id|wash )|ezurl} title="{$playlist.info.title|wash}">{if is_set( $playlist.video.0.thumbnails.0 )}<img src="{$playlist.video.0.thumbnails.0|wash}" border="0" alt="{$playlist.info.title|wash}" />{else}<img src="{$playlist.author.thumbnail|wash}" border="0" alt="{$playlist.info.title|wash}" />{/if}</a>
		</div>
			
		<h3><a href={concat( "eztube/playlist/", $playlist.info.id|wash )|ezurl} title="{$playlist.info.title|wash}">{$playlist.info.title|wash}</a></h3>


		<div class="attribute-byline">
			
			{set-block variable=profile_link}
				<a href={concat( "eztube/profile/", $playlist.author.id|wash )|ezurl}>{$playlist.author.display_name|wash}</a>
			{/set-block}
			
			<div class="small">
			 	{'Updated by %1 on %2'|i18n( 'fumaggo/design/eztube/playlist',, array( $profile_link, $playlist.info.updated|wash|l10n( shortdate ) ) )} {if and(is_set($playlist.info.view_count),gt($playlist.info.view_count|count,0))}| {'%1 views'|i18n('fumaggo/design/eztube/playlist',, array( $playlist.info.view_count|wash ) )}{/if}<br/> 
			</div>
		</div>

		{if ne( $playlist.info.subtitle|trim, '')}
			<div class="attribute-short">
				<p>{$playlist.info.subtitle|wash|shorten(250)|autolink}</p>
			</div>
		{/if}
	</div>
</div>
<hr/>