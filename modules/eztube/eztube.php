<?php

/**
 * File containing the eZTube module views.
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 */
 
$http = eZHTTPTool::instance();
$Module = $Params["Module"];
$currentview = $Module->currentView();
$eztube = new EZTube();

$Limit = 25;
$Offset = 0;
$SortBy = 4;
$SortByText = "relevance";

if ( isset( $Params['Offset'] ) )
	$Offset = $Params['Offset'];

if ( !is_numeric( $Offset ) )
	$Offset = 0;

if ( isset( $Params['Limit'] ) )
	$Limit = $Params['Limit'];

if ( !is_numeric( $Limit ) )
    	$Limit = 25;

if ( isset( $Params['SortBy'] ) )
{
	$SortBy = $Params['SortBy'];
	
	if ( $SortBy == 1 )
	{
		$SortByText = "viewCount";
	}
	else if ( $SortBy == 2 )
	{
		$SortByText = "rating";
	}
	else if ( $SortBy == 3 )
	{
		$SortByText = "published";
	}
	else
	{
		$SortByText = "relevance";
		$SortBy= 4;
	}
}

$viewParameters = array( 'offset' => $Offset, 'sortby' => $SortBy, 'limit' => $Limit );


$tpl = eZTemplate::factory(); 
$tpl->setVariable( "viewmode", $currentview );

if ( $currentview == "home" )
{
	$Result = array();
	$Result['path'][] = array( 'url' => '/', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Home' ) );
	$Result['path'][] = array( 'url' => '/eztube/home', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Videos' ) );
	$Result['content'] =  $tpl->fetch( 'design:home.tpl' );
}
else if ( $currentview == "playlist" )
{
	$PlayListID = urlencode( addslashes( $Params["PlayListID"] ) );

	if ( !is_string( $PlayListID ) or !$Params["PlayListID"] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );	
	}

	$playlist = $eztube->getPlaylistVideoFeed( $PlayListID, $Offset, $Limit );
	
	if ( !$playlist['result']['playlist_id'] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );	
	}

	$tpl->setVariable( "playlist_id", $playlist['result']['playlist_id'] );
	$tpl->setVariable( "playlist_info", $playlist['result']['playlist_info'] );
	$tpl->setVariable( "user_info", $playlist['result']['user_info'] );
	$tpl->setVariable( "video_count", $playlist['result']['video_count'] );
	$tpl->setVariable( "video_list", $playlist['result']['video_list'] );
	$tpl->setVariable( "view_parameters", $viewParameters );

	$Result = array();
	$Result['path'][] = array( 'url' => '/', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Home' ) );
	$Result['path'][] = array( 'url' => '/eztube/home', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Videos' ) );
	$Result['path'][]=array( 'url' => '/eztube/profile/'.$playlist['result']['user_info']['id'], 'text' => ucfirst( $playlist['result']['user_info']['id'] ) );
	$Result['path'][]=array( 'url' => '/eztube/playlists/'.$playlist['result']['user_info']['id'], 'text' => ezpI18n::tr( 'fumaggo/design/eztube/playlist', 'Playlists' ) );
	$Result['path'][]=array( 'url' => false, 'text' => $playlist['result']['playlist_info']['title'] );
	$Result['content'] =  $tpl->fetch( 'design:playlist.tpl' );

}
else if ( $currentview=="playlists" )
{
	$eztubeUserID = urlencode( addslashes( $Params["YouTubeUserID"] ) );

	if ( !is_string( $eztubeUserID ) or !$Params["YouTubeUserID"] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );	
	}

	$playlists = $eztube->getPlaylistListFeed( $eztubeUserID, $Offset, $Limit  );
	
	if ( !$playlists['result']['playlists_list'] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );	
	}

	$tpl->setVariable( "playlists_list", $playlists['result']['playlists_list'] );
	$tpl->setVariable( "playlists_count", $playlists['result']['playlists_count'] );
	$tpl->setVariable( "user_info", $playlists['result']['user_info'] );
	$tpl->setVariable( "view_parameters", $viewParameters );

	$Result = array();
	$Result['path'][] = array( 'url' => '/', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Home' ) );
	$Result['path'][] = array( 'url' => '/eztube/home', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Videos' ) );
	$Result['path'][] = array( 'url' => '/eztube/profile/'.$playlists['result']['user_info']['id'], 'text' => ucfirst( $playlists['result']['user_info']['id'] ) );
	$Result['path'][] = array( 'url' => false, 'text' => ezpI18n::tr( 'fumaggo/design/eztube/playlists', 'Playlists' ) );
	$Result['content'] =  $tpl->fetch( 'design:playlists.tpl' );
	
}
else if ( $currentview == "favorites" )
{
	$eztubeUserID = urlencode( addslashes( $Params["YouTubeUserID"] ) );

	if ( !is_string( $eztubeUserID ) or !$Params["YouTubeUserID"] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );	
	}

	$favorites = $eztube->getUserFavorites( $eztubeUserID, $Offset, $Limit, $SortByText );
	
	if ( !$favorites['result']['user_info'] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );	
	}

	$tpl->setVariable( "user_info", $favorites['result']['user_info'] );
	$tpl->setVariable( "video_list", $favorites['result']['video_list'] );
	$tpl->setVariable( "video_count", $favorites['result']['video_count'] );
	$tpl->setVariable( "view_parameters", $viewParameters );		

	$Result = array();
	$Result['path'][] = array( 'url' => '/', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Home' ) );
	$Result['path'][] = array( 'url' => '/eztube/home', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Videos' ) );
	$Result['path'][] = array( 'url' => '/eztube/profile/'.$favorites['result']['user_info']['id'],'text' => ucfirst( $favorites['result']['user_info']['id'] ) );
	$Result['path'][] = array( 'url' => false, 'text' => ezpI18n::tr( 'fumaggo/design/eztube/favorites', 'Favorites' ) );
	$Result['content'] =  $tpl->fetch( 'design:favorites.tpl' );

}
else if ( $currentview=="uploads" )
{
	
	$eztubeUserID = urlencode( addslashes( $Params["YouTubeUserID"] ) );

	if ( !is_string( $eztubeUserID ) or !$Params["YouTubeUserID"] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );	
	}

	$uploads = $eztube->getUserUploads( $eztubeUserID, $Offset, $Limit, $SortByText );
	
	if ( !$uploads['result']['user_info'] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );	
	}

	$tpl->setVariable( "user_info", $uploads['result']['user_info'] );
	$tpl->setVariable( "video_list", $uploads['result']['video_list'] );
	$tpl->setVariable( "video_count", $uploads['result']['video_count'] );
	$tpl->setVariable( "view_parameters", $viewParameters );		
	
	$Result = array();
	$Result['path'][] = array( 'url' => '/', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Home' ) );
	$Result['path'][] = array( 'url' => '/eztube/home', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Videos' ) );
	$Result['path'][] = array( 'url' => '/eztube/profile/'.$uploads['result']['user_info']['id'],'text' => ucfirst( $uploads['result']['user_info']['id'] ) );
	$Result['path'][] = array( 'url' => false, 'text' => ezpI18n::tr( 'fumaggo/design/eztube/uploads', 'Uploads' ) );
	$Result['content'] =  $tpl->fetch( 'design:uploads.tpl' );

	
}
else if ( $currentview == "contacts" )
{
	
	$eztubeUserID = urlencode( addslashes( $Params["YouTubeUserID"] ) );

	if ( !is_string( $eztubeUserID ) or !$Params["YouTubeUserID"] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );	
	}

	$contacts = $eztube->getContactFeed( $eztubeUserID, $Offset, $Limit, $SortByText  );
	
	if ( !$contacts['result']['contacts_list'] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );	
	}

	$tpl->setVariable( "contacts_list", $contacts['result']['contacts_list'] );
	$tpl->setVariable( "contacts_count", $contacts['result']['contacts_count'] );
	$tpl->setVariable( "user_info", $contacts['result']['user_info'] );
	$tpl->setVariable( "view_parameters", $viewParameters );

	$Result = array();
	$Result['path'][] = array( 'url' => '/', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Home' ) );
	$Result['path'][] = array( 'url' => '/eztube/home', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Videos' ) );
	$Result['path'][] = array( 'url' => '/eztube/profile/'.$contacts['result']['user_info']['id'],'text' => $contacts['result']['user_info']['id'] );
	$Result['path'][] = array( 'url' => false, 'text' => ezpI18n::tr( 'fumaggo/design/eztube/contacts', 'Friends' ) );
	$Result['content'] =  $tpl->fetch( 'design:contacts.tpl' );
	
}
else if ( $currentview == "profile" )
{	
	$eztubeUserID = urlencode( addslashes( $Params["YouTubeUserID"] ) );

	if ( !is_string( $eztubeUserID ) or !$Params["YouTubeUserID"] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );	
	}

	$profile = $eztube->getUserProfile( $eztubeUserID );
	
	if ( !$profile['result']['id'] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );	
	}
	
	$ezTubeFilter = new EZTubeFilter();
	$allowedProfile = $ezTubeFilter->ProfileFilter( $profile['result']['id'] );
	if ( !$allowedProfile ) 
	{
		return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
	}

	$tpl->setVariable( "user_info", $profile['result'] );
	
	$Result = array();
	$Result['path'][] = array( 'url' => '/', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Home' ) );
	$Result['path'][] = array( 'url' => '/eztube/home', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Videos' ) );
	$Result['path'][] = array( 'url' => false, 'text' => ezpI18n::tr( 'fumaggo/design/eztube/profile', 'YouTube Profiles' ) );
	$Result['path'][] = array( 'url' => false, 'text' => $profile['result']['id'] );
	$Result['content'] =  $tpl->fetch( 'design:profile.tpl' );
	
}
else if ( $currentview == "video" )
{
	$eztubeID = urlencode( addslashes( substr($Params["YouTubeID"], 0, 11 ) ) );

	if ( !is_string( $eztubeID ) || !$Params["YouTubeID"] || strlen( $eztubeID ) < 11 )
	{
		return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
	}

	$video = $eztube->getVideoEntry( $eztubeID );	
	
	if ( !$video['result']['id'] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );	
	}
	
	$tpl->setVariable( "video", $video['result'] );
	
	$ini = eZINI::instance( 'eztube.ini' );
	$playerArray = $ini->variable( 'PlayerSettings', 'Parameters' );
	$playerString = "";
	foreach( $playerArray as $key => $value )
	{
		$playerString .= "&" . $key . "=" . $value;		
	}
	
	$tpl->setVariable( "player_params", $playerString );

	$Result = array();
	$Result['path'][] = array( 'url' => '/', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Home' ) );
	$Result['path'][] = array( 'url' => '/eztube/home', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Videos' ) );
	$Result['path'][] = array( 'url' => false, 'text' => $video['result']['title'] );
	$Result['content'] =  $tpl->fetch( 'design:video.tpl' );
	
}
else if ( $currentview=="related" )
{
	$eztubeID = urlencode( addslashes( substr( $Params["YouTubeID"], 0, 11) ) );

	if ( !is_string( $eztubeID ) or !$Params["YouTubeID"] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );	
	}

	$related = $eztube->getRelatedVideoFeed( $eztubeID, $Offset, $Limit, $SortByText );
	$video = $eztube->getVideoEntry( $eztubeID );
	
	if ( !$video['result']['id'] or !$related['result']['video_list'] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );	
	}

	$tpl->setVariable( "video_list", $related['result']['video_list'] );
	$tpl->setVariable( "video_count", $related['result']['video_count'] );
	$tpl->setVariable( "video", $video['result'] );
	$tpl->setVariable( "view_parameters", $viewParameters );
	
	$Result = array();
	$Result['path'][] = array( 'url' => '/', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Home' ) );
	$Result['path'][] = array( 'url' => '/eztube/home', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Videos' ) );
	$Result['path'][] = array( 'url' => '/eztube/video/'.$video['result']['id'],'text' => $video['result']['title'] );
	$Result['path'][] = array( 'url' => false, 'text' => ezpI18n::tr( 'fumaggo/design/eztube/related', 'Related videos' ) );
	$Result['content'] =  $tpl->fetch( 'design:related.tpl' );
	
}
else if ( $currentview=="responses" )
{
	$eztubeID = urlencode(addslashes( substr( $Params["YouTubeID"], 0, 11) ) );

	if ( !is_string( $eztubeID ) or !$Params["YouTubeID"] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );	
	}

	$responses = $eztube->getVideoResponseFeed( $eztubeID, $Offset, $Limit, $SortByText );
	$video = $eztube->getVideoEntry( $eztubeID );

	if ( !$video['result'] or !$responses['result']['video_list'] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );	
	}
	
	$tpl->setVariable( "video_list", $responses['result']['video_list'] );
	$tpl->setVariable( "video_count", $responses['result']['video_count'] );
	$tpl->setVariable( "video", $video['result'] );
	$tpl->setVariable( "view_parameters", $viewParameters );

	$Result = array();
	$Result['path'][] = array( 'url' => '/', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Home' ) );
	$Result['path'][] = array( 'url' => '/eztube/home', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Videos' ) );
	$Result['path'][] = array( 'url' => '/eztube/video/'.$video['result']['id'],'text' => $video['result']['title'] );
	$Result['path'][] = array( 'url' => false, 'text' => ezpI18n::tr( 'fumaggo/design/eztube/responses', 'Video responses' ) );
	$Result['content'] =  $tpl->fetch( 'design:responses.tpl' );
	
}
else if ( $currentview=="comments" )
{
	$eztubeID = urlencode(addslashes( substr($Params["YouTubeID"], 0, 11) ));

	if ( !is_string( $eztubeID ) or !$Params["YouTubeID"] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );	
	}

	$comments = $eztube->getVideoCommentFeed( $eztubeID, $Offset, $Limit, $SortByText );
	$video = $eztube->getVideoEntry( $eztubeID );
	
	if ( !$video['result']['id'] or !$comments['result']['comments_list'] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );	
	}

	$tpl->setVariable( "comments_list", $comments['result']['comments_list'] );
	$tpl->setVariable( "comments_count", $comments['result']['comments_count'] );
	$tpl->setVariable( "video", $video['result'] );
	$tpl->setVariable( "view_parameters", $viewParameters );
	
	$Result = array();
	$Result['path'][] = array( 'url' => '/', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Home' ) );
	$Result['path'][] = array( 'url' => '/eztube/home', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Videos' ) );
	$Result['path'][] = array( 'url' => '/eztube/video/'.$video['result']['id'],'text' => $video['result']['title'] );
	$Result['path'][] = array( 'url' => false, 'text' => ezpI18n::tr( 'fumaggo/design/eztube/comments', 'Comments' ) );
	$Result['content'] =  $tpl->fetch( 'design:comments.tpl' );
}
else if ( $currentview=="tag" )
{
	$Tag = urldecode( trim( $Params["Tag"] ) );

	if ( !is_string( $Tag ) or !$Params["Tag"] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );	
	}
	
	$Filters = true;
	$tag = $eztube->fetchVideosByTag( $Tag, $Filters, $Offset, $Limit, $SortByText );
	
	if ( !$tag['result']['result_list'] )
	{
		return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );	
	}

	$tpl->setVariable( "video_list", $tag['result']['result_list'] );
	$tpl->setVariable( "video_count", $tag['result']['result_count'] );
	$tpl->setVariable( "tag", $Tag );
	$tpl->setVariable( "view_parameters", $viewParameters );

	$Result = array();
	$Result['path'][] = array( 'url' => '/', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Home' ) );
	$Result['path'][] = array( 'url' => '/eztube/home', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Videos' ) );
	$Result['path'][] = array( 'url' => false,'text' => ucfirst( htmlspecialchars( $Tag ) ) );
	$Result['content'] =  $tpl->fetch( 'design:tag.tpl' );
}
else if ( $currentview=="upload")
{


}
else if ( $currentview == "search" )
{
	$ini = eZINI::instance();
	$logSearchStats = $ini->variable( 'SearchSettings', 'LogSearchStats' ) == 'enabled';

	$searchText ="";
	if ( $http->hasGetVariable( 'q' ) )
        {
		$searchText = $http->getVariable( 'q' ); 
	}
	
	$searchType = "videos";
	if ( $http->hasGetVariable( 'search_type' ) )
	{
		$searchType = $http->getVariable( 'search_type' );
	}
	
	$Filters = true;
	
	$Params=array();
	$tubeini = eZINI::instance( 'eztube.ini' );
	if ( $tubeini->hasVariable( 'AllowedSearchParameters', 'Parameter' ) )
	{
		$allowedParams = $tubeini->variable( 'AllowedSearchParameters', 'Parameter' );
	}

	foreach ( $allowedParams as $param )
	{
		if ( $http->hasGetVariable( $param ) and $http->getVariable( $param ) != "" )
		{
			$Params[$param] = $http->getVariable( $param );
		}
	}
	
	if ( $http->hasGetVariable( 'sortby' ) )
	{
		$SortBy = $http->getVariable( 'sortby' );
	}
	else
	{
		if ( isset( $Params['SortBy'] ) )
		{
			$SortBy = $Params['SortBy'];
		}
		else
		{
			$SortBy = 4;
		}
	}
	
	if ( $SortBy == 1 )
	{
		$SortByText = "viewCount";
	}
	else if ( $SortBy == 2 )
	{
		$SortByText = "rating";
	}
	else if ( $SortBy == 3 )
	{
		$SortByText = "published";
	}
	else
	{
		$SortByText = "relevance";
	}
	
	
	$result = $eztube->searchYouTube( $searchText, $searchType, $Filters, $Params, $Offset, $Limit, $SortByText );
	$searchData = $result['result']['result_list'];
	$searchCount = $result['result']['result_count'];
	
	$tpl->setVariable( "keyword", $result['result']['keyword'] );
	$tpl->setVariable( "type", $result['result']['type'] );	
	$tpl->setVariable( "sortby", $SortBy );	
	$tpl->setVariable( "result_list", $searchData );
	$tpl->setVariable( "result_count", $searchCount );
	$tpl->setVariable( "view_parameters", $viewParameters );
	$tpl->setVariable( "youtube_uri", $result['result']['youtube_uri'] );
	$tpl->setVariable( "query", $result['result']['query'] );
	$tpl->setVariable( "get_array", $result['result']['get_array'] );
	
	$Result = array();
	$Result['path'][] = array( 'url' => '/', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Home' ) );
	$Result['path'][] = array( 'url' => '/eztube/home', 'text' => ezpI18n::tr( 'fumaggo/design/eztube', 'Videos' ) );
	$Result['path'][] = array( 'url' => false, 'text' => ezpI18n::tr( 'fumaggo/design/eztube/search', 'Search' ) );
	$Result['content'] =  $tpl->fetch( 'design:search.tpl' );

	if ( $logSearchStats and
	     trim( $searchText ) != "" and
	     is_array( $searchData ) and
	     is_numeric( $searchCount ) )
	{
	    eZSearchLog::addPhrase( $searchText, $searchCount );
	}
	
}
else
{
	return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );	
}



?>
