<?php require_once 'engine/init.php';
protect_page();
include 'layout/overall/header.php'; 

if (empty($_POST) === false) {
	// $_POST['']
	$required_fields = array('name');
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = 'You need to fill in all fields.';
			break 1;
		}
	}
	
	// check errors (= user exist, pass long enough
	if (empty($errors) === true) {
		if (!Token::isValid($_POST['token'])) {
			$errors[] = 'Token is invalid.';
		}
		$_POST['name'] = validate_name($_POST['name']);
		if ($_POST['name'] === false) {
			$errors[] = 'Your name can not contain more than 2 words.';
		} else {
			if (user_character_exist($_POST['name']) === true) {
				$errors[] = 'Sorry, that character name already exist.';
			}
			if (!preg_match("/^[a-zA-Z_ ']+$/", $_POST['name'])) {
				$errors[] = 'Your name may only contain a-z, A-Z and spaces.';
			}
			if (strlen($_POST['name']) < $config['minL'] || strlen($_POST['name']) > $config['maxL']) {
				$errors[] = 'Your character name must be between 4 - 20 characters long.';
			}
			// name restriction
			$resname = explode(" ", $_POST['name']);
			foreach($resname as $res) {
				if(in_array(strtolower($res), $config['invalidNameTags'])) {
					$errors[] = 'Your username contains a restricted word.';
				}
				else if(strlen($res) == 1) {
					$errors[] = 'Too short words in your name.';
				}
			}
			// Validate vocation id
			if (!in_array((int)$_POST['selected_vocation'], $config['available_vocations'])) {
				$errors[] = 'Permission Denied. Wrong vocation.';
			}
			
			// Validate gender id
			if (!in_array((int)$_POST['selected_gender'], array(0, 1))) {
				$errors[] = 'Permission Denied. Wrong gender.';
			}
			if (vocation_id_to_name($_POST['selected_vocation']) === false) {
				$errors[] = 'Failed to recognize that vocation, does it exist?';
			}

			if (gender_exist($_POST['selected_gender']) === false) {
				$errors[] = 'Failed to recognize that gender, does it exist?';
			}
			// Char count
			$char_count = user_character_list_count($session_user_id);
			if ($char_count >= $config['max_characters']) {
				$errors[] = 'Your account is not allowed to have more than '. $config['max_characters'] .' characters.';
			}
			if (validate_ip(getIP()) === false && $config['validate_IP'] === true) {
				$errors[] = 'Failed to recognize your IP address. (Not a valid IPv4 address).';
			}
		}
	}
}
?>

<h1>Create Character</h1>
<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
	echo 'Congratulations! Your character has been created. See you in-game!';
} else {
	if (empty($_POST) === false && empty($errors) === true) {
		if ($config['log_ip']) {
			znote_visitor_insert_detailed_data(2);
		}
		//Register
		$character_data = array(
			'name'		=>	format_character_name($_POST['name']),
			'account_id'=>	$session_user_id,
			'vocation'	=>	$_POST['selected_vocation'],
			'town_id'	=>	1,
			'sex'		=>	$_POST['selected_gender'],
			'lastip'	=>	ip2long(getIP()),
			'created'	=>	time()
		);
		
		user_create_character($character_data);
		header('Location: createcharacter.php?success');
		exit();
		//End register
		
	} else if (empty($errors) === false){
		echo '<font color="red"><b>';
		echo output_errors($errors);
		echo '</b></font>';
	}
	?>
	<div class="col-sm-6">
	<form action="" method="post">
		<table class="table table-condensed">
			<tbody>
				<tr>
					<td>
						<label>Name</label><input class="form-control" type="text" name="name">
					</td>
				</tr>				
				<!--
					Bring this array back
				-->
				<tr>
					<td>
						<label>Gender</label> 
						<select multiple class="form-control" name="selected_gender">
						<option value="1">Male</option>
						<option value="0">Female</option>
						</select>
					</td>
				</tr>
					<td>
						<label>Vocation</label> 
						<select multiple class="form-control" name="selected_vocation">
						<option value="1">Sorcerer</option>
						<option value="2">Druid</option>
						<option value="3">Paladin</option>
						<option value="4">Knight</option>
						</select>
					</td>
				<tr>

				</tr>
			<?php
				/* Form file */
				Token::create();
			?>
			</tbody>
		</table>
		<input class="btn btn-primary" type="submit" value="Create Character">		
	</form>
	</div>	
	<?php
}
include 'layout/overall/footer.php'; ?>