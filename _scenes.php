<?php
session_set_cookie_params(0, '/', '.cogdemos.ink');
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/base.php');
$cleantitle = strtolower(str_replace(" ", "-", $_POST['gametitle']));
//$cleantitle = preg_replace('/[^\p{L}\p{N}\s]/u', '', $cleantitle);
//$cleantitle = strtolower(str_replace(" ", "-", $_POST['gametitle']));
$dir = "play/".$_SESSION['nick'].'/'.$cleantitle.'/mygame/scenes/';
if($_SESSION['override']==1){
	// MEMBER HAS INCREASED FILESIZE LIMITS
	$max_size = 1024*10000; // 1G
}else{
	$max_size = 1024*1000; // 1mb
}
$extensions = array('txt');
$count = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_FILES['files']))
{
	// loop all files
	foreach ( $_FILES['files']['name'] as $i => $name )
	{
		// if file not uploaded then skip it
		if ( !is_uploaded_file($_FILES['files']['tmp_name'][$i]) )
			continue;
if($_SESSION['override']!=1){
	    // skip large files
		if ( $_FILES['files']['size'][$i] >= $max_size )
			continue;
}
		// skip unprotected files
		if( !in_array(pathinfo($name, PATHINFO_EXTENSION), $extensions) )
			continue;

		// now we can move uploaded files
	    if( move_uploaded_file($_FILES["files"]["tmp_name"][$i], $dir . $name) ){
	    	$count++;
			/*
			$capture = R::dispense('logs');
			$capture->name = $_SESSION['nick'];
			$capture->game = $cleantitle;
			$capture->upload = $name;
			$capture->approved = 0;
			$cid = R::store($capture);
			*/
		}
	}
}

echo json_encode(array('count' => $count));
