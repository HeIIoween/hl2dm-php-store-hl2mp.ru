<?php
session_start();

if( $_SESSION['key'] != $_SESSION['pKey'] )
	die('Bad pKey!');

include('include/functions.php');
include('include/players.php');
$translate = parse_ini_file('ini/translate.ini',true);
extract($translate[$_SESSION['language']],EXTR_OVERWRITE);

if(isset($_GET['buy'])) {
	$product = $_SESSION['product'][$_GET['buy']];
	$section = $_SESSION['section'][$product['section']][$_SESSION['language']];
	
	if($_SESSION['pFrag'] < $product['price'])
		die('<span class="icon-buy-false">&#x2718;</span>'.$section.': '.$product[$_SESSION['language']].', '.$tr_nehvataet);
	
	$result = NULL;
	if( isset($product['classname']) ) {
		if($product['count'] == 1)
			$product['count'] = 0;
		
		$result = rconCommand('sv_merchant_give #'.$_SESSION['rcpt'].' "'.$product['classname'].'" "'.$product['count'].'"');
	}
	
	if( isset($product['cmd']) )
		$result = rconCommand($product['cmd'].' #'.$_SESSION['rcpt'].' "'.$product['value'].'" "'.$product['limit'].'" "'.$product['weapon'].'" "'.$product['health'].'"');
	
	if( strpos($result, 'chant_0') == true )
		die('<span class="icon-buy-false">&#x2718;</span>'.$section.': '.$product[$_SESSION['language']].', '.$tr_already);
	
	if( $product['price'] == 0 )
		$product['price'] = $tr_free;
	else
		rconCommand('sv_merchant_decrement_frag '.$_SESSION['userid'].' '.$product['price']);

	echo('<span class="icon-buy-true">&#x2714;</span>'.$section.': '.$product[$_SESSION['language']].', '.$tr_spent.': '.$product['price']);
}
?>
