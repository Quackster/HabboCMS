<?php

global $db;

if(!defined('UBER') || !UBER) {
	exit;
}

$cutoff = time() - 604800;

$get = $db->Query('SELECT id FROM moderation_forum_threads WHERE timestamp <= ' . $cutoff);

while($topic = $db->FetchAssoc($get)) {
	$db->Query('DELETE FROM moderation_forum_threads WHERE id = "' . $topic['id'] . '" LIMIT 1');
	$db->Query('DELETE FROM moderation_forum_replies WHERE thread_id = "' . $topic['id'] . '"');
}