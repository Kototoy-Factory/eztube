{*

contacts.tpl template
Copyright (C) 2011 Fumaggo Internet Solutions. All rights reserved
Available variables:
$user_info - array with information about the user
$contacts_count - total number of contacts on the video
$contacs_list - array with contacts on the video

*}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

	<div class="content-view-full">
		<div class="eztube related">

		{if $contacts_list}
			<div class="attribute-header">
			    <h1>{'Contacts of "%1"'|i18n( 'fumaggo/design/eztube/contacts',,array( $user_info.id|wash ) )}</h1>
			</div>
			
			<div class="break"></div>

			<div class="attribute-long">
				<table width="100%" border="0" cellpadding="15" cellspacing="9">
					<tr>
					{foreach $contacts_list as $contact}
						<td width="25%" valign="top">
							<a href={concat( "eztube/profile/", $contact.id )|ezurl}><img src="{$contact.thumbnail|wash}" alt="{$contact.id|wash}" width="120" /></a>
							<div class="small">
								<a href={concat( "eztube/profile/", $contact.id|wash )|ezurl}>{$contact.id|wash}</a>
							</div>
						</td>
					{delimiter modulo=5}
					</tr>
					<tr>
					{/delimiter}
					{/foreach}
					</tr>
        			</table> 
			</div>
		{else}
			<div class="attribute-header">
				<h1>{'No results.'|i18n( 'fumaggo/design/eztube' )}</h1>
			</div>

			<div class="break"></div>
		{/if}

		{include name=navigator
				uri='design:navigator/google.tpl'
				page_uri=concat("eztube/contacts/",$user_info.id|wash())
				item_count=$contacts_count
				view_parameters=$view_parameters
				item_limit=25}

		</div>
	</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>
