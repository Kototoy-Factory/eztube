{*

home.tpl template
Copyright (C) 2011 Fumaggo Internet Solutions. All rights reserved
$video - array with information about the video

*}
{def	$home_row1 = fetch_alias( 'eztube_home_row_1' )
	$home_row2 = fetch_alias( 'eztube_home_row_2' )
	$home_row3 = fetch_alias( 'eztube_home_row_3' )
	$home_row4 = fetch_alias( 'eztube_home_row_4' )}
	

<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

	<div class="content-view-full">
        	<div class="eztube home">
		
		<div class="object-right">
			<form method="get" action={"/eztube/search/"|ezurl} style="display: inline;">
				<input type="text" id="eztubesearch" width="70"  name="q" value="" /> <input class="button" type="submit" name="search" value="{'Search'|i18n( 'fumaggo/design/eztube/home' )}" />
			</form>
		</div>
		
		<div class="attribute-header">
			<h1>{'Videos'|i18n( 'fumaggo/design/eztube/home' )}</h1>
		</div>
		
		<div class="break"></div>
		
		{if gt( $home_row1.result_count, 0 )}
			<hr/>
			<div class="object-right small"><a href={concat($home_row1.query.view,$home_row1.query.query)|ezurl} title="{'See more results'|i18n( 'fumaggo/design/eztube/home' )}">{'See more'|i18n( 'fumaggo/design/eztube/home' )}&nbsp;&raquo;</a></div>
			<h3>{ezini( 'eztube_home_row_1', 'Title', 'fetchalias.ini' )}</h3>
			<table width="100%" border="0" cellpadding="15" cellspacing="9">
				<tr>
				{foreach $home_row1.result_list as $video max 8}
					<td width="25%" valign="top">
						<a href={concat( "eztube/video/", $video.id )|ezurl} title="{$video.title|wash}"><img src="{$video.thumbnails.0|wash}" alt="{$video.title|wash}" /></a>
						<div class="small">
							{$video.title|wash}
							<br/>

							{'%1 views'|i18n( 'fumaggo/design/eztube/home',,array( $video.view_count|wash))}
							<br/>

							<a href={concat( "eztube/profile/", $video.user_name|wash )|ezurl} title="{'View profile of %1'|i18n( 'fumaggo/design/eztube/home',,array( $video.user_name|wash))}">{$video.user_name|wash}</a>
						</div>
					</td>
				{delimiter modulo=4}
				</tr>
				<tr>
				{/delimiter}
				{/foreach}
				</tr>
			</table> 

			<br/>
			
		{/if}
        	
        	
        	{if gt( $home_row2.result_count, 0 )}
        		<hr/>
			<div class="object-right small"><a href={concat($home_row2.query.view,$home_row2.query.query)|ezurl} title="{'See more results'|i18n( 'fumaggo/design/eztube/home' )}">{'See more'|i18n( 'fumaggo/design/eztube/home' )}&nbsp;&raquo;</a></div>
			<h3>{ezini( 'eztube_home_row_2', 'Title', 'fetchalias.ini' )}</h3>
			<table width="100%" border="0" cellpadding="15" cellspacing="9">
				<tr>
				{foreach $home_row2.result_list as $video max 4}
					<td width="25%" valign="top">
						<a href={concat( "eztube/video/", $video.id )|ezurl} title="{$video.title|wash}"><img src="{$video.thumbnails.0|wash}" alt="{$video.title|wash}" /></a>
						<div class="small">
							{$video.title|wash}
							<br/>

							{'%1 views'|i18n( 'fumaggo/design/eztube/home',,array( $video.view_count|wash))}
							<br/>

							<a href={concat( "eztube/profile/", $video.user_name|wash )|ezurl}>{$video.user_name|wash}</a>
						</div>
					</td>
				{delimiter modulo=4}
				</tr>
				<tr>
				{/delimiter}
				{/foreach}
				</tr>
			</table> 

			<br/>
		{/if}
		{if gt( $home_row3.result_count, 0 )}
			<hr/>
			<div class="object-right small"><a href={concat($home_row3.query.view,$home_row3.query.query)|ezurl} title="{'See more results'|i18n( 'fumaggo/design/eztube/home' )}">{'See more'|i18n( 'fumaggo/design/eztube/home' )}&nbsp;&raquo;</a></div>
			<h3>{ezini( 'eztube_home_row_3', 'Title', 'fetchalias.ini' )}</h3>
			<table width="100%" border="0" cellpadding="15" cellspacing="9">
				<tr>
				{foreach $home_row3.result_list as $video max 4}
					<td width="25%" valign="top">
						<a href={concat( "eztube/video/", $video.id )|ezurl} title="{$video.title|wash}"><img src="{$video.thumbnails.0|wash}" alt="{$video.title|wash}" /></a>
						<div class="small">
							{$video.title|wash}
							<br/>

							{'%1 views'|i18n( 'fumaggo/design/eztube/home',,array( $video.view_count|wash))}
							<br/>

							<a href={concat( "eztube/profile/", $video.user_name|wash )|ezurl}>{$video.user_name|wash}</a>
						</div>
					</td>
				{delimiter modulo=4}
				</tr>
				<tr>
				{/delimiter}
				{/foreach}
				</tr>
			</table> 

			<br/>
		{/if}
		
		{if gt( $home_row4.result_count, 0 )}
		<hr/>
		<div class="object-right small"><a href={concat($home_row4.query.view,$home_row4.query.query)|ezurl} title="{'See more results'|i18n( 'fumaggo/design/eztube/home' )}">{'See more'|i18n( 'fumaggo/design/eztube/home' )}&nbsp;&raquo;</a></div>
		<h3>{ezini( 'eztube_home_row_4', 'Title', 'fetchalias.ini' )}</h3>
		<table width="100%" border="0" cellpadding="15" cellspacing="9">
			<tr>
			{foreach $home_row4.result_list as $profile max 4}
				<td width="25%" valign="top">
					<a href={concat( "eztube/profile/", $profile.user_name )|ezurl} title="{'View profile of %1'|i18n( 'fumaggo/design/eztube/home',,array( $profile.user_name|wash))}"><img src="{$profile.thumbnail|wash}" alt="{$profile.user_name|wash}" width="120" height="90" /></a>
					<div class="small">
						{$profile.id|wash}
						<br/>

						{'%1 views'|i18n( 'fumaggo/design/eztube/home',,array( $profile.view_count|wash))}
						<br/>

						<a href={concat( "eztube/profile/", $profile.user_name|wash )|ezurl}>{$profile.user_name|wash}</a>
					</div>
				</td>
			{delimiter modulo=4}
			</tr>
			<tr>
			{/delimiter}
			{/foreach}
			</tr>
		</table> 

		<br/>
		{/if}	
	</div>
    </div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>
		
