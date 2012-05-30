<?php

/**
 * File containing the eZTubeFilter class.
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 */


class EZTubeFilter
{	
	function VideoFilter( $id = null, $user_id = null, $categories = false, $tags = false )
	{
		if ( $id !== null )
		{
			$ini = eZINI::instance( 'eztube.ini' );
			
			if ( $ini->hasVariable( 'FilterRules', 'VideoFilterRule' ) AND $ini->hasVariable( 'FilterRules', 'VideoFilter' ) )
			{
			
				$filterRule = $ini->variable( 'FilterRules', 'VideoFilterRule' );
				
				if ( $filterRule == "deny" )
				{
					$allowVideos = $ini->variable( 'FilterRules', 'VideoFilter' );
					if ( !in_array( $id, $allowVideos ) ) return false;
				}
				else
				{
					$denyVideos = $ini->variable( 'FilterRules', 'VideoFilter' );
					if ( in_array( $id, $denyVideos ) ) return false;
				}
			}
		}
				
		$allowedUser = self::UserFilter( $user_id );
		if ( !$allowedUser ) return false;

		$allowedCategory = self::CategoryFilter( $categories );
		if ( !$allowedCategory ) return false;

		$allowedTag = self::TagFilter( $tags );
		if ( !$allowedTag ) return false;
		
		return true;
	}
	
	
	function UserFilter( $id = null )
	{
		if ( $id != null )
		{
			$ini = eZINI::instance( 'eztube.ini' );
			
			if ( $ini->hasVariable( 'FilterRules', 'UserFilterRule' ) AND $ini->hasVariable( 'FilterRules', 'UserFilter' ) )
			{
				$filterRule = $ini->variable( 'FilterRules', 'UserFilterRule' );

				if ( $filterRule == "deny" )
				{
					$allowUsers = $ini->variable( 'FilterRules', 'UserFilter' );
					if ( !in_array( $id, $allowUsers ) ) return false;
				}
				else
				{
					$denyUsers = $ini->variable( 'FilterRules', 'UserFilter' );
					if ( in_array( $id, $denyUsers ) ) return false;
				}
			}
		}
		
		return true;		
	}
	
	
	function ProfileFilter( $id = null )
	{
		$eztube = new EZTube();
		if ( $id != null )
		{
			$ini = eZINI::instance( 'eztube.ini' );
			$searchtype = "videos";
			$params['author'] = $id;
			
			if ( $ini->hasVariable( 'FilterRules', 'ProfileFilterRule' ) AND $ini->hasVariable( 'FilterRules', 'ProfileFilter') )
			{
				$filterRule = $ini->variable( 'FilterRules', 'ProfileFilterRule' );
				if ( $filterRule == "deny" )
				{
					$allowProfiles = implode( " ", $ini->variable( 'FilterRules', 'ProfileFilter' ) );
					$profile = $eztube->searchYouTube( $allowProfiles, $searchtype, false, $params );					
					if ( $profile['result']['result_count'] == 0 ) return false;					
				}
				else
				{
					$denyProfiles = implode( " ", $ini->variable( 'FilterRules', 'ProfileFilter' ) );
					$profile = $eztube->searchYouTube( $denyProfiles, $searchtype, false, $params );
					if ( $profile['result']['result_count'] > 0 ) return false;
				}
			}
		}
		
		return true;
	}
	
	
	function CategoryFilter( $categories = null )
	{
		if ( $categories != null )
		{
			$ini = eZINI::instance( 'eztube.ini' );
			
			if ( $ini->hasVariable( 'FilterRules', 'CategoryFilterRule' ) AND $ini->hasVariable( 'FilterRules', 'CategoryFilter' ) )
			{
				$filterRule = $ini->variable( 'FilterRules', 'CategoryFilterRule' );
		
				if ( $filterRule == "deny" )
				{
					$allowCategories = $ini->variable( 'FilterRules', 'CategoryFilter' );
					if ( !array_intersect( $categories, $allowCategories ) ) return false;
				}
				else
				{
					$denyCategories = $ini->variable( 'FilterRules', 'CategoryFilter' );
					if ( array_intersect( $categories, $denyCategories ) ) return false;
				}		
			}
		
		}
		
		return true;
	}
	
	
	function TagFilter( $tags = null )
	{
		if ( $tags != null )
		{
			if ( !is_array( $tags ) ) $tags = array( $tags );
			$tags = array_map( 'strtolower', $tags );
			$tags = array_map( 'trim', $tags );

			$ini = eZINI::instance( 'eztube.ini' );

			if ( $ini->hasVariable( 'FilterRules', 'TagFilterRule') AND $ini->hasVariable( 'FilterRules', 'TagFilter' ) )
			{
				$filterRule = $ini->variable( 'FilterRules', 'TagFilterRule' );

				if ( $filterRule == "deny" )
				{
					$allowTags = $ini->variable( 'FilterRules', 'TagFilter' );
					$allowTags = array_map( 'strtolower', $allowTags );
					if ( !array_intersect( $tags, $allowTags ) ) return false;
				}
				else
				{
					$denyTags = $ini->variable( 'FilterRules', 'TagFilter' );
					$denyTags = array_map( 'strtolower', $denyTags );
					if ( array_intersect( $tags, $denyTags ) ) return false;
				}		
			}

		}
		return true;
	}
}

?>
