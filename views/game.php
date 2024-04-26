    <div class="main-panel">
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" href="#">Work-in-Progress Games</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <form>
              <div class="input-group no-border">
                <input type="text" value="" class="form-control" placeholder="Search...">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <i class="nc-icon nc-zoom-split"></i>
                  </div>
                </div>
              </div>
            </form>
            <ul class="navbar-nav">
              <li class="nav-item btn-rotate dropdown">
                <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="nc-icon nc-bell-55"></i>
                  <p>
                    <span class="d-lg-none d-md-block">Some Actions</span>
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="#">Action</a>
                  <a class="dropdown-item" href="#">Another action</a>
                  <a class="dropdown-item" href="#">Something else here</a>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link btn-rotate" href="#pablo">
                  <i class="nc-icon nc-key-25"></i>
                  <p>
                    <span class="d-lg-none d-md-block">Account</span>
                  </p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="content">



    <div class="container">
      <div class="hero-block">
        <div class="hero-left">
          <h2 class="hero-title"><?=$game[ "title" ];?></h2>
					<p>by <?=$nick;?></p>

          <a class="btn btn-outline-success btn-shadow btn-rounded btn-lg btn-block btn-effect" href="https://cogdemos.ink/go/<?=$game[ "id" ];?>" target="_blank" role="button">Play Demo</a>

					<hr>

          <a class="btn btn-primary btn-shadow btn-rounded btn-lg mt-0" href="" data-lightbox role="button"><i class="fa fa-shopping-cart"></i> Purchase Now</a>

          <a class="btn btn-info btn-shadow btn-rounded btn-lg mt-0 m-l-10" href="" data-lightbox role="button"><i class="fa fa-comments-o"></i> Join Discussion</a>

        </div>
        <div class="hero-right">
					<img src="/silo/img/game/game-1.jpg" class="img-fluid" alt="">
        </div>
      </div>
    </div>



<div class="progress progress-lg progress-loaded m-0" style="border-radius:0;background-color:#030;position:relative;height:16px;">
<div style="color:#3A3;letter-spacing:1px;font-size:10px;position:absolute;bottom:0;left:40%;padding-left:10px;">40% COMPLETE</div>
<div class="progress-bar progress-bar-success progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
</div>
</div>
</section>

  <section class="toolbar toolbar-links" data-fixed="true">
    <div class="container">
      <h5><i class="fa fa-heart reddy"></i>34</h5>
      <ul class="toolbar-nav m-r-25">
        <li class="report"><a href="/report/<?=$game[ "id" ];?>"><i class="fa fa-flag"></i> Report</a></li>
      </ul>
    </div>



    <div class="container">
      <div class="row">
        <div class="col-lg-8">
					<h2><?=$game[ "title" ];?></h2>
          <p style="font-size:18px;"><?=$game[ "blurb" ];?></p>
        </div>
        <div class="col-lg-4">

          <div class="">

            <div class="widget widget-game">
              <div class="widget-block" style="background-image: url( '/silo/img/widget-bg.jpg' )">
                <div class="overlay"></div>
                <div class="widget-item">

                  <h5 class="mt-0">Category</h5>
									<div class="mt-2">
                  <a href="/cat/wip"><span class="badge badge-secondary mb-2 btn-block">Work-in-Progress</span></a>
									</div>

                  <h5>Meta</h5>
									<table class="meta ml-2">
										<tr><td class="text-muted">Release Date</td><td class="text-dark"> &hellip; </td><td class="text-bold">May 18, 2015</td></tr>
										<tr><td class="text-muted">Last Update</td><td class="text-dark"> &hellip; </td><td class="text-bold"><?=$game[ "lastupdate" ];?></td></tr>
										<tr><td class="text-muted">Word Count</td><td class="text-dark"> &hellip; </td><td class="text-bold">14,224</td></tr>
										<tr><td class="text-muted">Unique Views</td><td class="text-dark"> &hellip; </td><td class="text-bold">16</td></tr>
									</table>

                  <h5>Type</h5>
									<div class="ml-2">
                  <a href="/type/adventure"><span class="badge badge-secondary mb-2 btn-block">Adventure</span></a>
                  <a href="/type/comedy"><span class="badge badge-dark mb-2">Comedy</span></a>
                  <a href="/type/fantasy"><span class="badge badge-dark mb-2">Fantasy</span></a>
                  <a href="/type/drama"><span class="badge badge-dark mb-2">Drama</span></a>
                  <a href="/type/romance"><span class="badge badge-dark mb-2">Romance</span></a>
									</div>

                  <h5>Tags</h5>
                  <ul id="tags" class="ml-2">
										<li><a href="/tag/fantasy">#fantasy</a></li>
                    <li><a href="/tag/nonbinary-inclusive">#nonbinary-inclusive</a></li>
                    <li><a href="/tag/nonbinary-romance">#nonbinary-romance</a></li>
                  </ul>

                </div>
              </div>
            </div>



          </div>
        </div>
      </div>
    </div>




</div>

