<?php

global $db;

if(!defined('UBER') || !UBER) {
	exit;
}

$db->Query('UPDATE users SET credits = "3000" WHERE credits < 3000');
Core::mus('updateCredits', 'ALL');