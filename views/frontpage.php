	<?php require_once( "__head.php" ); ?>
</head>
<body>
	<?php require_once( "__menu.php" ); ?>
<div class="site">
<div class="site-content">
      <div class="container" style="margin-top:120px;">

            <div class="row mb-4">
                <div class="col-md-12 text-center">
					<p class="alert alert-info mb-2"><i class="fa fa-info-circle"></i> This site primarily hosts "work-in-progress" games. The majority of games here are incomplete and unedited.</p>
					<p class="text-muted">cogdemos.ink is not associated or affiliated with Choice of Games LLC or Hosted Games LLC.<br>
					If you need support, please message dashingdon on the <u><a href="https://forum.choiceofgames.com/" target="_blank">Choice of Games forum</a></u>.</p>
                </div>
            </div>

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

<center><a href="/wip" class="btn btn-lg btn-success">Click Here for the Latest Works-in-Progress</a></center>

</div>
</div>
	<?php require_once( "__foot.php" ); ?>
</div>
	<?php require_once( "__code.php" ); ?>