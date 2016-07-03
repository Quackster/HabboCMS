<?php

global $db;

if (!defined('HOTEL') || !HOTEL) {
    exit;
}

$query = $db->prepare('SELECT `status` FROM `server_status` LIMIT 1');
$query->execute();
$curStat = $query->fetch(PDO::FETCH_ASSOC);
$curStat = (int)$curStat['status'];

if ($curStat == 1) {
    $query = $db->prepare('SELECT `stamp` FROM `server_status` LIMIT 1');
    $query->execute();
    $stamp = $query->fetch(PDO::FETCH_ASSOC);
    $stamp = (float)$stamp['stamp'];

    $diff = time() - $stamp;

    if ($diff >= 300) {
        $query = $db->prepare('UPDATE `server_status` SET `status`="2" LIMIT 1');
        $query->execute();
    }
}