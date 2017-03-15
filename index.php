<?php
	$catagories = parse_ini_file("./groups.ini");

	require_once("/etc/freepbx.conf");
	$mysqli = new mysqli($amp_conf['AMPDBHOST'], $amp_conf['AMPDBUSER'], $amp_conf['AMPDBPASS'], $amp_conf['AMPDBNAME']);
	function DBQuery($query){
		global $mysqli;
		if (!$sqlResult = mysqli_query($mysqli, $query)) {
			trigger_error('DB query failed: ' . $mysqli->error . "\nquery: " . $query);
			return false;
		} else {
			$all_rows = array();
			while ($row = mysqli_fetch_assoc($sqlResult)) {
				$all_rows[] = $row;
			}
			return $all_rows;
		}
	}
	

	$all_users   = DBQuery("select * from users");
	array_multisort($all_users);
	
	$catagories_array = Array();
	foreach ($catagories as $key => $c) {
		$count = 0;
		foreach ($all_users as $u){
			if (preg_match($c, $u['extension'])){
				$catagories_array[$key][$count]['name'] = $u['name'];
				$catagories_array[$key][$count]['extension'] = $u['extension'];
				$count++;
			}
		}
	}
?>



<html>
	<body>
		<table>
			<?php
				$grand_total = 0;
				foreach ($catagories_array as $key => $c) {
					print "<tr>";
						print "<th>";
							print $key;
						print "</th>";
					print "</tr>";
					$total = 0;
					foreach ($c as $x) {
						print "<tr>";
							print "<td>";
								print $x['name'];
							print "</td><td>";
								print $x['extension'];
							print "</td>";
						print "</tr>";
						$total++;
						$grand_total++;
					}
					//print "<tr><td><b>Total:</b></td><td><b>$total</b></td></tr>";
					print "<tr><td>&nbsp;</td></tr>";
				}
				//print "<tr><th>Grand Total: $grand_total</th></tr>";
			?>
		</table>
	</body>
</html>













