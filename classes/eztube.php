<?php

/**
 * File containing the eZTube class.
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 */

class EZTube
{

	const AUTH_SERVICE_NAME = 'youtube';
	
	const OPEN_SEARCH_NAMESPACE = 'http://a9.com/-/spec/opensearch/1.1/';
	const OPEN_SEARCH_RSS_NAMESPACE = 'http://a9.com/-/spec/opensearchrss/1.0/';
	const YAHOO_MEDIA_NAMESPACE = 'http://search.yahoo.com/mrss/';
	const YOUTUBE_NAMESPACE = 'http://gdata.youtube.com/schemas/2007';
	const GOOGLE_NAMESPACE = 'http://schemas.google.com/g/2005';
	const ATOM_NAMESPACE = 'http://www.w3.org/2005/Atom';
	const GEO_NAMESPACE = 'http://www.georss.org/georss';
	
 	const COMMENTS_URI_SUFFIX = 'comments';
 	const RELATED_URI_SUFFIX = 'related';
 	const RESPONSES_URI_SUFFIX = 'responses';
 	const FAVORITES_URI_SUFFIX = 'favorites';
 	const UPLOADS_URI_SUFFIX = 'uploads';
 	const PLAYLISTS_URI_SUFFIX = 'playlists';
 	const CONTACTS_URI_SUFFIX = 'contacts';

 	
	const USER_URI = 'http://gdata.youtube.com/feeds/api/users';
	const USER_SEARCH_URI = 'http://gdata.youtube.com/feeds/api/channels';
	const VIDEO_URI = 'http://gdata.youtube.com/feeds/api/videos';
	const PLAYLIST_URI = 'http://gdata.youtube.com/feeds/api/playlists';
	const PLAYLIST_SEARCH_URI = 'http://gdata.youtube.com/feeds/api/playlists/snippets';
	const CATEGORIES_URI = 'http://gdata.youtube.com/schemas/2007/categories.cat';
	const STANDARD_URI = 'http://gdata.youtube.com/feeds/api/standardfeeds';
	const PLAYLIST_REL = 'http://gdata.youtube.com/schemas/2007#playlist';
	const USER_UPLOADS_REL = 'http://gdata.youtube.com/schemas/2007#user.uploads';
	const USER_PLAYLISTS_REL = 'http://gdata.youtube.com/schemas/2007#user.playlists';
	const USER_SUBSCRIPTIONS_REL = 'http://gdata.youtube.com/schemas/2007#user.subscriptions';
	const USER_CONTACTS_REL = 'http://gdata.youtube.com/schemas/2007#user.contacts';
	const USER_FAVORITES_REL = 'http://gdata.youtube.com/schemas/2007#user.favorites';
	const VIDEO_RESPONSES_REL = 'http://gdata.youtube.com/schemas/2007#video.responses';
	const VIDEO_RATINGS_REL = 'http://gdata.youtube.com/schemas/2007#video.ratings';
	const VIDEO_COMPLAINTS_REL = 'http://gdata.youtube.com/schemas/2007#video.complaints';
	const COMMENT_REPLY_REL = 'http://gdata.youtube.com/schemas/2007#in-reply-to';
	
	
	const STANDARD_TOP_RATED_SUFFIX = 'top_rated';
	const STANDARD_TOP_FAVORITES_SUFFIX = 'top_favorites';
	const STANDARD_MOST_VIEWED_SUFFIX = 'most_viewed';
	const STANDARD_MOST_SHARED_SUFFIX = 'most_shared';
	const STANDARD_MOST_POPULAR_SUFFIX = 'most_popular';
	const STANDARD_MOST_RECENT_SUFFIX = 'most_recent';
	const STANDARD_MOST_RESPONDED_SUFFIX = 'most_responded';
	const STANDARD_RECENTLY_FEATURED_SUFFIX = 'recently_featured';
	const STANDARD_MOST_DISCUSSED_SUFFIX = 'most_discussed';
	const STANDARD_WATCH_ON_MOBILE_SUFFIX = 'watch_on_mobile';
	const STANDARD_TRENDING_VIDEOS_SUFFIX = 'on_the_web';
		
	function __construct() 
	{
		$ini = eZINI::instance( 'eztube.ini' );
		
		if ( $ini->hasVariable( 'StandardSearchFilters', 'Filter') )
		{
			$filterArray = $ini->variable( 'StandardSearchFilters', 'Filter' );
			$filterString = "";
			foreach( $filterArray as $key => $value )
			{
				if ( $key == "category" ) $value = str_replace( ",", "%2C", $value );
				$filterString .= "&" . $key . "=" . urlencode( $value );		
			}

			$this->filters = $filterString;
		}
		
		if ( $ini->hasVariable( 'Developer', 'Key') )
		{
			$this->key = "&key=" . $ini->variable( 'Developer', 'Key' );
		}
	}

	
	function getVideoID( $video )
	{
		
		$at = $video->children( self::ATOM_NAMESPACE );
		if ( $at )
		{
			$id = (string)$at->id;
			if ( strstr( $id, "playlist" ) )
			{
				$link = $at->link->attributes()->href;
				$ids = explode( "=", $link );
				if ( $ids )
				{
					return $ids[1];
				}
			}
			else
			{
				$ids = explode( ":", $id );
				if ( $ids )
				{
					return end( $ids );
				}
			}
		}
		
		return false;
	}
	
	
	function getVideoEntry( $id = null, $mimimal = false )
	{
		$id = rawurlencode( $id );
		if ( !isset( $minimal ) ) $minimal = false;
		$uri = self::VIDEO_URI . '/' . $id . "?v=2";
		$video = $this->loadXML( $uri );
		
		$videoEntryInfo = $this->fetchVideoMetaData( $video, $minimal );
		
		if ( count( $videoEntryInfo['result'] ) > 0 )
		{
			return $videoEntryInfo;
		}
		
		return false;
	}

		
	function findFlashUrl( $video = null )
	{
		if ( $video ) 
		{
			foreach ( $video->content as $content )
			{
				if ( $content->attributes()->type == "application/x-shockwave-flash" ) 
				{
					return (string)$content->attributes()->src;
				}
			}
		}
		
		return false;
	}
	
	
	function loadXML( $uri )
	{
		$cache = new EZTubeXMLCache();
		$contents = $cache->geteZTubeCacheByURI( $uri );
            	
            	$xml = simplexml_load_string( $contents ); 
            	
		if ( $xml )
		{
			return $xml;
		}
           	
           	return false;
           	
	}
	
		
	function fetchVideoList( $uri )
	{
		$xml = $this->loadXML( $uri );
		
		if ( $xml )
		{
			$counts = $xml->children( self::OPEN_SEARCH_NAMESPACE );
      			$videoCount = (string)$counts->totalResults; 
      			$title = (string)$xml->title;
      			$videoListData=array();
      			
			if ( $videoCount > 0 )
			{
				foreach ( $xml->entry as $entry ) 
				{
					$videoEntryInfo = $this->fetchVideoMetaData( $entry, true );
					if ( $videoEntryInfo['result'] )
					{
						$videoListData[] = $videoEntryInfo['result'];
					}
					else
					{
						$videoCount = $videoCount-1;
					}
				}
				
				if ( $videoCount > 999 )
					$videoCount = 999;
				
				if ( $videoCount > 0 )
					return array( 'result' => array( 'list' => array_slice( $videoListData, 0, 999 ), 'count' => $videoCount, 'title' => $title ) );	
			}
			
		}
		
		return array( 'result' => false );	
	}
	
	
	function fetchPlaylistList( $uri )
	{
		$xml = $this->loadXML( $uri );

		if ( $xml )
		{
			$counts = $xml->children( self::OPEN_SEARCH_NAMESPACE );
			$playlistCount = (string)$counts->totalResults; 
			$title = (string)$xml->title;
			$playlistListData=array();

			if ( $playlistCount > 0 )
			{
				foreach ( $xml->entry as $entry ) 
				{
					$playlistEntryInfo = $this->fetchplaylistMetaData( $entry, true );
					
					if ( $playlistEntryInfo['result']  )
					{
						$playlistListData[] = $playlistEntryInfo['result'];
					}
					else
					{
						$playlistCount = $playlistCount-1;
					}
				}

				if ( $playlistCount > 999 )
					$playlistCount = 999;

				if ( $playlistCount > 0 )
					return array( 'result' => array( 'list' => array_slice( $playlistListData, 0, 999 ), 'count' => $playlistCount, 'title' => $title ) );	
			}

		}

		return array( 'result' => false );	
	}
	
	
	function fetchUserList( $uri )
	{
		$xml = $this->loadXML( $uri );

		if ( $xml )
		{
			$counts = $xml->children( self::OPEN_SEARCH_NAMESPACE );
			$userCount = (string)$counts->totalResults; 
			$title = (string)$xml->title;
			$userListData=array();

			if ( $userCount > 0 )
			{
				foreach ( $xml->entry as $entry ) 
				{
					
					$userID = (string)$entry->author->name;
					$userEntryInfo = $this->getUserProfile( $userID, true );
									
					if ( $userEntryInfo['result'] )
					{
						$userListData[] = $userEntryInfo['result'];
					}
					else
					{
						$userCount = $userCount-1;
					}
				}

				if ( $userCount > 999 )
					$userCount = 999;

				if ( $userCount > 0 )
					return array( 'result' => array( 'list' => array_slice( $userListData, 0, 999 ), 'count' => $userCount, 'title' => $title ) );	
			}

		}

		return array( 'result' => false );	
	}

	
	function fetchplaylistMetaData( $playlist )
	{
		$at = $playlist->children( self::ATOM_NAMESPACE );
		if ( $at)
		{
			$id = (string)$at->id;
			$ids = explode( ":", $id );
			if ( $ids )
			{
				$playlistMetaDataArray['id'] = $id = end( $ids );
			}
			
			$playListInfo  = self::getPlaylistVideoFeed( $id, 0, 4 );
			
			if ( $playListInfo )
			{
				$playlistMetaDataArray['video'] =  $playListInfo['result']['video_list'];
				$playlistMetaDataArray['author'] = $playListInfo['result']['user_info'];
				$playlistMetaDataArray['info'] = $playListInfo['result']['playlist_info'];
				
				return array( 'result' => $playlistMetaDataArray );
			}

		}
		
		return false;
	}
	

	function fetchVideoMetaData( $video, $minimal = false )
	{

		if ( $video )
		{
			$videoID = $this->getVideoID( $video );
			if ( !$videoID )
				return false;
				
			$videoMetaDataArray['id'] = $videoID;
			$videoMetaDataArray['flash_url'] = (string)$this->findFlashUrl( $video );
			
			$media = $video->children( self::YAHOO_MEDIA_NAMESPACE );
			if ( $media )
			{
				$videoMetaDataArray['title'] = (string)$media->group->title;
				$videoMetaDataArray['description'] =  (string)$media->group->description;			
				$videoMetaDataArray['published'] = $this->convertYoutubeDate( $video->published );
				$videoMetaDataArray['uploaded'] = $this->convertYoutubeDate( $video->published );
				
				if ( !$videoMetaDataArray['uploaded'] )
				{
					$videoMetaDataArray['uploaded'] = $this->convertYoutubeDate( $video->updated );
				}
				
				if ( $media->group )
				{
					$videoMetaDataArray['license'] = (string)$media->group->license;
					$videoMetaDataArray['credit'] = (string)$media->group->credit;
					$videoMetaDataArray['keywords'] = $videoMetaDataArray['tags'] = explode( "," , trim( (string)$media->group->keywords ) );
					$videoMetaDataArray['category'] = "";

					$videoThumbnailsList = $media->group->thumbnail;
					if ( count( $videoThumbnailsList ) > 0 )
					{
						$videoThumbnailArray = array();
						foreach( $videoThumbnailsList as $videoThumbnail )
						{
							$uri = (string)$videoThumbnail->attributes()->url;
							$ini = eZINI::instance( 'eztube.ini' );
							$imageCacheArray = $ini->variable( 'CacheSettings', 'CacheImageList' );
							if ( in_array( basename($uri), $imageCacheArray) )
								$videoThumbnailArray[] = EZTubeImageCache::geteZTubeImageCacheByURI( $uri );
						}
					}
					$videoMetaDataArray['thumbnails'] = $videoThumbnailArray;
				}
				
				foreach( $media->group->category  as $category )
				{
					$videoMetaDataArray['categories'][]=(string)$category;
				}
				
				$videoMetaDataArray['user_name'] = $media->group->player->attributes()->url;
			}
			
			
			$at = $video->children( self::ATOM_NAMESPACE );
			if ( $at )
			{
				$videoMetaDataArray['user_name'] = (string)$at->author->name;
				$videoMetaDataArray['uploader'] = (string)$at->author->name;
				$videoMetaDataArray['user_id'] = (string)$at->author->name;
			}
					
			$yt = $media->children( self::YOUTUBE_NAMESPACE );
			if ( $yt )
			{
				$seconds = $yt->duration->attributes();
				$mins = floor ($seconds / 60);
				$secs = $seconds % 60;
				$videoMetaDataArray['duration'] = $mins.":".$secs;
			}
			
			$gd = $video->children( self::GOOGLE_NAMESPACE ); 
			if ( $gd )
			{
				if ( $gd->rating ) 
				{
					$rating = $gd->rating->attributes();
					$videoMetaDataArray['rating'] = (string)$rating['average']; 
					$videoMetaDataArray['rating_count'] = (string)$rating['numRaters'];
				}
				else
				{
					$videoMetaDataArray['rating'] = 0;
					$videoMetaDataArray['rating_count'] = 0;
				}

				if ($gd->comments->feedLink) 
				{ 
					$videoMetaDataArray['comments'] = (string)$gd->comments->feedLink->attributes()->countHint; 
				}
			}
			 
			$yt = $video->children( self::YOUTUBE_NAMESPACE );
			
			if ( $yt )
			{
			
				if ( $yt->rating )
				{
					$videoMetaDataArray['likes'] = (string)$yt->rating->attributes()->numLikes;
					$videoMetaDataArray['dislikes'] = (string)$yt->rating->attributes()->numDislikes;
				}
				else
				{
					$videoMetaDataArray['likes'] = 0;
					$videoMetaDataArray['dislikes'] = 0;
				}
				
				if ($yt->statistics)
				{
					$videoMetaDataArray['view_count'] = (string)$yt->statistics->attributes()->viewCount;
					$videoMetaDataArray['favorite_count'] = (string)$yt->statistics->attributes()->favoriteCount;
				}
				else
				{
					$videoMetaDataArray['view_count'] = 0;
					$videoMetaDataArray['favorite_count'] = 0;
				}
				
				if ( $yt->location )
				{
					$videoMetaDataArray['location'] = (string)$yt->location->attributes();

				}
				
				if ( $yt->state )
				{
					$videoMetaDataArray['state'] = (string)$yt->state;	
				}
				
				if ( $yt->recorded )
				{
					$videoMetaDataArray['recorded'] = (string)$yt->recorded;	
				}
				
				if ( $yt->accessControl )
				{
					foreach ( $yt->accessControl as $access )
					{
						$action = (string)$access->attributes()->action;
						$permission = (string)$access->attributes()->permission;
						$videoMetaDataArray['access'][$action]=$permission;
					}
				}
				
			}
			
			$eztubefilter = new EZTubeFilter();
			$allowed = $eztubefilter->VideoFilter( $videoID, $videoMetaDataArray['user_id'], $videoMetaDataArray['categories'], $videoMetaDataArray['tags'] );
			
			if ( !$allowed )
				return array( 'result' => false );	
			
			if ( $minimal )
				return array( 'result' => $videoMetaDataArray );
			
			
			$commentListArray = $this->getVideoCommentFeed( $videoID, 0, 25 );
			
			$videoMetaDataArray['comments'] = array();
			if ( $commentListArray['result'] )
				$videoMetaDataArray['comments'] = $commentListArray['result'];
			
		
			$relatedVideoListArray = $this->getRelatedVideoFeed( $videoID, 0, 25 );
			$videoMetaDataArray['related_videos'] = array();
			if ( $relatedVideoListArray['result'] )
				$videoMetaDataArray['related_videos'] = $relatedVideoListArray['result'];
			
	
			$videoResponsesListArray = $this->getVideoResponseFeed( $videoID, 0, 25 );
			$videoMetaDataArray['video_responses'] = array();
			if ( $videoResponsesListArray )
				$videoMetaDataArray['video_responses'] = $videoResponsesListArray['result'];
					
			return array( 'result' => $videoMetaDataArray );
		}
		
		return array( 'result' => false );
	}
	

	function getVideoCommentFeed( $id = null, $offset = 0, $limit = 25, $sortorder = 'relevance' )
	{		
		$offset++;
		if ( $offset ) $offset = "&start-index=" . $offset;
		if ( $limit ) $limit = "&max-results=" . $limit;
		
		if ($id !== null) 
		{
			$id = rawurlencode( $id );
			$uri = self::VIDEO_URI . "/" . $id . "/" . self::COMMENTS_URI_SUFFIX . "?v=2" . $offset . $limit . "&orderby=" . $sortorder . $this->key;
			$xml = $this->loadXML( $uri );
		}
			
		
		if ( $xml )
		{
			$counts = $xml->children( self::OPEN_SEARCH_NAMESPACE );
			$commentsCount = (string)$counts->totalResults; 
			
			if ( $commentsCount > 0 )
			{
				foreach ( $xml->entry as $entry ) 
				{
					foreach( $entry->link as $link )
					{
						$reply=false;
						if ( $link->attributes()->rel == self::COMMENT_REPLY_REL )
						{
							$reply = (string)$link->attributes()->href;
						}	
					}
					
					$commentsListArray[] = array( 'title'=> (string)$entry->title, 'description' => (string)$entry->content, 'published' => $this->convertYoutubeDate( (string)$entry->published ), 'author_name' => (string)$entry->author->name, 'author_uri' => (string)$entry->author->uri, 'in_reply_to' => $reply );
				}

				return array( 'result' => array( 'comments_list' => $commentsListArray, 'comments_count' => $commentsCount ) );
			}
		}
		
		return array( 'result' => false );
	}				
	
	
	function getRelatedVideoFeed( $id = null, $offset = 0, $limit = 25, $sortorder = 'relevance'  )
	{
		$offset++;
		if ( $offset ) $offset = "&start-index=" . $offset;
		if ( $limit ) $limit = "&max-results=" . $limit;
	
		if ($id !== null) 
		{
			$id = rawurlencode( $id );
			$uri = self::VIDEO_URI . "/" . $id . "/" . self::RELATED_URI_SUFFIX . "?v=2" . $offset . $limit . "&orderby=" . $sortorder. $this->filters . $this->key;
			$videoList = $this->fetchVideoList( $uri );
		} 
		
		if ( $videoList )
		{			
			if ( $videoList['result']['count'] > 0 )
			{				
				return array( 'result' => array( 'video_list' => $videoList['result']['list'], 'video_count' => $videoList['result']['count'] ) );
			}
		}
		
		return array( 'result' => false );
	}
	
	
	function getVideoResponseFeed( $id = null, $offset = 0, $limit = 25, $sortorder = 'relevance'  )
	{
		$offset++;
		if ( $offset ) $offset = "&start-index=" . $offset;
		if ( $limit ) $limit = "&max-results=" . $limit;
		
		if ($id !== null) 
		{
			$id = rawurlencode( $id );
			$uri = self::VIDEO_URI . "/" . $id . "/" . self::RESPONSES_URI_SUFFIX . "?v=2" . $offset . $limit . "&orderby=" . $sortorder. $this->key;
			$videoList = $this->fetchVideoList( $uri );
		} 
				
		if ( $videoList )
		{
			if ( $videoList['result']['count'] > 0 )
			{
				return array( 'result' => array( 'video_list' => $videoList['result']['list'], 'video_count' => $videoList['result']['count'] ) );
			}
		}

		return array( 'result' => false );
	}
	
	
	function fetchVideosByTag( $tag = null, $filters = true, $offset = 0, $limit = 25, $sortorder = 'relevance'  )
	{
		$offset++;
		$tag = "+".urldecode( $tag );
		$type = "videos";
		if ($tag !== null) 
		{			
			$videoList = $this->searchYouTube( $tag, $type, $filters, null, $offset, $limit, $sortorder );
			return $videoList;
		}	
		
		return array( 'result' => false );
	}
	
	
	function getUserFavorites( $id = null, $offset = 0, $limit = 25, $sortorder = 'relevance' )
	{
		$offset++;
		if ( $offset ) $offset = "&start-index=" . $offset;
		if ( $limit ) $limit = "&max-results=" . $limit;
		
		if ($id !== null) 
		{
			$id = rawurlencode( $id );
			
			$eztubefilter = new EZTubeFilter();
			$allowed = $eztubefilter->UserFilter( $id );
			if ( !$allowed )
				return array( 'result' => false );
			
			$uri = self::USER_URI . "/" . $id . "/" . self::FAVORITES_URI_SUFFIX . "?v=2" . $offset . $limit ."&orderby=" . $sortorder . $this->key;
			$videoList = $this->fetchVideoList( $uri );
		}
		
		if ( $videoList )
		{
			$userInfoArray = $this->getUserProfile( $id, true );
			$userInfo = $userInfoArray['result'];
			
			$videoListData = $videoList['result']['list'];
			$videoCount = $videoList['result']['count'];
			return array( 'result' => array( 'user_id' => $id, 'video_list' => $videoListData, 'video_count' => $videoCount, 'user_info' => $userInfo ) );	
		}
		
		return array( 'result' => false );	
	}
	
	
	function getUserUploads( $id = null, $offset = 0, $limit = 25, $sortorder = ''  )
	{
		$offset++;
		if ( $offset ) $offset = "&start-index=" . $offset;
		if ( $limit ) $limit = "&max-results=" . $limit;
	
		if ($id !== null) 
		{
			$id = rawurlencode( $id );
			
			$eztubefilter = new EZTubeFilter();
			$allowed = $eztubefilter->UserFilter( $id );
			if ( !$allowed )
				return array( 'result' => false );
				
			if ( $sortorder!='' )
				$sortorder = "&orderby=" . $sortorder;	
			$uri = self::USER_URI . "/" .  $id . "/" . self::UPLOADS_URI_SUFFIX . "?v=2" . $offset . $limit . $sortorder . $this->key;
			$videoList = $this->fetchVideoList( $uri );
		}
		
		
		if ( $videoList )
		{
			$userInfoArray = $this->getUserProfile( $id, true );
			$userInfo = $userInfoArray['result'];
			
			$videoListData = $videoList['result']['list'];
			$videoCount = $videoList['result']['count'];
			return array( 'result' => array( 'user_id' => $id, 'video_list' => $videoListData, 'video_count' => $videoCount, 'user_info' => $userInfo ) );	
		}

		return array( 'result' => false );	
	}
	

	function getPlaylistListFeed( $id = null, $offset = 0, $limit = 25  )
	{		
		$offset++;
		if ( $offset ) $offset = "&start-index=" . $offset;
		if ( $limit ) $limit = "&max-results=" . $limit;
		
		if ($id !== null) 
		{
			$id = rawurlencode( $id );
			
			$eztubefilter = new EZTubeFilter();
			$allowed = $eztubefilter->UserFilter( $id );
			if ( !$allowed )
				return array( 'result' => false );
			
			$uri = self::USER_URI . "/" .  $id . "/" . self::PLAYLISTS_URI_SUFFIX . "?v=2" . $offset . $limit . $this->key;
			$xml = $this->loadXML( $uri );
		}
				
		if ( $xml )
		{
			$counts = $xml->children( self::OPEN_SEARCH_NAMESPACE );
			$playlistsCount = (string)$counts->totalResults; 

			if ( $playlistsCount > 0 )
			{
				$userInfoArray = $this->getUserProfile( $id, true );
				$userInfo = $userInfoArray['result'];
				
				foreach ( $xml->entry as $entry ) 
				{					
					$playlistMetaDataArray = self::fetchplaylistMetaData( $entry );
					$playListData[] = $playlistMetaDataArray;

				}
				return array( 'result' => array( 'user_id' => $id, 'playlists_list' => $playListData, 'playlists_count' =>  $playlistsCount, 'user_info' => $userInfo ) );
			}
		}
				
		return array( 'result' => false );
	}


	function getPlaylistVideoFeed( $id = null, $offset = 0, $limit = 25, $sortorder = "position"  )
	{
		
		$offset++;
		if ( $offset ) $offset = "&start-index=" . $offset;
		if ( $limit  ) $limit = "&max-results=" . $limit;
		
		if ( isset( $sortorder ) ) $sortorder = "&orderby=" . $sortorder;
		
		if ($id !== null) 
		{
			$id = rawurlencode( $id );
			$uri =  self::PLAYLIST_URI ."/" . $id . "?v=2" . $offset . $limit . $sortorder . $this->key;
			$videoList = $this->fetchVideoList( $uri );
		}
		
		if ( $videoList )
		{
			$videoListData = $videoList['result']['list'];
			$videoCount = $videoList['result']['count'];
		}
		
		
		$xml = $this->loadXML( $uri );
		$playListInfo = false;

		if ( $xml )
		{
			$yt = $xml->children( self::YOUTUBE_NAMESPACE );
			if ( $yt )
			{
				$playListInfo['id'] = (string)$yt->playlistId;
			}
			
			$counts = $xml->children( self::OPEN_SEARCH_NAMESPACE );
			
			$videoCount = $playListInfo['count'] = (string)$counts->totalResults; 
			
			$media = $xml->children( self::YAHOO_MEDIA_NAMESPACE );
			$playListInfo['duration'] = 0;
			if ( $media )
			{
				$yt = $media->children( self::YOUTUBE_NAMESPACE );
				if ( $yt )
				{
					$seconds = $yt->duration->attributes();
					
					$hours = intval(intval($seconds) / 3600);
					if($hours > 0)
					{
						$playListInfo['duration'] .= "$hours:";
					}
					
					$minutes = bcmod((intval($seconds) / 60),60);
					if($hours > 0 || $minutes > 0)
					{
						$playListInfo['duration'] .= "$minutes:";
					}
					  
					$seconds = bcmod(intval($seconds),60);
    					$playListInfo['duration'] .= "$seconds";
				}
			}
						
			$at = $xml->children( self::ATOM_NAMESPACE );
			if ( $at )
			{		
				$playListInfo['title'] = (string)$at->title;
				$playListInfo['subtitle'] = (string)$at->subtitle;
				$playListInfo['updated'] = $this->convertYoutubeDate( (string)$at->updated );
				$playListInfo['author']['name'] = (string)$at->author->name;
				$playListInfo['author']['uri'] = (string)$at->author->uri;
				$playListInfo['author']['id'] = (string)$at->author->name;
				$author_id = $playListInfo['author']['name'];
			}
			
			
			$userInfo = false;			
			if ( isset( $author_id ) )
			{
				$userInfoArray = $this->getUserProfile( $author_id, true );
				$userInfo = $userInfoArray['result'];
			}
			
			foreach ($xml->category as $category)
			{
				$playListInfo['category'][]=(string)$category->attributes()->term;
			}
			
			
			if ( $playListInfo and $userInfo )
				return array( 'result' => array( 'playlist_id' => $id, 'video_list' => $videoListData, 'playlist_info' => $playListInfo, 'video_count' => $videoCount, 'user_info' => $userInfo ) );

		}
		else
		{
			return array( 'result' => false );
		}
	}
	

	function searchYouTube( $keywords = null, $searchtype = "videos", $filters = true, $params = null, $offset = 0, $limit = 25, $sortorder = 'relevance' )
	{
		if ( !$offset ) $offset = 0;
		if ( !$limit ) $limit = 25;
		$offset++;
		
		$ini = eZINI::instance( 'eztube.ini' );
		$tubeQueryString = $userQueryString = $searchParamQuery = "";
		
		$filterArray = array();
		if ( $filters == true) 
		{
			$filterArray = $ini->variable( 'StandardSearchFilters', 'Filter' );
		}
				
		$searchParams['q'] = rawurlencode( $keywords );
		if ( $searchtype != "users" and $searchtype != "playlists" ) 
			$searchtype = "videos";
		
		$searchParams['search_type'] = $searchtype;
		
		if ( $sortorder == "viewCount" )
		{
			$sortby = 1;
		}
		else if ( $sortorder == "rating" )
		{
			$sortby = 2;
		}
		else if ( $sortorder == "published" )
		{
			$sortby = 3;
		}
		else
		{
			$sortby = 4;
		}
		
		$searchParams['sortby'] = $sortby;
		if ( $params !== null )
		{
			foreach( $params as $key => $param )
			{
				if ( is_int( $key ) )
				{
					//only applies to parameters defined in fetch.ini
					$values = explode( "=", $param  );
					if ( is_array( $values ) )
					{
						if ( isset ( $values[0] ) and isset ($values[1] ) )
						{
							$key = $values[0];
							$searchParams[$key] = rawurlencode( $values[1] );
						}
					}
				}
				else
				{				
					if ( $key != "search" )
						$searchParams[$key] = rawurlencode( $param );
				}
			}			
		}
		
		foreach ( $searchParams as $key=>$value )
		{
		    $searchParamQuery .= $key . '=' . $value . '&';
		}
		
		$searchParamQuery = rtrim( $searchParamQuery, '&' ); 		
					
		$combined = array_merge_recursive( $searchParams, $filterArray );
		foreach ( $combined as $key => $parameter )
		{
			if ( is_array( $parameter ) )
			{
				$parameter = array_unique( $parameter );

				if ( $key == "q" AND count( $parameter ) > 1  )
				{
					$parameter = implode( "%20", $parameter );
				}
				else
				{
					$parameter = $parameter[0];
				}
			}
			
			$queryArray[$key] = $parameter;
		}
		
		foreach ( $queryArray as $key=>$value )
		{
		   $tubeQueryString .= $key . '=' . $value . '&';
		}
				
		$tubeQueryString = rtrim( $tubeQueryString, '&' ); 
		
		$userQueryArray['view'] = "/eztube/search";
		$userQueryArray['parameters']= array('offset' => ( $offset-1 ), 'limit' => $limit, 'sortby' => $sortby ); 
		$userQueryArray['query'] = "?" . $searchParamQuery;
		$autoFill = $ini->variable( 'SearchSettings', 'UseAutoFill' );
		$maxIterations = $ini->variable( 'SearchSettings', 'MaxIterations' );
		$i=0;

		
		if ( $searchtype == "playlists" )
		{
			$uri = self::PLAYLIST_SEARCH_URI . "?v=2&q=" . $searchParams['q']. "&start-index=" . $offset . "&max-results=" . $limit . $this->key;	
			$searchResult = $this->fetchPlaylistList( $uri );

		}
		elseif ( $searchtype == "users" )
		{
			$uri = self::USER_SEARCH_URI . "?v=2&q=" . $searchParams['q']. "&start-index=" . $offset . "&max-results=" . $limit . "&orderby=" . $sortorder . $this->key;
			$searchResult = $this->fetchUserList( $uri );
			
			if ( $autoFill === "enabled" )
			{

				if ( count( $searchResult['result']['list'] ) <  $limit )
				{
					$offset = $offset + $limit;
					do 
					{
						$i++;
						$offset++;
						$uri = self::USER_SEARCH_URI . "?v=2&q=" . $searchParams['q']. "&start-index=" . $offset . "&max-results=" . $limit . "&orderby=" . $sortorder . $this->key;
						$additionalSearchResult = $this->fetchUserList( $uri );
						if ( $additionalSearchResult['result']['list'] )
						{
							foreach( $additionalSearchResult['result']['list'] as $additional )
							{
								$searchResult['result']['list'][] = $additional;

								if ( count($searchResult['result']['list']) == $limit )
									break;
							}
						}
					} 
					while ( count( $searchResult['result']['list'] ) < $limit and $i < $maxIterations  );
				}
			}
		}
		else
		{		
			$uri = self::VIDEO_URI . "?v=2&" . $tubeQueryString. "&start-index=" . $offset . "&max-results=" . $limit . "&orderby=" . $sortorder . $this->key;	
			$searchResult = $this->fetchVideoList( $uri );
			
			if ( $autoFill === "enabled" )
			{
			
				if ( count( $searchResult['result']['list'] ) <  $limit )
				{
					$offset = $offset + $limit;
					do 
					{
						$i++;
						$offset++;
						$uri = self::VIDEO_URI . "?v=2&" . $tubeQueryString. "&start-index=" . $offset . "&max-results=" . $limit . "&orderby=" . $sortorder . $this->key;	
						$additionalSearchResult = $this->fetchVideoList( $uri );

						if ( $additionalSearchResult['result']['list'] )
						{
							foreach( $additionalSearchResult['result']['list'] as $additional )
							{
								$searchResult['result']['list'][] = $additional;

								if ( count($searchResult['result']['list']) == $limit )
									break;
							}
						}
					} 
					while ( count( $searchResult['result']['list'] ) < $limit and $i < $maxIterations  );
				}
			}
			
		}
		
		if ( $searchResult['result'] )
		{
			$resultListData = $searchResult['result']['list'];
			$resultCount = $searchResult['result']['count'];
			$title = $searchResult['result']['title'];
			return array( 'result' => array( 'result_list' => $resultListData, 'result_count' => $resultCount, 'title' => $title, 'query' => $userQueryArray, 'keyword' => htmlspecialchars( $keywords ), 'youtube_uri' => $uri, 'type' => $searchtype, 'get_array' => $queryArray ) );	
		}
		
		return array( 'result' => false );
	}
		
		
	function getUserProfile( $id = null, $minimal = false )
	{
		if ( $id !== null ) 
		{
			$uri = self::USER_URI . "/" . $id ."?v=2" . $this->key;	
			$xml = $this->loadXML( $uri );
			$result = array();
			
			if ( $xml )
			{
				$at = $xml->children( self::ATOM_NAMESPACE );
				
				if ( $at )
				{
					$result['id'] = $id;
					$result['published'] = $this->convertYoutubeDate( (string)$at->published );
					$result['member_since']  = $this->convertYoutubeDate( (string)$at->published );
					$result['last_update'] = $this->convertYoutubeDate( (string)$at->updated );
					$youtubeDisplayName = explode( ":", (string)$at->title );
					$result['display_name'] = trim( $youtubeDisplayName[1] );
					$result['content'] = (string)$at->content;
					$result['website'] = false;
					foreach( $at->link as $link )
					{
						if ( $link->attributes()->rel == "related" )
						{
							$result['website'] = (string)$link->attributes()->href;
						}	
					}

				}
				
				$yt = $xml->children( self::YOUTUBE_NAMESPACE );
				
				if ( $yt )
				{
					$result['age'] = (string)$yt->age;
					$result['gender'] = (string)$yt->gender;
					$result['location'] = (string)$yt->location;
					$result['user_name'] = (string)$yt->username;
					$result['first_name'] = (string)$yt->firstName;
					$result['last_name'] = (string)$yt->lastName;
					$result['hometown'] = (string)$yt->hometown;
					$result['company'] = (string)$yt->company;
					$result['occupation'] = (string)$yt->occupation;
					$result['school'] = (string)$yt->school;
					$result['hobbies'] = (string)$yt->hobbies;
					$result['books'] = (string)$yt->books;
					$result['movies'] = (string)$yt->movies;
					$result['music'] = (string)$yt->music;
					$result['about'] = (string)$yt->aboutMe;
					$result['relation'] = (string)$yt->relationship;
					$result['last_login'] = $this->convertYoutubeDate( $yt->statistics->attributes()->lastWebAccess );
					$result['subscriber_count'] = (string)$yt->statistics->attributes()->subscriberCount;
					$result['video_watch_count'] = (string)$yt->statistics->attributes()->videoWatchCount;
					$result['view_count'] = (string)$yt->statistics->attributes()->viewCount;
					$result['total_upload_views'] = (string)$yt->statistics->attributes()->totalUploadViews;					
				}
				
				$media = $xml->children( self::YAHOO_MEDIA_NAMESPACE );
				
				if ( $media )
				{
					if ( $media->thumbnail )
					{
						
						$uri = (string)$media->thumbnail[0]->attributes()->url;
						$result['thumbnail'] =  EZTubeImageCache::geteZTubeImageCacheByURI( $uri );
					}
				}
				
				$gd = $xml->children( self::GOOGLE_NAMESPACE ); 
				if ( $gd )
				{
					foreach( $gd->feedLink as $link )
					{
						switch ( $link->attributes()->rel ) 
						{
						    case self::USER_FAVORITES_REL:
						        $result['favorite_count'] = (string)$link->attributes()->countHint;
						        break;
						    case self::USER_CONTACTS_REL:
						        $result['contact_count'] = (string)$link->attributes()->countHint;
						        break;
						    case self::USER_SUBSCRIPTIONS_REL:
						    	$result['subscription_count'] = (string)$link->attributes()->countHint;
						        break;
						    case self::USER_UPLOADS_REL:
						    	$result['upload_count'] = (string)$link->attributes()->countHint;
						        break;
						}
					}
				}
				
				
				$eztubefilter = new EZTubeFilter();
				$allowed = $eztubefilter->UserFilter( $id );
				if ( !$allowed )
					return array( 'result' => false );
				
				if ( $minimal )
				{
					return array( 'result' => $result );
				}
				
				$videoUploadArray = $this->getUserUploads( $id, 0, 25 );
				if ( $videoUploadArray )
				{
					$result['latest_uploads'] = $videoUploadArray['result']['video_list'];
				}
				else
				{
					$result['latest_uploads'] = 0;
				}
							
				$contacts = $this->getContactFeed( $id, 0, 25 );
				if ( $contacts )
				{
					$result['contacts'] = $contacts['result'];
				}
				else
				{
					$result['contacts'] = 0;
				}
				
				return array( 'result' => $result );
			}			
		}
		
		return array( 'result' => false );
	}
	
	
	function getContactFeed( $id = null, $offset = 0, $limit = 25, $sortorder = 'relevance' )
	{
		
		$offset++;
		if ( $offset ) $offset = "&start-index=" . $offset;
		if ( $limit  ) $limit = "&max-results=" . $limit;
	
		if ($id !== null) 
		{
			$id = rawurlencode( $id );
			$uri = self::USER_URI . "/" . $id . "/" . self::CONTACTS_URI_SUFFIX . "?v=2" . $offset . $limit . "&orderby=" . $sortorder . $this->key;
			$xml = $this->loadXML( $uri );
		}
					
				
		if ( $xml )
		{
			$counts = $xml->children( self::OPEN_SEARCH_NAMESPACE );
			$contactsCount = (string)$counts->totalResults; 
		
			if ( $contactsCount > 0 )
			{
				foreach ( $xml->entry as $entry ) 
				{
					$userID = (string)$entry->title;
					if ( $userID )
					{
						$contact = $this->getUserProfile( $userID, true );
						if ( $contact['result'] )
						{
							$contactsListArray[] = $contact['result'];	
						}
						else
						{
							$contactsCount = $contactsCount - 1;	
						}
					}
				}
				
				$userInfoArray = $this->getUserProfile( $id, true );
				$userInfo = $userInfoArray['result'];
				
				if ( $contactsCount > 0 )
					return array( 'result' => array( 'contacts_list' => $contactsListArray, 'contacts_count' => $contactsCount, 'user_info' => $userInfo ) );
			}
		
		}
		
		return array( 'result' => false );
	}
	
	
	function getAvailableCategories( $language = null )
	{
		$uri = self::CATEGORIES_URI;
		
		if ( $language !== null )
		{
			$ini = eZINI::instance( 'eztube.ini' );
			$availableLanguages = $ini->variable( 'LocaleSettings', 'LocalizedCategory' );
			$preferredLanguage = $ini->variable( 'LocaleSettings', 'PreferredLanguage' );
			
			if ( in_array( $language, $availableLanguages ) )
			{
				$uri = $uri . "?hl=" . $language;
			}
			else
			{
				if ( isset( $preferredLanguage ) ) 
				{
					$uri = $uri . "?hl=" . $preferredLanguage;
				}
			}
		}
		
		$xml = $this->loadXML( $uri );
		if ($xml)
		{
			$at = $xml->children( self::ATOM_NAMESPACE );
			foreach ( $at->category as $category )
			{
				$term = (string)$category->attributes()->term;
				$label = (string)$category->attributes()->label;
				$categorylist[$term]=$label;
				$terms[]=$term;
				$labels[]=$label;
			}
			
			return array( 'result' => array( 'category_list' => $categorylist, 'terms' => $terms, 'labels' => $labels ) );
		}
		
		return array( 'result' => false );
	}
	
	
	function getStandardFeeds( $type = null, $time = null, $region = null, $category = null, $limit = 25 )
	{
		$ini = eZINI::instance( 'eztube.ini' );
		$availableRegions = $ini->variable( 'LocaleSettings', 'AvailableLocale' );
		$preferredLocale = $ini->variable( 'LocaleSettings', 'PreferredLocale' );
		
		if ($type !== null) 
		{
			$type = rawurlencode( $type );
			switch ( $type ) 
			{
				case 'rated':
				$suffix = self::STANDARD_TOP_RATED_SUFFIX;
				break;

				case 'favorites':
				$suffix = self::STANDARD_TOP_FAVORITES_SUFFIX;
				break;

				case 'viewed':
				$suffix = self::STANDARD_MOST_VIEWED_SUFFIX;
				break;

				case 'shared':
				$suffix = self::STANDARD_MOST_SHARED_SUFFIX;
				break;

				case 'recent':
				$suffix = self::STANDARD_MOST_RECENT_SUFFIX;
				break;

				case 'responded':
				$suffix = self::STANDARD_MOST_RESPONDED_SUFFIX;
				break;

				case 'recently':
				$suffix = self::STANDARD_RECENTLY_FEATURED_SUFFIX;
				break;

				case 'discussed':
				$suffix = self::STANDARD_MOST_DISCUSSED_SUFFIX;
				break;

				case 'trending':
				$suffix = self::STANDARD_TRENDING_VIDEOS_SUFFIX;
				break;

				default;
				$suffix = self::STANDARD_MOST_POPULAR_SUFFIX;
				break;			
			}
		}
		else
		{
			$suffix = self::STANDARD_TOP_RATED_SUFFIX;
		}
		
		if ($time !== null)
		{
			switch ( $time )
			{
				case 'day':
				$time = "&time=today";

				case 'week':
				$time = "&time=this_week";

				case 'month':
				$time = "&time=this_month";			

				default;
				$time = "";
				break;
			}
		}
		else
		{
			$time = "";
		}
		
		if ($region !== null)
		{
			$region = rawurlencode( $region );
			if ( !in_array( $region, $availableRegions ) OR  isset( $preferredLocale ) )
			{
				if ( isset( $preferredLocale ) and $preferredLocale != "" ) 
				{
					$region = $preferredLocale;
				}
			}

			$region = "/" . $region;
		}
		else
		{
			$region = "";
		}
		
		if ($category !== null)
		{
			$category = ucfirst( rawurlencode( $category ) );
			$categoryResultArray = $this->getAvailableCategories();
			$categoryTermsArray = $categoryResultArray['result']['terms'];
			if ( in_array( $category, $categoryTermsArray ) )
			{
				$category = "_" .$category;
			}
		}
		else
		{
			$category = "";
		}
		
				
		$uri = self::STANDARD_URI . $region . "/" . $suffix . $category . "?v=2" . $time . "&max-results=" . $limit . $this->key;		
		$videoList = $this->fetchVideoList( $uri );
		
		if ( $videoList )
		{
			$videoListData = $videoList['result']['list'];
			$videoCount = $videoList['result']['count'];
			$title = $videoList['result']['title'];
			return array( 'result' => array( 'video_list' => $videoListData, 'video_count' => $videoCount, 'title' => $title ) );	
		}
		
		return array( 'result' => false );		
	}
	
	
	function convertYoutubeDate( $youtubedate )
	{
		return strtotime( substr( $youtubedate,0,strlen( $youtubedate )-1 ) );
	}
			
}

?>
