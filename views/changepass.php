<?php
if( $_SESSION['memberlog'] == '' || $_SESSION['nick'] == '' )
	{
		header('Location: /logout');
		exit();
	}
?>
<div style="padding:10px;background:#FFF">

<div style="padding:10px;">

<h4>Change Password</h4>

<div id="response" class="alert alert-info text-center" style="font-weight: bold;display:none"></div>

<form id="changepass" name="changepass" method="POST" action="">
	<input type="hidden" name="token" value="<?=$_SESSION['token'];?>" />
	<input type="hidden" name="username" value="<?=$_SESSION['email'];?>" autocomplete="username" />
	<div class="row"><div class="col-12 text-center">
	<p><label class="input" for="oldpass">Current Password:<br><input class="form-control" type="password" value="" id="oldpass" name="oldpass" autocomplete="current-password"></label></p>
	</div></div>
	<div class="row"><div class="col-12 text-center">
	<p><label class="input" for="newpass">New Password:<br><input class="form-control" type="text" value="" id="newpass" name="newpass" autocomplete="new-password"></label></p>
	</div></div>
	<div class="row"><div class="col-12 text-center">
	<p><label class="input" for="newpass2">Confirm New Password:<br><input class="form-control" type="text" value="" id="newpass2" name="newpass2" autocomplete="new-password"></label></p>
	</div></div>
	<a href="javascript:void(0)" id="chapa" class="btn btn-sm btn-success float-right">Change Password</a>
	<div class="clearfix"></div>
</form>

</div>

</div>
<script>
$( "#chapa" ).click(function( event ) {
	event.preventDefault();
	var data = $('#changepass input').serialize(); 
	$.post('/_passchange', data, function(returnedData) {
		console.log(returnedData);
		$( "#response" ).html( returnedData );
		$( "#response" ).show();
		$( "#oldpass" ).val('');
		$( "#newpass" ).val('');
		$( "#newpass2" ).val('');
	});
});
</script>
