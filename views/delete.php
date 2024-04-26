<div style="padding:10px;background:#FFF">

	<div style="padding:10px;">

		<h4 class="text-danger">Delete Game</h4>

		<div id="response" class="alert alert-info text-center" style="font-weight: bold;display:none"></div>

		<form id="delete" name="delete" method="POST" action="/deletegame/<?=$game['id'];?>">
			<input type="hidden" name="token" value="<?=$_SESSION['token'];?>" />
			<input type="hidden" name="username" value="<?=$_SESSION['email'];?>" />
			<input type="hidden" name="gameid" value="<?=$game['id'];?>" />
			<p class="alert alert-danger">Are you sure you want to delete the game <strong><?=$game['title'];?></strong>?</p>
			<p></p>
			<div class="text-center">
				<a href="javascript:void(0);$.nmTop().close();" class="btn btn-outline-dark"><i class="fa fa-ban"></i> No, Cancel Deletion</a>
				<button type="submit" class="btn btn-danger"><i class="fa fa-trash-alt"></i> Yes, Confirm Deletion</button>
			</div>
		</form>

	</div>

</div>
