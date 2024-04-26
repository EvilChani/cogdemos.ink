<?php
session_set_cookie_params(0, '/', '.cogdemos.ink');
session_start();
if($_SESSION['memberlog']==''||$_SESSION['nick']==''){
	header('Location: index.php');
	exit();
}
if(!$_POST){exit('nopost');}

function uniqidReal($length = 5) {
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($length / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $length);
}

require_once($_SERVER['DOCUMENT_ROOT'].'/ink/base.php');
$thisid = $_SESSION['memberlog'];
$userinfo = R::load('users', $thisid);
if($_POST['a']=='createkey'){
	$userinfo->createkey = strtoupper(uniqidReal(8).'-'.uniqidReal(4).'-'.uniqidReal(4).'-'.uniqidReal(4).'-'.uniqidReal(12).'-'.uniqidReal(5));
	R::store($userinfo);
	echo $userinfo->createkey;
}
if($_POST['a']=='userkey'){
	$userinfo->userkey = strtoupper(uniqidReal(8).'-'.uniqidReal(4).'-'.uniqidReal(4).'-'.uniqidReal(4).'-'.uniqidReal(12).'-'.uniqidReal(5));
	R::store($userinfo);
	echo $userinfo->userkey;
}
exit();