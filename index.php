<?php

define('BAN_PAGE', true);
require_once 'inc/global.php';

if(LOGGED_IN) {
	header('Location: ' . WWW . '/me');
	exit;
}

$tpl = new Tpl();

$tpl->setParam('page_title', 'Create your avatar, decorate your room, chat and make new friends.');
$tpl->setParam('credentials_username', '');
$tpl->setParam('login_result', '');

$tpl->addTemplate('head-init');

$tpl->addIncludeSet('frontpage');
$tpl->writeIncludeFiles();

$tpl->addTemplate('head-overrides-fp');
$tpl->addTemplate('head-bottom');

if(isset($_GET['logout'])) {
	$loginResult = '<div id="loginerrorfieldwrapper">
						<div id="loginerrorfield" style="background-color:#4BB601">
							<div>Du wurdest erfolgreich ausgeloggt.</div>
						</div>
					</div>';
	$tpl->setParam('login_result', $loginResult);
}

if(isset($_POST['credentials_username']) && isset($_POST['credentials_password']))
{
	$tpl->setParam('credentials_username', $_POST['credentials_username']);
	
	$credUser = filter($_POST['credentials_username']);
	$credPass = Core::hash($_POST['credentials_password']);
	
	$errors = array();
	
	if(strlen($_POST['credentials_username']) < 1) {
		$errors[] = 'Please enter your username';
	}
	
	if(strlen($_POST['credentials_password']) < 1) {
		$errors[] = 'Please enter your password';
	}
	
	if(count($errors) == 0) {
		$check = Users::validateLogin($credUser, $credPass);
		if($check[0]) {
			if(isset($_POST['page'])) {
				$reqPage = filter($_POST['page']);
				$pos = strrpos($reqPage, WWW);
				
				if($pos === false || $pos != 0) {
					die('<b>Security warning!</b> A malicious request was detected that tried redirecting you to an external site. Please proceed with caution, this may have been an attempt to steal your login details. <a href="' . WWW . '">Return to site</a>');
				} else {
					$_SESSION['page-redirect'] = $reqPage;
				}
			}
			
			if(!$check[1]) {
				$_SESSION['USER_N'] = Users::getUserVar(Users::name2id($credUser), 'username');
				$_SESSION['page-redirect'] = 'me';
			} else {
				$_SESSION['USER_N'] = Users::getUserVar(Users::email2id($credUser), 'username');
				if($check[2] > 1) {
					$_SESSION['page-redirect'] = 'identity/avatars';
				}
			}
			$_SESSION['USER_H'] = $credPass;
			
			if(isset($_POST['_login_remember_me'])) {
				$_SESSION['set_cookies'] = true;
			}
			
			$_SESSION['jjp']['login']['user'] = $_SESSION['USER_N'];
			$_SESSION['jjp']['login']['email'] = Users::getUserVar(Users::name2id($_SESSION['jjp']['login']['user']), 'mail');
			$_SESSION['jjp']['login']['name'] = Users::getUserVar(Users::name2id($_SESSION['jjp']['login']['user']), 'real_name');
			header('Location: ' . WWW . '/mail_check');
			exit;
		} else {
			$errors[] = 'Incorrect password';
		}
	}
	
	if(count($errors) > 0) {
		$loginResult = '<div id="loginerrorfieldwrapper">
							<div id="loginerrorfield"><div>';
		
		foreach($errors as $err) {
			$loginResult .= $err;
		}
		
		$loginResult .= '</div>
					</div>
				</div>';
		
		$tpl->setParam('login_result', $loginResult);
	}
}

$tpl->addTemplate('page-fp');

echo $tpl;