	<?php require_once( "__head.php" ); ?>
</head>
<body>
	<?php require_once( "__menu.php" ); ?>
<div class="site">
<div class="site-content">
      <div class="container" style="margin-top:120px;">


<?php if( $n_page == 1 ){ ?>
            <div class="row mb-4">
                <div class="col-md-12 text-center">
<p class="alert alert-info mb-2"><i class="fa fa-info-circle"></i> This site primarily hosts "work-in-progress" games. The majority of games here are incomplete and unedited.</p>
<p class="text-muted">cogdemos.ink is not associated or affiliated with Choice of Games LLC or Hosted Games LLC.<br>
If you need support, please message dashingdon on the <u><a href="https://forum.choiceofgames.com/" target="_blank">Choice of Games forum</a></u>.</p>
                </div>
            </div>
<?php } ?>


<?php
foreach ( $games_public as $game_alone )
	{
		$basura = R::load( "user" , $game_alone["user_id"] );
		$member = $basura->export();
		$fix_author = str_replace( " " , "-" , $member["nick"] );
		$fix_title = $game_alone["url"];
		$games_id = $game_alone["id"];
		$url_title = str_replace( "'" , "\'" , $fix_title );
		$url_author = str_replace( "'" , "\'" , $fix_author );
		$breaktime = explode( "-", $game_alone['lastupdate'] );
		if( file_exists( "/play/" . $fix_author . "/" . $fix_title . "/mygame/dashpic.jpg" ) )
			{ $url_picture = "/play/". $url_author . "/" . $url_title . "/mygame/dashpic.jpg"; }
		elseif( file_exists( "/play/" . $fix_author . "/" . $fix_title . "/mygame/dashpic.JPG" ) )
			{ $url_picture = "/play/" . $url_author . "/" . $url_title . "/mygame/dashpic.JPG"; }
		else
			{ $url_picture = FALSE; }
		$url_picture = "<img src='{$url_picture}'>";
		$url_game = "/play/" . $fix_author . "/" .$fix_title . "/";
		if( $game_alone["blurb"] != substr( $game_alone["blurb"] , 0 , 300 ) )
			{
				$game_alone["blurb"] = substr( $game_alone["blurb"] , 0 , 300 )."&hellip;";
			}
		?>
		<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<h2 class="card-title mb-0">
						<a href="<?=Flight::get( "folder" );?>go/<?=$game_alone["id"];?>" target="_blank"><?=$game_alone["title"];?></a>
					</h2>
					<p class="card-category text-muted pl-2">&mdash; <?=$member["nick"];?></p>

					<div class="row">
						<div class="col-md-8">
							<div class="pl-4">
							<p class="blurb"><?=$game_alone["blurb"];?></p>
							<div class="statrow">
								<?php if( !empty( $game_alone['lastupdate'] ) ){ ?>
								<i class="far fa-calendar-check"></i> <?=date( "M", mktime(0, 0, 0, $breaktime[0], 10) );?> <?=$breaktime[1];?>, 20<?=$breaktime[2];?>
									&nbsp;&nbsp;&nbsp;
								<?php } ?>
								<?php if( $game_alone['words'] > 0 ){ ?>
								<i class="far fa-file-alt"></i> <?=number_format( $game_alone['words'] );?> Words
									&nbsp;&nbsp;&nbsp;
								<?php } ?>
								<?php if( $game_alone['views'] > 0 ){ ?>
								<i class="fa fa-desktop"></i> <?=$game_alone['views'];?> Views
								<?php } ?>
							</div>
							</div>
						</div>
						<div class="col-md-4">
							<?=$url_picture;?>
						</div>
					</div>

				</div>
			</div>
		</div>
		</div>
		<hr>
		<?php
	}
?>

<?=$pagination;?>

</div>
</div>
	<?php require_once( "__foot.php" ); ?>
</div>
	<?php require_once( "__code.php" ); ?>