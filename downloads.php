<?php require_once 'engine/init.php'; include 'layout/overall/header.php'; ?>
	<center><h2>Downloads</h2></center>
	<h3>Connection Information</h3>
	<tbody>
		<table class="table table-bordered">
			<tr>
				<td style="width:50%"><center><b>Custom Client</b></center></td><td><center><b>Original Client</b></center></td>
			</tr>
			<tr>
				<td><center><a href="https://dl.dropboxusercontent.com/u/7902004/hac_client.zip"><img class="img-responsive" src="https://farm3.staticflickr.com/2912/14016545650_9866bd310b_o.gif"></a></center></td>
				<td>
					<label>Step One</label>
					<a href="http://remeresmapeditor.com/rmedl.php?file=tibia<?php echo ($config['client']); ?>.exe">Download</a>, install, and start the tibia client if you havent already.
					<hr>
					<label>Step Two</label>
					<a href="http://static.otland.net/ipchanger.exe">Download</a> and run the IP changer with the IP as: <?php echo $_SERVER['SERVER_NAME']; ?>
				</td>
			</tr>		
		</table>
	</tbody>
	<hr>
	<li>Client version: <?php echo ($config['client'])/100.0; ?></li>
	<li>IP address: <?php echo $_SERVER['SERVER_NAME']; ?></li>
	<hr>

<?php 
include 'layout/overall/footer.php'; ?>