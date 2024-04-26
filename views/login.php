<?php
session_start();
?>
	<div class="container p-4">
		<div class="p-4 fade show" id="profile">
			<div class="row register-form">
				<div class="col-md-4">
					<h4>Login</h4>
					<form method="POST" action="/_login">
						<input type="hidden" name="token" value="<?=$_SESSION['token'];?>" />
						<div class="form-group mt-4">
							<input type="email" name="email" class="form-control text-center" placeholder="Email Address" value="" autocomplete="email" />
						</div>
						<div class="form-group mt-4">
							<input type="password" name="pass" class="form-control text-center" placeholder="Password" value="" autocomplete="current-password" />
						</div>
						<div class="form-group text-center">
							<input type="submit" class="btn btn-success"  value="Login"/>
						</div>
					</form>
				</div>
				<div class="col-md-4">
					<h4>Register</h4>
					<form method="POST" action="/_register">
						<input type="hidden" name="token" value="<?=$_SESSION['token'];?>" />
						<div class="form-group mt-4">
							<input type="text" name="nick" class="form-control text-center" placeholder="Nickname" value="" autocomplete="nickname" />
						</div>
						<div class="form-group mt-4">
							<input type="email" name="email" class="form-control text-center" placeholder="Email Address" value="" autocomplete="email" />
						</div>
						<div class="form-group mt-4">
							<input type="password" name="pass" class="form-control text-center" placeholder="Password" value="" autocomplete="new-password" />
						</div>
						<div class="form-group text-center">
							<input type="submit" class="btn btn-outline-success"  value="Register"/>
						</div>
					</form>
				</div>
				<div class="col-md-4">
					<h4><span style="text-decoration:line-through">Forgot Password</span></h4>
					<form method="POST" action="#">
						<input type="hidden" name="token" value="<?=$_SESSION['token'];?>" />
						<div class="form-group mt-4">
							<input type="email" name="email" class="form-control text-center" placeholder="Email Address" value="" autocomplete="email" />
						</div>
						<div class="form-group text-center">
							<input type="submit" class="btn btn-info"  value="Send New Password" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
