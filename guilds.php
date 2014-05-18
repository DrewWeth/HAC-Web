<?php require_once 'engine/init.php';
if ($config['require_login']['guilds']) protect_page();
include 'layout/overall/header.php';

if (user_logged_in() === true) {
	
	// fetch data
	$char_count = user_character_list_count($session_user_id);
	$char_array = user_character_list($user_data['id']);
	
	$characters = array();
	if ($char_array !== false) {
		foreach ($char_array as $value) {
			$characters[] = $value['name'];
		}
	}
}

if (empty($_GET['name'])) {
// Display the guild list
?>

	<h2 class="inline">Guilds</h2>

	<?php
	if (user_logged_in() === true) {
	?>
	<tbody>
		<table class="pull-right">
			<tr>
				<td>
					<form action="" method="post">
						<select name="selected_char" class="form-control">
							<?php
							for ($i = 0; $i < $char_count; $i++) {
								echo '<option value="'. $characters[$i] .'">'. $characters[$i] .'</option>'; 	
							}
							?>
						</select>
				</td>
				<td><input type="text" class="form-control" name="guild_name"></td>				
				<td><input type="submit" class="btn btn-success" value="Create Guild"></td>		
					</form>
			</tr>
		</table>
	</tbody>
	<?php }
	else
		echo "<p class=\"pull-right\">Log in to create a guild</p>"; ?>
	<hr>

	<?php
	$guilds = get_guilds_list();
	if ($guilds !== false) {
	?>
	<tbody>
		<table class="table table-condensed table-striped" id="guildsTable">
			<tr>
				<td>Guild Name</td>
				<td>Members</td>
				<td>Average Level</td>
				<td>Founded</td>
			</tr>
				<?php
				foreach ($guilds as $guild) {
					$gcount = count_guild_members($guild['id']);
					if ($gcount >= 1) {
						$url = url("guilds.php?name=". $guild['name']);
						echo '<tr class="special" onclick="javascript:window.location.href=\'' . $url . '\'">';
						echo '<td>'. $guild['name'] .'</td>';
						echo '<td>'. count_guild_members($guild['id']) .'</td>';
						echo '<td>'. average_guild_level($guild['id']) .'</td>';
						echo '<td>'. date($config['date'],$guild['creationdata']) .'</td>';
						echo '</tr>';
					}
				}
				?>
		</table>
	</tbody>
	<?php 
	} else 
		echo '<p>Guild list is empty.</p>';?>


	<!-- user stuff -->
	<?php
	if (user_logged_in() === true) {	
		// post verifications
		// CREATE GUILD
		if (!empty($_POST['selected_char']) && !empty($_POST['guild_name'])) {
			if (user_character_account_id($_POST['selected_char']) === $session_user_id) {
				//code here
				$name = sanitize($_POST['selected_char']);
				$user_id = user_character_id($name);
				$char_data = user_character_data($user_id, 'level', 'online');
				
				// If character level is high enough
				if ($char_data['level'] >= $config['create_guild_level']) {
				
					// If character is offline
					if ($char_data['online'] == 0) {
						$acc_data = user_data($user_data['id'], 'premdays');
						
						// If character is premium
						if ($config['guild_require_premium'] == false || $acc_data['premdays'] > 0) {
						
							if (get_character_guild_rank($user_id) < 1) {
							
								if (preg_match("/^[a-zA-Z_ ]+$/", $_POST['guild_name'])) {
								// Only allow normal symbols as guild name
									
									$guildname = sanitize($_POST['guild_name']);
									
									$gid = get_guild_id($guildname);
									if ($gid === false) {
										create_guild($user_id, $guildname);
										header('Location: success.php');
										exit();
									} else echo 'A guild with that name already exist.';
								} else echo 'Guild name may only contain a-z, A-Z and spaces.';
							} else echo 'You are already in a guild.';
						} else echo 'You need a premium account to create a guild.';
					} else echo 'Your character must be offline to create a guild.';
				} else echo $name .' is level '. $char_data['level'] .'. But you need level '. $config['create_guild_level'] .'+ to create your own guild!';
			}
		}	
	}
}	
 
else { // GUILD OVERVIEW
	$gid = get_guild_id($_GET['name']);
	if ($gid === false) {
		header('Location: guilds.php');
		exit();
	}
	$gcount = count_guild_members($gid);
	if ($gcount < 1) {
		header('Location: guilds.php');
		exit();
	}
	$inv_data = guild_invite_list($gid);
	$players = get_guild_players($gid);
	$inv_count = 0;
	
	// Calculate invite count
	if ($inv_data !== false) {
		foreach ($inv_data as $inv) {
			++$inv_count;
		}
	}
	
	// calculate visitor access
	if (user_logged_in() === true) {
		// Get visitor access in this guild
		$highest_access = 0;

		foreach ($players as $player) {
			$rid = $player['rank_id'];
			
			for ($i = 0; $i < $char_count; $i++) {
				$data = user_character_data(user_character_id($characters[$i]), 'rank_id');
				if ($data['rank_id'] == $rid) {
					$access = get_guild_position($data['rank_id']);
					if ($access == 2 || $access == 3) { //If player got access level vice leader or leader
						if ($access > $highest_access) $highest_access = $access;
					}
				}
			}
		}
	}
	// Display the specific guild page
?>

<h1>Guild <?php echo sanitize($_GET['name']); 
?> </h1>
<tbody>
<table class="table table-condensed" id="guildViewTable">
	<th>
		<td>Rank</td>
		<td>Name</td>
		<td>Level</td>
		<td>Vocation</td>
		<td>Status</td>
	</th>
		<?php
		foreach ($players as $player) {
			$chardata = user_character_data(user_character_id($player['name']), 'online');
			echo '<tr>';
			echo '<td>'. get_player_guild_rank($player['rank_id']) .'</td>';
			echo '<td><a href="characterprofile.php?name='. $player['name'] .'">'. $player['name'] .'</a></td>';
			echo '<td>'. $player['level'] .'</td>';
			echo '<td>'. $config['vocations'][$player['vocation']] .'</td>';
			if ($chardata['online'] == 1) echo '<td> <b><font color="green"> Online </font></b></td>';
			else echo '<td> Offline </td>';
			echo '</tr>';
		}
		?>
</table>
</tbody>

<?php if ($inv_count > 0) { ?>
<h3>Invited characters</h3>
<tbody>
<table class="table table-condensed">
	<tr>
		<td>Name:</td>
		<?php 
		if ($highest_access == 2 || $highest_access == 3) {
			echo '<td>Remove:</td>';
		}
		// Shuffle through visitor characters
		for ($i = 0; $i < $char_count; $i++) {
			$exist = false;
			// Shuffle through invited character, see if they match your character.
			foreach ($inv_data as $inv) {
				if (user_character_id($characters[$i]) == $inv['player_id']) {
					$exist = true;
				}
			}
			if ($exist) echo '<td>Join Guild:</td><td>Reject Invitation:</td>';
		}
		?>
	</tr>
		<?php
		$bool = false;
		foreach ($inv_data as $inv) {
			$uninv = user_character_data($inv['player_id'], 'name');
			echo '<tr>';
			echo '<td>'. $uninv['name'] .'</td>';
			// Remove invitation
			if ($highest_access == 2 || $highest_access == 3) {
			?> <form action="" method="post"> <?php
				echo '<td>';
				echo '<input type="hidden" name="uninvite" value="' . $inv['player_id'] . '" />';
				echo '<input type="submit" class="btn btn-danger" value="Remove Invitation">';
				echo '</td>';
			?> </form> <?php
			}
			// Join Guild
			?> <form action="" method="post"> <?php
				for ($i = 0; $i < $char_count; $i++) {
					if (user_character_id($characters[$i]) == $inv['player_id']) {
						echo '<td>';
						echo '<input type="hidden" name="joinguild" value="' . $inv['player_id'] . '" />';
						echo '<input class="btn btn-success" type="submit" value="Join Guild">';
						echo '</td>';
						$bool = true;
					}
				}
				if (isset($bool, $exist) && !$bool && $exist) {
					echo '<td></td>';
					$bool = false;
				}
			?> </form> <?php
			// Reject invitation
			?> <form action="" method="post"> <?php
				for ($i = 0; $i < $char_count; $i++) {
					if (user_character_id($characters[$i]) == $inv['player_id']) {
						echo '<td>';
						echo '<input type="hidden" name="uninvite" value="' . $inv['player_id'] . '" />';
						echo '<input type="submit" class="btn btn-danger" value="Reject Invitation">';
						echo '</td>';
						$bool = true;
					}
				}
				if (isset($bool, $exist) && !$bool && $exist) {
					echo '<td></td>';
					$bool = false;
				}
			?> </form> <?php
			echo '</tr>';
		}
		?>
</table>
</tbody>
<?php } ?>
<!-- Leader stuff -->
<?php
// Only guild leaders
if (user_logged_in() === true) {
	
	// Uninvite and joinguild is also used for visitors who reject their invitation.
	if (!empty($_POST['uninvite'])) {
		//
		guild_remove_invitation($_POST['uninvite'], $gid);
		header('Location: guilds.php?name='. $_GET['name']);
		exit();
	}
	if (!empty($_POST['joinguild'])) {
		// 
		foreach ($inv_data as $inv) {
			if ($inv['player_id'] == $_POST['joinguild']) {
				$chardata = user_character_data($_POST['joinguild'], 'online');
				if ($chardata['online'] == 0) {
					if (guild_player_join($_POST['joinguild'], $gid)) {
						header('Location: guilds.php?name='. $_GET['name']);
						exit();
					} else echo '<font color="red" size="4">Failed to find guild position representing member.</font>';
				} else echo '<font color="red" size="4">Character must be offline before joining guild.</font>';
			}
		}
	}
	
	if (!empty($_POST['leave_guild'])) {
		$name = sanitize($_POST['leave_guild']);
		$cidd = user_character_id($name);
		// If character is offline
		$chardata = user_character_data($cidd, 'online');
		if ($chardata['online'] == 0) {
			guild_player_leave($cidd);
			header('Location: guilds.php?name='. $_GET['name']);
			exit();
		} else echo '<font color="red" size="4">Character must be offline first!</font>';
	}
	
if ($highest_access >= 2) {
	// Guild leader stuff
	
	// Promote character to guild position
	if (!empty($_POST['promote_character']) && !empty($_POST['promote_position'])) {
		// Verify that promoted character is from this guild.
		$p_rid = $_POST['promote_position'];
		$p_cid = user_character_id($_POST['promote_character']);
		$p_guild = get_player_guild_data($p_cid);
		
		if ($p_guild['guild_id'] == $gid) {
			// Do the magic.
			$chardata = user_character_data($p_cid, 'online');
			if ($chardata['online'] == 0) {
				update_player_guild_position($p_cid, $p_rid);
				header('Location: guilds.php?name='. $_GET['name']);
				exit();
			} else echo '<font color="red" size="4">Character not offline.</font>';
			
		}
	}
	if (!empty($_POST['invite'])) {
		if (user_character_exist($_POST['invite'])) {
			// 
			$status = false;
			if ($inv_data !== false) {
				foreach ($inv_data as $inv) {
					if ($inv['player_id'] == user_character_id($_POST['invite'])) $status = true;
				}
			}
			foreach ($players as $player) {
				if ($player['name'] == $_POST['invite']) $status = true;
			}
			
			if ($status == false) {
				guild_invite_player(user_character_id($_POST['invite']), $gid);
				header('Location: guilds.php?name='. $_GET['name']);
				exit();
			} else echo '<font color="red" size="4">That character is already invited(or a member) on this guild.</font>';
		} else echo '<font color="red" size="4">That character name does not exist.</font>';
	}
	
	if (!empty($_POST['disband'])) {
		// 
		$gidd = (int)$_POST['disband'];
		$members = get_guild_players($gidd);
		$online = false;
		
		// First figure out if anyone are online.
		foreach ($members as $member) {
			$chardata = user_character_data(user_character_id($member['name']), 'online');
			if ($chardata['online'] == 1) {
				$online = true;
			}
		}
		
		if (!$online) {
			// Then remove guild rank from every player.
			foreach ($members as $member) {
				//$chardata = user_character_data(user_character_id($member['name']), 'online');
				guild_player_leave(user_character_id($member['name']));
			}
			
			// Remove all guild invitations to this guild
			if ($inv_count > 0) guild_remove_invites($gidd);
			
			// Then remove the guild itself.
			guild_delete($gidd);
			header('Location: success.php');
			exit();
		} else echo '<font color="red" size="4">All members must be offline to disband the guild.</font>';
	}
	
	if (!empty($_POST['new_leader'])) {
		$new_leader = (int)$_POST['new_leader'];
		$old_leader = guild_leader($gid);
		
		$online = false;
		$newData = user_character_data($new_leader, 'online');
		$oldData = user_character_data($old_leader, 'online');
		if ($newData['online'] == 1 || $oldData['online'] == 1) $online = true;
		
		if ($online == false) {
			if (guild_change_leader($new_leader, $old_leader)) {
				header('Location: guilds.php?name='. $_GET['name']);
				exit();
			} else echo '<font color="red" size="4">Something went wrong when attempting to change leadership.</font>';
		} else echo '<font color="red" size="4">The new and old leader must be offline to change leadership.</font>';
	}
	
	if (!empty($_POST['change_ranks'])) {
		$c_gid = (int)$_POST['change_ranks'];
		$c_ranks = get_guild_rank_data($c_gid);
		$rank_data = array();
		$rank_ids = array();
		
		// Feed new rank data
		foreach ($c_ranks as $rank) {
			$tmp = 'rank_name!'. $rank['level'];
			if (!empty($_POST[$tmp])) {
				$rank_data[$rank['level']] = sanitize($_POST[$tmp]);
				$rank_ids[$rank['level']] = $rank['id'];
			}
		}
		
		foreach ($rank_data as $level => $name) {
			guild_change_rank($rank_ids[$level], $name);
		}
		
		header('Location: guilds.php?name='. $_GET['name']);
		exit();
	}
	
	if (!empty($_POST['remove_member'])) {
		$name = sanitize($_POST['remove_member']);
		$cid = user_character_id($name);
		
		guild_remove_member($cid);
		header('Location: guilds.php?name='. $_GET['name']);
		exit();
	}

	if (!empty($_POST['forumGuildId'])) {
		if ($config['forum']['guildboard'] === true) {
			$forumExist = mysql_select_single("SELECT `id` FROM `znote_forum` WHERE `guild_id`='$gid' LIMIT 1;");
				if ($forumExist === false) {
					// Insert data
					mysql_insert("INSERT INTO `znote_forum` (`name`, `access`, `closed`, `hidden`, `guild_id`) 
						VALUES ('Guild', 
							'1', 
							'0', 
							'0', 
							'$gid');");
					echo '<h1>Guild board has been created.</h1>';
				} else echo '<h1>Guild board already exist.</h1>';
			
		} else {
			echo '<h1>Error: Guild board system is disabled.</h1>';
		}
	}
	
	$members = count_guild_members($gid);
	$ranks = get_guild_rank_data($gid);
	?>
		<!-- Form to create guild -->
		<?php
			if ($config['forum']['guildboard'] === true && $config['forum']['enabled'] === true) {
				$forumExist = mysql_select_single("SELECT `id` FROM `znote_forum` WHERE `guild_id`='$gid' LIMIT 1;");
				if ($forumExist === false) {
					?>
					<form action="" method="post">
						<ul>
							<li>Create forum guild board:<br>
							<input type="hidden" name="forumGuildId" value="<?php echo $gid; ?>">
							<input type="submit" class="btn btn-success" value="Create Guild Board">
						</ul>
					</form>
					<?php
				}
			}
		?>
		
		<!-- forms to invite character -->
		<form action="" method="post">
				Invite Character to guild<br>
					<input type="text" name="invite" placeholder="Character name"><br>
					<input type="submit" class="btn btn-success" value="Invite Character">
		</form>
		<?php if ($members > 1) { ?>
		<!-- FORMS TO PROMOTE CHARACTER-->
		<form action="" method="post">
			<ul>
				<li>
					Promote Character:<br>
					<select name="promote_character">
					<?php
					//$gid = get_guild_id($_GET['name']);
					//$players = get_guild_players($gid);
					foreach ($players as $player) {
						$pl_data = get_player_guild_data(user_character_id($player['name']));
						if ($pl_data['rank_level'] != 3) {
							echo '<option value="'. $player['name'] .'">'. $player['name'] .'</option>'; 
						}	
					}
					?>
					</select>
					<select name="promote_position">
						<?php
						foreach ($ranks as $rank) {
							if ($rank['level'] != 3) {
								if ($rank['level'] != 2) {
									echo '<option value="'. $rank['id'] .'">'. $rank['name'] .'</option>'; 
								} else {
									if ($highest_access == 3) {
										echo '<option value="'. $rank['id'] .'">'. $rank['name'] .'</option>'; 
									}
								}
							}
						}
						?>
					</select>
					<input type="submit" class="btn btn-success" value="Promote Member">
				</li>
			</ul>
		</form>
		<!-- Remove member from guild -->
		<form action="" method="post">
			<ul>
				<li>
					Kick member from guild:<br>
					<select name="remove_member">
					<?php
					//$gid = get_guild_id($_GET['name']);
					//$players = get_guild_players($gid);
					foreach ($players as $player) {
						$pl_data = get_player_guild_data(user_character_id($player['name']));
						if ($pl_data['rank_level'] != 3) {
							if ($pl_data['rank_level'] != 2) {
								echo '<option value="'. $player['name'] .'">'. $player['name'] .'</option>';
							} else if ($highest_access == 3) echo '<option value="'. $player['name'] .'">'. $player['name'] .'</option>';
						}
					}
					?>
					</select>
					<input type="submit" class="btn btn-danger" value="Remove member">
				</li>
			</ul>
		</form>
		<?php } ?>
		<br><br>
		<?php if ($highest_access == 3) { ?>
		<!-- forms to disband guild -->
		<form action="" method="post">
			<ul>
				<li><b>Change rank titles:</b><br>
					<?php
						$rank_count = 1;
						foreach ($ranks as $rank) {
							echo '<input type="text" name="rank_name!'. $rank['level'] .'" value="'. $rank['name'] .'">';
						}
						echo '<input type="hidden" name="change_ranks" value="' . $gid . '" />';
					?>
					<input type="submit" class="btn btn-default" value="Update Ranks">
				</li>
			</ul>
		</form>
		<!-- forms to disband guild -->
		<form action="" method="post">
			<ul>
				<li><b>DELETE GUILD (All members must be offline):</b><br>
					<?php echo '<input type="hidden" name="disband" value="' . $gid . '" />'; ?>
					<input class="btn btn-danger" type="submit" value="Disband Guild">
				</li>
			</ul>
		</form>
		<!-- forms to change leadership-->
		<?php if ($members > 1) { ?>
		<form action="" method="post">
			<ul>
				<li><b>Change Leadership with:</b><br>
					<select name="new_leader">
					<?php
					//$gid = get_guild_id($_GET['name']);
					//$players = get_guild_players($gid);
					foreach ($players as $player) {
						$pl_data = get_player_guild_data(user_character_id($player['name']));
						if ($pl_data['rank_level'] != 3) {
							echo '<option value="'. user_character_id($player['name']) .'">'. $player['name'] .'</option>'; 
						}	
					}
					?>
					</select>
					<input class="btn btn-success" type="submit" value="Change Leadership">
				</li>
			</ul>
		</form>
		<?php }} ?>
		<?php
	}
}
?>
<!-- end leader-->
<?php
if ($config['TFSVersion'] == 'TFS_02') $wardata = get_guild_wars();
else if ($config['TFSVersion'] == 'TFS_03') $wardata = get_guild_wars03();
else die("Can't recognize TFS version. It has to be either TFS_02 or TFS_03. Correct this in config.php");
$war_exist = false;
if ($wardata !== false) {
	foreach ($wardata as $wars) {
		if ($wars['guild1'] == $gid || $wars['guild2'] == $gid) $war_exist = true;
	}
}
if ($war_exist && $config['guildwar_enabled'] === true) {
?>
<h2>War overview:</h2>
<tbody>
<table class="table table-condensed">
	<th>
		<td>Attacker</td>
		<td>Defender</td>
		<td>status</td>
		<td>started</td>
	</th>
		<?php
		foreach ($wardata as $wars) {
			if ($wars['guild1'] == $gid || $wars['guild2'] == $gid) {
				$url = url("guildwar.php?warid=". $wars['id']);
				echo '<tr class="special" onclick="javascript:window.location.href=\'' . $url . '\'">';
				echo '<td>'. $wars['name1'] .'</td>';
				echo '<td>'. $wars['name2'] .'</td>';
				echo '<td>'. $config['war_status'][$wars['status']] .'</td>';
				echo '<td>'. date($config['date'],$wars['started']) .'</td>';
				echo '</tr>';
			}
		}
		?>
</table>
</tbody>
<?php 
} 
?>
<!-- leave guild with character -->
<?php
$bool = false;
if (user_logged_in() === true) {
	for ($i = 0; $i < $char_count; $i++) {
		foreach ($players as $player) {
			if ($player['name'] == $characters[$i]) $bool = true;
		}
	}
	if ($bool) {
		$forumExist = mysql_select_single("SELECT `id` FROM `znote_forum` WHERE `guild_id`='$gid' LIMIT 1;");
		if ($forumExist !== false) {
			?> - <font size="4"><a href="forum.php?cat=<?php echo $forumExist['id']; ?>">Visit Guild Board</a></font><br><br><br><?php
		}
?>

		<form action="" method="post">
			<ul>
				<li>
					Leave Guild:<br>
					<select name="leave_guild">
						<option disabled>With...</option>
					<?php
					for ($i = 0; $i < $char_count; $i++) {
						foreach ($players as $player) {
							if ($player['name'] == $characters[$i]) {
								$data = get_player_guild_data(user_character_id($player['name']));
								if ($data['rank_level'] != 3) echo '<option value="'. $characters[$i] .'">'. $characters[$i] .'</option>';
								else echo '<option disabled>'. $characters[$i] .' [disabled:Leader]</option>';
							}
						}
					}
					?>
					</select>
					<input class="btn btn-danger" type="submit" value="Leave Guild">
				</li>
			</ul>
		</form>

		<?php
		}	
	} // user logged in
} // if warname as $_GET
include 'layout/overall/footer.php';
?>