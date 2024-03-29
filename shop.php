<?php require_once 'engine/init.php';
include 'layout/overall/header.php'; 

// Import from config:
$shop = $config['shop'];
$shop_list = $config['shop_offers'];

if (!empty($_POST['buy'])) {
	$time = time();
	$player_points = (int)$user_znote_data['points'];
	$cid = (int)$user_data['id'];
	// Sanitizing post, setting default buy value
	$buy = false;
	$post = (int)$_POST['buy'];
	
	foreach ($shop_list as $key => $value) {
		if ($key === $post) {
			$buy = $value;
		}
	}
	if ($buy === false) die("Error: Shop offer ID mismatch.");
	
	// Verify that user can afford this offer.
	if ($player_points >= $buy['points']) {
		$old_points = mysql_result(mysql_query("SELECT `points` FROM `znote_accounts` WHERE `account_id`='$cid';"), 0, 'points');
		if ((int)$old_points != (int)$player_points) die("1: Failed to equalize your points.");
		// Remove points if they can afford
		// Give points to user
		$expense_points = $buy['points'];
		$new_points = $old_points - $expense_points;
		$update_account = mysql_query("UPDATE `znote_accounts` SET `points`='$new_points' WHERE `account_id`='$cid'");
		
		$verify = mysql_result(mysql_query("SELECT `points` FROM `znote_accounts` WHERE `account_id`='$cid';"), 0, 'points');
		if ((int)$old_points == (int)$verify) die("2: Failed to equalize your points.". var_dump((int)$old_points, (int)$verify, $new_points, $expense_points));
		
		// Do the magic (insert into db, or change sex etc)
		// If type is 2 or 3
		if ($buy['type'] == 2) {
			// Add premium days to account
			user_account_add_premdays($cid, $buy['count']);
			echo '<font color="green" size="4">You now have '.$buy['count'].' additional days of premium membership.</font>';
		} else if ($buy['type'] == 3) {
			// Character sex
			mysql_query("INSERT INTO `znote_shop_orders` (`account_id`, `type`, `itemid`, `count`, `time`) VALUES ('$cid', '". $buy['type'] ."', '". $buy['itemid'] ."', '". $buy['count'] ."', '$time')") or die(mysql_error());
			echo '<font color="green" size="4">You now have access to change character gender on your characters. Visit <a href="myaccount.php">My Account</a> to select character and change the gender.</font>';
		} else {
			mysql_query("INSERT INTO `znote_shop_orders` (`account_id`, `type`, `itemid`, `count`, `time`) VALUES ('$cid', '". $buy['type'] ."', '". $buy['itemid'] ."', '". $buy['count'] ."', '$time')") or die(mysql_error());
			echo '<font color="green" size="4">Your order is ready to be delivered. Write this command in-game to get it: [!shop].<br>Make sure you are in depot and can carry it before executing the command!</font>';
		}
		
		// No matter which type, we will always log it.
		mysql_query("INSERT INTO `znote_shop_logs` (`account_id`, `player_id`, `type`, `itemid`, `count`, `points`, `time`) VALUES ('$cid', '0', '". $buy['type'] ."', '". $buy['itemid'] ."', '". $buy['count'] ."', '". $buy['points'] ."', '$time')") or die(mysql_error());
		
	} else echo '<font color="red" size="4">You need more points, this offer cost '.$buy['points'].' points.</font>';
	//var_dump($buy);
	//echo '<font color="red" size="4">'. $_POST['buy'] .'</font>';
}
if ($shop['enabled']) {
?>

<h2>Shop Offers</h2>
<?php
if (user_logged_in() == true)
{
	if (!empty($_POST['buy'])) {
		if ($user_znote_data['points'] >= $buy['points']) {
			?><td>You have <?php echo (int)($user_znote_data['points'] - $buy['points']); ?> points. (<a href="buypoints.php">Buy points</a>).</td><?php
		} else {
			?><td>You have <?php echo $user_znote_data['points']; ?> points. (<a href="buypoints.php">Buy points</a>).</td><?php
		}
	} else {
		?><td>You have <?php echo $user_znote_data['points']; ?> points. (<a href="buypoints.php">Buy points</a>).</td><?php
	}
}
?>
<tbody>
<table class="table table-condensed table-striped" id="shopTable">
	<tr>
		<td>Describtion</td>
		<?php if ($config['shop']['showImage']) { ?><td>Image</td><?php } ?>
		<td>Count/duration</td>
		<td>Points</td>
		<td>Action</td>
	</tr>
		<?php
		foreach ($shop_list as $key => $offers) {
		echo '<tr class="special">';
		echo '<td>'. $offers['describtion'] .'</td>';
		if ($config['shop']['showImage']) echo '<td><img src="http://'. $config['shop']['imageServer'] .'/'. $offers['itemid'] .'.gif" alt="img"></td>';
		if ($offers['type'] == 2) echo '<td>'. $offers['count'] .' Days</td>';
		else if ($offers['type'] == 3 && $offers['count'] == 0) echo '<td>Unlimited</td>';
		else echo '<td>'. $offers['count'] .'x</td>';
		echo '<td>'. $offers['points'] .'</td>';
		echo '<td>';
		
		if (user_logged_in() == true) { ?>
		<form action="" method="POST">
			<input type="hidden" name="buy" value="<?php echo (int)$key; ?>">
			<input class="btn btn-primary" type="submit" value="  PURCHASE  ">
		</form>
		<?php }
		else { ?>
			<a class="btn btn-success" href="protected.php">PURCHASE</a>
		<?php
		}
		echo '</td>';
		echo '</tr>';
		}
		?>
</table>
</tbody>

<?php 
} else echo '<h1>Buy Points system disabled.</h1><p>Sorry, this functionality is disabled.</p>';

include 'layout/overall/footer.php'; ?>