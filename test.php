<?php
include('include/functions.php');
print '<pre>';
print rconCommand('stats');
print '</pre>';
print '<pre>';
print rconCommand('status');
print '</pre>';
?>