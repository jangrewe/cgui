<?php

function get_info($type, $count) {

	global $rig_api;
	global $coin;

	$output = array();
	$output['total_rate'] = 0;
	$output['total_errors'] = 0;
	
	for ($i = 0; $i < $count; $i++) {
	
		$request = $rig_api->request($type."|" . $i);
		$info = $request[strtoupper($type) . $i];
		$average_rate = 0;
		if (isset($info["MHS 5s"])) {
			$average_rate = $info["MHS 5s"];
		} elseif (isset($info["MHS 1s"])) {
			$average_rate = $info["MHS 1s"];
		}
		
		$output[$i]['enabled'] 		= $info['Enabled'];
		$output[$i]['status'] 		= $info['Status'];
		$output[$i]['temp'] 		= $info['Temperature'];
		$output[$i]['hw_errors'] 	= $info["Hardware Errors"];
		if($type == 'gpu') {
			$output[$i]['fan_speed'] 	= $info["Fan Speed"];
			$output[$i]['fan_percent'] 	= $info["Fan Percent"];
			$output[$i]['gpu_clock'] 	= $info["GPU Clock"];
			$output[$i]['mem_clock'] 	= $info["Memory Clock"];
			$output[$i]['intensity'] 	= $info["Intensity"];
		}else if($type == 'pga') {
			$output[$i]['freq'] 		= $info["Frequency"];
		}
		
		
		if ($coin == "scrypt") {
			$output[$i]['hash_rate'] = $average_rate * 1000;
			$output['hash_speed'] = "kh/s";
		} elseif ($coin == "sha256") {
			$output[$i]['hash_rate'] = $average_rate;
			$output['hash_speed'] = "Mh/s";
		}
		
		$output['total_rate'] 		+= $output[$i]['hash_rate'];
		$output['total_errors'] 	+= $info["Hardware Errors"];
		
		if ($info["Temperature"] > 0 && $info["Temperature"] >= $config["Temperature"][1]) {
			$output[$i]['temp_class'] = "error";
		} elseif ($info["Temperature"] > 0 && $info["Temperature"] >= $config["Temperature"][0]) {
			$output[$i]['temp_class'] = "warning";
		} else {
			$output[$i]['temp_class'] = "";
		}
		
		if($type == 'gpu') {
			if ($info["Fan Percent"] >= $config["Fan"][1]) {
				$output[$i]['fan_class'] = "error";
			} elseif ($info["fan_percent"] >= $config["Fan"][0]) {
				$output[$i]['fan_class'] = "warning";
			} else {
				$output[$i]['fan_class'] = "";
			}
		}
	}
	/*
	echo "<pre>";
	print_r($info);
	print_r($output);
	echo "</pre>";
	*/
	return $output;
	
}

?>