<?php
session_start();

if( $_SESSION['key'] != $_SESSION['pKey'] )
	die('Bad pKey!');

include('include/players.php');

$translate = parse_ini_file('ini/translate.ini',true);
extract($translate[$_SESSION['language']],EXTR_OVERWRITE);

foreach( $_SESSION['product'] as $section => $key ) {
	if( isset( $key['team'] ) && $_SESSION['pTeam'] != $key['team'] || isset( $_GET['favorites'] ) && !isFavorite( $section ) || !empty( $_GET['section'] ) && $key['section'] != $_GET['section'] )
		continue;
	
	$img = 'cache/nophoto.png';
	if( file_exists('cache/'.$key['section'].'/'.$section.'.jpg') )
		$img = 'cache/'.$key['section'].'/'.$section.'.jpg';
	
	$key['price'] .= 'p';
	if( $key['price'] == 0 )
		$key['price'] = $tr_free;
	$fvdiv = '<div onClick="return tooglefv(this, \''.$section.'\', '.isset( $_GET['favorites'] ).' )" class="favorites-icon '.( isFavorite( $section ) == true ? 'select' : '' ).'">'.( isFavorite( $section ) == true ? '&#x2605;' : '&#x2606;' ).'</div>';
	echo('<div class="product"><div onclick="return sendget(\'buy\',\''.$section.'\')" class="overlay"></div><img src="'.$img.'?sum='.md5_file($img).'"><span>'.$key[$_SESSION['language']].' - '.$key['price'].'</span>'.$fvdiv.'</div>'."\n");
}
?>
