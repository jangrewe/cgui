<?php

if(!file_exists('config.php')) {
	echo "Please copy config.php.example to config.php and adjust the settings.";
	die;
}else{
	require_once("config.php");
}
require_once("class.cgminer.php");
require_once("func.php");

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="author" content="Jan Grewe">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
		<link href="css/main.css" rel="stylesheet">
		<link rel="apple-touch-icon-precomposed" sizes="57x57" href="touch-icon-iphone-114.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="touch-icon-iphone-114.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="touch-icon-ipad-144.png">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="touch-icon-ipad-144.png">
		<link rel="icon" href="favicon.png">
		<!--[if IE]><link rel="shortcut icon" href="favicon.ico"><![endif]-->
		<title>cgminer UI</title>
	</head>
	<body>
		<div class="container" id="no-more-tables">

<?php 
foreach($rigs as $name=>$addr) {
?>
			<h3 class="info-header"><?php echo $name; ?></h3>
<?php
	$rig_api = new cgminerPHP($addr, '4028');

	$rig_summary = $rig_api->request("summary");
	$rig_config = $rig_api->request("config");
	$rig_coin = $rig_api->request("coin");

	$asc_count = $rig_config["CONFIG"]["ASC Count"];
	$pga_count = $rig_config["CONFIG"]["PGA Count"];
	$gpu_count = $rig_config["CONFIG"]["GPU Count"];
	$pool_count = $rig_config["CONFIG"]["Pool Count"];
	$coin = $rig_coin["COIN"]["Hash Method"];

if ($asc_count > 0) { 
	$asc_info = get_info('asc', $asc_count);
?>
			<h4 class="info-header">ASICs</h4>
			<table class="table table-striped table-bordered table-hover info-block">
				<thead>
					<tr>
						<th>Status</th>
						<th>ASIC</th>
						<th>Rate</th>
						<th>Temp</th>
						<th>HW Errors</th>
					</tr>
				</thead>
				<tbody>
					<?php
					for($i = 0; $i < $asc_count; $i++) {
					?>
					<tr>
						<td data-title="Status"><?php if ($asc_info[$i]["status"] == "Alive" && $asc_info[$i]["enabled"] == "Y") { ?><i class="icon-ok-sign"></i><?php } else { ?><i class="icon-remove-sign"></i><?php } ?></td>
						<td data-title="GPU"><?php echo $i + 1; ?></td>
						<td data-title="Rate"><?php echo $asc_info[$i]["hash_rate"].' '.$asc_info["hash_speed"] ?></td>
						<td data-title="Temp" class="<?php echo $asc_info[$i]['temp_class']; ?>"><?php if ($fahrenheit === true) { echo sprintf("%02.2f", (9/5) * $asc_info[$i]["temp"] + 32) . "°F"; } else { echo $asc_info[$i]["temp"] . "°C"; } ?></td>
						<td data-title="HW Errors"><?php echo $asc_info[$i]["hw_errors"]; ?></td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td class="total-text"><strong>Total:</strong></td>
						<td data-title="GPU"><?php echo $asc_count; ?></td>
						<td data-title="Rate"><?php echo $asc_info['total_rate'].' '.$asc_info['hash_speed']; ?></td>
						<td class="dont-display"></td>
						<td data-title="HW Errors"><?php echo $asc_info['total_errors']; ?></td>
					</tr>
				</tfoot>
			</table>
<?php 
}

if ($pga_count > 0) { 
	$pga_info = get_info('pga', $pga_count);
?>
			<h4 class="info-header">FPGAs</h4>
			<table class="table table-striped table-bordered table-hover info-block">
				<thead>
					<tr>
						<th>Status</th>
						<th>FPGA</th>
						<th>Rate</th>
						<th>Temp</th>
						<th>HW Errors</th>
					</tr>
				</thead>
				<tbody>
					<?php
					for($i = 0; $i < $pga_count; $i++) {
					?>
					<tr>
						<td data-title="Status"><?php if ($pga_info[$i]["status"] == "Alive" && $pga_info[$i]["enabled"] == "Y") { ?><i class="icon-ok-sign"></i><?php } else { ?><i class="icon-remove-sign"></i><?php } ?></td>
						<td data-title="FPGA"><?php echo $i + 1; ?></td>
						<td data-title="Rate"><?php echo $pga_info[$i]["hash_rate"].' '.$pga_info["hash_speed"] ?></td>
						<td data-title="Temp" class="<?php echo $pga_info[$i]["temp_class"]; ?>"><?php if ($fahrenheit === true) { echo sprintf("%02.2f", (9/5) * $pga_info[$i]["temp"] + 32) . "°F"; } else { echo $pga_info[$i]["temp"] . "°C"; } ?></td>
						<td data-title="HW Errors"><?php echo $pga_info[$i]["hw_errors"]; ?></td>
					</tr>
					<?php
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td class="total-text"><strong>Total:</strong></td>
						<td data-title="FPGA"><?php echo $pga_count; ?></td>
						<td data-title="Rate"><?php echo $pga_info['total_rate'].' '.$pga_info['hash_speed']; ?></td>
						<td class="dont-display"></td>
						<td data-title="HW Errors"><?php echo $pga_info['total_errors']; ?></td>
					</tr>
				</tfoot>
			</table>
			
<?php 
}

if ($gpu_count > 0) {
	$gpu_info = get_info('gpu', $gpu_count);
?>
			<h4 class="info-header">GPUs</h4>
			<table class="table table-striped table-bordered table-hover info-block">
				<thead>
					<tr>
						<th>Status</th>
						<th>GPU</th>
						<th>Rate</th>
						<th>Temp</th>
						<th>Fan Speed</th>
						<th>Fan Percent</th>
						<th>GPU Clock</th>
						<th>Memory Clock</th>
						<th>Intensity</th>
						<th>HW Errors</th>
					</tr>
				</thead>
				<tbody>
					<?php
					for($i = 0; $i < $gpu_count; $i++) {
					?>
					<tr>
						<td data-title="Status"><?php if ($gpu_info[$i]["status"] == "Alive" && $gpu_info[$i]["enabled"] == "Y") { ?><i class="icon-ok-sign"></i><?php } else { ?><i class="icon-remove-sign"></i><?php } ?></td>
						<td data-title="GPU"><?php echo $i + 1; ?></td>
						<td data-title="Rate"><?php echo $gpu_info[$i]["hash_rate"].' '.$gpu_info["hash_speed"] ?></td>
						<td data-title="Temp" class="<?php echo $gpu_info[$i]['temp_class']; ?>"><?php if ($fahrenheit === true) { echo sprintf("%02.2f", (9/5) * $gpu_info[$i]["temp"] + 32) . "°F"; } else { echo $gpu_info[$i]["temp"] . "°C"; } ?></td>
						<td data-title="Fan Speed"><?php echo $gpu_info[$i]["fan_speed"]; ?></td>
						<td data-title="Fan Percent" class="<?php echo $gpu_info[$i]['fan_class']; ?>"><?php echo $gpu_info[$i]["fan_percent"]; ?>%</td>
						<td data-title="GPU Clock"><?php echo $gpu_info[$i]["gpu_clock"]; ?></td>
						<td data-title="Memory Clock"><?php echo $gpu_info[$i]["mem_clock"]; ?></td>
						<td data-title="Intensity"><?php echo $gpu_info[$i]["intensity"]; ?></td>
						<td data-title="HW Errors"><?php echo $gpu_info[$i]["hw_errors"]; ?></td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td class="total-text"><strong>Total:</strong></td>
						<td data-title="GPU"><?php echo $gpu_count; ?></td>
						<td data-title="Rate"><?php echo $gpu_info['total_rate'].' '.$gpu_info['hash_speed']; ?></td>
						<td class="dont-display"></td>
						<td class="dont-display"></td>
						<td class="dont-display"></td>
						<td class="dont-display"></td>
						<td class="dont-display"></td>
						<td class="dont-display"></td>
						<td data-title="HW Errors"><?php echo $gpu_info['total_errors']; ?></td>
					</tr>
				</tfoot>
			</table>
<?php 
}

if ($pool_count > 0) { ?>
			<h3 class="info-header">Pools</h3>
			<table class="table table-striped table-bordered table-hover info-block">
				<thead>
					<tr>
						<th>Status</th>
						<th>Pool</th>
						<th>URL</th>
						<th>User</th>
						<th>Confirmed</th>
						<th>Accepted</th>
						<th>Rejected</th>
						<th>Discarded</th>
						<th>Stale</th>
					</tr>
				</thead>
				<tbody>
<?php
						$total_accepted = 0;
						$total_rejected = 0;
						$total_discarded = 0;
						$total_stale = 0;
						$total_confirmed = "N/A";
						
						for ($i = 0; $i < $pool_count; $i++) {
							$rig_pool = $rig_api->request("pools");
														
							$total_accepted += $rig_pool["POOL" . $i]["Accepted"];
							$total_rejected += $rig_pool["POOL" . $i]["Rejected"];
							$total_discarded += $rig_pool["POOL" . $i]["Discarded"];
							$total_stale += $rig_pool["POOL" . $i]["Stale"];
							
							if ($rig_pool["POOL" . $i]["Rejected"] > ((($config["Rejects"][1]) / 100) * $rig_pool["POOL" . $i]["Accepted"])) {
								$rejects_class = "error";
							} elseif ($rig_pool["POOL" . $i]["Rejected"] > ((($config["Rejects"][0]) / 100) * $rig_pool["POOL" . $i]["Accepted"])) {
								$rejects_class = "warning";
							} else {
								$rejects_class = "";
							}
							
							if ($rig_pool["POOL" . $i]["Discarded"] > ((($config["Discards"][1]) / 100) * $rig_pool["POOL" . $i]["Accepted"])) {
								$discards_class = "error";
							} elseif ($rig_pool["POOL" . $i]["Discarded"] > ((($config["Discards"][0]) / 100) * $rig_pool["POOL" . $i]["Accepted"])) {
								$discards_class = "warning";
							} else {
								$discards_class = "";
							}
							
							if ($rig_pool["POOL" . $i]["Stale"] > ((($config["Stales"][1]) / 100) * $rig_pool["POOL" . $i]["Accepted"])) {
								$stales_class = "error";
							} elseif ($rig_pool["POOL" . $i]["Stale"] > ((($config["Stales"][0]) / 100) * $rig_pool["POOL" . $i]["Accepted"])) {
								$stales_class = "warning";
							} else {
								$stales_class = "";
							}
							
							$confirmed_rewards = "N/A";
							$pool_data = parse_url($rig_pool["POOL" . $i]["URL"]);
							if (isset($apis[$pool_data["host"]])) {
								$api_data = json_decode(file_get_contents($apis[$pool_data["host"]]), true);
								if (isset($api_data["confirmed_rewards"])) {
									$confirmed_rewards = $api_data["confirmed_rewards"];
									if ($total_confirmed == "N/A") {
										$total_confirmed = $confirmed_rewards;
									} else {
										$total_confirmed += $confirmed_rewards;
									}
								}
							}
							
					?>
					<tr>
						<td data-title="Status"><?php if ($rig_pool["POOL" . $i]["Status"] == "Alive") { ?><i class="icon-ok-sign"></i><?php } else { ?><i class="icon-remove-sign"></i><?php } ?></td>
						<td data-title="Pool"><?php echo $i + 1; ?></td>
						<td data-title="URL" class="long-data"><?php echo $rig_pool["POOL" . $i]["URL"]; ?></td>
						<td data-title="User"><?php echo $rig_pool["POOL" . $i]["User"]; ?></td>
						<td data-title="Confirmed"><?php echo $confirmed_rewards; ?></td>
						<td data-title="Accepted"><?php echo $rig_pool["POOL" . $i]["Accepted"]; ?></td>
						<td data-title="Rejected" class="<?php echo $rejects_class; ?>"><?php echo $rig_pool["POOL" . $i]["Rejected"]; ?></td>
						<td data-title="Discarded" class="<?php echo $discards_class; ?>"><?php echo $rig_pool["POOL" . $i]["Discarded"]; ?></td>
						<td data-title="Stale" class="<?php echo $stales_class; ?>"><?php echo $rig_pool["POOL" . $i]["Stale"]; ?></td>
					</tr>
<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td class="total-text"><strong>Total:</strong></td>
						<td data-title="Pool"><?php echo $pool_count; ?></td>
						<td class="dont-display"></td>
						<td class="dont-display"></td>
						<td data-title="Confirmed"><?php echo $total_confirmed; ?></td>
						<td data-title="Accepted"><?php echo $total_accepted; ?></td>
						<td data-title="Rejected"><?php echo $total_rejected; ?></td>
						<td data-title="Discarded"><?php echo $total_discarded; ?></td>
						<td data-title="Stale"><?php echo $total_stale; ?></td>
					</tr>
				</tfoot>
			</table>
<?php } 
}
?>

		</div>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>