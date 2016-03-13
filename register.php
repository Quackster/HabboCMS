<?php

require_once 'inc/global.php';
require_once ROOT . '/inc/class.recaptchalib.php';

if(LOGGED_IN)
{
	header('Location: '. WWW .'/me');
	exit;
}

if(isset($_GET['cancel']))
{
	unset($_SESSION['jjp']['register']);
	header('Location: ' . WWW);
	exit;	
}

$tpl = new Tpl();

$tpl->SetParam('errors', '');
if(isset($_GET['errors']))
{
	$error = '<div id="error-messages-container" class="cbb">
            	<div class="rounded" style="background-color: #cb2121;">
					<div id="error-title" class="error">
						'. $_GET['errors'] .'
					</div>
				</div>
			</div>';
	$tpl->setParam('errors', $error);
}

$tpl->addTemplate('head-init');
$tpl->addIncludeSet('register');
$tpl->writeIncludeFiles();
$tpl->addTemplate('head-bottom');

if(!isset($_GET['stap']))
{
	header('Location: '. WWW .'/register.php?stap=1');
	exit;
}

switch($_GET['stap'])
{
	case '1':
	
		if(isset($_SESSION['jjp']['register'][1]))
		{
			header('Location: '. WWW .'/register.php?stap=3');
		}
		$tpl->addTemplate('page-register-1');
		break;
		
	case '2':
	
		$bday_day = $_POST['bean_day'];
		$bday_month = $_POST['bean_month'];
		$bday_year = $_POST['bean_year'];
		$gender = $_POST['bean_gender'];
		
		if(!is_numeric($bday_day) || !is_numeric($bday_month) || !is_numeric($bday_year) || $bday_day <= 0 || $bday_day > 31 || $bday_month <= 0 || $bday_month > 12 || $bday_year < 1900 || $bday_year > 2014)
		{
			$errors = 'Bitte gib ein g�ltiges Geburtsdatum ein!'; /* HABBO_TRANSLATION_ERROR */
		}
		elseif(!empty($gender))
		{
			$_SESSION['jjp']['register'][1]['bday_day'] = $bday_day;
			$_SESSION['jjp']['register'][1]['bday_month'] = $bday_month;
			$_SESSION['jjp']['register'][1]['bday_year'] = $bday_year;
			$_SESSION['jjp']['register'][1]['gender'] = $gender;
			header('Location: '. WWW .'/register.php?stap=3');
			exit;
		}
		else
		{
			$errors = 'Bitte w&auml;hle ein Geschlecht!'; /* HABBO_TRANSLATION_ERROR */
		}

		header('Location: '. WWW .'/register.php?stap=1&errors='. $errors);
		exit;
		break;
	
	case '3':
	
		if(!isset($_SESSION['jjp']['register'][1]))
		{
			header('Location: '. WWW .'/register.php?stap=1&errors=Du musst diesen Schritt tun um fortfahren zu k�nnen!'); /* HABBO_TRANSLATION_ERROR */
		}
		elseif(isset($_SESSION['jjp']['register'][2]))
		{
			header('Location: '. WWW .'/register.php?stap=5');
		}
		
		$tpl->addTemplate('page-register-2');
		break;		
	
	case '4':
	
		$name = $_POST['bean_name'];
		$email = $_POST['bean_email'];
		$pass1 = $_POST['bean_password'];
		$pass2 = $_POST['bean_retypedPassword'];
		
		if(strlen($name) < 1 && strlen($name) > 32)
		{
			$errors = 'Your username must be between 1 and 32 chars long';
		}
		elseif($users->IsNameTaken($name))
		{
			$errors = 'This username is in use. Please choose another name.';
		}
		elseif($users->IsNameBlocked($name))
		{
			$errors = 'This name is not allowed.';
		}
		elseif(!$users->IsValidName($name))
		{
			$errors = 'Your username is invalid or contains invalid characters.';
		}		
		elseif(!$users->IsValidEmail($email))
		{
			$errors = 'Please supply a valid e-mail address.';
		}
		elseif($pass1 <> $pass2 && strlen($pass1) < 6)
		{
			$errors = 'The passwords do not match. Please try again.';
		}
		elseif(isset($_POST['bean_termsOfServiceSelection']))
		{
			$_SESSION['jjp']['register'][2]['name'] = $name;
			$_SESSION['jjp']['register'][2]['email'] = $email;
			$_SESSION['jjp']['register'][2]['pass'] = $pass1;
			
			header('Location: '. WWW .'/register.php?stap=5');	
			exit;	
		}
		else
		{
			$errors = 'Please read and accept the Terms of Service to register.';
		}
		
		header('Location: '. WWW .'/register.php?stap=3&errors='. $errors);
		exit;
		break;
		
	case '5':
	
		if(!isset($_SESSION['jjp']['register'][1]))
		{
			header('Location: '. WWW .'/register.php?stap=1&errors=Je moet eerst deze stap doen voor je verder kan'); /* HABBO_TRANSLATION_ERROR */
		}
		else if (!isset($_SESSION['jjp']['register'][2]))
		{
			header('Location: '. WWW .'/register.php?stap=3&errors=Je moet eerst deze stap doen voor je verder kan'); /* HABBO_TRANSLATION_ERROR */
		}
		
		$tpl->SetParam('recaptcha_html', recaptcha_get_html("6Le-aQoAAAAAABnHRzXH_W-9-vx4B8oSP3_L5tb0"));
		$tpl->AddTemplate('page-register-3');
		break;	
	
	case '6':
	
		$resp = recaptcha_check_answer('6Le-aQoAAAAAAKaqhlUT0lAQbjqokPqmj0F1uvQm', $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
		
		if(!$resp->is_valid)
		{
			$errors = 'The code that you filled in is not right, are you sure you are a human?!';
		}	
		else
		{
			if ($_SESSION['jjp']['register'][1]['gender'] == 'male')
			{
				$look = 'hd-180-1.ch-210-66.lg-270-82.sh-290-91.hr-100-';
				$gender = 'M';
			}
			else
			{
				$look = 'hd-180-1.ch-210-66.lg-270-82.sh-290-91.hr-100-';
				$gender = 'F';
			}
			
			$users->add($_SESSION['jjp']['register'][2]['name'], $core->Hash($_SESSION['jjp']['register'][2]['pass']), $_SESSION['jjp']['register'][2]['email'], 1, $look, $gender);
			
			$_SESSION['SHOW_WELCOME'] = true;
			$_SESSION['USER_N'] = $_SESSION['jjp']['register'][2]['name'];
			$_SESSION['USER_H'] = $core->Hash($_SESSION['jjp']['register'][2]['pass']);
			
			unset($_SESSION['jjp']['register']);
			
			header('Location: '. WWW .'/me.php');
			exit;
		}
		
		header('Location: '. WWW .'/register.php?stap=5&errors='. $errors);
		exit;
		break;
}

echo $tpl;