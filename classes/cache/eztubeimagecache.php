<?php

/**
 * File containing the EZTubeImageCache class.
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 */

class EZTubeImageCache
{
	
	function __construct() 
	{
	}
	
	static function clearCache()
	{
		eZExpiryHandler::registerShutdownFunction();
		$expiryHandler = eZExpiryHandler::instance();
		$expiryHandler->setTimestamp( 'eztube-images', time() );
        	$expiryHandler->store();
	}
	
	
	public static function purgeCache()
	{
		$xmlCacheDirPath = eZSys::cacheDirectory() . "/eztube/images/";
		$fileHandler = eZClusterFileHandler::instance( $xmlCacheDirPath );
        	$fileHandler->purge();
	}
	
	
	static protected function eztubeImageInfoExpiry()
	{
		eZExpiryHandler::registerShutdownFunction();
		$handler = eZExpiryHandler::instance();
		
		if ( $handler->hasTimestamp( 'eztube-images' ) )
		{
		    $expiredTimestamp = $handler->timestamp( 'eztube-images' );
		}
		else
		{
		    $expiredTimestamp = time();
		    $handler->setTimestamp( 'eztube-images', $expiredTimestamp );
		}
		return $expiredTimestamp;
	}
	
	
	static protected function getImageCacheDir()
	{
		$imageCacheDirPath = eZSys::cacheDirectory() . "/eztube/images";
		return $imageCacheDirPath;
	}	
	
		
	static function retrieveEZTubeImageFromFile( $filePath, $mtime, $imageFileName )
	{		
		return false;
	}
	
	
	static function generateEZTubeImageForFile( $filePath, $image )
	{
		$buf = eZHTTPTool::sendHTTPRequest( $image, 80, false, 'eZ Publish', false );
		$header = false;
		$imagebody = false;

		if ( eZHTTPTool::parseHTTPResponse( $buf, $header, $imagebody ) )
		{
			$pathInfo = pathinfo( $image );
			return array( 'binarydata'  => $imagebody,
				'scope'    => 'eztube-images',
				'datatype' => $pathInfo['extension'],
				'store'    => true );
		}
		return false;
	}	
    	
    		
	
	static function geteZTubeImageCacheByURI( $uri )
	{
		$ini = eZINI::instance( 'eztube.ini' );
		$cacheExpiryArray = $ini->variable( 'CacheSettings', 'CacheTime' );
		$imageFileName = substr( md5( $uri ), 0, 20 );
		$imageSubDir = substr( md5( $uri ), 0, 1 );
		$path = parse_url($uri);	
		$cacheFilePath = self::getImageCacheDir(). "/" . $imageSubDir . "/eztube-image-{$imageFileName}." . basename( $path['path'] );
		$cacheFile = eZClusterFileHandler::instance( $cacheFilePath );
		$cacheFile->processCache(array( 'EZTubeImageCache', 'retrieveEZTubeImageFromFile' ),
					array( 'EZTubeImageCache', 'generateEZTubeImageForFile' ),
					$cacheExpiryArray['images'],
					self::eztubeImageInfoExpiry(),
					$uri );
		return "/".$cacheFilePath;
	}
	

}
?>
