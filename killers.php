<?php require_once 'engine/init.php'; include 'layout/overall/header.php';
if ($config['TFSVersion'] == 'TFS_02') {
$cache = new Cache('engine/cache/killers');
if ($cache->hasExpired()) {
	$killers = fetchMurders();
	
	$cache->setContent($killers);
	$cache->save();
} else {
	$killers = $cache->load();
}
$cache = new Cache('engine/cache/victims');
if ($cache->hasExpired()) {
	$victims = fetchLoosers();
	
	$cache->setContent($victims);
	$cache->save();
} else {
	$victims = $cache->load();
}
if ($killers) {
?>
<h1>Biggest Murders</h1>
<tbody>
<table class="table table-condensed table-striped" id="killersTable">
	<tr>
		<td>Name</td>
		<td>Kills</td>
	</tr>
	<?php foreach ($killers as $killer) { 
		echo '<tr>';
		echo "<td width='70%'><a href='characterprofile.php?name=". $killer['killed_by'] ."'>". $killer['killed_by'] ."</a></td>";
		echo "<td width='30%'>". $killer['kills'] ."</td>";
		echo '</tr>';
	} ?>
</table>
</tbody>
<?php
} else echo "No murders exist.\n";

if ($victims) {
?>
<h1>Biggest Victims</h1>
<table id="victimsTable">
	<tr class="yellow">
		<td>Name</td>
		<td>Deaths</td>
	</tr>
	<?php foreach ($victims as $victim) { 
		echo '<tr>';
		echo "<td width='70%'><a href='characterprofile.php?name=". $victim['name'] ."'>". $victim['name'] ."</a></td>";
		echo "<td width='30%'>". $victim['Deaths'] ."</td>";
		echo '</tr>';
	} ?>
</table>
<?php
} else echo "No victims exist.\n";

} else if ($config['TFSVersion'] == 'TFS_03') {
	/////////
	$cache = new Cache('engine/cache/killers');
	if ($cache->hasExpired()) {
		$deaths = fetchLatestDeaths_03(30, true);
		$cache->setContent($deaths);
		$cache->save();
	} else {
		$deaths = $cache->load();
	}
	?>
	<div class="well">
	<h1>Latest Killers</h1>
	<table class="table table-condensed table-striped" id="deathsTable">
		<tr>
			<td>Killer</td>
			<td>Time</td>
			<td>Victim</td>
		</tr>
		<?php foreach ($deaths as $death) { 
			echo '<tr>';
			echo "<td><a href='characterprofile.php?name=". $death['killed_by'] ."'>". $death['killed_by'] ."</a></td>";
			echo "<td>". date($config['date'], $death['time']) ."</td>";
			echo "<td>At level ". $death['level'] .": <a href='characterprofile.php?name=". $death['victim'] ."'>". $death['victim'] ."</a></td>";
			echo '</tr>';
		} ?>
	</table>
	</div>
	<?php
	/////////
}
include 'layout/overall/footer.php'; ?>