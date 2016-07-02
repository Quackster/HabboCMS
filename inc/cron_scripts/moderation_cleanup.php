<?php

global $db;

if(!defined('HOTEL') || !HOTEL) {
	exit;
}

$visitsCutoff = time() - 259200;
$chatlogsCutoff = time() - 1209600;

$query = $db->prepare('DELETE FROM `chatlogs` WHERE `timestamp`<=:chatlogsCutoff');
$query->execute(array(
    ':chatlogsCutoff' => $chatlogsCutoff,
));

$query = $db->prepare('DELETE FROM `user_roomvisits` WHERE `entry_timestamp`<=:visitsCutoff');
$query->execute(array(
    ':visitsCutoff' => $visitsCutoff,
));
