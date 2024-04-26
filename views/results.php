	<?php require_once( "__head.php" ); ?>
</head>
<body>
	<?php require_once( "__menu.php" ); ?>
<div class="site">
<div class="site-content">
      <div class="container" style="margin-top:120px;">

<h1><?=$n_games;?> Search Results for &ldquo;<?=$searchterm;?>&rdquo;</h1>

<?php
foreach ( $games as $game_alone )
	{
		$basura = R::load( "user" , $game_alone["user_id"] );
		$member = $basura->export();
		$fix_author = str_replace( " " , "-" , $member["nick"] );
		$fix_title = $game_alone["url"];
		$games_id = $game_alone["id"];
		$url_title = str_replace( "'" , "\'" , $fix_title );
		$url_author = str_replace( "'" , "\'" , $fix_author );
		$breaktime = explode( "-", $game_alone['lastupdate'] );
		$private = $game_alone["private"];
		if( file_exists( "F:/xampp/htdocs/play/" . $fix_author . "/" . $fix_title . "/mygame/dashpic.jpg" ) )
			{ $url_picture = "https://cogdemos.ink/play/". $url_author . "/" . $url_title . "/mygame/dashpic.jpg"; }
		elseif( file_exists( "F:/xampp/htdocs/play/" . $fix_author . "/" . $fix_title . "/mygame/dashpic.JPG" ) )
			{ $url_picture = "https://cogdemos.ink/play/" . $url_author . "/" . $url_title . "/mygame/dashpic.JPG"; }
		else
			{ $url_picture = FALSE; }
		$url_picture = "<img src='{$url_picture}'>";
		if( $game_alone["usecompiled"] == 1 )
			{
				if( file_exists( "/play/" . $fix_author . "/" . $fix_title ."/mygame/" . $fix_title . "_compiled.html" ) )
					{
						$url_game = "https://cogdemos.ink/play/" . $fix_author . "/" . $fix_title . "/mygame/" . $fix_title . "_compiled.html";
					}
				else
					{ $url_game = "https://cogdemos.ink/play/" . $fix_author . "/" . $fix_title . "/"; }
			}
		else
			{ $url_game = "https://cogdemos.ink/play/" . $fix_author . "/" .$fix_title . "/"; }
		if( $game_alone["blurb"] != substr( $game_alone["blurb"] , 0 , 300 ) )
			{
				$game_alone["blurb"] = substr( $game_alone["blurb"] , 0 , 300 ) .
					"<a href='/play/" . $game_alone["id"] . "'>&hellip;</a>";
			}
		?>
		<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<h2 class="card-title mb-0">
						<a href="/go/<?=$game_alone["id"];?>"><?=$game_alone["title"];?></a>
					</h2>
					<p class="card-category text-muted pl-2">&mdash; <?=$member["nick"];?></p>

					<div class="row">
						<div class="col-md-8">
							<div class="pl-4">
							<p class="blurb"><?=$game_alone["blurb"];?></p>
							<div class="statrow">
								<?php if( !empty( $game_alone['lastupdate'] ) ){ ?>
								<i class="far fa-calendar-check"></i> <?=date( "M", mktime(0, 0, 0, $breaktime[1], 10) );?> <?=$breaktime[2];?>, 20<?=$breaktime[0];?>
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

</div>
</div>
	<?php require_once( "__foot.php" ); ?>
</div>
	<?php require_once( "__code.php" ); ?>