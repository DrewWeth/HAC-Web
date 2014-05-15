<div class="sidebar">
	<h2>Server Information</h2>
	<div class="inner">
		<table>
			<tr>
				<td>
					<?php if ($status) {
					?>
						<li><a href="onlinelist.php"><?php echo user_count_online();?> player online</a>
					<?php
					}
					?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo user_count_accounts();?> registered accounts
				</td>
			</tr>

			<tr>
				<td>
					<?php
					$status = true;
					if ($config['status']['status_check']) {
						@$sock = fsockopen ($config['status']['status_ip'], $config['status']['status_port'], $errno, $errstr, 1);
						if(!$sock) {
							echo "<span style='color:red;font-weight:bold;'><center>Server Offline!</center></span><br/>";
							$status = false;
						}
						else {
							$info = chr(6).chr(0).chr(255).chr(255).'info';
							fwrite($sock, $info);
							$data='';
							while (!feof($sock))$data .= fgets($sock, 1024);
							fclose($sock);
							echo "<span style='color:green;font-weight:bold;'><center>Server Online!</center></span><br />";
						}
					}
					?>
				</td>
			</tr>
		</table>
	</div>
</div>