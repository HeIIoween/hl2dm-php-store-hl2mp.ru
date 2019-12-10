<?php
function toCommunityID($id) {
	if( preg_match('/^STEAM_/', $id) ) {
		$parts = explode(':', $id);
		return bcadd(bcadd(bcmul($parts[2], '2'), '76561197960265728'), $parts[1]);
	}
	$parts = explode(':', $id);
	return bcadd($parts[2], '76561197960265728');
}

function GetPlayerDataXML( $steamid ) {
	return simplexml_load_file( 'https://steamcommunity.com/profiles/'.toCommunityID($steamid).'/?xml=1' );
}
?>