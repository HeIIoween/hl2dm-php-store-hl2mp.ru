<?php
include('config.php');
include('rcon.class.php');

function rconCommand( $command ) {
	global $ip, $port, $pwd;
	$rcon = new RCon($ip, $port, $pwd);
	return $rcon->command($command);
}

function trim_array( &$value ) {
	$value = trim($value);
}

function clientinfo( $userid ) {
	$array = explode("\n",rconCommand("sv_merchant_client_info $userid"));
	array_walk($array, 'trim_array');
	$pos = 0;
	$client=array();
	foreach($array as $value) {
		if($value == "pName") {
			$pos++;
			$client[$value] = $array[$pos];
			continue;
		}
		if($value == "pFrag") {
			$pos++;
			$client[$value] = $array[$pos];
			continue;
		}
		if($value == "pAdmin") {
			$pos++;
			$client[$value] = $array[$pos];
			continue;
		}
		if($value == "pKey") {
			$pos++;
			$client[$value] = $array[$pos];
			continue;
		}
		if($value == "pTeam") {
			$pos++;
			$client[$value] = $array[$pos];
			continue;
		}
		$pos++;
	}
	return $client;
}

function clientlist() {
	$array = explode("\n",rconCommand("sv_merchant_client_info @all"));
	array_walk($array, 'trim_array');
	$pos = 0;
	$client=array();
	foreach($array as $value) {
		$pos++;
		if($value == "pName") {
			$name = $array[$pos];
			$client[$name] = $array[$pos+2];
		}
	}
	return $client;
}

function IsValidPlayer( $userid ) {
	$pArray = clientlist();
	foreach($pArray as $name => $key )
		if($key == $userid)
			return true;

	return false;
}

function GetBalance() {
	$pArray = clientinfo( $_SESSION['userid'] );
	return $_SESSION['pFrag'] = $pArray['pFrag'];
}
?>
