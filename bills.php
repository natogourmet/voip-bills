#!/usr/bin/php -q
<?php
require('phpagi.php');
$agi = new AGI();
$agi->answer();
sleep(1);
$agi->exec_agi("googletts.agi,\"This is a DB connection test\",en");

// ENV VAR SETTING
require("def.inc");
$link = mysql_connect(HOST, USER, PWD);
mysql_select_db(DB, $link);

// RECEIVING ID INPUT
$agi->exec_agi("googletts.agi,\"Please type your ID number followed by the Sharp Sign\",en");
$num = $agi->get_data('beep', 3000, 20, '#');
$userId = $num['result'];

// VALIDATING USER ID INPUT
if ($userId == '') {
	$agi->exec_agi("googletts.agi,\"You didn't input any ID\",en");
	$agi->hangup();
}

// RETRIEVING BILL VALUE FROM DB
$query = 'SELECT * FROM bills WHERE user_id=' . $userId;
$result = mysql_query($query, $link);
$row = mysql_fetch_array($result);

$debt = $row['bill_value'] - $row['balance'];
$agi->exec_agi("googletts.agi,\"Your pending balance is " . $debt . " Colombian Pesos\",en");
?>