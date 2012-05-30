<div class="content-view-line">
	<div class="eztube float-break" style="margin-bottom:25px;">

		{if $user.thumbnail}
			<div class="attribute-image" style="float:left; margin-right:25px;margin-top:0px;">
			    <a href={concat( "eztube/profile/", $user.id|wash )|ezurl} title="{$user.display_name|wash}"><img src="{$user.thumbnail|wash}" border="0" alt="{$user.display_name|wash}" width="120" /></a>
			</div>
		{/if}
		
		<h3><a href={concat( "eztube/profile/", $user.id|wash )|ezurl} title="{$user.display_name|wash}">{$user.display_name|wash}</a></h3>

		<div class="attribute-short">
			<p>{'%1 uploads'|i18n('fumaggo/design/eztube/profile',, array( $user.upload_count|wash ) )} | {'%1 upload views'|i18n('fumaggo/design/eztube/profile',, array( $user.total_upload_views|wash ) )} | {'%1 channel views'|i18n('fumaggo/design/eztube/profile',, array( $user.view_count|wash ) )} | {'Latest update on %1'|i18n('fumaggo/design/eztube/profile',, array( $user.last_update|wash|l10n( shortdate ) ) )}</p>
			
			{if ne( $user.company|trim, '' )}
				<p>{$user.company|wash}</p>
			{/if}
			
			{if ne( $user.website|trim, '' )}
				<p>{$user.website|wash|autolink}</p>
			{/if}
			
			{if ne( $user.about|trim, '' )}
				<br/>
				<p>{$user.about|wash|shorten(250)|autolink}</p>
			{/if}
			
			{if ne( $user.location|trim, '' )}
				{if gt($user.location|count_chars,2)}
					{$user.location|wash}
				{else}
					{ezini( $user.location|wash,'Name', 'country.ini' )}
				{/if}
			{/if}
		</div>
	</div>
</div>