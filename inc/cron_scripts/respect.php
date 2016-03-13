<?php

global $db;

if(!defined('UBER') || !UBER) {
	exit;
}

$db->Query('UPDATE users SET daily_respect_points = "3"');