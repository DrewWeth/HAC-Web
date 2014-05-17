<footer>
<p>&copy; <?php echo $config['site_title'];?>.
<?php
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$finish = $time;
	$total_time = round(($finish - $start), 4);
	echo 'Server date and clock is: '. date($config['date'],time()) .' Page generated in '. $total_time .' seconds.';
?>
</footer>