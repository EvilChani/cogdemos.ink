</head>
<body class="fixed-header">

<?php
	require_once( "__menu.php" );
?>

  <section class="p-y-80">
    <div class="container">
      <div class="heading">
        <i class="fa fa-gamepad"></i>
        <h2>Recently Updated Games</h2>
      </div>
      <div class="row">






<?php
foreach ( $games_public as $game_alone )
	{
		$basura = R::load( "user" , $game_alone[ "user_id" ] );
		$member = $basura->export();
		$fix_author = str_replace( " " , "-" , $member[ "nick" ] );
		$fix_title = $game_alone[ "url" ];
		$games_id = $game_alone[ "id" ];
		$url_title = str_replace( "'" , "\'" , $fix_title );
		$url_author = str_replace( "'" , "\'" , $fix_author );
		if(
				file_exists(
						"/var/www/play/" . $fix_author . "/" .
						$fix_title . "/mygame/dashpic.jpg"
					)
				)
			{
				$url_picture =
					"https://cogdemos.ink/play/". $url_author.
					"/" . $url_title . "/mygame/dashpic.jpg";
			}
		elseif(
				file_exists(
						"/var/www/html/play/" . $fix_author . "/" .
						$fix_title . "/mygame/dashpic.JPG"
					)
				)
			{
				$url_picture =
					"https://cogdemos.ink/play/" . $url_author .
					"/" . $url_title . "/mygame/dashpic.JPG";
			}
		else
			{
				$url_picture = "img/game/game-1.jpg";
			}
		if( $game_alone[ "usecompiled" ] == 1 )
			{
				if(
						file_exists(
								"/var/www/html/play/" . $fix_author . "/" .
								$fix_title ."/mygame/" . $fix_title . "_compiled.html"
							)
						)
					{
						$url_game =
							"https://cogdemos.ink/play/" . $fix_author . "/"
							. $fix_title . "/mygame/" . $fix_title . "_compiled.html";
					}
				else
					{
						$url_game =
							"https://cogdemos.ink/play/" . $fix_author .
							"/" . $fix_title . "/";
					}
			}
		else
			{
				$url_game =
					"https://cogdemos.ink/play/" . $fix_author .
					"/" .$fix_title . "/";
			}
		if( $game_alone[ "blurb" ] != substr( $game_alone[ "blurb" ] , 0 , 300 ) )
			{
				$game_alone[ "blurb" ] = substr( $game_alone[ "blurb" ] , 0 , 300 ) . "&hellip;";
			}
		?>

        <div class="col-12 col-6 col-md-4 mb-4">
          <div class="card card-lg" style="height:100%;">
            <div class="card-img">
              <a href="/silo/game/<?=$game_alone[ "id "];?>">
								<img src="<?=$url_picture;?>" class="card-img-top img-fluid" alt="<?=$game_alone[ "title" ];?>">
							</a>
              <div class="badge badge-primary badge-update">June 13, 2017</div>
            </div>
<a class="btn btn-sm btn-playnow btn-block btn-effect" href="<?=$url_game;?>" target="_blank">PLAY NOW</a>
            <div class="card-block">
              <h4 class="card-title">
								<a href="/silo/game/<?=$game_alone[ "id "];?>">
									<?=$game_alone[ "title" ];?>
								</a>
							</h4>
              <div class="card-meta"><i class="fa fa-address-card"></i> <strong><?=$member[ "nick" ];?></strong></div>
              <p class="card-text"><?=$game_alone[ "blurb" ];?></p>

              <div class="card-likes btn btn-outline btn-xs">
                <a href="#">15</a>
              </div>

							<div class="card-bottext text-muted">
							<b>14,242</b> Words &bull; <b>188</b> Views
							</div>

            </div>

          </div>
        </div>
		<?php
	}
?>










      </div>
      <div class="text-center"><a class="btn btn-primary btn-shadow btn-rounded btn-effect btn-lg m-t-10" href="/search">Search All Games</a></div>
    </div>
  </section>

  <section class="bg-secondary no-border-bottom p-y-80">
    <div class="container">
      <div class="heading">
        <i class="fa fa-star"></i>
        <h2>Recent Reviews</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      </div>
      <div class="owl-carousel owl-list">
        <div class="card card-review">
          <a class="card-img" href="review-post.html">
          <img src="img/review/review-1.jpg" alt="">
          <div class="badge badge-success">7.2</div>
        </a>
          <div class="card-block">
            <h4 class="card-title"><a href="review-post.html">Uncharted 4</a></h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
          </div>
        </div>
        <div class="card card-review">
          <a class="card-img" href="review-post.html">
          <img src="img/review/review-2.jpg" alt="">
          <div class="badge badge-warning">5.4</div>
        </a>
          <div class="card-block">
            <h4 class="card-title"><a href="review-post.html">Hitman: Absolution</a></h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
          </div>
        </div>
        <div class="card card-review">
          <a class="card-img" href="review-post.html">
          <img src="img/review/review-3.jpg" alt="">
          <div class="badge badge-danger">2.1</div>
        </a>
          <div class="card-block">
            <h4 class="card-title"><a href="review-post.html">Last of us: Remastered</a></h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
          </div>
        </div>
        <div class="card card-review">
          <a class="card-img" href="review-post.html">
          <img src="img/review/review-4.jpg" alt="">
          <div class="badge badge-success">7.8</div>
        </a>
          <div class="card-block">
            <h4 class="card-title"><a href="review-post.html">Bioshock: Infinite</a></h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
          </div>
        </div>
        <div class="card card-review">
          <a class="card-img" href="review-post.html">
          <img src="img/review/review-5.jpg" alt="">
          <div class="badge badge-success">8.9</div>
        </a>
          <div class="card-block">
            <h4 class="card-title"><a href="review-post.html">Grand Theft Auto: 5</a></h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
          </div>
        </div>
        <div class="card card-review">
          <a class="card-img" href="review-post.html">
          <img src="img/review/review-6.jpg" alt="">
          <div class="badge badge-warning">4.7</div>
        </a>
          <div class="card-block">
            <h4 class="card-title"><a href="review-post.html">Dayz</a></h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
          </div>
        </div>
        <div class="card card-review">
          <a class="card-img" href="review-post.html">
          <img src="img/review/review-7.jpg" alt="">
          <div class="badge badge-danger">3.1</div>
        </a>
          <div class="card-block">
            <h4 class="card-title">
              <a href="review-post.html">Liberty City</a>
            </h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-image" style="background-image: url(https://img.youtube.com/vi/IsDX_LiJT7E/maxresdefault.jpg);">
    <div class="overlay"></div>
    <div class="container">
      <div class="video-play" data-src="https://www.youtube.com/embed/IsDX_LiJT7E?rel=0&amp;amp;autoplay=1&amp;amp;showinfo=0">
        <div class="embed-responsive embed-responsive-16by9">
          <img class="embed-responsive-item" src="https://img.youtube.com/vi/IsDX_LiJT7E/maxresdefault.jpg" alt="">
          <div class="video-play-icon">
            <i class="fa fa-play"></i>
          </div>
        </div>
      </div>
    </div>
  </section>
