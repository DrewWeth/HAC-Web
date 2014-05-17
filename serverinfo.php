<?php require_once 'engine/init.php'; include 'layout/overall/header.php'; ?>
	<center><h1>Server Information</h1></center>
	<ul>
	<h3>Connection Information</h3>
	<li>Client version: <?php echo ($config['client'])/100.0; ?></li>
	<li>IP address: <?php echo $_SERVER['SERVER_NAME']; ?></li>
	<h3>Experience Stages</h3>
	<tbody>
		<table class="table table-condensed table-striped">
			<tr>
				<td>
					Starting
				</td>
				<td>
					Ending
				</td>
				<td>
					Multiplier
				</td>
			</tr>
			<tr><td>1</td><td>7</td><td>70x</td></tr>
			<tr><td>8</td><td>29</td><td>60x</td></tr>
			<tr><td>30</td><td>49</td><td>50x</td></tr>
			<tr><td>50</td><td>74</td><td>35x</td></tr>
			<tr><td>75</td><td>94</td><td>20x</td></tr>
			<tr><td>95</td><td>119</td><td>15x</td></tr>
			<tr><td>120</td><td>149</td><td>10x</td></tr>
			<tr><td>150</td><td>199</td><td>5x</td></tr>
			<tr><td>200</td><td>299</td><td>3x</td></tr>
			<tr><td>300</td><td>-</td><td>2x</td></tr>
		</table></tbody>
	<hr>
<ul>
	<li>Magic: 10.0x</li>
	<li>Skill: 30.0x</li>
	<li>Loot: 3x</li>
</ul>
<?php include 'layout/overall/footer.php'; ?>