<?php
session_start();
//require_once($_SERVER['DOCUMENT_ROOT']."/../../../hid/gamehead.php");
require_once($_SERVER['DOCUMENT_ROOT']."../../cghost/hid/gamehead.php");
?>
<!DOCTYPE html>
<html>
<!--
Copyright 2010 by Dan Fabulich.

Dan Fabulich licenses this file to you under the
ChoiceScript License, Version 1.0 (the "License"); you may
not use this file except in compliance with the License. 
You may obtain a copy of the License at

 http://www.choiceofgames.com/LICENSE-1.0.txt

See the License for the specific language governing
permissions and limitations under the License.

Unless required by applicable law or agreed to in writing,
software distributed under the License is distributed on an
"AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
either express or implied.
-->
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name = "viewport" content = "width = device-width, initial-scale = 1.0, maximum-scale = 1.0">
<!-- INSERT correct meta values -->
<!-- <title>Multiple Choice Example Game | My First ChoiceScript Game</title> -->
<script>window.version="UNKNOWN"</script>
<script src="/cog/version.js"></script>
<script src="/cog/persist.js"></script>
<script src="/cog/alertify.min.js"></script>
<script src="/cog/util.js"></script>
<link href="/cog/alertify.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<?php /* <link href="/fontawesome/css/all.min.css" rel="stylesheet" type="text/css"> */ ?>
<link href="/cog/style0.css" rel="stylesheet" type="text/css">
<style id="dynamic"></style>
<script src="/cog/ui.js"></script>
<script src="/cog/scene.js"></script>
<script src="/cog/navigator.js"></script>
<script src="/cog/mygame.js"></script>
<?php if(!empty( $_SESSION["memberlog"] ) ){ ?>
<?php if( $thisgame['saveallowed'] || $thisgame['user_id'] == $_SESSION["memberlog"] ) { ?>
	<script src="/cog/savetoserver.js" data-game="<?=$thisgame['id'];?>"></script>
<?php } } ?>
<link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
<!--[if IE 6]><style>.alertify-logs { position: absolute; }</style><![endif]-->
<meta name="apple-mobile-web-app-capable" content="yes" />
<script>
window.storeName = null;
//Scene.generatedFast = true;
var rootDir = "/cog/";
</script>
</head>
<body>
<nav id="topmenu" class="navbar">
<?php
	if( !empty( $_SESSION['errormessage'] ) )
		{
			foreach( $_SESSION['errormessage'] as $message )
				{
					echo '<h3 class="alert alert-danger alert-dismissible text-center"><i class="fa fa-exclamation-circle"></i> '.$message.'</h3>';
				}
			unset( $_SESSION['errormessage'] );
		}
	if( !empty( $_SESSION['noticemessage'] ) )
		{
			foreach( $_SESSION['noticemessage'] as $message )
				{
					echo '<h3 class="alert alert-info alert-dismissible text-center"><i class="fa fa-exclamation-circle"></i> '.$message.'</h3>';
				}
			unset( $_SESSION['noticemessage'] );
		}
?>
	<span class="navbar-toggle" id="js-navbar-toggle">
			<i class="fas fa-bars"></i>
	</span>
	<a href="/" class="logo"><img src="/img/logo.png"></a>
	<ul class="main-nav" id="js-menu">
		<?php if( !empty( $_SESSION['memberlog'] ) ){ ?>
			<li>
					<?php if( $favorite ) { ?>
						<a href="javascript:void(0);" id="favorite" class="nav-links favorite"><i class="fa fa-sm fa-heart"></i> Favorite</a>
					<?php }else{ ?>
						<a href="javascript:void(0);" id="favorite" class="nav-links"><i class="fa fa-sm fa-heart"></i> Favorite</a>
					<?php } ?>
			</li>
		<?php } ?>
			<li>
					<?=$feedback;?>
			</li>
		<?php if( !empty( $_SESSION['memberlog'] ) ){ ?>
			<li>
					<a href="/report/<?=$thisgame['id'];?>" target="_blank" class="nav-links"><i class="fa fa-sm fa-exclamation-circle"></i> Report</a>
			</li>
		<?php } ?>
		<?php if( empty( $_SESSION['memberlog'] ) ){ ?>
			<li>
					<span class="nav-links" style="color:rgba(255,255,255,0.25);">|</span>
			</li>
			<li>
					<a href="javascript:void(0);" class="nav-links" id="login"><i class="fa fa-sm fa-sign-in-alt"></i> Login</a>
			</li>
		<?php } ?>
			<li>
					<span class="nav-links" style="color:rgba(255,255,255,0.25);">|</span>
			</li>
			<li>
					<a href="https://www.choiceofgames.com/make-your-own-games/choicescript-intro/" class="nav-links" target="_blank"><i class="fa fa-sm fa-external-link-alt"></i> Make Games with ChoiceScript</a>
			</li>
			<li>
					<span class="nav-links" style="color:rgba(255,255,255,0.25);">|</span>
			</li>
			<li>
					<a href="javascript:void(0);" id="ddclose" class="nav-links"><i class="fa fa-lg fa-times-circle"></i></a>
			</li>
	</ul>
</nav>
<nav class="nonav" style="display:none;min-height:2em;padding-top:1em;text-align:right;padding-right:1em">
<a href="javascript:void(0);" id="ddopen" class="nav-links" style="color:#555"><i class="fa fa-ellipsis-h"></i></a>
</nav>
<script>
let mainNav = document.getElementById('js-menu');
let navBarToggle = document.getElementById('js-navbar-toggle');
navBarToggle.addEventListener('click', function () {
  mainNav.classList.toggle('active');
});
</script>
<div class="container" id="container1">
<div id="advertisement">
</div>
<div id="header">
<div id="identity"><a href="#" id="email">you@example.com</a><a href="#" id="logout">Sign Out</a></div>
<h1 class="gameTitle"><!-- My First ChoiceScript Game --></h1>
<h2 id="author" class="gameTitle"><!--by INSERTINSERTINSERT --></h2>
<p id="headerLinks"></p>
<div id="buttons">
    <button id="statsButton" class="spacedLink" onclick="showStats()">Show Stats</button>
    <button id="restartButton" class="spacedLink" onclick="restartGame('prompt')">Restart</button>
    <button id="achievementsButton" onclick="showAchievements()" class="spacedLink" style="display: none">Achievements</button>
    <button id="bugButton" onclick="reportBug()" class="spacedLink" style="display: none">Report Bug</button>
    <button id="menuButton" onclick="textOptionsMenu()" class="spacedLink">Settings</button>
	<?php if(!empty( $_SESSION["memberlog"] ) ){ ?>
	<?php if( $thisgame['saveallowed'] || $thisgame['user_id'] == $_SESSION["memberlog"] ) { ?>
		<div class="dropdown">
			<button onclick="dropDown( 'saveMenu' )" class="dropbtn">Save</button>
			<div id="saveMenu" class="dropdown-content">
				<a href="javascript:void(0)" onclick="inkSave(1);"><i class="fa fa-save"></i> Slot 1</a>
				<a href="javascript:void(0)" onclick="inkSave(2);"><i class="fa fa-save"></i> Slot 2</a>
				<a href="javascript:void(0)" onclick="inkSave(3);"><i class="fa fa-save"></i> Slot 3</a>
				<a href="javascript:void(0)" onclick="inkSave(4);"><i class="fa fa-save"></i> Slot 4</a>
				<a href="javascript:void(0)" onclick="inkSave(5);"><i class="fa fa-save"></i> Slot 5</a>
				<a href="javascript:void(0)" onclick="inkSave(6);"><i class="fa fa-save"></i> Slot 6</a>
			</div>
		</div>
		<div class="dropdown">
			<button onclick="dropDown( 'loadMenu' )" class="dropbtn">Load</button>
			<div id="loadMenu" class="dropdown-content">
				<a href="javascript:void(0)" onclick="inkLoad(1);"><i class="fa fa-angle-right"></i> Slot 1</a>
				<a href="javascript:void(0)" onclick="inkLoad(2);"><i class="fa fa-angle-right"></i> Slot 2</a>
				<a href="javascript:void(0)" onclick="inkLoad(3);"><i class="fa fa-angle-right"></i> Slot 3</a>
				<a href="javascript:void(0)" onclick="inkLoad(4);"><i class="fa fa-angle-right"></i> Slot 4</a>
				<a href="javascript:void(0)" onclick="inkLoad(5);"><i class="fa fa-angle-right"></i> Slot 5</a>
				<a href="javascript:void(0)" onclick="inkLoad(6);"><i class="fa fa-angle-right"></i> Slot 6</a>
				<?php if( $_SESSION["memberlog"] == $thisgame['user_id'] ){ ?>
					<a href="javascript:void(0)" onclick="inkLoad(0);"><i class="fa fa-code"></i> DevSlot</a>
				<?php } ?>
			</div>
		</div>
	<?php } } ?>
</div>
</div>
<div id="main">
	<div id="text"></div>
	<script>startLoading();</script>
</div>
<div id="mobileLinks" class="webOnly"></div>
<noscript>
<p>This game requires JavaScript; please enable JavaScript and refresh this page.</p>
</noscript>

<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script>
		function dropDown( menu )
			{
				event.preventDefault();
				document.getElementById( "saveMenu" ).classList.remove( "show" );
				document.getElementById( "loadMenu" ).classList.remove( "show" );
				document.getElementById( menu ).classList.toggle( "show" );
			}
		window.onclick = function(event)
			{
				if( !event.target.matches( '.dropbtn ' ) )
					{
						var dropdowns = document.getElementsByClassName( "dropdown-content" );
						var i;
						for(i = 0; i < dropdowns.length; i++)
							{
								var openDropdown = dropdowns[i];
								if(openDropdown.classList.contains( 'show' ))
									{ openDropdown.classList.remove( 'show' ); }
							}
					}
			}
	$( document ).ready( function() {
		<?php if( !empty( $_SESSION["memberlog"] ) ) { ?>
		$( "#js-menu" ).on('click', '#favorite', function()
				{
					var data = { m: "<?=$_SESSION[ "memberlog" ];?>", g: "<?=$thisgame[ "id" ];?>" };
					$.post( "/favorite/" , data , function( msg )
							{
								var mess = JSON.parse( msg );
								if( mess.favorite )
									{
										$( "#favorite" ).addClass( 'favorite' );
									}
								else if( mess.unfavorite )
									{
										$( "#favorite" ).removeClass( 'favorite' );
									}
							}
						);
				}
			);
		<?php } ?>
		$( "#ddclose" ).click( function()
				{
					$( ".navbar" ).slideUp();
					$( ".nonav" ).fadeIn(2000);
					$( "#loginform" ).hide();
				}
			);
		$( "#ddopen" ).click( function()
				{
					$( ".navbar" ).slideDown();
					$( ".nonav" ).hide();
				}
			);
		$( "#login" ).click(function(){
			$( "#loginform" ).slideDown();
		});
		$( "#closelogin" ).click(function(){
			$( "#loginform" ).slideUp();
		});
		$( ".alert" ).show().delay(2000).fadeOut(1000);

	});
</script>


<div id="loginform" style="display:none;" class="text-center">
	<form method="POST" action="/gamelogin/<?=$thisgame['id'];?>">
	<input type="hidden" name="token" value="<?=$_SESSION['token'];?>">
	<input type="hidden" name="author" value="<?=$urlparts[2];?>">
	<input type="hidden" name="game" value="<?=$urlparts[3];?>">
	<input type="email" class="form-control" name="email" autocomplete="username" placeholder="Email Address"><br>
	<input type="password" class="form-control" name="password" autocomplete="password" placeholder="Password"><br>
	<button id="closelogin" type="button" class="loginbuttons mx-2" style="color:#C00"><i class="fa fa-times-circle"></i> Cancel</button> <button id="loginbutton" type="submit" class="loginbuttons mx-2"><i class="fa fa-sign-in-alt"></i> Login</button>
	</form>
</div>

</body>
</html>
