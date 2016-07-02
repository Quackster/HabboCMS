<?php

global $db;

if(!defined('HOTEL') || !HOTEL) {
	exit;
}

$query = $db->prepare('SELECT `status` FROM `server_status` LIMIT 1');
$query->execute();
$curStat = $query->fetch(PDO::FETCH_ASSOC);
$curStat = $curStat['status'];

$curStat = (int) $db->Result($db->Query('SELECT status FROM server_status LIMIT 1'), 0);

if($curStat == 1) {
	$stamp = $db->Result($db->Query('SELECT stamp FROM server_status LIMIT 1'), 0);
	$diff = time() - $stamp;
	
	if($diff >= 300) {
		$db->Query('UPDATE server_status SET status = "2" LIMIT 1');
	}
}