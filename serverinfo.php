<?php require_once 'engine/init.php'; include 'layout/overall/header.php'; ?>
	<center><h1>Server Information</h1></center>
	<ul>
	<h3>Connection Information</h3>
	<li>Client version: <?php echo ($config['client']); ?></li>
	<li>IP address: <?php echo $_SERVER['SERVER_NAME']; ?></li>
	<br>
	<h3>Rates</h3>
	<li>Experience rate: 20</li>
	<li>Magic: 20.0</li>
	<li>Skill: 20.0</li>
	<li>Loot: 3.0</li>
	</ul>
<?php include 'layout/overall/footer.php'; ?>