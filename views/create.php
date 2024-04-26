	<?php require_once( "__head.php" ); ?>
</head>
<body>
	<?php require_once( "__menu.php" ); ?>
<div class="site">
<div class="site-content">
      <div class="container" style="margin-top:120px;">
<header>
  <h1>Create New Game</h1>
</header>

<fieldset id="filtergames">
	<a class="btn btn-dark" href="/dashboard" onclick="grayOut();">Return to Dashboard</a>
	<a class="btn btn-danger" href="logout.php" onclick="grayOut();">Logout</a>
</fieldset>

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

<form method="POST" id="creater" name="creater" action="/_create">
	<input type="hidden" name="token" value="<?=$_SESSION['token'];?>" />
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col col-md-6 col-sm-12">
					<section class="mb-4 p-2">
						<h4>Title:</h4>
						<input class="form-control" id="title" name="title" value="">
						<div class="note text-muted mt-2">The title must be unique to your account. You cannot have two games with the exact same title.</div>
					</section>
					<hr>
					<section class="mb-4 p-2">
						<h4>Short Description</h4>
						<textarea class="form-control" style="width:100%" placeholder="Short Description of Your Game" id="blurb" name="blurb" maxlength="250" rows="4"></textarea>
						<div class="note text-muted mt-2">Max length of 250 characters. Public games without a description will not appear in general game listings but will appear in searches.</div>
					</section>
					<hr>
					<section class="mb-4 p-2">
						<h4>Feedback URL/Email:</h4>
						<input class="form-control" id="feedbackurl" name="feedbackurl" placeholder="ex: https://forum.choiceofgames.com/t/my-awesome-game/1234/123" value="">
						<div class="note text-muted mt-2">This is an optional link others can click to view more details about or discuss your game. You may also use an email address such as <i>author@myemailaddress.com</i> instead of a URL if you wish.</div>
					</section>
				</div>
				<div class="col col-md-6 col-sm-12">
					<section class="mb-4 p-2">
						<h4>Allow Saving</h4>
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input" id="saveallowed" name="saveallowed">
							<label class="custom-control-label" for="saveallowed"><strong>Check to allow save slots</strong></label>
						</div>
						<div class="note text-muted mt-2">This will allow readers to save the story to the server at any point.</div>
					</section>
					<hr>
					<section class="mb-4 p-2">
						<h4>Public Access</h4>
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input" id="private" name="private" checked=checked>
							<label class="custom-control-label" for="private"><strong>Check to keep game private</strong></label>
						</div>
						<div class="note text-muted mt-2">Leave this option checked if you wish to keep your game private &ndash; only those with the URL will be able to access it. Private games do not show up in searches.</div>
					</section>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 text-right">
			<button type="submit" class="btn btn-lg btn-success">Create Game</button>
		</div>
	</div>
</form>

</div>

</div>
<hr>
</div>
</div>
	<?php require_once( "__foot.php" ); ?>
</div>
	<?php require_once( "__code.php" ); ?>
