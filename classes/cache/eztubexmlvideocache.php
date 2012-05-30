<?php

/**
 * File containing the EZTubeXMLVideoCache class.
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 */

class EZTubeXMLVideoCache extends EZTubeXMLCache
{
	static function clearCache()
	{
		eZExpiryHandler::registerShutdownFunction();
		$expiryHandler = eZExpiryHandler::instance();
		$expiryHandler->setTimestamp( 'eztube-xml-videos', time() );
		$expiryHandler->store();
	}


	public static function purgeCache()
	{
		$xmlCacheDirPath = eZSys::cacheDirectory() . "/eztube/xml/videos/";
		$fileHandler = eZClusterFileHandler::instance( $xmlCacheDirPath );
		$fileHandler->purge();
	}

}

?>