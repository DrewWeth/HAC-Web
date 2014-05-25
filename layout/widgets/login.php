<div class="well">
	<h2>Login / Register</h2>
	<div class="inner">
		<tbody>
			<table class="table table-condensed table-striped">
				<tr>
					<form action="login.php" method="post">
					<td>
						Userame
					</td>
					<td>
						<input class="form-control input-sm" type="text" name="username">
					</td>
				</tr>
				<tr>
					<td>
						Password
					</td>
					<td>
						<input class="form-control input-sm" type="password" name="password">
					</td>
				</tr>
			</table>
		</tbody>
						<center><input type="submit" class="btn btn-primary btn-sm" value="Log in"></center>
					<?php
						/* Form file */
						Token::create();
					?>
				</form>
		<center><a href="register.php">Create Account</a></center>
		<center>Lost <a href="recovery.php?mode=username">username</a> or <a href="recovery.php?mode=password">password</a>?</center>		
	</div>
</div>