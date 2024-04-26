<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
	<a href="/"><img src="/img/logo.png" class="logo"></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto pl-4">

			<?php if( !empty( $_SESSION['memberlog'] ) ){ ?>
			<li class="nav-item">
				<a class="nav-link waves-effect waves-light" href="/dashboard">Dashboard</a>
			</li>
			<?php } ?>

			<li class="nav-item">
				<a class="nav-link waves-effect waves-light" href="/wip/">Works-in-Progress</a>
			</li>
			<?php /*
			<li class="nav-item">
				<a class="nav-link waves-effect waves-light" href="/demo/">Demos</a>
			</li>
			<li class="nav-item">
				<a class="nav-link waves-effect waves-light" href="/complete/">Complete</a>
			</li>
			*/ ?>


<?php /*
	<li class="nav-item dropdown megamenu-li">
		<a class="nav-link dropdown-toggle" href="" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Categories</a>
		<div class="dropdown-menu megamenu" aria-labelledby="dropdown01">
		<div class="row">
			<div class="col-6 col-lg-3">
			<a class="dropdown-item" href="#">Another action</a>
			<a class="dropdown-item" href="#">Something else here</a>
			<a class="dropdown-item" href="#">Another action</a>
			<a class="dropdown-item" href="#">Something else here</a>
			</div>
			<div class="col-6 col-lg-3">
			<a class="dropdown-item" href="#">Another action</a>
			<a class="dropdown-item" href="#">Something else here</a>
			<a class="dropdown-item" href="#">Another action</a>
			<a class="dropdown-item" href="#">Something else here</a>
			</div>
			<div class="col-6 col-lg-3">
			<a class="dropdown-item" href="#">Another action</a>
			<a class="dropdown-item" href="#">Something else here</a>
			<a class="dropdown-item" href="#">Another action</a>
			<a class="dropdown-item" href="#">Something else here</a>
			</div>
			<div class="col-6 col-lg-3">
			<a class="dropdown-item" href="#">Another action</a>
			<a class="dropdown-item" href="#">Something else here</a>
			<a class="dropdown-item" href="#">Another action</a>
			<a class="dropdown-item" href="#">Something else here</a>
			</div>
		</div>
		</div>
	</li>
*/ ?>

			<?php if( empty( $_SESSION['memberlog'] ) ){ ?>
			<li class="nav-item">
				<a class="nav-link waves-effect waves-light nyroModal" href="/views/login.php">Login/Signup</a>
			</li>
			<?php } ?>

		</ul>
		<form class="form-inline" method="POST" action="/search">
			<div class="md-form my-0">
				<input class="form-control mr-4" type="text" name="search" placeholder="Search" aria-label="Search" maxlength="14">
			</div>
		</form>
	</div>
</nav>