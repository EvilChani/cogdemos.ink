<?php
$thisid = $_SESSION['memberlog'];
$userinfo = R::load('user', $thisid);

if($userinfo->createkey == ''){
	$userinfo->createkey = strtoupper(uniqidReal(8).'-'.uniqidReal(4).'-'.uniqidReal(8));
	R::store($userinfo);
}
?>
<div style="padding:10px;background:#FFF">

<div style="padding:10px;">

<h4>Creation API Key</h4>

<p>This key can be used by external applications to create games on your account.</p>

<input class="form-control text-center" type="text" value="<?=$userinfo->createkey;?>" id="createkey" name="createkey" onclick="this.select()" readonly=readonly>
<a href="#" id="rcreate" class="btn btn-sm btn-danger float-right">Revoke &amp; Replace Key</a>

<div class="clearfix"></div>

<a href="https://cogdemos.ink/easy/documentation/" target="_blank"><i class="fa fa-external-link-alt"></i> EasyAPI Documentation</a>

</div>

</div>

<script>
$( "#rcreate" ).click(function( event ) {
	event.preventDefault();
	var data = 'a=createkey';
	$.post('/_keycreate', data, function(returnedData) {
		$( "#createkey" ).val(returnedData);
	});
});
</script>