<?php
include('include/ini.php');
$translate = parse_ini_file('ini/translate.ini',true);
extract($translate[$_SESSION['language']],EXTR_OVERWRITE);

$favorites = parse_ini_file('ini/players.ini',true);

$fullfv = $favorites['default']['favorites'];
if( isset( $favorites[$_SESSION['steamid']] ) ) {
	$fullfv = $favorites[$_SESSION['steamid']]['favorites'];
}

function isFavorite( $sName ) {
	global $fullfv;
	foreach( explode(':sp:',$fullfv) as $key ) {
		if( $key == $sName )
			return true;
	}
	return false;
}

if( !empty( $_GET['addfv'] ) ) {
	$msg = $_SESSION['product'][$_GET['addfv']][$_SESSION['language']];
	if( !empty( $msg ) ) {
		writeini('ini/players.ini', $_SESSION['steamid'], 'favorites', $fullfv.$_GET['addfv'].':sp:');
		echo($msg.' '.$tr_fvadd);
	}
	die;
}

if( !empty( $_GET['delfv'] ) ) {
	$msg = $_SESSION['product'][$_GET['delfv']][$_SESSION['language']];
	if( !empty( $msg ) ) {
		$result = str_replace($_GET['delfv'].':sp:', '', $fullfv);
		writeini('ini/players.ini', $_SESSION['steamid'], 'favorites', $result);
		echo($msg.' '.$tr_fvdel);
	}
	die;
}
?>