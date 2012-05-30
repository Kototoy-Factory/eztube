<?php

/**
 * File containing the EZTubeXMLCache class.
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 */

class EZTubeXMLCache
{
	private static $cacheType = "other";
	private static $cacheExpiry = 86400;

	function __construct() 
	{
	}
	
	
	static function clearCache()
	{
		//implemented per cache type
	}
	
	
	public static function purgeCache()
	{
		//implemented per cache type
	}
	
	
	static protected function eztubeXMLInfoExpiry()
	{
		eZExpiryHandler::registerShutdownFunction();
		$handler = eZExpiryHandler::instance();
		
		if ( $handler->hasTimestamp( 'eztube-xml-' . self::$cacheType ) )
		{
		    $expiredTimestamp = $handler->timestamp( 'eztube-xml-' . self::$cacheType );
		}
		else
		{
		    $expiredTimestamp = time();
		    $handler->setTimestamp( 'eztube-xml-' . self::$cacheType, $expiredTimestamp );
		}

		return $expiredTimestamp;
	}	
	
	static protected function getXMLCacheDir( $uri )
	{
		$ini = eZINI::instance( 'eztube.ini' );
		$typeArray = $ini->variable( 'CacheSettings', 'CacheTime' );

		foreach( $typeArray as $key => $type )
		{
			if ( strrchr( $uri, $key ) ) 
			{
				self::$cacheType = $key; 
				self::$cacheExpiry = $type;
			}
		}

		$xmlCacheDirPath = eZSys::cacheDirectory() . "/eztube/xml/". self::$cacheType;
		
		if ( !is_dir( $xmlCacheDirPath ) )
		{
		    eZDir::mkdir( $xmlCacheDirPath, false, true );
        	}
        	
		return $xmlCacheDirPath;
	}
	
	
	static function retrieveEZTubeXMLFromFile( $filePath, $mtime, $xmlFileName )
	{
		return include( $filePath );
	}
	
	
	static function generateEZTubeXMLForFile( $filePath, $uri )
    	{
    		$buf = eZHTTPTool::sendHTTPRequest( $uri, 80, false, 'eZ Publish', false );
		$header = false;
		$xmlbody = false;

		if ( eZHTTPTool::parseHTTPResponse( $buf, $header, $xmlbody ) )
		{
			if ( isset($header['transfer-encoding']) )
			{
				if ( $header['transfer-encoding'] === "chunked" )
				{
					$fp = 0;
					$outXML = "";
					while ( $fp < strlen( $xmlbody ) ) 
					{
					    $rawnum = substr( $xmlbody, $fp, strpos( substr( $xmlbody, $fp ), "\r\n" ) + 2 );
					    $num = hexdec( trim( $rawnum ) );
					    $fp += strlen( $rawnum );
					    $chunk = substr( $xmlbody, $fp, $num );
					    $outXML .= $chunk;
					    $fp += strlen( $chunk );
					}
					
					$xmlbody = $outXML;					
				}
				
			}
			return array( 'content'  => $xmlbody,
				'scope'    => 'eztube-xml-'.self::$cacheType,
				'datatype' => 'php',
				'store'    => true );
		}
		return false;		
    	}
    	
    		
	static function geteZTubeCacheByURI( $uri )
	{
		$xmlFileName = substr( md5( $uri ), 0, 20 );
		$xmlSubDir = substr( md5( $uri ), 0, 1 );
		
		$cacheFilePath = self::getXMLCacheDir( $uri ) . "/" . $xmlSubDir . "/eztube-xml-{$xmlFileName}.cache.php" ;
		$cacheFile = eZClusterFileHandler::instance( $cacheFilePath );
		return $cacheFile->processCache( array( 'eZTubeXMLCache', 'retrieveEZTubeXMLFromFile' ),
						 array( 'eZTubeXMLCache', 'generateEZTubeXMLForFile' ),
						 self::$cacheExpiry,
						 self::eztubeXMLInfoExpiry(),
						 $uri );
	}
	
}
?>
