<?php

define('TAB_ID', 1);
define('PAGE_ID', 1);

require_once 'system' . DIRECTORY_SEPARATOR . 'global.php';

if(!LOGGED_IN)
{
	header('Location: ' . WWW);
	exit;
}

// Initialize template system
$tpl = new Tpl();

// Initial variables
$tpl->SetParam('page_title', 'Home');

// Generate page header
$tpl->AddTemplate('head-init');
$tpl->AddIncludeSet('generic');
$tpl->AddIncludeFile(new IncludeFile('text/css', '%www%/css/personal.css', 'stylesheet'));
$tpl->AddIncludeFile(new IncludeFile('text/css', '%www%/css/myhabbo/control.textarea.css', 'stylesheet'));
$tpl->AddIncludeFile(new IncludeFile('text/css', '%www%/css/minimail.css', 'stylesheet'));
$tpl->AddIncludeFile(new IncludeFile('text/javascript', '%www%/js/minimail.js'));
$tpl->AddIncludeFile(new IncludeFile('text/javascript', '%www%/js/habboclub.js'));
$tpl->WriteIncludeFiles();
$tpl->AddTemplate('head-overrides-generic');
$tpl->AddTemplate('head-bottom');

// Generate generic top/navigation/login box
$tpl->AddTemplate('generic-top');

// Column 1
$tpl->Write('<div id="column1" class="column">');

// Me/infofeed widget
$tpl->SetParam('look', $users->GetUserVar(USER_ID, 'look'));
$tpl->SetParam('motto', clean($users->GetUserVar(USER_ID, 'motto')));
$tpl->SetParam('creditsBalance', intval($users->GetUserVar(USER_ID, 'credits')));
$tpl->SetParam('pixelsBalance', intval($users->GetUserVar(USER_ID, 'activity_points')));
$tpl->SetParam('lastSignedIn', $users->GetUserVar(USER_ID, 'last_online'));
$tpl->SetParam('respects', intval($users->GetUserVar(USER_ID, 'respect')));
$tpl->SetParam('clubStatus', (($users->HasClub(USER_ID)) ? '<a href="%www%/credits/club">'. $users->GetClubDays(USER_ID) .'</a> Days' : '<a href="%www%/credits/club">Join Club &raquo;</a>'));
$tpl->AddTemplate('comp-me');

$tpl->AddTemplate('comp-minimail');
$tpl->AddTemplate('comp-hotcampaigns');

$tpl->Write('</div>');

// Column 2
$tpl->Write('<div id="column2" class="column">');
$tpl->AddTemplate('comp-news');
$tpl->AddTemplate('comp-usertags');
$tpl->AddTemplate('comp-twitter');
$tpl->Write('</div>');

// Column 3
$tpl->AddTemplate('generic-column3');

// Footer
$tpl->AddTemplate('footer');

// Output the page
echo $tpl;