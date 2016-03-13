<?php

require_once 'inc/global.php';
require_once ROOT . '/inc/class.recaptchalib.php';

if(!LOGGED_IN)
{
	header('Location: '. WWW);
	exit;
}

// Initialize template system
$tpl = new Tpl();

// Errors
$tpl->setParam('errors', '');

if(isset($_GET['errors']))
{
	$error = '<div id="error-messages-container" style="margin: 5px; margin-top: 10px;">
				<div class="error-messages-holder">
					<h3>Change some information, And try again.</h3>
					<ul>
						<li><p class="error-message">'. $_GET['errors'] .'.</p></li>
					</ul>
				</div>
			</div>';
	
	$tpl->setParam('errors', $error);
}
$type = $_GET['type'];

// Initial variables
$tpl->setParam('page_title', 'Identity');

// Generate page header
$tpl->addTemplate('head-init');
$tpl->addIncludeSet('identity');

if($type == 'password')
{
	$tpl->addIncludeFile(new IncludeFile('text/css', '%www%/css/changepassword.css', 'stylesheet'));
	$tpl->addIncludeFile(new IncludeFile('text/css', '%www%/css/embeddedregistration.css', 'stylesheet'));
}
$tpl->addIncludeFile(new IncludeFile('text/css', '%www%/css/identity_settings.css', 'stylesheet'));

if($type == 'avatars' || $type == 'add_avatar')
{
	$tpl->addIncludeFile(new IncludeFile('text/css', '%www%/css/avatarselection.css', 'stylesheet'));
}
$tpl->writeIncludeFiles();
$tpl->addTemplate('head-bottom');

// Habbo name check
$tpl->addTemplate('check-name');

switch ($type)
{
	case 'avatars':
	
		$db->Query('UPDATE users SET real_name = "' . $_SESSION['jjp']['login']['name'] . '" WHERE mail = "' . $_SESSION['jjp']['login']['email'] . '"');
		$tpl->AddTemplate('identity-avatars');
		break;
	
	case 'add_avatar':
	
		$tpl->AddIncludeFile(new IncludeFile('text/css', '%www%/css/avatarselection.css', 'stylesheet'));
		$tpl->AddTemplate('identity-add-avatars');
		break;
	
	case 'add_avatar_add':
	
		$userP = $_SESSION['USER_H'];
		$userL = filter($_POST['bean_look']);
		$userN = filter($_POST['bean_avatarName']);
		$userE = $_SESSION['jjp']['login']['email'];
		$gender = filter($_POST['bean_gender']);
		
		if(strlen($userN) < 1 && strlen($userN) > 32)
			$errors = 'Your name must be between 1 and 32 characters';
		elseif($users->IsNameTaken($userN))
			$errors = 'This name is already in use';
		elseif($users->IsNameBlocked($userN))
			$errors = 'This name is blocked by habbo staff';
		elseif(!$users->IsValidName($userN))
			$errors = 'This name is not valid';
		
		if(!isset($errors))
		{
			$users->add($userN, $userP, $userE, 1, $userL, $gender);
			$db->Query('UPDATE users SET real_name = "' . $_SESSION['jjp']['login']['name'] . '" WHERE mail = "' . $_SESSION['jjp']['login']['email'] . '"');
			
			$_SESSION['SHOW_WELCOME'] = true;
			$_SESSION['USER_N'] = $userN;
			
			$_SESSION['jjp']['login']['user'] = $_SESSION['USER_N'];
			$_SESSION['jjp']['login']['email'] = Users::GetUserVar(Users::Name2id($_SESSION['jjp']['login']['user']), 'mail');
			$_SESSION['jjp']['login']['name'] = Users::GetUserVar(Users::Name2id($_SESSION['jjp']['login']['user']), 'real_name');
			
			header('Location: ' . WWW . '/quickregister/complete');
			exit;
		}
		header('Location: ' . WWW . '/identity.php?type=add_avatar&errors='. $errors);
		exit;
		break;
	
	case 'useOrCreateAvatar':
	
		if (Users::GetUserVar($_GET['param'], 'mail') == $_SESSION['jjp']['login']['email'] && Users::GetUserVar($_GET['param'], 'password') == $_SESSION['USER_H'])
		{
			$_SESSION['USER_N'] = Users::GetUserVar($_GET['param'], 'username');
		}
		else
		{
			header('Location: ' . WWW . '/identity.php?type=avatars&errors=You can\'t log-in on this account');
			exit;
		}
			
		header('Location: ' . WWW . '/');
		exit;
		
		break;
		
	case 'settings':
	
		$tpl->AddTemplate('identity-settings');
		break;
		
	case 'password':
	
		$tpl->SetParam('recaptcha_html', recaptcha_get_html('6Le-aQoAAAAAABnHRzXH_W-9-vx4B8oSP3_L5tb0'));
		$tpl->AddTemplate('identity-password');
		break;
		
	case 'password_change':
	
		$userP = $_SESSION['USER_H'];
		$userE = $_SESSION['jjp']['login']['email'];
		$userCP = $core->uberHash($_POST['currentPassword']);
		$userNP = $_POST['newPassword'];
		$userNPA = $_POST['retypedNewPassword'];
		
		$resp = recaptcha_check_answer('6Le-aQoAAAAAAKaqhlUT0lAQbjqokPqmj0F1uvQm', $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
		if(!$resp->is_valid)
			$error = 'Captcha code is not valid';
		elseif($userP <> $userCP)
			$error = 'Your password is not equal with your old password';
		elseif($userNP <> $userNPA)
			$error = 'Your new password is not equal with your retype password';
			exit;
		elseif(strlen($userNP) < 6)
			$error = 'Your new password is too short';
		elseif(!isset($error))
		{
			$newPass = $core->uberHash($userNP);
			$result = $db->Query('UPDATE users SET password = "'. $newPass .'" WHERE mail = "'. $userE .'" AND password = "'. $userCP .'"');
			if ($result)
			{
				$_SESSION['USER_H'] = $newPass;
				header('Location: ' . WWW . '/identity.php?type=settings&passwordChanged=true');
			}
			else
				$error = 'Your password is not saved!';
		}
		
		header('Location: ' . WWW . '/identity.php?type=password&errors='. $error);
		exit;
		
		break;
}

// Footer
$tpl->AddTemplate('footer');

// Output the page
echo $tpl;