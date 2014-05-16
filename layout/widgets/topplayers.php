<div class="sidebar">
		<h2>Top 5 players</h2>
		<?php
		$cache = new Cache('engine/cache/topPlayer');
		if ($cache->hasExpired()) {
			$players = mysql_select_multi("SELECT `name`, `level`, `experience` FROM `players` ORDER BY `experience` DESC LIMIT 5;");
			
			$cache->setContent($players);
			$cache->save();
		} else {
			$players = $cache->load();
		}

		$count = 1;
		echo "<tbody>";
		echo "<table class=\"table table-condensed\">";
		foreach($players as $player) {
			echo "<tr><td>$count</td><td><a href='characterprofile.php?name=". $player['name']. "'>". $player['name']. "</a></td><td><span class=\"label label-default\"". $player['level'] ."</span></td></tr>";
			$count++;
		}
		echo "</table>";
		echo "</tbody>";
		?>
</div>