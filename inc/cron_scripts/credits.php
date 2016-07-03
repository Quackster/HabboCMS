<?php

global $db;

if (!defined('HOTEL') || !HOTEL) {
    exit;
}

$query = $db->prepare('UPDATE `users` SET `credits`="3000" WHERE `credits` < 3000');
$query->execute();

Core::mus('updateCredits', 'ALL');