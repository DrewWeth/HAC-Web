<?php require_once 'engine/init.php'; include 'layout/overall/header.php'; ?>

<h1>Who is online?</h1>
<?php
$array = online_list();
if ($array) {
	?>
	<tbody>
	<table class="table table-condensed" id="onlinelistTable">
		<tr>
			<td>Name</td>
			<td>Level</td>
			<td>Vocation</td>
		</tr>
			<?php
			foreach ($array as $value) {
			echo '<tr>';
			echo '<td><a href="characterprofile.php?name='. $value[0] .'">'. $value[0] .'</a></td>';
			echo '<td>'. $value[1] .'</td>';
			echo '<td>'. vocation_id_to_name($value[2]) .'</td>';
			echo '</tr>';
			}
			?>
	</table>

	<?php
} else {
	echo 'Nobody is online.';
}
?>
<?php include 'layout/overall/footer.php'; ?>