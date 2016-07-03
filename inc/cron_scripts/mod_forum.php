<?php

global $db;

if (!defined('HOTEL') || !HOTEL) {
    exit;
}

$cutoff = time() - 604800;

$get = $db->prepare('SELECT `id` FROM `moderation_forum_threads` WHERE `timestamp`<=:cutOff');
$get->execute(array(
    ':cutOff' => $cutoff,
));

$getData = $get->fetchAll(PDO::FETCH_ASSOC);

foreach ($getData as $topic) {
    $query = $db->prepare('DELETE FROM `moderation_forum_threads` WHERE `id`=:topicId LIMIT 1');
    $query->execute(array(':topicId' => $topic['id']));
    $query = $db->prepare('DELETE FROM `moderation_forum_replies` WHERE `thread_id`=:topicId');
    $query->execute(array(':topicId' => $topic['id']));
}