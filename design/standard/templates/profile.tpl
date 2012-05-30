{*

profile.tpl template
Copyright (C) 2011 Fumaggo Internet Solutions. All rights reserved
Available variables:
$user_info - array with information about the user

*}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

	<div class="content-view-full">
		<div class="eztube profile">
		
		<div class="object-right">
			<form method="get" action={"/eztube/search/"|ezurl} style="display: inline;">
				<input type="text" id="eztubesearch" width="70"  name="q" value="" /> <input class="button" type="submit" name="search" value="{'Search'|i18n( 'fumaggo/design/eztube/home' )}" />
			</form>
		</div>
		
		<div class="attribute-header">
			    <h1>{$user_info.display_name|wash}</h1>
		</div>

		<div class="break"></div>
		
		<hr/>
		
		<table width="100%" border="0">
			<tr>
				<td width="52%" valign="top">
				
					{if $user_info}
								
						<div class="attribute-image" style="float:left; margin-right:15px;">
							<img src="{$user_info.thumbnail|wash}" alt="{$user_info.display_name|wash}"/>
						</div>

						<table width="100%" border="0" cellpadding="5" cellspacing="5">
						
						<tr>
							<th valign="top">{'Name:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td>{if ne( $user_info.first_name|trim, '' )}{$user_info.first_name|wash} {/if}</td>
						</tr>
						
						<tr>
							<th>{'Channel views:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td>{$user_info.view_count|wash}</td>
						</tr>
						
						<tr>
							<th>{'Total upload views:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td>{$user_info.total_upload_views|wash}</td>
						</tr>
						
						<tr>
							<th>{'Joined:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td>{$user_info.member_since|wash|l10n( shortdate )}</td>
						</tr>
						
						{if or( ne( $user_info.subscriber_count|trim, '' ), eq( $user_info.subscriber_count, 0 ) )}						
						<tr>
							<th>{'Subscribers:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td>{$user_info.subscriber_count|wash}</td>
						</tr>
						{/if}
						
						{if ne( $user_info.website|trim, '' )}
						<tr>
							<th valign="top">{'Website:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td><a href="{$user_info.website|wash}" target="_blank">{$user_info.website|wash}</a></td>
						</tr>
						{/if}
						
						{if ne( $user_info.about|trim, '' )}
							<tr>
								<th valign="top">{'About:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td>{$user_info.about|wash|autolink|nl2br}</td>
							</tr>
						{/if}
						
						{if ne( $user_info.hometown|trim, '' )}
							<tr>
								<th>{'Hometown:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td>{$user_info.hometown|wash}</td>
							</tr>
						{/if}
						
						{if ne( $user_info.location|trim, '' )}
							<tr>
								<th>{'Location:'|i18n( 'fumaggo/design/eztube/profile' )}</th>
								<td>
									{if gt($user_info.location|count_chars,2)}
										{$user_info.location|wash}
									{else}
										{ezini( $user_info.location|wash,'Name', 'country.ini' )}
									{/if}
								</td>
							</tr>
						{/if}
						
						{if ne( $user_info.occupation|trim, '' )}
							<tr>
								<th>{'Occupation:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td>{$user_info.occupation|wash}</td>
							</tr>
						{/if}
						
						{if ne( $user_info.company|trim, '' )}
							<tr>
								<th valign="top">{'Company:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td>{$user_info.company|wash}</td>
							</tr>
						{/if}
						
						{if ne( $user_info.school|trim, '' )}
							<tr>
								<th valign="top">{'School:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td>{$user_info.school|wash}</td>
							</tr>
						{/if}
						
						{if ne( $user_info.hobbies|trim, '' )}
							<tr>
								<th valign="top">{'Interests:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td>{$user_info.hobbies|wash}</td>
							</tr>
						{/if}
						
						{if ne( $user_info.music|trim, '' )}
							<tr>
								<th valign="top">{'Music:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td>{$user_info.music|wash}</td>
							</tr>
						{/if}
						
						{if ne( $user_info.movies|trim, '' )}
							<tr>
								<th valign="top">{'Films:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td>{$user_info.movies|wash}</td>
							</tr>
						{/if}
						
						{if ne( $user_info.books|trim, '' )}
							<tr>
								<th valign="top">{'Books:'|i18n( 'fumaggo/design/eztube/profile' )}</th><td>{$user_info.books|wash}</td>
							</tr>
						{/if}
						
						
						</table>	
						
						<br/>
						
						
						{if fetch( 'user', 'has_access_to', hash( 'module',   'eztube', 'function', 'contacts') )}
							{if is_set( $user_info.contacts.contacts_count)}
								<hr/>
									<div style="float:right">&raquo;&nbsp;<a href={concat( "eztube/contacts/", $user_info.id|wash )|ezurl}>{'View all friends'|i18n( 'fumaggo/design/eztube/profile' )}</a></div><h3>{'Friends (%1)'|i18n( 'fumaggo/design/eztube/profile',, array( $user_info.contacts.contacts_count ) )}</h3> 
									<div class="attribute-contacts">
										<table width="100%" border="0"><tr>
											{foreach $user_info.contacts.contacts_list as $contact max 3}
												{if is_set($contact.id)}
													<td width="30%" valign="top">
														<a href={concat( "eztube/profile/", $contact.id|wash )|ezurl}><img src="{$contact.thumbnail}" border="0" alt="{$contact.id}" width="110" /></a><br/>
														<a href={concat( "eztube/profile/", $contact.id|wash )|ezurl}>{$contact.id}</a>
													</td>
													<td>&nbsp;</td>
												{/if}
											{/foreach}
										</tr>
										</table>
									</div>
							{/if}
						{/if}
					{else}
						 <div class="attribute-header">
						 	<h1>{'No results.'|i18n( 'fumaggo/design/eztube' )}</h1>
						 </div>
					{/if}
				</td>
				<td width="3%">&nbsp;</td>
        			<td width="45%" valign="top">
					{if fetch( 'user', 'has_access_to', hash( 'module',   'eztube', 'function', 'playlists') )}
						{def $playlists=fetch( 'eztube', 'playlists', hash( 'user_id', $user_info.id|wash, 'offset', 0, 'limit', 3 ) )}
						{if $playlists}
							<h4>{'Playlists (%1)'|i18n( 'fumaggo/design/eztube/profile',, array( $playlists.playlists_count|wash ) )}</h4>
							<table width="100%" cellpadding="0" cellspacing="0">
								{foreach $playlists.playlists_list as $playlist max 3}
									<tr>
										<td valign="top"><a href={concat( "eztube/playlist/", $playlist.id|wash )|ezurl}>{if $playlist.thumbnails}<img src="{$playlist.thumbnails.0|wash}" align="left" border="0" alt="{$playlist.title|wash}" width="120" /></a>{/if}</td>
										<td width="5%">&nbsp;</td>
										<td valign="top" width="70%">
											<a href={concat( "eztube/playlist/", $playlist.id|wash )|ezurl} title="{$playlist.title|wash}">{$playlist.title|wash}</a><br/>
											{'%1 videos'|i18n( 'fumaggo/design/eztube/profile',, array( $playlist.count|wash ) )}
											<br clear="all"/>
										</td>
									</tr>
									<tr><td colspan="3">&nbsp;</td></tr>
								{/foreach}
								<tr>
									<td colspan="3">
										&raquo;&nbsp;<a href={concat( "eztube/playlists/", $user_info.id|wash )|ezurl}>{'View all playlists'|i18n( 'fumaggo/design/eztube/profile' )}</a>
									</td>
								</tr>
							</table>
							<br clear="all"/>&nbsp;
						{/if}
					{/if}
					{if fetch( 'user', 'has_access_to', hash( 'module',   'eztube', 'function', 'uploads') )}
						{if gt( $user_info.latest_uploads|count,0 )}
							<h4>{'Uploads (%1)'|i18n( 'fumaggo/design/eztube/profile',, array( $user_info.upload_count|wash ) )}</h4>
							<table width="100%" cellpadding="0" cellspacing="0">
								{foreach $user_info.latest_uploads as $upload max 3}
									<tr>
										<td valign="top"><a href={concat( "eztube/video/", $upload.id|wash)|ezurl}><img src="{$upload.thumbnails.0|wash}" align="left" border="0" alt="{$upload.title|wash}" /></a></td>
										<td width="5%">&nbsp;</td>
										<td valign="top" width="70%">
											<a href={concat( "eztube/video/", $upload.id|wash )|ezurl}>{$upload.title|wash}</a><br/>
											<br clear="all"/>
										</td>
									</tr>
									<tr><td colspan="3">&nbsp;</td></tr>
								{/foreach}
								<tr>
									<td colspan="3">
										&raquo;&nbsp;<a href={concat( "eztube/uploads/", $user_info.id|wash )|ezurl}>{'View all uploads'|i18n( 'fumaggo/design/eztube/profile' )}</a>
									</td>
								</tr>
							</table>
						{/if}
					{/if}
				</td>
			</tr>
		</table>
		</div>
	    </div>

	</div></div></div>
	<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>
		
				

