<?php

/**
 * File containing the eZTube module.
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 */

$Module = array( 'name' => 'eztube' );

$ViewList = array();

$ViewList['home'] = array(
	'functions' => array( 'home' ),
	'script' => 'eztube.php' );

$ViewList['playlist'] = array(
	'functions' => array( 'playlist' ),
	'script' => 'eztube.php',
	'params' => array ( 'PlayListID'), 
	'unordered_params' => array( "offset" => "Offset", "limit" => "Limit", "sortby" => "SortBy" ) );

$ViewList['playlists'] = array(
	'functions' => array( 'playlists' ),
	'script' => 'eztube.php',
	'params' => array ( 'YouTubeUserID' ),
	'unordered_params' => array( "offset" => "Offset", "limit" => "Limit", "sortby" => "SortBy" ) );
	
$ViewList['favorites'] = array(
	'functions' => array( 'favorites' ),
	'script' => 'eztube.php',
	'params' => array ( 'YouTubeUserID' ),
	'unordered_params' => array( "offset" => "Offset", "limit" => "Limit", "sortby" => "SortBy" )  );

$ViewList['uploads'] = array(
	'functions' => array( 'uploads' ),
	'script' => 'eztube.php',
	'params' => array ( 'YouTubeUserID' ),
	'unordered_params' => array( "offset" => "Offset", "limit" => "Limit", "sortby" => "SortBy" )  );

$ViewList['profile'] = array(
	'functions' => array( 'profile' ),
	'script' => 'eztube.php',
	'params' => array ( 'YouTubeUserID'),
	'unordered_params' => array( "offset" => "Offset", "limit" => "Limit", "sortby" => "SortBy" )  );

$ViewList['contacts'] = array(
	'functions' => array( 'contacts' ),
	'script' => 'eztube.php',
	'params' => array ( 'YouTubeUserID'),
	'unordered_params' => array( "offset" => "Offset", "limit" => "Limit", "sortby" => "SortBy" )  );

$ViewList['video'] = array(
	'functions' => array( 'video' ),
	'script' => 'eztube.php',
	'params' => array ( 'YouTubeID' ) );

$ViewList['comments'] = array(
	'functions' => array( 'comments' ),
	'script' => 'eztube.php',
	'params' => array ( 'YouTubeID' ) , 
	'unordered_params' => array( "offset" => "Offset", "limit" => "Limit", "sortby" => "SortBy" ) );
	
$ViewList['responses'] = array(
	'functions' => array( 'responses' ),
	'script' => 'eztube.php',
	'params' => array ( 'YouTubeID' ), 
	'unordered_params' => array( "offset" => "Offset", "limit" => "Limit", "sortby" => "SortBy" ) );
	
$ViewList['related'] = array(
	'functions' => array( 'related' ),
	'script' => 'eztube.php',
	'params' => array ( 'YouTubeID' ), 
	'unordered_params' => array( "offset" => "Offset", "limit" => "Limit", "sortby" => "SortBy" )  );

$ViewList['tag'] = array(
	'functions' => array( 'tag' ),
	'script' => 'eztube.php',
	'params' => array ( 'Tag' ), 
	'unordered_params' => array( "offset" => "Offset", "limit" => "Limit", "sortby" => "SortBy" )  );
	
$ViewList['search'] = array(
	'functions' => array( 'search' ),
	'script' => 'eztube.php',
	'unordered_params' => array( "offset" => "Offset", "sortby" => "SortBy" )  );

$FunctionList = array();

$FunctionList['home'] = array( );

$FunctionList['playlist'] = array( );

$FunctionList['playlists'] = array( );

$FunctionList['favorites'] = array( );

$FunctionList['uploads'] = array( );

$FunctionList['profile'] = array( );

$FunctionList['contacts'] = array( );

$FunctionList['video'] = array( );

$FunctionList['comments'] = array( );

$FunctionList['responses'] = array( );

$FunctionList['related'] = array( );

$FunctionList['tag'] = array( );

$FunctionList['search'] = array( );

?>
