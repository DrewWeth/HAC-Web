<form action="houses.php" method="post">
	<label>Select town</label>
	<select class="form-control" name="selected">
	<?php
	foreach ($config['towns'] as $id => $name) echo '<option value="'. $id .'">'. $name .'</option>';
	?>
	</select> 
	<?php
		/* Form file */
		Token::create();
	?>
	<input type="submit" class="btn btn-primary" value="Fetch houses">
</form>