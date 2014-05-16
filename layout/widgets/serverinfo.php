<div class="well">
		<h2>Server Information</h2>
		<div class="inner">
		<tbody>
			<table class="table table-condensed">
				<tr>
					<td><center>
						<?php
						$status = true;
						if ($config['status']['status_check']) {
							@$sock = fsockopen ($config['status']['status_ip'], $config['status']['status_port'], $errno, $errstr, 1);
							if(!$sock) {
								echo "<div class=\"label label-danger\">Server Offline</div>";
								$status = false;
							}
							else {
								$info = chr(6).chr(0).chr(255).chr(255).'info';
								fwrite($sock, $info);
								$data='';
								while (!feof($sock))$data .= fgets($sock, 1024);
								fclose($sock);
								echo "<div class=\"label label-success\">Server Online!</div>";
							}
						}
						?>
					</center></td>
				</tr>
				<tr>
					<td>
						<?php if ($status) {
						?>
							<a href="onlinelist.php"><?php echo user_count_online();?> player online</a>
						<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<td>
						<a href="register.php"><?php echo user_count_accounts();?> registered accounts</a>
					</td>
				</tr>
			</table>
		</tbody>
		</div>
</div>