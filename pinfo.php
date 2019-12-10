<?php
session_start();
include('include/functions.php');
include('include/steam.php');
$translate = parse_ini_file('ini/translate.ini',true);
extract($translate[$_SESSION['language']],EXTR_OVERWRITE);

function GetPlayer( $player = 0, $itbuyer = 0 ) {
	global $tr_rcpt, $tr_balance;
	$array = explode("\n",rconCommand('status'));
	$size = count($array);
	for( $i = 1; $i <= $size; $i++ ) {
		$start = strpos($array[$i],'[') + 1;
		$end =  strpos($array[$i],']');
		$steamid = substr( $array[$i], $start, $end-$start);
		
		if( strpos($steamid,'BOT') )
			continue;
		
		$start = strpos($array[$i],'"') + 1;
		$end =  strrpos($array[$i],'"');
		$name = substr( $array[$i], $start, $end-$start);
		$name = htmlspecialchars( $name );
		
		$start = strpos($array[$i],'#') + 1;
		$end =  strpos($array[$i],'"');
		$userid = substr( $array[$i], $start, $end-$start);
		$userid = trim($userid);
		
		if( strpos($array[$i],'active') === false )
			continue;
		
		if( $player > 0 && $player != $userid )
			continue;
		
		$img = 'images/noavatar.jpg';
		$data = GetPlayerDataXML( $steamid );
		
		if( isset( $data->steamID ) && isset( $data->avatarIcon ) )
			$img = $data->avatarIcon;
		
		if( $itbuyer ) {
			$pArray = clientinfo( $_SESSION['userid'] );
			$_SESSION['steamid'] = $steamid;
			$_SESSION['pName'] = $pArray['pName'];
			$_SESSION['pFrag'] = $pArray['pFrag'];
			$_SESSION['pKey'] = $pArray['pKey'];
			$_SESSION['pTeam'] = $pArray['pTeam'];
			echo('<img src="'.$img.'" class="avatar"><span class="right-text">'.$_SESSION['pName'].'<br /><font color="#FFB000"><b>'.$tr_balance.': <span id="buyer-balance">'.$_SESSION['pFrag'].'</span>p</b></font></span>');
		}
		else if( $player > 0 )
			echo('<img src='.$img.' class="avatar"><span class="right-text"><font color="#EBEBEB"><b>'.$tr_rcpt.'</b></font><br />'.$name.'</span><div class="icon-change-rcpt">&#x27a4;</div>');
		else
			echo('<div class="rcpt_item '.($userid == $_SESSION['rcpt'] ? 'current' : '').'" onclick="setrcpt( '.$userid.' )"><img src="'.$img.'" class="avatar">'.$name.'</div>');
	}
}

if( isset( $_GET['player'] ) ) {
	GetPlayer( $_GET['player'] );
}

if( isset( $_GET['rcpt'] ) ) {
	if( $_GET['rcpt'] > 0 && IsValidPlayer( $_GET['rcpt'] ) )
		$_SESSION['rcpt'] = intval( $_GET['rcpt'] );
	GetPlayer( $_SESSION['rcpt'] );
}

if( isset( $_GET['buyer'] ) ) {
	GetPlayer( $_SESSION['userid'], true );
}

if( isset( $_GET['buyer-balance'] ) ) {
	print GetBalance();
}
?>
