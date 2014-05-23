<?php require_once 'engine/init.php'; include 'layout/overall/header.php'; 
if ($config['log_ip']) {
	znote_visitor_insert_detailed_data(4);
}
if (isset($_GET['name']) === true && empty($_GET['name']) === false) {
	$name = $_GET['name'];
	
	if (user_character_exist($name)) {
		$user_id = user_character_id($name);
		$profile_data = user_character_data($user_id, 'name', 'level', 'vocation', 'lastlogin', 'online');
		$profile_znote_data = user_znote_character_data($user_id, 'created', 'hide_char', 'comment');
		
		$guild_exist = false;
		if (get_character_guild_rank($user_id) > 0) {
			$guild_exist = true;
			$guild = get_player_guild_data($user_id);
			$guild_name = get_guild_name($guild['guild_id']);
		}
		?>
		
		<!-- PROFILE MARKUP HERE-->
			<?php ?>
			<h1><font class="profile_font" name="profile_font_header">Profile: <?php echo $profile_data['name']; ?></font></h1>
			<tbody>
			<table class="table table-condensed table-striped">
				<tr>
					<td>
						<font class="profile_font" name="profile_font_level">Level
					</td>
					<td>
						<?php echo $profile_data['level']; ?></font>
					</td>
				</tr>
				<tr>
					<td>
						<font class="profile_font" name="profile_font_vocation">Vocation
					</td>
					<td>
						<?php echo vocation_id_to_name($profile_data['vocation']); ?></font>
					</td>
				<?php 
				if ($guild_exist) {
				?>
				</tr>
				<tr>
					<td>Guild</td>
					<td><font class="profile_font" name="profile_font_vocation"><b><?php echo $guild['rank_name']; ?></b> of <a href="guilds.php?name=<?php echo $guild_name; ?>"><?php echo $guild_name; ?></a></font>
					</td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td><font class="profile_font" name="profile_font_lastlogin">Last Login</td> <?php
					if ($profile_data['lastlogin'] != 0) {
						echo('<td>'.date($config['date'],$profile_data['lastlogin']).'</td>');
					} else {
						echo '<td>Never</td>';
					}
					
				?></font></tr>
				<tr>
					<td>
						<font class="profile_font" name="profile_font_status">Status</font>
					</td> 
					<td>
						<?php 
						if ($profile_data['online'] == 1) {
							echo '<label class="label label-success">Online</label>';
						} else {
							echo '<label class="label label-danger">Offline</label>';
						}
					?></td></tr>
				<tr><td><font class="profile_font" name="profile_font_created">Created:
					</td>
					<td>
						<?php echo(date($config['date'],$profile_znote_data['created'])); ?></font>
					</td>
				</tr>
				<tr><td><font class="profile_font" name="profile_font_comment">Comment</font></td><td><?php echo $profile_znote_data['comment']; ?></td></tr>
				<!-- DEATH LIST -->
				</tbody>
				</table>
					<b>Death List:</b><br>
					<?php
					if ($config['TFSVersion'] == 'TFS_02') {
						$array = user_fetch_deathlist($user_id);
						if ($array) {
							//print_r($array);
							?>
							<ul>
								<?php
								// Design and present the list
								foreach ($array as $value) {
									echo '<li>';
									// $value[0]
									$value[1] = date($config['date'],$value[1]);								
									if ($value[4] == 1) {
										$value[3] = 'player: <a href="characterprofile.php?name='. $value[3] .'">'. $value[3] .'</a>';
									} else {
										$value[3] = 'monster: '. $value[3] .'.';
									}
									
									echo '['. $value[1] .'] Killed at level '. $value[2] .' by '. $value[3];
									echo '</li>';
								}
							?>
							</ul>
							<?php
							} else {
								echo '<b><font color="green">This player has never died.</font></b>';
							}
							//Done.
						}
						if ($config['TFSVersion'] == 'TFS_03') {
							$array = user_fetch_deathlist03($user_id);
							if ($array) {
							?>
							<ul>
								<?php
								// Design and present the list
								foreach ($array as $value) {
									echo '<li>';
									$value[3] = user_get_killer_id(user_get_kid($value['id']));
									if ($value[3] !== false && $value[3] >= 1) {
										$namedata = user_character_data((int)$value[3], 'name');
										if ($namedata !== false) {
											$value[3] = $namedata['name'];
											$value[3] = 'player: <a href="characterprofile.php?name='. $value[3] .'">'. $value[3] .'</a>';
										} else {
											$value[3] = 'deleted player.';
										}
									} else {
										$value[3] = user_get_killer_m_name(user_get_kid($value['id']));
										if ($value[3] === false) $value[3] = 'deleted player.';
									}
									echo '['. date($config['date'],$value['date']) .'] Killed at level '. $value['level'] .' by '. $value[3];
									echo '</li>';
								}
							?>
							</ul>
							<?php
							} else {
								echo '<b><font color="green">This player has never died.</font></b>';
							}
						}
						?>

				
				<!-- END DEATH LIST -->
				
				<!-- CHARACTER LIST -->
				<?php
				if (user_character_hide($profile_data['name']) != 1 && user_character_list_count(user_character_account_id($name)) > 1) {
				?>
						<p><b>Other characters</b></p>
						<?php
						$characters = user_character_list(user_character_account_id($profile_data['name']));
						// characters: [0] = name, [1] = level, [2] = vocation, [3] = town_id, [4] = lastlogin, [5] = online
						if ($characters && count($characters) > 1) {
							?>
							<tbody>
							<table class="table table-striped table-condensed" id="characterprofileTable">
								<tr>
									<th>
										Name
									</th>
									<th>
										Level
									</th>
									<th>
										Vocation
									</th>
									<th>
										Last login
									</th>
									<th>
										Status
									</th>
								</tr>
								<?php
								// Design and present the list
								foreach ($characters as $char) {
									if ($char['name'] != $profile_data['name']) {
										if (hide_char_to_name(user_character_hide($char['name'])) != 'hidden') {
											echo '<tr>';
											echo '<td><a href="characterprofile.php?name='. $char['name'] .'">'. $char['name'] .'</a></td>';
											echo '<td>'. $char['level'] .'</td>';
											echo '<td>'. $char['vocation'] .'</td>';
											echo '<td>'. $char['lastlogin'] .'</td>';
											echo '<td>'. $char['online'] .'</td>';
											echo '</tr>';
										}
									}
								}
							?>
							</table>
						</tbody>
							<?php
							} else {
								echo '<span class="label label-success">This player has never died</span>';
							}
								//Done.
							?>
				<?php
				}
				?>
				<!-- END CHARACTER LIST -->
				<div class="profile-post-address"><font class="profile_font" name="profile_font_share_url"><a href="<?php 
					if ($config['htwrite']) echo "http://".$_SERVER['HTTP_HOST']."/". $profile_data['name'];
					else echo "http://".$_SERVER['HTTP_HOST']."/characterprofile.php?name=". $profile_data['name'];
					
				?>"><?php
					if ($config['htwrite']) echo "http://".$_SERVER['HTTP_HOST']."/". $profile_data['name'];
					else echo "http://".$_SERVER['HTTP_HOST']."/characterprofile.php?name=". $profile_data['name'];
				?></a></font></div>
		<!-- END PROFILE MARKUP HERE-->
		
		<?php
	} else {
		echo htmlentities(strip_tags($name, ENT_QUOTES)).' does not exist.';
	}
} else {
	header('Location: index.php');
}

include 'layout/overall/footer.php'; ?>