<?php

/**
 * File containing the EZTubeXMLOtherCache class.
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 */

class EZTubeXMLOtherCache extends EZTubeXMLCache
{
	static function clearCache()
	{
		eZExpiryHandler::registerShutdownFunction();
		$expiryHandler = eZExpiryHandler::instance();
		$expiryHandler->setTimestamp( 'eztube-xml-other', time() );
		$expiryHandler->store();
	}


	public static function purgeCache()
	{
		$xmlCacheDirPath = eZSys::cacheDirectory() . "/eztube/xml/other/";
		$fileHandler = eZClusterFileHandler::instance( $xmlCacheDirPath );
		$fileHandler->purge();
	}

}

?>