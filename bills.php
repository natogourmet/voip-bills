#!/usr/bin/php -q
<?php
require('phpagi.php');
$agi = new AGI();
$agi->answer();
sleep(1);
$agi->exec_agi("googletts.agi,\"Welcome to the Bill Paying System.\",en");

// ENV VAR SETTING
// require("def.inc");
define("HOST","localhost");
define("USER","root");
define("PWD","root");
define("DB","Bills");
$link = mysql_connect(HOST, USER, PWD);
mysql_select_db(DB, $link);

// READING ID INPUT
$agi->exec_agi("googletts.agi,\"Please type your ID number followed by the Sharp Sign\",en");
$num = $agi->get_data('beep', 3000, 20, '#');
$userId = $num['result'];

// VALIDATING USER ID INPUT
if ($userId == '') {
	$agi->exec_agi("googletts.agi,\"You didn't input any ID\",en");
	$agi->hangup();
}

// RETRIEVING BILL VALUE FROM DB
$query = "SELECT * FROM bills WHERE user_id=$userId";
$result = mysql_query($query, $link);
$row = mysql_fetch_array($result);

// CALCULATING THE PENDING BALANCE
$balance = $row['balance'];

// READING AMOUNT TO PAY INPUT
$amount = '';
while (true) {
	$agi->exec_agi("googletts.agi,\"Your balance is $balance dollars\",en");
	sleep(1)
	$agi->exec_agi("googletts.agi,\"Please type the amount of money you want to pay\",en");
	$num = $agi->get_data('beep', 3000, 20, '#');
	$amount = $num['result'];

	// VALIDATING AMOUNT TO PAY INPUT
	if ($amount == '') {
		$agi->exec_agi("googletts.agi,\"You didn't input any amount. Please try again\",en");
	} else if ($amount > $balance) {
		$agi->exec_agi("googletts.agi,\"The amount you provided is higher than the amount you owe. Please try again\",en");
	} else {
		$agi->exec_agi("googletts.agi,\"The amount you are going to pay is $amount dollars. Do you want to change this amount? Type 1 for Yes or Type 2 for No.\",en");
		$num = $agi->get_data('beep', 3000, 20, '#');
		$confirmation = $num['result'];
		if ($confirmation == 2) {
			break;
		}
	}
}

// CALCULATING NEW BALANCE
$newBalance = $balance - $amount;

// UPDATING THE NEW BALANCE TO DB
$query = "UPDATE bills SET balance=$balance WHERE user_id=$userId"
$updateResult = mysql_query($query, $link);

// PROVIDING END MESSAGE BASED ON UPDATE RESULT
if ($updateResult) {
	$agi->exec_agi("googletts.agi,\"You have successfully payed $amount dollars to your debt\",en");
	$agi->exec_agi("googletts.agi,\"Your new balance is $newBalance dollars\",en");
	$agi->exec_agi("googletts.agi,\"Thanks for using the Bill Paying System\",en");
} else {
	$agi->exec_agi("googletts.agi,\"There was an error during the transaction. Please try again later\",en");\
}
?>