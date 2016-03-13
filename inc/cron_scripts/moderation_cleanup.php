<?php

global $db;

if(!defined('UBER') || !UBER) {
	exit;
}

$visitsCutoff = time() - 259200;
$chatlogsCutoff = time() - 1209600;

$db->Query('DELETE FROM chatlogs WHERE timestamp <= ' . $chatlogsCutoff);
$db->Query('DELETE FROM user_roomvisits WHERE entry_timestamp <= ' . $visitsCutoff);