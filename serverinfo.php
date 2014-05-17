<?php require_once 'engine/init.php'; include 'layout/overall/header.php'; ?>
	<center><h1>Server Information</h1></center>
	<ul>
	<h3>Connection Information</h3>
	<li>Client version: <?php echo ($config['client']); ?></li>
	<li>IP address: <?php echo $_SERVER['SERVER_NAME']; ?></li>
	
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
			<td>1</td><td>7</td><td>x70</td>
			<td>8</td><td>29</td><td>x60</td>
			<td>30</td><td>49</td><td>x50</td>
			<td>50</td><td>74</td><td>x35</td>
			<td>75</td><td>94</td><td>x20</td>
			<td>95</td><td>119</td><td>x15</td>
			<td>120</td><td>149</td><td>x10</td>
			<td>150</td><td>199</td><td>x5</td>
			<td>200</td><td>299</td><td>x3</td>
			<td>300</td><td>-</td><td>x2</td>

	<h3>Rates</h3>
	<li>Experience rate: 20</li>
	<li>Magic: 20.0</li>
	<li>Skill: 20.0</li>
	<li>Loot: 3.0</li>
	</ul>
<?php include 'layout/overall/footer.php'; ?>