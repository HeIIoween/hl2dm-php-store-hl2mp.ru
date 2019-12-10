<?php
function convertarray($ini) {
	foreach($ini as $section => $array) {
		$result .= "[\"$section\"]\r\n";
		$count = count($array);
		foreach($array as $key => $value) {
			if($key != 'section')
				$result .= "$key = \"$value\"\r\n";

			if($count-- <= 1)
				$result .= "\r\n";
		}
	}
	return $result;
}

function writeini($file, $section, $key, $value) {
	$ini = parse_ini_file($file,true);
	if(isset($ini[$section][$key]))
		unset($ini[$section][$key]);

	if(!isset($ini[$section]))
		$ini[$section] = array($key => $value);
	else
		$ini[$section] += array($key => $value);

	$ini_file = fopen($file, 'w+');
	fwrite($ini_file, convertarray($ini));
	fclose($ini_file);
}

function deletesectionini($file, $section) {
	$ini = parse_ini_file($file,true);
	if(isset($ini[$section]))
		unset($ini[$section]);
	else
		return false;

	$ini_file = fopen($file, 'w+');
	fwrite($ini_file, convertarray($ini));
	fclose($ini_file);
	return true;
}
?>
