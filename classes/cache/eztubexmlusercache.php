<?php

/**
 * File containing the EZTubeXMLUserCache class.
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 */

class EZTubeXMLUserCache extends EZTubeXMLCache
{
	static function clearCache()
	{
		eZExpiryHandler::registerShutdownFunction();
		$expiryHandler = eZExpiryHandler::instance();
		$expiryHandler->setTimestamp( 'eztube-xml-users', time() );
		$expiryHandler->store();
	}


	public static function purgeCache()
	{
		$xmlCacheDirPath = eZSys::cacheDirectory() . "/eztube/xml/users/";
		$fileHandler = eZClusterFileHandler::instance( $xmlCacheDirPath );
		$fileHandler->purge();
	}

}

?>