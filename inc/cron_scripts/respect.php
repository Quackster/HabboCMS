<?php

global $db;

if(!defined('HOTEL') || !HOTEL) {
	exit;
}

$query = $db->prepare('UPDATE `users` SET `daily_respect_points`="3"');
$query->execute();