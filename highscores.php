<?php require_once 'engine/init.php'; include 'layout/overall/header.php';
if ($config['log_ip']) {
	znote_visitor_insert_detailed_data(3);
}
?>
<div class="well">

	<?php
		if (empty($_POST) === false) {
			
			#if ($_POST['token'] == $_SESSION['token']) {
			
			/* Token used for cross site scripting security */
			if (isset($_POST['token']) && Token::isValid($_POST['token'])) {
				
				$skillid = (int)$_POST['selected'];
				$cache = new Cache('engine/cache/highscores');

				if ($cache->hasExpired()) {
					$tmp = highscore_getAll();

					$cache->setContent($tmp);
					$cache->save();

					$array = isset($tmp[$skillid]) ? $tmp[$skillid] : $tmp[7];
				} else {
					$tmp = $cache->load();
					$array = $tmp[$skillid];
				}
				
				if ($skillid < 9) {
				// Design and present the list
				if ($array) {
					?>
					<h3>
						<?php echo ucfirst(skillid_to_name($skillid)); ?> scoreboard.</h3><div class="news-post-date">Next update: 
							<?php
								if ($cache->remainingTime() > 0) {
									$hours = seconds_to_hours($cache->remainingTime());
									$minutes = ($hours - (int)$hours) * 60;
									$seconds = ($minutes - (int)$minutes) * 60;
									if ($hours >= 1) {
										echo (int)$hours .'h';
									}
									if ($minutes >= 1) {
										echo ' '. (int)$minutes .'m';
									}
									if ($seconds >= 1) {
										echo ' '. (int)$seconds .'s';
									}
								} else {
									echo '0s';
								}
								
							?>. <?php echo remaining_seconds_to_clock($cache->remainingTime());?>
					</div>





					<div class="col-sm-3">
						<tbody>
							<table class="table table-condensed">
								<tr>
									<td>
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
										<td>
											<input type="submit" class="btn btn-primary" value="Fetch scoreboard">
										</form>
									</td>
								</tr>
							</table>
						</tbody>
					
					</div>
					<div class="col-sm-9">
					<tbody>
						<table class="table table-condensed" id="highscoresTable">
							<tr>
								<td>Name</td>
								<?php
								if ($skillid == 7) echo '<td>Level</td><td>Experience</td>';
								else {
								?>
								<td>Value</td>
								<?php
								}
								if ($skillid == 7 || $skillid == 6 || $skillid == 5) {
									echo '<td>Vocation</td>';
								}
								?>
							</tr>
								<?php
								foreach ($array as $value) {
									// start foreach
									if ($value['group_id'] < 2) {
										echo '<tr>';
										echo '<td><a href="characterprofile.php?name='. $value['name'] .'">'. $value['name'] .'</a></td>';
										if ($skillid == 7) echo '<td>'. $value['level'] .'</td>';
										echo '<td>'. $value['value'] .'</td>';
										if ($skillid == 7 || $skillid == 6 || $skillid == 5) {
											echo '<td>'. $value['vocation'] .'</td>';
										}
										echo '</tr>';
									}
									// end foreach
								}
								?>
						</table>
					</tbody>
				</div>

					<?php
				} else {
					echo 'Empty list, it appears all players have less than 500 experience points.';
				}
				//Done.
				}
			} else {
				echo 'Token appears to be incorrect.<br><br>';
				//Token::debug($_POST['token']);
				echo 'Please clear your web cache/cookies <b>OR</b> use another web browser<br>';
			}
		}
		else
		{
			echo "<h3>Nothing Posted</h3>";
		}
		?>
			</div>

<?php
/*
0 fist: SELECT (SELECT `name` from `players` WHERE `player_id`=`id`) AS `name`, `value` FROM `player_skills` WHERE `skillid`=0
1 club: 
2 sword: 
3 axe: 
4 dist: 
5 Shield: 
6 Fish
7 Hardcoded experience
8 Hardcoded maglevel
*/
include 'layout/overall/footer.php'; ?>