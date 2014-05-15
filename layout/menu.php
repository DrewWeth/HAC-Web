<ul class="sf-menu" id="nav">
	<li><a href="index.php">Home</a></li>
	<li><a href="downloads.php">Downloads</a></li>
	<li><a href="serverinfo.php">Server Information</a></li>

	<li><a href="forum.php">Community</a>
		<ul> <!-- (sub)dropdown COMMUNITY -->
			<li><a href="highscores.php">Highscores</a></li>
			<li><a href="houses.php">Houses</a></li>
			<li><a href="deaths.php">Deaths</a></li>
			<li><a href="killers.php">Killers</a></li>
		</ul>
	</li>
	<li><a href="forum.php">Forum</a></li>
	
	<li><a href="shop.php">Shop</a>
		<ul> <!-- (sub)dropdown SHOP -->
			<li><a href="buypoints.php">Buy Points</a></li>
			<li><a href="shop.php">Shop Offers</a></li>
		</ul>
	</li>
	<li><a href="guilds.php">Guilds</a>
	<?php if ($config['guildwar_enabled'] === true) { ?>
		<ul>
			<li><a href="guilds.php">Guild List</a></li>
			<li><a href="guildwar.php">Guild Wars</a></li>
		</ul>
	<?php } ?></li>
	
</ul>