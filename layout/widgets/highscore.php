<div class="sidebar">
	<h2>Search highscores</h2>
	<div class="inner">
		<tbody>
			<table class="table table-striped">
				<tr>
					<td class="center">

						<form action="highscores.php" method="post">
							<select name="selected" class="form-control">
							<option value="7">Experience</option>
							<option value="5">Shielding</option>
							<option value="3">Axe</option>
							<option value="2">Sword</option>
							<option value="1">Club</option>
							<option value="4">Distance</option>
							<option value="0">Fist</option>
							<option value="6">Fish</option>
							<option value="8">Magic</option>
							</select> 
							<?php
								/* Form file */
								Token::create();
							?>
						</td>
					</tr>
					<tr>
						<td class="center">
							<input type="submit" class="btn btn-primary" value="Fetch scoreboard">
						</form>
					</td>
				</tr>
			</table>
		</tbody>
	</div>
</div>