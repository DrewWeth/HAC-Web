<div class="sidebar">
	<h2>Login / Register</h2>
	<div class="inner">
		<tbody>
			<table class="table table-striped">
				<tr>
					<form action="login.php" method="post">
					<ul id="login">
					<td>
						Userame
					</td>
					<td>
						<input type="text" name="username">
					</td>
				</tr>
				<tr>
					<td>
						Password
					</td>
					<td>
						<input type="password" name="password">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<center><input type="submit" value="Log in"></center>
					</td>
					<?php
						/* Form file */
						Token::create();
					?>
				</tr>
				</form>
				<tr>
					<td colspan="2">
						<center><a href="register.php">Create Account</a></center>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<center>Lost <a href="recovery.php?mode=username">username</a> or <a href="recovery.php?mode=password">password</a>?></center>
					</td>
				</tr>
			</table>
		</tbody>
	</div>
</div>