<?php

define('BAN_PAGE', true);
require_once 'system' . DIRECTORY_SEPARATOR . 'global.php';

$ban = null;

if(Users::IsIpBanned(USER_IP))
	$ban = Users::GetBan('ip', USER_IP, true);

if(LOGGED_IN && Users::IsUserBanned(USER_NAME))
	$ban = Users::GetBan('user', USER_NAME, true);

if ($ban == null)
{
	header('Location: '. WWW);
	exit;
}

$tpl = new Tpl();

$tpl->SetParam('page_title', 'Home');

$tpl->AddTemplate('head-init');

$tpl->AddIncludeSet('process-template');
$tpl->WriteIncludeFiles();

$tpl->AddTemplate('head-overrides-process');
$tpl->AddTemplate('head-bottom');
$tpl->AddTemplate('process-template-top');

$tpl->SetParam('reason', clean($ban['reason'], false, true));
$tpl->SetParam('end', date('d F, Y', $ban['expire']));
$tpl->AddTemplate('comp-banned');

$tpl->AddTemplate('process-template-bottom');
$tpl->AddTemplate('footer');

$tpl->Output();