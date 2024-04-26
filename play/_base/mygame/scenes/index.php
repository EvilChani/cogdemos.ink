<?php
session_start();
//require_once($_SERVER['DOCUMENT_ROOT']."/../../../hid/gamehead.php");
require_once($_SERVER['DOCUMENT_ROOT']."../../cghost/hid/gamehead.php");
$accessallowed = "NO";
if( !empty( $_SESSION['memberlog'] ) )
	{
		if( $thisgame['user_id'] == $_SESSION['memberlog'] )
			{
				$accessallowed = "YES";
			}
	}
if( !empty( $thisgame['restricted'] ) )
	{
		$allowedarray = explode( ",", $thisgame['restricted'] );
		if( !in_array( $_SESSION['memberlog'], $allowedarray ) )
			{
				header( "Location: /restricted" );
				exit();
			}
		else
			{
				$accessallowed = "YES";
			}
	}
if( $accessallowed == "NO" )
	{
		if( !empty( $thisgame['restricted'] ) )
			{
				header( "Location: /restricted" );
				exit();
			}
		if( !empty( $thisgame['private'] ) )
			{
				if( $thisgame['private'] == 1 )
					{
						header( "Location: /unlisted" );
						exit();
					}
			}
	}
?>
<?php $part = explode('/',getcwd());$nick = $part[4];$title = ucwords(str_replace('-',' ',$part[5]));?><!DOCTYPE html><html><head><title>DashingDon: Free ChoiceScript Game Hosting</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0"><style type="text/css">body,html{margin:0;border:0}html{padding:0}body{font:15px 'trebuchet MS','lucida sans';padding:5% 0 0 5%}.x{width:95%}.x ul{list-style:none;padding:0;margin-bottom:4em}.x a{position:relative;display:block;padding:.4em .4em .4em 2em;margin:.5em 0;background:#FFF;color:#444;text-decoration:none;-moz-border-radius:.3em;-webkit-border-radius:.3em;border-radius:.3em}.x a:hover{background:#cbe7f8}</style></head><body><h4>Scene Files for &ldquo;<?=$title;?>&rdquo; by <?=$nick;?></h4><div class="x"><ul><?php $files = array();$dir = opendir('.');while(false != ($file = readdir($dir))) { if(($file != ".") and ($file != "..") and ($file != "index.php")) { $files[] = $file; } }natsort($files);foreach($files as $file) { echo("<li><a href='$file' target='_blank'>$file</a></li>"); }?></ul></div></body></html>