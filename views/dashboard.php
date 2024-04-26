	<?php require_once( "__head.php" ); ?>
</head>
<body>
	<?php require_once( "__menu.php" ); ?>
<div class="site">
<div class="site-content">
      <div class="container" style="margin-top:120px;">
<?php
$greetings = ["Heya" , "Hi" , "Howdy" , "Welcome" , "Hello" , "Greetings" , "Aloha" , "Ciao" , "Salutations" , "Bonjour" , "G'day" , "Good to see you" , "Hi there" , "Lovely day for it" , "Halloo" , "Wotcha" , "Well hello" , "Ahoy hoy" , "Well met" , "Aaayo" , "All good", "Nice to see you" , "Hellooo"];
$greeting = array_rand( $greetings );
$thisday = date("m-d-y");
?>
<header>
  <h1><?=$greetings[$greeting];?>, <?=$_SESSION['nick'];?>!</h1>
</header>

<fieldset id="filtergames">
  <?php
  if( $_SESSION["nick"] != "" )
    {
      ?>
	    <a class="btn btn-success btn-left" href="/create" onclick="grayOut();">Create Game</a>
	    <a class="nyroModal btn btn-secondary" href="/changepass">Change Password</a>
		<a class="nyroModal btn btn-default" href="/apicreds">API Create Key</a>
      <?php
        if( $_SESSION["moderator"] == "soauth" )
          {
            ?>
		        <a class="btn btn-dark" href="management.php" onclick="grayOut();">Manage Members</a>
		        <a class="btn btn-dark" href="moderate.php" onclick="grayOut();">Moderate Games</a>
            <?php
          }
      ?>
		  <a class="btn btn-danger" href="/logout" onclick="grayOut();">Logout</a>
      <?php
    }
  ?>
</fieldset>

<?php if( !empty( $_SESSION['favorites'] ) ){ ?>
<div class="p-4" style="background-color:#ffffe8">
<b>Favorites:</b>
<ul>
<?php
	$favgames = explode( ",", $_SESSION['favorites'] );
	foreach( $favgames as $favgame )
		{
			$onefav = R::getRow( "SELECT * FROM game WHERE id = ?", [ $favgame ] );
			$onenick = R::getCell( "SELECT nick FROM user WHERE id = ?", [ $onefav['user_id'] ] );
			$onehref = "http://cogdemos.ink/play/".$onenick."/".$onefav['url'];
			echo "<li><a href='".$onehref."' target='_blank'>".$onefav['title']."</a></li>";
		}
?>
</ul>
</div>
<?php } ?>


<?php
	if( !empty( $_SESSION['errormessage'] ) )
		{
			foreach( $_SESSION['errormessage'] as $message )
				{
					echo '<div class="alert alert-danger alert-dismissible fade show mt-4"><i class="fa fa-exclamation-circle"></i> '.$message.'<button type="button" class="close" data-dismiss="alert"><span >&times;</span></button></div>';
				}
			unset( $_SESSION['errormessage'] );
		}
	if( !empty( $_SESSION['noticemessage'] ) )
		{
			foreach( $_SESSION['noticemessage'] as $message )
				{
					echo '<div class="alert alert-info alert-dismissible fade show mt-4"><i class="fa fa-info-circle"></i> '.$message.'<button type="button" class="close" data-dismiss="alert"><span >&times;</span></button></div>';
				}
			unset( $_SESSION['noticemessage'] );
		}
	if( !empty( $_SESSION['successmessage'] ) )
		{
			foreach( $_SESSION['successmessage'] as $message )
				{
					echo '<div class="alert alert-success alert-dismissible fade show mt-4"><i class="fa fa-check-circle"></i> '.$message.'<button type="button" class="close" data-dismiss="alert"><span >&times;</span></button></div>';
				}
			unset( $_SESSION['successmessage'] );
		}
?>

<?php
	$thisid = $_SESSION["memberlog"];
	$thesegames = R::getAll(
			"SELECT * FROM game WHERE user_id = ?" , [ $thisid ]
		);

  foreach ( $thesegames as $game )
    {
      if($game['private']=='1')
		{ $listed = 'public'; $whatcol='0C0'; }else{ $listed = 'private'; $whatcol='C00'; }
      $thismem = R::getRow( "SELECT * FROM user WHERE id = " . $game["user_id"] );
      $authorfix = str_replace( " " , "-" , $thismem['nick'] );
      $titlefix = $game["url"];
      $urltitlefix = str_replace( "'" , "\'" , $titlefix );
      $urlauthorfix = str_replace( "'" , "\'" , $authorfix );
      if( $game["private"] == 1 )
        {
					$shorturl = '<div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text text-muted text-mono">GO&rarr; </span></div><div class="form-control text-muted text-center" style="text-decoration: line-through">https://cogdemos.ink/go/' . $game["id"] .'</div></div>';
		}
	  else
		{
					$shorturl = '<div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text text-muted text-mono">GO&rarr; </span></div><input class="form-control text-center" type="text" value="https://cogdemos.ink/go/'.$game["id"].'" name="shorturl" onclick="this.select()"></div>';
		}
      if( $game["stylesheet"] != 0 )
        {
          if($game["stylesheet"] == 1 )
            {
              $thistheme = 'DARK TECH';
              $thiscolor = '84281F';
            }
          elseif( $game["stylesheet"] == 2 )
            {
              $thistheme = '<span>TYPEWRITTEN</span>';
              $thiscolor = 'a9acaf';
            }
          elseif( $game["stylesheet"] == 3 )
            {
              $thistheme = '<span>STORY FIRST</span>';
              $thiscolor = '2b60e6';
            }
          elseif( $game["stylesheet"] == 9 )
            {
              $thistheme = '<span>CLASSIC CS</span>';
              $thiscolor = 'e6962b';
            }
        }
      else
        {
          $thistheme = 'EASY READER';
          $thiscolor = '9999999';
        }

    if( file_exists( "/var/www/play/" . $authorfix . "/" . $titlefix . "/mygame/dashpic.jpg" ) )
      {
			  $burl = "https://cogdemos.ink/play/$urlauthorfix/$urltitlefix/mygame/dashpic.jpg";
      }
    elseif( file_exists( "/var/www/play/" . $authorfix . "/" . $titlefix . "/mygame/dashpic.JPG" ) )
      {
			  $burl = "https://cogdemos.ink/play/$urlauthorfix/$urltitlefix/mygame/dashpic.JPG";
      }
    else
      {
			  //$burl = "";
	  }

    $gurl = "https://cogdemos.ink/play/" . $authorfix . "/" . $titlefix . "/mygame/";
?>
<hr>
<div class="card">
<div class="card-body">
	<div class="row">
			<?php
				if( !empty( $burl ) )
					{
						echo '<div class="col-md-3 col-sm-12"><img src="img/game-1.jpg" class="img-fluid float-left mr-4"></div>';
						echo '<div class="col-md-9 col-sm-12">';
					}
				else
					{
						echo '<div class="col-12">';
					}
			?>
			<h2><?=$game["title"];?> <?php if( $game["private"] == 1 && empty( $game["restricted"] ) ){ echo '<sup data-toggle="tooltip" title="Private Game"><i class="fa fa-user-lock fa-xs text-warning"></i></sup>'; } ?> <?php if( !empty( $game["restricted"] ) ){ echo '<sup data-toggle="tooltip" title="Restricted Game"><i class="fa fa-user-shield fa-xs text-danger"></i></sup>'; } ?></h2>
			<p>
				<?=$game["blurb"];?>
			</p>
			<p>
<div class="input-group mb-3">
	<div class="input-group-prepend">
		<span class="input-group-text text-muted text-mono">URL </span>
	</div>
	<input class="form-control text-center" type="text" value="<?=$gurl;?>" name="url" onclick="this.select()">
</div>
			</p>
			<p>
				<?=$shorturl;?>
			</p>
	</div>
		</div>


<div class="row">
<div class="col-12">
<ul class="list-group flex-md-row text-center" style="font-size:0.9em">
  <li class="list-group-item flex-fill bg-dark">
		<a href="<?=$gurl;?>" target="_blank" class="text-light"><i class="fa fa-play"></i> Playtest Game</a>
	</li>
  <li class="list-group-item flex-fill list-group-item-light">
	<i class="fa fa-bell-slash"></i> <span style="text-decoration:line-through">Announce Update</span>
			<?php
			/* ANNOUNCE UPDATES OUT FOR NOW
			if( $game["private"] != 1 )
				{
					if( $game["lastupdate"]!=$thisday || is_null( $game["lastupdate"] ) )
						{
							?>
							<a href="/announce/<?=$game['id'];?>" class="text-success"><i class="fa fa-bell"></i> Announce Update</a>
							<?php
						}
					else
						{
							?>
							<i class="fa fa-bell-slash"></i> Updated Today!
							<?php
						}
					?>
					<?php
				}
			else
				{
					?>
					<i class="fa fa-bell-slash"></i> <span style="text-decoration:line-through">Announce Update</span>
					<?php
				}
			*/
			?>
	</li>
  <li class="list-group-item flex-fill list-group-item-light">
		<a href="/edit/<?=$game['id']?>" class="text-info"><i class="fa fa-cog"></i> Edit Details</a>
	</li>
  <li class="list-group-item flex-fill list-group-item-light">
		<a href="/upload/<?=$game['id']?>" class="text-secondary"><i class="fa fa-upload"></i> Upload Files</a>
	</li>
  <li class="list-group-item flex-fill list-group-item-light">
		<a href="/devedit/<?=$game['id'];?>" class="text-primary"><i class="far fa-save"></i> DevSlot Editor</a>
	</li>
  <li class="list-group-item flex-fill bg-gray">
		<a href="/delete/<?=$game['id'];?>" class="nyroModal text-danger"><i class="fa fa-trash-alt"></i> Delete Game</a>
	</li>
</ul>
</div>
</div>

</div>

</div>
<?php $burl = ""; } ?>
<hr>
</div>
</div>
	<?php require_once( "__foot.php" ); ?>
</div>
	<?php require_once( "__code.php" ); ?>