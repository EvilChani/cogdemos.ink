<!doctype html>
<html><head>
<title>Report Game</title>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
	<link rel="stylesheet" href="/css/bootstrap.min.css">
<style>
	body { padding: 0; margin: 0; border: 0; font: 20px Helvetica, sans-serif; color: #333; }
	.banner{ background:#333;padding:1em;margin-bottom: 5em; }
</style>
</head>
<body>
<div class="banner text-center"><a href="https://cogdemos.ink"><img src="/img/logo.png"></a></div>

<?php if( empty( $_SESSION['reported'] ) || !in_array( $game['id'], $_SESSION['reported'] ) ){ ?>

<form method="POST" action="/_report/<?=$game['id'];?>">
<input type="hidden" name="token" value="<?=$_SESSION['token'];?>" />
<div class="container" style="max-width:800px;">
	<h1><i class="fa fa-angry text-danger"></i> Reporting &ldquo;<?=$game['title'];?>&rdquo;</h1>
	<div class="row my-4 p-2">
		<div class="col-12">
			<label for="reason"><i class="fa fa-exclamation-circle"></i> Reason</label>
			<select class="form-control" name="reason" id="reason" required >
			<option value="1">ChoiceScript Terms Violation</option>
			<option value="2">Untagged Adult Content</option>
			<option value="3">DMCA Violation</option>
			<option value="4">Copyright Infringement</option>
			<option value="5">Spam / Advertisement</option>
			<option value="6">Other</option>
			</select>
		</div>
	</div>
	<div class="row my-4 p-2">
		<div class="col-12">
			<label for="reason"><i class="fa fa-question-circle"></i> Additional Information</label>
			<textarea class="form-control" name="info" id="info" ></textarea>
		</div>
	</div>
	<div class="row my-4 p-2">
		<div class="col-12">
			<button type="submit" class="btn btn-lg btn-block btn-outline-danger"><i class="far fa-file-alt"></i> Submit Report</button>
		</div>
	</div>
</div>
</form>
<script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>
$( "#reason" ).change(function() {
	if( $( "#reason" ).val() == "6" )
		{ $('#info').prop('required', true); }
	else
		{ $('#info').prop('required', false); }
});
</script>

<?php }else{ ?>

<div class="container" style="max-width:800px;">
	<h1 class="text-center"><i class="fa fa-check text-success"></i> You have reported &ldquo;<?=$game['title'];?>&rdquo;</h1>
</div>


<?php } ?>

</body></html>