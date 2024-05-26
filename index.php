<?php
// before any output:
// ----------------------------------------------------------------------------------------------------
// - Display Errors
// ----------------------------------------------------------------------------------------------------
ini_set('display_errors', 'On');
ini_set('html_errors', 0);

// ----------------------------------------------------------------------------------------------------
// - Error Reporting
// ----------------------------------------------------------------------------------------------------
error_reporting(-1);

// ----------------------------------------------------------------------------------------------------
// - Shutdown Handler
// ----------------------------------------------------------------------------------------------------
function ShutdownHandler()
{
    if(@is_array($error = @error_get_last()))
    {
        return(@call_user_func_array('ErrorHandler', $error));
    };

    return(TRUE);
};

register_shutdown_function('ShutdownHandler');

// ----------------------------------------------------------------------------------------------------
// - Error Handler
// ----------------------------------------------------------------------------------------------------
function ErrorHandler($type, $message, $file, $line)
{
    $_ERRORS = Array(
        0x0001 => 'E_ERROR',
        0x0002 => 'E_WARNING',
        0x0004 => 'E_PARSE',
        0x0008 => 'E_NOTICE',
        0x0010 => 'E_CORE_ERROR',
        0x0020 => 'E_CORE_WARNING',
        0x0040 => 'E_COMPILE_ERROR',
        0x0080 => 'E_COMPILE_WARNING',
        0x0100 => 'E_USER_ERROR',
        0x0200 => 'E_USER_WARNING',
        0x0400 => 'E_USER_NOTICE',
        0x0800 => 'E_STRICT',
        0x1000 => 'E_RECOVERABLE_ERROR',
        0x2000 => 'E_DEPRECATED',
        0x4000 => 'E_USER_DEPRECATED'
    );

    if(!@is_string($name = @array_search($type, @array_flip($_ERRORS))))
    {
        $name = 'E_UNKNOWN';
    };

    return(print(@sprintf("%s Error in file \xBB%s\xAB at line %d: %s\n", $name, @basename($file), $line, $message)));
};

$old_error_handler = set_error_handler("ErrorHandler");

// other php code

//ini_set('display_startup_errors',1); 
//ini_set('display_errors',1);
//error_reporting(E_ALL);

//ini_set('zlib.output_compression', 0);
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

$allowed_hosts = array('172.31.9.201', '34.234.225.210');
if(substr_count( $_SERVER["HTTP_ACCEPT_ENCODING"] , "gzip" ) )
	{ ob_start( "ob_gzhandler" ); }
else
	{ ob_start(); }
session_start();
if(empty( $_SESSION['token'] ) )
	{ $_SESSION['token'] = bin2hex( random_bytes( 32 ) ); }
error_reporting(0);
//error_reporting( E_ALL & ~E_NOTICE );
//header( "Access-Control-Allow-Origin: *" );

require_once($_SERVER['DOCUMENT_ROOT']."inc/flight/Flight.php");
require_once($_SERVER['DOCUMENT_ROOT']."../../cghost/hid/base.php");

# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ INDEX
Flight::route( "/" , function(){

//scuttle( $_POST, false );
//scuttle( $_SESSION );

	Flight::render( "frontpage" );
	exit();

die( "err.index" ); });
# ]


# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ _FORGOT
function configureMail() {

    $config = include '../../cghost/hid/config/mail.php';

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $config['smtp_host'];
    $mail->Port = $config['smtp_port'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtp_username'];
    $mail->Password = $config['smtp_password'];
    $mail->SMTPSecure = 'tls';

    return $mail;
}

Flight::route("/_forgot", function() {
    $safePost = filter_input_array(INPUT_POST);
    check_token($safePost);

    $thismember = R::getRow(
        'SELECT * FROM user WHERE email = :username',
        [':username' => $safePost['email']]
    );

    if (empty($thismember)) {
        em( "Invalid email address!" );
        forward();
    } else {

        if (strtolower($thismember['nick']) != strtolower($safePost['nick'])) {
            em( "Invalid nickname!" );
            forward();
        }

        $reload = R::load("user", $thismember['id']);

        $plainpass = uniqid();
        $newpass = password_hash($plainpass, PASSWORD_DEFAULT);
        $reload->pass = $newpass;
        $update = R::store($reload);
        $to = $reload->email;
        $subject = 'New Password for cogdemos.ink';
        $message = 'Your password for cogdemos.ink has been reset to: ' . $plainpass;

        $mail = configureMail();

        $mail->setFrom('no-reply@cogdemos.ink', 'CoG Demos Ink');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;

        if ($mail->send()) {
            sm( "A new password has been sent to your email address." );
            forward();
        } else {
            em( "Error sending email:" . $mail->ErrorInfo);
            forward();
        }
    }

    die("err.forgot");
});


# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ WIP
Flight::route( "/wip(/@page)" , function( $page ){

	if( empty( $page ) ){ $page = 1; }

	$numberOfGames = numberOfGames();

	try
		{
			### How many items to list per page
				$pageLimit = 10;

			### How many pages will there be
				$pageNumbers = ceil( $numberOfGames / $pageLimit );

			### What page are we currently on?
				$pageNumber = min( $pageNumbers , $page );

			### Calculate the offset for the query
				$pageOffset = ( $pageNumber - 1 )  * $pageLimit;

			### Some information to display to the user
				$pageStart = $pageOffset + 1;
				$pageEnd = min( ( $pageOffset + $pageLimit ) , $numberOfGames );

			if( $pageNumber > 1 )
				{
					$linkPrevious = '<li class="page-item"><a href="/wip/1" class="page-link">First</a></li><li class="page-item"><a class="page-link" href="/wip/' . ( $pageNumber - 1 ) . '">Previous</a></li>';
				}
			else
				{
					$linkPrevious = '<li class="page-item disabled"><a class="page-link" href="javascript:void(0);">First</a></li><li class="page-item disabled"><a class="page-link" href="javascript:void(0);">Previous</a></li>';
				}

			if( $pageNumber < $pageNumbers )
				{
					$linkNext = '<li class="page-item"><a class="page-link" href="/wip/' . ( $pageNumber + 1 ) . '">Next</a></li><li class="page-item"><a class="page-link" href="/wip/' . $pageNumbers .  '">Last</a></li>';
				}
			else
				{
					$linkNext = '<li class="page-item disabled"><a class="page-link" href="javascript:void(0);">Next</a></li><li class="page-item disabled"><a class="page-link" href="javascript:void(0);">Last</a></li>';
				}

			$pagination = 
				'<div class="displaying text-center">' . 
					'Displaying ' . $pageStart . '-' . $pageEnd . ' of ' . $numberOfGames . ' results' . 
				'</div>' . 
				'<nav>' . 
					'<ul class="pagination pg-purple justify-content-center">' .
						$linkPrevious . $linkNext . 
					'</ul>' . 
				'</nav>';

			$showingPage = '<div id="showing">Page ' . $pageNumber . ' of ' . $pageNumbers . '</div>';

			$publicGames = R::getAll("
					SELECT * FROM game 
					WHERE private = 0 
					AND blurb != '' 
					AND restricted = '' 
					AND gametype = 0
					ORDER BY id DESC 
					LIMIT :limit OFFSET :offset ",
						[
							":limit"		=> $pageLimit,
							":offset"		=> $pageOffset
						]
				);

			if ( !is_array( $publicGames ) )
				{ $pagination = "<p>No results could be displayed.</p>"; }

		}
	catch ( Exception $e )
		{ echo '<p>' , $e->getMessage() , '</p>'; }

	if( !empty( $_GET["hidden"] ) && $_SESSION["nick"] == "dashingdon" )
		{
			$publicGames = R::getAll("
					SELECT * FROM game 
					WHERE private = 1 
					ORDER BY lastupdate DESC, id DESC
				");
			$pagination = '';
		}

	Flight::render( "list" ,
			[
				"n_page"				=> $pageNumber,
				"showing"				=> $showingPage,
				"pagination"			=> $pagination,
				"link_prev"				=> $linkPrevious,
				"link_next"				=> $linkNext,
				"games_public"			=> $publicGames,
				"n_games"				=> $numberOfGames
			]
		);

	exit();

die( "err.wip" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ PRIVATE
Flight::route( "/private" , function(){

	Flight::render( "private" );
	exit();

die( "err.private" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ BANNED
Flight::route( "/banned" , function(){

	Flight::render( "banned" );
	exit();

die( "err.banned" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ LOAD SLOTS
Flight::route( "/slots/@game_id" , function( $game_id ){

	$user_id = $_SESSION["memberlog"];

	$slots = loadSaves( $user_id , $game_id );

	scuttle( $slots );

	exit();

die( "err.load_slots" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ LOAD
Flight::route( "/load/@game_id/@slot_id" , function( $game_id, $slot_id ){

	if( $slot_id > 6 )
		{ http_response_code(404);exit(); }

	$user_id = $_SESSION["memberlog"];

	//sm( "user ". $user_id. " game ". $game_id . " slot " . $slot_id );
	//scuttle( [ "user" => $user_id, "game" => $game_id, "slot" => $slot_id ] );

	$save = R::getRow(
			"SELECT * FROM save WHERE user_id = :uid AND game_id = :gid AND slot_id = :sid", 
			[ ":uid" => $user_id, ":gid" => $game_id, ":sid" => $slot_id ]
		);

	if( $save['id'] == 0 || $save['user_id'] != $_SESSION["memberlog"] )
		{ http_response_code(404);exit(); }

	echo $save['state'];

	exit();

die( "err.load" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ SAVE POST
Flight::route("POST /save/@game_id/@slot_id/@slot_name", function($game_id, $slot_id, $slot_name) {

    $user_id = $_SESSION["memberlog"];

    $safePost = filter_input_array(INPUT_POST);

    $status = $safePost["status"];

    $ob = json_decode($status);
    if ($ob === null) {
        scuttle("Bad JSON");
    }

    $prev = R::getRow(
        "SELECT * FROM save WHERE user_id = :uid AND game_id = :gid AND slot_id = :sid",
        [":uid" => $user_id, ":gid" => $game_id, ":sid" => $slot_id]
    );

    if (empty($prev)) {
        sm("prev empty -- new save");
        $newsave = R::dispense("save");
        $newsave->user_id = $user_id;
        $newsave->game_id = $game_id;
        $newsave->slot_id = $slot_id;
        $newsave->slot_name = $slot_name; // Added slot_name assignment
        $newsave->state = $_POST["status"];
        return R::store($newsave);
    } else {
        sm("prev found -- old save");
        if ($prev['id'] == 0 || $prev['user_id'] != $_SESSION["memberlog"]) {
            http_response_code(404);
            exit();
        }

        $save = R::load("save", $prev['id']);

        if (empty($save)) {
            return false;
        } else {
            $save->state = $safePost["status"];
            return R::store($save);
        }
    }

    exit();

    die("err.save");
});
# ]


# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ DELETE POST
Flight::route('DELETE /deleteSave/@slotID', function($slotID) {
    // Check if the slot ID is provided
    if (!empty($slotID)) {
        // Find the save with the given slot ID
        $save = R::findOne('save', 'slot_id = ?', [$slotID]);

        // If a save with the given slot ID exists, delete it
        if ($save) {
            R::trash($save);
            // Respond with success message or appropriate status code
            Flight::json(["message" => "Save deleted successfully"]);
        } else {
            // Respond with error message or appropriate status code if no save found
            Flight::halt(404, "Save not found");
        }
    } else {
        // Respond with error message or appropriate status code if slot ID is missing
        Flight::halt(400, "Slot ID parameter is missing");
    }
exit();

die("err.save");
});
# ]


# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ DASHBOARD
Flight::route( "/dashboard" , function(){

	check_membership();

	$games = R::getAll(
			"SELECT * FROM game WHERE usersid = ? " ,
			[ $_SESSION["memberlog"] ]
		);

	Flight::render( "dashboard" ,
			[
				"game" => $games
			]
		);
	exit();

die( "err.dashboard" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ GO
Flight::route( "/go/@id", function( $id ){

	$game = R::load( "game", $id );

	if( $game['id'] == 0 || empty( $game ) ){ forward( "notFound" ); }

	if( $game['private'] == 1 ){ forward( "private" ); }

	if( $game['banned'] == 1 ){ forward( "banned" ); }

	$author = R::getCell( "SELECT nick FROM user WHERE id = ? ORDER BY id DESC LIMIT 1", [ $game['user_id'] ] );

	playgame( $author, $game['url'] );

die( "err.go" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ APICREDS
Flight::route( "/apicreds" , function(){

	check_membership();

	Flight::render( "apicreds" ,
			[
				"none" => "nothing"
			]
		);
	exit();

die( "err.apicreds" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ CHANGE PASSWORD
Flight::route( "/changepass" , function(){

	check_membership();

	Flight::render( "changepass" ,
			[
				"none" => "nothing"
			]
		);
	exit();

die( "err.changepass" ); });
# ]


# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ CHANGE PASSWORD
Flight::route( "POST /_passchange" , function(){

	check_membership();

	$safePost = filter_input_array( INPUT_POST );

	check_token( $safePost );

	if( $_POST['newpass'] != $_POST['newpass2'] )
		{
			exit( 'The new password and new password confirm do not match.' );
		}

	$thisid = $_SESSION['memberlog'];
	$userinfo = R::load( 'user', $thisid );

	if( !password_verify( $safePost['oldpass'] , $userinfo->pass ) )
		{
			exit( "The current password {$safePost['oldpass']} is incorrect." );
		}

	$password = password_hash( $safePost['newpass'], PASSWORD_DEFAULT );

	$userinfo->pass = $password;
	R::store( $userinfo );
	exit( 'Your password has been changed successfully.' );

die( "err._passchange" ); });
# ]





Flight::route( "GET /makeapassword" , function(){
	echo password_hash( $_GET['pass'], PASSWORD_DEFAULT );
});





# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ REGISTER
Flight::route( "POST /_register" , function(){

	$safePost = filter_input_array( INPUT_POST );

	check_token( $safePost );

	$cleanauthor = strtolower( str_replace( " ", "-", $safePost['nick'] ) );

	$checknick = R::findOne( "user", " nick = ? ", [ $cleanauthor ] );
	if( $checknick )
		{
			em( 'That nickname has already been registered.' );
			forward();
		}

	$checkmail = R::findOne( "user", " email = ? ", [ $safePost['email'] ] );
	if( $checkmail )
		{
			em( 'That email address has already been registered.' );
			forward();
		}

	$newuser = R::dispense( 'user' );
	$newuser->nick = $cleanauthor;
	$newuser->email = $safePost['email'];
	$newuser->pass = password_hash( $safePost['pass'], PASSWORD_DEFAULT );
	$newuser->createkey = strtoupper(uniqidReal(8).'-'.uniqidReal(4).'-'.uniqidReal(8));
	R::store( $newuser );

	sm( 'Your account has been created successfully.' );
	forward();

die( "err._register" ); });
# ]


# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ CHANGE CREATE KEY
Flight::route( "POST /_keycreate" , function(){

	check_membership();

	$safePost = filter_input_array( INPUT_POST );

	$thisid = $_SESSION['memberlog'];
	$userinfo = R::load( 'user', $thisid );

	$userinfo->createkey = strtoupper(uniqidReal(8).'-'.uniqidReal(4).'-'.uniqidReal(8));
	R::store($userinfo);
	echo $userinfo->createkey;

	exit();

die( "err._keycreate" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ CHANGE GAME KEY
Flight::route( "POST /_keygame/@game_id" , function( $game_id ){

	check_membership();

	$game = R::load( "game" , $game_id );
	if( $game["user_id"] != $_SESSION["memberlog"] )
		{ forward( "logout" ); }

	$safePost = filter_input_array( INPUT_POST );

	$game->apikey = strtoupper(uniqidReal(8).'-'.uniqidReal(4).'-'.uniqidReal(8));
	R::store($game);
	echo $game->apikey;

	exit();

die( "err._keygame" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ ADD ACCESS
Flight::route( "/_addaccess/@game_id" , function( $game_id ){

	check_membership();

	$game = R::load( "game" , $game_id );
	if( $game["user_id"] != $_SESSION["memberlog"] )
		{ forward( "logout" ); }

	$safePost = filter_input_array( INPUT_POST );

	$safePost['allowaccess'] = str_replace( " ", "", $safePost['allowaccess'] );

	$currentarray = explode( ",", $game['restricted'] );
	$allowarray = explode( ",", $safePost['allowaccess'] );

	$newcsv = "";
	foreach( $allowarray as $user )
		{
			$addition = R::findOne( 'user', ' nick = ? ', [ $user ]);
			if( !empty( $addition ) && !in_array( $addition['id'], $currentarray ) )
				{
					$newcsv .= $addition['id'].",";
				}
			elseif( in_array( $addition['id'], $currentarray ) )
				{
					em( "Member <strong>{$addition['nick']}</strong> already has access to this game." );
				}
			else
				{
					em( "The name <strong>{$user}</strong> was not found as a registered member." );
				}
		}
	$newcsv = $game['restricted'].",".$newcsv;
	$newcsv = ltrim( $newcsv, "," );
	$newcsv = rtrim( $newcsv, "," );
	$game->restricted = $newcsv;
	$basura = R::store( $game );

	exit();

die( "err.removeaccess" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ REMOVE ACCESS
Flight::route( "/removeaccess/@game_id/@user_id" , function( $game_id, $user_id ){

	check_membership();

	$game = R::load( "game" , $game_id );
	if( $game["user_id"] != $_SESSION["memberlog"] )
		{ forward( "logout" ); }
	$allowarray = explode( ",", $game['restricted'] );
	if( ( $key = array_search( $user_id, $allowarray ) ) !== false )
		{ unset( $allowarray[$key] ); }
	$newcsv = "";
	foreach( $allowarray as $num )
		{ $newcsv .= $num.","; }
	$newcsv = rtrim( $newcsv, "," );

	$game->restricted = $newcsv;
	$basura = R::store( $game );

	forward( "edit/".$game['id'] );

die( "err.removeaccess" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ _HASH
/**
* Display a password hash
*/
Flight::route( "/_hash/@pass", function( $pass ){

	exit( password_hash( $pass, PASSWORD_DEFAULT ) );
	//use: password_verify( string $password, string $hash )

die( "err.hash" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ LOGOUT
/**
* Process a logout request
*/
Flight::route( "/logout", function(){

	session_destroy();
	unset( $_SESSION );
	$_SESSION = [];
	forward( "wip/2" );

die( "err.logout" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ DEVSAVE
Flight::route( "/devsave/@id" , function( $id ){

	check_membership();

	$safePost = filter_input_array( INPUT_POST );

	$game = R::load( "game" , $id );

	if( $game["user_id"] != $_SESSION["memberlog"] )
		{ forward( "logout" ); }

	$savefile = R::getRow( 
			"SELECT * FROM save WHERE user_id = :uid AND game_id = :gid AND slot_id = 0",
			[ ":uid" => $_SESSION['memberlog'], ":gid" => $id ]
		);

	if( empty( $savefile ) )
		{ em( "Developer save not found" );forward( "dashboard" ); }

	$newstats = json_encode( $safePost );
	$newstats = str_replace( ':"false",', ":false,", $newstats );
	$newstats = str_replace( ':"true",', ":true,", $newstats );

	$save = R::load( "save", $savefile['id'] );

	$decoded = json_decode( $save->state, true );

	$sceneName = $decoded['stats']['sceneName'];
	if( !empty( $decoded['stats']['implicit_control_flow'] ) )
		{
			$implicit_control_flow = $decoded['stats']['implicit_control_flow'];
		}
	else
		{
			$implicit_control_flow = "";
		}
	if( !empty( $decoded['stats']['choice_subscene_stack'] ) )
		{
			$choice_subscene_stack = $decoded['stats']['choice_subscene_stack'];
		}
	else
		{
			$choice_subscene_stack = "";
		}

	unset( $decoded['stats'] );

	$decoded['stats']['sceneName'] = $sceneName;
	$decoded['stats']['implicit_control_flow'] = $implicit_control_flow;
	$decoded['stats']['choice_subscene_stack'] = $choice_subscene_stack;

	foreach( $safePost as $key => $value )
		{
			$decoded['stats'][$key] = $value;
		}

	$save->state = json_encode( $decoded );
	$basura = R::store( $save );

	sm( "Developer save slot updated." );
	forward( "dashboard" );

die( "err.devsave" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ DEVEDIT
Flight::route( "/devedit/@id" , function( $id ){

	check_membership();

	$game = R::load( "game", $id );

	if( $game["user_id"] != $_SESSION["memberlog"] )
		{ forward( "logout" ); }

	$savefile = R::getRow( 
			"SELECT * FROM save WHERE user_id = :uid AND game_id = :gid AND slot_id = 0",
			[ ":uid" => $_SESSION['memberlog'], ":gid" => $id ]
		);

	if( empty( $savefile ) )
		{
			nm( "Developer Slot save not found." );
			$savefile = R::getRow( 
					"SELECT * FROM save WHERE user_id = :uid AND game_id = :gid AND slot_id = 1",
					[ ":uid" => $_SESSION['memberlog'], ":gid" => $id ]
				);
			if( !empty( $savefile ) )
				{
					$devsave = R::dispense( "save" );
					$devsave->slot_id = 0;
					$devsave->user_id = $_SESSION['memberlog'];
					$devsave->game_id = $game['id'];
					$devsave->state = $savefile['state'];
					$save_id = R::store( $devsave );
					$savefile = R::load( "save", $save_id );
					nm( "Save copied from Slot 1 to Developer Slot." );
				}
			else
				{
					em( "No save in Slot 1 found to copy." );forward( "dashboard" );
				}
		}
	else
		{
			$savefile = R::load( "save", $savefile['id'] );
		}

	$savefile->state = str_replace( ":false,", ':"false",', $savefile->state );
	$savefile->state = str_replace( ":true,", ':"true",', $savefile->state );

	$state = json_decode( $savefile['state'], true );

	$sceneName = $state['stats']['sceneName'];
	unset($state['stats']['sceneName']);
	unset($state['stats']['implicit_control_flow']);
	unset($state['stats']['choice_subscene_stack']);

	ksort($state['stats']);

	$saves[] =
		[
			"id" => $game['id'],
			"title" => $game['title'],
			"sceneName" => $sceneName,
			"stats" => $state['stats']
		];

	Flight::render( "saveeditor" ,
			[
				"saves" => $saves,
				"game" => $game
			]
		);
	exit();

die( "err.devedit" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ SEARCH
Flight::route( "/search", function(){

	$safePost = filter_input_array( INPUT_POST );

	$games = R::find( 'game', ' private = 0 AND title LIKE :search OR blurb LIKE :search ORDER BY lastupdate DESC LIMIT 30', [ ":search" => "%".$safePost['search']."%" ] );

	Flight::render( "results" ,
			[
				"searchterm" => $safePost['search'],
				"games" => $games,
				"n_games" => count( $games )
			]
		);

	exit();

die( "err.search" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ FAVORITE
Flight::route( "/favorite", function(){

	check_membership();
	
	$safePost = filter_input_array( INPUT_POST );

	if( !empty( $safePost[ "m" ] ) && !empty( $safePost[ "g" ] && !empty( $_SESSION['memberlog'] ) ) )
		{

		# CHECK REQUIRED PARAMETERS
		# ------------------------------------------
			if( $safePost[ "m" ] != $_SESSION[ "memberlog" ] )
				{ echo json_encode( [ "error" => "member not found" ] );exit(); }

		# LOAD THE GAME AND USER DATA
		# ------------------------------------------
			$user = R::load( "user", $safePost[ "m" ] );
			$game = R::load( "game", $safePost[ "g" ] );

			if( empty( $user ) || empty( $game ) )
				{ echo json_encode( [ "error" => "member/game not found" ] );exit(); }

			if( !empty ( $user['favorites'] ) )
				{
					$favarray = explode( ",", $user['favorites'] );
				}
			else
				{
					$favarray = [];
				}
			if( !in_array( $game['id'], $favarray ) )
				{
					# add to favorites
					if( !empty( $user['favorites'] ) )
						{ $user->favorites = $user['favorites'].",".$game['id']; }
					else
						{ $user->favorites = $game['id']; }
					echo json_encode( [ "favorite" => true ] );
				}
			else
				{
					# remove from favorites
					if( ( $key = array_search( $game['id'], $favarray ) ) !== false )
						{ unset( $favarray[$key] ); }
					$newcsv = "";
					foreach( $favarray as $num )
						{ $newcsv .= $num.","; }
					$newcsv = ltrim( $newcsv, "," );
					$newcsv = rtrim( $newcsv, "," );
					$user->favorites = $newcsv;
					echo json_encode( [ "unfavorite" => true ] );
				}
			$_SESSION['favorites'] = $user->favorites;
			$basura = R::store( $user );
		}
	else
		{
			echo json_encode( [ "error" => "no post" ] );
		}

	exit();

die( "err.favorite" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ REPORT
Flight::route( "/report/@game_id" , function( $game_id ){

	check_membership();

	$game = R::load( "game" , $game_id );
	if( empty( $game ) )
		{ forward( "notfound" ); }

	Flight::render( "report" ,
			[
				"game" => $game
			]
		);
	exit();

die( "err.report" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ REPORT
Flight::route( "/_report/@game_id" , function( $game_id ){

	check_membership();

	$safePost = filter_input_array( INPUT_POST );

	check_token( $safePost );

	$game = R::load( "game" , $game_id );
	if( empty( $game ) )
		{ forward( "notfound" ); }

	$report = R::dispense( "report" );
	$report->game_id = $game['id'];
	$report->user_id = $_SESSION['memberlog'];
	$report->reason = $safePost['reason'];
	$report->info = $safePost['info'];
	$report->stamp = time();
	$basura = R::store( $report );

	$_SESSION['reported'][] = $game['id'];

	Flight::render( "report" ,
			[
				"game" => $game
			]
		);
	exit();

die( "err._report" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ PRIVATE
Flight::route( "/private", function(){

	Flight::render( "private" );
	exit();

die( "err.private" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ RESTRICTED
Flight::route( "/restricted", function(){

	Flight::render( "restricted" );
	exit();

die( "err.restricted" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ SUSPENDED
Flight::route( "/suspended", function(){

	Flight::render( "suspended" );
	exit();

die( "err.suspended" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ UPLOAD
Flight::route( "/upload/@id", function( $id ){

	check_membership();

	$game = R::load( "game" , $id );

	if( $game["user_id"] != $_SESSION["memberlog"] )
		{ em( "unauthorized" );exit( "unauthorized" ); }

	$game['author'] = str_replace( " ", "-", $_SESSION['nick'] );
	
	if ( is_localhost() )
		{
			$files = array_slice(scandir( "F:\\xampp\htdocs\\moody.ink\\play\\{$game['author']}\\{$game['url']}\\mygame\\scenes" ), 2);
		}
	else
		{
			$files = array_slice(scandir( "/var/www/html/play/{$game['author']}/{$game['url']}/mygame/scenes" ), 2);
		}

	Flight::render( "upload" ,
			[
				"game" => $game,
                "files" => $files
			]
		);

	exit();

die( "err.upload" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ UNFILE
Flight::route( "/unfile/@id/@key", function( $id, $key ){

	$game = R::load( "game" , $id );

	if( $game["user_id"] != $_SESSION["memberlog"] )
		{ forward( "logout" ); }

	$game['author'] = str_replace( " ", "-", $_SESSION['nick'] );

	if ( is_localhost() )
		{
			$files = array_slice(scandir( "F:\\xampp\htdocs\\moody.ink\\play\\{$game['author']}\\{$game['url']}\\mygame\\scenes" ), 2);
		}
	else
		{
			$files = array_slice(scandir( "/var/www/html/play/{$game['author']}/{$game['url']}/mygame/scenes" ), 2);
		}

	$ext = explode( ".", $files[$key] );
	if( $ext[1] == "txt" )
		{
			$deletethis = $files[$key];
			try
				{
					if ( is_localhost() )
						{
							unlink( "F:\\xampp\htdocs\\moody.ink\\play\\{$game['author']}\\{$game['url']}\\mygame\\scenes\\{$deletethis}" );
						}
					else
						{
							unlink( "/var/www/html/play/{$game['author']}/{$game['url']}/mygame/scenes/{$deletethis}" );
						}
				}
			catch( Exception $e )
				{
					echo 'Caught exception: ',  $e->getMessage(), "\n";exit();
				}
		}

	forward( "upload/".$game['id'] );

die( "err.unfile" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ ASSETS UPLOAD
Flight::route( "POST /assets/@id", function( $id ){

	//sm( "attempting" );

	$game = R::load( "game" , $id );

	if( $game["user_id"] != $_SESSION["memberlog"] )
		{ forward( "logout" ); }

	$request = Flight::request();
	$files = $request->files['images']; // (id) which is your html element name

	if( $_SESSION['override']==1 )
		{ $max_size = 1024*10000; } // 1GB
	else
		{ $max_size = 1024*2000; } // 2MB

	$cleantitle = strtolower( str_replace( " ", "-", $game['url'] ) );
	$cleanauthor = strtolower( str_replace( " ", "-", $_SESSION['nick'] ) );
	$dir = "/var/www/html/play/".$cleanauthor.'/'.$cleantitle.'/mygame/';
	$tdir = "";
	$extensions = 
		[ 'jpeg', 'jpg', 'png', 'gif', 'JPEG', 'JPG', 'PNG', 'GIF', 'mp3', 'MP3', 'wav', 'WAV', 'svg', 'SVG', 'txt' ];
	$count = 0;

	if( $_SERVER['REQUEST_METHOD'] == 'POST' and isset( $files ) )
		{
			// loop all files
			foreach ( $files['name'] as $i => $name )
				{
					//sm( $i );
					//sm( "each: ".$count );
					// if file not uploaded then skip it
					if ( !is_uploaded_file( $files['tmp_name'][$i] ) )
						{ em('not uploaded');continue; }
					if( !empty( $_SESSION['override'] ))
						{
							if( $_SESSION['override'] != 1 )
								{
									em( "checksize" );
									// skip large files
									if ( $files['size'][$i] >= $max_size )
										{ em('too large');continue; }
								}
						}
					// skip non-supported files
					if( !in_array(pathinfo($name, PATHINFO_EXTENSION), $extensions) )
						{ em('not supported');continue; }

					if( pathinfo($name, PATHINFO_EXTENSION) == "txt" )
						{ $tdir = $dir . "scenes/"; }
					else
						{ $tdir = $dir; }
					
					//sm($tdir);
					//em( $files["tmp_name"][$i]." to ". $tdir . $name );

					if(file_exists( $tdir . $name ))
						{ unlink( $tdir . $name ); }

					// now we can move uploaded files
					if( move_uploaded_file( $files["tmp_name"][$i], $tdir . $name ) )
						{
							//em( "moved ".$files["tmp_name"][$i] );
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
					else
						{
							//em( "didn't move ".$files["tmp_name"][$i] );
						}
				}
/*
			# https://cogdemos.ink/play/dashingdon/the-burden/mygame/dashpic.jpg
			if( file_exists( "/var/www/{$dir}dashpic.jpg" ) )
				{
					if( !smart_resize_image( '/var/www/'.$dir.'dashpic.jpg',null,360,195,false,'/var/www/'.$dir.'dashpic.jpg',true,false,100 ) )
						{
							em( 'eor' );
						}
				}
*/
		}
	else
		{
			em( 'not post, not images' );
		}

	//sm( "count ".$count );

	echo json_encode( array( 'count' => $count ) );
	exit();

die( "err.assets_upload" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ SCENES UPLOAD
Flight::route( "POST /_scenes/@id", function( $id ){

	$game = R::load( "game" , $id );

	if( $game["user_id"] != $_SESSION["memberlog"] )
		{ forward( "logout" ); }

	$request = Flight::request();
	$files = $request->files['images']; // (id) which is your html element name

	if( $_SESSION['override']==1 )
		{ $max_size = 1024*10000; } // 1GB
	else
		{ $max_size = 1024*2000; } // 2MB

	$cleantitle = strtolower( str_replace( " ", "-", $game['url'] ) );
	$cleanauthor = strtolower( str_replace( " ", "-", $_SESSION['nick'] ) );
	$dir = "play/".$cleanauthor.'/'.$cleantitle.'/mygame/';
	$extensions = 
		[ 'txt' ];
	$count = 0;

	if( $_SERVER['REQUEST_METHOD'] == 'POST' and isset( $files ) )
		{
			// loop all files
			foreach ( $files['name'] as $i => $name )
				{
					// if file not uploaded then skip it
					if ( !is_uploaded_file( $files['tmp_name'][$i] ) ){ em('not uploaded');continue; }
					if( $_SESSION['override'] != 1 )
						{
								// skip large files
							if ( $files['size'][$i] >= $max_size ){ em('too large');continue; }
						}
					// skip unprotected files
					if( !in_array(pathinfo($name, PATHINFO_EXTENSION), $extensions) ){ emm('unprotected');continue; }

					// now we can move uploaded files
						if( move_uploaded_file( $files['tmp_name'][$i], $dir . $name ) )
							{
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
			# https://cogdemos.ink/play/dashingdon/the-burden/mygame/dashpic.jpg
			if( file_exists( "/var/www/{$dir}dashpic.jpg" ) )
				{
					if( !smart_resize_image( '/var/www/'.$dir.'dashpic.jpg',null,360,195,false,'/var/www/'.$dir.'dashpic.jpg',true,false,100 ) )
						{
							echo 'eor';
						}
				}
		}
	else
		{
			em('not post, not images');
		}

	echo json_encode( array( 'count' => $count ) );
	exit();

die( "err.scenes_upload" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ CREATE
Flight::route( "/create" , function(){

	check_membership();

	Flight::render( "create" );

	exit();

die( "err.create" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ EDIT
Flight::route( "/edit/@id" , function( $id ){

	check_membership();

	$game = R::load( "game" , $id );

	if( $game["user_id"] != $_SESSION["memberlog"] )
		{ forward( "logout" ); }

	$checkbox['private'] = "";
	$checkbox['saveallowed'] = "";

	if($game['private'] == 1)
		{ $checkbox['private'] = 'checked=checked'; }

	if($game['saveallowed'] == 1)
		{ $checkbox['saveallowed'] = 'checked=checked'; }

	$authorfix = str_replace( " ", "-", $_SESSION['nick'] );
	$titlefix = $game['url'];
	$urltitlefix = str_replace( "'", "\'", $titlefix );
	$urlauthorfix = str_replace( "'", "\'", $authorfix );

	if( !empty( $game['restricted'] ) )
		{
			$permitted = explode( ",", $game['restricted'] );
			foreach( $permitted as $oneuser )
				{
					$thisnick = R::getCell( "SELECT nick FROM user WHERE id = ? LIMIT 1", [ $oneuser ] );
					$allowed[] = [ "id" => $oneuser, "nick" => $thisnick ];
				}
		}
	else
		{
			$allowed = [];
		}
	

	Flight::render( "edit" ,
			[
				"game" => $game,
				"authorfix" => $authorfix,
				"titlefix" => $titlefix,
				"urltitlefix" => $urltitlefix,
				"urlauthorfix" => $urlauthorfix,
				"checkbox" => $checkbox,
				"allowed" => $allowed,
				"saveallowed" => $game['saveallowed']
			]
		);

	exit();

die( "err.edit" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ _EDIT
Flight::route( "/_edit/@id" , function( $id ){

	check_membership();

	$game = R::load( "game" , $id );

	if( $game["user_id"] != $_SESSION["memberlog"] )
		{ em( "Unauthorized access." );forward( "logout" ); }

	$safePost = filter_input_array( INPUT_POST );

	$game->blurb = $safePost['blurb'];
	$game->feedbackurl = $safePost['feedbackurl'];
	
	if( !empty( $safePost['private'] ) )
		{
			if( $safePost['private'] == "on" )
				{ $game->private = 1; }
			else
				{ $game->private = 0; }
		}
	else
		{
			$game->private = 0;
		}
	
	if( !empty( $safePost['saveallowed'] ) )
		{
			if( $safePost['saveallowed'] == "on" )
				{ $saveallowed = 1; }
			else
				{ $saveallowed = 0; }
		}
	else
		{
			$saveallowed = 0;
		}

	$basura = R::store( $game );

	sm( "Game details have been saved." );
	forward( "edit/".$game['id'] );

	exit();

die( "err._edit" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ _CREATE
Flight::route( "/_create" , function(){

	check_membership();
	
	$safePost = filter_input_array( INPUT_POST );

	if( $safePost['title']=='' || $_SESSION['memberlog']=='' )
		{
			header('Location: index.php');
			exit();
		}

	//$thisnick = $_SESSION['nick'];
	$cleanauthor = strtolower( str_replace( " ", "-", $_SESSION['nick'] ) );
	$gamename = smallclean($safePost['title']);
	$thisgame = clean($safePost['title']);

	$structure = "/var/www/html/play/$cleanauthor/$thisgame/mygame/scenes";
	$gamebase = "/var/www/html/play/$cleanauthor/$thisgame/";
	$thisbase = "/var/www/html/play/_base/";

	if ( !file_exists( $structure ) )
		{
			if (!mkdir($structure, 0777, true))
				{
					exit( "Failed to create folders..." );
				}
		}

	$thesefiles = 
		[
			'.htaccess',
			'favicon.ico',
			'index.php',
			'index.html',
			'mygame/index.php',
			'mygame/scenes/index.php'
		];

	foreach( $thesefiles as $file )
		{
			$newfile = $gamebase.$file;
			$file = $thisbase.$file;
			if ( !copy( $file, $newfile ) )
				{
					die("failed to copy $file");
				}
		}

	if( !empty( $safePost['private'] ) )
		{
			if( $safePost['private'] == "on" )
				{ $private = 1; }
			else
				{ $private = 0; }
		}
	else
		{
			$private = 0;
		}

	if( !empty( $safePost['saveallowed'] ) )
		{
			if( $safePost['saveallowed'] == "on" )
				{ $saveallowed = 1; }
			else
				{ $saveallowed = 0; }
		}
	else
		{
			$saveallowed = 0;
		}

	$newgame = R::dispense('game');
	$newgame->user_id = $_SESSION['memberlog'];
	$newgame->title = $gamename;
	$newgame->url = $thisgame;
	$newgame->blurb = $safePost['blurb'];
	$newgame->private = $private;
	$newgame->restricted = "";
	$newgame->suspended = 0;
	$newgame->version = 0;
	$newgame->gametype = 0;
	$newgame->words = 0;
	$newgame->stylesheet = 0;
	$newgame->saveallowed = $saveallowed;
	$newgame->feedbackurl = $safePost['feedbackurl'];
	$newgame->lastupdate = date('m-d-y');
	$newgame->apikey = strtoupper(uniqidReal(8).'-'.uniqidReal(4).'-'.uniqidReal(8));
	$id = R::store($newgame);

	sm( "Game details have been saved." );
	forward( "edit/".$id );

	exit();

die( "err._create" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ DELETE
Flight::route( "/delete/@id" , function( $id ){

	check_membership();

	$game = R::load( "game" , $id );

	if( $game["user_id"] != $_SESSION["memberlog"] )
		{ em( "Unauthorized access." );forward( "logout" ); }

	Flight::render( "delete" ,
			[
				"game" => $game
			]
		);
	exit();

die( "err.delete" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ DELETE
Flight::route( "POST /deletegame/@id" , function( $id ){

	check_membership();

	$safePost = filter_input_array( INPUT_POST );
	
	if( $safePost['gameid'] != $id )
		{
			em( "<b>ERROR:</b> Mismatched game ID" );
			forward( "dashboard" );
		}

	$game = R::load( "game" , $id );

	if( $game["user_id"] != $_SESSION["memberlog"] )
		{ em( "Unauthorized access." );forward( "dashboard" ); }

	$rando = date('Y').'-'.date('m').'-'.date('B');
	$cleantitle = strtolower(str_replace(" ", "-", $game['title']));
	$gonegame = '/var/www/html/play/'.$_SESSION['nick'].'/'.$cleantitle;
	$byegame = '/var/www/html/play/'.$_SESSION['nick'].'/_'.$rando.'_'.$cleantitle;

	rename($gonegame,$byegame);

	$badgame = R::load('game', $id);
	R::trash( $badgame );


	forward( "dashboard" );
	exit();

die( "err.delete" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ WORDS
Flight::route( "/words/@id" , function( $id ){

	check_membership();

	$game = R::load( "game" , $id );

	if( $game["user_id"] != $_SESSION["memberlog"] )
		{ em( "Unauthorized access." );forward( "logout" ); }

	$game['author'] = str_replace( " ", "-", $_SESSION['nick'] );

	$dir = "/var/www/html/play/".$game['author']."/".$game['url']."/mygame/scenes/";

	$content = file_get_contents( $dir."startup.txt" );

	# COUNT ORIGINAL LINES
	# ------------------------------------------------------------------------------------
		$count_original = countlines( $content );

	# COUNT "NO BLANK LINES" LINES
	# ------------------------------------------------------------------------------------
		$content = removeblanks( $content );
		$count_compact = countlines( $content );
		$part = explode( "\r\n" , $content );

	# REMOVE COMMENTS
	# ------------------------------------------------------------------------------------
		$removed = removecomments( $part );
		$part = $removed[0];
		$removedcomments = $removed[1];

	# FIND SCENE_LIST, TITLE, AND AUTHOR
	# ------------------------------------------------------------------------------------
		foreach( $part as $key => $sec )
			{
				if( strtoupper( $sec ) == "*SCENE_LIST" )
					{ $scene_list = $key + 1; }
				if( stripos( $sec , "*title"  ) !== false )
					{ $title = str_replace( "*title ", "", $part[ $key ] ); }
				if( stripos( $sec , "*author"  ) !== false )
					{ $author = str_replace( "*author ", "", $part[ $key ] ); }
			}

	# FIND END OF SCENE LIST
	# ------------------------------------------------------------------------------------
		$iteration = $scene_list;
		while( preg_match( "/\t/", $part[ $iteration ] ) )
			{ $iteration += 1; }
		$end = $iteration - 1;

	# SCENE_LIST
	# ------------------------------------------------------------------------------------
		$i = $scene_list;
		while( $i <= $end )
			{
				$scenes[] = str_replace( "\t", "", $part[ $i ] );
				$i += 1;
			}

	# TOTAL WORDS OF ALL SCENES
	# ------------------------------------------------------------------------------------
		$words = 0;
		if( !empty( $scenes ) )
			{
				foreach( $scenes as $scene )
					{
						$content = file_get_contents( $dir . $scene . ".txt" );
						$count_original = countlines( $content );
						$content = removeblanks( $content );
						$count_compact = countlines( $content );
						$part = explode( "\r\n" , $content );
						$removed = removecomments( $part );
						$part = $removed[0];
						$removedcomments = $removed[1];
						$words = $words + str_word_count( reconstruct( onlywords( $part ) ) );
					}
			}
		else
			{
				em( "Game scene list was not found." );
				em( "Automated word count requires coding with tabs, not spaces." );
				forward( "upload/".$game['id'] );
			}
		$total_words = $words;

	$game->words = $words;
	$basura = R::store( $game );

	sm( "Word count has been updated." );
	forward( "upload/".$game['id'] );

	exit();

die( "err.words" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ PLAY
Flight::route( "/play/@author/@url" , function( $author , $url ){

	$game = R::getRow(
			"SELECT game.*, user.nick FROM game INNER JOIN user ON game.user_id = user.id WHERE url = :url AND user.nick = :author",
			[ ":author" => $author , ":url" => $url ]
		);

/*
Array
(
    [id] => 738
    [user_id] => 2
    [private] => 0
    [version] => 0
    [title] => Phobia
    [url] => phobia
    [words] => 0
    [blurb] => Inspired by Obsidian

"With great fear comes great power."
    [stylesheet] => 3
    [rating] => 0
    [saveplugin] => 0
    [feedbackurl] => https://forum.choiceofgames.com/t/phobia-wip-based-on-concept-by-obsidian/20360
    [lastupdate] => 17-11-07
    [gamekey] => 
    [nick] => dashingdon
)
*/

	if( empty( $game ) )
		{ forward( "notfound" ); }

	$slots = [];
	if( !empty( $_SESSION["memberlog"] ) )
		{
			$slots = loadSaves( $SESSION["memberlog"] , $game["id"] );
		}






scuttle( $game );


	//playgame( $author , $url );

die( "err.play" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ IMAGE
Flight::route( "/image/@id", function( $id ){

	$basura = R::load( "game", $id );
	$game = $basura->export();
	unset( $basura );
	$basura = R::load( "user", $game['user_id'] );
	$member = $basura->export();
	$fix_author = str_replace( " ", "-", $member['nick'] );
	$fix_title = $game['url'];
	$games_id = $game['id'];
	$url_title = str_replace( "'", "\'", $fix_title );
	$url_author = str_replace( "'", "\'", $fix_author );

	if( file_exists( "F:/xampp/htdocs/play/" . $fix_author . "/" . $fix_title . "/mygame/dashpic.jpg" ) )
		{ $url_picture = "https://cogdemos.ink/play/". $url_author . "/" . $url_title . "/mygame/dashpic.jpg"; }
	elseif( file_exists( "F:/xampp/htdocs/play/" . $fix_author . "/" . $fix_title . "/mygame/dashpic.JPG" ) )
		{ $url_picture = "https://cogdemos.ink/play/" . $url_author . "/" . $url_title . "/mygame/dashpic.JPG"; }
	else
		{ $url_picture = "img/game-1.jpg"; }

scuttle( $url_picture );

die( "err.image" ); });
# ]




# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ _LOGIN
Flight::route( "/_login" , function(){

	$safePost = filter_input_array( INPUT_POST );

	$thismember = R::getRow(
			"SELECT * FROM user WHERE email = :username" ,
			[ ":username" => $safePost["email"] ]
		);

	if( $thismember == "" )
		{
			em( "Invalid login credentials" );
			forward();
		}

	if( !password_verify( $safePost['pass'] , $thismember['pass'] ) )
		{
			em( "Invalid login credentials" );
			forward();
		}

	$reload = R::load(
			"user" , $thismember["id"]
		);

	$reload->logins = $reload->logins + 1;
	$newreload = R::store( $reload );
	$_SESSION["memberlog"] = $thismember["id"];
	$_SESSION["nick"] = $thismember["nick"];
	$_SESSION["email"] = $thismember["email"];
	$_SESSION["override"] = $thismember["override"];
	$_SESSION["favorites"] = $thismember["favorites"];
	$_SESSION["logger"] = "jackedin";

	if( !empty( $thismember['moderator'] ))
		{
			if( $thismember["moderator"] == "1" )
				{ $_SESSION["moderator"] = "soauth"; }
		}
	else
		{
			$_SESSION["moderator"] = "none";
		}

	forward( "dashboard" );

die( "err._login" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ GAMELOGIN
Flight::route( "/gamelogin/@id" , function( $id ){

	$safePost = filter_input_array( INPUT_POST );

	check_token( $safePost );

	$thismember = R::getRow(
			"SELECT * FROM user WHERE email = :username" ,
			[ ":username" => $safePost["email"] ]
		);

	if( $thismember == "" )
		{
			em( "Invalid login credentials." );
			forward( "play/{$safePost['author']}/{$safePost['game']}/mygame" );
		}

	if( !password_verify( $safePost['password'] , $thismember['pass'] ) )
		{
			em( "Invalid login credentials." );
			forward( "play/{$safePost['author']}/{$safePost['game']}/mygame" );
		}

	$reload = R::load(
			"user" , $thismember["id"]
		);

	$reload->logins = $reload->logins + 1;
	$newreload = R::store( $reload );
	$_SESSION["memberlog"] = $thismember["id"];
	$_SESSION["nick"] = $thismember["nick"];
	$_SESSION["email"] = $thismember["email"];
	$_SESSION["override"] = $thismember["override"];
	$_SESSION["logger"] = "jackedin";

	if( $thismember["moderator"] == "1" )
		{ $_SESSION["moderator"]= "soauth"; }

	nm( "Welcome back, {$_SESSION['nick']}!" );
	$_SESSION['loadtemp'] = true;
	forward( "play/{$safePost['author']}/{$safePost['game']}/mygame" );

die( "err.gamelogin" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ TOPMENU
Flight::route( "/_topmenu/@game_id" , function( $game_id ){

	$favorites = R::getCell( "SELECT favorites FROM user WHERE id = ? LIMIT 1", [ $_SESSION['memberlog'] ] );
	$favarray = explode( ",", $favorites );
	if( in_array( $thisgame['id'], $favarray ) )
		{ $favorite = true; }

	$game = R::load( "game" , $game_id );
	if( empty( $game ) )
		{ echo json_encode( [ "error" => "GAME NOT FOUND" ] );exit(); }

	if( strpos( $game[ "feedbackurl" ] , "@" ) !== FALSE )
		{
			$feedback = '<a href="mailto:' . $game[ "feedbackurl" ] . '" class="nav-links" target="_blank"><i class="fa fa-envelope"></i> Email Author</a>';
		}
	else
		{
			$feedback = '<a href="' . $game[ "feedbackurl" ] . '" class="nav-links" target="_blank"><i class="fa fa-comments"></i> Discuss</a>';
		}

	Flight::render( "topmenu" ,
			[
				"favorite" => $favorite,
				"feedback" => $feedback,
				"thisgame" => $game
			]
		);
	exit();

die( "err._topmenu" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ _FORGOT
Flight::route( "/_forgot" , function(){

	$safePost = filter_input_array( INPUT_POST );

	check_token( $safePost );

	$thismember = R::getRow(
			'SELECT * FROM users WHERE email = :username',
			[ ':username' => $safePost['email'] ]
		);

	if( $thismember == '' )
		{
			exit( "No registration for that email address." );
		}
	else
		{
			$reload = R::load( "user", $thismember['id'] );
			$plainpass = uniqid();
			$newpass = password_hash( $plainpass, PASSWORD_DEFAULT );
			$reload->pass = $newpass;
			$update = R::store( $reload );
			$to      = $reload->email;
			$subject = 'New Password for cogdemos.ink';
			$message = 'Your password for cogdemos.ink has been reset to: '.$plainpass;
			$headers = 'From: no-reply@cogdemos.ink' . "\r\n" .
					'Reply-To: no-reply@cogdemos.ink' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
					
			scuttle($message." \r\n ".$newpass);
					
			mail( $to, $subject, $message, $headers );
			exit( "A new password has been sent to your email address." );
		}

die( "err.forgot" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ ANNOUNCE
Flight::route( "/announce/@id" , function( $id ){

	$thisgame = R::load( "game", $id );

	if( empty( $thisgame ) )
		{
			em( "Game not found." );
			forward( "dashboard" );
		}

	$thisdate = date('y-m-d');

	if( $thisgame->lastupdate == $thisdate )
		{
			em( "<i>&ldquo;{$thisgame->title}&rdquo;</i> has already been marked updated as of today." );
			forward( "dashboard" );
		}

	$thisgame->lastupdate = $thisdate;
	$gameid = R::store( $thisgame );

	sm( "<i>&ldquo;{$thisgame->title}&rdquo;</i> has been marked as updated as of today." );
	forward( "dashboard" );

die( "err.announce" ); });
# ]











# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ 404
Flight::map( "notFound" , function(){

	Flight::render( "4004" );

	exit();

die( "err.notFound" ); });
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ FLIGHT START
Flight::set( "folder" , "/" );
Flight::start();
# ]



# ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••• [ COMMON FUNCTIONS

	# FORWARD TO ANOTHER ROUTE
	# ------------------------------------------
		function forward( $pathForward = "" )
			{ header( "Location: /".$pathForward ); exit(); }

	function numberOfAll()
		{ return R::count( "game" ); }

	function numberOfGames()
		{ return R::count( "game" , " private = 0 AND blurb != '' " ); }

	function numberOfUsers()
		{ return R::count( "user" ); }

	# SEND TO A GAME PAGE
	# ------------------------------------------
		function playgame( $author , $url )
			{
				$fixAuthor = str_replace( " " , "-" , $author );
				header( "Location: " . 
						"/play/" . $fixAuthor . "/" . $url . "/"
					);
				// "https://cogdemos.ink/play/" . $fixAuthor . "/" . $url . "/"
				exit();
			}

	# CHECK IF LOGGED IN FOR MEMBER PAGES
	# ------------------------------------------
		function check_membership()
			{
				if( empty( $_SESSION['memberlog'] ) )
					{ forward( "logout" ); }
				$member = R::getCell("
						SELECT id FROM user WHERE id = ? ",
							[
								$_SESSION["memberlog"]
							]
					);
				if( !$member )
					{ forward( "logout" ); }
			}

	# CHECK FORM TOKEN
	# ------------------------------------------
		function check_token( $safePost )
			{
				if( !empty( $safePost['token'] ) )
					{
						if (hash_equals($_SESSION['token'], $safePost['token']))
							{
								return true;
							}
						else
							{ scuttle( "MISMATCH" );forward( "logout" ); }
					}
				else
					{ scuttle( "MISSING" );forward( "logout" ); }
			}

	# GET DASH PIC
	# ------------------------------------------
		function dashpic( $fixAuthor , $fixTitle )
			{
				$url_title = str_replace( "'" , "\'" , $fixTitle );
				$urlAuthor = str_replace( "'" , "\'" , $fixAuthor );
				if(
						file_exists(
								"/var/www/play/" . $fixAuthor . "/" .
								$fixTitle . "/mygame/dashpic.jpg"
							)
						)
					{
						return "https://cogdemos.ink/play/". $urlAuthor .
							"/" .$urlTitle . "/mygame/dashpic.jpg";
					}
				elseif(
						file_exists(
								"/var/www/play/" . $fixAuthor . "/" .
								$fixTitle . "/mygame/dashpic.JPG"
							)
						)
					{
						return "https://cogdemos.ink/play/" . $urlAuthor .
							"/" .$urlTitle . "/mygame/dashpic.JPG";
					}
				else
					{
						return "";
					}
			}

	# LOAD GAME SAVES
	# ------------------------------------------
		function loadSaves( $user_id , $game_id )
			{
				return R::getAll(
						"SELECT save.*, game.title, game.url, user.nick FROM save INNER JOIN game ON save.game_id = game.id INNER JOIN user ON save.user_id = user.id WHERE save.user_id = :uid AND save.game_id = :gid",
						[ ":uid" => $user_id , ":gid" => $game_id ]
					);
			}

	# CREATE A RANDOM TEMPORARY PASSWORD
	# ------------------------------------------
		function random_password( $length = 8 )
			{
				$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
				$password = substr( str_shuffle( $chars ) , 0 , $length );
				return $password;
			}

	# CREATE A UNIQUE ID
	# ------------------------------------------
		function uniqidReal( $length = 5 )
			{
				if( function_exists( "random_bytes" ) )
					{
						$bytes = random_bytes( ceil( $length / 2 ) );
					}
				elseif( function_exists( "openssl_random_pseudo_bytes" ) )
					{
						$bytes = openssl_random_pseudo_bytes( ceil( $length / 2 ) );
					}
				else
					{
						throw new Exception( "no cryptographically secure random function available" );
					}
				return substr( bin2hex( $bytes ) , 0 , $length );
			}

	# USED IN TESTING TO OUTPUT TO SCREEN
	# ------------------------------------------
		function scuttle( $output , $break = true )
			{
				echo "<pre>";
				print_r( $output );
				echo "</pre>";
				if( $break ){ exit(); }
			}

	# CHECK SUBMITTED GOOGLE RECAPTCHA
	# ------------------------------------------
		function captchacheck( $input )
			{
				$goods = [
						"secret" => $recaptchakey,
						"response" => $input
					];
				$prove = curl_init();
				curl_setopt( $prove , CURLOPT_URL , "https://www.google.com/recaptcha/api/siteverify" );
				curl_setopt( $prove , CURLOPT_POST , true );
				curl_setopt( $prove , CURLOPT_POSTFIELDS , http_build_query( $goods ) );
				curl_setopt( $prove , CURLOPT_SSL_VERIFYPEER , false );
				curl_setopt( $prove , CURLOPT_RETURNTRANSFER , true );
				$reply = json_decode(curl_exec( $prove ) , true );
				if( $reply["success"] != true )
					{ return false; }
				else
					{ return true; }
			}

	# RENDER SESSION ERROR MESSAGE
	# ------------------------------------------
		function render_error()
			{
				if( $_SESSION["errormessage"] )
					{
echo <<< HTML
<h3 class='text-center bg-danger text-light p-2 card font-weight-bold'>
	{$_SESSION["errormessage"]}
</h3>
HTML;
						unset( $_SESSION["errormessage"] );
					}
			}

	# RENDER SESSION NOTICE MESSAGE
	# ------------------------------------------
		function render_notice()
			{
				if( $_SESSION["noticemessage"] )
					{
echo <<< HTML
<h3 class='text-center bg-success text-light p-2 card font-weight-bold'>
	{$_SESSION["noticemessage"]}
</h3>
HTML;
						unset( $_SESSION["noticemessage"] );
					}
			}

	# SET SESSION ERROR MESSAGE
	# ------------------------------------------
		function em( $input )
			{ $_SESSION["errormessage"][] = $input; }

	# SET SESSION NOTICE MESSAGE
	# ------------------------------------------
		function nm( $input )
			{ $_SESSION["noticemessage"][] = $input; }

	# SET SESSION SUCCESS MESSAGE
	# ------------------------------------------
		function sm( $input )
			{ $_SESSION["successmessage"][] = $input; }

	# LOGOUT USER
	# ------------------------------------------
		function logout( $goback = NULL )
			{
				em( "Your login has expired.<br>Please log in again." );
				if( !is_null( $goback ) )
					{ $_SESSION["goback"] = $goback; }
				forward( "logout" );
			}


	# CHECK IF LOCALHOST
	# ------------------------------------------
	function is_localhost()
		{
			$whitelist = array( '127.0.0.1', '::1' );
			if ( in_array( $_SERVER['REMOTE_ADDR'], $whitelist ) )
				{
					return true;
				}
		}

	# RESIZE IMAGE
	# ------------------------------------------
		function smart_resize_image(
					$file,
					$string             = null,
					$width              = 320, 
					$height             = 180, 
					$proportional       = false, 
					$output             = 'dashpic.jpg', 
					$delete_original    = true, 
					$use_linux_commands = false,
					$quality = 100
				)
			{
				if ( $height <= 0 && $width <= 0 ) return false;
				if ( $file === null && $string === null ) return false;

				# Setting defaults and meta
				$info                           = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
				$image                          = '';
				$final_width                    = 0;
				$final_height                   = 0;
				list( $width_old, $height_old ) = $info;
				$cropHeight = $cropWidth        = 0;

				# Calculating proportionality
				if( $proportional )
					{
						if      ($width  == 0)  $factor = $height/$height_old;
						elseif  ($height == 0)  $factor = $width/$width_old;
						else                    $factor = min( $width / $width_old, $height / $height_old );

						$final_width  = round( $width_old * $factor );
						$final_height = round( $height_old * $factor );
					}
				else
					{
						$final_width = ( $width <= 0 ) ? $width_old : $width;
						$final_height = ( $height <= 0 ) ? $height_old : $height;
						$widthX = $width_old / $width;
						$heightX = $height_old / $height;
						
						$x = min($widthX, $heightX);
						$cropWidth = ($width_old - $width * $x) / 2;
						$cropHeight = ($height_old - $height * $x) / 2;
					}

				# Loading image to memory according to type
				switch ( $info[2] ) {
					case IMAGETYPE_JPEG:
						$file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);
						break;
					case IMAGETYPE_GIF:
						$file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);
						break;
					case IMAGETYPE_PNG:
						$file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);
						break;
					default:
						return false;
				}
				
				# This is the resizing/resampling/transparency-preserving magic
				$image_resized = imagecreatetruecolor( $final_width, $final_height );
				if( ( $info[2] == IMAGETYPE_GIF ) || ( $info[2] == IMAGETYPE_PNG ) )
					{
						$transparency = imagecolortransparent($image);
						$palletsize = imagecolorstotal($image);

						if ($transparency >= 0 && $transparency < $palletsize)
							{
								$transparent_color  = imagecolorsforindex($image, $transparency);
								$transparency       = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
								imagefill($image_resized, 0, 0, $transparency);
								imagecolortransparent($image_resized, $transparency);
							}
						elseif ($info[2] == IMAGETYPE_PNG)
							{
								imagealphablending($image_resized, false);
								$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
								imagefill($image_resized, 0, 0, $color);
								imagesavealpha($image_resized, true);
							}
					}
				imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);
			
			
				# Taking care of original, if needed
				if ( $delete_original ) {
					if ( $use_linux_commands ) exec('rm '.$file);
					else @unlink( $file );
				}

				# Preparing a method of providing result
				switch ( strtolower( $output ) ) {
					case 'browser':
						$mime = image_type_to_mime_type( $info[2] );
						header("Content-type: $mime");
						$output = NULL;
						break;
					case 'file':
						$output = $file;
						break;
					case 'return':
						return $image_resized;
						break;
					default:
						break;
				}
				
				# Writing image according to type to the output destination and image quality
				switch ( $info[2] ) {
					case IMAGETYPE_GIF:
						imagegif( $image_resized, $output );
						break;
					case IMAGETYPE_JPEG:
						imagejpeg( $image_resized, $output, $quality );
						break;
					case IMAGETYPE_PNG:
						$quality = 9 - (int)( ( 0.9*$quality )/10.0 );
						imagepng( $image_resized, $output, $quality );
						break;
					default:
						return false;
				}
				return true;
			}

	# REPLACE SPACES WITH HYPHENS AND REMOVE SPECIAL CHARACTERS
	# ------------------------------------------------------------------------------------
		function clean( $string )
			{
				$string = str_replace( ' ', '-', $string );
				$string = strtolower( $string );
				return preg_replace( '/[^A-Za-z0-9\-]/', '', $string );
			}

		function smallclean($string)
			{
				return strtolower( str_replace( '%', '-', $string ) );
			}

	# COUNT NUMBER OF LINES IN BLOB
	# ------------------------------------------------------------------------------------
		function countlines( $content )
			{ return count( explode( "\r\n" , $content ) ); }

	# REMOVE ALL BLANK LINES FROM BLOB
	# ------------------------------------------
		function removeblanks( $content )
			{
				return rtrim(
						preg_replace(
								"/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\r\n", $content
							)
					);
			}

	# REMOVE COMMENTS FROM ARRAY
	# ------------------------------------------------------------------------------------
		function removecomments( $part )
			{
				$removedcomments = 0;
				foreach( $part as $key => $sec )
					{
						if( stripos( $sec , "*comment"  ) !== false )
							{
								unset( $part[ $key ] );
								$removedcomments += 1;
							}
					}
				$part = array_values( $part );
				return
					[
						$part ,
						$removedcomments
					];
			}

	# REMOVE ALL BUT ACTUAL WORDS FROM ARRAY
	# ------------------------------------------------------------------------------------
		function onlywords( $part )
			{
				$commands =
					[
						"*create" ,
						"*temp" ,
						"*set" ,
						"*fake_choice" ,
						"*if" ,
						"*page_break" ,
						"*label" ,
						"*goto" ,
						"*gosub" ,
						"*gosub_scene" ,
						"*bug"
					];
				foreach( $part as $key => $sec )
					{
						$part[ $key ] =
							preg_replace(
									"/[^ \w]+/" , "" , $part[ $key ]
								);
						foreach( $commands as $command )
							{
								if( stripos( strtolower( $sec ) , $command  ) !== false || empty( $part[ $key ] ) )
									{ unset( $part[ $key ] ); }
							}
					}
				$part = array_values( $part );
				return $part;
			}

	# RECONSTRUCT BLOB FROM ARRAY
	# ------------------------------------------------------------------------------------
		function reconstruct( $part )
			{
				$merged = "";
				foreach( $part as $line )
					{ $merged = $merged . " " . $line; }
				return $merged;
			}
