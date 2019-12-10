$(function() {
	$("#buyer").load('pinfo.php?buyer', function() {
		openurl('product.php?favorites');
		$("#rcpt").load('pinfo.php?rcpt');
	});
	
    $('#gmenu').on('click', 'div', function(event) {
		$(this).addClass('select').siblings().removeClass('select');   
		event.preventDefault();
    });
});

function openurl( url ) {
	$('#content').empty();
	$('#content').addClass('load');
	$('#content').load(url, function() {
		$('#content').removeClass('load');
		$('#content').scrollTop('0px');
	});
}

function sendget( str1, str2 ) {
	$('#blocker').removeClass('hide');
	$.ajax({
		type: 'GET',
		url: 'get.php'+'?'+str1+'='+str2,
		success: function(data) {
			if( str1 == 'buy' )
				$('#buyer-balance').load('pinfo.php?buyer-balance');
			
			$('#blocker').addClass('hide');
			notify(data);
		}
	});
}

function tooglefv(current, str, loadurl) {
	if( current.classList.contains('select') ) {
		current.classList.remove('select');
		current.innerHTML = '&#x2606;';
		sendget( 'delfv', str );
		if( loadurl ) {
			openurl('product.php?favorites');
		}
	}
	else {
		current.classList.add('select');
		current.innerHTML = '&#x2605;';
		sendget( 'addfv', str );
	}
}

function openrcpt(  ) {
	$('#blocker').removeClass('hide');
	$("#info").load('pinfo.php?player', function() {
		$('#loading').toggle();
		$('#info').removeClass('hide');
	});
}

function setrcpt( rcpt ) {
	$('#info').addClass('hide');
	$('#loading').toggle();
	$( '#rcpt' ).load( 'pinfo.php?rcpt='+rcpt, function() {
		$('#blocker').addClass('hide');
	});
}

var g_timer;

function notify( msg ) {
	if( g_timer )
		clearTimeout(g_timer);

	szMsg = document.getElementById('log');
	szMsg.innerHTML += msg+'<br />';
	szMsg.scrollTop = szMsg.scrollHeight;
	//$('#log').removeClass('hide');
	g_timer = setTimeout(function() {
		szMsg.innerHTML = '';
		//$('#log').addClass('hide');
	}, 3500);
	alertify.log(msg);
}