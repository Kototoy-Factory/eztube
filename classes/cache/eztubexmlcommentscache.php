<?php

/**
 * File containing the EZTubeXMLCommentsCache class.
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 */

class EZTubeXMLCommentsCache extends EZTubeXMLCache
{
	static function clearCache()
	{
		eZExpiryHandler::registerShutdownFunction();
		$expiryHandler = eZExpiryHandler::instance();
		$expiryHandler->setTimestamp( 'eztube-xml-comments', time() );
		$expiryHandler->store();
	}


	public static function purgeCache()
	{
		$xmlCacheDirPath = eZSys::cacheDirectory() . "/eztube/xml/comments/";
		$fileHandler = eZClusterFileHandler::instance( $xmlCacheDirPath );
		$fileHandler->purge();
	}

}

?>