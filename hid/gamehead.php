<?php
if ( isset( $_SERVER["REMOTE_ADDR"] ) ){ $_SESSION["ipAddress"] = $_SERVER["REMOTE_ADDR"]; }
elseif( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ){ $_SESSION["ipAddress"] = $_SERVER["HTTP_X_FORWARDED_FOR"]; }
elseif( isset( $_SERVER["HTTP_CLIENT_IP"] ) ){ $_SESSION["ipAddress"] = $_SERVER["HTTP_CLIENT_IP"]; }
else{ $_SESSION["ipAddress"] = "127.0.0.1"; }
$_SESSION["ipAddress"] = trim( $_SESSION["ipAddress"] , " " );
ini_set( "error_reporting" , E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED );
require_once("base.php");
if( empty( $_SESSION['token'] ) )
	{ $_SESSION['token'] = bin2hex( random_bytes( 32 ) ); }
function currentPageURL()
	{
		$pageURL = "https";
//		if( $_SERVER[ "HTTPS" ] == "on" )
		if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" )
			{ $pageURL .= "s"; }
		$pageURL .= "://";
		if( $_SERVER[ "SERVER_PORT" ] != "80" )
			{
				$pageURL .= $_SERVER[ "SERVER_NAME" ] . ":" . $_SERVER[ "SERVER_PORT" ] . $_SERVER[ "REQUEST_URI" ];
			}
		else
			{ $pageURL .= $_SERVER[ "SERVER_NAME" ] . $_SERVER[ "REQUEST_URI" ]; }
		return $pageURL;
	}
$fullurl = parse_url( currentPageURL() );
$urlparts = explode( "/" , $fullurl[ "path" ] );

$nickname = $urlparts[2];
$game = $urlparts[3];
$titlefix = strtolower( str_replace( "-" , " " , $game ) );
$finder = R::getRow(
		"SELECT * FROM user WHERE nick = :nickname",
		[ ":nickname" => $nickname ]
	);

if( $finder[ "banned" ] == 1 )
	{ header( "Location: /banned" );exit(); }

$user_id = $finder[ "id" ];

$thisgame = R::getRow( 
		"SELECT * FROM game WHERE user_id = :user_id AND url = :game ORDER BY id DESC LIMIT 1", 
		[ ":user_id" => $user_id , ":game" => $game ] 
	);

if( empty( $thisgame ) )
	{ header( "Location: /notfound" );exit(); }

if( $thisgame['suspended'] == 1 )
	{ header( "Location: /suspended" );exit(); }

if( !empty( $thisgame['restricted'] ) )
	{
		if( empty( $_SESSION['memberlog'] ) )
			{ header( "Location: /restricted" );exit(); }
		else
			{
				if( $_SESSION['memberlog'] != $thisgame['user_id'] )
					{
						$allowedarray = explode( ",", $thisgame['restricted'] );
						if( !in_array( $_SESSION['memberlog'], $allowedarray ) )
							{ header( "Location: /restricted" );exit(); }
					}
			}
	}

if( !empty( $_SESSION['memberlog'] ) )
	{
		$favorite = false;
		$favorites = R::getCell( "SELECT favorites FROM user WHERE id = ? LIMIT 1", [ $_SESSION['memberlog'] ] );
		if( $favorites )
			{
				$favarray = explode( ",", $favorites );
				if( in_array( $thisgame['id'], $favarray ) )
					{ $favorite = true; }
			}
	}

if( empty($_SESSION['ipkeep'] ) )
	{
		$view = R::load( "game", $thisgame['id'] );
		$view->views = $view->views + 1;
		$basura = R::store( $view );
		$_SESSION['ipkeep'] = $_SESSION["ipAddress"];
	}

if( !empty( $thisgame[ "feedbackurl" ] ) )
	{
		if( strpos( $thisgame[ "feedbackurl" ] , "@" ) !== FALSE )
			{
				$feedback = '<a href="mailto:' . $thisgame[ "feedbackurl" ] . '" class="nav-links" target="_blank"><i class="fa fa-envelope"></i> Email Author</a>';
			}
		else
			{
				$feedback = '<a href="' . $thisgame[ "feedbackurl" ] . '" class="nav-links" target="_blank"><i class="fa fa-comments"></i> Discuss</a>';
			}
	}
else
	{
	$feedback = '';
	}
