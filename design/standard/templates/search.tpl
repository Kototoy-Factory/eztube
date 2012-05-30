{*

search.tpl template
Copyright (C) 2011 Fumaggo Internet Solutions. All rights reserved
Available variables:
$result_count - total number of videos in the search results
$result_list - array with information about the videos in the search result
$keyword - string with search terms
$youtube_uri  - string with search call to youtube api - for debugging purposes.
$type - string with type of search (videos, users or playlists)
$query - array with module view/view parameters/get variables

*}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

	<div class="content-view-full">
		<div class="eztube search">
			
			<div class="object-right">
				<form method="get" action={"/eztube/search/"|ezurl} style="display: inline;">
					<input type="text" id="eztubesearch" width="70"  name="q" value="{$keyword}" /> <input class="button" type="submit" name="search" value="{'Search'|i18n( 'fumaggo/design/eztube/home' )}" />
				</form>
			</div>
			
			<div class="attribute-header">
				{if $result_list}
					<h1>{'Search results for "%1"'|i18n( 'fumaggo/design/eztube/search',,array( $keyword ) )}</h1>
				{else}
					<h1>{'No results were found when searching for "%1"'|i18n("fumaggo/design/eztube/search",,array($keyword))}</h1>
				{/if}
			</div>
			
			<div class="break"></div>
			
			<hr/>
				{if eq( $type, "videos" )}
					<table width="100%" cellpadding="6" cellspacing="6" class="small">	
						<tr>
							<td width="25%">{'Result type:'|i18n( 'fumaggo/design/eztube/search' )}</td>
							<td width="25%">{'Sort by:'|i18n( 'fumaggo/design/eztube/search' )}</td>
							<td width="25%">{'Upload date:'|i18n( 'fumaggo/design/eztube/search' )}</td>
							<td width="25%">{'Duration:'|i18n( 'fumaggo/design/eztube/search' )}</td></tr>
						<tr>
							<td><a href={concat( "eztube/search", $query.query, "&search_type=videos" )|ezurl} {if eq( $type, "videos" )}style="font-weight:bold;"{/if}>{'Videos'|i18n( 'fumaggo/design/eztube/search' )}</a></td>
							<td><a href={concat( "eztube/search", $query.query, "&sortby=4" )|ezurl} {if eq( $sortby, 4 )}style="font-weight:bold;"{/if}>{'Relevance'|i18n( 'fumaggo/design/eztube/search' )}</a></td>
							<td><a href={concat( "eztube/search", $query.query, "&time=all_time" )|ezurl} {if or( not( is_set($get_array.time)),and( is_set( $get_array.time ), eq( $get_array.time, "all_time" ) ) )}style="font-weight:bold;"{/if}>{'Anytime'|i18n( 'fumaggo/design/eztube/search' )}</a></td>
							<td><a href={concat( "eztube/search", $query.query, "&duration=" )|ezurl} {if not( is_set( $get_array.duration ) )}style="font-weight:bold;"{/if}>{'All'|i18n( 'fumaggo/design/eztube/search' )}</a></td></tr>
						<tr>
							<td>{if fetch( 'user', 'has_access_to', hash( 'module',   'eztube', 'function', 'profile') )}<a href={concat( "eztube/search", $query.query, "&search_type=users" )|ezurl}>{'Users'|i18n( 'fumaggo/design/eztube/search' )}</a>{else}&nbsp;{/if}</td>
							<td><a href={concat( "eztube/search", $query.query, "&sortby=3" )|ezurl} {if eq( $sortby, 3 )}style="font-weight:bold;"{/if}>{'Upload date'|i18n( 'fumaggo/design/eztube/search' )}</a></td>
							<td><a href={concat( "eztube/search", $query.query, "&time=today" )|ezurl} {if and( is_set( $get_array.time ), eq( $get_array.time, "today" ) )}style="font-weight:bold;"{/if}>{'Today'|i18n( 'fumaggo/design/eztube/search' )}</a></td>
							<td><a href={concat( "eztube/search", $query.query, "&duration=short" )|ezurl} {if and( is_set( $get_array.duration ), eq( $get_array.duration, "short" ) )}style="font-weight:bold;"{/if}>{'Short (~4 minutes)'|i18n( 'fumaggo/design/eztube/search' )}</a></td>
						</tr>
						<tr>
							<td>{if fetch( 'user', 'has_access_to', hash( 'module',   'eztube', 'function', 'playlists') )}<a href={concat( "eztube/search", $query.query, "&search_type=playlists" )|ezurl}>{'Playlists'|i18n( 'fumaggo/design/eztube/search' )}</a>{else}&nbsp;{/if}</td>
							<td><a href={concat( "eztube/search", $query.query, "&sortby=1" )|ezurl} {if eq( $sortby, 1 )}style="font-weight:bold;"{/if}>{'View count'|i18n( 'fumaggo/design/eztube/search' )}</a></td>
							<td><a href={concat( "eztube/search", $query.query, "&time=this_week" )|ezurl} {if and( is_set( $get_array.time ), eq( $get_array.time, "this_week" ) )}style="font-weight:bold;"{/if}>{'This week'|i18n( 'fumaggo/design/eztube/search' )}</a></td>
							<td><a href={concat( "eztube/search", $query.query, "&duration=long" )|ezurl} {if and( is_set( $get_array.duration ), eq( $get_array.duration, "long" ) )}style="font-weight:bold;"{/if}>{'Long (20~ minutes)'|i18n( 'fumaggo/design/eztube/search' )}</a></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><a href={concat( "eztube/search", $query.query, "&sortby=2" )|ezurl} {if eq( $sortby, 2 )}style="font-weight:bold;"{/if}>{'Rating'|i18n( 'fumaggo/design/eztube/search' )}</a></td>
							<td><a href={concat( "eztube/search", $query.query, "&time=this_month" )|ezurl} {if and( is_set( $get_array.time ), eq( $get_array.time, "this_month" ) )}style="font-weight:bold;"{/if}>{'This month'|i18n( 'fumaggo/design/eztube/search' )}</a></td>
							<td>&nbsp;</td>
						</tr>

					</table>
				{else}
					<table width="100%" cellpadding="6" cellspacing="6" class="small">	
						<tr>
							<td>{'Result type:'|i18n( 'fumaggo/design/eztube/search' )}</td>
						</tr>
						<tr>
							<td><a href={concat( "eztube/search", $query.query, "&search_type=videos" )|ezurl}>{'Videos'|i18n( 'fumaggo/design/eztube/search' )}</a></td>
						</tr>
						{if fetch( 'user', 'has_access_to', hash( 'module',   'eztube', 'function', 'profile') )}
							<tr>
								<td><a href={concat( "eztube/search", $query.query, "&search_type=users" )|ezurl} {if eq( $type, "users" )}style="font-weight:bold;"{/if}>{'Users'|i18n( 'fumaggo/design/eztube/search' )}</a></td>
							</tr>
						{/if}
						{if fetch( 'user', 'has_access_to', hash( 'module',   'eztube', 'function', 'playlists') )}
							<tr>
								<td><a href={concat( "eztube/search", $query.query, "&search_type=playlists" )|ezurl} {if eq( $type, "playlists" )}style="font-weight:bold;"{/if}>{'Playlists'|i18n( 'fumaggo/design/eztube/search' )}</a></td>
							</tr>
						{/if}
					</table>
				{/if}
			<hr/>
			<br/>
			{if $result_list}
				<div class="attribute-long">
					{foreach $result_list as $result_item}
						{switch match=$type}
							{case match="playlists"}
								{include uri="design:content/youtubeplaylist.tpl" playlist=$result_item}
							{/case}
						
							{case match="users"}
								{include uri="design:content/youtubeuser.tpl" user=$result_item}
							{/case}

							{case}
								{include uri="design:content/youtubevideo.tpl" video=$result_item}
							{/case}

						{/switch}
					{/foreach}
				</div>
			{else}
				<div class="break"></div>
				 
				
				<p>{'Search tips'|i18n('fumaggo/design/eztube/search')}</p>
				<ul>
				<li>{'Check spelling of keywords.'|i18n('fumaggo/design/eztube/search')}</li>
				<li>{'Try changing some keywords eg. &quot;car&quot; instead of &quot;cars&quot;.'|i18n('fumaggo/design/eztube/search')}</li>
				<li>{'Try more general keywords.'|i18n('fumaggo/design/eztube/search')}</li>
				<li>{'Fewer keywords result in more matches. Try reducing keywords until you get a result.'|i18n('fumaggo/design/eztube/search')}</li>
				</ul>
				
			{/if}
				
		{include name=navigator
			uri='design:navigator/google.tpl'
			page_uri=$query.view
			page_uri_suffix=$query.query
			item_count=$result_count
			view_parameters=$query.parameters
			item_limit=25}

		</div>
	</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>
		
				



