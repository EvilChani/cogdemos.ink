<?php
$recaptchakey = "???";
require_once( "rb-mysql.php" );
R::setup('mysql:host=localhost;dbname=moodydb',
                'DBuser',
                'dbpassword'
	);
