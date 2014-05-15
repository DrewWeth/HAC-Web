<div class="sidebar">
	<div class="container">
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
		echo "<table class=\"table table-striped\">";
		foreach($players as $player) {
			echo "<tr><td>$count</td><td><a href='characterprofile.php?name=". $player['name']. "'>". $player['name']. "</a> (". $player['level'] .")</td></tr>";
			$count++;
		}
		echo "</table>";
		?>
	</div>
</div>