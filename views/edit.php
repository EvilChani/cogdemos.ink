	<?php require_once( "__head.php" ); ?>
</head>
<body>
	<?php require_once( "__menu.php" ); ?>
<div class="site">
<div class="site-content">
      <div class="container" style="margin-top:120px;">
<header>
  <h1>Editing &ldquo;<?=$game['title'];?>&rdquo;</h1>
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

<form method="POST" id="editer" name="editer" action="/_edit/<?=$game['id'];?>">
<input type="hidden" name="token" value="<?=$_SESSION['token'];?>" />
<div class="card">
<div class="card-body">


<div class="row">
<div class="col col-md-6 col-sm-12">

<section class="mb-4 p-2">
<h4>Short Description</h4>
<textarea class="form-control" style="width:100%" placeholder="Short Description of Your Game" id="blurb" name="blurb" maxlength="250" rows="4"><?=$game['blurb'];?></textarea>
<div class="note text-muted mt-2">Public games without a description will not appear in general game listings but will appear in searches.</div>
</section>

<hr>

<section class="mb-4 p-2">
<h4>Feedback URL:</h4>
<input class="form-control" id="feedbackurl" name="feedbackurl" placeholder="ex: https://forum.choiceofgames.com/t/my-awesome-game/1234/123" value="<?=$game['feedbackurl'];?>">
<div class="note text-muted mt-2">This is an optional link others can click to view more details about or discuss your game. You may also use an email address such as <i>author@myemailaddress.com</i> instead of a URL if you wish.</div>
</section>

<hr>

<section class="mb-4 p-2">
<h4>Game API Key</h4>
<p class="mb-0">Game ID: <b style="font-family:monospace"><?=$game['id'];?></b></p>
<input class="form-control text-center" type="text" value="<?=$game['apikey'];?>" id="apikey" name="apikey" onclick="this.select()" readonly=readonly>
<a href="#" id="rcreate" class="btn btn-sm btn-danger float-right">Revoke &amp; Replace Key</a>
<div class="note text-muted mt-2">This key can be used by external applications to make changes to this game.</div>
</section>

<?php /* THEMES REMOVED FOR NOW
<hr>

<section class="mb-4 p-2">
<div class="float-right"><a href="/_themes.php" class="nyroModal">Theme Examples</a></div>
<h4>Theme</h4>
<select class="form-control mb-1" name="stylesheet" id="stylesheet">
<option value="0" <?php if($game['stylesheet']==0){ echo 'selected=selected'; }?>>Classic CS</option>
<option value="5" <?php if($game['stylesheet']==5){ echo 'selected=selected'; }?>>Easy Reader</option>
<option value="3" <?php if($game['stylesheet']==3){ echo 'selected=selected'; }?>>Story First</option>
<option value="4" <?php if($game['stylesheet']==4){ echo 'selected=selected'; }?>>Nice Space</option>
<option value="2" <?php if($game['stylesheet']==2){ echo 'selected=selected'; }?>>Typewritten</option>
<option value="1" <?php if($game['stylesheet']==1){ echo 'selected=selected'; }?>>Dark Tech</option>

</select>
<div class="note text-muted mt-2">This option is only valid for 'scenes' hosted games, not compiled HTML games.</div>
</section>
*/ ?>

</div>

<div class="col col-md-6 col-sm-12">

<section class="mb-4 p-2">
<h4>Allow Saving</h4>
<div class="custom-control custom-checkbox">
	<input type="checkbox" class="custom-control-input" id="saveallowed" name="saveallowed" <?=$checkbox['saveallowed'];?>>
	<label class="custom-control-label" for="saveallowed"><strong>Check to allow save slots</strong></label>
</div>
<div class="note text-muted mt-2">This will allow readers to save the story at any point so that they can return to that point by loading the save data.</div>
</section>

<hr>

<section class="mb-4 p-2">
<h4>Public Access</h4>
<div class="custom-control custom-checkbox">
	<input type="checkbox" class="custom-control-input" id="private" name="private" <?=$checkbox['private'];?>>
	<label class="custom-control-label" for="private"><strong>Check to keep game private</strong></label>
</div>
<div class="note text-muted mt-2">Leave this option checked if you wish to keep your game private &ndash; only those with the URL will be able to access it.<br><i class="fa fa-info-circle"></i> Restricting your game overrides this option.</div>
</section>

<hr>

<section class="mb-4 p-2" style="background-color:#ffffe8;">
<h4><i class="fa fa-lock"></i> Restricted Access</h4>
<div class="input-group">
  <input class="form-control" id="allowaccess" name="allowaccess" placeholder="ex: dashingdon,bobbytables,piratesteve">
  <div class="input-group-append">
    <button id="accessfunction" type="button" class="btn btn-primary mt-0" type="button">Allow Access</button>
  </div>
</div>
<br>
<?php if( !empty( $allowed ) ) {
	foreach( $allowed as $user )
		{
			?>
				<span class="badge badge-pill badge-dark p-2 m-1"><i class="fa fa-unlock fa-lg text-success"></i> <?=$user['nick'];?> <a href="/removeaccess/<?=$game['id'];?>/<?=$user['id'];?>"><i class="fa fa-times text-danger" data-toggle="tooltip" title="Remove access for <?=$user['nick'];?>"></i></a></span>
			<?php
		}
} ?>
<div class="note text-danger mt-2"><i class="fa fa-exclamation-triangle"></i> <span class="text-muted">Adding any members here automatically locks down your game so that only the pre-approved logged-in members can access the game.</span></div>
</section>


</div>
</div>







</div>
</div>


<div class="row">
<div class="col-12 text-right">
<button type="submit" class="btn btn-lg btn-success">Submit Edit</button>
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

<script>
$( "#accessfunction" ).click(function( event ) {
	event.preventDefault();
	var data = $('#allowaccess').serialize(); 
	$.post('/_addaccess/<?=$game['id'];?>', data, function( returnedData )
		{
			$( "#allowaccess" ).val('');
			window.location.reload(true); 
		});
});
$( "#rcreate" ).click(function( event ) {
	event.preventDefault();
	var data = 'a=gamekey';
	$.post('/_keygame/<?=$game['id'];?>', data, function(returnedData) {
		$( "#apikey" ).val(returnedData);
	});
});
</script>