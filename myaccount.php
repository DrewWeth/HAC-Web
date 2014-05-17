<?php require_once 'engine/init.php';
protect_page();
include 'layout/overall/header.php'; 
// Change character comment PAGE2 (Success).
if (!empty($_POST['comment']) &&!empty($_POST['charn'])) {
	if (!Token::isValid($_POST['token'])) {
		exit();
	}
	if (user_character_account_id($_POST['charn']) === $session_user_id) {
		user_update_comment(user_character_id($_POST['charn']), $_POST['comment']);
		echo 'Successfully updated comment.';
	}
} else {
// Hide character
if (!empty($_POST['selected_hide'])) {
	if (!Token::isValid($_POST['token'])) {
		exit();
	}
	$hide_array = explode("!", $_POST['selected_hide']);	
	if (user_character_account_id($hide_array[0]) === $session_user_id) {
		user_character_set_hide(user_character_id($hide_array[0]), $hide_array[1]);
	}
}
// end
// DELETE character
if (!empty($_POST['selected_delete'])) {
	if (!Token::isValid($_POST['token'])) {
		exit();
	}
	if (user_character_account_id($_POST['selected_delete']) === $session_user_id) {
		$charid = user_character_id($_POST['selected_delete']);
		$chr_data = user_character_data($charid, 'online');
		if ($chr_data['online'] != 1) {
			if (guild_leader_gid($charid) === false) user_delete_character($charid);
			else echo 'Character is leader of a guild, you must disband the guild or change leadership before deleting character.';
		} else echo 'Character must be offline first.';
	}
}
// end
// Change character sex
if (!empty($_POST['change_gender'])) {
	if (!Token::isValid($_POST['token'])) {
		exit();
	}
	if (user_character_account_id($_POST['change_gender']) === $session_user_id) {
		$char_name = sanitize($_POST['change_gender']);
		$char_id = (int)user_character_id($char_name);
		$account_id = user_character_account_id($char_name);
		
		$chr_data = user_character_data($char_id, 'online');
		if ($chr_data['online'] != 1) {
			// Verify that we are not messing around with data
			if ($account_id != $user_data['id']) die("wtf? Something went wrong, try relogging.");
			
			// Fetch character tickets
			$tickets = shop_account_gender_tickets($account_id);
			//$tickets = mysql_result(mysql_query("SELECT `count` FROM `znote_shop_orders` WHERE `account_id`='' AND `type`='3';"), 0, 'count');
			//$dbid = mysql_result(mysql_query("SELECT `id` FROM `znote_shop_orders` WHERE `account_id`='$account_id' AND `type`='3';"), 0, 'id');
			if ($tickets !== false || $config['free_sex_change'] == true) {
				// They are allowed to change gender
				$last = false;
				$infinite = false;
				$tks = 0;
				// Do we have any infinite tickets?
				foreach ($tickets as $ticket) {
					if ($ticket['count'] == 0) $infinite = true;
					else if ($ticket > 0 && $infinite === false) $tks += (int)$ticket['count'];
				}
				if ($infinite === true) $tks = 0;
				$dbid = (int)$tickets[0]['id'];
				// If they dont have unlimited tickets, remove a count from their ticket.
				if ($tickets[0]['count'] > 1) { // Decrease count
					$tks--;
					$tkr = ((int)$tickets[0]['count'] - 1);
					shop_update_row_count($dbid, $tkr);
				} else if ($tickets[0]['count'] == 1) { // Delete record
					shop_delete_row_order($dbid);
					$tks--;
				}
				
				// Change character gender:
				//
				user_character_change_gender($char_name);
				echo 'You have successfully changed gender on character '. $char_name .'.';
				if ($tks > 0) echo '<br>You have '. $tks .' gender change tickets left.';
				else if ($infinite !== true) echo '<br>You are out of tickets.';
			} else echo 'You don\'t have any character gender tickets, buy them in the <a href="shop.php">SHOP</a>!';
		} else echo 'Your character must be offline.';
	}
}
// end
// Change character comment PAGE1:
if (!empty($_POST['selected_comment'])) {
	if (!Token::isValid($_POST['token'])) {
		exit();
	}
	if (user_character_account_id($_POST['selected_comment']) === $session_user_id) {
		$comment_data = user_znote_character_data(user_character_id($_POST['selected_comment']), 'comment');
		?>
		<!-- Changing comment MARKUP -->
		<h1>Change comment on:</h1>
		<form action="" method="post">
			<ul>
				<li>
					<input name ="charn" type="text" value="<?php echo $_POST['selected_comment']; ?>" readonly="readonly">
				</li>
				<li>
					<font class="profile_font" name="profile_font_comment">Comment:</font> <br>
					<textarea name="comment" cols="70" rows="10"><?php echo $comment_data['comment']; ?></textarea>
				</li>
				<?php
					/* Form file */
					Token::create();
				?>
				<li><input class="btn btn-default" type="submit" value="Update Comment"></li>
			</ul>
		</form>
		<?php
	}
} else {
	// end
	$char_count = user_character_list_count($session_user_id);
	?>

	<div class="well" id="myaccount">
		<h2>Account Management</h2><div class="pull-right"><a class="btn btn-success" href='createcharacter.php'>Create Character</a></div>

		<hr>
		<h3>Characters</h3>
		<?php
		// Echo character list!
		$char_array = user_character_list($user_data['id']);
		// Design and present the list
		if ($char_array) {
			?>
			<tbody>
				<table class="table table-condensed" id="myaccountTable">
					<?php
					$characters = array();
					foreach ($char_array as $value) {
						// characters: [0] = name, [1] = level, [2] = vocation, [3] = town_id, [4] = lastlogin, [5] = online
						echo '<tr>';
						echo '<td><a href="characterprofile.php?name='. $value['name'] .'">'. $value['name'] .'</a></td><td>'. hide_char_to_name(user_character_hide($value['name'])) .'</td>';
						?>
						<td>
						<form action="" method="post" style="display:inline;">
							<?php
							echo '<input type="hidden" name="selected_delete" value="'. $value['name'] .'"/>'; 	
							/* Form file */
							Token::create();
							?>
							<input class="btn btn-danger" type="submit" value="Delete">	
						</form>
						
							<form action="" method="post" style='margin: 0; padding: 0'>
								<?php
								echo '<input type="hidden" name="change_gender" value="'. $value['name'] .'"/>'; 	
								/* Form file */
								Token::create();
								?>
								<input class="btn btn-default" type="submit" value="Gender">
							</form>
						
							<form action="" method="post" style='margin: 0; padding: 0'>
								<?php
								echo '<input type="hidden" name="selected_comment" value="'. $value['name'] .'"/>'; 	
								/* Form file */
								Token::create();
								?>
								<input class="btn btn-default" type="submit" value="Comment">
							</form>
					
							<form action="" method="post" style='margin: 0; padding: 0'>
								<?php
								echo '<input type="hidden" name="selected_hide" value="'. $value['name'] .'"/>'; 	
								/* Form file */
								Token::create();
								?>
								<input class="btn btn-default" type="submit" value="Hide">
							</form>
						</td>

						<?php
						echo '</tr>';
						$characters[] = $value['name'];
					}
				?>
				</table>
			</tbody>

			<?php
			} else {
				echo 'You don\'t have any characters. Why don\'t you <a href="createcharacter.php">create one</a>?';
			}
			//Done.
		}
		?>
	</div>
	<?php
}
include 'layout/overall/footer.php'; ?>