<?php
require_once 'engine/init.php';
protect_page();
include 'layout/overall/header.php';

if (empty($_POST) === false) {
	// $_POST['']
	/* Token used for cross site scripting security */
	if (!Token::isValid($_POST['token'])) {
		$errors[] = 'Token is invalid.';
	}
	$required_fields = array('new_email');
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = 'You need to fill in all fields.';
			break 1;
		}
	}
	
	if (empty($errors) === true) {
		if (filter_var($_POST['new_email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = 'A valid email address is required.';
		} else if (user_email_exist($_POST['new_email']) === true && $user_data['email'] !== $_POST['new_email']) {
			$errors[] = 'That email address is already in use.';
		}
	}
	
	print_r($errors);
}
?>

<div class="well">
<h1>Settings</h1>

<?php
if (isset($_GET['success']) === true && empty($_GET['success']) === true) {
	echo 'Your settings have been updated.';
} else {
	if (empty($_POST) === false && empty($errors) === true) {
		$update_data = array(
			'email' => $_POST['new_email'],
		);
		
		user_update_account($update_data);
		header('Location: settings.php?success');
		exit();
		
	} else if (empty($errors) === false) {
		echo output_errors($errors);
	}
	?>
	
	<form action="" method="post">
		<ul>
			<li>
				Email:<br>
				<input type="text" class="form-control"  name="new_email" value="<?php echo $user_data['email']; ?>">
			</li>
			<?php
				/* Form file */
				Token::create();
			?>
			<li>
				<input class="btn btn-success" type="submit" value="Update settings">
			</li>
		</ul>
	</form>
<?php
}


if (empty($_POST) === false) {
	/* Token used for cross site scripting security */
	if (!Token::isValid($_POST['token'])) {
		$errors[] = 'Token is invalid.';
	}
	
	$required_fields = array('current_password', 'new_password', 'new_password_again');
	
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = 'You need to fill in all fields.';
			break 1;
		}
	}
	
	$pass_data = user_data($session_user_id, 'password');
	//$pass_data['password'];
	// $_POST['']
	
	// .3 compatibility
	if ($config['TFSVersion'] == 'TFS_03' && $config['salt'] === true) {
		$salt = user_data($session_user_id, 'salt');
	}
	if (sha1($_POST['current_password']) === $pass_data['password'] || $config['TFSVersion'] == 'TFS_03' && $config['salt'] === true && sha1($salt['salt'].$_POST['current_password']) === $pass_data['password']) {
		if (trim($_POST['new_password']) !== trim($_POST['new_password_again'])) {
			$errors[] = 'Your new passwords do not match.';
		} else if (strlen($_POST['new_password']) < 6) {
			$errors[] = 'Your new passwords must be at least 6 characters.';
		} else if (strlen($_POST['new_password']) > 32) {
			$errors[] = 'Your new passwords must be less than 33 characters.';
		}
	} else {
		$errors[] = 'Your current password is incorrect.';
	}
	
	print_r($errors);
}

?>

<h1>Change Password</h1>

<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
	echo 'Your password has been changed.<br>You will need to login again with the new password.';
	session_destroy();
	header("refresh:2;url=index.php");
	exit();
} else {
	if (empty($_POST) === false && empty($errors) === true) {
		//Posted the form without errors
		if ($config['TFSVersion'] == 'TFS_02') {
			user_change_password($session_user_id, $_POST['new_password']);
		} else if ($config['TFSVersion'] == 'TFS_03') {
			user_change_password03($session_user_id, $_POST['new_password']);
		}
		header('Location: changepassword.php?success');
	} else if (empty($errors) === false){
		echo '<font color="red"><b>';
		echo output_errors($errors);
		echo '</b></font>';
	}
	?>

	<form action="" method="post">
		<ul>
			<li>
				Current password:<br>
				<input class="form-control"  type="password" name="current_password">
			</li>
			<li>
				New password:<br>
				<input class="form-control"  type="password" name="new_password">
			</li>
			<li>
				New password again:<br>
				<input class="form-control" type="password" name="new_password_again">
			</li>
			<?php
				/* Form file */
				Token::create();
			?>
			<li>
				<input class="btn btn-success" type="submit" value="Change password">
			</li>
		</ul>
	</form>
</div>
<?php
}
include 'layout/overall/footer.php';
?>