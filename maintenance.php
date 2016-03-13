<?php

define('IN_MAINTENANCE', true);
require_once 'system' . DIRECTORY_SEPARATOR . 'global.php';

if(!defined('FORCE_MAINTENANCE') || !FORCE_MAINTENANCE)
{
	header('Location: ' . WWW);
	exit;
}
elseif(LOGGED_IN && Users::HasFuse(USER_ID, 'fuse_ignore_maintenance'))
{
	header('Location: ' . WWW);
	exit;
}

$tpl = new Tpl();
$tpl->AddGeneric('page-maintenance');
echo $tpl;