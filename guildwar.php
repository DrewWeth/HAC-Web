<?php require_once 'engine/init.php'; 
if ($config['require_login']['guildwars']) protect_page();
if ($config['log_ip']) znote_visitor_insert_detailed_data(3);
if ($config['guildwar_enabled'] === false) {
	header('Location: guilds.php');
	exit();
}
include 'layout/overall/header.php';

if (!empty($_GET['warid'])) {
	$warid = (int)$_GET['warid']; // Sanitizing GET.
	
	if ($config['TFSVersion'] == 'TFS_02') $war = get_guild_war($warid);
	else if ($config['TFSVersion'] == 'TFS_03') $war = get_guild_war03($warid);
	else die("Can't recognize TFS version. It has to be either TFS_02 or TFS_03. Correct this in config.php");
	
	if ($war != false) {
		// Kills data for this specific war entry
		if ($config['TFSVersion'] == 'TFS_02') $kills = get_war_kills($warid);
		else if ($config['TFSVersion'] == 'TFS_03') $kills = get_war_kills03($warid);
		// XDXD
		
		?>
		<h1><?php echo $war['name1']; ?> - VERSUS - <?php echo $war['name2']; ?></h1>
		
		<?php
		// Collecting <ul> data:
		$guild1 = $war['guild1'];
		$g1c = 0; // kill count
		
		$guild2 = $war['guild2'];
		$g2c = 0; // kill count
		
		if ($config['TFSVersion'] == 'TFS_02') {
			foreach ($kills as $kill) {
				if ($kill[killerguild] == $guild1) ++$g1c;
				if ($kill[killerguild] == $guild2) ++$g2c;
			}
			
			$green = false;
			if ($g1c > $g2c) {
				$leading = $war['name1'];
				$green = true;
			} else if ($g2c > $g1c) $leading = $war['name2'];
			else $leading = "Tie";
		}
		?>
		<ul class="war_list">
			<li>
				War status: <?php echo $config['war_status'][$war['status']]; ?>.
			</li>
			<?php if ($config['TFSVersion'] == 'TFS_02') { ?>
			<li>
				Leading guild: <?php echo $leading; ?>.
			</li>
			<li>
				<?php
				if ($green) echo 'Score: <font color="green">'. $g1c .'</font>-<font color="red">'. $g2c .'</font>';
				else if ($g1c = $g2c) echo 'Score: <font color="orange">'. $g1c .'</font>-<font color="orange">'. $g2c .'</font>';
				else echo 'Score: <font color="red">'. $g1c .'</font>-<font color="green">'. $g2c .'</font>';
				?>
			</li>
			<?php } ?>
		</ul>
		<?php
		if ($config['TFSVersion'] == 'TFS_02') {
		?>
			<table id="guildwarTable">
				<tr class="yellow">
					<td>Killer's guild:</td>
					<td>Killer:</td>
					<td>Victim:</td>
					<td>Time:</td>
				</tr>
					<?php
					foreach ($kills as $kill) {
						echo '<tr>';
						//echo '<td>'. get_guild_name($kill['killerguild']) .'</td>';
						echo '<td><a href="guilds.php?name='. get_guild_name($kill['killerguild']) .'">'. get_guild_name($kill['killerguild']) .'</a></td>';
						echo '<td><a href="characterprofile.php?name='. $kill['killer'] .'">'. $kill['killer'] .'</a></td>';
						echo '<td><a href="characterprofile.php?name='. $kill['target'] .'">'. $kill['target'] .'</a></td>';
						echo '<td>'. date($config['date'],$kill['time']) .'</td>';
						echo '</tr>';
					}
					?>
			</table>
		<?php
		}
		if ($config['TFSVersion'] == 'TFS_03') {
			// BORROWED FROM GESIOR (and ported to work on Znote AAC).
			$main_content = "";
			$deaths = gesior_sql_death($warid);
			if($deaths !== false)
			{
				//die(print_r($deaths));
				foreach($deaths as $death)
				{
					$killers = gesior_sql_killer((int)$death['id']);
					$count = count($killers); $i = 0;

					$others = false;
					$main_content .= date("j M Y, H:i", $death['date']) . " <span style=\"font-weight: bold; color: " . ($death['enemy'] == $war['guild_id'] ? "red" : "lime") . ";\">+</span>
<a href=\"characterprofile.php?name=" . urlencode($death['name']) . "\"><b>".$death['name']."</b></a> ";
					foreach($killers as $killer)
					{
						$i++;
						if($killer['is_war'] != 0)
						{
							if($i == 1)
								$main_content .= "killed at level <b>".$death['level']."</b> by ";
							else if($i == $count && $others == false)
								$main_content .= " and by ";
							else
								$main_content .= ", ";
							if($killer['player_exists'] == 0)
								$main_content .= "<a href=\"characterprofile.php?name=".urlencode($killer['player_name'])."\">";

							$main_content .= $killer['player_name'];
							if($killer['player_exists'] == 0)
								$main_content .= "</a>";
						}
						else
							$others = true;

						if($i == $count)
						{
							if($others == true)
									$main_content .= " and few others";
							$main_content .= ".<br />";
						}
					}
				}
			}
			else
				$main_content .= "<center>There were no frags on this war so far.</center>";
			echo $main_content;
			// END BORROWED FROM GESIOR
		}
	}
	
} else {
	// Display current wars.
	
	// Fetch list of wars
	if ($config['TFSVersion'] == 'TFS_02') $wardata = get_guild_wars();
	else if ($config['TFSVersion'] == 'TFS_03') $wardata = get_guild_wars03();
	else die("Can't recognize TFS version. It has to be either TFS_02 or TFS_03. Correct this in config.php");
	//echo $wardata[0]['name1'];
	
	if ($wardata != false) {
	
	// kills data
	$killsdata = array(); // killsdata[guildid] => array(warid) => array info about the selected war entry
	foreach ($wardata as $wars) {
		if ($config['TFSVersion'] == 'TFS_02') $killsdata[$wars['id']] = get_war_kills($wars['id']);
		else if ($config['TFSVersion'] == 'TFS_03') $killsdata[$wars['id']] = get_war_kills03($wars['id']);
	}
		?>
		
		<table id="guildwarViewTable">
			<tr class="yellow">
				<td>Attacking Guild:</td>
				<td>Death Count:</td>
				<td>Defending Guild:</td>
			</tr>
				<?php
				foreach ($wardata as $wars) {
					$url = url("guildwar.php?warid=". $wars['id']);
					echo '<tr class="special" onclick="javascript:window.location.href=\'' . $url . '\'">';
					echo '<td>'. $wars['name1'] .'</td>';
					echo '<td>'. count($killsdata[$wars['id']]) .'</td>';
					echo '<td>'. $wars['name2'] .'</td>';
					echo '</tr>';
				}
				?>
		</table>

		<?php
	} else {
		echo 'There have not been any pending wars on this server.';
	}
}
// GET links sample:
// guildwar.php?warid=1
include 'layout/overall/footer.php'; ?>