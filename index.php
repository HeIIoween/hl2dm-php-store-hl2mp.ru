<?php
session_start();
session_unset();

if( strpos($_SERVER['HTTP_USER_AGENT'],'Valve Client') === false )
	die('Only through the game');

$translate = parse_ini_file('ini/translate.ini',true);
$_SESSION['language'] = 'english';
if( isset( $translate[$_GET['language']] ) )
	$_SESSION['language'] = $_GET['language'];

extract($translate[$_SESSION['language']],EXTR_OVERWRITE);

$_SESSION['userid'] = $_SESSION['rcpt'] = intval($_GET['userid']);
$_SESSION['key'] = intval($_GET['key']);
$_SESSION['section'] = parse_ini_file('ini/section.ini',true);
$_SESSION['product'] = parse_ini_file('ini/product.ini',true);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Merchant - hl2mp.ru - Welcome</title>
<script src="js/jquery-latest.js"></script>
<script src="js/alertify.min.js"></script>
<script src="js/script.js<?php echo '?'.md5_file('js/script.js') ?>"></script>
<link rel="stylesheet" href="css/alertify.core.css" />
<link rel="stylesheet" href="css/alertify.default.css" />
<link rel="stylesheet" type="text/css" href="css/style.css?<?php echo md5_file('css/style.css'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div id="blocker" class="blocker hide"><img id="loading" class="load-img" src="images/loading.gif" /></div>
<div class="log hide" id="log"></div>
<div class="info hide" id="info"></div>
<div class="menu">
	<div class="rcpt_item" id="buyer" ><img src="images/noavatar.jpg" class="avatar"><?php echo $tr_loading; ?></div>
	<HR color="#172331"/>
	<div id="gmenu">
		<?php
		echo('<div onclick="openurl(\'product.php?favorites\');" class="menu_item select"><img src="images/favorites.jpg" width="32" height="32" style="vertical-align:middle; margin:0 7px 0 0;" >'.$tr_favorites.'</div><hr color="#172331" />');
		foreach( $_SESSION['section'] as $a => $b ) {
			echo('<div onclick="openurl(\'product.php?section='.$a.'\');" class="menu_item"><img src="cache/section/'.$a.'.jpg?sum='.md5_file('cache/section/'.$a.'.jpg').'" width="32" height="32" style="vertical-align:middle; margin:0 7px 0 0;" >'.$b[$_SESSION['language']].'</div>');
		}
		?>
	</div>
	<HR color="#172331"/>
	<div class="rcpt_item" onclick="openrcpt()" id="rcpt"><img src="images/noavatar.jpg" class="avatar"><?php echo $tr_loading; ?></div>
	<div style="width: 100%; position: absolute; bottom: 0; text-align: center;">
		<a href="https://hl2mp.ru">hl2mp.ru</a>
	</div>
</div>
<div id="content" class="content load">
</div>
</body>
</html>