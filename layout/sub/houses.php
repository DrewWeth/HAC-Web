	<form action="houses.php" method="post">
			<label>Select Town</label>
	<tbody>
		<table>
			<tr>
				<td>
					<select class="form-control" name="selected">
					<?php
					foreach ($config['towns'] as $id => $name) echo '<option value="'. $id .'">'. $name .'</option>';
					?>
					</select> 
					<?php
						/* Form file */
						Token::create();
					?>
				</td>
				<td style="width: 60%">
					<input type="submit" class="btn btn-primary" value="Fetch houses">
				</td>
			</tr>
		</table>
	</tbody>
	</form>
